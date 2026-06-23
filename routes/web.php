    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\AuthController;
    use App\Http\Controllers\Customer\HomeController;
    use App\Http\Controllers\Customer\CartController;
    use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
    use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
    use App\Http\Controllers\Customer\TrackingController;
    use App\Http\Controllers\Restaurant\DashboardController as RestDashboard;
    use App\Http\Controllers\Restaurant\MenuController;
    use App\Http\Controllers\Restaurant\OrderController as RestOrderController;
    use App\Http\Controllers\Restaurant\ReportController as RestReportController;
    use App\Http\Controllers\Delivery\DashboardController as DeliveryDashboard;
    use App\Http\Controllers\Delivery\OrderController as DeliveryOrderController;
    use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
    use App\Http\Controllers\Admin\DeliveryPartnerController;
    use App\Http\Controllers\Admin\OrderController as AdminOrderController;
    use App\Http\Controllers\Admin\ReportController as AdminReportController;
    use App\Http\Controllers\Admin\CategoryController;

    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
    Route::get('/restaurants', [HomeController::class, 'restaurants'])->name('restaurants');
    Route::get('/restaurants/{id}', [HomeController::class, 'restaurantMenu'])->name('restaurant.menu');
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    /*
    |--------------------------------------------------------------------------
    | AUTH ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/restaurant/register', [AuthController::class, 'showRestaurantRegister'])->name('restaurant.register');
    Route::post('/restaurant/register', [AuthController::class, 'restaurantRegister'])->name('restaurant.register.post');
    Route::get('/delivery/register', [AuthController::class, 'showDeliveryRegister'])->name('delivery.register');
    Route::post('/delivery/register', [AuthController::class, 'deliveryRegister'])->name('delivery.register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // Checkout & Payment
        Route::get('/checkout', [CustomerOrderController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [CustomerOrderController::class, 'placeOrder'])->name('order.place');
        // Route::post('/payment/stripe', [CustomerOrderController::class, 'stripePayment'])->name('payment.stripe');
        // Route::get('/payment/success/{order}', [CustomerOrderController::class, 'paymentSuccess'])->name('payment.success');
        // Route::get('/payment/failed', [CustomerOrderController::class, 'paymentFailed'])->name('payment.failed');


        Route::get('/payment/stripe/{order}', [CustomerOrderController::class, 'stripePayment'])->name('payment.stripe');
        Route::post('/payment/razorpay/callback', [CustomerOrderController::class, 'razorpayCallback'])->name('payment.callback');
        Route::get('/payment/success/{order}', [CustomerOrderController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/payment/failed', [CustomerOrderController::class, 'paymentFailed'])->name('payment.failed');

        // Orders
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('/orders/{order}/track', [TrackingController::class, 'track'])->name('orders.track');
        Route::get('/orders/{order}/location', function(\App\Models\Order $order) {
        return response()->json([
            'current_latitude'  => $order->current_latitude,
            'current_longitude' => $order->current_longitude,
        ]);
    });

        // Profile
        Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');
    });

    /*
    |--------------------------------------------------------------------------
    | RESTAURANT ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:restaurant'])->prefix('restaurant')->name('restaurant.')->group(function () {
        Route::get('/dashboard', [RestDashboard::class, 'index'])->name('dashboard');

        // Menu Management
        Route::resource('/menu', MenuController::class);
        Route::post('/menu/{item}/toggle', [MenuController::class, 'toggle'])->name('menu.toggle');

        // Category Management
        Route::resource('/categories', CategoryController::class)->only(['index','store','destroy']);

        // Orders
        Route::get('/orders', [RestOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [RestOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/accept', [RestOrderController::class, 'accept'])->name('orders.accept');
        Route::post('/orders/{order}/reject', [RestOrderController::class, 'reject'])->name('orders.reject');
        Route::post('/orders/{order}/status', [RestOrderController::class, 'updateStatus'])->name('orders.status');

        // Reports
        Route::get('/reports', [RestReportController::class, 'index'])->name('reports');
        Route::get('/reports/sales', [RestReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/revenue', [RestReportController::class, 'revenue'])->name('reports.revenue');

        // Profile
        Route::get('/profile', [RestDashboard::class, 'profile'])->name('profile');
        Route::put('/profile', [RestDashboard::class, 'updateProfile'])->name('profile.update');
    });

    /*
    |--------------------------------------------------------------------------
    | DELIVERY PARTNER ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:delivery'])->prefix('delivery')->name('delivery.')->group(function () {
        Route::get('/dashboard', [DeliveryDashboard::class, 'index'])->name('dashboard');
        Route::get('/orders', [DeliveryOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [DeliveryOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/pickup', [DeliveryOrderController::class, 'pickup'])->name('orders.pickup');
        Route::post('/orders/{order}/delivered', [DeliveryOrderController::class, 'delivered'])->name('orders.delivered');
        Route::post('/orders/{order}/location', [DeliveryOrderController::class, 'updateLocation'])->name('orders.location');
        Route::get('/earnings', [DeliveryDashboard::class, 'earnings'])->name('earnings');
        Route::get('/profile', [DeliveryDashboard::class, 'profile'])->name('profile');
        Route::put('/profile', [DeliveryDashboard::class, 'updateProfile'])->name('profile.update');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Users
        Route::resource('/users', UserController::class);
        Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

        // Restaurants
        Route::resource('/restaurants', AdminRestaurantController::class);
        Route::post('/restaurants/{restaurant}/approve', [AdminRestaurantController::class, 'approve'])->name('restaurants.approve');
        Route::post('/restaurants/{restaurant}/reject', [AdminRestaurantController::class, 'reject'])->name('restaurants.reject');
        Route::post('/restaurants/{restaurant}/toggle', [AdminRestaurantController::class, 'toggle'])->name('restaurants.toggle');

        // Delivery Partners
        Route::resource('/delivery-partners', DeliveryPartnerController::class);
        Route::post('/delivery-partners/{partner}/approve', [DeliveryPartnerController::class, 'approve'])->name('delivery.approve');
        Route::post('/delivery-partners/{partner}/assign', [DeliveryPartnerController::class, 'assign'])->name('delivery.assign');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

        // Categories
        Route::resource('/categories', CategoryController::class);

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
        Route::get('/reports/users', [AdminReportController::class, 'users'])->name('reports.users');
        Route::get('/reports/revenue', [AdminReportController::class, 'revenue'])->name('reports.revenue');

        // Location update for tracking
        Route::post('/orders/{order}/location', [AdminOrderController::class, 'updateLocation'])->name('orders.location');
    });

    /*
    |--------------------------------------------------------------------------
    | API / AJAX ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
        Route::get('/restaurants/search', [HomeController::class, 'ajaxSearch'])->name('restaurants.search');
        Route::get('/orders/{order}/location', [TrackingController::class, 'getLocation'])->name('order.location');
        Route::post('/delivery/{order}/location', [DeliveryOrderController::class, 'updateLocation']);
    });
