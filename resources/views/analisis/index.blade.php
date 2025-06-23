@extends('layouts.app')

@section('title', 'Analisis Prediksi - UMKM Prediction')
@section('page-icon', '🎯')
@section('page-title', 'Analisis Prediksi')
@section('page-subtitle', 'Analisis dan prediksi penjualan berdasarkan data historis')

@section('styles')
<style>
    /* PERBAIKAN: Pastikan background ter-apply */
    body {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%) !important;
        min-height: 100vh !important;
    }

    .analisis-wrapper {
        padding: 2rem;
        background: transparent;
        min-height: calc(100vh - 200px);
    }

    .modern-card {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .produk-card {
        background: rgba(155, 89, 182, 0.1) !important;
        border: 1px solid rgba(155, 89, 182, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .produk-card:hover {
        background: rgba(155, 89, 182, 0.2) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(155, 89, 182, 0.3);
    }

    .produk-name {
        color: #9b59b6 !important;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .produk-stats {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.1) !important;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-align: center;
        flex: 1;
    }

    .stat-value {
        font-weight: 700;
        font-size: 1.1rem;
        color: #3498db !important;
    }

    .stat-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7) !important;
    }

    .btn-analyze {
        background: linear-gradient(135deg, #9b59b6, #8e44ad) !important;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        color: white !important;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 1rem;
    }

    .btn-analyze:hover {
        background: linear-gradient(135deg, #8e44ad, #9b59b6) !important;
        transform: translateY(-1px);
        color: white !important;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
    }

    .info-card {
        background: rgba(52, 152, 219, 0.1) !important;
        border: 1px solid rgba(52, 152, 219, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Perbaikan untuk text color */
    .analisis-wrapper h4,
    .analisis-wrapper h5,
    .analisis-wrapper p,
    .analisis-wrapper div {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .analisis-wrapper .text-muted {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    @media (max-width: 768px) {
        .analisis-wrapper {
            padding: 1rem;
        }
        
        .produk-stats {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('konten')
<div class="analisis-wrapper">
    
    @if(session('success'))
        <div class="alert alert-success">
            <strong>✅ Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>❌ Error!</strong> {{ session('error') }}
        </div>
    @endif

    <!-- Info Section -->
    <div class="info-card">
        <h5 style="color: #3498db !important; margin-bottom: 1rem;">📊 Tentang Analisis Prediksi</h5>
        <p style="color: rgba(255, 255, 255, 0.8) !important; line-height: 1.6; margin: 0;">
            Sistem ini menggunakan algoritma <strong>Linear Regression</strong> untuk memprediksi tren penjualan berdasarkan data historis. 
            Produk harus memiliki minimal <strong>2 periode data penjualan</strong> untuk dapat dianalisis.
        </p>
    </div>

    <!-- Products for Analysis -->
    <div class="modern-card">
        <h4 style="color: #9b59b6 !important; margin-bottom: 1.5rem;">🎯 Pilih Produk untuk Analisis</h4>
        
        @if($produksWithSales->count() > 0)
            <div class="row">
                @foreach($produksWithSales as $produk)
                    <div class="col-md-6 col-lg-4">
                        <div class="produk-card" onclick="window.location.href='{{ route('analisis.produk', $produk->id) }}'">
                            <div class="produk-name">{{ $produk->nama_produk }}</div>
                            <div style="color: rgba(255, 255, 255, 0.7) !important; margin-bottom: 1rem;">
                                {{ Str::limit($produk->deskripsi ?? 'Tidak ada deskripsi', 60) }}
                            </div>
                            
                            <div class="produk-stats">
                                <div class="stat-item">
                                    <div class="stat-value">{{ $produk->penjualan_count }}</div>
                                    <div class="stat-label">Data Periode</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ $produk->penjualan->sum('jumlah_penjualan') }}</div>
                                    <div class="stat-label">Total Terjual</div>
                                </div>
                            </div>
                            
                            <button class="btn-analyze">
                                🔍 Analisis Produk Ini
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <span class="empty-icon">🎯</span>
                <h5 style="color: rgba(255, 255, 255, 0.8) !important;">Belum ada produk yang dapat dianalisis</h5>
                <p style="color: rgba(255, 255, 255, 0.6) !important;">
                    Untuk melakukan analisis prediksi, Anda perlu:
                </p>
                <ul style="text-align: left; display: inline-block; margin: 1rem 0; color: rgba(255, 255, 255, 0.6) !important;">
                    <li>Menambahkan minimal 1 produk</li>
                    <li>Menginput minimal 2 periode data penjualan untuk setiap produk</li>
                </ul>
                <div style="margin-top: 2rem;">
                    <a href="{{ route('produk.create') }}" class="btn btn-primary me-2">
                        📦 Tambah Produk
                    </a>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-success">
                        📊 Input Data Penjualan
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    @if($produksWithSales->count() > 0)
    <div class="modern-card">
        <h5 style="color: #3498db !important; margin-bottom: 1.5rem;">📈 Ringkasan Analisis</h5>
        <div class="row">
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value">{{ $produksWithSales->count() }}</div>
                    <div class="stat-label">Produk Siap Analisis</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value">{{ $produksWithSales->sum('penjualan_count') }}</div>
                    <div class="stat-label">Total Data Periode</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value">{{ number_format($produksWithSales->sum(function($p) { return $p->penjualan->sum('jumlah_penjualan'); })) }}</div>
                    <div class="stat-label">Total Unit Terjual</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value">{{ round($produksWithSales->avg('penjualan_count'), 1) }}</div>
                    <div class="stat-label">Rata-rata Periode/Produk</div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects
    const produkCards = document.querySelectorAll('.produk-card');
    
    produkCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endsection
