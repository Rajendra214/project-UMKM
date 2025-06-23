@extends('layouts.app')

@section('title', 'Dashboard - UMKM Prediction')
@section('page-icon', '🏠')
@section('page-title', 'Dashboard Utama')
@section('page-subtitle', 'Ringkasan dan overview sistem prediksi penjualan UMKM')

@section('styles')
<style>
    .dashboard-wrapper {
        padding: 2rem;
        background: transparent;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* PERBAIKAN KONTRAS: Force text colors */
    .dashboard-card h3,
    .dashboard-card h4,
    .dashboard-card h5,
    .dashboard-card p,
    .dashboard-card div {
        color: #ffffff !important;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(41, 128, 185, 0.2)) !important;
        border: 1px solid rgba(52, 152, 219, 0.3);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
    }

    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #3498db !important;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }

    .action-btn {
        background: linear-gradient(135deg, rgba(46, 204, 113, 0.2), rgba(39, 174, 96, 0.2)) !important;
        border: 1px solid rgba(46, 204, 113, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-decoration: none;
        color: #ffffff !important;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .action-btn:hover {
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(46, 204, 113, 0.3);
    }

    .action-icon {
        font-size: 2rem;
    }

    .recent-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .recent-card {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .recent-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #3498db !important;
    }

    .recent-item {
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recent-item:last-child {
        border-bottom: none;
    }

    .recent-name {
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .recent-meta {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7) !important;
    }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .recent-section {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('konten')
<div class="dashboard-wrapper">
    
    <!-- Welcome Section -->
    <div class="dashboard-card">
        <h3 style="color: #3498db !important; margin-bottom: 1rem;">🎉 Selamat Datang di Dashboard UMKM Prediction</h3>
        <p style="color: rgba(255, 255, 255, 0.9) !important; line-height: 1.6;">
            Platform analisis prediksi penjualan yang membantu UMKM dalam merencanakan strategi bisnis berdasarkan data historis dan algoritma machine learning.
        </p>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon">📦</span>
            <div class="stat-number">{{ $totalProduk }}</div>
            <div class="stat-label">Total Produk</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">📊</span>
            <div class="stat-number">{{ $totalPenjualan }}</div>
            <div class="stat-label">Data Penjualan</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">🎯</span>
            <div class="stat-number">{{ $sidebarStats['prediksiAktif'] }}</div>
            <div class="stat-label">Prediksi Aktif</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">⚡</span>
            <div class="stat-number">{{ $sidebarStats['akurasiRata'] }}%</div>
            <div class="stat-label">Akurasi Rata-rata</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-card">
        <h4 style="color: #3498db !important; margin-bottom: 1.5rem;">🚀 Aksi Cepat</h4>
        <div class="quick-actions">
            <a href="{{ route('produk.create') }}" class="action-btn">
                <span class="action-icon">➕</span>
                <span>Tambah Produk</span>
            </a>
            <a href="{{ route('penjualan.create') }}" class="action-btn">
                <span class="action-icon">📈</span>
                <span>Input Penjualan</span>
            </a>
            <a href="{{ route('analisis.index') }}" class="action-btn">
                <span class="action-icon">🔍</span>
                <span>Analisis Prediksi</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="action-btn">
                <span class="action-icon">📋</span>
                <span>Lihat Laporan</span>
            </a>
        </div>
    </div>

    <!-- Recent Data -->
    <div class="recent-section">
        <div class="recent-card">
            <h5 class="recent-title">📦 Produk Terbaru</h5>
            @forelse($produkTerbaru as $produk)
                <div class="recent-item">
                    <div>
                        <div class="recent-name">{{ $produk->nama_produk }}</div>
                        <div class="recent-meta">{{ $produk->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="recent-item">
                    <div class="recent-meta">Belum ada produk</div>
                </div>
            @endforelse
        </div>

        <div class="recent-card">
            <h5 class="recent-title">📊 Penjualan Terbaru</h5>
            @forelse($penjualanTerbaru as $penjualan)
                <div class="recent-item">
                    <div>
                        <div class="recent-name">{{ $penjualan->produk->nama_produk }}</div>
                        <div class="recent-meta">{{ $penjualan->jumlah_penjualan }} unit - {{ $penjualan->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="recent-item">
                    <div class="recent-meta">Belum ada data penjualan</div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chart Summary -->
    @if(isset($chartData) && count($chartData['sales']) > 0)
    <div class="dashboard-card">
        <h4 style="color: #3498db !important; margin-bottom: 1.5rem;">📈 Tren Penjualan 6 Bulan Terakhir</h4>
        <canvas id="summaryChart" width="400" height="150"></canvas>
    </div>
    @endif

</div>
@endsection

@section('scripts')
@if(isset($chartData) && count($chartData['sales']) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('summaryChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['months']) !!},
            datasets: [{
                label: 'Total Penjualan',
                data: {!! json_encode($chartData['sales']) !!},
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 3,
                pointRadius: 6,
                pointBackgroundColor: '#3498db',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        font: {
                            family: 'Inter, sans-serif'
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.6)',
                        font: {
                            family: 'Inter, sans-serif'
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.6)',
                        font: {
                            family: 'Inter, sans-serif'
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection
