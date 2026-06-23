@extends('layouts.admin')
@section('title','Delivery Partner')
@section('page_title','Delivery Partner Details')
@section('content')

<div class="mb-3">
    <a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;">← Back</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-motorcycle mr-2" style="color:#e42e0c;"></i>Partner Information</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Full Name</p>
                        <p style="font-size:14px;font-weight:600;margin:0;">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Email</p>
                        <p style="font-size:13px;margin:0;">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Phone</p>
                        <p style="font-size:13px;margin:0;">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">City</p>
                        <p style="font-size:13px;margin:0;">{{ $user->city ?? '-' }}</p>
                    </div>
                    @if($partner)
                    <div class="col-md-4 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Vehicle Type</p>
                        <p style="font-size:13px;font-weight:600;margin:0;">{{ $partner->vehicle_type ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">Vehicle Number</p>
                        <p style="font-size:13px;margin:0;">{{ $partner->vehicle_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <p style="font-size:12px;color:#888;margin:0;">License Number</p>
                        <p style="font-size:13px;margin:0;">{{ $partner->license_number ?? '-' }}</p>
                    </div>
                    @endif
                </div>

                @if(!$user->is_approved)
                <div class="mt-3">
                    <form method="POST" action="{{ route('admin.delivery.approve',$user->id) }}">
                        @csrf
                        <button class="btn btn-sm" style="background:#4caf50;color:#fff;border:none;border-radius:5px;padding:8px 20px;font-size:13px;">✓ Approve Partner</button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Deliveries -->
        <div class="card">
            <div class="card-header"><i class="fas fa-clipboard-list mr-2" style="color:#e42e0c;"></i>Recent Deliveries</div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td style="font-size:13px;font-weight:600;color:#e42e0c;">{{ $order->order_number }}</td>
                        <td style="font-size:13px;">{{ $order->user->name ?? '-' }}</td>
                        <td style="font-size:13px;font-weight:600;">₹{{ number_format($order->total,2) }}</td>
                        <td>{!! $order->status_badge !!}</td>
                        <td style="font-size:12px;color:#888;">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center" style="padding:20px;color:#888;">No deliveries yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">Stats</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                    <span style="color:#555;">Total Deliveries</span>
                    <strong>{{ $partner->total_deliveries ?? 0 }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                    <span style="color:#555;">Total Earnings</span>
                    <strong style="color:#2e7d32;">₹{{ number_format($partner->earnings_total ?? 0, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                    <span style="color:#555;">Rating</span>
                    <strong>⭐ {{ number_format($partner->rating ?? 0, 1) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                    <span style="color:#555;">Approved</span>
                    <span class="badge-status {{ $user->is_approved ? 'badge-active' : 'badge-pending' }}">{{ $user->is_approved ? 'Yes' : 'Pending' }}</span>
                </div>
                <div class="d-flex justify-content-between" style="font-size:13px;">
                    <span style="color:#555;">Status</span>
                    <span class="badge-status {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Actions</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.delivery-partners.update',$user->id) }}" class="mb-2">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                    <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                    <input type="hidden" name="is_approved" value="{{ $user->is_approved }}">
                    <button type="submit" class="btn btn-sm btn-block" style="background:{{ $user->is_active ? '#fce4ec' : '#e8f5e9' }};color:{{ $user->is_active ? '#c62828' : '#2e7d32' }};border:none;border-radius:5px;padding:8px;font-size:13px;width:100%;">
                        {{ $user->is_active ? 'Suspend Partner' : 'Activate Partner' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection