@extends('layouts.app')
@section('title','Search Results')
@section('banner')
<div style="background:#252525;padding:35px 0;text-align:center;">
    <h3 style="color:#fff;font-weight:700;margin:0;">Search Results for "{{ $q }}"</h3>
    <p style="color:#aaa;margin:5px 0 0;">{{ $restaurants->count() + $foods->count() }} results found</p>
</div>
@endsection
@section('content')
<div style="background:#f8f8f8;padding:30px 0;min-height:60vh;">
<div class="container">
    @if($restaurants->count())
    <h5 style="font-weight:700;color:#252525;margin-bottom:20px;border-left:4px solid #e42e0c;padding-left:12px;">Restaurants</h5>
    <div class="row mb-4">
        @foreach($restaurants as $rest)
        <div class="col-md-3 mb-3">
            <div class="card h-100" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);overflow:hidden;">
                <img src="{{ $rest->logo_url }}" style="width:100%;height:130px;object-fit:cover;">
                <div class="card-body" style="padding:12px;"><h6 style="font-weight:700;font-size:13px;margin:0 0 3px;">{{ $rest->name }}</h6><p style="color:#888;font-size:12px;margin:0 0 8px;">{{ $rest->city }}</p><a href="{{ route('restaurant.menu',$rest->id) }}" class="btn btn-block btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;font-size:12px;">View Menu</a></div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @if($foods->count())
    <h5 style="font-weight:700;color:#252525;margin-bottom:20px;border-left:4px solid #e42e0c;padding-left:12px;">Food Items</h5>
    <div class="row">
        @foreach($foods as $item)
        <div class="col-md-3 mb-3">
            <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);overflow:hidden;">
                <img src="{{ $item->image_url }}" style="width:100%;height:120px;object-fit:cover;">
                <div class="card-body" style="padding:12px;"><h6 style="font-weight:700;font-size:13px;margin:0 0 3px;">{{ $item->name }}</h6><p style="color:#888;font-size:11px;margin:0 0 5px;">{{ $item->restaurant->name ?? '' }}</p><div class="d-flex justify-content-between align-items-center"><span style="color:#e42e0c;font-weight:700;">₹{{ number_format($item->effective_price,2) }}</span><button onclick="addToCart({{ $item->id }},1)" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 10px;">+Add</button></div></div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @if(!$restaurants->count() && !$foods->count())
    <div class="text-center py-5"><i class="fas fa-search" style="font-size:60px;color:#ddd;display:block;margin-bottom:20px;"></i><h5 style="color:#888;">No results for "{{ $q }}"</h5><a href="{{ route('restaurants') }}" style="color:#e42e0c;">Browse all restaurants</a></div>
    @endif
</div>
</div>
@endsection
@push('scripts')
<script>
function addToCart(id,qty){ @auth $.post('{{ route("customer.cart.add") }}',{food_item_id:id,quantity:qty},function(r){ if(r.success){$('#cart-count').text(r.count);} }); @else window.location='{{ route("login") }}'; @endauth }
</script>
@endpush
