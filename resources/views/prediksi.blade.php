@extends('layouts.app')

{{-- Override main content untuk prediksi --}}
@section('konten')
<main class="prediksi-main">

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* ===== MODERN DESIGN SYSTEM ===== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root {
    --primary-color: #6366f1;
    --primary-dark: #4f46e5;
    --secondary-color: #f1f5f9;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* ===== RESET & BASE ===== */
.prediksi-wrapper,
.prediksi-wrapper * {
    font-family: var(--font-family) !important;
    box-sizing: border-box;
}

main.prediksi-main {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
    min-height: 100vh !important;
    padding: 0 !important;
}

.prediksi-wrapper {
    min-height: 100vh;
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== MODERN CARDS ===== */
.modern-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.modern-card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 1.5rem;
    border: none;
}

.modern-card-header.success {
    background: linear-gradient(135deg, var(--success-color), #059669);
}

.modern-card-header.warning {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
}

.modern-card-header.danger {
    background: linear-gradient(135deg, var(--danger-color), #dc2626);
}

.modern-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modern-card-body {
    padding: 1.5rem;
}

/* ===== MODERN FORMS ===== */
.modern-form-group {
    margin-bottom: 1.25rem;
}

.modern-form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.modern-form-control,
.modern-form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
    color: var(--text-primary);
}

.modern-form-control:focus,
.modern-form-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
}

.modern-form-control::placeholder {
    color: var(--text-secondary);
}

/* ===== MODERN BUTTONS ===== */
.modern-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    justify-content: center;
}

.modern-btn-primary {
    background: var(--primary-color);
    color: white;
}

.modern-btn-primary:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-1px);
}

.modern-btn-success {
    background: var(--success-color);
    color: white;
}

.modern-btn-success:hover {
    background: #059669;
    color: white;
    transform: translateY(-1px);
}

.modern-btn-warning {
    background: var(--warning-color);
    color: white;
}

.modern-btn-warning:hover {
    background: #d97706;
    color: white;
    transform: translateY(-1px);
}

.modern-btn-danger {
    background: var(--danger-color);
    color: white;
}

.modern-btn-danger:hover {
    background: #dc2626;
    color: white;
    transform: translateY(-1px);
}

.modern-btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
}

/* ===== MODERN TABLE ===== */
.modern-table-container {
    background: white;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead th {
    background: var(--secondary-color);
    color: var(--text-primary);
    padding: 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.modern-table tbody td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    font-size: 0.875rem;
    color: var(--text-primary);
}

.modern-table tbody tr:hover {
    background: #f8fafc;
}

.modern-table tbody tr:last-child td {
    border-bottom: none;
}

/* ===== MODERN STATS CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.stats-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    text-align: center;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stats-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
}

.stats-icon.primary { background: rgb(99 102 241 / 0.1); color: var(--primary-color); }
.stats-icon.success { background: rgb(16 185 129 / 0.1); color: var(--success-color); }
.stats-icon.warning { background: rgb(245 158 11 / 0.1); color: var(--warning-color); }
.stats-icon.danger { background: rgb(239 68 68 / 0.1); color: var(--danger-color); }

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.stats-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
}

/* ===== MODERN ALERTS ===== */
.modern-alert {
    padding: 1rem 1.5rem;
    border-radius: var(--radius-md);
    margin: 1rem 0;
    border-left: 4px solid;
    font-size: 0.875rem;
}

.modern-alert-success {
    background: rgb(16 185 129 / 0.1);
    border-color: var(--success-color);
    color: #065f46;
}

.modern-alert-warning {
    background: rgb(245 158 11 / 0.1);
    border-color: var(--warning-color);
    color: #92400e;
}

.modern-alert-danger {
    background: rgb(239 68 68 / 0.1);
    border-color: var(--danger-color);
    color: #991b1b;
}

/* ===== MODERN MODAL ===== */
.modern-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modern-modal-content {
    background: white;
    margin: 5% auto;
    padding: 0;
    border-radius: var(--radius-lg);
    width: 90%;
    max-width: 500px;
    box-shadow: var(--shadow-lg);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}

.modern-modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modern-modal-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.modern-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-secondary);
    padding: 0;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.modern-modal-close:hover {
    background: var(--secondary-color);
    color: var(--text-primary);
}

.modern-modal-body {
    padding: 1.5rem;
}

/* ===== HEADER SECTION ===== */
.modern-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
}

.modern-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.modern-subtitle {
    font-size: 1.125rem;
    color: var(--text-secondary);
    font-weight: 400;
    line-height: 1.6;
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .prediksi-wrapper {
        padding: 1rem;
    }
    
    .modern-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modern-btn {
        width: 100%;
    }
}

/* ===== LOADING SPINNER ===== */
.loading-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ===== DELETE FORM STYLING ===== */
.delete-form {
    display: inline-block;
    margin: 0;
}

.delete-form button {
    border: none;
    background: none;
    padding: 0;
}

/* ===== DEBUG INFO ===== */
.debug-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    margin: 1rem 0;
    font-family: monospace;
    font-size: 0.875rem;
}
</style>
@endsection

<div class="prediksi-wrapper">
    <!-- Debug Info (hanya untuk development) -->
    <div class="debug-info" style="display: none;" id="debugInfo">
        <strong>Debug Information:</strong>
        <div id="debugContent"></div>
    </div>

    <!-- Modern Header -->
    <div class="modern-header">
        <h1 class="modern-title">🚀 Smart Sales Prediction</h1>
        <p class="modern-subtitle">Platform analisis prediksi penjualan dengan AI dan machine learning untuk mengoptimalkan strategi bisnis UMKM</p>
        
        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stats-card">
                <div class="stats-icon primary">📦</div>
                <div class="stats-value">{{ $sidebarStats['totalProduk'] ?? 0 }}</div>
                <div class="stats-label">Total Produk</div>
            </div>
            <div class="stats-card">
                <div class="stats-icon success">📊</div>
                <div class="stats-value">{{ $sidebarStats['totalData'] ?? 0 }}</div>
                <div class="stats-label">Data Penjualan</div>
            </div>
            <div class="stats-card">
                <div class="stats-icon warning">🎯</div>
                <div class="stats-value">{{ $sidebarStats['prediksiAktif'] ?? 0 }}</div>
                <div class="stats-label">Prediksi Aktif</div>
            </div>
            <div class="stats-card">
                <div class="stats-icon danger">⚡</div>
                <div class="stats-value">{{ $sidebarStats['akurasiRata'] ?? 0 }}%</div>
                <div class="stats-label">Akurasi Rata-rata</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="modern-alert modern-alert-success">
            <strong>✅ Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="modern-alert modern-alert-danger">
            <strong>❌ Error!</strong> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="modern-alert modern-alert-danger">
            <strong>❌ Error!</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Form Tambah Produk -->
        <div class="col-md-6 mb-4">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h5 class="modern-card-title">
                        <span>🏷️</span>
                        Tambah Produk Baru
                    </h5>
                </div>
                <div class="modern-card-body">
                    <form action="{{ route('prediksi.tambah-produk') }}" method="POST">
                        @csrf
                        <div class="modern-form-group">
                            <label for="nama_produk" class="modern-form-label">Nama Produk</label>
                            <input type="text" class="modern-form-control" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk..." required>
                        </div>
                        <button type="submit" class="modern-btn modern-btn-primary">
                            <span>➕</span>
                            Tambah Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form Tambah Data Penjualan -->
        <div class="col-md-6 mb-4">
            <div class="modern-card">
                <div class="modern-card-header success">
                    <h5 class="modern-card-title">
                        <span>📈</span>
                        Tambah Data Penjualan
                    </h5>
                </div>
                <div class="modern-card-body">
                    <form action="{{ route('prediksi.tambah-penjualan') }}" method="POST">
                        @csrf
                        <div class="modern-form-group">
                            <label for="produk_id" class="modern-form-label">Pilih Produk</label>
                            <select class="modern-form-select" id="produk_id" name="produk_id" required>
                                <option value="" selected disabled>-- Pilih Produk --</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label for="bulan" class="modern-form-label">Bulan</label>
                                    <select class="modern-form-select" id="bulan" name="bulan" required>
                                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $key => $bulan)
                                            <option value="{{ $key + 1 }}" {{ ($key + 1) == date('n') ? 'selected' : '' }}>{{ $bulan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label for="tahun" class="modern-form-label">Tahun</label>
                                    <input type="number" class="modern-form-control" id="tahun" name="tahun" value="{{ date('Y') }}" min="2000" max="2100" required>
                                </div>
                            </div>
                        </div>
                        <div class="modern-form-group">
                            <label for="jumlah_penjualan" class="modern-form-label">Jumlah Penjualan</label>
                            <input type="number" class="modern-form-control" id="jumlah_penjualan" name="jumlah_penjualan" min="0" placeholder="Masukkan jumlah penjualan..." required>
                        </div>
                        <button type="submit" class="modern-btn modern-btn-success">
                            <span>💾</span>
                            Simpan Data Penjualan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Produk untuk Prediksi -->
    @if($produks->count() > 0)
        <div class="modern-card mb-4">
            <div class="modern-card-header warning">
                <h5 class="modern-card-title">
                    <span>🔍</span>
                    Pilih Produk untuk Analisis Prediksi
                </h5>
            </div>
            <div class="modern-card-body text-center">
                <select class="modern-form-select" id="productFilter" style="max-width: 400px; margin: 0 auto;">
                    <option value="">-- Pilih Produk untuk Melihat Prediksi --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                    @endforeach
                </select>
                <p class="mt-3 text-muted">Pilih salah satu produk untuk melihat analisis prediksi dengan sistem kriteria bobot</p>
                
                <!-- Debug Button (hanya untuk development) -->
                <button type="button" class="modern-btn modern-btn-warning modern-btn-sm mt-2" onclick="showDebugInfo()">
                    🔧 Debug Data
                </button>
            </div>
        </div>
    @endif

    <!-- Container untuk Menampilkan Produk yang Dipilih -->
    <div id="selectedProductContainer" style="display: none;">
        <!-- Konten produk akan dimuat di sini via JavaScript -->
    </div>

    <!-- Pesan jika belum ada produk -->
    @if($produks->count() == 0)
        <div class="modern-card">
            <div class="modern-card-body text-center py-5">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🏪</div>
                <h5 class="mb-3">Belum ada produk yang terdaftar</h5>
                <p class="text-muted">Silakan tambahkan produk terlebih dahulu untuk memulai analisis prediksi.</p>
            </div>
        </div>
    @endif
</div>

<!-- Modal Edit Data Penjualan -->
<div id="editSalesModal" class="modern-modal">
    <div class="modern-modal-content">
        <div class="modern-modal-header">
            <h5 class="modern-modal-title">✏️ Edit Data Penjualan</h5>
            <button type="button" class="modern-modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <div class="modern-modal-body">
            <form id="editSalesForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modern-form-group">
                    <label class="modern-form-label">Produk</label>
                    <input type="text" class="modern-form-control" id="edit_produk_nama" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="modern-form-group">
                            <label for="edit_bulan" class="modern-form-label">Bulan</label>
                            <select class="modern-form-select" id="edit_bulan" name="bulan" required>
                                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $key => $bulan)
                                    <option value="{{ $key + 1 }}">{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modern-form-group">
                            <label for="edit_tahun" class="modern-form-label">Tahun</label>
                            <input type="number" class="modern-form-control" id="edit_tahun" name="tahun" min="2000" max="2100" required>
                        </div>
                    </div>
                </div>
                <div class="modern-form-group">
                    <label for="edit_jumlah_penjualan" class="modern-form-label">Jumlah Penjualan</label>
                    <input type="number" class="modern-form-control" id="edit_jumlah_penjualan" name="jumlah_penjualan" min="0" required>
                </div>
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="modern-btn modern-btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="modern-btn modern-btn-success">
                        <span class="loading-spinner" id="editLoadingSpinner" style="display: none;"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</main>
@endsection

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data produk untuk JavaScript dengan validasi ID
const produksData = {
    @foreach($produks as $produk)
        {{ $produk->id }}: {
            id: {{ $produk->id }},
            nama: "{{ addslashes($produk->nama_produk) }}",
            penjualan: [
                @foreach($produk->penjualan->sortBy(fn($item) => $item->tahun * 100 + $item->bulan) as $penjualan)
                    {
                        id: {{ $penjualan->id }},
                        bulan: {{ $penjualan->bulan }},
                        tahun: {{ $penjualan->tahun }},
                        jumlah: {{ $penjualan->jumlah_penjualan }}
                    },
                @endforeach
            ],
            prediksi: @if(isset($prediksiData[$produk->id])) {!! json_encode($prediksiData[$produk->id]) !!} @else null @endif
        },
    @endforeach
};

const bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

// Debug function untuk melihat data
function showDebugInfo() {
    console.log('=== DEBUG INFO ===');
    console.log('Produk Data:', produksData);
    
    fetch('/debug-data')
        .then(response => response.json())
        .then(data => {
            console.log('Database Data:', data);
            
            const debugInfo = document.getElementById('debugInfo');
            const debugContent = document.getElementById('debugContent');
            
            debugContent.innerHTML = `
                <div><strong>Produk di JavaScript:</strong> ${Object.keys(produksData).length}</div>
                <div><strong>Produk di Database:</strong> ${data.produks.length}</div>
                <div><strong>Penjualan di Database:</strong> ${data.penjualan.length}</div>
                <div style="margin-top: 1rem;"><strong>Detail Penjualan:</strong></div>
                <pre>${JSON.stringify(data.penjualan, null, 2)}</pre>
            `;
            
            debugInfo.style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching debug data:', error);
        });
}

// Handle product filter change
document.addEventListener('DOMContentLoaded', function() {
    const productFilter = document.getElementById('productFilter');
    const selectedProductContainer = document.getElementById('selectedProductContainer');
    
    if (productFilter) {
        productFilter.addEventListener('change', function() {
            const selectedProductId = this.value;
            
            if (selectedProductId && produksData[selectedProductId]) {
                showSelectedProduct(selectedProductId);
                selectedProductContainer.style.display = 'block';
                selectedProductContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                selectedProductContainer.style.display = 'none';
            }
        });
    }
});

function showSelectedProduct(productId) {
    const produk = produksData[productId];
    const container = document.getElementById('selectedProductContainer');
    
    if (!produk || !container) {
        console.error('Produk atau container tidak ditemukan:', productId);
        return;
    }
    
    console.log('Showing product:', produk);
    
    // Generate HTML untuk produk yang dipilih dengan desain modern
    let html = `
        <div class="modern-card mb-4">
            <div class="modern-card-header" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="modern-card-title">
                        <span>📦</span>
                        Analisis Prediksi: ${produk.nama}
                    </h5>
                    <button type="button" class="modern-btn modern-btn-danger modern-btn-sm" 
                            onclick="deleteProduk(${produk.id}, '${produk.nama.replace(/'/g, "\\'")}')">
                        <span>🗑️</span>
                        Hapus Produk
                    </button>
                </div>
            </div>
            
            <div class="modern-card-body">
    `;
    
    if (produk.penjualan.length === 0) {
        html += `
            <div class="text-center py-5">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📊</div>
                <h5 class="mb-3">Belum ada data penjualan</h5>
                <p class="text-muted">Silakan tambahkan data penjualan untuk memulai analisis prediksi.</p>
            </div>
        `;
    } else {
        // Data Penjualan Section dengan tombol edit dan hapus
        html += `
            <h6 style="color: #1e293b; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>📈</span>
                Data Penjualan Historis
            </h6>
            <div class="modern-table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Tahun</th>
                            <th>Jumlah Penjualan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        produk.penjualan.forEach((penjualan, index) => {
            console.log('Processing penjualan:', penjualan);
            html += `
                <tr>
                    <td><strong>${index + 1}</strong></td>
                    <td>${bulanNames[penjualan.bulan - 1]}</td>
                    <td>${penjualan.tahun}</td>
                    <td><strong>${penjualan.jumlah.toLocaleString()}</strong> unit</td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="modern-btn modern-btn-warning modern-btn-sm" 
                                    onclick="openEditModal(${penjualan.id}, '${produk.nama.replace(/'/g, "\\'")}', ${penjualan.bulan}, ${penjualan.tahun}, ${penjualan.jumlah})">
                                <span>✏️</span>
                                Edit
                            </button>
                            <button type="button" class="modern-btn modern-btn-danger modern-btn-sm" 
                                    onclick="deletePenjualan(${penjualan.id}, '${bulanNames[penjualan.bulan - 1]}', ${penjualan.tahun})">
                                <span>🗑️</span>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        // Chart dan prediksi sections
        html += `
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 2rem; margin: 2rem 0; box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);">
                <div style="color: #1e293b; font-weight: 600; font-size: 1.125rem; margin-bottom: 1.5rem; text-align: center; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <span>📊</span>
                    Grafik Tren Penjualan & Proyeksi
                </div>
                <canvas id="chart-produk-${produk.id}" width="400" height="200"></canvas>
            </div>
        `;
        
        // Bagian prediksi dengan desain modern
        if (produk.prediksi && produk.prediksi.dapat_prediksi) {
            const prediksiPeriode = produk.prediksi.periode_prediksi;
            const nextMonthName = prediksiPeriode.nama_bulan;
            const nextYear = prediksiPeriode.tahun;
            
            html += `
                <div style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 0.75rem; padding: 2rem; margin: 2rem 0; text-align: center;">
                    <h5 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">🎯 Hasil Prediksi</h5>
                    <div style="font-size: 2.5rem; font-weight: 700; margin: 1rem 0;">
                        ${produk.prediksi.prediksi_bulan_depan.toLocaleString()} unit
                    </div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">
                        Prediksi untuk ${nextMonthName} ${nextYear}
                    </div>
                </div>
            `;
        }
    }
    
    html += `
            </div>
        </div>
    `;
    
    container.innerHTML = html;
    
    // Render chart jika ada data penjualan
    if (produk.penjualan.length > 0) {
        setTimeout(() => renderChart(produk), 100);
    }
}

function renderChart(produk) {
    const canvas = document.getElementById(`chart-produk-${produk.id}`);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    const labels = produk.penjualan.map(p => `${bulanNames[p.bulan - 1]} ${p.tahun}`);
    const dataPenjualan = produk.penjualan.map(p => p.jumlah);
    
    const datasets = [
        {
            label: 'Penjualan Aktual',
            data: dataPenjualan,
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            borderWidth: 3,
            pointRadius: 6,
            pointBackgroundColor: '#6366f1',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 8,
            fill: true,
            tension: 0.4
        }
    ];

    if (produk.prediksi && produk.prediksi.dapat_prediksi) {
        const dataTren = [];
        const a = produk.prediksi.koefisien_a;
        const b = produk.prediksi.koefisien_b;
        
        for(let i = 0; i < produk.penjualan.length; i++) {
            dataTren.push(a + b * (i + 1));
        }
        
        const prediksiPeriode = produk.prediksi.periode_prediksi;
        labels.push(`${prediksiPeriode.nama_bulan} ${prediksiPeriode.tahun} (Prediksi)`);
        dataTren.push(produk.prediksi.prediksi_bulan_depan);
        
        datasets.push({
            label: 'Garis Tren & Prediksi',
            data: dataTren,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 3,
            borderDash: [8, 4],
            pointRadius: 6,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 8,
            fill: false,
            tension: 0.4
        });
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#1e293b',
                        font: {
                            family: 'Poppins, sans-serif',
                            size: 12,
                            weight: '500'
                        },
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#6366f1',
                    borderWidth: 1,
                    cornerRadius: 8,
                    titleFont: {
                        family: 'Poppins, sans-serif'
                    },
                    bodyFont: {
                        family: 'Poppins, sans-serif'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Penjualan (Unit)',
                        color: '#1e293b',
                        font: {
                            family: 'Poppins, sans-serif',
                            size: 12,
                            weight: '500'
                        }
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            family: 'Poppins, sans-serif',
                            size: 11
                        },
                        callback: function(value) {
                            return value.toLocaleString() + ' unit';
                        }
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Periode Waktu',
                        color: '#1e293b',
                        font: {
                            family: 'Poppins, sans-serif',
                            size: 12,
                            weight: '500'
                        }
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            family: 'Poppins, sans-serif',
                            size: 11
                        },
                        maxRotation: 45
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Modal functions
function openEditModal(id, produkNama, bulan, tahun, jumlah) {
    console.log('Opening edit modal for sales ID:', id);
    
    document.getElementById('edit_produk_nama').value = produkNama;
    document.getElementById('edit_bulan').value = bulan;
    document.getElementById('edit_tahun').value = tahun;
    document.getElementById('edit_jumlah_penjualan').value = jumlah;
    
    const form = document.getElementById('editSalesForm');
    form.action = `/penjualan/${id}`;
    
    document.getElementById('editSalesModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editSalesModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editSalesModal');
    if (event.target == modal) {
        closeEditModal();
    }
}

// Handle edit form submission
document.getElementById('editSalesForm').addEventListener('submit', function(e) {
    const spinner = document.getElementById('editLoadingSpinner');
    spinner.style.display = 'inline-block';
});

// Function untuk mendapatkan CSRF token yang fresh
function getCsrfToken() {
    // Coba dari meta tag dulu
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Jika tidak ada, coba dari input hidden
    if (!token) {
        token = document.querySelector('input[name="_token"]')?.value;
    }
    
    // Jika masih tidak ada, coba dari Laravel global
    if (!token && typeof window.Laravel !== 'undefined' && window.Laravel.csrfToken) {
        token = window.Laravel.csrfToken;
    }
    
    return token || '';
}

// Update semua form dengan CSRF token yang fresh
function updateCsrfTokens() {
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = csrfToken;
        });
    }
}

// ===== FUNGSI HAPUS DENGAN AJAX - DIPERBAIKI =====

function deletePenjualan(id, bulan, tahun) {
    console.log('=== DELETE PENJUALAN FUNCTION CALLED ===');
    console.log('ID:', id, 'Type:', typeof id);
    console.log('Bulan:', bulan, 'Tahun:', tahun);
    
    // Validasi ID
    if (!id || id === 'undefined' || id === 'null') {
        console.error('Invalid sales ID:', id);
        alert('ID penjualan tidak valid!');
        return;
    }
    
    // Konversi ke number untuk memastikan
    const salesId = parseInt(id);
    if (isNaN(salesId) || salesId <= 0) {
        console.error('Invalid sales ID after parsing:', salesId);
        alert('ID penjualan tidak valid!');
        return;
    }
    
    if (!confirm(`Apakah Anda yakin ingin menghapus data penjualan ${bulan} ${tahun}?`)) {
        return;
    }
    
    console.log('Attempting to delete sales data ID:', salesId);
    
    const csrfToken = getCsrfToken();
    
    if (!csrfToken) {
        alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    console.log('CSRF Token:', csrfToken);
    
    // Kirim request DELETE dengan fetch
    fetch(`/penjualan/${salesId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        return response.text().then(text => {
            console.log('Response text:', text);
            
            try {
                const data = JSON.parse(text);
                return { data, status: response.status, ok: response.ok };
            } catch (e) {
                return { data: { message: text }, status: response.status, ok: response.ok };
            }
        });
    })
    .then(({ data, status, ok }) => {
        if (ok) {
            console.log('Delete successful:', data);
            alert(data.message || 'Data penjualan berhasil dihapus!');
            // Redirect ke halaman prediksi untuk refresh data
            window.location.href = '/prediksi';
        } else {
            console.error('Delete failed:', data);
            alert(data.message || `Error ${status}: Gagal menghapus data penjualan`);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Gagal menghapus data penjualan: ' + error.message);
    });
}

function deleteProduk(id, nama) {
    console.log('=== DELETE PRODUK FUNCTION CALLED ===');
    console.log('ID:', id, 'Type:', typeof id);
    console.log('Nama:', nama);
    
    // Validasi ID
    if (!id || id === 'undefined' || id === 'null') {
        console.error('Invalid product ID:', id);
        alert('ID produk tidak valid!');
        return;
    }
    
    // Konversi ke number untuk memastikan
    const productId = parseInt(id);
    if (isNaN(productId) || productId <= 0) {
        console.error('Invalid product ID after parsing:', productId);
        alert('ID produk tidak valid!');
        return;
    }
    
    if (!confirm(`Apakah Anda yakin ingin menghapus produk "${nama}" beserta semua data penjualannya?`)) {
        return;
    }
    
    console.log('Attempting to delete product ID:', productId);
    
    const csrfToken = getCsrfToken();
    
    if (!csrfToken) {
        alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    console.log('CSRF Token:', csrfToken);
    
    // Kirim request DELETE dengan fetch
    fetch(`/produk/${productId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        return response.text().then(text => {
            console.log('Response text:', text);
            
            try {
                const data = JSON.parse(text);
                return { data, status: response.status, ok: response.ok };
            } catch (e) {
                return { data: { message: text }, status: response.status, ok: response.ok };
            }
        });
    })
    .then(({ data, status, ok }) => {
        if (ok) {
            console.log('Delete successful:', data);
            alert(data.message || 'Produk berhasil dihapus!');
            // Redirect ke halaman prediksi untuk refresh data
            window.location.href = '/prediksi';
        } else {
            console.error('Delete failed:', data);
            alert(data.message || `Error ${status}: Gagal menghapus produk`);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Gagal menghapus produk: ' + error.message);
    });
}

// Panggil update CSRF token setiap kali halaman di-load
document.addEventListener('DOMContentLoaded', function() {
    updateCsrfTokens();
    console.log('Page loaded, CSRF tokens updated');
    console.log('Available products:', Object.keys(produksData));
});
</script>
