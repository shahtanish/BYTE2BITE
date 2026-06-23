<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | BYTE2BITE Admin</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #e42e0c; --dark: #252525; --sidebar-width: 240px; }
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
        .sidebar {
            width: var(--sidebar-width); background: var(--dark);
            min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 100;
            overflow-y: auto; transition: all 0.3s;
        }
        .sidebar-brand { padding: 20px 20px; border-bottom: 1px solid #333; }
        .sidebar-brand span { font-size: 20px; font-weight: 700; color: #fff; }
        .sidebar-brand span em { color: var(--primary); font-style: normal; }
        .sidebar-nav { padding: 15px 0; }
        .sidebar-nav .nav-section { color: #888; font-size: 11px; text-transform: uppercase; padding: 10px 20px 5px; letter-spacing: 1px; }
        .sidebar-nav a {
            display: flex; align-items: center; padding: 10px 20px; color: #ccc;
            font-size: 13px; text-decoration: none; transition: all 0.2s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(228,46,12,0.15); color: #fff; border-left: 3px solid var(--primary); }
        .sidebar-nav a i { width: 20px; margin-right: 10px; color: var(--primary); }
        .main-content { margin-left: var(--sidebar-width); padding: 0; min-height: 100vh; }
        .top-bar {
            background: #fff; padding: 12px 25px; border-bottom: 1px solid #e0e0e0;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 99;
        }
        .top-bar h5 { margin: 0; font-size: 16px; font-weight: 600; color: var(--dark); }
        .top-bar .user-info { display: flex; align-items: center; gap: 10px; }
        .top-bar .user-info img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .page-content { padding: 25px; }
        .card { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 20px; }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; padding: 15px 20px; font-weight: 600; font-size: 14px; border-radius: 8px 8px 0 0 !important; }
        .card-body { padding: 20px; }
        .stat-card { border-radius: 8px; color: #fff; padding: 20px; display: flex; align-items: center; gap: 15px; }
        .stat-card .stat-icon { font-size: 36px; opacity: 0.8; }
        .stat-card .stat-value { font-size: 28px; font-weight: 700; }
        .stat-card .stat-label { font-size: 13px; opacity: 0.9; }
        .stat-red { background: var(--primary); }
        .stat-dark { background: var(--dark); }
        .stat-blue { background: #1976d2; }
        .stat-green { background: #388e3c; }
        .table th { background: #f8f9fa; font-size: 13px; font-weight: 600; color: var(--dark); }
        .table td { font-size: 13px; vertical-align: middle; }
        .badge-status { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-pending { background: #fff3e0; color: #e65100; }
        .badge-accepted { background: #e3f2fd; color: #1565c0; }
        .badge-preparing { background: #fce4ec; color: #c62828; }
        .badge-on_the_way { background: #e8f5e9; color: #2e7d32; }
        .badge-delivered { background: #e8f5e9; color: #1b5e20; }
        .badge-rejected { background: #fce4ec; color: #b71c1c; }
        .badge-active { background: #e8f5e9; color: #2e7d32; }
        .badge-inactive { background: #f5f5f5; color: #757575; }
        .btn-primary-b2b { background: var(--primary); border-color: var(--primary); color: #fff; font-size: 13px; }
        .btn-primary-b2b:hover { background: #c0250a; color: #fff; }
        .btn-sm { padding: 4px 10px; font-size: 12px; }
        @yield('extra_css')
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <span>BYTE<em>2BITE</em></span><br>
        <small style="color:#888;font-size:11px;">Admin Panel</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <div class="nav-section">Management</div>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Customers
        </a>
        <a href="{{ route('admin.restaurants.index') }}" class="{{ request()->routeIs('admin.restaurants*') ? 'active' : '' }}">
            <i class="fas fa-store"></i> Restaurants
        </a>
        <a href="{{ route('admin.delivery-partners.index') }}" class="{{ request()->routeIs('admin.delivery*') ? 'active' : '' }}">
            <i class="fas fa-motorcycle"></i> Delivery Partners
        </a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Categories
        </a>

        <div class="nav-section">Orders & Reports</div>
        <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> All Orders
        </a>
        <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Reports
        </a>

        <div class="nav-section">Account</div>
        <a href="{{ route('home') }}" target="_blank">
            <i class="fas fa-globe"></i> View Site
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;width:100%;text-align:left;">
                <a href="#" onclick="this.closest('form').submit()" style="color:#ccc;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </button>
        </form>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="top-bar">
        <h5>@yield('page_title', 'Dashboard')</h5>
        <div class="user-info">
            <span style="font-size:13px;color:#555;">{{ auth()->user()->name }}</span>
            <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="{{ asset('js/jquery-3.0.0.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    setTimeout(function(){ $('.alert-dismissible').fadeOut('slow'); }, 4000);
</script>
@stack('scripts')
</body>
</html>
