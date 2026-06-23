@extends('layouts.admin')
@section('title','Order #'.$order->order_number)
@section('page_title','Order Details')
@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Status + Location Update -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>Order #{{ $order->order_number }} &nbsp; {!! $order->status_badge !!}</span>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;font-size:13px;">← Back</a>
            </div>
            <div class="card-body">
                <!-- Assign delivery partner -->
                @if(in_array($order->status,['ready','accepted','preparing']) && !$order->delivery_partner_id)
                <div class="alert alert-warning" style="border-radius:8px;">
                    <strong>No delivery partner assigned.</strong>
                    <form method="POST" action="{{ route('admin.delivery.assign',['partner'=>0]) }}" class="d-inline mt-2" style="display:block!important;">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="d-flex align-items-center mt-2 gap-2" style="gap:10px;">
                            <select name="partner_id" class="form-control form-control-sm" style="max-width:250px;border-radius:5px;" onchange="this.form.action='{{ route('admin.delivery.assign','') }}/'+this.value">
                                <option value="">Select Delivery Partner</option>
                                @foreach($deliveryPartners as $p)<option value="{{ $p->id }}">{{ $p->name }} — {{ $p->phone }}</option>@endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;">Assign</button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Update Location (Admin Manual Override) -->
                <div class="mb-4">
                    <h6 style="font-weight:700;color:#252525;margin-bottom:15px;"><i class="fas fa-location-arrow mr-2" style="color:#e42e0c;"></i>Update Delivery Location</h6>
                    <p style="font-size:13px;color:#888;margin-bottom:15px;">Manually set the delivery partner's current coordinates to update customer's live tracking map.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <label style="font-size:12px;font-weight:600;">Latitude</label>
                            <input type="number" step="any" id="lat-input" class="form-control form-control-sm" value="{{ $order->current_latitude }}" placeholder="e.g. 13.0827" style="border-radius:5px;font-size:13px;">
                        </div>
                        <div class="col-md-4">
                            <label style="font-size:12px;font-weight:600;">Longitude</label>
                            <input type="number" step="any" id="lng-input" class="form-control form-control-sm" value="{{ $order->current_longitude }}" placeholder="e.g. 80.2707" style="border-radius:5px;font-size:13px;">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button onclick="updateLocation()" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;width:100%;">
                                <i class="fas fa-map-pin mr-1"></i> Update Location
                            </button>
                        </div>
                    </div>
                    <div id="location-msg" class="mt-2" style="font-size:13px;"></div>
                    <small class="text-muted">Current: {{ $order->current_latitude ?? 'Not set' }}, {{ $order->current_longitude ?? 'Not set' }}</small>
                </div>

                <!-- Order Items -->
                <h6 style="font-weight:700;color:#252525;margin-bottom:12px;border-top:1px solid #f0f0f0;padding-top:15px;">Order Items</h6>
                <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Item</th><th>Restaurant</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->food_name }}</td>
                        <td>{{ $item->restaurant->name ?? '-' }}</td>
                        <td>₹{{ number_format($item->food_price,2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td><strong>₹{{ number_format($item->subtotal,2) }}</strong></td>
                    </tr>
                    @endforeach
                    <tr style="background:#f8f9fa;">
                        <td colspan="4" class="text-right"><strong>Subtotal</strong></td><td><strong>₹{{ number_format($order->subtotal,2) }}</strong></td>
                    </tr>
                    <tr><td colspan="4" class="text-right">Delivery Fee</td><td>₹{{ number_format($order->delivery_fee,2) }}</td></tr>
                    <tr><td colspan="4" class="text-right">Tax</td><td>₹{{ number_format($order->tax,2) }}</td></tr>
                    <tr style="background:#fff3f3;">
                        <td colspan="4" class="text-right"><strong style="color:#e42e0c;">TOTAL</strong></td><td><strong style="color:#e42e0c;">₹{{ number_format($order->total,2) }}</strong></td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <!-- Restaurant Statuses -->
        <div class="card">
            <div class="card-header"><i class="fas fa-store mr-2" style="color:#e42e0c;"></i>Restaurant-wise Status</div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <thead><tr><th>Restaurant</th><th>Status</th><th>Accepted At</th><th>Ready At</th></tr></thead>
                    <tbody>
                    @foreach($order->restaurantStatuses as $rs)
                    <tr>
                        <td>{{ $rs->restaurant->name ?? '-' }}</td>
                        <td><span class="badge-status badge-{{ $rs->status }}">{{ ucfirst($rs->status) }}</span></td>
                        <td style="font-size:12px;color:#888;">{{ $rs->accepted_at ? $rs->accepted_at->format('d M, h:i A') : '-' }}</td>
                        <td style="font-size:12px;color:#888;">{{ $rs->ready_at ? $rs->ready_at->format('d M, h:i A') : '-' }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Customer & Delivery Info -->
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user mr-2" style="color:#e42e0c;"></i>Customer</div>
            <div class="card-body">
                <p style="font-size:13px;margin-bottom:5px;"><strong>{{ $order->user->name ?? '-' }}</strong></p>
                <p style="font-size:13px;color:#555;margin-bottom:5px;">{{ $order->user->email ?? '' }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:0;">{{ $order->user->phone ?? '' }}</p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>Delivery Address</div>
            <div class="card-body">
                <p style="font-size:13px;margin-bottom:5px;"><strong>{{ $order->delivery_name }}</strong> — {{ $order->delivery_phone }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:0;">{{ $order->delivery_address }}, {{ $order->delivery_city }} {{ $order->delivery_pincode }}</p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-motorcycle mr-2" style="color:#e42e0c;"></i>Delivery Partner</div>
            <div class="card-body">
                @if($order->deliveryPartner)
                <p style="font-size:13px;margin-bottom:3px;"><strong>{{ $order->deliveryPartner->name }}</strong></p>
                <p style="font-size:13px;color:#555;margin-bottom:0;">{{ $order->deliveryPartner->phone }}</p>
                @else
                <p style="color:#888;font-size:13px;margin:0;">Not assigned yet</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-credit-card mr-2" style="color:#e42e0c;"></i>Payment</div>
            <div class="card-body">
                <p style="font-size:13px;margin-bottom:5px;">Method: <strong>{{ strtoupper($order->payment_method) }}</strong></p>
                <p style="font-size:13px;margin-bottom:0;">Status: <strong style="color:{{ $order->payment_status==='paid' ? '#2e7d32' : '#e65100' }};">{{ ucfirst($order->payment_status) }}</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- @push('scripts')
<script>
function updateLocation() {
    var lat = document.getElementById('lat-input').value;
    var lng = document.getElementById('lng-input').value;
    if (!lat || !lng) { document.getElementById('location-msg').innerHTML = '<span style="color:#e42e0c;">Please enter both latitude and longitude.</span>'; return; }
    $.post('{{ route("admin.orders.location",$order->id) }}', {latitude:lat, longitude:lng}, function(res) {
        if (res.success) {
            document.getElementById('location-msg').innerHTML = '<span style="color:#2e7d32;"><i class="fas fa-check-circle mr-1"></i>'+res.message+'</span>';
        }
    });
}
</script>
@endpush -->


@push('scripts')
<script>
function updateLocation() {
    var lat = document.getElementById('lat-input').value;
    var lng = document.getElementById('lng-input').value;
    if (!lat || !lng) {
        document.getElementById('location-msg').innerHTML = '<span style="color:#e42e0c;">Please enter both latitude and longitude.</span>';
        return;
    }

    fetch('{{ route("admin.orders.location", $order->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ latitude: lat, longitude: lng })
    })

    
    .then(res => res.json())
    .then(function(res) {
        if (res.success) {
            document.getElementById('location-msg').innerHTML =
                '<span style="color:#2e7d32;"><i class="fas fa-check-circle mr-1"></i>' + res.message + '</span>';
        }
    })
    .catch(function() {
        document.getElementById('location-msg').innerHTML =
            '<span style="color:#e42e0c;">Something went wrong. Try again.</span>';
    });
}
</script>
@endpush