@extends('layouts.app')
@section('title','Checkout')
@section('banner')
<div style="background:#f8f8f8;padding:30px 0;border-bottom:1px solid #eee;">
    <div class="container"><h3 style="color:#252525;font-weight:700;margin:0;"><i class="fas fa-credit-card mr-2" style="color:#e42e0c;"></i>Checkout</h3></div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;min-height:60vh;">
<div class="container">
<div class="row">
    <div class="col-md-7">
        <div class="card mb-4" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;padding:15px 20px;border-bottom:1px solid #f0f0f0;">
                <h6 style="font-weight:700;color:#252525;margin:0;"><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>Delivery Address</h6>
            </div>
            <div class="card-body" style="padding:20px;">
                @if($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif
                <form action="{{ route('customer.order.place') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label style="font-size:13px;font-weight:600;">Full Name *</label>
                            <input type="text" name="delivery_name" class="form-control" value="{{ old('delivery_name', $user->name) }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label style="font-size:13px;font-weight:600;">Phone *</label>
                            <input type="text" name="delivery_phone" class="form-control" value="{{ old('delivery_phone', $user->phone) }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Delivery Address *</label>
                        <textarea name="delivery_address" class="form-control" rows="2" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">{{ old('delivery_address', $user->address) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label style="font-size:13px;font-weight:600;">City *</label>
                            <input type="text" name="delivery_city" class="form-control" value="{{ old('delivery_city', $user->city) }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label style="font-size:13px;font-weight:600;">Pincode</label>
                            <input type="text" name="delivery_pincode" class="form-control" value="{{ old('delivery_pincode', $user->pincode) }}" style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Order Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions..." style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;"></textarea>
                    </div>

                    <!-- Payment Method -->
                    <h6 style="font-weight:700;color:#252525;margin:20px 0 15px;border-top:1px solid #f0f0f0;padding-top:15px;"><i class="fas fa-wallet mr-2" style="color:#e42e0c;"></i>Payment Method</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label style="display:block;border:2px solid #ddd;border-radius:8px;padding:15px;cursor:pointer;transition:all 0.2s;" id="cod-label">
                                <input type="radio" name="payment_method" value="cod" checked onchange="selectPayment('cod')"> &nbsp;
                                <i class="fas fa-money-bill-wave mr-2" style="color:#4caf50;"></i>
                                <strong>Cash on Delivery</strong>
                                <p style="color:#888;font-size:12px;margin:5px 0 0;">Pay when your order arrives</p>
                            </label>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="display:block;border:2px solid #ddd;border-radius:8px;padding:15px;cursor:pointer;transition:all 0.2s;" id="online-label">
                                <input type="radio" name="payment_method" value="online" onchange="selectPayment('online')"> &nbsp;
                                <i class="fas fa-credit-card mr-2" style="color:#1976d2;"></i>
                                <strong>Online Payment</strong>
                                <p style="color:#888;font-size:12px;margin:5px 0 0;">Pay securely via Stripe</p>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block mt-2"
                        style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:13px;font-size:15px;font-weight:700;">
                        <i class="fas fa-check-circle mr-2"></i>Place Order
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Order Summary -->
    <div class="col-md-5">
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.1);">
            <div style="background:#252525;padding:15px 20px;border-radius:10px 10px 0 0;">
                <h6 style="color:#fff;font-weight:700;margin:0;">Order Summary</h6>
            </div>
            <div class="card-body" style="padding:20px;">
                @foreach($cartData['byRestaurant'] as $rid => $rData)
                <div class="mb-3">
                    <p style="font-weight:700;color:#252525;font-size:13px;margin-bottom:8px;border-bottom:1px solid #f0f0f0;padding-bottom:6px;"><i class="fas fa-store mr-2" style="color:#e42e0c;"></i>{{ $rData['restaurant_name'] }}</p>
                    @foreach($rData['items'] as $item)
                    <div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                        <span style="color:#555;">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                        <span style="font-weight:600;">₹{{ number_format($item['line_total'],2) }}</span>
                    </div>
                    @endforeach
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;"><span style="color:#555;">Subtotal</span><span>₹{{ number_format($cartData['subtotal'],2) }}</span></div>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;"><span style="color:#555;">Delivery Fee</span><span>₹{{ number_format($cartData['deliveryFee'],2) }}</span></div>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;"><span style="color:#555;">Tax (5%)</span><span>₹{{ number_format($cartData['tax'],2) }}</span></div>
                <hr>
                <div class="d-flex justify-content-between" style="font-size:16px;font-weight:700;"><span>Total</span><span style="color:#e42e0c;">₹{{ number_format($cartData['total'],2) }}</span></div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
function selectPayment(type) {
    $('#cod-label').css('border-color', type==='cod' ? '#e42e0c' : '#ddd');
    $('#online-label').css('border-color', type==='online' ? '#e42e0c' : '#ddd');
}
selectPayment('cod');
</script>
@endpush
