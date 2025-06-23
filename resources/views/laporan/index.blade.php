@extends('layouts.app')

@section('title', 'Laporan & Grafik - UMKM Prediction')
@section('page-icon', '📈')
@section('page-title', 'Laporan & Grafik')
@section('page-subtitle', 'Visualisasi data dan laporan penjualan komprehensif')

@section('styles')
<style>
    .laporan-wrapper {
        padding: 2rem;
        background: transparent;
    }

    .modern-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.2), rgba(192, 57, 43, 0.2));
        border: 1px solid rgba(231, 76, 60, 0.3);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:nth-child(2) {
        background: linear-gradient(135deg, rgba(46, 204, 113, 0.2), rgba(39, 174, 96, 0.2));
        border-color: rgba(46, 204, 113, 0.3);
    }

    .stat-card:nth-child(3) {
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.2), rgba(41, 128, 185, 0.2));
        border-color: rgba(52, 152, 219, 0.3);
    }

    .stat-card:nth-child(4) {
        background: linear-gradient(135deg, rgba(155, 89, 182, 0.2), rgba(142, 68, 173, 0.2));
        border-color: rgba(155, 89, 182, 0.3);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    .produk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .produk-card {
        background: rgba(52, 152, 219, 0.1);
        border: 1px solid rgba(52, 152, 219, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .produk-card:hover {
        background: rgba(52, 152, 219, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
    }

    .produk-name {
        color: #3498db;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .produk-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .produk-stat {
        text-align: center;
    }

    .produk-stat-value {
        font-weight: 700;
        color: #2ecc71;
        font-size: 1.1rem;
    }

    .produk-stat-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .btn-view-chart {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-view-chart:hover {
        background: linear-gradient(135deg, #2980b9, #3498db);
        transform: translateY(-1px);
        color: white;
    }

    .export-section {
        background: rgba(46, 204, 113, 0.1);
        border: 1px solid rgba(46, 204, 113, 0.3);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
    }

    .btn-export {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 0 0.5rem;
    }

    .btn-export:hover {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        transform: translateY(-1px);
        color: white;
    }

    .trend-chart {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
    }

    /* PERBAIKAN: No data message styling */
    .no-trend-data {
        background: rgba(255, 159, 67, 0.1);
        border: 1px solid rgba(255, 159, 67, 0.3);
        border-radius: 15px;
        padding: 3rem 2rem;
        text-align: center;
        margin-top: 2rem;
    }

    .no-trend-data h5 {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .no-trend-data p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .debug-info {
        background: rgba(52, 152, 219, 0.1);
        border: 1px solid rgba(52, 152, 219, 0.3);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
    }

    @media (max-width: 768px) {
        .laporan-wrapper {
            padding: 1rem;
        }
        
        .summary-stats {
            grid-template-columns: 1fr;
        }
        
        .produk-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('konten')
<div class="laporan-wrapper">
    
    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-card">
            <span class="stat-icon">📊</span>
            <div class="stat-number">{{ number_format($totalPenjualanAllTime) }}</div>
            <div class="stat-label">Total Unit Terjual</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">📈</span>
            <div class="stat-number">{{ number_format($avgPenjualanPerBulan, 0) }}</div>
            <div class="stat-label">Rata-rata per Bulan</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">🏆</span>
            <div class="stat-number">{{ $produksWithSales->count() }}</div>
            <div class="stat-label">Produk Aktif</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">📅</span>
            <div class="stat-number">{{ $produksWithSales->sum('penjualan_count') }}</div>
            <div class="stat-label">Total Periode Data</div>
        </div>
    </div>

    <!-- Best Seller Info -->
    @if($produkTerlaris)
    <div class="modern-card">
        <h4 style="color: #f39c12; margin-bottom: 1rem;">🏆 Produk Terlaris</h4>
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 style="color: #3498db; margin-bottom: 0.5rem;">{{ $produkTerlaris->nama_produk }}</h5>
                <p style="color: rgba(255, 255, 255, 0.8); margin: 0;">
                    Total penjualan: <strong style="color: #2ecc71;">{{ number_format($produkTerlaris->penjualan_sum_jumlah_penjualan) }} unit</strong>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('laporan.grafik', $produkTerlaris->id) }}" class="btn btn-warning">
                    📊 Lihat Detail Grafik
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- PERBAIKAN: Monthly Trend Chart dengan debugging -->
    <div class="modern-card">
        <h4 style="color: #9b59b6; margin-bottom: 1.5rem;">📈 Tren Penjualan Bulanan</h4>
        
        <!-- Penjelasan -->
        <div style="background: rgba(155, 89, 182, 0.1); border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <h6 style="color: #9b59b6; margin-bottom: 0.8rem;">💡 Penjelasan Tren Bulanan</h6>
            <p style="color: rgba(255, 255, 255, 0.8); margin: 0; line-height: 1.5;">
                Grafik ini menampilkan <strong>total penjualan gabungan semua produk per bulan</strong> untuk melihat 
                pola naik-turun bisnis secara keseluruhan. Data diambil dari periode yang tersedia dalam database.
            </p>
        </div>
        
        @if($trendBulanan->count() > 0)
            <!-- Data Summary -->
            <div style="margin-bottom: 1.5rem;">
                <small style="color: rgba(255, 255, 255, 0.7);">
                    Menampilkan {{ $trendBulanan->count() }} periode data | 
                    Total: <strong style="color: #2ecc71;">{{ number_format($trendBulanan->sum('total')) }} unit</strong> | 
                    Rata-rata: <strong style="color: #4facfe;">{{ number_format($trendBulanan->avg('total'), 0) }} unit/bulan</strong>
                </small>
            </div>
            
            <div class="trend-chart">
                <canvas id="trendChart" height="100"></canvas>
            </div>
            
            <!-- Data breakdown -->
            <div style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                @foreach($trendBulanan as $trend)
                <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 8px; text-align: center;">
                    <div style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.7);">{{ $trend['periode'] }}</div>
                    <div style="font-size: 1.2rem; font-weight: 700; color: #9b59b6;">{{ number_format($trend['total']) }}</div>
                    <div style="font-size: 0.7rem; color: rgba(255, 255, 255, 0.6);">unit</div>
                </div>
                @endforeach
            </div>
        @else
            <div class="no-trend-data">
                <span style="font-size: 4rem; display: block; margin-bottom: 1rem;">📈</span>
                <h5>Belum Ada Data untuk Tren Bulanan</h5>
                <p>
                    Tren penjualan bulanan akan muncul setelah Anda menambahkan data penjualan. 
                    Grafik ini akan menampilkan <strong>total penjualan gabungan semua produk</strong> per bulan 
                    untuk membantu analisis pola bisnis.
                </p>
                
                <!-- Debug info -->
                <div class="debug-info">
                    <strong>Debug Info:</strong><br>
                    Total Produk: {{ $produksWithSales->count() }}<br>
                    Total Data Penjualan: {{ \App\Models\PenjualanProduk::count() }}<br>
                    Tren Data Count: {{ $trendBulanan->count() }}
                    
                    @if(\App\Models\PenjualanProduk::count() > 0)
                        <br><br>
                        <strong>Sample Data Tersedia:</strong><br>
                        @php
                            $sampleData = \App\Models\PenjualanProduk::with('produk')->latest()->take(3)->get();
                        @endphp
                        @foreach($sampleData as $sample)
                            • {{ $sample->produk->nama_produk }}: {{ $sample->jumlah_penjualan }} unit ({{ $sample->bulan }}/{{ $sample->tahun }})<br>
                        @endforeach
                    @endif
                </div>
                
                <div style="margin-top: 2rem;">
                    <a href="{{ route('penjualan.create') }}" class="btn btn-success me-2">
                        📊 Tambah Data Penjualan
                    </a>
                    <a href="{{ route('laporan.index') }}?debug=1" class="btn btn-secondary">
                        🔍 Debug Data
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Products Chart List -->
    <div class="modern-card">
        <h4 style="color: #3498db; margin-bottom: 1.5rem;">📊 Grafik per Produk</h4>
        
        @if($produksWithSales->count() > 0)
            <div class="produk-grid">
                @foreach($produksWithSales as $produk)
                    <div class="produk-card" onclick="window.location.href='{{ route('laporan.grafik', $produk->id) }}'">
                        <div class="produk-name">{{ $produk->nama_produk }}</div>
                        
                        <div class="produk-stats">
                            <div class="produk-stat">
                                <div class="produk-stat-value">{{ $produk->penjualan_count }}</div>
                                <div class="produk-stat-label">Periode</div>
                            </div>
                            <div class="produk-stat">
                                <div class="produk-stat-value">{{ number_format($produk->penjualan->sum('jumlah_penjualan')) }}</div>
                                <div class="produk-stat-label">Total Terjual</div>
                            </div>
                            <div class="produk-stat">
                                <div class="produk-stat-value">{{ number_format($produk->penjualan->avg('jumlah_penjualan'), 0) }}</div>
                                <div class="produk-stat-label">Rata-rata</div>
                            </div>
                        </div>
                        
                        <button class="btn-view-chart">
                            📈 Lihat Grafik Detail
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <span style="font-size: 4rem; display: block; margin-bottom: 1rem;">📊</span>
                <h5 style="color: rgba(255, 255, 255, 0.8);">Belum ada data untuk ditampilkan</h5>
                <p style="color: rgba(255, 255, 255, 0.6);">
                    Tambahkan produk dan data penjualan untuk melihat grafik dan laporan.
                </p>
                <div style="margin-top: 2rem;">
                    <a href="{{ route('produk.create') }}" class="btn btn-primary me-2">
                        📦 Tambah Produk
                    </a>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-success">
                        📊 Input Penjualan
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Export Section -->
    @if($produksWithSales->count() > 0)
    <div class="export-section">
        <h5 style="color: #2ecc71; margin-bottom: 1.5rem;">📥 Export Laporan</h5>
        <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 2rem;">
            Download laporan penjualan dalam format yang Anda inginkan
        </p>
        <div>
            <a href="{{ route('laporan.export', 'csv') }}" class="btn-export">
                📄 Export CSV
            </a>
            <a href="{{ route('laporan.export', 'pdf') }}" class="btn-export">
                📋 Export PDF
            </a>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
@if($trendBulanan->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // PERBAIKAN: Trend Chart dengan error handling
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendData = @json($trendBulanan);
    
    console.log('Trend Data:', trendData); // Debug
    
    if (!trendData || trendData.length === 0) {
        console.error('No trend data available');
        return;
    }
    
    try {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: trendData.map(item => item.periode),
                datasets: [{
                    label: 'Total Penjualan (unit)',
                    data: trendData.map(item => item.total),
                    backgroundColor: 'rgba(155, 89, 182, 0.8)',
                    borderColor: '#9b59b6',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
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
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        callbacks: {
                            title: function(context) {
                                return 'Periode: ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Total Penjualan: ' + context.parsed.y.toLocaleString() + ' unit';
                            },
                            afterLabel: function(context) {
                                const total = trendData.reduce((sum, item) => sum + item.total, 0);
                                const percentage = ((context.parsed.y / total) * 100).toFixed(1);
                                return 'Kontribusi: ' + percentage + '% dari total';
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
                            },
                            callback: function(value) {
                                return value.toLocaleString() + ' unit';
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
        
        console.log('Trend chart created successfully');
        
    } catch (error) {
        console.error('Error creating trend chart:', error);
    }
});
</script>
@endif

<!-- Debug script -->
@if(request()->get('debug'))
<script>
// Debug mode - fetch trend data info
fetch('/debug-trend-data')
    .then(response => response.json())
    .then(data => {
        console.log('Debug Trend Data:', data);
        alert('Debug info logged to console. Check browser developer tools.');
    })
    .catch(error => {
        console.error('Debug fetch error:', error);
    });
</script>
@endif
@endsection
