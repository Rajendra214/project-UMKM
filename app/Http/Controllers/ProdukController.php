<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    public function index()
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        $produks = Produk::withCount('penjualan')->paginate(10);
        
        return view('produk.index', compact('produks', 'sidebarStats'));
    }
    
    public function create()
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        return view('produk.create', compact('sidebarStats'));
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_produk' => 'required|string|max:255|unique:produks,nama_produk',
                'deskripsi' => 'nullable|string|max:1000',
                'kategori' => 'nullable|string|max:100',
            ]);
            
            Produk::create($request->all());
            
            return redirect()->route('produk.index')
                ->with('success', 'Produk berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk', 'sidebarStats'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);
            
            $request->validate([
                'nama_produk' => 'required|string|max:255|unique:produks,nama_produk,' . $id,
                'deskripsi' => 'nullable|string|max:1000',
                'kategori' => 'nullable|string|max:100',
            ]);
            
            $produk->update($request->all());
            
            return redirect()->route('produk.index')
                ->with('success', 'Produk berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $nama = $produk->nama_produk;
            
            $produk->penjualan()->delete();
            $produk->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Produk '{$nama}' berhasil dihapus!"
                ]);
            }
            
            return redirect()->route('produk.index')
                ->with('success', "Produk '{$nama}' berhasil dihapus!");
                
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus produk: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
