@extends('layouts.admin')
@section('title','Restaurants')
@section('page_title','Restaurants')
@section('content')
<div class="card mb-4">
    <div class="card-body" style="padding:12px 20px;">
        <form method="GET" class="d-flex flex-wrap align-items-end" style="gap:10px;">
            <div><input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}" style="border-radius:5px;font-size:13px;min-width:200px;"></div>

            <button type="submit" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;"><i class="fas fa-filter mr-1"></i>Filter</button>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header"><i class="fas fa-store mr-2" style="color:#e42e0c;"></i>Restaurants ({{ $restaurants->total() }})</div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Restaurant</th><th>Owner</th><th>City</th><th>Cuisine</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($restaurants as $rest)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $rest->logo_url }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;margin-right:10px;">
                        <div><strong style="font-size:13px;">{{ $rest->name }}</strong><br><small style="color:#888;">{{ $rest->phone }}</small></div>
                    </div>
                </td>
                <td style="font-size:13px;">{{ $rest->user->name ?? '-' }}<br><small style="color:#aaa;">{{ $rest->user->email ?? '' }}</small></td>
                <td style="font-size:13px;">{{ $rest->city }}</td>
                <td style="font-size:13px;">{{ $rest->cuisine_type ?? '-' }}</td>
                <td style="font-size:13px;">⭐ {{ number_format($rest->rating,1) }}</td>
                <td>
                    @if($rest->status==='pending')<span class="badge-status badge-pending">Pending</span>
                    @elseif($rest->status==='approved')<span class="badge-status badge-delivered">Approved</span>
                    @elseif($rest->status==='rejected')<span class="badge-status badge-rejected">Rejected</span>
                    @else<span class="badge-status badge-inactive">{{ ucfirst($rest->status) }}</span>@endif
                </td>
                <td>
                    <div class="d-flex" style="gap:5px;flex-wrap:wrap;">
                    @if($rest->status==='pending')
                        <form method="POST" action="{{ route('admin.restaurants.approve',$rest->id) }}">@csrf<button class="btn btn-sm" style="background:#4caf50;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">✓ Approve</button></form>
                        <button onclick="rejectModal({{ $rest->id }})" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">✗ Reject</button>
                    @endif
                    <a href="{{ route('admin.restaurants.show',$rest->id) }}" class="btn btn-sm" style="background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">View</a>
                    <form method="POST" action="{{ route('admin.restaurants.toggle',$rest->id) }}">@csrf<button class="btn btn-sm" style="background:#f0f0f0;color:#555;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">{{ $rest->is_active ? 'Disable' : 'Enable' }}</button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center" style="padding:30px;color:#888;">No restaurants found.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $restaurants->withQueryString()->links() }}</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content" style="border-radius:10px;">
        <div class="modal-header" style="background:#e42e0c;color:#fff;border-radius:10px 10px 0 0;"><h5 class="modal-title">Reject Restaurant</h5><button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:1;">&times;</button></div>
        <form id="reject-form" method="POST"><@csrf>
            @csrf
            <div class="modal-body"><div class="form-group"><label style="font-weight:600;font-size:13px;">Reason for rejection</label><textarea name="reason" class="form-control" rows="3" required style="border-radius:5px;font-size:14px;"></textarea></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sm" style="background:#e42e0c;color:#fff;border:none;border-radius:5px;">Reject</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>
function rejectModal(id) {
    document.getElementById('reject-form').action = '/admin/restaurants/' + id + '/reject';
    $('#rejectModal').modal('show');
}
</script>
@endpush
