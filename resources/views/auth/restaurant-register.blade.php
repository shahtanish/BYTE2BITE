@extends('layouts.app')
@section('title','Register Restaurant')
@section('banner')
<div style="background:#f8f8f8;padding:40px 0;border-bottom:1px solid #eee;">
    <div class="container text-center">
        <h2 style="color:#252525;font-weight:700;">Register Your Restaurant</h2>
        <p style="color:#888;">Partner with BYTE2BITE and reach thousands of customers</p>
    </div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:40px 0;">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">
    <div class="card" style="border:none;border-radius:10px;box-shadow:0 5px 30px rgba(0,0,0,0.1);overflow:hidden;">
        <div style="background:#252525;padding:25px;text-align:center;">
            <h4 style="color:#fff;font-weight:700;margin:0;"><i class="fas fa-store mr-2" style="color:#e42e0c;"></i>Restaurant Partner Registration</h4>
            <p style="color:rgba(255,255,255,0.6);margin:5px 0 0;font-size:13px;">Fill in your details — approval usually takes 24 hours</p>
        </div>
        <div class="card-body" style="padding:35px;">
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('restaurant.register.post') }}" enctype="multipart/form-data">
                @csrf
                <h6 style="color:#e42e0c;font-weight:700;margin-bottom:15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Owner Details</h6>
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Owner Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Phone *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Password *</label>
                        <input type="password" name="password" class="form-control" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <h6 style="color:#e42e0c;font-weight:700;margin:20px 0 15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Restaurant Details</h6>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Restaurant Name *</label>
                        <input type="text" name="restaurant_name" class="form-control" value="{{ old('restaurant_name') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Restaurant Phone *</label>
                        <input type="text" name="restaurant_phone" class="form-control" value="{{ old('restaurant_phone') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Cuisine Type</label>
                        <select name="cuisine_type" class="form-control" style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                            <option value="">Select Cuisine</option>
                            <option value="North Indian">North Indian</option>
                            <option value="South Indian">South Indian</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Italian">Italian</option>
                            <option value="Fast Food">Fast Food</option>
                            <option value="Biryani">Biryani</option>
                            <option value="Pizza">Pizza</option>
                            <option value="Burger">Burger</option>
                            <option value="Desserts">Desserts</option>
                            <option value="Multi-Cuisine">Multi-Cuisine</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Restaurant Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*" style="border-radius:5px;border:1px solid #ddd;padding:8px 15px;font-size:14px;">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Address *</label>
                    <textarea name="address" class="form-control" rows="2" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">{{ old('address') }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">City *</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">State</label>
                        <input type="text" name="state" class="form-control" value="{{ old('state') }}" style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-block mt-2"
                    style="background:#e42e0c;border:none;padding:12px;border-radius:5px;font-size:14px;font-weight:600;color:#fff;width:100%;">
                    <i class="fas fa-store mr-2"></i>Register Restaurant
                </button>
            </form>
            <hr>
            <p style="text-align:center;font-size:13px;color:#666;margin:0;">
                Already registered? <a href="{{ route('login') }}" style="color:#e42e0c;font-weight:600;">Login here</a>
            </p>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection
