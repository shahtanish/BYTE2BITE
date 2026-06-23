@extends('layouts.delivery')
@section('title','My Deliveries')
@section('page_title','My Deliveries')
@section('content')

@if($active->count())
<div class="card mb-4">
    <div class="card-header" style="background:#e42e0c;color:#fff;border-radius:8px 8px 0 0;font-weight:700;"><i class="fas fa-motorcycle mr-2"></i>Active ({{ $active->count() }})</div>
    <div class="card-body" style="padding:0;">
        @foreach($active as $order)
        <div class="d-flex align-items-center justify-content-between p-3" style="border-bottom:1px solid #f8f8f8;">
            <div><strong style="color:#e42e0c;font-size:14px;">{{ $order->order_number }}</strong><p style="color:#555;font-size:13px;margin:3px 0;">{{ $order->delivery_name }} — {{ $order->delivery_phone }}</p><p style="color:#888;font-size:12px;margin:0;">{{ $order->delivery_address }}, {{ $order->delivery_city }}</p></div>
            <div class="text-right">
                <p style="font-weight:700;color:#252525;margin:0;">₹{{ number_format($order->total,2) }}</p>
                <form method="POST" action="{{ route('delivery.orders.delivered',$order->id) }}" class="mt-2">@csrf<button class="btn btn-sm" style="background:#4caf50;color:#fff;border:none;border-radius:5px;padding:6px 16px;font-weight:600;"><i class="fas fa-check mr-1"></i>Delivered</button></form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card mb-4">
    <div class="card-header"><i class="fas fa-box-open mr-2" style="color:#e42e0c;"></i>Available to Pick Up</div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr><th>Order #</th><th>Customer</th><th>Delivery Address</th><th>Total</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($available as $order)
            <tr>
                <td><strong style="color:#e42e0c;font-size:13px;">{{ $order->order_number }}</strong></td>
                <td style="font-size:13px;">{{ $order->delivery_name }}<br><small style="color:#888;">{{ $order->delivery_phone }}</small></td>
                <td style="font-size:13px;">{{ $order->delivery_city }}<br><small style="color:#888;">{{ Str::limit($order->delivery_address,35) }}</small></td>
                <td style="font-weight:700;font-size:13px;">₹{{ number_format($order->total,2) }}</td>
                <td><form method="POST" action="{{ route('delivery.orders.pickup',$order->id) }}">@csrf<button class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;font-size:12px;padding:5px 14px;font-weight:600;">Pick Up</button></form></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center" style="padding:25px;color:#888;">No orders available to pick up.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-check-circle mr-2" style="color:#e42e0c;"></i>Completed Deliveries</div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Delivered</th></tr></thead>
            <tbody>
            @forelse($completed as $order)
            <tr>
                <td><strong style="color:#e42e0c;font-size:13px;">{{ $order->order_number }}</strong></td>
                <td style="font-size:13px;">{{ $order->delivery_name }}</td>
                <td style="font-weight:700;font-size:13px;">₹{{ number_format($order->total,2) }}</td>
                <td style="font-size:12px;color:#888;">{{ $order->delivered_at ? $order->delivered_at->format('d M Y, h:i A') : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center" style="padding:25px;color:#888;">No completed deliveries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $completed->links() }}</div>
@endsection
