@extends('layouts.app')
@section('title', 'Pay for Order #' . $order->order_number)
@section('content')
<div style="background:#f8f8f8;padding:60px 0;min-height:60vh;">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card" style="border:none;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div class="card-body" style="padding:35px;">
            <h5 style="font-weight:700;color:#252525;margin-bottom:5px;">Complete Payment</h5>
            <p style="color:#888;font-size:13px;margin-bottom:25px;">Order #{{ $order->order_number }}</p>

            <div style="background:#f8f8f8;border-radius:8px;padding:15px;margin-bottom:25px;">
                <div class="d-flex justify-content-between" style="font-size:13px;color:#555;margin-bottom:5px;">
                    <span>Subtotal</span><span>₹{{ number_format($order->subtotal,2) }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:13px;color:#555;margin-bottom:5px;">
                    <span>Delivery Fee</span><span>₹{{ number_format($order->delivery_fee,2) }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:13px;color:#555;margin-bottom:5px;">
                    <span>Tax</span><span>₹{{ number_format($order->tax,2) }}</span>
                </div>
                <hr style="margin:10px 0;">
                <div class="d-flex justify-content-between" style="font-weight:700;color:#e42e0c;">
                    <span>Total</span><span>₹{{ number_format($order->total,2) }}</span>
                </div>
            </div>

            <button id="pay-btn" class="btn btn-block" style="background:#e42e0c;color:#fff;border:none;border-radius:6px;padding:12px;font-size:15px;font-weight:600;">
                <i class="fas fa-lock mr-2"></i>Pay ₹{{ number_format($order->total,2) }}
            </button>

            <p style="text-align:center;color:#aaa;font-size:12px;margin-top:15px;">
                <i class="fas fa-shield-alt mr-1"></i>Secured by Razorpay
            </p>
        </div>
    </div>
</div>
</div>
</div>
</div>

{{-- Hidden form for callback --}}
<form id="razorpay-form" action="{{ route('customer.payment.callback') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('pay-btn').addEventListener('click', function() {
    var options = {
        key: '{{ $razorpayKey }}',
        amount: {{ $order->total * 100 }}, // in paise
        currency: 'INR',
        name: 'BYTE2BITE',
        description: 'Order #{{ $order->order_number }}',
        handler: function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value   = response.razorpay_order_id || '';
            document.getElementById('razorpay_signature').value  = response.razorpay_signature || '';
            document.getElementById('razorpay-form').submit();
        },
        prefill: {
            name:  '{{ $order->user->name ?? "" }}',
            email: '{{ $order->user->email ?? "" }}',
            contact: '{{ $order->delivery_phone }}'
        },
        theme: { color: '#e42e0c' }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
@endpush
@endsection