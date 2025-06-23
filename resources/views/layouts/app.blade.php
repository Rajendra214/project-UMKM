<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UMKM Prediction Dashboard')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @yield('styles')
    
    <style>
        /* 🎨 MODERN CSS DESIGN SYSTEM */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            
            --dark-bg: #1a1a2e;
            --darker-bg: #16213e;
            --card-bg: rgba(255, 255, 255, 0.08);
            --card-border: rgba(255, 255, 255, 0.12);
            
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.8);
            --text-muted: rgba(255, 255, 255, 0.6);
            --text-accent: #667eea;
        }

        /* Reset dan Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif !important;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
            color: var(--text-primary) !important;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 87, 108, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(67, 233, 123, 0.1) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes backgroundMove {
            0%, 100% { transform: translateX(0) translateY(0) rotate(0deg); }
            33% { transform: translateX(-20px) translateY(-10px) rotate(1deg); }
            66% { transform: translateX(20px) translateY(10px) rotate(-1deg); }
        }

        /* 🎯 SIDEBAR NAVIGATION */
        nav {
            width: 320px;
            background: var(--card-bg) !important;
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--card-border);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
        }

        /* Brand Section */
        nav .brand {
            margin-bottom: 3rem;
            text-align: center;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--card-border);
        }

        nav .brand-icon {
            width: 90px;
            height: 90px;
            background: var(--primary-gradient);
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 3rem;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        nav h4 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary) !important;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        nav .brand-subtitle {
            font-size: 1rem;
            color: var(--text-secondary) !important;
            font-weight: 400;
            text-align: center;
        }

        /* Navigation Sections */
        .nav-section {
            margin: 2rem 0 0.8rem 0;
        }

        .nav-section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-accent) !important;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 0 1.5rem;
            margin-bottom: 0.8rem;
        }

        /* Navigation Menu */
        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            margin: 0.5rem 0;
        }

        nav ul li a {
            text-decoration: none;
            color: var(--text-secondary) !important;
            font-size: 1rem;
            font-weight: 500;
            padding: 1rem 1.5rem;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        nav ul li a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            transition: left 0.3s ease;
            z-index: -1;
        }

        nav ul li a:hover {
            color: var(--text-primary) !important;
            transform: translateX(8px);
        }

        nav ul li a:hover::before {
            left: 0;
        }

        nav ul li a.active {
            background: var(--primary-gradient);
            color: var(--text-primary) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        nav ul li a .nav-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Quick Stats */
        .quick-stats {
            background: var(--card-bg) !important;
            border-radius: 15px;
            padding: 1.8rem;
            margin: 2rem 0;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
        }

        .quick-stats h6 {
            color: var(--text-primary) !important;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .stat-label {
            color: var(--text-secondary) !important;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .stat-value {
            color: var(--text-accent) !important;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Logout Button */
        .logout-section {
            margin-top: 2rem;
        }

        .logout-btn {
            width: 100%;
            padding: 1.2rem 1.5rem;
            background: var(--danger-gradient);
            border: none;
            border-radius: 15px;
            color: var(--text-primary) !important;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(250, 112, 154, 0.4);
            color: var(--text-primary) !important;
        }

        /* 🎯 MAIN CONTENT AREA */
        main {
            flex: 1;
            padding: 0;
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Section */
        .main-header {
            background: var(--card-bg) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--card-border);
            padding: 2.5rem 3rem;
            margin: 0;
            position: relative;
        }

        .main-header h2 {
            font-weight: 700;
            font-size: 2.2rem;
            color: var(--text-primary) !important;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary) !important;
            font-weight: 400;
            margin-top: 0.8rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 1.5rem 0 0 0;
        }

        .breadcrumb-item a {
            color: var(--text-secondary) !important;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item a:hover {
            color: var(--text-accent) !important;
        }

        .breadcrumb-item.active {
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--text-muted) !important;
        }

        /* Content Section */
        .main-content {
            flex: 1;
            padding: 0;
            position: relative;
        }

        /* 🎨 MODERN CARDS */
        .modern-card {
            background: var(--card-bg) !important;
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        /* 🎯 ALERT MESSAGES */
        .alert {
            border: none;
            border-radius: 15px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            color: var(--text-primary) !important;
            font-weight: 500;
            border-left: 5px solid;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(67, 233, 123, 0.15), rgba(56, 249, 215, 0.15)) !important;
            border-left-color: #43e97b;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(250, 112, 154, 0.15), rgba(254, 225, 64, 0.15)) !important;
            border-left-color: #fa709a;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 159, 67, 0.15), rgba(255, 206, 84, 0.15)) !important;
            border-left-color: #ff9f43;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.15), rgba(0, 242, 254, 0.15)) !important;
            border-left-color: #4facfe;
        }

        /* 🎯 FORM ELEMENTS */
        .form-label {
            color: var(--text-primary) !important;
            font-weight: 600;
            margin-bottom: 0.8rem;
            display: block;
            font-size: 1rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 2px solid var(--card-border);
            border-radius: 12px;
            color: var(--text-primary) !important;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: var(--text-accent);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            color: var(--text-primary) !important;
        }

        .form-control::placeholder {
            color: var(--text-muted) !important;
        }

        .form-control option {
            background: var(--darker-bg) !important;
            color: var(--text-primary) !important;
        }

        /* 🎯 BUTTONS */
        .btn {
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-primary) !important;
            padding: 0.8rem 2rem;
            font-size: 1rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary-gradient) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            color: var(--text-primary) !important;
        }

        .btn-success {
            background: var(--success-gradient) !important;
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(79, 172, 254, 0.4);
            color: var(--text-primary) !important;
        }

        .btn-warning {
            background: var(--warning-gradient) !important;
            box-shadow: 0 8px 25px rgba(67, 233, 123, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(67, 233, 123, 0.4);
            color: var(--text-primary) !important;
        }

        .btn-danger {
            background: var(--danger-gradient) !important;
            box-shadow: 0 8px 25px rgba(250, 112, 154, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(250, 112, 154, 0.4);
            color: var(--text-primary) !important;
        }

        .btn-secondary {
            background: var(--card-bg) !important;
            border: 2px solid var(--card-border);
            color: var(--text-secondary) !important;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: var(--text-accent);
            color: var(--text-primary) !important;
            transform: translateY(-2px);
        }

        /* 🎯 TABLE ELEMENTS */
        .table-container {
            background: var(--card-bg) !important;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(20px);
        }

        .table {
            margin: 0;
            color: var(--text-primary) !important;
        }

        .table thead th {
            background: var(--primary-gradient) !important;
            border: none;
            color: var(--text-primary) !important;
            font-weight: 600;
            padding: 1.5rem;
            font-size: 1rem;
        }

        .table tbody td {
            border: none;
            border-bottom: 1px solid var(--card-border);
            padding: 1.5rem;
            vertical-align: middle;
            color: var(--text-secondary) !important;
            font-weight: 500;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        /* 🎯 PAGINATION */
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .page-link {
            background: var(--card-bg) !important;
            border: 1px solid var(--card-border);
            color: var(--text-secondary) !important;
            margin: 0 0.3rem;
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            font-weight: 500;
        }

        .page-link:hover {
            background: var(--primary-gradient) !important;
            border-color: transparent;
            color: var(--text-primary) !important;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: var(--primary-gradient) !important;
            border-color: transparent;
            color: var(--text-primary) !important;
        }

        /* 🎯 BADGES */
        .badge {
            color: var(--text-primary) !important;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
        }

        /* 🎯 RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            nav {
                width: 100%;
                padding: 1.5rem;
                border-right: none;
                border-bottom: 1px solid var(--card-border);
            }
            
            nav .brand {
                margin-bottom: 1.5rem;
                padding-bottom: 1.5rem;
            }
            
            nav .brand-icon {
                width: 70px;
                height: 70px;
                font-size: 2.5rem;
            }
            
            nav h4 {
                font-size: 1.8rem;
            }
            
            nav ul {
                display: flex;
                gap: 0.8rem;
                overflow-x: auto;
                padding: 1rem 0;
            }
            
            nav ul li {
                margin: 0;
                min-width: max-content;
            }
            
            nav ul li a {
                padding: 1rem 1.5rem;
                font-size: 0.95rem;
            }
            
            .quick-stats {
                margin: 1.5rem 0;
                padding: 1.5rem;
            }
            
            .main-header {
                padding: 2rem;
            }
            
            .main-header h2 {
                font-size: 1.8rem;
            }
            
            .modern-card {
                padding: 2rem;
            }
        }

        /* 🎯 LOADING ANIMATIONS */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content {
            animation: fadeInUp 0.6s ease-out;
        }

        /* 🎯 SCROLLBAR STYLING */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--card-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-accent);
        }

        /* 🎯 INVALID FEEDBACK */
        .invalid-feedback {
            color: #fa709a !important;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .form-control.is-invalid {
            border-color: #fa709a !important;
        }

        /* 🎯 SMALL TEXT */
        small {
            color: var(--text-muted) !important;
            font-weight: 500;
        }

        /* 🎯 STRONG TEXT */
        strong {
            color: var(--text-primary) !important;
            font-weight: 700;
        }

        /* 🎯 EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted) !important;
        }

        .empty-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            display: block;
            opacity: 0.7;
        }
    </style>
</head>

<body>
<!-- Sidebar Navigation -->
<nav>
    <div>
        <div class="brand">
            <div class="brand-icon">📊</div>
            <h4>UMKM Prediction</h4>
            <div class="brand-subtitle">Smart Analytics Dashboard</div>
        </div>
        
        <ul>
            <li><a href="{{ route('dashboard') }}" class="nav-link">
                <div class="nav-icon">🏠</div>
                Dashboard
            </a></li>
            
            <!-- Manajemen Data -->
            <li class="nav-section">
                <div class="nav-section-title">Manajemen Data</div>
            </li>
            <li><a href="{{ route('produk.index') }}" class="nav-link">
                <div class="nav-icon">📦</div>
                Kelola Produk
            </a></li>
            <li><a href="{{ route('penjualan.index') }}" class="nav-link">
                <div class="nav-icon">📊</div>
                Data Penjualan
            </a></li>
            
            <!-- Analisis & Prediksi -->
            <li class="nav-section">
                <div class="nav-section-title">Analisis & Prediksi</div>
            </li>
            <li><a href="{{ route('analisis.index') }}" class="nav-link">
                <div class="nav-icon">🎯</div>
                Analisis Prediksi
            </a></li>
            <li><a href="{{ route('laporan.index') }}" class="nav-link">
                <div class="nav-icon">📈</div>
                Laporan & Grafik
            </a></li>
        </ul>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <h6>Ringkasan Sistem</h6>
            <div class="stat-item">
                <span class="stat-label">Total Produk</span>
                <span class="stat-value">{{ $sidebarStats['totalProduk'] ?? '0' }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Data Penjualan</span>
                <span class="stat-value">{{ $sidebarStats['totalData'] ?? '0' }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Prediksi Aktif</span>
                <span class="stat-value">{{ $sidebarStats['prediksiAktif'] ?? '0' }}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Akurasi Rata-rata</span>
                <span class="stat-value">{{ $sidebarStats['akurasiRata'] ?? '0' }}%</span>
            </div>
        </div>
    </div>

    <!-- Form logout -->
    <div class="logout-section">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="logout-btn">
                <span>🚪</span>
                Keluar Sistem
            </button>
        </form>
    </div>
</nav>

<!-- Main Content Area -->
<main>
    <!-- Header -->
    <header class="main-header">
        <h2>
            <span>@yield('page-icon', '📊')</span>
            @yield('page-title', 'Dashboard Prediksi UMKM')
        </h2>
        <div class="header-subtitle">@yield('page-subtitle', 'Platform analisis data untuk prediksi tren penjualan')</div>
        
        <!-- Breadcrumb -->
        @if(isset($breadcrumbs))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
        @endif
    </header>
    
    <!-- Content -->
    <section class="main-content">
        <div class="content-wrapper">
            @yield('konten')
        </div>
    </section>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Add active class to current navigation item
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentPath.includes(href) && href !== '#') {
            link.classList.add('active');
        }
    });
});

// Real-time stats update via AJAX
function updateSidebarStats() {
    fetch('/api/sidebar-stats')
        .then(response => response.json())
        .then(data => {
            const statValues = document.querySelectorAll('.stat-value');
            if (statValues.length >= 4) {
                statValues[0].textContent = data.totalProduk;
                statValues[1].textContent = data.totalData;
                statValues[2].textContent = data.prediksiAktif;
                statValues[3].textContent = data.akurasiRata + '%';
            }
        })
        .catch(error => console.log('Stats update failed:', error));
}

// Update stats every 5 minutes
setInterval(updateSidebarStats, 300000);

// Smooth transitions for navigation
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        this.style.opacity = '0.7';
        setTimeout(() => {
            this.style.opacity = '1';
        }, 200);
    });
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key === 'd') {
        e.preventDefault();
        window.location.href = '{{ route("dashboard") }}';
    }
    if (e.altKey && e.key === 'p') {
        e.preventDefault();
        window.location.href = '{{ route("produk.index") }}';
    }
    if (e.altKey && e.key === 's') {
        e.preventDefault();
        window.location.href = '{{ route("penjualan.index") }}';
    }
    if (e.altKey && e.key === 'a') {
        e.preventDefault();
        window.location.href = '{{ route("analisis.index") }}';
    }
});
</script>

@yield('scripts')
</body>
</html>
