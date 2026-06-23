@extends('layouts.delivery')
@section('title','Order Detail')
@section('page_title','Order Detail')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-receipt mr-2" style="color:#e42e0c;"></i>Order #{{ $order->order_number }}</span>
        <a href="{{ route('delivery.orders') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;font-size:12px;">← Back</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 style="font-weight:700;margin-bottom:12px;">Deliver To</h6>
                <p style="font-size:13px;font-weight:600;margin-bottom:3px;">{{ $order->delivery_name }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:3px;">{{ $order->delivery_phone }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:15px;">{{ $order->delivery_address }}, {{ $order->delivery_city }}</p>
                @if(!$order->delivery_partner_id)
                <form method="POST" action="{{ route('delivery.orders.pickup',$order->id) }}">@csrf
                    <button class="btn" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:8px 20px;font-size:13px;font-weight:600;"><i class="fas fa-motorcycle mr-2"></i>Pick Up This Order</button>
                </form>
                @elseif($order->delivery_partner_id === auth()->id() && $order->status !== 'delivered')
                <form method="POST" action="{{ route('delivery.orders.delivered',$order->id) }}">@csrf
                    <button class="btn" style="background:#4caf50;color:#fff;border:none;border-radius:5px;padding:8px 20px;font-size:13px;font-weight:600;"><i class="fas fa-check mr-2"></i>Mark Delivered</button>
                </form>
                @endif
            </div>
            <div class="col-md-6">
                <h6 style="font-weight:700;margin-bottom:12px;">Order Items</h6>
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                    <span>{{ $item->food_name }} × {{ $item->quantity }}</span>
                    <strong>₹{{ number_format($item->subtotal,2) }}</strong>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between" style="font-size:15px;font-weight:700;"><span>Total</span><span style="color:#e42e0c;">₹{{ number_format($order->total,2) }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
