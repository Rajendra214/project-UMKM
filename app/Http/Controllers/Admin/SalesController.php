<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\User;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display sales list
     */
    public function index(Request $request)
    {
        $query = Penjualan::with(['user', 'produk']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('produk', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $sales = $query->latest('tanggal')->paginate(20);
        $users = User::where('is_active', true)->get();

        // Statistics
        $stats = [
            'total_sales' => $query->count(),
            'total_revenue' => $query->sum('total_harga'),
            'avg_sale_value' => $query->avg('total_harga'),
            'total_quantity' => $query->sum('jumlah'),
        ];

        return view('admin.sales.index', compact('sales', 'users', 'stats'));
    }

    /**
     * Show sales analytics
     */
    public function analytics()
    {
        // Monthly sales data
        $monthlySales = Penjualan::select(
                DB::raw('MONTH(tanggal) as month'),
                DB::raw('YEAR(tanggal) as year'),
                DB::raw('SUM(jumlah) as total_quantity'),
                DB::raw('SUM(total_harga) as total_revenue'),
                DB::raw('COUNT(*) as total_transactions')
            )
            ->where('tanggal', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Top selling products
        $topProducts = Produk::select('produk.*', 
                DB::raw('SUM(penjualan.jumlah) as total_sold'),
                DB::raw('SUM(penjualan.total_harga) as total_revenue'),
                DB::raw('COUNT(penjualan.id) as total_transactions')
            )
            ->leftJoin('penjualan', 'produk.id', '=', 'penjualan.produk_id')
            ->groupBy('produk.id')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Sales by user
        $userSales = User::select('users.*',
                DB::raw('SUM(penjualan.jumlah) as total_sold'),
                DB::raw('SUM(penjualan.total_harga) as total_revenue'),
                DB::raw('COUNT(penjualan.id) as total_transactions')
            )
            ->leftJoin('penjualan', 'users.id', '=', 'penjualan.user_id')
            ->where('users.is_active', true)
            ->groupBy('users.id')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        return view('admin.sales.analytics', compact('monthlySales', 'topProducts', 'userSales'));
    }

    /**
     * Edit sales data
     */
    public function edit(Penjualan $sale)
    {
        $users = User::where('is_active', true)->get();
        $products = Produk::where('user_id', $sale->user_id)->get();
        
        return view('admin.sales.edit', compact('sale', 'users', 'products'));
    }

    /**
     * Update sales data
     */
    public function update(Request $request, Penjualan $sale)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $sale->update([
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $request->jumlah * $request->harga_satuan,
        ]);

        return redirect()->route('admin.sales.index')
            ->with('success', 'Data penjualan berhasil diperbarui.');
    }

    /**
     * Delete sales data
     */
    public function destroy(Penjualan $sale)
    {
        $sale->delete();
        
        return back()->with('success', 'Data penjualan berhasil dihapus.');
    }
}
