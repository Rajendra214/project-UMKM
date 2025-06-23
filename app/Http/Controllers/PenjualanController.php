<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\PenjualanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        
        // PERBAIKAN: Handle filter parameters
        $query = PenjualanProduk::with('produk');
        
        // Filter by produk
        if ($request->filled('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }
        
        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        
        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        
        // Order by latest
        $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc');
        
        $penjualan = $query->paginate(15);
        
        // Keep filter parameters in pagination links
        $penjualan->appends($request->query());
        
        return view('penjualan.index', compact('penjualan', 'sidebarStats'));
    }
    
    public function create()
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        $produks = Produk::all();
        
        return view('penjualan.create', compact('produks', 'sidebarStats'));
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'produk_id' => 'required|exists:produks,id',
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2000|max:2100',
                'jumlah_penjualan' => 'required|integer|min:0',
            ]);
            
            // Cek duplikasi
            $existing = PenjualanProduk::where('produk_id', $request->produk_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->first();
                
            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Data penjualan untuk periode tersebut sudah ada!')
                    ->withInput();
            }
            
            PenjualanProduk::create($request->all());
            
            return redirect()->route('penjualan.index')
                ->with('success', 'Data penjualan berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            Log::error('Error creating sales data: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data penjualan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function edit($id)
    {
        $sidebarStats = app(DashboardController::class)->getSidebarStats();
        $penjualan = PenjualanProduk::with('produk')->findOrFail($id);
        $produks = Produk::all();
        
        return view('penjualan.edit', compact('penjualan', 'produks', 'sidebarStats'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $penjualan = PenjualanProduk::findOrFail($id);
            
            $request->validate([
                'produk_id' => 'required|exists:produks,id',
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2000|max:2100',
                'jumlah_penjualan' => 'required|integer|min:0',
            ]);
            
            // Cek duplikasi (kecuali data yang sedang diedit)
            $existing = PenjualanProduk::where('produk_id', $request->produk_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->where('id', '!=', $id)
                ->first();
                
            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Data penjualan untuk periode tersebut sudah ada!')
                    ->withInput();
            }
            
            $penjualan->update($request->all());
            
            return redirect()->route('penjualan.index')
                ->with('success', 'Data penjualan berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Error updating sales data: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data penjualan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $penjualan = PenjualanProduk::with('produk')->findOrFail($id);
            $info = $penjualan->produk->nama_produk . ' - ' . 
                    $this->getNamaBulan($penjualan->bulan) . ' ' . $penjualan->tahun;
            
            $penjualan->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data penjualan {$info} berhasil dihapus!"
                ]);
            }
            
            return redirect()->route('penjualan.index')
                ->with('success', "Data penjualan {$info} berhasil dihapus!");
                
        } catch (\Exception $e) {
            Log::error('Error deleting sales data: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data penjualan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus data penjualan: ' . $e->getMessage());
        }
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
