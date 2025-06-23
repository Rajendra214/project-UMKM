<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\PenjualanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalisisController extends Controller
{
    public function index()
    {
        try {
            $sidebarStats = app(DashboardController::class)->getSidebarStats();
            
            // Ambil produk yang memiliki data penjualan minimal 2 periode
            $produksWithSales = Produk::whereHas('penjualan', function($query) {
                $query->havingRaw('COUNT(*) >= 2');
            })->withCount('penjualan')
            ->with(['penjualan' => function($query) {
                $query->orderBy('tahun', 'asc')->orderBy('bulan', 'asc');
            }])
            ->get();
            
            return view('analisis.index', compact('produksWithSales', 'sidebarStats'));
            
        } catch (\Exception $e) {
            Log::error('Error in AnalisisController@index: ' . $e->getMessage());
            
            // Fallback jika error
            $sidebarStats = [
                'totalProduk' => 0,
                'totalData' => 0,
                'prediksiAktif' => 0,
                'akurasiRata' => 0
            ];
            
            $produksWithSales = collect([]);
            
            return view('analisis.index', compact('produksWithSales', 'sidebarStats'))
                ->with('error', 'Terjadi kesalahan saat memuat data analisis.');
        }
    }
    
    public function produk($id)
    {
        try {
            $sidebarStats = app(DashboardController::class)->getSidebarStats();
            $produk = Produk::with('penjualan')->findOrFail($id);
            
            // Cek apakah produk memiliki cukup data untuk prediksi
            if ($produk->penjualan->count() < 2) {
                return redirect()->route('analisis.index')
                    ->with('error', 'Produk ini belum memiliki cukup data untuk analisis prediksi (minimal 2 periode).');
            }
            
            // Data penjualan untuk chart
            $penjualanData = $produk->penjualan()
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get();
            
            return view('analisis.produk', compact('produk', 'penjualanData', 'sidebarStats'));
            
        } catch (\Exception $e) {
            Log::error('Error in AnalisisController@produk: ' . $e->getMessage());
            return redirect()->route('analisis.index')
                ->with('error', 'Terjadi kesalahan saat memuat data produk.');
        }
    }
    
    public function generate(Request $request)
    {
        try {
            $request->validate([
                'produk_id' => 'required|exists:produks,id',
                'periode_prediksi' => 'required|integer|min:1|max:12'
            ]);
            
            $produk = Produk::with('penjualan')->findOrFail($request->produk_id);
            
            if ($produk->penjualan->count() < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data penjualan tidak cukup untuk prediksi (minimal 2 periode)'
                ]);
            }
            
            // Generate prediksi menggunakan linear regression sederhana
            $prediksi = $this->generatePrediksi($produk, $request->periode_prediksi);
            
            return response()->json([
                'success' => true,
                'data' => $prediksi
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating prediction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menggenerate prediksi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function apiPrediksi($id)
    {
        try {
            $produk = Produk::with('penjualan')->findOrFail($id);
            
            if ($produk->penjualan->count() < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak cukup untuk prediksi'
                ]);
            }
            
            // Generate prediksi untuk 6 bulan ke depan
            $prediksi = $this->generatePrediksi($produk, 6);
            
            return response()->json([
                'success' => true,
                'data' => $prediksi
            ]);
            
        } catch (\Exception $e) {
            Log::error('API Prediksi Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function generatePrediksi($produk, $periodePrediksi)
    {
        $penjualanData = $produk->penjualan()
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
        
        // Prepare data untuk linear regression
        $x = []; // periode (1, 2, 3, ...)
        $y = []; // jumlah penjualan
        
        foreach ($penjualanData as $index => $penjualan) {
            $x[] = $index + 1;
            $y[] = $penjualan->jumlah_penjualan;
        }
        
        $n = count($x);
        
        // Hitung linear regression: y = mx + b
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }
        
        // Proteksi division by zero
        $denominator = ($n * $sumX2 - $sumX * $sumX);
        if ($denominator == 0) {
            $m = 0;
            $b = $n > 0 ? $sumY / $n : 0;
        } else {
            $m = ($n * $sumXY - $sumX * $sumY) / $denominator;
            $b = ($sumY - $m * $sumX) / $n;
        }
        
        // Generate prediksi
        $prediksiData = [];
        $lastPeriode = $penjualanData->last();
        $currentMonth = $lastPeriode->bulan;
        $currentYear = $lastPeriode->tahun;
        
        for ($i = 1; $i <= $periodePrediksi; $i++) {
            $currentMonth++;
            if ($currentMonth > 12) {
                $currentMonth = 1;
                $currentYear++;
            }
            
            $nextPeriode = $n + $i;
            $prediksiValue = max(0, round($m * $nextPeriode + $b));
            
            $prediksiData[] = [
                'periode' => $i,
                'bulan' => $currentMonth,
                'tahun' => $currentYear,
                'bulan_nama' => $this->getNamaBulan($currentMonth),
                'prediksi' => $prediksiValue,
                'trend' => $m > 0 ? 'naik' : ($m < 0 ? 'turun' : 'stabil')
            ];
        }
        
        // Hitung akurasi menggunakan MAPE
        $akurasi = $this->calculateAccuracy($x, $y, $m, $b);
        
        return [
            'produk' => $produk->nama_produk,
            'data_historis' => $penjualanData->map(function($item) {
                return [
                    'bulan' => $item->bulan,
                    'tahun' => $item->tahun,
                    'bulan_nama' => $this->getNamaBulan($item->bulan),
                    'penjualan' => $item->jumlah_penjualan
                ];
            }),
            'prediksi' => $prediksiData,
            'akurasi' => $akurasi,
            'trend_coefficient' => $m,
            'intercept' => $b,
            'total_data' => $n
        ];
    }
    
    private function calculateAccuracy($x, $y, $m, $b)
    {
        $totalError = 0;
        $n = count($x);
        
        if ($n == 0) return 0;
        
        for ($i = 0; $i < $n; $i++) {
            $predicted = $m * $x[$i] + $b;
            $actual = $y[$i];
            
            if ($actual != 0) {
                $error = abs(($actual - $predicted) / $actual);
                $totalError += $error;
            }
        }
        
        $mape = ($totalError / $n) * 100;
        $accuracy = max(0, 100 - $mape);
        
        return round($accuracy, 2);
    }
    
    private function getNamaBulan($bulan)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $namaBulan[$bulan] ?? 'Unknown';
    }
}
