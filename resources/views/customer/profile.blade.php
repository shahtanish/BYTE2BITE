@extends('layouts.app')
@section('title','My Profile')
@section('banner')
<div style="background:#f8f8f8;padding:30px 0;border-bottom:1px solid #eee;">
    <div class="container"><h3 style="color:#252525;font-weight:700;margin:0;"><i class="fas fa-user mr-2" style="color:#e42e0c;"></i>My Profile</h3></div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;">
<div class="container">
<div class="row">
<div class="col-md-8">
<div class="card mb-4" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
    <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;"><h6 style="font-weight:700;margin:0;">Personal Information</h6></div>
    <div class="card-body" style="padding:25px;">
        @if(session('success'))<div class="alert alert-success" style="font-size:13px;">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:13px;font-weight:600;">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:13px;font-weight:600;">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}" required style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;"></div>
            </div>
            <div class="form-group mb-3"><label style="font-size:13px;font-weight:600;">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;"></div>
            <div class="form-group mb-3"><label style="font-size:13px;font-weight:600;">Address</label><textarea name="address" class="form-control" rows="2" style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;">{{ old('address',$user->address) }}</textarea></div>
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:13px;font-weight:600;">City</label><input type="text" name="city" class="form-control" value="{{ old('city',$user->city) }}" style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:13px;font-weight:600;">Pincode</label><input type="text" name="pincode" class="form-control" value="{{ old('pincode',$user->pincode) }}" style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;"></div>
            </div>
            <button type="submit" style="background:#e42e0c;color:#fff;border:none;padding:10px 30px;border-radius:5px;font-size:14px;font-weight:600;"><i class="fas fa-save mr-2"></i>Update Profile</button>
        </form>
    </div>
</div>
</div>
<div class="col-md-4">
    <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
        <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;"><h6 style="font-weight:700;margin:0;">Recent Orders</h6></div>
        <div class="card-body" style="padding:0;">
            @forelse($orders as $order)
            <a href="{{ route('customer.orders.show',$order->id) }}" style="display:block;padding:12px 15px;border-bottom:1px solid #f8f8f8;text-decoration:none;">
                <div class="d-flex justify-content-between"><span style="font-size:13px;font-weight:600;color:#e42e0c;">{{ $order->order_number }}</span>{!! $order->status_badge !!}</div>
                <p style="font-size:12px;color:#888;margin:3px 0 0;">₹{{ number_format($order->total,2) }} — {{ $order->created_at->format('d M Y') }}</p>
            </a>
            @empty
            <p style="padding:20px;color:#888;font-size:13px;margin:0;">No orders yet.</p>
            @endforelse
        </div>
        @if($orders->count())<div style="padding:12px 15px;"><a href="{{ route('customer.orders') }}" style="color:#e42e0c;font-size:13px;">View all orders →</a></div>@endif
    </div>
</div>
</div>
</div>
</div>
@endsection
