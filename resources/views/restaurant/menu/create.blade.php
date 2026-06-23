@extends('layouts.restaurant')
@section('title', isset($item) ? 'Edit Item' : 'Add Item')
@section('page_title', isset($item) ? 'Edit Menu Item' : 'Add Menu Item')
@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }} mr-2" style="color:#e42e0c;"></i>{{ isset($item) ? 'Edit' : 'Add' }} Menu Item</div>
    <div class="card-body">
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ isset($item) ? route('restaurant.menu.update',$item->id) : route('restaurant.menu.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="row">
                <div class="col-md-8 form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Item Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name',$item->name ?? '') }}" required style="border-radius:5px;font-size:14px;">
                </div>
                <div class="col-md-4 form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Category *</label>
                    <select name="category_id" class="form-control" required style="border-radius:5px;font-size:14px;">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id',($item->category_id ?? '')) == $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;">Description</label>
                <textarea name="description" class="form-control" rows="3" style="border-radius:5px;font-size:14px;">{{ old('description',$item->description ?? '') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-3 form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Price (₹) *</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price',$item->price ?? '') }}" required style="border-radius:5px;font-size:14px;">
                </div>
                <div class="col-md-3 form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Discount Price (₹)</label>
                    <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price',$item->discount_price ?? '') }}" style="border-radius:5px;font-size:14px;">
                </div>
                <div class="col-md-3 form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Food Type *</label>
                    <select name="food_type" class="form-control" required style="border-radius:5px;font-size:14px;">
                        @foreach(['veg'=>'Veg','non_veg'=>'Non-Veg','vegan'=>'Vegan'] as $v => $l)
                        <option value="{{ $v }}" {{ old('food_type',($item->food_type ?? 'veg')) === $v ? 'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group mb-3">
                    <label style="font-size:13px;font-weight:600;">Prep Time (min)</label>
                    <input type="number" name="preparation_time" class="form-control" value="{{ old('preparation_time',$item->preparation_time ?? 15) }}" style="border-radius:5px;font-size:14px;">
                </div>
            </div>
            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;">Item Image</label>
                @if(isset($item) && $item->image)<img src="{{ $item->image_url }}" style="width:80px;height:80px;border-radius:8px;object-fit:cover;display:block;margin-bottom:10px;">@endif
                <input type="file" name="image" class="form-control" accept="image/*" style="border-radius:5px;font-size:14px;">
            </div>
            <div class="d-flex mb-3" style="gap:20px;">
                <div class="form-check"><input type="checkbox" name="is_available" class="form-check-input" id="avail" {{ old('is_available',($item->is_available ?? true)) ? 'checked' : '' }}><label class="form-check-label" for="avail" style="font-size:13px;">Available</label></div>
                <div class="form-check"><input type="checkbox" name="is_featured" class="form-check-input" id="feat" {{ old('is_featured',($item->is_featured ?? false)) ? 'checked' : '' }}><label class="form-check-label" for="feat" style="font-size:13px;">Featured</label></div>
            </div>
            <div class="d-flex" style="gap:10px;">
                <button type="submit" class="btn btn-primary-b2b" style="border-radius:5px;padding:10px 25px;"><i class="fas fa-save mr-2"></i>{{ isset($item) ? 'Update' : 'Add' }} Item</button>
                <a href="{{ route('restaurant.menu.index') }}" class="btn" style="background:#f0f0f0;color:#555;border-radius:5px;padding:10px 25px;font-size:14px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
