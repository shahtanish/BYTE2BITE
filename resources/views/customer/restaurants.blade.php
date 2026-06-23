@extends('layouts.app')
@section('title','Restaurants')
@section('banner')
<div style="background:#252525;padding:40px 0;">
    <div class="container">
        <h2 style="color:#fff;font-weight:700;text-align:center;margin-bottom:20px;">All Restaurants</h2>
        <form action="{{ route('restaurants') }}" method="GET" class="d-flex justify-content-center flex-wrap gap-3">
            <input type="text" name="city" placeholder="City" value="{{ request('city') }}" class="form-control" style="max-width:200px;border-radius:5px;border:none;padding:10px 15px;font-size:14px;">
            <input type="text" name="search" placeholder="Search restaurants..." value="{{ request('search') }}" class="form-control" style="max-width:280px;border-radius:5px;border:none;padding:10px 15px;font-size:14px;margin:0 10px;">
            <button type="submit" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:10px 25px;font-size:14px;font-weight:600;white-space:nowrap;"><i class="fas fa-search mr-2"></i>Search</button>
        </form>
    </div>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;min-height:60vh;">
<div class="container">
<p style="color:#888;font-size:13px;margin-bottom:20px;">{{ $restaurants->total() }} restaurants found</p>
<div class="row">
@forelse($restaurants as $rest)
<div class="col-md-3 mb-4">
    <div class="card h-100" style="border:none;border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.08);overflow:hidden;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-4px)';" onmouseout="this.style.transform='translateY(0)';">
        <div style="position:relative;">
            <img src="{{ $rest->logo_url }}" style="width:100%;height:150px;object-fit:cover;">
            @if(!$rest->is_open)<div style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;"><span style="color:#fff;font-weight:700;font-size:16px;">CLOSED</span></div>@endif
        </div>
        <div class="card-body" style="padding:15px;">
            <h6 style="font-weight:700;color:#252525;margin:0 0 3px;">{{ $rest->name }}</h6>
            <p style="color:#888;font-size:12px;margin-bottom:3px;"><i class="fas fa-map-marker-alt mr-1" style="color:#e42e0c;"></i>{{ $rest->city }}</p>
            <p style="color:#888;font-size:12px;margin-bottom:8px;">{{ $rest->cuisine_type }}</p>
            <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:12px;">
                <span style="color:#f39c12;">@for($i=1;$i<=5;$i++)<i class="fas fa-star"></i>@endfor {{ number_format($rest->rating,1) }}</span>
                <span style="color:#888;"><i class="fas fa-clock mr-1" style="color:#e42e0c;"></i>{{ $rest->avg_delivery_time }} min</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:12px;">
                <span style="color:#888;"><i class="fas fa-motorcycle mr-1" style="color:#e42e0c;"></i>₹{{ $rest->delivery_fee }} delivery</span>
                <span style="color:#888;">{{ $rest->food_items_count }} items</span>
            </div>
            <a href="{{ route('restaurant.menu',$rest->id) }}" class="btn btn-block" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:7px;font-size:13px;font-weight:600;">View Menu</a>
        </div>
    </div>
</div>
@empty
<div class="col-12 text-center py-5">
    <i class="fas fa-store" style="font-size:60px;color:#ddd;margin-bottom:20px;display:block;"></i>
    <h5 style="color:#888;">No restaurants found</h5>
    <a href="{{ route('restaurants') }}" style="color:#e42e0c;">View all restaurants</a>
</div>
@endforelse
</div>
<div class="mt-4">{{ $restaurants->withQueryString()->links() }}</div>
</div>
</div>
@endsection
