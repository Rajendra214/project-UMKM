@extends('layouts.app')

@section('title', 'Data Penjualan - UMKM Prediction')
@section('page-icon', '📊')
@section('page-title', 'Data Penjualan')
@section('page-subtitle', 'Manajemen data penjualan untuk analisis prediksi')

@section('styles')
<style>
    /* 🎨 FORCE BACKGROUND & COLORS */
    body {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
        color: #ffffff !important;
        min-height: 100vh !important;
    }

    .penjualan-wrapper {
        padding: 2rem;
        background: transparent;
        min-height: calc(100vh - 200px);
    }

    /* 🎯 MODERN CARDS - FIXED */
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

    /* 🎯 FILTER CARD - ENHANCED */
    .filter-card {
        background: linear-gradient(135deg, rgba(67, 233, 123, 0.2), rgba(56, 249, 215, 0.2)) !important;
        border: 1px solid rgba(67, 233, 123, 0.4);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(67, 233, 123, 0.2);
    }

    .filter-card h6 {
        color: #ffffff !important;
        margin-bottom: 1.5rem;
        font-weight: 700;
        font-size: 1.2rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .filter-row {
        display: flex;
        gap: 1.5rem;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-row > div {
        flex: 1;
        min-width: 200px;
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
        border-color: #43e97b;
        box-shadow: 0 0 0 4px rgba(67, 233, 123, 0.2);
        color: #ffffff !important;
        outline: none;
    }

    .form-control option {
        background: #1a1a2e !important;
        color: #ffffff !important;
        padding: 0.5rem;
    }

    /* 🎯 BUTTONS - ENHANCED */
    .btn {
        font-weight: 600;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-size: 1rem;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        color: #ffffff !important;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2, #667eea) !important;
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        color: #ffffff !important;
    }

    .btn-success {
        background: linear-gradient(135deg, #43e97b, #38f9d7) !important;
        color: #ffffff !important;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #38f9d7, #43e97b) !important;
        transform: translateY(-2px);
        color: #ffffff !important;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ff9f43, #ffce54) !important;
        color: #ffffff !important;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #ffce54, #ff9f43) !important;
        transform: translateY(-2px);
        color: #ffffff !important;
    }

    .btn-danger {
        background: linear-gradient(135deg, #fa709a, #fee140) !important;
        color: #ffffff !important;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #fee140, #fa709a) !important;
        transform: translateY(-2px);
        color: #ffffff !important;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #ffffff !important;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        border-color: rgba(255, 255, 255, 0.5);
        color: #ffffff !important;
    }

    /* 🎯 FILTER RESULTS - ENHANCED */
    .filter-results {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
    }

    .filter-results h6 {
        color: #667eea !important;
        margin-bottom: 1.5rem;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .filter-tag {
        display: inline-block;
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        color: #ffffff !important;
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-size: 0.9rem;
        margin: 0.3rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .clear-filters {
        background: linear-gradient(135deg, #fa709a, #fee140) !important;
        border: none;
        color: #ffffff !important;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-left: 1rem;
        box-shadow: 0 4px 15px rgba(250, 112, 154, 0.3);
    }

    .clear-filters:hover {
        transform: translateY(-2px);
        color: #ffffff !important;
    }

    /* 🎯 TABLE - COMPLETELY REDESIGNED */
    .table-container {
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

    /* 🎯 BADGES - ENHANCED */
    .badge-produk {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        color: #ffffff !important;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        display: inline-block;
    }

    .badge-periode {
        background: linear-gradient(135deg, #43e97b, #38f9d7) !important;
        color: #ffffff !important;
        padding: 0.7rem 1.3rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(67, 233, 123, 0.3);
        display: inline-block;
    }

    .sales-amount {
        color: #4facfe !important;
        font-weight: 800;
        font-size: 1.3rem;
        text-shadow: 0 2px 4px rgba(79, 172, 254, 0.3);
    }

    /* 🎯 BUTTON GROUP - ENHANCED */
    .btn-group {
        display: flex;
        gap: 0.8rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-group .btn {
        padding: 0.8rem 1.5rem;
        font-size: 0.9rem;
        min-width: auto;
    }

    .btn-sm {
        padding: 0.6rem 1.2rem !important;
        font-size: 0.85rem !important;
    }

    /* 🎯 EMPTY STATE - ENHANCED */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: #ffffff !important;
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 2rem;
        display: block;
        opacity: 0.8;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
    }

    .empty-state h5 {
        color: #ffffff !important;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: rgba(255, 255, 255, 0.8) !important;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    /* 🎯 PAGINATION - ENHANCED */
    .pagination {
        justify-content: center;
        margin-top: 3rem;
        gap: 0.5rem;
    }

    .page-link {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff !important;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .page-link:hover {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        border-color: transparent;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        border-color: transparent;
        color: #ffffff !important;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* 🎯 ALERTS - ENHANCED */
    .alert {
        border: none;
        border-radius: 15px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(67, 233, 123, 0.2), rgba(56, 249, 215, 0.2)) !important;
        border-left: 5px solid #43e97b;
        color: #ffffff !important;
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(250, 112, 154, 0.2), rgba(254, 225, 64, 0.2)) !important;
        border-left: 5px solid #fa709a;
        color: #ffffff !important;
    }

    /* 🎯 HEADINGS - FORCE COLORS */
    .penjualan-wrapper h1,
    .penjualan-wrapper h2,
    .penjualan-wrapper h3,
    .penjualan-wrapper h4,
    .penjualan-wrapper h5,
    .penjualan-wrapper h6 {
        color: #ffffff !important;
        font-weight: 700;
    }

    .penjualan-wrapper p,
    .penjualan-wrapper div,
    .penjualan-wrapper span,
    .penjualan-wrapper small {
        color: #ffffff !important;
    }

    /* 🎯 RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .penjualan-wrapper {
            padding: 1rem;
        }
        
        .filter-row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .filter-row > div {
            min-width: auto;
        }
        
        .btn-group {
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }
        
        .btn-group .btn {
            width: 100%;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .table {
            min-width: 600px;
        }
        
        .modern-card {
            padding: 1.5rem;
        }
        
        .filter-card {
            padding: 1.5rem;
        }
    }

    /* 🎯 SCROLLBAR STYLING */
    ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 6px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
    }
</style>
@endsection

@section('konten')
<div class="penjualan-wrapper">
    
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

    <!-- Header Actions -->
    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 style="color: var(--text-accent) !important; margin: 0;">📊 Data Penjualan</h4>
                <p style="color: var(--text-secondary) !important; margin: 0.5rem 0 0 0;">
                    Kelola data penjualan historis untuk analisis prediksi yang akurat
                </p>
            </div>
            <a href="{{ route('penjualan.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Data Penjualan
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <h6>🔍 Filter Data Penjualan</h6>
        <form method="GET" action="{{ route('penjualan.index') }}" id="filterForm">
            <div class="filter-row">
                <div class="flex-fill">
                    <label class="form-label">Produk</label>
                    <select name="produk_id" class="form-control">
                        <option value="">Semua Produk</option>
                        @foreach(\App\Models\Produk::all() as $produk)
                            <option value="{{ $produk->id }}" {{ request('produk_id') == $produk->id ? 'selected' : '' }}>
                                {{ $produk->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-fill">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-control">
                        <option value="">Semua Tahun</option>
                        @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="flex-fill">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $key => $bulan)
                            <option value="{{ $key + 1 }}" {{ request('bulan') == ($key + 1) ? 'selected' : '' }}>
                                {{ $bulan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        🔍 Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Filter Results Info -->
    @if(request()->hasAny(['produk_id', 'tahun', 'bulan']))
    <div class="filter-results">
        <h6>📋 Hasil Filter:</h6>
        <div>
            @if(request('produk_id'))
                @php
                    $selectedProduk = \App\Models\Produk::find(request('produk_id'));
                @endphp
                <span class="filter-tag">
                    📦 {{ $selectedProduk ? $selectedProduk->nama_produk : 'Produk tidak ditemukan' }}
                </span>
            @endif
            
            @if(request('tahun'))
                <span class="filter-tag">
                    📅 Tahun {{ request('tahun') }}
                </span>
            @endif
            
            @if(request('bulan'))
                @php
                    $bulanNames = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                @endphp
                <span class="filter-tag">
                    🗓️ {{ $bulanNames[request('bulan')] }}
                </span>
            @endif
            
            <a href="{{ route('penjualan.index') }}" class="clear-filters">
                ❌ Hapus Filter
            </a>
        </div>
        <small style="color: var(--text-muted) !important; margin-top: 0.5rem; display: block;">
            Menampilkan {{ $penjualan->total() }} dari {{ \App\Models\PenjualanProduk::count() }} total data
        </small>
    </div>
    @endif

    <!-- Sales Data Table -->
    <div class="modern-card">
        @if($penjualan->count() > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Periode</th>
                                <th>Jumlah Penjualan</th>
                                <th>Diinput</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan as $index => $item)
                                <tr>
                                    <td><strong>{{ $penjualan->firstItem() + $index }}</strong></td>
                                    <td>
                                        <span class="badge-produk">{{ $item->produk->nama_produk }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-periode">
                                            {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$item->bulan] }} {{ $item->tahun }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="sales-amount">{{ number_format($item->jumlah_penjualan) }} unit</span>
                                    </td>
                                    <td>
                                        <span style="color: var(--text-muted) !important;">
                                            {{ $item->created_at->format('d M Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('penjualan.edit', $item->id) }}" 
                                               class="btn btn-warning btn-sm">
                                                ✏️ Edit
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="deletePenjualan({{ $item->id }}, '{{ $item->produk->nama_produk }}', '{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$item->bulan] }}', {{ $item->tahun }})">
                                                🗑️ Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $penjualan->links() }}
            </div>
        @else
            <div class="empty-state">
                <span class="empty-icon">📊</span>
                <h5 style="color: var(--text-secondary) !important;">
                    @if(request()->hasAny(['produk_id', 'tahun', 'bulan']))
                        Tidak ada data yang sesuai dengan filter
                    @else
                        Belum ada data penjualan
                    @endif
                </h5>
                <p style="color: var(--text-muted) !important;">
                    @if(request()->hasAny(['produk_id', 'tahun', 'bulan']))
                        Coba ubah kriteria filter atau hapus filter untuk melihat semua data.
                    @else
                        Mulai dengan menambahkan data penjualan untuk analisis prediksi.
                    @endif
                </p>
                @if(request()->hasAny(['produk_id', 'tahun', 'bulan']))
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">
                        🔄 Hapus Filter
                    </a>
                @else
                    <a href="{{ route('penjualan.create') }}" class="btn btn-success mt-3">
                        📈 Tambah Data Penjualan
                    </a>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
function deletePenjualan(id, produk, bulan, tahun) {
    if (!confirm(`Apakah Anda yakin ingin menghapus data penjualan?\n\nProduk: ${produk}\nPeriode: ${bulan} ${tahun}`)) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/penjualan/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Gagal menghapus data penjualan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data penjualan');
    });
}

// Auto-submit form when filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const selects = filterForm.querySelectorAll('select');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Optional: Auto-submit on change
            // filterForm.submit();
        });
    });
});
</script>
@endsection
