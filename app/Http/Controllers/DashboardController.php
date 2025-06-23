<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\PenjualanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $sidebarStats = $this->getSidebarStats();
        
        // Data untuk dashboard utama
        $totalProduk = Produk::count();
        $totalPenjualan = PenjualanProduk::count();
        $produkTerbaru = Produk::latest()->take(5)->get();
        $penjualanTerbaru = PenjualanProduk::with('produk')->latest()->take(5)->get();
        
        // Data untuk chart ringkasan
        $chartData = $this->getChartSummary();
        
        return view('dashboard', compact(
            'sidebarStats',
            'totalProduk', 
            'totalPenjualan',
            'produkTerbaru',
            'penjualanTerbaru',
            'chartData'
        ));
    }
    
    public function getSidebarStats()
    {
        try {
            $totalProduk = Produk::count();
            $totalData = PenjualanProduk::count();
            $prediksiAktif = Produk::whereHas('penjualan', function($query) {
                $query->havingRaw('COUNT(*) >= 2');
            })->count();
            
            // Hitung akurasi rata-rata
            $akurasiRata = $this->calculateAverageAccuracy();
            
            return [
                'totalProduk' => $totalProduk,
                'totalData' => $totalData,
                'prediksiAktif' => $prediksiAktif,
                'akurasiRata' => $akurasiRata
            ];
        } catch (\Exception $e) {
            Log::error('Error getting sidebar stats: ' . $e->getMessage());
            return [
                'totalProduk' => 0,
                'totalData' => 0,
                'prediksiAktif' => 0,
                'akurasiRata' => 0
            ];
        }
    }
    
    public function apiSidebarStats()
    {
        return response()->json($this->getSidebarStats());
    }
    
    private function calculateAverageAccuracy()
    {
        // Logic untuk hitung akurasi rata-rata
        return 85; // Placeholder
    }
    
    private function getChartSummary()
    {
        // Data untuk chart ringkasan di dashboard
        $months = [];
        $sales = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthlySales = PenjualanProduk::where('bulan', $date->month)
                ->where('tahun', $date->year)
                ->sum('jumlah_penjualan');
            $sales[] = $monthlySales;
        }
        
        return [
            'months' => $months,
            'sales' => $sales
        ];
    }
}
