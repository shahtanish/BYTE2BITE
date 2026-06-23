<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant') | BYTE2BITE</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #e42e0c; --dark: #252525; --sidebar-width: 240px; }
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }
        .sidebar { width: var(--sidebar-width); background: var(--dark); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 100; overflow-y: auto; }
        .sidebar-brand { padding: 20px; border-bottom: 1px solid #333; }
        .sidebar-brand span { font-size: 18px; font-weight: 700; color: #fff; }
        .sidebar-brand em { color: var(--primary); font-style: normal; }
        .sidebar-nav { padding: 15px 0; }
        .nav-section { color: #888; font-size: 11px; text-transform: uppercase; padding: 10px 20px 5px; letter-spacing: 1px; }
        .sidebar-nav a { display: flex; align-items: center; padding: 10px 20px; color: #ccc; font-size: 13px; text-decoration: none; transition: all 0.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(228,46,12,0.15); color: #fff; border-left: 3px solid var(--primary); }
        .sidebar-nav a i { width: 20px; margin-right: 10px; color: var(--primary); }
        .main-content { margin-left: var(--sidebar-width); }
        .top-bar { background: #fff; padding: 12px 25px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 99; }
        .top-bar h5 { margin: 0; font-size: 16px; font-weight: 600; color: var(--dark); }
        .page-content { padding: 25px; }
        .card { border: none; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 20px; }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; padding: 15px 20px; font-weight: 600; font-size: 14px; border-radius: 8px 8px 0 0 !important; }
        .card-body { padding: 20px; }
        .stat-card { border-radius: 8px; color: #fff; padding: 20px; }
        .stat-red { background: var(--primary); }
        .stat-dark { background: var(--dark); }
        .stat-blue { background: #1976d2; }
        .stat-green { background: #388e3c; }
        .table th { background: #f8f9fa; font-size: 13px; font-weight: 600; }
        .table td { font-size: 13px; vertical-align: middle; }
        .badge-status { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-pending { background: #fff3e0; color: #e65100; }
        .badge-accepted { background: #e3f2fd; color: #1565c0; }
        .badge-preparing { background: #fce4ec; color: #c62828; }
        .badge-delivered { background: #e8f5e9; color: #1b5e20; }
        .badge-rejected { background: #fce4ec; color: #b71c1c; }
        .btn-primary-b2b { background: var(--primary); border-color: var(--primary); color: #fff; font-size: 13px; }
        .btn-primary-b2b:hover { background: #c0250a; color: #fff; }
        @yield('extra_css')
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar">
    <div class="sidebar-brand">
        <span>BYTE<em>2BITE</em></span><br>
        <small style="color:#888;font-size:11px;">Restaurant Panel</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="{{ route('restaurant.dashboard') }}" class="{{ request()->routeIs('restaurant.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <div class="nav-section">Menu</div>
        <a href="{{ route('restaurant.menu.index') }}" class="{{ request()->routeIs('restaurant.menu*') ? 'active' : '' }}">
            <i class="fas fa-utensils"></i> Manage Menu
        </a>
        <a href="{{ route('restaurant.menu.create') }}" class="{{ request()->routeIs('restaurant.menu.create') ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> Add Item
        </a>
        <div class="nav-section">Orders</div>
        <a href="{{ route('restaurant.orders') }}" class="{{ request()->routeIs('restaurant.orders*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Orders
        </a>
        <div class="nav-section">Reports</div>
        <a href="{{ route('restaurant.reports') }}" class="{{ request()->routeIs('restaurant.reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Sales Reports
        </a>
        <div class="nav-section">Account</div>
        <a href="{{ route('restaurant.profile') }}" class="{{ request()->routeIs('restaurant.profile') ? 'active' : '' }}">
            <i class="fas fa-store"></i> My Restaurant
        </a>
        <a href="{{ route('home') }}" target="_blank"><i class="fas fa-globe"></i> View Site</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" style="background:none;border:none;width:100%;text-align:left;">
                <a href="#" onclick="this.closest('form').submit()" style="color:#ccc;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </button>
        </form>
    </nav>
</div>
<div class="main-content">
    <div class="top-bar">
        <h5>@yield('page_title', 'Dashboard')</h5>
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:13px;color:#555;">{{ auth()->user()->restaurant->name ?? auth()->user()->name }}</span>
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
