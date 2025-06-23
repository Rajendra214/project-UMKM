@extends('layouts.app')

@section('title', 'Edit Data Penjualan - UMKM Prediction')
@section('page-icon', '✏️')
@section('page-title', 'Edit Data Penjualan')
@section('page-subtitle', 'Perbarui data penjualan untuk analisis prediksi')

@section('styles')
<style>
    .form-wrapper {
        padding: 2rem;
        background: transparent;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .info-card {
        background: var(--warning-gradient) !important;
        border-radius: 15px;
        padding: 1.8rem;
        margin: 1.5rem 0;
        box-shadow: 0 10px 30px rgba(67, 233, 123, 0.2);
    }

    .info-card h6 {
        color: var(--text-primary) !important;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .current-data {
        background: var(--card-bg) !important;
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .current-data h6 {
        color: var(--text-accent) !important;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .data-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px solid var(--card-border);
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-label {
        color: var(--text-secondary) !important;
        font-weight: 500;
    }

    .data-value {
        color: var(--text-primary) !important;
        font-weight: 600;
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
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary);">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('penjualan.index') }}" style="color: var(--text-secondary);">Data Penjualan</a>
            </li>
            <li class="breadcrumb-item active" style="color: var(--text-primary);">Edit Data</li>
        </ol>
    </nav>

    <!-- Current Data Info -->
    <div class="current-data">
        <h6>📋 Data Saat Ini</h6>
        <div class="data-item">
            <span class="data-label">Produk:</span>
            <span class="data-value">{{ $penjualan->produk->nama_produk }}</span>
        </div>
        <div class="data-item">
            <span class="data-label">Periode:</span>
            <span class="data-value">
                {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$penjualan->bulan] }} {{ $penjualan->tahun }}
            </span>
        </div>
        <div class="data-item">
            <span class="data-label">Jumlah Penjualan:</span>
            <span class="data-value">{{ number_format($penjualan->jumlah_penjualan) }} unit</span>
        </div>
        <div class="data-item">
            <span class="data-label">Terakhir Diupdate:</span>
            <span class="data-value">{{ $penjualan->updated_at->format('d M Y H:i') }}</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="modern-card">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h4 style="color: var(--text-accent) !important; margin-bottom: 1.5rem; text-align: center;">
                    ✏️ Edit Data Penjualan
                </h4>
                
                <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="produk_id" class="form-label">
                            <span style="color: #fa709a;">*</span> Pilih Produk
                        </label>
                        <select class="form-control @error('produk_id') is-invalid @enderror" 
                                id="produk_id" 
                                name="produk_id" 
                                required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}" 
                                        {{ (old('produk_id', $penjualan->produk_id) == $produk->id) ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                        @error('produk_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulan" class="form-label">
                                    <span style="color: #fa709a;">*</span> Bulan
                                </label>
                                <select class="form-control @error('bulan') is-invalid @enderror" 
                                        id="bulan" 
                                        name="bulan" 
                                        required>
                                    <option value="">-- Pilih Bulan --</option>
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $key => $bulan)
                                        <option value="{{ $key + 1 }}" 
                                                {{ (old('bulan', $penjualan->bulan) == ($key + 1)) ? 'selected' : '' }}>
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
                                    <span style="color: #fa709a;">*</span> Tahun
                                </label>
                                <select class="form-control @error('tahun') is-invalid @enderror" 
                                        id="tahun" 
                                        name="tahun" 
                                        required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @for($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}" 
                                                {{ (old('tahun', $penjualan->tahun) == $year) ? 'selected' : '' }}>
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
                            <span style="color: #fa709a;">*</span> Jumlah Penjualan (Unit)
                        </label>
                        <input type="number" 
                               class="form-control @error('jumlah_penjualan') is-invalid @enderror" 
                               id="jumlah_penjualan" 
                               name="jumlah_penjualan" 
                               value="{{ old('jumlah_penjualan', $penjualan->jumlah_penjualan) }}"
                               placeholder="Contoh: 150"
                               min="0"
                               required>
                        @error('jumlah_penjualan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info-card">
                        <h6>⚠️ Perhatian Saat Edit Data:</h6>
                        <ul style="color: var(--text-primary) !important; margin: 0; padding-left: 1.5rem;">
                            <li>Pastikan tidak ada duplikasi periode untuk produk yang sama</li>
                            <li>Perubahan data akan mempengaruhi hasil analisis prediksi</li>
                            <li>Data yang sudah diubah tidak dapat dikembalikan secara otomatis</li>
                            <li>Backup data penting sebelum melakukan perubahan besar</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-3 justify-content-center btn-group">
                        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                            ← Batal
                        </a>
                        <button type="submit" class="btn btn-warning">
                            💾 Update Data Penjualan
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
        
        // Konfirmasi update
        if (!confirm('Apakah Anda yakin ingin mengupdate data penjualan ini?')) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Mengupdate...';
        submitBtn.disabled = true;
    });
    
    // Highlight changes
    const originalValues = {
        produk_id: '{{ $penjualan->produk_id }}',
        bulan: '{{ $penjualan->bulan }}',
        tahun: '{{ $penjualan->tahun }}',
        jumlah_penjualan: '{{ $penjualan->jumlah_penjualan }}'
    };
    
    function checkChanges() {
        const fields = ['produk_id', 'bulan', 'tahun', 'jumlah_penjualan'];
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
    ['produk_id', 'bulan', 'tahun', 'jumlah_penjualan'].forEach(field => {
        document.getElementById(field).addEventListener('change', checkChanges);
        document.getElementById(field).addEventListener('input', checkChanges);
    });
});
</script>
@endsection
