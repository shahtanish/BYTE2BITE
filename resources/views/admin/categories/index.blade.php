@extends('layouts.admin')
@section('title','Categories')
@section('page_title','Food Categories')
@section('content')
<div class="row">
<div class="col-md-8">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-tags mr-2" style="color:#e42e0c;"></i>Categories ({{ $categories->total() }})</span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;font-size:13px;"><i class="fas fa-plus mr-1"></i>Add Category</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr><th>Category</th><th>Items</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($categories as $cat)
            <tr>
                <td><div class="d-flex align-items-center"><img src="{{ $cat->image_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;margin-right:10px;"><strong style="font-size:13px;">{{ $cat->name }}</strong></div></td>
                <td style="font-size:13px;">{{ $cat->food_items_count }}</td>
                <td><span class="badge-status {{ $cat->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <a href="{{ route('admin.categories.edit',$cat->id) }}" class="btn btn-sm" style="background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy',$cat->id) }}" style="display:inline;">@csrf @method('DELETE')<button class="btn btn-sm" style="background:#fce4ec;color:#c62828;border:none;border-radius:4px;font-size:11px;padding:4px 8px;" onclick="return confirm('Delete?')">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center" style="padding:30px;color:#888;">No categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $categories->links() }}</div>
</div>
<div class="col-md-4">
<div class="card">
    <div class="card-header"><i class="fas fa-plus mr-2" style="color:#e42e0c;"></i>Add Category</div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success alert-sm" style="font-size:13px;">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label style="font-size:12px;font-weight:600;">Name *</label>
                <input type="text" name="name" class="form-control form-control-sm" required style="border-radius:5px;font-size:13px;">
            </div>
            <div class="form-group mb-3">
                <label style="font-size:12px;font-weight:600;">Description</label>
                <textarea name="description" class="form-control form-control-sm" rows="2" style="border-radius:5px;font-size:13px;"></textarea>
            </div>
            <div class="form-group mb-3">
                <label style="font-size:12px;font-weight:600;">Image</label>
                <input type="file" name="image" class="form-control form-control-sm" accept="image/*" style="border-radius:5px;font-size:13px;">
            </div>
            <div class="form-group mb-3">
                <label style="font-size:12px;font-weight:600;">Sort Order</label>
                <input type="number" name="sort_order" class="form-control form-control-sm" value="0" style="border-radius:5px;font-size:13px;">
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" id="active" checked>
                <label class="form-check-label" for="active" style="font-size:13px;">Active</label>
            </div>
            <button type="submit" class="btn btn-sm btn-primary-b2b btn-block" style="border-radius:5px;">Add Category</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
