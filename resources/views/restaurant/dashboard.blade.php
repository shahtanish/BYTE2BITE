@extends('layouts.restaurant')
@section('title','Dashboard')
@section('page_title','Dashboard')
@section('content')

@if($restaurant->status === 'pending')
<div class="alert alert-warning" style="border-radius:8px;"><i class="fas fa-clock mr-2"></i><strong>Pending Approval</strong> — Your restaurant is under review. You'll be notified once approved.</div>
@elseif($restaurant->status === 'rejected')
<div class="alert alert-danger" style="border-radius:8px;"><i class="fas fa-times-circle mr-2"></i><strong>Application Rejected</strong> — Reason: {{ $restaurant->rejection_reason }}</div>
@endif

<div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="stat-card stat-red"><div class="stat-icon"><i class="fas fa-clipboard-list"></i></div><div><div class="stat-value">{{ $totalOrders }}</div><div class="stat-label">Total Orders</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-dark"><div class="stat-icon"><i class="fas fa-bell"></i></div><div><div class="stat-value">{{ $pendingOrders }}</div><div class="stat-label">Pending Orders</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-green"><div class="stat-icon"><i class="fas fa-rupee-sign"></i></div><div><div class="stat-value">₹{{ number_format($totalRevenue,0) }}</div><div class="stat-label">Total Revenue</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-blue"><div class="stat-icon"><i class="fas fa-utensils"></i></div><div><div class="stat-value">{{ $totalItems }}</div><div class="stat-label">Menu Items</div></div></div></div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-chart-bar mr-2" style="color:#e42e0c;"></i>Revenue (Last 7 Days)</div>
            <div class="card-body"><canvas id="weeklyChart" height="120"></canvas></div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clock mr-2" style="color:#e42e0c;"></i>Recent Orders</span>
                <a href="{{ route('restaurant.orders') }}" style="font-size:13px;color:#e42e0c;">View All</a>
            </div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Status</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                    @forelse($recentOrders as $rs)
                    <tr>
                        <td><strong style="color:#e42e0c;font-size:13px;">{{ $rs->order->order_number ?? '-' }}</strong></td>
                        <td style="font-size:13px;">{{ $rs->order->user->name ?? '-' }}</td>
                        <td style="font-size:13px;">{{ $rs->order->items->count() ?? 0 }}</td>
                        <td><span class="badge-status badge-{{ $rs->status }}">{{ ucfirst($rs->status) }}</span></td>
                        <td style="font-size:12px;color:#888;">{{ $rs->created_at->format('d M, h:i A') }}</td>
                        <td><a href="{{ route('restaurant.orders.show',$rs->order_id) }}" class="btn btn-sm btn-primary-b2b" style="border-radius:4px;font-size:11px;">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center" style="padding:20px;color:#888;">No orders yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border:none;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header"><i class="fas fa-store mr-2" style="color:#e42e0c;"></i>Restaurant Info</div>
            <div class="card-body">
                <img src="{{ $restaurant->logo_url }}" style="width:80px;height:80px;border-radius:10px;object-fit:cover;margin-bottom:15px;display:block;">
                <h6 style="font-weight:700;color:#252525;">{{ $restaurant->name }}</h6>
                <p style="font-size:13px;color:#888;margin-bottom:5px;"><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>{{ $restaurant->city }}</p>
                <p style="font-size:13px;color:#888;margin-bottom:5px;"><i class="fas fa-clock mr-2" style="color:#e42e0c;"></i>{{ $restaurant->opening_time }} - {{ $restaurant->closing_time }}</p>
                <p style="font-size:13px;color:#888;margin-bottom:15px;"><i class="fas fa-fire mr-2" style="color:#e42e0c;"></i>{{ $restaurant->avg_delivery_time }} min avg delivery</p>
                <div class="d-flex align-items-center justify-content-between">
                    <span style="font-size:13px;color:{{ $restaurant->is_open ? '#2e7d32' : '#e42e0c' }};font-weight:600;">{{ $restaurant->is_open ? '● Open' : '● Closed' }}</span>
                    <a href="{{ route('restaurant.profile') }}" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
<script>
new Chart(document.getElementById('weeklyChart'), {
    type:'bar',
    data:{
        labels:{!! json_encode(array_column($weeklyRevenue,'date')) !!},
        datasets:[{label:'Revenue (₹)',data:{!! json_encode(array_column($weeklyRevenue,'revenue')) !!},backgroundColor:'rgba(228,46,12,0.8)',borderRadius:4}]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'₹'+v}}}}
});
</script>
@endpush
