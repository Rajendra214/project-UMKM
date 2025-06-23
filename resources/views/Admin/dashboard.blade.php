@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-icon', '📊')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-12">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary-500), var(--primary-600));">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $stats['new_users_this_month'] }} this month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--success-500), #16a34a);">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_sales']) }}</div>
                <div class="stat-label">Total Sales Records</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $stats['sales_today'] }} today
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning-500), #d97706);">
                    <i class="fas fa-box text-white"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
                <div class="stat-label">Total Products</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    Active products
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--secondary-500), #c026d3);">
                    <i class="fas fa-brain text-white"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['total_predictions']) }}</div>
                <div class="stat-label">Predictions Generated</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    AI-powered insights
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="col-lg-8">
        <div class="admin-card">
            <h5 class="mb-4">
                <i class="fas fa-chart-area me-2 text-primary"></i>
                Sales Trend (Last 6 Months)
            </h5>
            <canvas id="salesChart" height="300"></canvas>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-lg-4">
        <div class="admin-card">
            <h5 class="mb-4">
                <i class="fas fa-trophy me-2 text-warning"></i>
                Top Selling Products
            </h5>
            <div class="top-products-list">
                @foreach($topProducts as $index => $product)
                <div class="top-product-item d-flex align-items-center mb-3">
                    <div class="product-rank me-3">
                        <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                    </div>
                    <div class="product-info flex-grow-1">
                        <h6 class="mb-1">{{ $product->nama }}</h6>
                        <small class="text-muted">{{ number_format($product->total_sold ?? 0) }} sold</small>
                    </div>
                    <div class="product-price">
                        <strong>Rp {{ number_format($product->harga ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="col-lg-6">
        <div class="admin-card">
            <h5 class="mb-4">
                <i class="fas fa-user-plus me-2 text-success"></i>
                Recent Users
            </h5>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Business</th>
                            <th>Joined</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->business_name ?? 'Not specified' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="col-lg-6">
        <div class="admin-card">
            <h5 class="mb-4">
                <i class="fas fa-shopping-cart me-2 text-info"></i>
                Recent Sales
            </h5>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSales as $sale)
                        <tr>
                            <td>
                                <strong>{{ $sale->produk->nama ?? 'Unknown Product' }}</strong>
                            </td>
                            <td>{{ $sale->user->name ?? 'Unknown User' }}</td>
                            <td>
                                <strong>Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</strong><br>
                                <small class="text-muted">{{ $sale->jumlah }} units</small>
                            </td>
                            <td>{{ $sale->tanggal->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.875rem;
}

.top-product-item {
    padding: 0.75rem;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.top-product-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

.product-rank .badge {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesChart);
    
    const months = salesData.map(item => {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return monthNames[item.month - 1] + ' ' + item.year;
    });
    
    const quantities = salesData.map(item => item.total_quantity);
    const revenues = salesData.map(item => item.total_revenue);

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Quantity Sold',
                data: quantities,
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Revenue (Rp)',
                data: revenues,
                borderColor: '#d946ef',
                backgroundColor: 'rgba(217, 70, 239, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#f8fafc',
                        font: {
                            family: 'Inter',
                            weight: 600
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#cbd5e1',
                        font: {
                            family: 'Inter'
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        color: '#cbd5e1',
                        font: {
                            family: 'Inter'
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    ticks: {
                        color: '#cbd5e1',
                        font: {
                            family: 'Inter'
                        },
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        fetch('{{ route("admin.dashboard.stats") }}')
            .then(response => response.json())
            .then(data => {
                // Update real-time stats if needed
                console.log('Stats updated:', data);
            })
            .catch(error => console.log('Stats update failed:', error));
    }, 30000);
});
</script>
@endsection
