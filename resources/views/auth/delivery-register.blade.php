@extends('layouts.app')
@section('title','Join as Delivery Partner')
@section('banner')
<div style="background:#f8f8f8;padding:40px 0;border-bottom:1px solid #eee;">
    <div class="container text-center">
        <h2 style="color:#252525;font-weight:700;">Join as Delivery Partner</h2>
        <p style="color:#888;">Earn money by delivering food across your city</p>
    </div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:40px 0;">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-7">
    <div class="card" style="border:none;border-radius:10px;box-shadow:0 5px 30px rgba(0,0,0,0.1);overflow:hidden;">
        <div style="background:#252525;padding:25px;text-align:center;">
            <h4 style="color:#fff;font-weight:700;margin:0;"><i class="fas fa-motorcycle mr-2" style="color:#e42e0c;"></i>Delivery Partner Registration</h4>
        </div>
        <div class="card-body" style="padding:35px;">
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('delivery.register.post') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Phone *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
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
                <h6 style="color:#e42e0c;font-weight:700;margin:10px 0 15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Vehicle Details</h6>
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Vehicle Type *</label>
                        <select name="vehicle_type" class="form-control" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                            <option value="">Select</option>
                            <option value="Bike">Bike</option>
                            <option value="Scooter">Scooter</option>
                            <option value="Bicycle">Bicycle</option>
                            <option value="Car">Car</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Vehicle Number *</label>
                        <input type="text" name="vehicle_number" class="form-control" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">License Number</label>
                        <input type="text" name="license_number" class="form-control" style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-block mt-2"
                    style="background:#e42e0c;border:none;padding:12px;border-radius:5px;font-size:14px;font-weight:600;color:#fff;width:100%;">
                    <i class="fas fa-motorcycle mr-2"></i>Register as Delivery Partner
                </button>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection
