<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SalesController as AdminSalesController;

// Home routes
Route::get('/', function () {
    return view('Home');
});

Route::get('/login', function () {
    return view('login'); 
});

Route::get('/register', function () {
    return view('register'); 
});

// Auth routes
Route::post('/register/submit', [AuthController::class, 'register']);
Route::post('/login/submit', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// ===== ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Admin Auth Routes (Guest only)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });
    
    // Admin Protected Routes
    Route::middleware(['admin.auth'])->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'apiStats'])->name('dashboard.stats');
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::patch('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        });
        
        // Sales Management
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [AdminSalesController::class, 'index'])->name('index');
            Route::get('/analytics', [AdminSalesController::class, 'analytics'])->name('analytics');
            Route::get('/{sale}/edit', [AdminSalesController::class, 'edit'])->name('edit');
            Route::put('/{sale}', [AdminSalesController::class, 'update'])->name('update');
            Route::delete('/{sale}', [AdminSalesController::class, 'destroy'])->name('destroy');
        });
        
        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

// ===== MAIN APPLICATION ROUTES =====
Route::middleware(['web'])->group(function () {
    
    // 1. DASHBOARD UTAMA
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // 2. MANAJEMEN PRODUK
    Route::prefix('produk')->name('produk.')->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('index');
        Route::get('/create', [ProdukController::class, 'create'])->name('create');
        Route::post('/', [ProdukController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProdukController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('destroy');
    });
    
    // 3. MANAJEMEN PENJUALAN
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('index');
        Route::get('/create', [PenjualanController::class, 'create'])->name('create');
        Route::post('/', [PenjualanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PenjualanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PenjualanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenjualanController::class, 'destroy'])->name('destroy');
    });
    
    // 4. ANALISIS PREDIKSI
    Route::prefix('analisis')->name('analisis.')->group(function () {
        Route::get('/', [AnalisisController::class, 'index'])->name('index');
        Route::get('/produk/{id}', [AnalisisController::class, 'produk'])->name('produk');
        Route::post('/generate', [AnalisisController::class, 'generate'])->name('generate');
    });
    
    // 5. LAPORAN & GRAFIK
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/grafik/{id}', [LaporanController::class, 'grafik'])->name('grafik');
        Route::get('/export/{type}', [LaporanController::class, 'export'])->name('export');
    });
    
    // API routes untuk AJAX
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/sidebar-stats', [DashboardController::class, 'apiSidebarStats'])->name('sidebar-stats');
        Route::get('/produk/{id}/prediksi', [AnalisisController::class, 'apiPrediksi'])->name('prediksi');
        Route::get('/chart-data/{id}', [LaporanController::class, 'apiChartData'])->name('chart-data');
    });
    
    // 🔧 PERBAIKAN: Debug routes yang lebih lengkap
    Route::get('/debug-chart', [DashboardController::class, 'debugChartData'])->name('debug.chart');
    Route::get('/debug-trend-data', [LaporanController::class, 'debugTrendData'])->name('debug.trend');
    Route::get('/debug-laporan', [LaporanController::class, 'debugTrendData'])->name('debug.laporan');
    
    // Redirect route lama ke dashboard
    Route::get('/prediksi', function () {
        return redirect()->route('dashboard');
    });
});
