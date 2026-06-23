<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BYTE2BITE') | Multi-Vendor Food Delivery</title>
    <meta name="keywords" content="food delivery, online food ordering, byte2bite">
    <meta name="description" content="@yield('meta_description', 'Order food from multiple restaurants in a single order')">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary: #e42e0c; --dark: #252525; --light-bg: #efefef; }
        body { font-family: 'Poppins', sans-serif; }
        .btn-primary-b2b { background-color: var(--primary); border-color: var(--primary); color: #fff; border-radius: 3px; padding: 8px 25px; font-size: 14px; }
        .btn-primary-b2b:hover { background-color: #c0250a; color: #fff; }
        .btn-dark-b2b { background-color: var(--dark); border-color: var(--dark); color: #fff; border-radius: 3px; padding: 8px 25px; }
        .alert { border-radius: 3px; }
        .cart-count-badge { background: var(--primary); color: #fff; border-radius: 50%; font-size: 11px; padding: 2px 6px; margin-left: 2px; }
        .header_section .navbar { background: #fff !important; }
        .header_section .nav-link { color: #282827 !important; font-size: 14px; font-weight: 500; }
        .header_section .nav-link:hover { color: var(--primary) !important; }
        /* Dropdown styles */
        .user-dropdown-wrap { position: relative; display: inline-block; }
        .user-dropdown-btn { background: #e42e0c; color: #fff; border: none; padding: 8px 16px; border-radius: 3px; font-size: 14px; font-family: 'Poppins', sans-serif; cursor: pointer; display: flex; align-items: center; gap: 6px; line-height: 1; }
        .user-dropdown-btn:hover { background: #c0250a; }
        #userDropdownMenu { display: none; position: absolute; right: 0; top: calc(100% + 5px); background: #fff; min-width: 200px; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,0.15); z-index: 99999; overflow: hidden; border: 1px solid #f0f0f0; }
        #userDropdownMenu.open { display: block; }
        #userDropdownMenu a { display: flex; align-items: center; padding: 12px 16px; color: #333; font-size: 13px; text-decoration: none; border-bottom: 1px solid #f5f5f5; gap: 10px; font-family: 'Poppins', sans-serif; }
        #userDropdownMenu a:hover { background: #fff5f5; color: #e42e0c; }
        #userDropdownMenu .logout-btn { display: flex; align-items: center; padding: 12px 16px; color: #e42e0c; font-size: 13px; background: none; border: none; width: 100%; cursor: pointer; font-family: 'Poppins', sans-serif; gap: 10px; }
        #userDropdownMenu .logout-btn:hover { background: #fff5f5; }
        @yield('extra_css')
    </style>
    @stack('styles')
</head>
<body>

<!-- header -->
<div class="header_section">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span style="font-size:22px;font-weight:700;color:var(--primary);">BYTE<span style="color:var(--dark);">2BITE</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('home') }}"><i class="fa fa-angle-right mr-1"></i>Home</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('restaurants') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('restaurants') }}"><i class="fa fa-angle-right mr-1"></i>Restaurants</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('about') }}"><i class="fa fa-angle-right mr-1"></i>About</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('contact') }}"><i class="fa fa-angle-right mr-1"></i>Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center ml-3">
                    @auth
                        @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.cart') }}" style="background:var(--dark);border-radius:3px;padding:7px 15px;text-decoration:none;margin-right:8px;">
                            <i class="fas fa-shopping-cart" style="color:#fff;"></i>
                            <span class="cart-count-badge" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                        </a>
                        @endif

                        <div class="user-dropdown-wrap">
                            <button class="user-dropdown-btn" id="userDropdownBtn">
                                <i class="fas fa-user"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-caret-down" style="font-size:11px;"></i>
                            </button>
                            <div id="userDropdownMenu">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt" style="color:#e42e0c;width:16px;"></i> Admin Panel
                                    </a>
                                @elseif(auth()->user()->role === 'restaurant')
                                    <a href="{{ route('restaurant.dashboard') }}">
                                        <i class="fas fa-store" style="color:#e42e0c;width:16px;"></i> Restaurant Panel
                                    </a>
                                @elseif(auth()->user()->role === 'delivery')
                                    <a href="{{ route('delivery.dashboard') }}">
                                        <i class="fas fa-motorcycle" style="color:#e42e0c;width:16px;"></i> Delivery Panel
                                    </a>
                                @else
                                    <a href="{{ route('customer.profile') }}">
                                        <i class="fas fa-user" style="color:#e42e0c;width:16px;"></i> My Profile
                                    </a>
                                    <a href="{{ route('customer.orders') }}">
                                        <i class="fas fa-clipboard-list" style="color:#e42e0c;width:16px;"></i> My Orders
                                    </a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="logout-btn">
                                        <i class="fas fa-sign-out-alt" style="width:16px;"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" style="background:#e42e0c;color:#fff;padding:7px 18px;border-radius:3px;font-size:14px;text-decoration:none;">Login</a>
                        <a href="{{ route('register') }}" style="background:#252525;color:#fff;padding:7px 18px;border-radius:3px;font-size:14px;text-decoration:none;margin-left:5px;">Register</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
    @yield('banner')
</div>

@yield('content')

<!-- footer -->
<div class="footer_section layout_padding" style="background:#252525;">
    <div class="container">
        <div class="footer_section_2">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <h2 style="color:#fff;font-size:20px;font-weight:700;">BYTE<span style="color:var(--primary);">2BITE</span></h2>
                    <p style="color:#aaa;font-size:13px;margin-top:10px;">Your favourite multi-vendor food delivery platform. Order from multiple restaurants in a single cart.</p>
                    <div class="mt-3">
                        <a href="#" class="mr-2"><img src="{{ asset('images/facebook-icon.png') }}" width="30"></a>
                        <a href="#" class="mr-2"><img src="{{ asset('images/twitter-icon.png') }}" width="30"></a>
                        <a href="#" class="mr-2"><img src="{{ asset('images/instagram-icon.png') }}" width="30"></a>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h5 style="color:#fff;font-weight:600;margin-bottom:15px;">Quick Links</h5>
                    <ul style="list-style:none;padding:0;">
                        <li><a href="{{ route('home') }}" style="color:#aaa;font-size:13px;"><i class="fa fa-angle-right mr-1"></i>Home</a></li>
                        <li><a href="{{ route('restaurants') }}" style="color:#aaa;font-size:13px;"><i class="fa fa-angle-right mr-1"></i>Restaurants</a></li>
                        <li><a href="{{ route('about') }}" style="color:#aaa;font-size:13px;"><i class="fa fa-angle-right mr-1"></i>About Us</a></li>
                        <li><a href="{{ route('contact') }}" style="color:#aaa;font-size:13px;"><i class="fa fa-angle-right mr-1"></i>Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h5 style="color:#fff;font-weight:600;margin-bottom:15px;">Join Us As</h5>
                    <ul style="list-style:none;padding:0;">
                        <li><a href="{{ route('register') }}" style="color:#aaa;font-size:13px;"><i class="fa fa-angle-right mr-1"></i>Customer</a></li>
                        <li><a href="{{ route('restaurant.register') }}" style="color:#aaa;font-size:13px;"><i class="fa fa-angle-right mr-1"></i>Restaurant Partner</a></li>
                        <li><a href="{{ route('delivery.register') }}" style="color:#aaa;font-size:13px;"><i class="fa fa-angle-right mr-1"></i>Delivery Partner</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h5 style="color:#fff;font-weight:600;margin-bottom:15px;">Contact</h5>
                    <p style="color:#aaa;font-size:13px;"><i class="fas fa-map-marker-alt mr-2" style="color:var(--primary);"></i>123 Food Street, City</p>
                    <p style="color:#aaa;font-size:13px;"><i class="fas fa-phone mr-2" style="color:var(--primary);"></i>+1 800 BYTE2BITE</p>
                    <p style="color:#aaa;font-size:13px;"><i class="fas fa-envelope mr-2" style="color:var(--primary);"></i>@byte2bite.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="copyright_section" style="background:#1a1a1a;padding:15px 0;text-align:center;">
    <div class="container">
        <p style="color:#aaa;margin:0;font-size:13px;">© {{ date('Y') }} BYTE2BITE. All Rights Reserved.</p>
    </div>
</div>

<script src="{{ asset('js/jquery-3.0.0.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    // CSRF for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Flash message auto-dismiss
    setTimeout(function(){ $('.alert-dismissible').fadeOut('slow'); }, 4000);

    // Cart count
    function updateCartCount() {
        $.get('/api/cart/count', function(data) { $('#cart-count').text(data.count); });
    }

    // User Dropdown — safe null check
    var dropBtn  = document.getElementById('userDropdownBtn');
    var dropMenu = document.getElementById('userDropdownMenu');

    if (dropBtn && dropMenu) {
        dropBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropMenu.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (!dropBtn.contains(e.target) && !dropMenu.contains(e.target)) {
                dropMenu.classList.remove('open');
            }
        });
    }
</script>

@stack('scripts')
</body>
</html>