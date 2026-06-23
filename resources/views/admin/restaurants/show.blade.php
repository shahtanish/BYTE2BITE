@extends('layouts.admin')
@section('title', $restaurant->name)
@section('page_title', 'Restaurant Details')
@section('content')

<div class="mb-3">
    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;">← Back to Restaurants</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-store mr-2" style="color:#e42e0c;"></i>{{ $restaurant->name }}</span>
                <span class="badge-status {{ $restaurant->status === 'approved' ? 'badge-active' : 'badge-pending' }}">{{ ucfirst($restaurant->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Owner</p>
                        <p style="font-size:14px;font-weight:600;margin:0;">{{ $restaurant->user->name ?? '-' }}</p>
                        <p style="font-size:13px;color:#555;margin:0;">{{ $restaurant->user->email ?? '' }}</p>
                        <p style="font-size:13px;color:#555;margin:0;">{{ $restaurant->user->phone ?? '' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Restaurant Phone</p>
                        <p style="font-size:14px;font-weight:600;margin:0;">{{ $restaurant->phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Address</p>
                        <p style="font-size:13px;margin:0;">{{ $restaurant->address }}, {{ $restaurant->city }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Cuisine Type</p>
                        <p style="font-size:13px;margin:0;">{{ $restaurant->cuisine_type ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Opening Time</p>
                        <p style="font-size:13px;margin:0;">{{ $restaurant->opening_time }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Closing Time</p>
                        <p style="font-size:13px;margin:0;">{{ $restaurant->closing_time }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Delivery Fee</p>
                        <p style="font-size:13px;font-weight:600;margin:0;">₹{{ $restaurant->delivery_fee }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Min Order</p>
                        <p style="font-size:13px;font-weight:600;margin:0;">₹{{ $restaurant->min_order_amount }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Avg Delivery Time</p>
                        <p style="font-size:13px;margin:0;">{{ $restaurant->avg_delivery_time }} min</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Rating</p>
                        <p style="font-size:13px;margin:0;">⭐ {{ number_format($restaurant->rating,1) }} ({{ $restaurant->total_reviews }} reviews)</p>
                    </div>
                </div>

                @if($restaurant->status === 'pending')
                <div class="mt-3 d-flex" style="gap:10px;">
                    <form method="POST" action="{{ route('admin.restaurants.approve',$restaurant->id) }}">
                        @csrf
                        <button class="btn btn-sm" style="background:#4caf50;color:#fff;border:none;border-radius:5px;padding:8px 20px;">✓ Approve</button>
                    </form>
                    <button class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:8px 20px;" data-toggle="modal" data-target="#rejectModal">✗ Reject</button>
                </div>
                @endif
            </div>
        </div>

        <!-- Menu Items -->
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-utensils mr-2" style="color:#e42e0c;"></i>Menu Items ({{ $restaurant->foodItems->count() }})</div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <thead><tr><th>Item</th><th>Category</th><th>Price</th><th>Type</th><th>Available</th></tr></thead>
                    <tbody>
                    @forelse($restaurant->foodItems as $item)
                    <tr>
                        <td style="font-size:13px;font-weight:600;">{{ $item->name }}</td>
                        <td style="font-size:13px;">{{ $item->category->name ?? '-' }}</td>
                        <td style="font-size:13px;font-weight:600;">₹{{ number_format($item->price,2) }}</td>
                        <td><span style="font-size:11px;padding:2px 8px;border-radius:3px;background:{{ $item->food_type==='veg' ? '#e8f5e9' : '#fce4ec' }};color:{{ $item->food_type==='veg' ? '#2e7d32' : '#c62828' }};">{{ strtoupper($item->food_type) }}</span></td>
                        <td><span class="badge-status {{ $item->is_available ? 'badge-active' : 'badge-inactive' }}">{{ $item->is_available ? 'Yes' : 'No' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center" style="padding:20px;color:#888;">No menu items added yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Logo -->
        <div class="card mb-3">
            <div class="card-body text-center" style="padding:20px;">
                <img src="{{ $restaurant->logo_url }}" style="width:100px;height:100px;border-radius:10px;object-fit:cover;margin-bottom:10px;">
                <h6 style="font-weight:700;margin:0;">{{ $restaurant->name }}</h6>
                <p style="color:#888;font-size:13px;">{{ $restaurant->city }}</p>
                <span class="badge-status {{ $restaurant->is_open ? 'badge-active' : 'badge-inactive' }}">{{ $restaurant->is_open ? 'Currently Open' : 'Currently Closed' }}</span>
            </div>
        </div>

    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content" style="border-radius:10px;">
        <div class="modal-header" style="background:#e42e0c;color:#fff;border-radius:10px 10px 0 0;">
            <h5 class="modal-title">Reject Restaurant</h5>
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.restaurants.reject',$restaurant->id) }}">@csrf
            <div class="modal-body">
                <div class="form-group">
                    <label style="font-weight:600;font-size:13px;">Reason for rejection *</label>
                    <textarea name="reason" class="form-control" rows="3" required style="border-radius:5px;font-size:14px;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" data-dismiss="modal" style="background:#f0f0f0;color:#555;border-radius:5px;">Cancel</button>
                <button type="submit" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:6px 16px;">Reject</button>
            </div>
        </form>
    </div></div>
</div>

@endsection