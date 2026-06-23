@extends('layouts.app')
@section('title','Order #'.$order->order_number)
@section('banner')
<div style="background:#f8f8f8;padding:30px 0;border-bottom:1px solid #eee;">
    <div class="container d-flex justify-content-between align-items-center">
        <h3 style="color:#252525;font-weight:700;margin:0;">Order #{{ $order->order_number }}</h3>
        <div>
            <a href="{{ route('customer.orders.invoice',$order->id) }}" class="btn btn-sm" style="background:#252525;color:#fff;border:none;border-radius:5px;padding:8px 15px;font-size:13px;margin-right:8px;" target="_blank"><i class="fas fa-file-invoice mr-1"></i>Invoice</a>
            @if(in_array($order->status,['on_the_way','picked_up','preparing','accepted']))
            <a href="{{ route('customer.orders.track',$order->id) }}" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:8px 15px;font-size:13px;"><i class="fas fa-map-marker-alt mr-1"></i>Track</a>
            @endif
        </div>
    </div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;">
<div class="container">
<div class="row">
    <div class="col-md-8">
        <!-- Order Status Timeline -->
        <div class="card mb-4" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;"><h6 style="font-weight:700;margin:0;">Order Status</h6></div>
            <div class="card-body" style="padding:25px;">
                @php $steps = ['pending'=>'Order Placed','accepted'=>'Accepted','preparing'=>'Preparing','on_the_way'=>'On The Way','delivered'=>'Delivered'];
                $statuses = array_keys($steps); $currentIdx = array_search($order->status, $statuses); if($currentIdx===false) $currentIdx=-1; @endphp
                <div class="d-flex justify-content-between" style="position:relative;">
                    <div style="position:absolute;top:15px;left:10%;right:10%;height:3px;background:#eee;z-index:0;"></div>
                    @foreach($steps as $s => $label)
                    @php $idx = array_search($s,$statuses); $done = $idx <= $currentIdx && $currentIdx>=0; @endphp
                    <div class="text-center" style="z-index:1;width:18%;">
                        <div style="width:32px;height:32px;border-radius:50%;background:{{ $done ? '#e42e0c' : '#eee' }};color:{{ $done ? '#fff' : '#aaa' }};display:flex;align-items:center;justify-content:center;margin:0 auto 8px;font-size:14px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <p style="font-size:11px;color:{{ $done ? '#e42e0c' : '#aaa' }};margin:0;font-weight:{{ $done ? '600' : '400' }};">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Items by Restaurant -->
        @foreach($order->items->groupBy('restaurant_id') as $rid => $items)
        <div class="card mb-3" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#252525;color:#fff;padding:12px 20px;border-radius:10px 10px 0 0;">
                <i class="fas fa-store mr-2" style="color:#e42e0c;"></i>{{ $items->first()->restaurant->name ?? 'Restaurant' }}
                @php $rst = $order->restaurantStatuses->firstWhere('restaurant_id',$rid); @endphp
                @if($rst)<span style="float:right;font-size:12px;background:rgba(255,255,255,0.2);padding:2px 10px;border-radius:10px;">{{ ucfirst($rst->status) }}</span>@endif
            </div>
            <div class="card-body" style="padding:20px;">
                @foreach($items as $item)
                <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom:1px solid #f8f8f8;">
                    <div style="flex:1;">
                        <h6 style="font-weight:600;font-size:14px;margin:0 0 3px;">{{ $item->food_name }}</h6>
                        <span style="color:#888;font-size:13px;">₹{{ number_format($item->food_price,2) }} × {{ $item->quantity }}</span>
                    </div>
                    <span style="font-weight:700;color:#252525;">₹{{ number_format($item->subtotal,2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="col-md-4">
        <!-- Delivery Info -->
        <div class="card mb-3" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;"><h6 style="font-weight:700;margin:0;"><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>Delivery Info</h6></div>
            <div class="card-body" style="padding:15px 20px;">
                <p style="font-size:13px;color:#555;margin-bottom:5px;"><strong>{{ $order->delivery_name }}</strong></p>
                <p style="font-size:13px;color:#555;margin-bottom:5px;">{{ $order->delivery_phone }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:5px;">{{ $order->delivery_address }}, {{ $order->delivery_city }}</p>
                @if($order->deliveryPartner)
                <hr>
                <p style="font-size:12px;color:#888;margin-bottom:3px;">Delivery Partner</p>
                <p style="font-size:13px;color:#555;margin-bottom:0;"><strong>{{ $order->deliveryPartner->name }}</strong> — {{ $order->deliveryPartner->phone }}</p>
                @endif
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;"><h6 style="font-weight:700;margin:0;"><i class="fas fa-receipt mr-2" style="color:#e42e0c;"></i>Payment</h6></div>
            <div class="card-body" style="padding:15px 20px;">
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;"><span style="color:#555;">Subtotal</span><span>₹{{ number_format($order->subtotal,2) }}</span></div>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;"><span style="color:#555;">Delivery Fee</span><span>₹{{ number_format($order->delivery_fee,2) }}</span></div>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;"><span style="color:#555;">Tax</span><span>₹{{ number_format($order->tax,2) }}</span></div>
                <hr style="margin:10px 0;">
                <div class="d-flex justify-content-between" style="font-size:15px;font-weight:700;"><span>Total</span><span style="color:#e42e0c;">₹{{ number_format($order->total,2) }}</span></div>
                <p style="margin-top:10px;font-size:12px;color:#888;">Method: {{ strtoupper($order->payment_method) }} &bull; Status: <span style="color:{{ $order->payment_status==='paid' ? '#4caf50' : '#e65100' }};">{{ ucfirst($order->payment_status) }}</span></p>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
