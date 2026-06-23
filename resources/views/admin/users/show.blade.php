@extends('layouts.admin')
@section('title', 'User #' . $user->id)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight:700;margin:0;">User Details</h4>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">← Back to Users</a>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card mb-4" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-body text-center" style="padding:30px;">
                <div style="width:80px;height:80px;background:#e42e0c;border-radius:50%;color:#fff;
                    display:flex;align-items:center;justify-content:center;font-size:28px;
                    font-weight:700;margin:0 auto 15px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 style="font-weight:700;margin:0 0 5px;">{{ $user->name }}</h5>
                <p style="color:#888;font-size:13px;margin:0 0 15px;">{{ $user->email }}</p>
                <span style="background:{{ $user->role === 'admin' ? '#252525' : ($user->role === 'restaurant' ? '#e42e0c' : '#28a745') }};
                    color:#fff;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card mb-4" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-body" style="padding:20px;">
                <h6 style="font-weight:700;margin-bottom:15px;">Account Status</h6>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px;color:#555;">Status</span>
                    <span style="background:{{ $user->is_active ?? true ? '#d4edda' : '#f8d7da' }};
                        color:{{ $user->is_active ?? true ? '#155724' : '#721c24' }};
                        padding:3px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                        {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px;color:#555;">Joined</span>
                    <span style="font-size:13px;font-weight:600;">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:13px;color:#555;">Last Updated</span>
                    <span style="font-size:13px;font-weight:600;">{{ $user->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- User Info -->
        <div class="card mb-4" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;">
                <h6 style="font-weight:700;margin:0;">User Information</h6>
            </div>
            <div class="card-body" style="padding:20px;">
                <table class="table table-borderless" style="font-size:13px;margin:0;">
                    <tr>
                        <td style="color:#888;width:35%;padding:8px 0;">Full Name</td>
                        <td style="font-weight:600;padding:8px 0;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="color:#888;padding:8px 0;">Email Address</td>
                        <td style="font-weight:600;padding:8px 0;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="color:#888;padding:8px 0;">Phone</td>
                        <td style="font-weight:600;padding:8px 0;">{{ $user->phone ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="color:#888;padding:8px 0;">Role</td>
                        <td style="font-weight:600;padding:8px 0;">{{ ucfirst($user->role) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Recent Orders (if customer) -->
        @if($user->role === 'customer')
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;">
                <h6 style="font-weight:700;margin:0;">Recent Orders</h6>
            </div>
            <div class="card-body" style="padding:0;">
                @php $orders = $user->orders()->latest()->take(5)->get(); @endphp
                @if($orders->count())
                <table class="table table-hover" style="margin:0;font-size:13px;">
                    <thead style="background:#f8f8f8;">
                        <tr>
                            <th style="padding:12px 20px;font-weight:600;">Order #</th>
                            <th style="padding:12px;">Total</th>
                            <th style="padding:12px;">Status</th>
                            <th style="padding:12px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td style="padding:12px 20px;">{{ $order->order_number }}</td>
                            <td style="padding:12px;">₹{{ number_format($order->total, 2) }}</td>
                            <td style="padding:12px;">
                                <span style="background:#fff3cd;color:#856404;padding:2px 10px;
                                    border-radius:20px;font-size:11px;font-weight:600;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="padding:12px;color:#888;">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="padding:20px;color:#888;margin:0;font-size:13px;">No orders yet.</p>
                @endif
            </div>
        </div>
        @endif


    </div>
</div>
@endsection