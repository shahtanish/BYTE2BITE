@extends('layouts.app')
@section('title','Register')
@section('banner')
<div style="background:#f8f8f8;padding:40px 0;border-bottom:1px solid #eee;">
    <div class="container text-center">
        <h2 style="color:#252525;font-weight:700;">Create Account</h2>
        <p style="color:#888;">Join BYTE2BITE and order from multiple restaurants</p>
    </div>
</div>
@endsection
@section('content')
<div class="layout_padding" style="background:#f8f8f8;min-height:70vh;padding:40px 0;">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-6">
    <div class="card" style="border:none;border-radius:10px;box-shadow:0 5px 30px rgba(0,0,0,0.1);overflow:hidden;">
        <div style="background:#e42e0c;padding:25px;text-align:center;">
            <h4 style="color:#fff;font-weight:700;margin:0;">Customer Registration</h4>
            <p style="color:rgba(255,255,255,0.8);margin:5px 0 0;font-size:13px;">Create your free account</p>
        </div>
        <div class="card-body" style="padding:35px;">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                            style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required
                            style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                        style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Password *</label>
                        <input type="password" name="password" class="form-control" required
                            style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label style="font-size:13px;font-weight:600;">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required
                            style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-block mt-2"
                    style="background:#e42e0c;border:none;padding:12px;border-radius:5px;font-size:14px;font-weight:600;color:#fff;width:100%;">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
            </form>
            <hr>
            <p style="text-align:center;font-size:13px;color:#666;margin:0;">
                Already have an account? <a href="{{ route('login') }}" style="color:#e42e0c;font-weight:600;">Login</a>
            </p>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection
