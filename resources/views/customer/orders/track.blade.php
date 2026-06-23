@extends('layouts.app')
@section('title','Track Order #'.$order->order_number)
@section('banner')
<div style="background:#f8f8f8;padding:25px 0;border-bottom:1px solid #eee;">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h4 style="color:#252525;font-weight:700;margin:0;">Track Your Order</h4>
            <p style="color:#888;margin:3px 0 0;font-size:13px;">Order #{{ $order->order_number }}</p>
        </div>
        <div>{!! $order->status_badge !!}</div>
    </div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;">
<div class="container">
<div class="row">
    <div class="col-md-8">
        <!-- Map -->
        <div class="card mb-4" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);overflow:hidden;">
            <div id="tracking-map" style="height:450px;background:#e0e0e0;display:flex;align-items:center;justify-content:center;">
                @if($mapsKey)
                    <div id="map" style="height:100%;width:100%;"></div>
                @else
                    <div style="text-align:center;padding:40px;">
                        <i class="fas fa-map-marked-alt" style="font-size:60px;color:#e42e0c;margin-bottom:15px;display:block;"></i>
                        <h5 style="color:#252525;font-weight:700;">Live Tracking</h5>
                        <p style="color:#888;font-size:13px;">The delivery agent's location will appear here once they are en route.</p>
                        <p style="color:#aaa;font-size:12px;">Add your Google Maps API key to <code>.env</code> to enable map display.</p>
                        @if($order->current_latitude && $order->current_longitude)
                        <div style="background:#e8f5e9;border-radius:8px;padding:15px;margin-top:15px;">
                            <p style="color:#2e7d32;font-weight:600;margin:0;"><i class="fas fa-location-arrow mr-2"></i>Current Location Available</p>
                            <p style="color:#555;font-size:12px;margin:5px 0 0;">Lat: {{ $order->current_latitude }}, Lng: {{ $order->current_longitude }}</p>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;"><h6 style="font-weight:700;margin:0;">Status Updates</h6></div>
            <div class="card-body" style="padding:20px;">
                @php
                $timeline = [
                    ['status'=>'pending',    'label'=>'Order Placed',       'icon'=>'shopping-cart', 'time'=>$order->created_at],
                    ['status'=>'accepted',   'label'=>'Order Accepted',     'icon'=>'check-circle',  'time'=>$order->accepted_at],
                    ['status'=>'preparing',  'label'=>'Being Prepared',     'icon'=>'fire',          'time'=>null],
                    ['status'=>'on_the_way', 'label'=>'Out for Delivery',   'icon'=>'motorcycle',    'time'=>$order->picked_up_at],
                    ['status'=>'delivered',  'label'=>'Delivered',          'icon'=>'home',          'time'=>$order->delivered_at],
                ];
                $statusOrder = ['pending','accepted','preparing','on_the_way','delivered'];
                $currentIdx  = array_search($order->status, $statusOrder);
                @endphp
                @foreach($timeline as $i => $tl)
                @php $done = $i <= ($currentIdx ?? -1); @endphp
                <div class="d-flex align-items-start mb-4 {{ !$loop->last ? 'pb-4' : '' }}" style="{{ !$loop->last ? 'border-bottom:1px dashed #f0f0f0;' : '' }}">
                    <div style="width:40px;height:40px;border-radius:50%;background:{{ $done ? '#e42e0c' : '#f0f0f0' }};color:{{ $done ? '#fff' : '#ccc' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:15px;">
                        <i class="fas fa-{{ $tl['icon'] }}"></i>
                    </div>
                    <div>
                        <h6 style="font-weight:{{ $done ? '700' : '400' }};color:{{ $done ? '#252525' : '#aaa' }};margin:0 0 3px;font-size:14px;">{{ $tl['label'] }}</h6>
                        @if($tl['time'])
                        <p style="color:#888;font-size:12px;margin:0;">{{ \Carbon\Carbon::parse($tl['time'])->format('d M Y, h:i A') }}</p>
                        @endif
                    </div>
                    @if($i === ($currentIdx ?? -1))
                    <span style="margin-left:auto;background:#e8f5e9;color:#2e7d32;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Current</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Delivery Agent -->
        @if($order->deliveryPartner)
        <div class="card mb-3" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-body" style="padding:20px;text-align:center;">
                <div style="width:60px;height:60px;background:#e42e0c;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;margin:0 auto 12px;">
                    {{ strtoupper(substr($order->deliveryPartner->name,0,1)) }}
                </div>
                <h6 style="font-weight:700;color:#252525;margin:0 0 3px;">{{ $order->deliveryPartner->name }}</h6>
                <p style="color:#888;font-size:13px;margin-bottom:15px;">Your Delivery Partner</p>
                <a href="tel:{{ $order->deliveryPartner->phone }}" class="btn btn-block" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:8px;font-size:13px;">
                    <i class="fas fa-phone mr-2"></i>{{ $order->deliveryPartner->phone }}
                </a>
            </div>
        </div>
        @endif

        <!-- Order Summary -->
        <div class="card mb-3" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;"><h6 style="font-weight:700;margin:0;">Order Summary</h6></div>
            <div class="card-body" style="padding:15px 20px;">
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                    <span>{{ $item->food_name }} ×{{ $item->quantity }}</span>
                    <span style="font-weight:600;">₹{{ number_format($item->subtotal,2) }}</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between" style="font-weight:700;font-size:14px;">
                    <span>Total</span><span style="color:#e42e0c;">₹{{ number_format($order->total,2) }}</span>
                </div>
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-body" style="padding:15px 20px;">
                <h6 style="font-weight:700;margin-bottom:10px;"><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>Delivery Address</h6>
                <p style="color:#555;font-size:13px;margin:0;">{{ $order->delivery_address }}<br>{{ $order->delivery_city }}</p>
            </div>
        </div>

        <div style="text-align:center;margin-top:20px;">
            <p id="last-updated" style="color:#aaa;font-size:12px;">Refreshing every 15 seconds...</p>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')
@if($mapsKey)
<script src="https://maps.googleapis.com/maps/api/js?key={{ $mapsKey }}&callback=initMap" async defer></script>
<script>
var map, deliveryMarker, destinationMarker;
var orderId = {{ $order->id }};

function initMap() {
    var defaultPos = {lat: 20.5937, lng: 78.9629};
    map = new google.maps.Map(document.getElementById('map'), {zoom:14, center:defaultPos});

    @if($order->delivery_latitude && $order->delivery_longitude)
    destinationMarker = new google.maps.Marker({
        position: {lat: {{ $order->delivery_latitude }}, lng: {{ $order->delivery_longitude }}},
        map: map, title: 'Delivery Address',
        icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
    });
    map.setCenter({lat: {{ $order->delivery_latitude }}, lng: {{ $order->delivery_longitude }}});
    @endif

    @if($order->current_latitude && $order->current_longitude)
    deliveryMarker = new google.maps.Marker({
        position: {lat: {{ $order->current_latitude }}, lng: {{ $order->current_longitude }}},
        map: map, title: 'Delivery Partner',
        icon: 'http://maps.google.com/mapfiles/ms/icons/motorcycling.png'
    });
    map.setCenter({lat: {{ $order->current_latitude }}, lng: {{ $order->current_longitude }}});
    @endif

    refreshLocation(); // ✅ fire immediately on load
    setInterval(refreshLocation, 5000); // ✅ then every 5 seconds
}

function refreshLocation() {
    fetch('/api/orders/' + orderId + '/location')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('last-updated').textContent = 'Updated: ' + new Date().toLocaleTimeString();
            if (data.current_latitude && data.current_longitude) {
                var pos = {
                    lat: parseFloat(data.current_latitude),
                    lng: parseFloat(data.current_longitude)
                };
                if (deliveryMarker) {
                    deliveryMarker.setPosition(pos); // move existing marker
                } else {
                    // create marker if it didn't exist on page load
                    deliveryMarker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        title: 'Delivery Partner',
                        icon: 'http://maps.google.com/mapfiles/ms/icons/motorcycling.png'
                    });
                }
                map.panTo(pos); // ✅ always pan map to delivery partner
            }
        });
}
</script>
@endif
@endpush
