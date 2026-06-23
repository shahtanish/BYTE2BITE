@extends('layouts.app')
@section('title','My Orders')
@section('banner')
<div style="background:#f8f8f8;padding:30px 0;border-bottom:1px solid #eee;">
    <div class="container"><h3 style="color:#252525;font-weight:700;margin:0;"><i class="fas fa-clipboard-list mr-2" style="color:#e42e0c;"></i>My Orders</h3></div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;min-height:60vh;">
<div class="container">
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@forelse($orders as $order)
<div class="card mb-3" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
    <div class="card-body" style="padding:20px;">
        <div class="row align-items-center">
            <div class="col-md-3">
                <p style="margin:0;font-size:12px;color:#888;">Order Number</p>
                <h6 style="font-weight:700;color:#e42e0c;margin:0;">{{ $order->order_number }}</h6>
                <small style="color:#aaa;">{{ $order->created_at->format('d M Y, h:i A') }}</small>
            </div>
            <div class="col-md-3">
                <p style="margin:0;font-size:12px;color:#888;">Items</p>
                @foreach($order->items->groupBy('restaurant_id') as $rid => $items)
                <p style="margin:0;font-size:13px;color:#555;">{{ $items->first()->restaurant->name ?? '' }}: {{ $items->count() }} item(s)</p>
                @endforeach
            </div>
            <div class="col-md-2">
                <p style="margin:0;font-size:12px;color:#888;">Total</p>
                <h6 style="font-weight:700;color:#252525;margin:0;">₹{{ number_format($order->total,2) }}</h6>
                <small style="color:#888;font-size:11px;">{{ strtoupper($order->payment_method) }}</small>
            </div>
            <div class="col-md-2">
                {!! $order->status_badge !!}
            </div>
            <div class="col-md-2 text-right">
                <a href="{{ route('customer.orders.show',$order->id) }}" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:6px 14px;font-size:12px;display:block;margin-bottom:5px;">View Details</a>
                @if(in_array($order->status,['on_the_way','picked_up','preparing','accepted']))
                <a href="{{ route('customer.orders.track',$order->id) }}" class="btn btn-sm" style="background:#252525;color:#fff;border:none;border-radius:5px;padding:6px 14px;font-size:12px;display:block;">Track Order</a>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="fas fa-clipboard-list" style="font-size:60px;color:#ddd;margin-bottom:20px;display:block;"></i>
    <h5 style="color:#888;">No orders yet</h5>
    <a href="{{ route('restaurants') }}" class="btn mt-3" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:10px 30px;">Order Now</a>
</div>
@endforelse
{{ $orders->links() }}
</div>
</div>
@endsection
