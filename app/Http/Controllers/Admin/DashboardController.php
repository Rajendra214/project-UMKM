<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Prediksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Statistik utama
        $stats = [
            'total_users' => User::where('is_active', true)->count(),
            'total_products' => Produk::count(),
            'total_sales' => Penjualan::count(),
            'total_predictions' => Prediksi::count(),
            'active_users_today' => User::whereDate('last_login_at', today())->count(),
            'sales_today' => Penjualan::whereDate('created_at', today())->count(),
            'revenue_today' => Penjualan::whereDate('tanggal', today())->sum('total_harga'),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        // Data penjualan per bulan (6 bulan terakhir)
        $salesChart = Penjualan::select(
                DB::raw('MONTH(tanggal) as month'),
                DB::raw('YEAR(tanggal) as year'),
                DB::raw('SUM(jumlah) as total_quantity'),
                DB::raw('SUM(total_harga) as total_revenue')
            )
            ->where('tanggal', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Top 5 produk terlaris
        $topProducts = Produk::select('produk.*', DB::raw('SUM(penjualan.jumlah) as total_sold'))
            ->leftJoin('penjualan', 'produk.id', '=', 'penjualan.produk_id')
            ->groupBy('produk.id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // User terbaru
        $recentUsers = User::latest()->limit(5)->get();

        // Penjualan terbaru
        $recentSales = Penjualan::with(['produk', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        // Data untuk grafik prediksi
        $predictionData = $this->getPredictionChartData();

        return view('admin.dashboard', compact(
            'stats', 
            'salesChart', 
            'topProducts', 
            'recentUsers', 
            'recentSales',
            'predictionData'
        ));
    }

    /**
     * Get prediction chart data
     */
    private function getPredictionChartData()
    {
        $predictions = Prediksi::with('produk')
            ->where('created_at', '>=', now()->subMonths(3))
            ->get()
            ->groupBy(function($item) {
                return $item->produk->nama ?? 'Unknown';
            });

        $chartData = [];
        foreach ($predictions as $productName => $productPredictions) {
            $chartData[] = [
                'name' => $productName,
                'data' => $productPredictions->pluck('prediksi_bulan_depan')->toArray(),
                'dates' => $productPredictions->pluck('created_at')->map(function($date) {
                    return $date->format('M Y');
                })->toArray()
            ];
        }

        return $chartData;
    }

    /**
     * API endpoint for dashboard stats
     */
    public function apiStats()
    {
        $stats = [
            'users_online' => User::where('last_login_at', '>=', now()->subMinutes(5))->count(),
            'sales_today' => Penjualan::whereDate('created_at', today())->count(),
            'revenue_today' => number_format(Penjualan::whereDate('tanggal', today())->sum('total_harga'), 0, ',', '.'),
            'predictions_generated' => Prediksi::whereDate('created_at', today())->count(),
        ];

        return response()->json($stats);
    }
}
