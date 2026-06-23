@extends('layouts.restaurant')
@section('title','Orders')
@section('page_title','Manage Orders')
@section('content')

<div class="card mb-3">
    <div class="card-body" style="padding:12px 20px;">
        <form method="GET" class="d-flex align-items-center" style="gap:10px;">
            <select name="status" class="form-control form-control-sm" style="max-width:180px;border-radius:5px;font-size:13px;">
                <option value="">All Status</option>
                @foreach(['pending','accepted','preparing','ready','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;"><i class="fas fa-filter mr-1"></i>Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-clipboard-list mr-2" style="color:#e42e0c;"></i>Orders</div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Amount</th><th>My Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($statuses as $rs)
            @php $order = $rs->order; @endphp
            <tr>
                <td><strong style="color:#e42e0c;font-size:13px;">{{ $order->order_number ?? '-' }}</strong></td>
                <td style="font-size:13px;">{{ $order->user->name ?? '-' }}<br><small style="color:#aaa;">{{ $order->user->phone ?? '' }}</small></td>
                <td style="font-size:13px;">{{ $order->items->count() }} item(s)<br><small style="color:#888;">₹{{ number_format($order->subtotal,2) }}</small></td>
                <td style="font-size:13px;font-weight:600;">₹{{ number_format($order->total,2) }}</td>
                <td><span class="badge-status badge-{{ $rs->status }}">{{ ucfirst($rs->status) }}</span></td>
                <td style="font-size:12px;color:#888;">{{ $rs->created_at->format('d M Y') }}<br>{{ $rs->created_at->format('h:i A') }}</td>
                <td>
                    <div class="d-flex flex-column" style="gap:4px;">
                        <a href="{{ route('restaurant.orders.show',$order->id) }}" class="btn btn-sm btn-primary-b2b" style="border-radius:4px;font-size:11px;white-space:nowrap;">View Details</a>
                        @if($rs->status === 'pending')
                        <form method="POST" action="{{ route('restaurant.orders.accept',$order->id) }}">@csrf<button class="btn btn-sm" style="background:#4caf50;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">✓ Accept</button></form>
                        <button onclick="showReject({{ $order->id }})" class="btn btn-sm" style="background:#fce4ec;color:#c62828;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">✗ Reject</button>
                        @elseif($rs->status === 'accepted')
                        <form method="POST" action="{{ route('restaurant.orders.status',$order->id) }}">@csrf<input type="hidden" name="status" value="preparing"><button class="btn btn-sm" style="background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">🔥 Preparing</button></form>
                        @elseif($rs->status === 'preparing')
                        <form method="POST" action="{{ route('restaurant.orders.status',$order->id) }}">@csrf<input type="hidden" name="status" value="ready"><button class="btn btn-sm" style="background:#388e3c;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">✓ Ready</button></form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center" style="padding:40px;color:#888;"><i class="fas fa-clipboard-list" style="font-size:36px;color:#ddd;display:block;margin-bottom:10px;"></i>No orders found.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $statuses->links() }}</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content" style="border-radius:10px;">
        <div class="modal-header" style="background:#e42e0c;color:#fff;border-radius:10px 10px 0 0;">
            <h5 class="modal-title">Reject Order</h5>
            <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button>
        </div>
        <form id="reject-form" method="POST">@csrf
            <div class="modal-body">
                <div class="form-group"><label style="font-weight:600;font-size:13px;">Reason for rejection *</label>
                <textarea name="reason" class="form-control" rows="3" required style="border-radius:5px;font-size:14px;" placeholder="e.g. Item out of stock, Restaurant closing early..."></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-sm" data-dismiss="modal" style="background:#f0f0f0;color:#555;border-radius:5px;">Cancel</button><button type="submit" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:6px 16px;">Reject Order</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>
function showReject(orderId) {
    document.getElementById('reject-form').action = '/restaurant/orders/' + orderId + '/reject';
    $('#rejectModal').modal('show');
}
</script>
@endpush
