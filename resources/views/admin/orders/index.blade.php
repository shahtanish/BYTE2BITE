@extends('layouts.admin')
@section('title','All Orders')
@section('page_title','All Orders')
@section('content')

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body" style="padding:15px 20px;">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end">
            <div><label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Order # or customer name" value="{{ request('search') }}" style="min-width:200px;border-radius:5px;font-size:13px;"></div>
            <div><label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Status</label>
                <select name="status" class="form-control form-control-sm" style="border-radius:5px;font-size:13px;">
                    <option value="">All Status</option>
                    @foreach(['pending','accepted','preparing','on_the_way','delivered','cancelled','rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div><label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">From</label><input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}" style="border-radius:5px;font-size:13px;"></div>
            <div><label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">To</label><input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}" style="border-radius:5px;font-size:13px;"></div>
            <button type="submit" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;"><i class="fas fa-filter mr-1"></i>Filter</button>
            <a href="{{ route('admin.orders') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;font-size:13px;">Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clipboard-list mr-2" style="color:#e42e0c;"></i>Orders ({{ $orders->total() }})</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Delivery Partner</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($orders as $order)
            <tr>
                <td><strong style="color:#e42e0c;">{{ $order->order_number }}</strong></td>
                <td>{{ $order->user->name ?? '-' }}<br><small style="color:#aaa;">{{ $order->user->phone ?? '' }}</small></td>
                <td>{{ $order->items->count() }} item(s)</td>
                <td><strong>₹{{ number_format($order->total,2) }}</strong></td>
                <td>
                    <span style="font-size:11px;text-transform:uppercase;background:#f0f0f0;padding:2px 8px;border-radius:3px;">{{ $order->payment_method }}</span><br>
                    <span style="font-size:11px;color:{{ $order->payment_status==='paid' ? '#2e7d32' : '#e65100' }};">{{ ucfirst($order->payment_status) }}</span>
                </td>
                <td>{{ $order->deliveryPartner->name ?? '<span style="color:#aaa;">Unassigned</span>' }}</td>
                <td>{!! $order->status_badge !!}</td>
                <td style="font-size:12px;color:#888;">{{ $order->created_at->format('d M Y') }}<br>{{ $order->created_at->format('h:i A') }}</td>
                <td><a href="{{ route('admin.orders.show',$order->id) }}" class="btn btn-sm btn-primary-b2b" style="white-space:nowrap;">View</a></td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center" style="padding:30px;color:#888;">No orders found.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $orders->withQueryString()->links() }}</div>
@endsection
