@extends('layouts.restaurant')
@section('title','Order Detail')
@section('page_title','Order Detail')
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-receipt mr-2" style="color:#e42e0c;"></i>Order #{{ $order->order_number }}</span>
                <div>
                    <span class="badge-status badge-{{ $status->status }}">{{ ucfirst($status->status) }}</span>
                    <a href="{{ route('restaurant.orders') }}" class="btn btn-sm ml-2" style="background:#f0f0f0;color:#555;border-radius:5px;font-size:12px;">← Back</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))<div class="alert alert-success" style="font-size:13px;">{{ session('success') }}</div>@endif

                <!-- Status Actions -->
                @if($status->status === 'pending')
                <div class="d-flex mb-4" style="gap:10px;">
                    <form method="POST" action="{{ route('restaurant.orders.accept',$order->id) }}">@csrf<button class="btn" style="background:#4caf50;color:#fff;border:none;border-radius:5px;padding:8px 20px;font-size:13px;font-weight:600;"><i class="fas fa-check mr-2"></i>Accept Order</button></form>
                    <button class="btn" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:8px 20px;font-size:13px;font-weight:600;" data-toggle="modal" data-target="#rejectModal"><i class="fas fa-times mr-2"></i>Reject</button>
                </div>
                @elseif($status->status === 'accepted')
                <form method="POST" action="{{ route('restaurant.orders.status',$order->id) }}" class="mb-4">@csrf<input type="hidden" name="status" value="preparing"><button class="btn" style="background:#1976d2;color:#fff;border:none;border-radius:5px;padding:8px 20px;font-size:13px;font-weight:600;"><i class="fas fa-fire mr-2"></i>Start Preparing</button></form>
                @elseif($status->status === 'preparing')
                <form method="POST" action="{{ route('restaurant.orders.status',$order->id) }}" class="mb-4">@csrf<input type="hidden" name="status" value="ready"><button class="btn" style="background:#388e3c;color:#fff;border:none;border-radius:5px;padding:8px 20px;font-size:13px;font-weight:600;"><i class="fas fa-check-circle mr-2"></i>Mark as Ready</button></form>
                @endif

                <!-- Order Items -->
                <h6 style="font-weight:700;color:#252525;margin-bottom:15px;border-bottom:1px solid #f0f0f0;padding-bottom:10px;">Items for Your Restaurant</h6>
                @foreach($order->items as $item)
                <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom:1px solid #f8f8f8;">
                    <div style="flex:1;">
                        <h6 style="font-weight:600;font-size:14px;margin:0 0 3px;">{{ $item->food_name }}</h6>
                        @if($item->special_instructions)<small style="color:#e65100;"><i class="fas fa-info-circle mr-1"></i>{{ $item->special_instructions }}</small>@endif
                    </div>
                    <div style="text-align:right;">
                        <span style="color:#888;font-size:13px;">₹{{ number_format($item->food_price,2) }} × {{ $item->quantity }}</span><br>
                        <strong style="color:#252525;">₹{{ number_format($item->subtotal,2) }}</strong>
                    </div>
                </div>
                @endforeach
                <div class="d-flex justify-content-end mt-2" style="font-size:15px;font-weight:700;color:#e42e0c;">
                    Your subtotal: ₹{{ number_format($order->items->sum('subtotal'),2) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user mr-2" style="color:#e42e0c;"></i>Customer</div>
            <div class="card-body">
                <p style="font-size:13px;font-weight:600;margin-bottom:3px;">{{ $order->user->name }}</p>
                <p style="font-size:13px;color:#555;margin-bottom:3px;">{{ $order->user->phone }}</p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>Delivery To</div>
            <div class="card-body">
                <p style="font-size:13px;margin-bottom:3px;"><strong>{{ $order->delivery_name }}</strong></p>
                <p style="font-size:13px;color:#555;margin-bottom:3px;">{{ $order->delivery_phone }}</p>
                <p style="font-size:13px;color:#555;margin:0;">{{ $order->delivery_address }}, {{ $order->delivery_city }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-clock mr-2" style="color:#e42e0c;"></i>Timeline</div>
            <div class="card-body">
                <p style="font-size:13px;color:#555;margin-bottom:5px;">Ordered: <strong>{{ $order->created_at->format('d M Y, h:i A') }}</strong></p>
                @if($status->accepted_at)<p style="font-size:13px;color:#555;margin-bottom:5px;">Accepted: <strong>{{ $status->accepted_at->format('h:i A') }}</strong></p>@endif
                @if($status->ready_at)<p style="font-size:13px;color:#555;margin:0;">Ready: <strong>{{ $status->ready_at->format('h:i A') }}</strong></p>@endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content" style="border-radius:10px;">
        <div class="modal-header" style="background:#e42e0c;color:#fff;border-radius:10px 10px 0 0;"><h5 class="modal-title">Reject Order</h5><button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button></div>
        <form method="POST" action="{{ route('restaurant.orders.reject',$order->id) }}">@csrf
            <div class="modal-body"><div class="form-group"><label style="font-weight:600;font-size:13px;">Reason *</label><textarea name="reason" class="form-control" rows="3" required style="border-radius:5px;font-size:14px;"></textarea></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-sm" data-dismiss="modal" style="background:#f0f0f0;color:#555;border-radius:5px;">Cancel</button><button type="submit" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:6px 16px;">Reject</button></div>
        </form>
    </div></div>
</div>
@endsection
