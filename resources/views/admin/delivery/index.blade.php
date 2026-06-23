@extends('layouts.admin')
@section('title','Delivery Partners')
@section('page_title','Delivery Partners')
@section('content')
<div class="card mb-3">
    <div class="card-body" style="padding:12px 20px;">
        <form method="GET" class="d-flex" style="gap:10px;">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name..." value="{{ request('search') }}" style="max-width:280px;border-radius:5px;font-size:13px;">
            <button type="submit" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;"><i class="fas fa-search mr-1"></i>Search</button>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header"><i class="fas fa-motorcycle mr-2" style="color:#e42e0c;"></i>Delivery Partners ({{ $partners->total() }})</div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive"><table class="table mb-0">
            <thead><tr><th>Name</th><th>Phone</th><th>Vehicle</th><th>Deliveries</th><th>Earnings</th><th>Approved</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($partners as $user)
            @php $dp = $user->deliveryPartner; @endphp
            <tr>
                <td><strong style="font-size:13px;">{{ $user->name }}</strong><br><small style="color:#aaa;">{{ $user->email }}</small></td>
                <td style="font-size:13px;">{{ $user->phone ?? '-' }}</td>
                <td style="font-size:13px;">{{ $dp->vehicle_type ?? '-' }} — {{ $dp->vehicle_number ?? '-' }}</td>
                <td style="font-size:13px;">{{ $dp->total_deliveries ?? 0 }}</td>
                <td style="font-size:13px;font-weight:600;color:#2e7d32;">₹{{ number_format($dp->earnings_total ?? 0,2) }}</td>
                <td><span class="badge-status {{ $user->is_approved ? 'badge-active' : 'badge-pending' }}">{{ $user->is_approved ? 'Yes' : 'Pending' }}</span></td>
                <td><span class="badge-status {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                    <div class="d-flex" style="gap:5px;flex-wrap:wrap;">
                    @if(!$user->is_approved)
                        <form method="POST" action="{{ route('admin.delivery.approve',$user->id) }}">@csrf<button class="btn btn-sm" style="background:#4caf50;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">Approve</button></form>
                    @endif
                        <a href="{{ route('admin.delivery-partners.show',$user->id) }}" class="btn btn-sm" style="background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">View</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center" style="padding:30px;color:#888;">No delivery partners found.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div>
</div>
<div class="mt-3">{{ $partners->withQueryString()->links() }}</div>
@endsection
