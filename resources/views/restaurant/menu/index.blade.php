@extends('layouts.restaurant')
@section('title','Menu')
@section('page_title','Manage Menu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="{{ route('restaurant.menu.create') }}" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;"><i class="fas fa-plus mr-1"></i>Add Item</a>
</div>
<div class="card">
    <div class="card-header"><i class="fas fa-utensils mr-2" style="color:#e42e0c;"></i>Menu Items ({{ $items->total() }})</div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive"><table class="table mb-0">
            <thead><tr><th>Item</th><th>Category</th><th>Price</th><th>Type</th><th>Available</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($items as $item)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $item->image_url }}" style="width:44px;height:44px;border-radius:8px;object-fit:cover;margin-right:10px;">
                        <div><strong style="font-size:13px;">{{ $item->name }}</strong>@if($item->is_featured)<span style="background:#fff3e0;color:#e65100;font-size:10px;padding:2px 6px;border-radius:3px;margin-left:6px;">Featured</span>@endif</div>
                    </div>
                </td>
                <td style="font-size:13px;">{{ $item->category->name ?? '-' }}</td>
                <td style="font-size:13px;font-weight:600;">₹{{ number_format($item->effective_price,2) }}@if($item->discount_price)<br><small style="color:#999;text-decoration:line-through;font-weight:normal;">₹{{ number_format($item->price,2) }}</small>@endif</td>
                <td><span style="font-size:11px;padding:2px 8px;border-radius:3px;background:{{ $item->food_type==='veg' ? '#e8f5e9' : '#fce4ec' }};color:{{ $item->food_type==='veg' ? '#2e7d32' : '#c62828' }};">{{ strtoupper(str_replace('_',' ',$item->food_type)) }}</span></td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="sw-{{ $item->id }}" {{ $item->is_available ? 'checked' : '' }} onchange="toggleItem({{ $item->id }})">
                        <label class="custom-control-label" for="sw-{{ $item->id }}"></label>
                    </div>
                </td>
                <td>
                    <a href="{{ route('restaurant.menu.edit',$item->id) }}" class="btn btn-sm" style="background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">Edit</a>
                    <form method="POST" action="{{ route('restaurant.menu.destroy',$item->id) }}" style="display:inline;">@csrf @method('DELETE')<button class="btn btn-sm" style="background:#fce4ec;color:#c62828;border:none;border-radius:4px;font-size:11px;padding:4px 8px;" onclick="return confirm('Delete this item?')">Delete</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center" style="padding:30px;color:#888;">No menu items yet. <a href="{{ route('restaurant.menu.create') }}" style="color:#e42e0c;">Add your first item!</a></td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div>
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
@push('scripts')
<script>
function toggleItem(id) {
    $.post('/restaurant/menu/' + id + '/toggle', {}, function(res) {
        console.log(res.available ? 'Available' : 'Unavailable');
    });
}
</script>
@endpush
