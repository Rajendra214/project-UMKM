<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\PenjualanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        
        // Ambil semua produk dengan data penjualan
        $produksWithSales = Produk::whereHas('penjualan')
            ->withCount('penjualan')
            ->with(['penjualan' => function($query) {
                $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');
            }])
            ->get();
        
        // Summary statistics
        $totalPenjualanAllTime = PenjualanProduk::sum('jumlah_penjualan');
        $avgPenjualanPerBulan = PenjualanProduk::avg('jumlah_penjualan');
        $produkTerlaris = $this->getProdukTerlaris();
        
        // PERBAIKAN: Tren bulanan dengan debugging
        $trendBulanan = $this->getTrendBulanan();
        
        // Debug info
        Log::info('Laporan Index Data:', [
            'total_produk_with_sales' => $produksWithSales->count(),
            'total_penjualan_records' => PenjualanProduk::count(),
            'trend_bulanan_count' => $trendBulanan->count(),
            'trend_data' => $trendBulanan->toArray()
        ]);
        
        return view('laporan.index', compact(
            'produksWithSales', 
            'sidebarStats',
            'totalPenjualanAllTime',
            'avgPenjualanPerBulan',
            'produkTerlaris',
            'trendBulanan'
        ));
    }
    
    public function grafik($id)
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        $produk = Produk::with('penjualan')->findOrFail($id);
        
        if ($produk->penjualan->count() == 0) {
            return redirect()->route('laporan.index')
                ->with('error', 'Produk ini belum memiliki data penjualan untuk ditampilkan dalam grafik.');
        }
        
        // Data untuk chart
        $chartData = $this->prepareChartData($produk);
        
        return view('laporan.grafik', compact('produk', 'chartData', 'sidebarStats'));
    }
    
    public function apiChartData($id)
    {
        try {
            $produk = Produk::with('penjualan')->findOrFail($id);
            $chartData = $this->prepareChartData($produk);
            
            return response()->json([
                'success' => true,
                'data' => $chartData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chart Data API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function export($type)
    {
        try {
            switch ($type) {
                case 'csv':
                    return $this->exportCSV();
                case 'pdf':
                    return $this->exportPDF();
                default:
                    return redirect()->back()->with('error', 'Format export tidak valid');
            }
        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    private function prepareChartData($produk)
    {
        $penjualanData = $produk->penjualan()
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
        
        $labels = [];
        $data = [];
        $colors = [];
        
        foreach ($penjualanData as $penjualan) {
            $labels[] = $this->getNamaBulan($penjualan->bulan) . ' ' . $penjualan->tahun;
            $data[] = $penjualan->jumlah_penjualan;
            $colors[] = $this->generateColor($penjualan->jumlah_penjualan);
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'produk_nama' => $produk->nama_produk,
            'total_periode' => count($data),
            'max_penjualan' => count($data) > 0 ? max($data) : 0,
            'min_penjualan' => count($data) > 0 ? min($data) : 0,
            'avg_penjualan' => count($data) > 0 ? round(array_sum($data) / count($data), 2) : 0
        ];
    }
    
    private function getProdukTerlaris()
    {
        return Produk::withSum('penjualan', 'jumlah_penjualan')
            ->orderBy('penjualan_sum_jumlah_penjualan', 'desc')
            ->first();
    }
    
    private function getTrendBulanan()
    {
        try {
            // PERBAIKAN: Ambil 12 bulan terakhir dengan lebih detail
            Log::info('Starting getTrendBulanan...');
            
            // Cek total data penjualan
            $totalRecords = PenjualanProduk::count();
            Log::info("Total PenjualanProduk records: {$totalRecords}");
            
            if ($totalRecords == 0) {
                Log::info('No sales data found, returning empty collection');
                return collect([]);
            }
            
            // Ambil data dengan grouping yang benar
            $rawData = PenjualanProduk::selectRaw('
                    tahun,
                    bulan,
                    SUM(jumlah_penjualan) as total,
                    COUNT(*) as jumlah_transaksi
                ')
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get();
            
            Log::info('Raw trend data:', $rawData->toArray());
            
            // Jika tidak ada data, return empty
            if ($rawData->isEmpty()) {
                Log::info('No grouped data found');
                return collect([]);
            }
            
            // Ambil 12 data terakhir dan reverse untuk urutan terbaru
            $limitedData = $rawData->take(-12)->reverse();
            
            // Format data untuk chart
            $formattedData = $limitedData->map(function($item) {
                return [
                    'periode' => $this->getNamaBulan($item->bulan) . ' ' . $item->tahun,
                    'bulan' => $item->bulan,
                    'tahun' => $item->tahun,
                    'total' => (int) $item->total,
                    'jumlah_transaksi' => (int) $item->jumlah_transaksi
                ];
            });
            
            Log::info('Formatted trend data:', $formattedData->toArray());
            
            return $formattedData;
            
        } catch (\Exception $e) {
            Log::error('Error in getTrendBulanan: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return empty collection on error
            return collect([]);
        }
    }
    
    /**
     * Debug method untuk troubleshoot trend data
     */
    public function debugTrendData()
    {
        try {
            $totalRecords = PenjualanProduk::count();
            $sampleData = PenjualanProduk::with('produk')
                ->latest()
                ->take(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'produk' => $item->produk->nama_produk,
                        'bulan' => $item->bulan,
                        'tahun' => $item->tahun,
                        'jumlah_penjualan' => $item->jumlah_penjualan,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s')
                    ];
                });
            
            $groupedData = PenjualanProduk::selectRaw('
                    tahun,
                    bulan,
                    SUM(jumlah_penjualan) as total,
                    COUNT(*) as jumlah_transaksi
                ')
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->get();
            
            $trendData = $this->getTrendBulanan();
            
            return response()->json([
                'total_records' => $totalRecords,
                'sample_data' => $sampleData,
                'grouped_data' => $groupedData,
                'trend_data' => $trendData,
                'current_time' => Carbon::now()->format('Y-m-d H:i:s'),
                'timezone' => config('app.timezone')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    private function exportCSV()
    {
        $filename = 'laporan_penjualan_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['Produk', 'Bulan', 'Tahun', 'Jumlah Penjualan', 'Tanggal Input']);
            
            // Data
            $penjualan = PenjualanProduk::with('produk')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->get();
            
            foreach ($penjualan as $item) {
                fputcsv($file, [
                    $item->produk->nama_produk,
                    $this->getNamaBulan($item->bulan),
                    $item->tahun,
                    $item->jumlah_penjualan,
                    $item->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportPDF()
    {
        // Implementasi export PDF bisa menggunakan library seperti DomPDF
        // Untuk sementara return CSV
        return $this->exportCSV();
    }
    
    private function generateColor($value)
    {
        // Generate warna berdasarkan nilai penjualan
        $max = 1000; // Asumsi nilai maksimum
        $intensity = min($value / $max, 1);
        
        $red = round(255 * (1 - $intensity));
        $green = round(255 * $intensity);
        $blue = 100;
        
        return "rgba($red, $green, $blue, 0.8)";
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
