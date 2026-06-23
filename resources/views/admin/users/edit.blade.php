@extends('layouts.admin')
@section('title', 'Edit User #' . $user->id)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-weight:700;margin:0;">Edit User</h4>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">← Back to Users</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $e) <div>{{ $e }}</div> @endforeach
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f0;padding:15px 20px;">
                <h6 style="font-weight:700;margin:0;">User Information</h6>
            </div>
            <div class="card-body" style="padding:25px;">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label style="font-weight:600;font-size:13px;">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control" style="border-radius:5px;font-size:13px;" required>
                    </div>

                    <div class="form-group mb-3">
                        <label style="font-weight:600;font-size:13px;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="form-control" style="border-radius:5px;font-size:13px;" required>
                    </div>

                    <div class="form-group mb-3">
                        <label style="font-weight:600;font-size:13px;">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                            class="form-control" style="border-radius:5px;font-size:13px;">
                    </div>

                    <div class="form-group mb-3">
                        <label style="font-weight:600;font-size:13px;">Role</label>
                        <select name="role" class="form-control" style="border-radius:5px;font-size:13px;">
                            @foreach(['customer','restaurant','delivery','admin'] as $role)
                            <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label style="font-weight:600;font-size:13px;">Status</label>
                        <select name="is_active" class="form-control" style="border-radius:5px;font-size:13px;">
                            <option value="1" {{ ($user->is_active ?? 1) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !($user->is_active ?? 1) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <hr>
                    <p style="font-size:12px;color:#888;margin-bottom:15px;">Leave password blank to keep unchanged.</p>

                    <div class="form-group mb-3">
                        <label style="font-weight:600;font-size:13px;">New Password</label>
                        <input type="password" name="password"
                            class="form-control" style="border-radius:5px;font-size:13px;"
                            placeholder="Leave blank to keep current">
                    </div>

                    <div class="form-group mb-4">
                        <label style="font-weight:600;font-size:13px;">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                            class="form-control" style="border-radius:5px;font-size:13px;">
                    </div>

                    <button type="submit" class="btn"
                        style="background:#e42e0c;color:#fff;border:none;padding:10px 30px;border-radius:5px;font-weight:600;">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users.show', $user->id) }}"
                        class="btn btn-secondary ml-2" style="padding:10px 20px;border-radius:5px;">
                        Cancel
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
            <div class="card-body text-center" style="padding:30px;">
                <div style="width:70px;height:70px;background:#e42e0c;border-radius:50%;color:#fff;
                    display:flex;align-items:center;justify-content:center;font-size:26px;
                    font-weight:700;margin:0 auto 15px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h6 style="font-weight:700;margin:0 0 4px;">{{ $user->name }}</h6>
                <p style="color:#888;font-size:12px;margin:0 0 10px;">{{ $user->email }}</p>
                <span style="background:#252525;color:#fff;padding:3px 12px;border-radius:20px;font-size:11px;">
                    {{ ucfirst($user->role) }}
                </span>
                <hr>
                <p style="font-size:12px;color:#888;margin:0;">
                    Member since {{ $user->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection