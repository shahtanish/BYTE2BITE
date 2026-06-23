@extends('layouts.app')
@section('title','My Cart')
@section('banner')
<div style="background:#f8f8f8;padding:30px 0;border-bottom:1px solid #eee;">
    <div class="container"><h3 style="color:#252525;font-weight:700;margin:0;"><i class="fas fa-shopping-cart mr-2" style="color:#e42e0c;"></i>My Cart</h3></div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;min-height:60vh;">
<div class="container">
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
@if(empty($cart))
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart" style="font-size:60px;color:#ddd;margin-bottom:20px;display:block;"></i>
        <h5 style="color:#888;">Your cart is empty</h5>
        <a href="{{ route('restaurants') }}" class="btn mt-3" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:10px 30px;">Browse Restaurants</a>
    </div>
@else
<div class="row">
    <div class="col-md-8">
        @foreach($cartData['byRestaurant'] as $rid => $rData)
        <div class="card mb-3" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#252525;color:#fff;padding:12px 20px;border-radius:10px 10px 0 0;">
                <i class="fas fa-store mr-2" style="color:#e42e0c;"></i>{{ $rData['restaurant_name'] }}
                <span style="float:right;font-size:12px;color:#aaa;">Delivery: ₹{{ number_format($rData['delivery_fee'],2) }}</span>
            </div>
            <div class="card-body" style="padding:20px;">
                @foreach($rData['items'] as $key => $item)
                <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom:1px solid #f0f0f0;" id="row-{{ $key }}">
                    <img src="{{ $item['image'] }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;margin-right:15px;">
                    <div style="flex:1;">
                        <h6 style="font-weight:600;margin:0 0 3px;font-size:14px;">{{ $item['name'] }}</h6>
                        <span style="color:#e42e0c;font-weight:700;">₹{{ number_format($item['price'],2) }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <button onclick="updateQty('{{ $key }}', {{ $item['quantity']-1 }})" style="background:#f0f0f0;border:none;width:28px;height:28px;border-radius:50%;font-weight:700;">-</button>
                        <span id="qty-{{ $key }}" style="margin:0 10px;font-weight:600;min-width:20px;text-align:center;">{{ $item['quantity'] }}</span>
                        <button onclick="updateQty('{{ $key }}', {{ $item['quantity']+1 }})" style="background:#e42e0c;color:#fff;border:none;width:28px;height:28px;border-radius:50%;font-weight:700;">+</button>
                        <button onclick="removeItem('{{ $key }}')" style="background:none;border:none;color:#e42e0c;margin-left:10px;font-size:16px;"><i class="fas fa-trash"></i></button>
                    </div>
                    <div style="min-width:80px;text-align:right;">
                        <span id="line-{{ $key }}" style="font-weight:700;color:#252525;">₹{{ number_format($item['line_total'],2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    <!-- Order Summary -->
    <div class="col-md-4">
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.1);position:sticky;top:80px;">
            <div style="background:#e42e0c;padding:15px 20px;border-radius:10px 10px 0 0;">
                <h6 style="color:#fff;font-weight:700;margin:0;">Order Summary</h6>
            </div>
            <div class="card-body" style="padding:20px;">
                <div class="d-flex justify-content-between mb-2" style="font-size:14px;"><span style="color:#555;">Subtotal</span><span id="summary-subtotal" style="font-weight:600;">₹{{ number_format($cartData['subtotal'],2) }}</span></div>
                <div class="d-flex justify-content-between mb-2" style="font-size:14px;"><span style="color:#555;">Delivery Fee</span><span id="summary-delivery" style="font-weight:600;">₹{{ number_format($cartData['deliveryFee'],2) }}</span></div>
                <div class="d-flex justify-content-between mb-2" style="font-size:14px;"><span style="color:#555;">Tax (5%)</span><span id="summary-tax" style="font-weight:600;">₹{{ number_format($cartData['tax'],2) }}</span></div>
                <hr>
                <div class="d-flex justify-content-between" style="font-size:16px;font-weight:700;"><span>Total</span><span id="summary-total" style="color:#e42e0c;">₹{{ number_format($cartData['total'],2) }}</span></div>
                <a href="{{ route('customer.checkout') }}" class="btn btn-block mt-4"
                    style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:12px;font-size:14px;font-weight:600;">
                    Proceed to Checkout <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="{{ route('restaurants') }}" class="btn btn-block mt-2"
                    style="background:#f0f0f0;color:#555;border:none;border-radius:5px;padding:10px;font-size:13px;">
                    <i class="fas fa-plus mr-1"></i> Add More Items
                </a>
            </div>
        </div>
    </div>
</div>
@endif
</div>
</div>
@endsection
@push('scripts')
<script>
function updateQty(key, qty) {
    $.post('{{ route("customer.cart.update") }}', {key: key, quantity: qty}, function(res) {
        if (res.success) {
            if (qty <= 0) { $('#row-'+key).remove(); }
            else { $('#qty-'+key).text(qty); }
            $('#cart-count').text(res.count);
            $('#summary-subtotal').text('₹'+res.subtotal);
            $('#summary-total').text('₹'+res.total);
        }
    });
}
function removeItem(key) {
    $.post('{{ route("customer.cart.remove") }}', {key: key}, function(res) {
        if (res.success) { $('#row-'+key).fadeOut(function(){ $(this).remove(); }); $('#cart-count').text(res.count); }
    });
}
</script>
@endpush
