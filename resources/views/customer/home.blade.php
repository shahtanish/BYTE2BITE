@extends('layouts.app')
@section('title','Home')
@section('banner')
<!-- banner section start -->
<div class="banner_section layout_padding" style="background-image:url('{{ asset('images/banner-bg.png') }}');background-size:cover;background-repeat:no-repeat;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div id="bannerCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="banner_img"><img src="{{ asset('images/banner-img.png') }}" class="img-fluid"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h1 class="banner_taital" style="color:#252525;font-size:42px;font-weight:700;line-height:1.2;">
                    Order from <span style="color:#e42e0c;">Multiple</span><br>Restaurants at Once!
                </h1>
                <p class="banner_text" style="color:#555;font-size:16px;margin:15px 0 25px;">
                    BYTE2BITE — one cart, many restaurants. Get food from all your favourites delivered together.
                </p>
                <div class="container" style="padding:0;">
                    <div class="select_box_section">
                        <div class="select_box_main" style="background:#fff;border-radius:8px;padding:20px;box-shadow:0 5px 20px rgba(0,0,0,0.1);">
                            <form action="{{ route('restaurants') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <input type="text" name="city" class="form-control" placeholder="Enter City"
                                            value="{{ request('city') }}"
                                            style="border:1px solid #ddd;border-radius:5px;padding:10px 15px;font-size:14px;">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <select name="cuisine" class="form-control" style="border:1px solid #ddd;border-radius:5px;padding:10px 15px;font-size:14px;">
                                            <option value="">All Cuisines</option>
                                            <option value="North Indian">North Indian</option>
                                            <option value="South Indian">South Indian</option>
                                            <option value="Chinese">Chinese</option>
                                            <option value="Fast Food">Fast Food</option>
                                            <option value="Pizza">Pizza</option>
                                            <option value="Biryani">Biryani</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="text" name="search" class="form-control" placeholder="Search restaurants..."
                                            style="border:1px solid #ddd;border-radius:5px;padding:10px 15px;font-size:14px;">
                                    </div>
                                </div>
                                <div class="search_btn mt-3">
                                    <button type="submit" style="background:#e42e0c;color:#fff;border:none;padding:10px 35px;border-radius:5px;font-size:14px;font-weight:600;">
                                        <i class="fas fa-search mr-2"></i>Search Now
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner section end -->
@endsection

@section('content')

<!-- categories section -->
@if($categories->count())
<div class="layout_padding" style="background:#fff;padding:50px 0;">
    <div class="container">
        <h1 class="services_taital" style="text-align:center;font-weight:700;color:#252525;font-size:28px;">Browse by Category</h1>
        <p style="text-align:center;color:#888;margin-bottom:30px;">Find food across all categories</p>
        <div class="row">
            @foreach($categories as $cat)
            <div class="col-6 col-md-2 mb-4 text-center">
                <a href="{{ route('restaurants', ['search'=>$cat->name]) }}" style="text-decoration:none;">
                    <div style="background:#f8f8f8;border-radius:10px;padding:20px 10px;transition:all 0.3s;border:2px solid transparent;" onmouseover="this.style.borderColor='#e42e0c';this.style.background='#fff';" onmouseout="this.style.borderColor='transparent';this.style.background='#f8f8f8';">
                        <img src="{{ $cat->image_url }}" style="width:50px;height:50px;object-fit:cover;border-radius:50%;margin-bottom:10px;">
                        <p style="color:#252525;font-size:13px;font-weight:600;margin:0;">{{ $cat->name }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- popular food section -->
<div class="services_section layout_padding" style="background:#efefef;padding:50px 0;">
    <div class="container">
        <h1 class="services_taital" style="text-align:center;font-weight:700;color:#252525;font-size:28px;">Popular Food Items</h1>
        <p class="services_text" style="text-align:center;color:#888;margin-bottom:30px;">Trending dishes from top restaurants</p>
        <div class="row">
            @forelse($featuredItems as $item)
            <div class="col-md-3 mb-4">
                <div class="card h-100" style="border:none;border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.08);overflow:hidden;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 10px 30px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 3px 15px rgba(0,0,0,0.08)';">
                    <div style="position:relative;">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width:100%;height:160px;object-fit:cover;">
                        @if($item->food_type === 'veg')
                            <span style="position:absolute;top:10px;right:10px;background:#4caf50;color:#fff;border-radius:3px;padding:2px 8px;font-size:11px;font-weight:600;">VEG</span>
                        @elseif($item->food_type === 'non_veg')
                            <span style="position:absolute;top:10px;right:10px;background:#e42e0c;color:#fff;border-radius:3px;padding:2px 8px;font-size:11px;font-weight:600;">NON-VEG</span>
                        @endif
                    </div>
                    <div class="card-body" style="padding:15px;">
                        <h6 style="font-weight:700;color:#252525;margin-bottom:5px;font-size:14px;">{{ $item->name }}</h6>
                        <p style="color:#888;font-size:12px;margin-bottom:8px;">{{ $item->restaurant->name }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#e42e0c;font-weight:700;font-size:15px;">
                                ₹{{ number_format($item->effective_price, 2) }}
                                @if($item->discount_price)
                                    <small style="color:#999;text-decoration:line-through;font-size:12px;font-weight:normal;margin-left:5px;">₹{{ number_format($item->price, 2) }}</small>
                                @endif
                            </span>
                           
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p style="color:#888;">No featured items yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- restaurants section -->
<div style="background:#fff;padding:50px 0;">
    <div class="container">
        <h1 style="text-align:center;font-weight:700;color:#252525;font-size:28px;">Top Restaurants</h1>
        <p style="text-align:center;color:#888;margin-bottom:30px;">Choose from our handpicked restaurant partners</p>
        <div class="row">
            @forelse($restaurants as $rest)
            <div class="col-md-3 mb-4">
                <div class="card h-100" style="border:none;border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.08);overflow:hidden;transition:all 0.3s;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='translateY(0)';">
                    <img src="{{ $rest->logo_url }}" alt="{{ $rest->name }}" style="width:100%;height:150px;object-fit:cover;">
                    <div class="card-body" style="padding:15px;">
                        <h6 style="font-weight:700;color:#252525;margin-bottom:3px;">{{ $rest->name }}</h6>
                        <p style="color:#888;font-size:12px;margin-bottom:5px;"><i class="fas fa-map-marker-alt" style="color:#e42e0c;"></i> {{ $rest->city }}</p>
                        <p style="color:#888;font-size:12px;margin-bottom:8px;">{{ $rest->cuisine_type }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:#f39c12;font-size:12px;">
                                @for($i=1;$i<=5;$i++)<i class="fas fa-star{{ $i <= round($rest->rating) ? '' : '-o' }}"></i>@endfor
                                <span style="color:#888;margin-left:3px;">({{ $rest->total_reviews }})</span>
                            </span>
                            <span style="color:#888;font-size:11px;"><i class="fas fa-clock mr-1" style="color:#e42e0c;"></i>{{ $rest->avg_delivery_time }} min</span>
                        </div>
                        <a href="{{ route('restaurant.menu', $rest->id) }}" class="btn btn-block mt-2"
                            style="background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:7px;font-size:13px;font-weight:600;">
                            View Menu
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p style="color:#888;">No restaurants available yet.</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('restaurants') }}" style="background:#252525;color:#fff;padding:12px 35px;border-radius:5px;font-size:14px;font-weight:600;text-decoration:none;">
                View All Restaurants <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- how it works section -->
<div style="background:#efefef;padding:50px 0;">
    <div class="container">
        <h1 style="text-align:center;font-weight:700;color:#252525;font-size:28px;">How It Works</h1>
        <p style="text-align:center;color:#888;margin-bottom:40px;">Order food in 3 simple steps</p>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div style="background:#fff;border-radius:10px;padding:35px;box-shadow:0 3px 15px rgba(0,0,0,0.07);">
                    <div style="width:70px;height:70px;background:#e42e0c;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <i class="fas fa-search" style="color:#fff;font-size:28px;"></i>
                    </div>
                    <h5 style="font-weight:700;color:#252525;margin-bottom:10px;">1. Search & Browse</h5>
                    <p style="color:#888;font-size:14px;">Search restaurants by city, cuisine, or food item. Add items from multiple restaurants to one cart.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div style="background:#fff;border-radius:10px;padding:35px;box-shadow:0 3px 15px rgba(0,0,0,0.07);">
                    <div style="width:70px;height:70px;background:#e42e0c;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <i class="fas fa-shopping-cart" style="color:#fff;font-size:28px;"></i>
                    </div>
                    <h5 style="font-weight:700;color:#252525;margin-bottom:10px;">2. Place Your Order</h5>
                    <p style="color:#888;font-size:14px;">Checkout with a single order from multiple restaurants. Pay online or cash on delivery.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div style="background:#fff;border-radius:10px;padding:35px;box-shadow:0 3px 15px rgba(0,0,0,0.07);">
                    <div style="width:70px;height:70px;background:#e42e0c;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <i class="fas fa-motorcycle" style="color:#fff;font-size:28px;"></i>
                    </div>
                    <h5 style="font-weight:700;color:#252525;margin-bottom:10px;">3. Track & Enjoy</h5>
                    <p style="color:#888;font-size:14px;">Track your delivery live on the map. Get real-time updates as your food makes its way to you.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};
    var LOGIN_URL    = '{{ route("login") }}';
    var ADD_CART_URL = '{{ route("customer.cart.add") }}';
    var CSRF_TOKEN   = '{{ csrf_token() }}';

    function addToCart(foodItemId, qty) {
        if (!IS_LOGGED_IN) {
            window.location.href = LOGIN_URL;
            return;
        }
        $.ajax({
            url: ADD_CART_URL,
            method: 'POST',
            data: { _token: CSRF_TOKEN, food_item_id: foodItemId, quantity: qty },
            success: function(res) {
                if (res.success) {
                    $('#cart-count').text(res.count);
                    var toast = $('<div>').css({
                        position:'fixed', bottom:'25px', right:'25px',
                        background:'#e42e0c', color:'#fff', padding:'13px 22px',
                        borderRadius:'8px', fontSize:'14px', fontWeight:'600',
                        zIndex:9999, boxShadow:'0 5px 20px rgba(0,0,0,0.25)',
                        fontFamily:'Poppins,sans-serif'
                    }).text('✓ ' + res.message);
                    $('body').append(toast);
                    setTimeout(function(){ toast.fadeOut(400, function(){ toast.remove(); }); }, 2800);
                }
            },
            error: function(xhr) {
                if (xhr.status === 401 || xhr.status === 419) {
                    window.location.href = LOGIN_URL;
                }
            }
        });
    }
</script>
@endpush
