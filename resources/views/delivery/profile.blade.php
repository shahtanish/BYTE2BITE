@extends('layouts.delivery')
@section('title','My Profile')
@section('page_title','My Profile')
@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card">
    <div class="card-header"><i class="fas fa-user mr-2" style="color:#e42e0c;"></i>Edit Profile</div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success" style="font-size:13px;">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('delivery.profile.update') }}">
            @csrf @method('PUT')
            <h6 style="font-weight:700;color:#e42e0c;margin-bottom:15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Personal Info</h6>
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Phone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}" required style="border-radius:5px;font-size:14px;"></div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">City</label><input type="text" name="city" class="form-control" value="{{ old('city',$user->city) }}" style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Address</label><input type="text" name="address" class="form-control" value="{{ old('address',$user->address) }}" style="border-radius:5px;font-size:14px;"></div>
            </div>
            <h6 style="font-weight:700;color:#e42e0c;margin:20px 0 15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Vehicle Info</h6>
            <div class="row">
                <div class="col-md-4 form-group mb-3"><label style="font-size:12px;font-weight:600;">Vehicle Type</label>
                    <select name="vehicle_type" class="form-control" style="border-radius:5px;font-size:14px;">
                        @foreach(['Bike','Scooter','Bicycle','Car'] as $v)<option value="{{ $v }}" {{ ($partner->vehicle_type??'')===$v?'selected':'' }}>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group mb-3"><label style="font-size:12px;font-weight:600;">Vehicle Number</label><input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number',$partner->vehicle_number??'') }}" style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-4 form-group mb-3"><label style="font-size:12px;font-weight:600;">License Number</label><input type="text" name="license_number" class="form-control" value="{{ old('license_number',$partner->license_number??'') }}" style="border-radius:5px;font-size:14px;"></div>
            </div>
            <h6 style="font-weight:700;color:#e42e0c;margin:20px 0 15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Change Password</h6>
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">New Password</label><input type="password" name="password" class="form-control" placeholder="Leave blank to keep current" style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Confirm Password</label><input type="password" name="password_confirmation" class="form-control" style="border-radius:5px;font-size:14px;"></div>
            </div>
            <button type="submit" class="btn btn-primary-b2b" style="border-radius:5px;padding:10px 30px;"><i class="fas fa-save mr-2"></i>Save Changes</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
