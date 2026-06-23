@extends('layouts.app')
@section('title','Contact Us')
@section('banner')
<div style="background:#252525;padding:50px 0;text-align:center;"><h2 style="color:#fff;font-weight:700;">Contact Us</h2><p style="color:#aaa;">We'd love to hear from you</p></div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:50px 0;">
<div class="container">
<div class="row">
    <div class="col-md-5 mb-4">
        <h4 style="font-weight:700;color:#252525;margin-bottom:25px;">Get In Touch</h4>
        <div class="d-flex mb-4"><div style="width:50px;height:50px;background:#e42e0c;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:15px;flex-shrink:0;"><i class="fas fa-map-marker-alt" style="color:#fff;font-size:18px;"></i></div><div><h6 style="font-weight:700;color:#252525;margin:0 0 3px;">Address</h6><p style="color:#666;font-size:14px;margin:0;">123 Food Street, Foodie City<br>India - 600001</p></div></div>
        <div class="d-flex mb-4"><div style="width:50px;height:50px;background:#e42e0c;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:15px;flex-shrink:0;"><i class="fas fa-phone" style="color:#fff;font-size:18px;"></i></div><div><h6 style="font-weight:700;color:#252525;margin:0 0 3px;">Phone</h6><p style="color:#666;font-size:14px;margin:0;">+91 800 BYTE2BITE</p></div></div>
        <div class="d-flex mb-4"><div style="width:50px;height:50px;background:#e42e0c;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:15px;flex-shrink:0;"><i class="fas fa-envelope" style="color:#fff;font-size:18px;"></i></div><div><h6 style="font-weight:700;color:#252525;margin:0 0 3px;">Email</h6><p style="color:#666;font-size:14px;margin:0;">hello@byte2bite.com</p></div></div>
    </div>
    <div class="col-md-7">
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 3px 20px rgba(0,0,0,0.1);padding:30px;">
            @if(session('success'))<div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
            <h5 style="font-weight:700;color:#252525;margin-bottom:20px;">Send a Message</h5>
            <form method="POST" action="{{ route('contact.submit') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group mb-3"><label style="font-size:13px;font-weight:600;">Name</label><input type="text" name="name" class="form-control" required value="{{ old('name') }}" style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;"></div>
                    <div class="col-md-6 form-group mb-3"><label style="font-size:13px;font-weight:600;">Email</label><input type="email" name="email" class="form-control" required value="{{ old('email') }}" style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;"></div>
                </div>
                <div class="form-group mb-3"><label style="font-size:13px;font-weight:600;">Subject</label><input type="text" name="subject" class="form-control" value="{{ old('subject') }}" style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;"></div>
                <div class="form-group mb-3"><label style="font-size:13px;font-weight:600;">Message</label><textarea name="message" class="form-control" rows="4" required style="border-radius:5px;border:1px solid #ddd;padding:10px 15px;font-size:14px;">{{ old('message') }}</textarea></div>
                <button type="submit" style="background:#e42e0c;color:#fff;border:none;padding:12px 35px;border-radius:5px;font-size:14px;font-weight:600;"><i class="fas fa-paper-plane mr-2"></i>Send Message</button>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection
