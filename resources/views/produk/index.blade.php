@extends('layouts.app')

@section('title', 'Kelola Produk - UMKM Prediction')
@section('page-icon', '📦')
@section('page-title', 'Kelola Produk')
@section('page-subtitle', 'Manajemen data produk untuk analisis prediksi penjualan')

@section('styles')
<style>
    /* 🎨 FORCE BACKGROUND & COLORS */
    body {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
        color: #ffffff !important;
        min-height: 100vh !important;
    }

    .produk-wrapper {
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

    .btn-sm {
        padding: 0.6rem 1.2rem !important;
        font-size: 0.85rem !important;
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

    /* 🎯 PRODUCT NAME STYLING */
    .table tbody td strong {
        color: #4facfe !important;
        font-weight: 800;
        font-size: 1.2rem;
        text-shadow: 0 2px 4px rgba(79, 172, 254, 0.3);
    }

    /* 🎯 BADGES - ENHANCED */
    .badge {
        background: linear-gradient(135deg, #43e97b, #38f9d7) !important;
        color: #ffffff !important;
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 6px 20px rgba(67, 233, 123, 0.3);
        display: inline-block;
    }

    /* 🎯 DATA COUNT STYLING */
    .table tbody td span[style*="color: #2ecc71"] {
        color: #43e97b !important;
        font-weight: 800;
        font-size: 1.1rem;
        text-shadow: 0 2px 4px rgba(67, 233, 123, 0.3);
    }

    /* �� DATE STYLING */
    .table tbody td span[style*="color: rgba(255, 255, 255, 0.6)"] {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 500;
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
    .produk-wrapper h1,
    .produk-wrapper h2,
    .produk-wrapper h3,
    .produk-wrapper h4,
    .produk-wrapper h5,
    .produk-wrapper h6 {
        color: #ffffff !important;
        font-weight: 700;
    }

    .produk-wrapper p,
    .produk-wrapper div,
    .produk-wrapper span,
    .produk-wrapper small {
        color: #ffffff !important;
    }

    /* 🎯 SPECIFIC COLOR OVERRIDES */
    .modern-card h4[style*="color: #3498db"] {
        color: #4facfe !important;
        text-shadow: 0 2px 4px rgba(79, 172, 254, 0.3);
    }

    .modern-card p[style*="color: rgba(255, 255, 255, 0.7)"] {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* 🎯 RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .produk-wrapper {
            padding: 1rem;
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
<div class="produk-wrapper">
    
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
                <h4 style="color: #3498db; margin: 0;">📦 Daftar Produk</h4>
                <p style="color: rgba(255, 255, 255, 0.7); margin: 0.5rem 0 0 0;">
                    Kelola semua produk yang akan dianalisis untuk prediksi penjualan
                </p>
            </div>
            <a href="{{ route('produk.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="modern-card">
        @if($produks->count() > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th>Data Penjualan</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produks as $index => $produk)
                                <tr>
                                    <td><strong>{{ $produks->firstItem() + $index }}</strong></td>
                                    <td>
                                        <strong style="color: #3498db;">{{ $produk->nama_produk }}</strong>
                                    </td>
                                    <td>
                                        <span style="color: rgba(255, 255, 255, 0.8);">
                                            {{ Str::limit($produk->deskripsi ?? 'Tidak ada deskripsi', 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: rgba(52, 152, 219, 0.3); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                                            {{ $produk->kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: #2ecc71; font-weight: 600;">
                                            {{ $produk->penjualan_count }} data
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: rgba(255, 255, 255, 0.6);">
                                            {{ $produk->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('produk.edit', $produk->id) }}" 
                                               class="btn btn-warning btn-sm">
                                                ✏️ Edit
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="deleteProduk({{ $produk->id }}, '{{ addslashes($produk->nama_produk) }}')">
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
                {{ $produks->links() }}
            </div>
        @else
            <div class="empty-state">
                <span class="empty-icon">📦</span>
                <h5 style="color: rgba(255, 255, 255, 0.8);">Belum ada produk</h5>
                <p>Mulai dengan menambahkan produk pertama untuk analisis prediksi penjualan.</p>
                <a href="{{ route('produk.create') }}" class="btn btn-primary mt-3">
                    ➕ Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
function deleteProduk(id, nama) {
    if (!confirm(`Apakah Anda yakin ingin menghapus produk "${nama}"?\n\nSemua data penjualan terkait juga akan dihapus.`)) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/produk/${id}`, {
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
            alert(data.message || 'Gagal menghapus produk');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus produk');
    });
}
</script>
@endsection
