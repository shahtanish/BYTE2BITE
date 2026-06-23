<!-- @extends('layouts.app')
@section('title','Login')
@section('banner')
<div style="background:#f8f8f8;padding:40px 0;border-bottom:1px solid #eee;">
    <div class="container text-center">
        <h2 style="color:#252525;font-weight:700;">Welcome Back</h2>
        <p style="color:#888;">Login to your BYTE2BITE account</p>
    </div>
</div>
@endsection
@section('content')
<div class="layout_padding" style="background:#f8f8f8;min-height:70vh;display:flex;align-items:center;">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card" style="border:none;border-radius:10px;box-shadow:0 5px 30px rgba(0,0,0,0.1);overflow:hidden;">
        <div style="background:#e42e0c;padding:30px;text-align:center;">
            <h3 style="color:#fff;font-weight:700;margin:0;">BYTE<span style="color:#ffcdd2;">2BITE</span></h3>
            <p style="color:rgba(255,255,255,0.8);margin:5px 0 0;font-size:13px;">Sign in to your account</p>
        </div>
        <div class="card-body" style="padding:35px;">
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group mb-3">
                    <label style="font-size:13px;font-weight:600;color:#333;">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="Enter your email" required
                        style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                </div>
                <div class="form-group mb-3">
                    <label style="font-size:13px;font-weight:600;color:#333;">Password</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="Enter your password" required
                        style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label style="font-size:13px;color:#555;margin:0;">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary-b2b btn-block"
                    style="background:#e42e0c;border:none;padding:12px;border-radius:5px;font-size:14px;font-weight:600;color:#fff;width:100%;">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>
            <hr style="margin:25px 0;">
            <p style="text-align:center;font-size:13px;color:#666;margin:0;">
                Don't have an account? <a href="{{ route('register') }}" style="color:#e42e0c;font-weight:600;">Register here</a>
            </p>
            <p style="text-align:center;font-size:12px;color:#999;margin-top:10px;">
                Restaurant? <a href="{{ route('restaurant.register') }}" style="color:#252525;">Register your restaurant</a> |
                Delivery? <a href="{{ route('delivery.register') }}" style="color:#252525;">Join as partner</a>
            </p>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection -->






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | BYTE2BITE</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f8f8; margin: 0; }
        .top-bar { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 14px 0; }
        .brand { font-size: 22px; font-weight: 700; color: #e42e0c; text-decoration: none; }
        .brand em { color: #252525; font-style: normal; }
        .nav-btn { padding: 7px 20px; border-radius: 4px; font-size: 14px; text-decoration: none; margin-left: 8px; font-family: 'Poppins', sans-serif; display: inline-block; }
        .nav-btn-red { background: #e42e0c; color: #fff; }
        .nav-btn-dark { background: #252525; color: #fff; }
        .page-wrap { min-height: calc(100vh - 60px); display: flex; align-items: center; justify-content: center; padding: 40px 15px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 5px 30px rgba(0,0,0,0.12); overflow: hidden; width: 100%; max-width: 440px; border: none; }
        .card-top { background: #e42e0c; padding: 30px; text-align: center; }
        .card-top h3 { color: #fff; font-weight: 700; margin: 0; font-size: 22px; }
        .card-top p { color: rgba(255,255,255,0.85); margin: 6px 0 0; font-size: 13px; }
        .card-body { padding: 35px !important; }
        label { font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; display: block; }
        .form-control { border: 1px solid #ddd !important; border-radius: 6px !important; padding: 11px 15px !important; font-size: 14px !important; width: 100%; font-family: 'Poppins', sans-serif; }
        .form-control:focus { border-color: #e42e0c !important; box-shadow: 0 0 0 3px rgba(228,46,12,0.1) !important; outline: none; }
        .btn-go { background: #e42e0c; color: #fff; border: none; border-radius: 6px; padding: 13px; font-size: 15px; font-weight: 600; width: 100%; cursor: pointer; font-family: 'Poppins', sans-serif; }
        .btn-go:hover { background: #c0250a; }
        .err { background: #fce4ec; border: 1px solid #f8bbd0; color: #c62828; border-radius: 6px; padding: 12px 15px; font-size: 13px; margin-bottom: 18px; }
        .suc { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; border-radius: 6px; padding: 12px 15px; font-size: 13px; margin-bottom: 18px; }
        .mb18 { margin-bottom: 18px; }
        .center { text-align: center; }
        .link-red { color: #e42e0c; font-weight: 600; text-decoration: none; }
        .small-links { font-size: 12px; color: #999; margin-top: 10px; }
        .small-links a { color: #666; text-decoration: none; }
        .small-links a:hover { color: #e42e0c; }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('home') }}" class="brand">BYTE<em>2BITE</em></a>
        <div>
            <a href="{{ route('home') }}" class="nav-btn" style="color:#333;">Home</a>
            <a href="{{ route('restaurants') }}" class="nav-btn" style="color:#333;">Restaurants</a>
            <a href="{{ route('login') }}" class="nav-btn nav-btn-red">Login</a>
            <a href="{{ route('register') }}" class="nav-btn nav-btn-dark">Register</a>
        </div>
    </div>
</div>

<div class="page-wrap">
    <div class="card">
        <div class="card-top">
            <h3>BYTE<span style="color:#ffcdd2;">2BITE</span></h3>
            <p>Sign in to your account</p>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div class="err"><i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}</div>
            @endif

            @if(session('success'))
            <div class="suc"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb18">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ old('email') }}" placeholder="you@example.com"
                        required autocomplete="email" autofocus>
                </div>

                <div class="mb18">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Your password" required autocomplete="current-password">
                </div>

                <div class="mb18 d-flex align-items-center">
                    <input type="checkbox" name="remember" id="remember" style="margin-right:7px;">
                    <label for="remember" style="margin:0;font-weight:400;font-size:13px;cursor:pointer;">Remember me</label>
                </div>

                <button type="submit" class="btn-go">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <hr style="margin:25px 0;border-color:#f0f0f0;">

            <div class="center" style="font-size:13px;color:#666;">
                Don't have an account? <a href="{{ route('register') }}" class="link-red">Register here</a>
            </div>
            <div class="center small-links">
                <a href="{{ route('restaurant.register') }}">Register Restaurant</a>
                &nbsp;|&nbsp;
                <a href="{{ route('delivery.register') }}">Join as Delivery Partner</a>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-3.0.0.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>