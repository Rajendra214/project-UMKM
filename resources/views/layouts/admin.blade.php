<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - UMKM Prediction</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('styles')
    
    <style>
        /* Use the same design system from the main app.blade.php */
        :root {
            --primary-50: #f0f9ff;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --secondary-500: #d946ef;
            --success-500: #22c55e;
            --warning-500: #f59e0b;
            --danger-500: #ef4444;
            --dark-bg-primary: #0f172a;
            --dark-bg-secondary: #1e293b;
            --dark-surface: rgba(255, 255, 255, 0.05);
            --dark-border: rgba(255, 255, 255, 0.1);
            --dark-text-primary: #f8fafc;
            --dark-text-secondary: #cbd5e1;
            --dark-text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg-primary) 0%, var(--dark-bg-secondary) 100%);
            color: var(--dark-text-primary);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(14, 165, 233, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(217, 70, 239, 0.15) 0%, transparent 50%);
            animation: backgroundFloat 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes backgroundFloat {
            0%, 100% { transform: translateX(0) translateY(0); }
            33% { transform: translateX(-30px) translateY(-20px); }
            66% { transform: translateX(30px) translateY(20px); }
        }

        /* Admin Sidebar */
        .admin-sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--dark-border);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 100;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.2);
        }

        .admin-brand {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--dark-border);
        }

        .admin-brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--danger-500), #dc2626);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3);
            animation: adminFloat 3s ease-in-out infinite;
        }

        @keyframes adminFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }

        .admin-brand h4 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark-text-primary);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--danger-500), #dc2626);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .admin-brand-subtitle {
            font-size: 0.875rem;
            color: var(--dark-text-secondary);
            font-weight: 500;
        }

        .admin-user-info {
            background: var(--dark-surface);
            border: 1px solid var(--dark-border);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        .admin-user-details h6 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-text-primary);
            margin: 0;
        }

        .admin-user-details small {
            font-size: 0.75rem;
            color: var(--dark-text-muted);
        }

        .admin-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-nav-section {
            margin: 1.5rem 0 0.75rem 0;
        }

        .admin-nav-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--dark-text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 1rem;
            margin-bottom: 0.75rem;
        }

        .admin-nav-item {
            margin: 0.25rem 0;
        }

        .admin-nav-link {
            text-decoration: none;
            color: var(--dark-text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .admin-nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--danger-500), #dc2626);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .admin-nav-link:hover {
            color: var(--dark-text-primary);
            transform: translateX(4px);
        }

        .admin-nav-link:hover::before {
            left: 0;
        }

        .admin-nav-link.active {
            background: linear-gradient(135deg, var(--danger-500), #dc2626);
            color: var(--dark-text-primary);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .admin-nav-icon {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-logout {
            margin-top: auto;
            padding-top: 1.5rem;
        }

        .admin-logout-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--danger-500), #dc2626);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .admin-logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .admin-header {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--dark-border);
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--dark-text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-content {
            flex: 1;
            padding: 2rem;
            animation: contentFadeIn 0.6s ease-out;
        }

        @keyframes contentFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cards */
        .admin-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid var(--dark-border);
            border-radius: 1.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .admin-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid var(--dark-border);
            border-radius: 1.5rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark-text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--dark-text-secondary);
            font-weight: 500;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .stat-change.positive {
            color: var(--success-500);
        }

        .stat-change.negative {
            color: var(--danger-500);
        }

        /* Tables */
        .admin-table-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid var(--dark-border);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .admin-table thead th {
            background: linear-gradient(135deg, var(--danger-500), #dc2626);
            color: white;
            font-weight: 700;
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--dark-border);
            color: var(--dark-text-primary);
            font-weight: 500;
        }

        .admin-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Buttons */
        .btn-admin {
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-admin-primary {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            color: white;
        }

        .btn-admin-danger {
            background: linear-gradient(135deg, var(--danger-500), #dc2626);
            color: white;
        }

        .btn-admin-success {
            background: linear-gradient(135deg, var(--success-500), #16a34a);
            color: white;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .admin-sidebar {
                width: 100%;
                padding: 1rem;
                border-right: none;
                border-bottom: 1px solid var(--dark-border);
            }
            
            .admin-content {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Admin Sidebar -->
    <nav class="admin-sidebar">
        <div class="admin-brand">
            <div class="admin-brand-icon">🛡️</div>
            <h4>Admin Panel</h4>
            <div class="admin-brand-subtitle">UMKM Prediction Control</div>
        </div>

        <div class="admin-user-info">
            <div class="admin-avatar">
                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
            </div>
            <div class="admin-user-details">
                <h6>{{ Auth::guard('admin')->user()->name }}</h6>
                <small>{{ ucfirst(Auth::guard('admin')->user()->role) }}</small>
            </div>
        </div>

        <ul class="admin-nav">
            <li class="admin-nav-item">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="admin-nav-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
            </li>

            <li class="admin-nav-section">
                <div class="admin-nav-title">User Management</div>
            </li>
            <li class="admin-nav-item">
                <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <div class="admin-nav-icon"><i class="fas fa-users"></i></div>
                    Manage Users
                </a>
            </li>

            <li class="admin-nav-section">
                <div class="admin-nav-title">Data Management</div>
            </li>
            <li class="admin-nav-item">
                <a href="{{ route('admin.sales.index') }}" class="admin-nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <div class="admin-nav-icon"><i class="fas fa-chart-line"></i></div>
                    Sales Data
                </a>
            </li>
            <li class="admin-nav-item">
                <a href="{{ route('admin.sales.analytics') }}" class="admin-nav-link {{ request()->routeIs('admin.sales.analytics') ? 'active' : '' }}">
                    <div class="admin-nav-icon"><i class="fas fa-analytics"></i></div>
                    Analytics
                </a>
            </li>
        </ul>

        <div class="admin-logout">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="admin-logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        <header class="admin-header">
            <h1>
                <span>@yield('page-icon', '🛡️')</span>
                @yield('page-title', 'Admin Dashboard')
            </h1>
            <div class="admin-header-actions">
                <span class="badge bg-danger">Admin Mode</span>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-external-link-alt me-1"></i>
                    User Portal
                </a>
            </div>
        </header>

        <section class="admin-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Enhanced navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.querySelector('.btn-close')) {
                        alert.querySelector('.btn-close').click();
                    }
                });
            }, 5000);

            // Confirm delete actions
            document.querySelectorAll('[data-confirm]').forEach(element => {
                element.addEventListener('click', function(e) {
                    if (!confirm(this.dataset.confirm)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
