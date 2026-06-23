@extends('layouts.admin')
@section('title','Dashboard')
@section('page_title','Dashboard')
@section('content')

<!-- Stat Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-red">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div><div class="stat-value">{{ number_format($totalOrders) }}</div><div class="stat-label">Total Orders</div></div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-dark">
            <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
            <div><div class="stat-value">₹{{ number_format($totalRevenue,0) }}</div><div class="stat-label">Total Revenue</div></div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="fas fa-store"></i></div>
            <div><div class="stat-value">{{ number_format($totalRestaurants) }}</div><div class="stat-label">Restaurants</div></div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div><div class="stat-value">{{ number_format($totalCustomers) }}</div><div class="stat-label">Customers</div></div>
        </div>
    </div>
</div>

<!-- Pending Approvals Alert -->
@if($pendingRestaurants > 0 || $pendingDelivery > 0)
<div class="alert alert-warning d-flex align-items-center mb-4" style="border-radius:8px;">
    <i class="fas fa-exclamation-triangle mr-3" style="font-size:20px;"></i>
    <div>
        @if($pendingRestaurants > 0)<strong>{{ $pendingRestaurants }} restaurant(s)</strong> pending approval. <a href="{{ route('admin.restaurants.index',['status'=>'pending']) }}" style="color:#e65100;">Review now</a>@endif
        @if($pendingRestaurants > 0 && $pendingDelivery > 0) &nbsp;|&nbsp; @endif
        @if($pendingDelivery > 0)<strong>{{ $pendingDelivery }} delivery partner(s)</strong> pending approval. <a href="{{ route('admin.delivery-partners.index') }}" style="color:#e65100;">Review now</a>@endif
    </div>
</div>
@endif

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-line mr-2" style="color:#e42e0c;"></i>Monthly Revenue</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Order Status Pie -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-pie mr-2" style="color:#e42e0c;"></i>Order Status</div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
                <div class="mt-3">
                    @foreach($orderStatusCounts as $s => $c)
                    <div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                        <span style="color:#555;">{{ ucwords(str_replace('_',' ',$s)) }}</span>
                        <strong>{{ $c }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clock mr-2" style="color:#e42e0c;"></i>Recent Orders</span>
        <a href="{{ route('admin.orders') }}" style="font-size:13px;color:#e42e0c;">View All</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Order #</th><th>Customer</th><th>Restaurants</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td><strong style="color:#e42e0c;">{{ $order->order_number }}</strong></td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $order->items->pluck('restaurant.name')->filter()->unique()->implode(', ') }}</td>
                <td><strong>₹{{ number_format($order->total,2) }}</strong></td>
                <td><span style="font-size:11px;text-transform:uppercase;background:{{ $order->payment_status==='paid' ? '#e8f5e9' : '#fff3e0' }};color:{{ $order->payment_status==='paid' ? '#2e7d32' : '#e65100' }};padding:2px 8px;border-radius:3px;">{{ $order->payment_method }}</span></td>
                <td>{!! $order->status_badge !!}</td>
                <td style="font-size:12px;color:#888;">{{ $order->created_at->format('d M, h:i A') }}</td>
                <td><a href="{{ route('admin.orders.show',$order->id) }}" class="btn btn-sm btn-primary-b2b">View</a></td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center" style="padding:30px;color:#888;">No orders yet.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
<script>
var months  = {!! json_encode(array_column($monthlyRevenue,'month')) !!};
var revenue = {!! json_encode(array_column($monthlyRevenue,'revenue')) !!};
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: { labels: months, datasets: [{ label: 'Revenue (₹)', data: revenue, backgroundColor: 'rgba(228,46,12,0.8)', borderColor: '#e42e0c', borderWidth: 1, borderRadius: 4 }] },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { callback: v => '₹'+v } } } }
});

var statusLabels = {!! json_encode(array_keys($orderStatusCounts)) !!};
var statusData   = {!! json_encode(array_values($orderStatusCounts)) !!};
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: { labels: statusLabels.map(s=>s.charAt(0).toUpperCase()+s.slice(1).replace('_',' ')), datasets: [{ data: statusData, backgroundColor: ['#ff9800','#1976d2','#4caf50','#2e7d32','#e42e0c'] }] },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});
</script>
@endpush
