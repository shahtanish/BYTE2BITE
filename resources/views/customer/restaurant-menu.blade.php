@extends('layouts.app')
@section('title', $restaurant->name . ' - Menu')

@section('banner')
<div style="background:url('{{ $restaurant->banner_url }}') center/cover no-repeat;height:200px;position:relative;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.55);display:flex;align-items:flex-end;padding:20px 0;">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="{{ $restaurant->logo_url }}" style="width:70px;height:70px;border-radius:10px;object-fit:cover;border:3px solid #fff;margin-right:15px;">
                <div>
                    <h3 style="color:#fff;font-weight:700;margin:0;">{{ $restaurant->name }}</h3>
                    <p style="color:rgba(255,255,255,0.8);margin:3px 0 0;font-size:13px;">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $restaurant->address }}, {{ $restaurant->city }}
                        &nbsp;|&nbsp;<i class="fas fa-clock mr-1"></i>{{ $restaurant->opening_time }} - {{ $restaurant->closing_time }}
                        &nbsp;|&nbsp;<i class="fas fa-motorcycle mr-1"></i>₹{{ $restaurant->delivery_fee }} delivery
                        &nbsp;|&nbsp;<i class="fas fa-fire mr-1"></i>{{ $restaurant->avg_delivery_time }} min
                        @if($restaurant->is_open)
                            &nbsp;<span style="background:#4caf50;color:#fff;border-radius:3px;padding:2px 8px;font-size:11px;">OPEN</span>
                        @else
                            &nbsp;<span style="background:#e42e0c;color:#fff;border-radius:3px;padding:2px 8px;font-size:11px;">CLOSED</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div style="background:#f8f8f8;padding:30px 0;min-height:60vh;">
<div class="container">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <!-- Menu Items -->
        <div class="col-md-8">
            @forelse($menuByCategory as $catId => $data)
            <div class="mb-4">
                <h5 style="font-weight:700;color:#252525;border-left:4px solid #e42e0c;padding-left:12px;margin-bottom:20px;">
                    {{ $data['category']->name }}
                </h5>
                @foreach($data['items'] as $item)
                <div class="card mb-3" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);overflow:hidden;">
                    <div class="d-flex">
                        <div style="flex:1;padding:15px;">
                            <div class="d-flex align-items-start">
                                <div style="width:12px;height:12px;border:2px solid {{ $item->food_type==='veg' ? '#4caf50' : '#e42e0c' }};border-radius:2px;margin-top:3px;margin-right:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                    <div style="width:6px;height:6px;background:{{ $item->food_type==='veg' ? '#4caf50' : '#e42e0c' }};border-radius:50%;"></div>
                                </div>
                                <div>
                                    <h6 style="font-weight:700;color:#252525;margin:0 0 5px;">{{ $item->name }}</h6>
                                    <p style="color:#888;font-size:12px;margin:0 0 8px;">{{ Str::limit($item->description, 80) }}</p>
                                    <div class="d-flex align-items-center">
                                        <span style="color:#e42e0c;font-weight:700;font-size:15px;">₹{{ number_format($item->effective_price, 2) }}</span>
                                        @if($item->discount_price)
                                            <span style="color:#999;text-decoration:line-through;font-size:12px;margin-left:8px;">₹{{ number_format($item->price, 2) }}</span>
                                        @endif
                                        <span style="color:#888;font-size:11px;margin-left:12px;"><i class="fas fa-clock mr-1"></i>{{ $item->preparation_time }} min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="width:130px;position:relative;flex-shrink:0;">
                            <img src="{{ $item->image_url }}" style="width:130px;height:100%;min-height:100px;object-fit:cover;">
                            <button
                                onclick="addToCart({{ $item->id }}, 1)"
                                style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);background:#e42e0c;color:#fff;border:none;border-radius:5px;padding:5px 15px;font-size:12px;font-weight:600;white-space:nowrap;cursor:pointer;">
                                + Add
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @empty
            <div class="text-center py-5"><p style="color:#888;">No menu items available.</p></div>
            @endforelse
        </div>

        <!-- Cart Sidebar -->
        <div class="col-md-4">
            <div class="card" style="border:none;border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.1);position:sticky;top:80px;">
                
                <div style="padding:15px 20px;border-top:1px solid #f0f0f0;">
                    <a href="{{ route('customer.cart') }}" class="btn btn-block"
                        style="background:#252525;color:#fff;border:none;border-radius:5px;padding:10px;font-size:14px;font-weight:600;display:block;text-align:center;text-decoration:none;">
                        View Cart &amp; Checkout
                    </a>
                </div>
            </div>

            <!-- Restaurant Info -->
            <div class="card mt-3" style="border:none;border-radius:10px;box-shadow:0 3px 15px rgba(0,0,0,0.07);">
                <div class="card-body" style="padding:20px;">
                    <h6 style="font-weight:700;color:#252525;margin-bottom:15px;">Restaurant Info</h6>
                    <p style="font-size:13px;color:#555;margin-bottom:8px;"><i class="fas fa-phone mr-2" style="color:#e42e0c;"></i>{{ $restaurant->phone }}</p>
                    <p style="font-size:13px;color:#555;margin-bottom:8px;"><i class="fas fa-map-marker-alt mr-2" style="color:#e42e0c;"></i>{{ $restaurant->address }}</p>
                    @if($restaurant->min_order_amount > 0)
                    <p style="font-size:13px;color:#555;margin-bottom:8px;"><i class="fas fa-info-circle mr-2" style="color:#e42e0c;"></i>Min order: ₹{{ $restaurant->min_order_amount }}</p>
                    @endif
                    @if($restaurant->description)
                    <p style="font-size:13px;color:#888;margin:0;">{{ $restaurant->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    @if($reviews->count())
    <div class="mt-5">
        <h5 style="font-weight:700;color:#252525;border-left:4px solid #e42e0c;padding-left:12px;margin-bottom:20px;">Customer Reviews</h5>
        <div class="row">
            @foreach($reviews as $review)
            <div class="col-md-6 mb-3">
                <div class="card" style="border:none;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.07);padding:15px;">
                    <div class="d-flex align-items-center mb-2">
                        <div style="width:40px;height:40px;background:#e42e0c;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;margin-right:12px;">
                            {{ strtoupper(substr($review->user->name,0,1)) }}
                        </div>
                        <div>
                            <h6 style="margin:0;font-weight:600;font-size:14px;">{{ $review->user->name }}</h6>
                            <div style="color:#f39c12;font-size:12px;">
                                @for($i=1;$i<=5;$i++)<i class="fas fa-star"></i>@endfor
                            </div>
                        </div>
                        <span style="margin-left:auto;font-size:11px;color:#aaa;">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="color:#555;font-size:13px;margin:0;">{{ $review->comment }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
</div>

{{-- Inline script so it always runs regardless of @stack --}}
<script>
(function() {
    var IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};
    var LOGIN_URL    = '{{ route("login") }}';
    var CART_URL     = '{{ route("customer.cart.add") }}';
    var CSRF         = document.querySelector('meta[name="csrf-token"]') ?
                       document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    window.addToCart = function(foodItemId, qty) {
        if (!IS_LOGGED_IN) {
            window.location.href = LOGIN_URL;
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', CART_URL, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        // Update cart badge
                        var badge = document.getElementById('cart-count');
                        if (badge) badge.textContent = res.count;

                        // Show toast
                        showToast(res.message, '#e42e0c');
                    } else if (xhr.status === 401 || xhr.status === 419) {
                        window.location.href = LOGIN_URL;
                    } else {
                        showToast(res.message || 'Error adding item', '#c62828');
                    }
                } catch(e) {
                    if (xhr.status === 401 || xhr.status === 419) {
                        window.location.href = LOGIN_URL;
                    } else {
                        showToast('Error. Please try again.', '#c62828');
                    }
                }
            }
        };

        xhr.send('_token=' + encodeURIComponent(CSRF) +
                 '&food_item_id=' + encodeURIComponent(foodItemId) +
                 '&quantity=' + encodeURIComponent(qty));
    };

    window.showToast = function(msg, color) {
        var div = document.createElement('div');
        div.textContent = '✓ ' + msg;
        div.style.cssText = [
            'position:fixed', 'bottom:25px', 'right:25px',
            'background:' + (color || '#e42e0c'), 'color:#fff',
            'padding:13px 22px', 'border-radius:8px',
            'font-size:14px', 'font-weight:600',
            'z-index:99999', 'box-shadow:0 5px 20px rgba(0,0,0,0.3)',
            'font-family:Poppins,sans-serif', 'transition:opacity 0.4s'
        ].join(';');
        document.body.appendChild(div);
        setTimeout(function() {
            div.style.opacity = '0';
            setTimeout(function() {
                if (div.parentNode) div.parentNode.removeChild(div);
            }, 400);
        }, 2800);
    };
})();
</script>
@endsection
