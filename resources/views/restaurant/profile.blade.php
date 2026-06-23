@extends('layouts.restaurant')
@section('title','My Restaurant')
@section('page_title','Restaurant Profile')
@section('content')
<div class="row">
<div class="col-md-8">
<div class="card">
    <div class="card-header"><i class="fas fa-store mr-2" style="color:#e42e0c;"></i>Edit Restaurant Profile</div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success" style="font-size:13px;">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('restaurant.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <h6 style="font-weight:700;color:#e42e0c;margin-bottom:15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Basic Info</h6>
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Restaurant Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$restaurant->name) }}" required style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Phone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$restaurant->phone) }}" required style="border-radius:5px;font-size:14px;"></div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$restaurant->email) }}" style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Cuisine Type</label>
                    <select name="cuisine_type" class="form-control" style="border-radius:5px;font-size:14px;">
                        @foreach(['North Indian','South Indian','Chinese','Italian','Fast Food','Biryani','Pizza','Burger','Desserts','Multi-Cuisine'] as $c)
                        <option value="{{ $c }}" {{ ($restaurant->cuisine_type===$c)?'selected':'' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group mb-3"><label style="font-size:12px;font-weight:600;">Description</label><textarea name="description" class="form-control" rows="3" style="border-radius:5px;font-size:14px;">{{ old('description',$restaurant->description) }}</textarea></div>
            <div class="form-group mb-3"><label style="font-size:12px;font-weight:600;">Address *</label><textarea name="address" class="form-control" rows="2" required style="border-radius:5px;font-size:14px;">{{ old('address',$restaurant->address) }}</textarea></div>
            <div class="row">
                <div class="col-md-4 form-group mb-3"><label style="font-size:12px;font-weight:600;">City *</label><input type="text" name="city" class="form-control" value="{{ old('city',$restaurant->city) }}" required style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-4 form-group mb-3"><label style="font-size:12px;font-weight:600;">State</label><input type="text" name="state" class="form-control" value="{{ old('state',$restaurant->state) }}" style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-4 form-group mb-3"><label style="font-size:12px;font-weight:600;">Pincode</label><input type="text" name="pincode" class="form-control" value="{{ old('pincode',$restaurant->pincode) }}" style="border-radius:5px;font-size:14px;"></div>
            </div>
            <h6 style="font-weight:700;color:#e42e0c;margin:20px 0 15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Operations</h6>
            <div class="row">
                <div class="col-md-3 form-group mb-3"><label style="font-size:12px;font-weight:600;">Opening Time</label><input type="time" name="opening_time" class="form-control" value="{{ old('opening_time',$restaurant->opening_time) }}" required style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-3 form-group mb-3"><label style="font-size:12px;font-weight:600;">Closing Time</label><input type="time" name="closing_time" class="form-control" value="{{ old('closing_time',$restaurant->closing_time) }}" required style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-3 form-group mb-3"><label style="font-size:12px;font-weight:600;">Delivery Fee (₹)</label><input type="number" step="0.01" name="delivery_fee" class="form-control" value="{{ old('delivery_fee',$restaurant->delivery_fee) }}" style="border-radius:5px;font-size:14px;"></div>
                <div class="col-md-3 form-group mb-3"><label style="font-size:12px;font-weight:600;">Avg Delivery (min)</label><input type="number" name="avg_delivery_time" class="form-control" value="{{ old('avg_delivery_time',$restaurant->avg_delivery_time) }}" style="border-radius:5px;font-size:14px;"></div>
            </div>
            <h6 style="font-weight:700;color:#e42e0c;margin:20px 0 15px;border-bottom:2px solid #e42e0c;padding-bottom:8px;">Images</h6>
            <div class="row">
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Logo</label>@if($restaurant->logo)<img src="{{ $restaurant->logo_url }}" style="width:60px;height:60px;border-radius:8px;display:block;margin-bottom:8px;">@endif<input type="file" name="logo" class="form-control" accept="image/*" style="border-radius:5px;font-size:13px;"></div>
                <div class="col-md-6 form-group mb-3"><label style="font-size:12px;font-weight:600;">Banner</label>@if($restaurant->banner)<img src="{{ $restaurant->banner_url }}" style="width:100%;height:60px;object-fit:cover;border-radius:8px;display:block;margin-bottom:8px;">@endif<input type="file" name="banner" class="form-control" accept="image/*" style="border-radius:5px;font-size:13px;"></div>
            </div>
            <button type="submit" class="btn btn-primary-b2b" style="border-radius:5px;padding:10px 30px;"><i class="fas fa-save mr-2"></i>Save Changes</button>
        </form>
    </div>
</div>
</div>
<div class="col-md-4">
    <div class="card" style="border:none;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
        <div class="card-header"><i class="fas fa-info-circle mr-2" style="color:#e42e0c;"></i>Status</div>
        <div class="card-body">
            <p style="font-size:13px;margin-bottom:8px;">Approval: <span style="font-weight:700;color:{{ $restaurant->status==='approved'?'#2e7d32':($restaurant->status==='pending'?'#e65100':'#c62828') }};">{{ ucfirst($restaurant->status) }}</span></p>
            <p style="font-size:13px;margin-bottom:8px;">Currently: <span style="font-weight:700;color:{{ $restaurant->is_open?'#2e7d32':'#e42e0c' }};">{{ $restaurant->is_open ? 'Open' : 'Closed' }}</span></p>
            <p style="font-size:13px;margin:0;">Rating: <span style="font-weight:700;">⭐ {{ number_format($restaurant->rating,1) }}</span> ({{ $restaurant->total_reviews }} reviews)</p>
        </div>
    </div>
</div>
</div>
@endsection
