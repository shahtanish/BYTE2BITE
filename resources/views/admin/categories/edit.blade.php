@extends('layouts.admin')
@section('title','Edit Category')
@section('page_title','Edit Category')
@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-tags mr-2" style="color:#e42e0c;"></i>Edit Category</span>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;">← Back</a>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success" style="font-size:13px;">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('admin.categories.update',$category->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;">Category Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name',$category->name) }}" required style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;">
            </div>
            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;">Description</label>
                <textarea name="description" class="form-control" rows="3" style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;">{{ old('description',$category->description) }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order',$category->sort_order) }}" style="border-radius:5px;font-size:14px;border:1px solid #ddd;padding:10px 15px;">
            </div>
            <div class="form-group mb-3">
                <label style="font-size:13px;font-weight:600;">Image</label>
                @if($category->image)
                    <div class="mb-2"><img src="{{ $category->image_url }}" style="width:60px;height:60px;border-radius:8px;object-fit:cover;"></div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" style="border-radius:5px;font-size:13px;">
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" id="active" {{ $category->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="active" style="font-size:13px;">Active</label>
            </div>
            <div class="d-flex" style="gap:10px;">
                <button type="submit" class="btn btn-primary-b2b" style="border-radius:5px;padding:10px 25px;"><i class="fas fa-save mr-2"></i>Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn" style="background:#f0f0f0;color:#555;border-radius:5px;padding:10px 25px;font-size:14px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection