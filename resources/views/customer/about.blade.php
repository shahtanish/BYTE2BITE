@extends('layouts.app')
@section('title','About Us')
@section('banner')
<div style="background-image:url('{{ asset('images/about-bg.png') }}');background-size:cover;background-repeat:no-repeat;padding:70px 0;">
    <div class="container text-center"><h1 style="color:#fff;font-weight:700;font-size:40px;">About BYTE2BITE</h1><p style="color:rgba(255,255,255,0.8);font-size:16px;">Connecting food lovers with the best local restaurants</p></div>
</div>
@endsection
@section('content')
<div style="background:#fff;padding:60px 0;">
<div class="container">
    <div class="row align-items-center mb-60">
        <div class="col-md-6">
            <img src="{{ asset('images/about-img.png') }}" class="img-fluid" style="border-radius:10px;">
        </div>
        <div class="col-md-6" style="padding-left:40px;">
            <h2 style="font-weight:700;color:#252525;margin-bottom:20px;">Who We Are</h2>
            <p style="color:#666;font-size:15px;line-height:1.8;margin-bottom:20px;">BYTE2BITE is a multi-vendor online food ordering and delivery platform that connects hungry customers with their favourite local restaurants. What makes us unique is the ability to order from <strong style="color:#e42e0c;">multiple restaurants in a single order</strong> — your whole table, sorted in one checkout.</p>
            <p style="color:#666;font-size:15px;line-height:1.8;margin-bottom:25px;">Founded with the belief that great food should be accessible, easy, and fast — we partner with restaurants across cities to bring variety to your doorstep.</p>
            <div class="row text-center">
                <div class="col-4"><h3 style="color:#e42e0c;font-weight:700;">500+</h3><p style="color:#888;font-size:13px;">Restaurants</p></div>
                <div class="col-4"><h3 style="color:#e42e0c;font-weight:700;">50K+</h3><p style="color:#888;font-size:13px;">Happy Customers</p></div>
                <div class="col-4"><h3 style="color:#e42e0c;font-weight:700;">100K+</h3><p style="color:#888;font-size:13px;">Orders Delivered</p></div>
            </div>
        </div>
    </div>
    <div class="row mt-5 text-center">
        <div class="col-md-4 mb-4"><div style="background:#f8f8f8;border-radius:10px;padding:30px;"><i class="fas fa-utensils" style="font-size:36px;color:#e42e0c;margin-bottom:15px;display:block;"></i><h5 style="font-weight:700;color:#252525;">Multi-Restaurant Cart</h5><p style="color:#888;font-size:14px;">Order from multiple restaurants in a single checkout — no juggling multiple apps.</p></div></div>
        <div class="col-md-4 mb-4"><div style="background:#f8f8f8;border-radius:10px;padding:30px;"><i class="fas fa-map-marker-alt" style="font-size:36px;color:#e42e0c;margin-bottom:15px;display:block;"></i><h5 style="font-weight:700;color:#252525;">Live Order Tracking</h5><p style="color:#888;font-size:14px;">Track your delivery in real-time on a live map from restaurant to your doorstep.</p></div></div>
        <div class="col-md-4 mb-4"><div style="background:#f8f8f8;border-radius:10px;padding:30px;"><i class="fas fa-shield-alt" style="font-size:36px;color:#e42e0c;margin-bottom:15px;display:block;"></i><h5 style="font-weight:700;color:#252525;">Safe & Secure</h5><p style="color:#888;font-size:14px;">Secure online payments, transparent pricing, and no hidden fees — ever.</p></div></div>
    </div>
</div>
</div>
@endsection
