@extends('layouts.app')

@section('title', 'Analisis ' . $produk->nama_produk . ' - UMKM Prediction')
@section('page-icon', '🔍')
@section('page-title', 'Analisis Produk: ' . $produk->nama_produk)
@section('page-subtitle', 'Prediksi penjualan berdasarkan data historis')

@section('styles')
<style>
    /* 🎨 FORCE BACKGROUND & COLORS */
    body {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
        color: #ffffff !important;
        min-height: 100vh !important;
    }

    .analisis-wrapper {
        padding: 2rem;
        background: transparent;
        min-height: calc(100vh - 200px);
    }

    /* 🎯 MODERN CARDS - ENHANCED */
    .modern-card {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    }

    /* 🎯 PREDICTION FORM - ENHANCED */
    .prediction-form {
        background: linear-gradient(135deg, rgba(155, 89, 182, 0.2), rgba(142, 68, 173, 0.2)) !important;
        border: 1px solid rgba(155, 89, 182, 0.4);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(155, 89, 182, 0.2);
    }

    .prediction-form h4 {
        color: #ffffff !important;
        margin-bottom: 1.5rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* 🎯 FORM CONTROLS - ENHANCED */
    .form-label {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 0.8rem;
        display: block;
        font-size: 1rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .form-control {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        color: #ffffff !important;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: #9b59b6;
        box-shadow: 0 0 0 4px rgba(155, 89, 182, 0.2);
        color: #ffffff !important;
        outline: none;
    }

    .form-control option {
        background: #1a1a2e !important;
        color: #ffffff !important;
        padding: 0.5rem;
    }

    /* 🎯 BUTTONS - ENHANCED */
    .btn-predict {
        background: linear-gradient(135deg, #9b59b6, #8e44ad) !important;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(155, 89, 182, 0.3);
    }

    .btn-predict:hover {
        background: linear-gradient(135deg, #8e44ad, #9b59b6) !important;
        transform: translateY(-2px);
        color: #ffffff !important;
        box-shadow: 0 12px 35px rgba(155, 89, 182, 0.4);
    }

    /* 🎯 DATA TABLE - COMPLETELY REDESIGNED */
    .data-table {
        background: rgba(255, 255, 255, 0.05) !important;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        border: none;
        color: #ffffff !important;
        font-weight: 700;
        padding: 1.8rem 1.5rem;
        font-size: 1rem;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .table tbody td {
        border: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.8rem 1.5rem;
        vertical-align: middle;
        background: transparent;
        color: #ffffff !important;
        font-weight: 500;
        font-size: 1rem;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        transform: scale(1.01);
    }

    .table tbody tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.02) !important;
    }

    .table tbody tr:nth-child(even):hover {
        background: rgba(255, 255, 255, 0.08) !important;
    }

    /* 🎯 SPECIFIC DATA STYLING */
    .table tbody td span[style*="color: #9b59b6"] {
        color: #c39bd3 !important;
        font-weight: 700;
        font-size: 1.1rem;
        text-shadow: 0 2px 4px rgba(195, 155, 211, 0.3);
    }

    .table tbody td span[style*="color: #2ecc71"] {
        color: #58d68d !important;
        font-weight: 800;
        font-size: 1.2rem;
        text-shadow: 0 2px 4px rgba(88, 214, 141, 0.3);
    }

    .table tbody td span[style*="color: rgba(255, 255, 255, 0.6)"] {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
    }

    /* 🎯 PREDICTION RESULTS - ENHANCED */
    .prediction-results {
        background: linear-gradient(135deg, rgba(46, 204, 113, 0.2), rgba(39, 174, 96, 0.2)) !important;
        border: 1px solid rgba(46, 204, 113, 0.4);
        border-radius: 20px;
        padding: 2.5rem;
        margin-top: 2rem;
        display: none;
        box-shadow: 0 15px 35px rgba(46, 204, 113, 0.2);
    }

    .prediction-results h4 {
        color: #ffffff !important;
        margin-bottom: 1.5rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .result-item {
        background: rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .result-item:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        transform: translateY(-2px);
    }

    .result-period {
        font-weight: 700;
        color: #58d68d !important;
        font-size: 1.1rem;
        text-shadow: 0 2px 4px rgba(88, 214, 141, 0.3);
    }

    .result-value {
        font-weight: 800;
        font-size: 1.3rem;
        color: #4facfe !important;
        text-shadow: 0 2px 4px rgba(79, 172, 254, 0.3);
    }

    .accuracy-badge {
        background: linear-gradient(135deg, #2ecc71, #27ae60) !important;
        color: #ffffff !important;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        font-weight: 700;
        display: inline-block;
        margin-top: 1rem;
        box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);
    }

    /* 🎯 CHART CONTAINER - ENHANCED */
    .chart-container {
        position: relative;
        height: 400px;
        margin: 2rem 0;
        background: rgba(255, 255, 255, 0.05) !important;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    /* 🎯 PRODUCT INFO STYLING */
    .modern-card h4[style*="color: #9b59b6"] {
        color: #c39bd3 !important;
        text-shadow: 0 2px 4px rgba(195, 155, 211, 0.3);
    }

    .modern-card p[style*="color: rgba(255, 255, 255, 0.8)"] {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* 🎯 HEADINGS - FORCE COLORS */
    .analisis-wrapper h1,
    .analisis-wrapper h2,
    .analisis-wrapper h3,
    .analisis-wrapper h4,
    .analisis-wrapper h5,
    .analisis-wrapper h6 {
        color: #ffffff !important;
        font-weight: 700;
    }

    .analisis-wrapper p,
    .analisis-wrapper div,
    .analisis-wrapper span,
    .analisis-wrapper small {
        color: #ffffff !important;
    }

    /* 🎯 BREADCRUMB STYLING */
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 1.5rem 0 0 0;
    }

    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8) !important;
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-item a:hover {
        color: #4facfe !important;
    }

    .breadcrumb-item.active {
        color: #ffffff !important;
        font-weight: 600;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    /* 🎯 RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .analisis-wrapper {
            padding: 1rem;
        }
        
        .chart-container {
            height: 300px;
            padding: 1rem;
        }
        
        .modern-card {
            padding: 1.5rem;
        }
        
        .prediction-form {
            padding: 1.5rem;
        }
        
        .prediction-results {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('konten')
<div class="analisis-wrapper">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" style="margin-bottom: 2rem;">
        <ol class="breadcrumb" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" style="color: rgba(255, 255, 255, 0.7);">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('analisis.index') }}" style="color: rgba(255, 255, 255, 0.7);">Analisis Prediksi</a>
            </li>
            <li class="breadcrumb-item active" style="color: rgba(255, 255, 255, 0.9);">{{ $produk->nama_produk }}</li>
        </ol>
    </nav>

    <!-- Product Info -->
    <div class="modern-card">
        <div class="row">
            <div class="col-md-8">
                <h4 style="color: #9b59b6; margin-bottom: 1rem;">📦 Informasi Produk</h4>
                <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 0.5rem;">
                    <strong>Nama:</strong> {{ $produk->nama_produk }}
                </p>
                @if($produk->deskripsi)
                <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 0.5rem;">
                    <strong>Deskripsi:</strong> {{ $produk->deskripsi }}
                </p>
                @endif
                @if($produk->kategori)
                <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 0;">
                    <strong>Kategori:</strong> 
                    <span class="badge" style="background: rgba(52, 152, 219, 0.3); color: white; padding: 0.3rem 0.8rem; border-radius: 15px;">
                        {{ $produk->kategori }}
                    </span>
                </p>
                @endif
            </div>
            <div class="col-md-4">
                <div style="background: rgba(52, 152, 219, 0.1); border-radius: 12px; padding: 1.5rem; text-align: center;">
                    <div style="font-size: 2rem; color: #3498db; font-weight: 700;">{{ $penjualanData->count() }}</div>
                    <div style="color: rgba(255, 255, 255, 0.8);">Periode Data Tersedia</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historical Data -->
    <div class="modern-card">
        <h4 style="color: #3498db; margin-bottom: 1.5rem;">📊 Data Penjualan Historis</h4>
        
        @if($penjualanData->count() > 0)
            <div class="data-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Periode</th>
                                <th>Jumlah Penjualan</th>
                                <th>Tanggal Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualanData as $index => $penjualan)
                                <tr>
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        <span style="color: #9b59b6; font-weight: 600;">
                                            {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$penjualan->bulan] }} {{ $penjualan->tahun }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: #2ecc71; font-weight: 700; font-size: 1.1rem;">
                                            {{ number_format($penjualan->jumlah_penjualan) }} unit
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: rgba(255, 255, 255, 0.6);">
                                            {{ $penjualan->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chart -->
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        @endif
    </div>

    <!-- Prediction Form -->
    <div class="prediction-form">
        <h4 style="color: #9b59b6; margin-bottom: 1.5rem;">🎯 Generate Prediksi</h4>
        <form id="predictionForm">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
            
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" style="color: rgba(255, 255, 255, 0.9); font-weight: 600;">
                        Jumlah Periode Prediksi
                    </label>
                    <select name="periode_prediksi" class="form-control" required>
                        <option value="">Pilih Periode</option>
                        <option value="1">1 Bulan ke Depan</option>
                        <option value="3">3 Bulan ke Depan</option>
                        <option value="6" selected>6 Bulan ke Depan</option>
                        <option value="12">12 Bulan ke Depan</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn-predict w-100">
                        🔮 Generate Prediksi
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Prediction Results -->
    <div id="predictionResults" class="prediction-results">
        <h4 style="color: #2ecc71; margin-bottom: 1.5rem;">📈 Hasil Prediksi</h4>
        <div id="resultsContent"></div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Render historical data chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($penjualanData);
    
    const labels = salesData.map(item => {
        const months = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return months[item.bulan] + ' ' + item.tahun;
    });
    
    const data = salesData.map(item => item.jumlah_penjualan);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan Historis',
                data: data,
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
            maintainAspectRatio: false,
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
    
    // Handle prediction form
    document.getElementById('predictionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Show loading
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Generating...';
        submitBtn.disabled = true;
        
        fetch('{{ route("analisis.generate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPredictionResults(data.data);
            } else {
                alert(data.message || 'Gagal generate prediksi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat generate prediksi');
        })
        .finally(() => {
            // Reset button
            submitBtn.innerHTML = '🔮 Generate Prediksi';
            submitBtn.disabled = false;
        });
    });
    
    function displayPredictionResults(data) {
        const resultsDiv = document.getElementById('predictionResults');
        const contentDiv = document.getElementById('resultsContent');
        
        let html = `
            <div class="accuracy-badge">
                Akurasi Model: ${data.akurasi}%
            </div>
            <div style="margin: 1.5rem 0;">
                <h6 style="color: rgba(255, 255, 255, 0.9);">Prediksi Penjualan:</h6>
        `;
        
        data.prediksi.forEach(item => {
            html += `
                <div class="result-item">
                    <div class="result-period">${item.bulan_nama} ${item.tahun}</div>
                    <div class="result-value">${item.prediksi.toLocaleString()} unit</div>
                </div>
            `;
        });
        
        html += `
            </div>
            <div style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 1rem; margin-top: 1rem;">
                <small style="color: rgba(255, 255, 255, 0.7);">
                    <strong>Catatan:</strong> Prediksi ini menggunakan algoritma Linear Regression berdasarkan ${data.total_data} periode data historis. 
                    Trend: ${data.trend_coefficient > 0 ? 'Naik' : (data.trend_coefficient < 0 ? 'Turun' : 'Stabil')}.
                </small>
            </div>
        `;
        
        contentDiv.innerHTML = html;
        resultsDiv.style.display = 'block';
        
        // Smooth scroll to results
        resultsDiv.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endsection
