<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display users list
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by business type
        if ($request->filled('business_type')) {
            $query->where('business_type', $request->business_type);
        }

        $users = $query->withCount(['penjualan', 'produk'])
            ->latest()
            ->paginate(15);

        $businessTypes = User::distinct()->pluck('business_type')->filter();

        return view('admin.users.index', compact('users', 'businessTypes'));
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        $user->load(['penjualan.produk', 'produk']);
        
        $stats = [
            'total_products' => $user->produk->count(),
            'total_sales' => $user->penjualan->count(),
            'total_revenue' => $user->penjualan->sum('total_harga'),
            'avg_sale_value' => $user->penjualan->avg('total_harga'),
        ];

        $recentSales = $user->penjualan()
            ->with('produk')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentSales'));
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Delete related data
        $user->penjualan()->delete();
        $user->produk()->delete();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} dan semua datanya berhasil dihapus.");
    }
}
