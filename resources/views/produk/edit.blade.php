@extends('layouts.app')

@section('title', 'Edit Produk - UMKM Prediction')
@section('page-icon', '✏️')
@section('page-title', 'Edit Produk')
@section('page-subtitle', 'Perbarui informasi produk untuk analisis prediksi')

@section('styles')
<style>
    .form-wrapper {
        padding: 2rem;
        background: transparent;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .current-data {
        background: var(--card-bg) !important;
        border: 1px solid var(--card-border);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .current-data h6 {
        color: var(--text-accent) !important;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .data-item {
        background: rgba(255, 255, 255, 0.03) !important;
        border-radius: 10px;
        padding: 1.2rem;
        text-align: center;
    }

    .data-label {
        color: var(--text-muted) !important;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .data-value {
        color: var(--text-primary) !important;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .stats-card {
        background: var(--primary-gradient) !important;
        border-radius: 15px;
        padding: 1.8rem;
        margin: 1.5rem 0;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .stats-card h6 {
        color: var(--text-primary) !important;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        background: rgba(255, 255, 255, 0.1) !important;
        border-radius: 10px;
        padding: 1rem;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary) !important;
        margin-bottom: 0.3rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
    }

    .danger-zone {
        background: linear-gradient(135deg, rgba(250, 112, 154, 0.1), rgba(254, 225, 64, 0.1)) !important;
        border: 1px solid rgba(250, 112, 154, 0.3);
        border-radius: 15px;
        padding: 1.8rem;
        margin-top: 2rem;
    }

    .danger-zone h6 {
        color: #fa709a !important;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .form-wrapper {
            padding: 1rem;
        }
        
        .data-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .btn-group {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('konten')
<div class="form-wrapper">
    
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>❌ Terdapat kesalahan:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" style="margin-bottom: 2rem;">
        <ol class="breadcrumb" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary);">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('produk.index') }}" style="color: var(--text-secondary);">Kelola Produk</a>
            </li>
            <li class="breadcrumb-item active" style="color: var(--text-primary);">Edit Produk</li>
        </ol>
    </nav>

    <!-- Current Product Info -->
    <div class="current-data">
        <h6>📦 Informasi Produk Saat Ini</h6>
        <div class="data-grid">
            <div class="data-item">
                <div class="data-label">Nama Produk</div>
                <div class="data-value">{{ $produk->nama_produk }}</div>
            </div>
            <div class="data-item">
                <div class="data-label">Kategori</div>
                <div class="data-value">{{ $produk->kategori ?? 'Tidak ada' }}</div>
            </div>
            <div class="data-item">
                <div class="data-label">Dibuat</div>
                <div class="data-value">{{ $produk->created_at->format('d M Y') }}</div>
            </div>
            <div class="data-item">
                <div class="data-label">Terakhir Update</div>
                <div class="data-value">{{ $produk->updated_at->format('d M Y') }}</div>
            </div>
        </div>
        
        @if($produk->deskripsi)
        <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(255, 255, 255, 0.03); border-radius: 10px;">
            <div class="data-label">Deskripsi</div>
            <div class="data-value">{{ $produk->deskripsi }}</div>
        </div>
        @endif
    </div>

    <!-- Product Statistics -->
    <div class="stats-card">
        <h6>📊 Statistik Produk</h6>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $produk->penjualan->count() }}</div>
                <div class="stat-label">Data Penjualan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ number_format($produk->penjualan->sum('jumlah_penjualan')) }}</div>
                <div class="stat-label">Total Terjual</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $produk->penjualan->count() > 0 ? number_format($produk->penjualan->avg('jumlah_penjualan'), 0) : '0' }}</div>
                <div class="stat-label">Rata-rata/Periode</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $produk->penjualan->count() >= 2 ? '✅' : '❌' }}</div>
                <div class="stat-label">Siap Prediksi</div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="modern-card">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h4 style="color: var(--text-accent) !important; margin-bottom: 1.5rem; text-align: center;">
                    ✏️ Edit Informasi Produk
                </h4>
                
                <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="nama_produk" class="form-label">
                            <span style="color: #fa709a;">*</span> Nama Produk
                        </label>
                        <input type="text" 
                               class="form-control @error('nama_produk') is-invalid @enderror" 
                               id="nama_produk" 
                               name="nama_produk" 
                               value="{{ old('nama_produk', $produk->nama_produk) }}"
                               placeholder="Contoh: Kopi Arabica Premium"
                               required>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="4"
                                  placeholder="Deskripsi detail tentang produk (opsional)">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kategori" class="form-label">Kategori Produk</label>
                        <select class="form-control @error('kategori') is-invalid @enderror" 
                                id="kategori" 
                                name="kategori">
                            <option value="">Pilih Kategori (Opsional)</option>
                            <option value="Makanan" {{ old('kategori', $produk->kategori) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('kategori', $produk->kategori) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Fashion" {{ old('kategori', $produk->kategori) == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                            <option value="Kerajinan" {{ old('kategori', $produk->kategori) == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                            <option value="Elektronik" {{ old('kategori', $produk->kategori) == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Kesehatan" {{ old('kategori', $produk->kategori) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan & Kecantikan</option>
                            <option value="Lainnya" {{ old('kategori', $produk->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 justify-content-center btn-group">
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                            ← Batal
                        </a>
                        <button type="submit" class="btn btn-warning">
                            💾 Update Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    @if($produk->penjualan->count() > 0)
    <div class="danger-zone">
        <h6>⚠️ Zona Berbahaya</h6>
        <p style="color: var(--text-secondary) !important; margin-bottom: 1rem;">
            Produk ini memiliki <strong>{{ $produk->penjualan->count() }} data penjualan</strong>. 
            Menghapus produk akan menghapus semua data penjualan terkait dan mempengaruhi analisis prediksi.
        </p>
        <button type="button" 
                class="btn btn-danger" 
                onclick="deleteProduct({{ $produk->id }}, '{{ addslashes($produk->nama_produk) }}', {{ $produk->penjualan->count() }})">
            🗑️ Hapus Produk & Semua Data
        </button>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto focus pada input pertama
    document.getElementById('nama_produk').focus();
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const namaProduk = document.getElementById('nama_produk').value.trim();
        
        if (namaProduk.length < 3) {
            e.preventDefault();
            alert('Nama produk harus minimal 3 karakter');
            document.getElementById('nama_produk').focus();
            return false;
        }
        
        // Konfirmasi update
        if (!confirm('Apakah Anda yakin ingin mengupdate produk ini?')) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Mengupdate...';
        submitBtn.disabled = true;
    });
    
    // Character counter for description
    const deskripsi = document.getElementById('deskripsi');
    const maxLength = 1000;
    
    // Create counter element
    const counter = document.createElement('small');
    counter.style.color = 'var(--text-muted)';
    counter.style.float = 'right';
    deskripsi.parentNode.appendChild(counter);
    
    function updateCounter() {
        const remaining = maxLength - deskripsi.value.length;
        counter.textContent = `${deskripsi.value.length}/${maxLength} karakter`;
        
        if (remaining < 100) {
            counter.style.color = '#fa709a';
        } else {
            counter.style.color = 'var(--text-muted)';
        }
    }
    
    deskripsi.addEventListener('input', updateCounter);
    updateCounter(); // Initial call
    
    // Highlight changes
    const originalValues = {
        nama_produk: '{{ $produk->nama_produk }}',
        deskripsi: '{{ $produk->deskripsi }}',
        kategori: '{{ $produk->kategori }}'
    };
    
    function checkChanges() {
        const fields = ['nama_produk', 'deskripsi', 'kategori'];
        let hasChanges = false;
        
        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element.value != originalValues[field]) {
                element.style.borderColor = '#43e97b';
                element.style.boxShadow = '0 0 0 3px rgba(67, 233, 123, 0.2)';
                hasChanges = true;
            } else {
                element.style.borderColor = '';
                element.style.boxShadow = '';
            }
        });
        
        // Show/hide save button based on changes
        const submitBtn = document.querySelector('button[type="submit"]');
        if (hasChanges) {
            submitBtn.style.background = 'var(--warning-gradient)';
            submitBtn.style.transform = 'scale(1.05)';
        } else {
            submitBtn.style.background = '';
            submitBtn.style.transform = '';
        }
    }
    
    // Add change listeners
    ['nama_produk', 'deskripsi', 'kategori'].forEach(field => {
        document.getElementById(field).addEventListener('change', checkChanges);
        document.getElementById(field).addEventListener('input', checkChanges);
    });
});

function deleteProduct(id, nama, dataCount) {
    if (!confirm(`⚠️ PERINGATAN KERAS!\n\nAnda akan menghapus produk "${nama}" beserta ${dataCount} data penjualan.\n\nTindakan ini TIDAK DAPAT DIBATALKAN!\n\nKetik "HAPUS" untuk konfirmasi:`)) {
        return;
    }
    
    const confirmation = prompt('Ketik "HAPUS" (huruf besar) untuk konfirmasi:');
    if (confirmation !== 'HAPUS') {
        alert('Konfirmasi tidak sesuai. Penghapusan dibatalkan.');
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
            window.location.href = '{{ route("produk.index") }}';
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
