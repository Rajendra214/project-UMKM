@extends('layouts.app')

@section('title', 'Tambah Produk - UMKM Prediction')
@section('page-icon', '➕')
@section('page-title', 'Tambah Produk Baru')
@section('page-subtitle', 'Tambahkan produk baru untuk analisis prediksi penjualan')

@section('styles')
<style>
    .form-wrapper {
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

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: white;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        color: white;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .btn-primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2980b9, #3498db);
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    .alert {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background: rgba(231, 76, 60, 0.2);
        border-left: 4px solid #e74c3c;
        color: rgba(255, 255, 255, 0.9);
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .form-control.is-invalid {
        border-color: #e74c3c;
    }

    @media (max-width: 768px) {
        .form-wrapper {
            padding: 1rem;
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
                <a href="{{ route('dashboard') }}" style="color: rgba(255, 255, 255, 0.7);">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('produk.index') }}" style="color: rgba(255, 255, 255, 0.7);">Kelola Produk</a>
            </li>
            <li class="breadcrumb-item active" style="color: rgba(255, 255, 255, 0.9);">Tambah Produk</li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="modern-card">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h4 style="color: #3498db; margin-bottom: 1.5rem; text-align: center;">
                    📦 Formulir Tambah Produk
                </h4>
                
                <form action="{{ route('produk.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama_produk" class="form-label">
                            <span style="color: #e74c3c;">*</span> Nama Produk
                        </label>
                        <input type="text" 
                               class="form-control @error('nama_produk') is-invalid @enderror" 
                               id="nama_produk" 
                               name="nama_produk" 
                               value="{{ old('nama_produk') }}"
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
                                  placeholder="Deskripsi detail tentang produk (opsional)">{{ old('deskripsi') }}</textarea>
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
                            <option value="Makanan" {{ old('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('kategori') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Fashion" {{ old('kategori') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                            <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                            <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Kesehatan" {{ old('kategori') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan & Kecantikan</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="background: rgba(52, 152, 219, 0.1); border-left: 4px solid #3498db; padding: 1rem; border-radius: 8px; margin: 1.5rem 0;">
                        <h6 style="color: #3498db; margin-bottom: 0.5rem;">💡 Tips:</h6>
                        <ul style="color: rgba(255, 255, 255, 0.8); margin: 0; padding-left: 1.5rem;">
                            <li>Gunakan nama produk yang jelas dan mudah diingat</li>
                            <li>Deskripsi membantu dalam analisis yang lebih detail</li>
                            <li>Kategori memudahkan pengelompokan produk</li>
                            <li>Setelah produk dibuat, Anda dapat menambahkan data penjualan</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-3 justify-content-center btn-group">
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            💾 Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...';
        submitBtn.disabled = true;
    });
    
    // Character counter for description
    const deskripsi = document.getElementById('deskripsi');
    const maxLength = 1000;
    
    // Create counter element
    const counter = document.createElement('small');
    counter.style.color = 'rgba(255, 255, 255, 0.6)';
    counter.style.float = 'right';
    deskripsi.parentNode.appendChild(counter);
    
    function updateCounter() {
        const remaining = maxLength - deskripsi.value.length;
        counter.textContent = `${deskripsi.value.length}/${maxLength} karakter`;
        
        if (remaining < 100) {
            counter.style.color = '#`;
        
        if (remaining < 100) {
            counter.style.color = '#e74c3c';
        } else {
            counter.style.color = 'rgba(255, 255, 255, 0.6)';
        }
    }
    
    deskripsi.addEventListener('input', updateCounter);
    updateCounter(); // Initial call
});
</script>
@endsection
