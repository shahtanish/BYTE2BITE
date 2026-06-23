@extends('layouts.delivery')
@section('title','Dashboard')
@section('page_title','Delivery Dashboard')
@section('content')

@if(!$user->is_approved)
<div class="alert alert-warning" style="border-radius:8px;"><i class="fas fa-clock mr-2"></i><strong>Pending Approval</strong> — Your account is under review. You'll be able to accept deliveries once approved.</div>
@endif

<div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="stat-card stat-red"><div class="stat-icon"><i class="fas fa-motorcycle"></i></div><div><div class="stat-value">{{ $totalDeliveries }}</div><div class="stat-label">Deliveries Done</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-green"><div class="stat-icon"><i class="fas fa-rupee-sign"></i></div><div><div class="stat-value">₹{{ number_format($totalEarnings,0) }}</div><div class="stat-label">Total Earnings</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-dark"><div class="stat-icon"><i class="fas fa-star"></i></div><div><div class="stat-value">{{ number_format($partner->rating ?? 0,1) }}</div><div class="stat-label">Rating</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-blue"><div class="stat-icon"><i class="fas fa-check-circle"></i></div><div><div class="stat-value">{{ $user->is_approved ? 'Active' : 'Pending' }}</div><div class="stat-label">Account Status</div></div></div></div>
</div>

<div class="row">
    <div class="col-md-7">
        @if($active->count())
        <div class="card mb-4">
            <div class="card-header" style="background:#e42e0c;color:#fff;border-radius:8px 8px 0 0;"><i class="fas fa-motorcycle mr-2"></i>Active Delivery</div>
            <div class="card-body">
                @foreach($active as $order)
                <div class="d-flex align-items-center justify-content-between">
                    <div><h6 style="font-weight:700;color:#252525;margin:0 0 3px;">Order #{{ $order->order_number }}</h6><p style="color:#888;font-size:13px;margin:0;">{{ $order->delivery_address }}, {{ $order->delivery_city }}</p><p style="color:#888;font-size:12px;margin:3px 0 0;">{{ $order->delivery_name }} — {{ $order->delivery_phone }}</p></div>
                    <div class="text-right">
                        <p style="font-weight:700;color:#e42e0c;font-size:16px;margin:0;">₹{{ number_format($order->total,2) }}</p>
                        <form method="POST" action="{{ route('delivery.orders.delivered',$order->id) }}" class="mt-2">@csrf<button class="btn btn-sm" style="background:#4caf50;color:#fff;border:none;border-radius:5px;padding:6px 14px;font-size:12px;font-weight:600;"><i class="fas fa-check mr-1"></i>Mark Delivered</button></form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Available Orders -->
        <div class="card">
            <div class="card-header"><i class="fas fa-box-open mr-2" style="color:#e42e0c;"></i>Available Orders to Pick Up</div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <thead><tr><th>Order #</th><th>Address</th><th>Amount</th><th>Action</th></tr></thead>
                    <tbody>
                    @forelse($pendingOrders as $order)
                    @if($user->is_approved)
                    <tr>
                        <td><strong style="color:#e42e0c;font-size:13px;">{{ $order->order_number }}</strong></td>
                        <td style="font-size:13px;">{{ $order->delivery_city }}<br><small style="color:#888;">{{ Str::limit($order->delivery_address,40) }}</small></td>
                        <td style="font-weight:700;font-size:13px;">₹{{ number_format($order->total,2) }}</td>
                        <td><form method="POST" action="{{ route('delivery.orders.pickup',$order->id) }}">@csrf<button class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:4px;font-size:12px;padding:5px 12px;font-weight:600;">Pick Up</button></form></td>
                    </tr>
                    @endif
                    @empty
                    <tr><td colspan="4" class="text-center" style="padding:25px;color:#888;">No orders available right now.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-clock mr-2" style="color:#e42e0c;"></i>Recent Deliveries</div>
            <div class="card-body" style="padding:0;">
                @forelse($myOrders as $order)
                <div class="d-flex justify-content-between align-items-center p-3" style="border-bottom:1px solid #f8f8f8;">
                    <div><p style="margin:0;font-size:13px;font-weight:600;color:#e42e0c;">{{ $order->order_number }}</p><p style="margin:0;font-size:12px;color:#888;">{{ $order->delivery_city }} — {{ $order->created_at->format('d M') }}</p></div>
                    <div class="text-right"><span style="font-weight:700;font-size:13px;">₹{{ number_format($order->total,2) }}</span><br><span class="badge-status badge-{{ $order->status }}" style="font-size:10px;">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span></div>
                </div>
                @empty
                <div class="text-center p-4"><p style="color:#888;font-size:13px;">No deliveries yet.</p></div>
                @endforelse
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-user mr-2" style="color:#e42e0c;"></i>My Info</div>
            <div class="card-body">
                <p style="font-size:13px;font-weight:600;margin-bottom:3px;">{{ $user->name }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:3px;">{{ $user->phone }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:8px;">{{ $user->email }}</p>
                @if($partner)<p style="font-size:13px;color:#555;margin-bottom:3px;"><i class="fas fa-motorcycle mr-2" style="color:#e42e0c;"></i>{{ $partner->vehicle_type }} — {{ $partner->vehicle_number }}</p>@endif
                <a href="{{ route('delivery.profile') }}" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;margin-top:5px;">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection
