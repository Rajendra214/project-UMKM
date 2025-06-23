@extends('layouts.app')

@section('title', 'Tambah Data Penjualan - UMKM Prediction')
@section('page-icon', '📈')
@section('page-title', 'Tambah Data Penjualan')
@section('page-subtitle', 'Input data penjualan untuk analisis prediksi')

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
        border-color: #2ecc71;
        box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2);
        color: white;
    }

    .form-control option {
        background: #2c3e50;
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
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

    .info-card {
        background: rgba(46, 204, 113, 0.1);
        border: 1px solid rgba(46, 204, 113, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1.5rem 0;
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
                <a href="{{ route('penjualan.index') }}" style="color: rgba(255, 255, 255, 0.7);">Data Penjualan</a>
            </li>
            <li class="breadcrumb-item active" style="color: rgba(255, 255, 255, 0.9);">Tambah Data</li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="modern-card">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h4 style="color: #2ecc71; margin-bottom: 1.5rem; text-align: center;">
                    📊 Formulir Input Data Penjualan
                </h4>
                
                <form action="{{ route('penjualan.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="produk_id" class="form-label">
                            <span style="color: #e74c3c;">*</span> Pilih Produk
                        </label>
                        <select class="form-control @error('produk_id') is-invalid @enderror" 
                                id="produk_id" 
                                name="produk_id" 
                                required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                        @error('produk_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($produks->count() == 0)
                            <small style="color: #e74c3c;">
                                Belum ada produk. <a href="{{ route('produk.create') }}" style="color: #3498db;">Tambah produk terlebih dahulu</a>
                            </small>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulan" class="form-label">
                                    <span style="color: #e74c3c;">*</span> Bulan
                                </label>
                                <select class="form-control @error('bulan') is-invalid @enderror" 
                                        id="bulan" 
                                        name="bulan" 
                                        required>
                                    <option value="">-- Pilih Bulan --</option>
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $key => $bulan)
                                        <option value="{{ $key + 1 }}" {{ old('bulan') == ($key + 1) ? 'selected' : '' }}>
                                            {{ $bulan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bulan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun" class="form-label">
                                    <span style="color: #e74c3c;">*</span> Tahun
                                </label>
                                <select class="form-control @error('tahun') is-invalid @enderror" 
                                        id="tahun" 
                                        name="tahun" 
                                        required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @for($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}" {{ old('tahun') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_penjualan" class="form-label">
                            <span style="color: #e74c3c;">*</span> Jumlah Penjualan (Unit)
                        </label>
                        <input type="number" 
                               class="form-control @error('jumlah_penjualan') is-invalid @enderror" 
                               id="jumlah_penjualan" 
                               name="jumlah_penjualan" 
                               value="{{ old('jumlah_penjualan') }}"
                               placeholder="Contoh: 150"
                               min="0"
                               required>
                        @error('jumlah_penjualan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info-card">
                        <h6 style="color: #2ecc71; margin-bottom: 0.5rem;">💡 Tips Input Data:</h6>
                        <ul style="color: rgba(255, 255, 255, 0.8); margin: 0; padding-left: 1.5rem;">
                            <li>Pastikan data periode (bulan & tahun) belum pernah diinput untuk produk yang sama</li>
                            <li>Input data secara berurutan untuk hasil prediksi yang lebih akurat</li>
                            <li>Minimal 2 periode data diperlukan untuk analisis prediksi</li>
                            <li>Data yang diinput akan langsung tersedia untuk analisis</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-3 justify-content-center btn-group">
                        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            💾 Simpan Data Penjualan
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
    // Auto focus pada select pertama
    document.getElementById('produk_id').focus();
    
    // Set default bulan dan tahun ke bulan/tahun saat ini
    const currentMonth = new Date().getMonth() + 1;
    const currentYear = new Date().getFullYear();
    
    if (!document.getElementById('bulan').value) {
        document.getElementById('bulan').value = currentMonth;
    }
    
    if (!document.getElementById('tahun').value) {
        document.getElementById('tahun').value = currentYear;
    }
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const produkId = document.getElementById('produk_id').value;
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const jumlah = document.getElementById('jumlah_penjualan').value;
        
        if (!produkId || !bulan || !tahun || !jumlah) {
            e.preventDefault();
            alert('Semua field wajib diisi!');
            return false;
        }
        
        if (parseInt(jumlah) < 0) {
            e.preventDefault();
            alert('Jumlah penjualan tidak boleh negatif!');
            document.getElementById('jumlah_penjualan').focus();
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...';
        submitBtn.disabled = true;
    });
    
    // Check for duplicate data when form fields change
    function checkDuplicate() {
        const produkId = document.getElementById('produk_id').value;
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        
        if (produkId && bulan && tahun) {
            // You could add AJAX call here to check for duplicates
            // For now, just show a warning in console
            console.log(`Checking for duplicate: Produk ${produkId}, ${bulan}/${tahun}`);
        }
    }
    
    document.getElementById('produk_id').addEventListener('change', checkDuplicate);
    document.getElementById('bulan').addEventListener('change', checkDuplicate);
    document.getElementById('tahun').addEventListener('change', checkDuplicate);
});
</script>
@endsection
