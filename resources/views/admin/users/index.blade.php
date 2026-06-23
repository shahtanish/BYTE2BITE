@extends('layouts.admin')
@section('title','Customers')
@section('page_title','Customers')
@section('content')
<div class="card mb-3">
    <div class="card-body" style="padding:12px 20px;">
        <form method="GET" class="d-flex align-items-center" style="gap:10px;">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}" style="max-width:300px;border-radius:5px;font-size:13px;">
            <button type="submit" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;"><i class="fas fa-search mr-1"></i>Search</button>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header"><i class="fas fa-users mr-2" style="color:#e42e0c;"></i>Customers ({{ $users->total() }})</div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive"><table class="table mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Status</th><th>Registered</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($users as $user)
            <tr>
                <td><strong style="font-size:13px;">{{ $user->name }}</strong></td>
                <td style="font-size:13px;">{{ $user->email }}</td>
                <td style="font-size:13px;">{{ $user->phone ?? '-' }}</td>
                <td style="font-size:13px;">{{ $user->city ?? '-' }}</td>
                <td><span class="badge-status {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $user->is_active ? 'Active' : 'Suspended' }}</span></td>
                <td style="font-size:12px;color:#888;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div class="d-flex" style="gap:5px;">
                        <a href="{{ route('admin.users.show',$user->id) }}" class="btn btn-sm" style="background:#1976d2;color:#fff;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">View</a>
                        <form method="POST" action="{{ route('admin.users.toggle',$user->id) }}">@csrf<button class="btn btn-sm" style="background:#f0f0f0;color:#555;border:none;border-radius:4px;font-size:11px;padding:4px 8px;">{{ $user->is_active ? 'Suspend' : 'Activate' }}</button></form>
                        <form method="POST" action="{{ route('admin.users.destroy',$user->id) }}">@csrf @method('DELETE')<button class="btn btn-sm" style="background:#fce4ec;color:#c62828;border:none;border-radius:4px;font-size:11px;padding:4px 8px;" onclick="return confirm('Delete this user?')">Delete</button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center" style="padding:30px;color:#888;">No customers found.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div>
</div>
<div class="mt-3">{{ $users->withQueryString()->links() }}</div>
@endsection
