@extends('layouts.app')

@section('title', 'Grafik ' . $produk->nama_produk . ' - UMKM Prediction')
@section('page-icon', '📊')
@section('page-title', 'Grafik Penjualan: ' . $produk->nama_produk)
@section('page-subtitle', 'Visualisasi detail data penjualan produk')

@section('styles')
<style>
    /* PERBAIKAN: Pastikan background ter-apply */
    body {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%) !important;
        min-height: 100vh !important;
    }

    .grafik-wrapper {
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

    .chart-container {
        position: relative;
        height: 400px;
        margin: 2rem 0;
        background: rgba(255, 255, 255, 0.05) !important;
        border-radius: 12px;
        padding: 2rem;
    }

    .chart-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .stat-item {
        background: rgba(52, 152, 219, 0.1) !important;
        border: 1px solid rgba(52, 152, 219, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #3498db !important;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 500;
    }

    .chart-controls {
        background: rgba(155, 89, 182, 0.1) !important;
        border: 1px solid rgba(155, 89, 182, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .btn-chart-type {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        color: rgba(255, 255, 255, 0.8) !important;
        margin: 0 0.5rem 0.5rem 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-chart-type.active {
        background: #9b59b6 !important;
        border-color: #9b59b6;
        color: white !important;
    }

    .btn-chart-type:hover {
        background: rgba(155, 89, 182, 0.3) !important;
        border-color: #9b59b6;
        color: white !important;
    }

    /* Perbaikan untuk text color */
    .grafik-wrapper h4,
    .grafik-wrapper h5,
    .grafik-wrapper h6,
    .grafik-wrapper p,
    .grafik-wrapper td,
    .grafik-wrapper th {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .table {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .table thead th {
        background: rgba(243, 156, 18, 0.2) !important;
        border: none;
        padding: 1rem;
    }

    .table tbody td {
        border: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1rem;
    }

    @media (max-width: 768px) {
        .grafik-wrapper {
            padding: 1rem;
        }
        
        .chart-container {
            height: 300px;
            padding: 1rem;
        }
        
        .chart-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('konten')
<div class="grafik-wrapper">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" style="margin-bottom: 2rem;">
        <ol class="breadcrumb" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" style="color: rgba(255, 255, 255, 0.7);">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('laporan.index') }}" style="color: rgba(255, 255, 255, 0.7);">Laporan & Grafik</a>
            </li>
            <li class="breadcrumb-item active" style="color: rgba(255, 255, 255, 0.9);">{{ $produk->nama_produk }}</li>
        </ol>
    </nav>

    <!-- Product Info -->
    <div class="modern-card">
        <div class="row">
            <div class="col-md-8">
                <h4 style="color: #3498db !important; margin-bottom: 1rem;">📦 {{ $produk->nama_produk }}</h4>
                @if($produk->deskripsi)
                <p style="color: rgba(255, 255, 255, 0.8) !important; margin-bottom: 0.5rem;">
                    {{ $produk->deskripsi }}
                </p>
                @endif
                @if($produk->kategori)
                <span class="badge" style="background: rgba(52, 152, 219, 0.3); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                    {{ $produk->kategori }}
                </span>
                @endif
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('analisis.produk', $produk->id) }}" class="btn btn-primary">
                    🎯 Analisis Prediksi
                </a>
            </div>
        </div>
    </div>

    <!-- Chart Statistics -->
    <div class="chart-stats">
        <div class="stat-item">
            <div class="stat-value">{{ $chartData['total_periode'] }}</div>
            <div class="stat-label">Total Periode</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($chartData['max_penjualan']) }}</div>
            <div class="stat-label">Penjualan Tertinggi</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($chartData['min_penjualan']) }}</div>
            <div class="stat-label">Penjualan Terendah</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($chartData['avg_penjualan']) }}</div>
            <div class="stat-label">Rata-rata Penjualan</div>
        </div>
    </div>

    <!-- Chart Controls -->
    <div class="chart-controls">
        <h6 style="color: #9b59b6 !important; margin-bottom: 1rem;">📊 Tipe Grafik</h6>
        <button class="btn-chart-type active" data-type="line">📈 Line Chart</button>
        <button class="btn-chart-type" data-type="bar">📊 Bar Chart</button>
        <button class="btn-chart-type" data-type="area">📈 Area Chart</button>
        <button class="btn-chart-type" data-type="pie">🥧 Pie Chart</button>
    </div>

    <!-- Main Chart -->
    <div class="modern-card">
        <h4 style="color: #2ecc71 !important; margin-bottom: 1.5rem;">📈 Grafik Penjualan {{ $produk->nama_produk }}</h4>
        <div class="chart-container">
            <canvas id="mainChart"></canvas>
        </div>
    </div>

    <!-- Data Table -->
    <div class="modern-card">
        <h4 style="color: #f39c12 !important; margin-bottom: 1.5rem;">📋 Detail Data Penjualan</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Jumlah Penjualan</th>
                        <th>Persentase dari Total</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chartData['labels'] as $index => $label)
                        @php
                            $current = $chartData['data'][$index];
                            $previous = $index > 0 ? $chartData['data'][$index - 1] : null;
                            $percentage = ($current / array_sum($chartData['data'])) * 100;
                            
                            if ($previous) {
                                $trend = $current > $previous ? 'naik' : ($current < $previous ? 'turun' : 'stabil');
                                $trendIcon = $current > $previous ? '📈' : ($current < $previous ? '📉' : '➡️');
                                $trendColor = $current > $previous ? '#2ecc71' : ($current < $previous ? '#e74c3c' : '#95a5a6');
                            } else {
                                $trend = 'baseline';
                                $trendIcon = '⭐';
                                $trendColor = '#3498db';
                            }
                        @endphp
                        <tr>
                            <td>
                                <strong style="color: #f39c12 !important;">{{ $label }}</strong>
                            </td>
                            <td>
                                <strong style="color: #2ecc71 !important; font-size: 1.1rem;">{{ number_format($current) }} unit</strong>
                            </td>
                            <td>
                                <span style="color: #3498db !important;">{{ number_format($percentage, 1) }}%</span>
                            </td>
                            <td>
                                <span style="color: {{ $trendColor }} !important;">
                                    {{ $trendIcon }} {{ ucfirst($trend) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('mainChart').getContext('2d');
    const chartData = @json($chartData);
    
    let currentChart;
    
    function createChart(type) {
        // Destroy existing chart
        if (currentChart) {
            currentChart.destroy();
        }
        
        // PERBAIKAN: Konfigurasi chart yang lebih robust
        let config = {
            type: type,
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Penjualan (unit)',
                    data: chartData.data,
                    borderColor: '#3498db',
                    backgroundColor: getBackgroundColor(type),
                    borderWidth: 3,
                    pointRadius: (type === 'line' || type === 'area') ? 6 : 0,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: type === 'area',
                    tension: (type === 'line' || type === 'area') ? 0.4 : 0,
                    borderRadius: type === 'bar' ? 8 : 0,
                    borderSkipped: false,
                }]
            },
            options: getChartOptions(type)
        };
        
        // PERBAIKAN: Handle area chart sebagai line chart dengan fill
        if (type === 'area') {
            config.type = 'line';
            config.data.datasets[0].fill = true;
            config.data.datasets[0].backgroundColor = 'rgba(52, 152, 219, 0.3)';
        }
        
        try {
            currentChart = new Chart(ctx, config);
            console.log(`Chart created successfully: ${type}`);
        } catch (error) {
            console.error(`Error creating ${type} chart:`, error);
            // Fallback to line chart if error
            if (type !== 'line') {
                createChart('line');
            }
        }
    }
    
    function getBackgroundColor(type) {
        switch(type) {
            case 'pie':
                return [
                    '#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6', 
                    '#1abc9c', '#34495e', '#f1c40f', '#e67e22', '#95a5a6'
                ];
            case 'area':
                return 'rgba(52, 152, 219, 0.3)';
            case 'bar':
                return 'rgba(52, 152, 219, 0.8)';
            default:
                return 'rgba(52, 152, 219, 0.1)';
        }
    }
    
    function getChartOptions(type) {
        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: type === 'pie',
                    position: type === 'pie' ? 'right' : 'top',
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
                    borderColor: '#3498db',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            if (type === 'pie') {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return `${context.label}: ${context.parsed.toLocaleString()} unit (${percentage}%)`;
                            }
                            return `Penjualan: ${context.parsed.toLocaleString()} unit`;
                        }
                    }
                }
            }
        };
        
        // Add scales for non-pie charts
        if (type !== 'pie') {
            baseOptions.scales = {
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
            };
        }
        
        return baseOptions;
    }
    
    // Initialize with line chart
    createChart('line');
    
    // PERBAIKAN: Chart type buttons dengan error handling
    document.querySelectorAll('.btn-chart-type').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all buttons
            document.querySelectorAll('.btn-chart-type').forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get chart type
            const type = this.getAttribute('data-type');
            console.log(`Switching to chart type: ${type}`);
            
            // Create new chart with delay to ensure proper rendering
            setTimeout(() => {
                createChart(type);
            }, 100);
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (currentChart) {
            currentChart.resize();
        }
    });
});
</script>
@endsection
