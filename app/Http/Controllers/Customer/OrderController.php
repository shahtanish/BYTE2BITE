<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRestaurantStatus;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('customer.cart')->with('error','Your cart is empty.');

        $cartController = new CartController();
        $cartData = $cartController->buildCartData($cart);
        $user = Auth::user();
        return view('customer.cart.checkout', compact('cart','cartData','user'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'delivery_name'    => 'required|string|max:100',
            'delivery_phone'   => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'delivery_city'    => 'required|string',
            'payment_method'   => 'required|in:cod,online',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('customer.cart')->with('error','Cart is empty.');

        $cartController = new CartController();
        $cartData = $cartController->buildCartData($cart);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'user_id'          => Auth::id(),
                'delivery_name'    => $request->delivery_name,
                'delivery_phone'   => $request->delivery_phone,
                'delivery_address' => $request->delivery_address,
                'delivery_city'    => $request->delivery_city,
                'delivery_pincode' => $request->delivery_pincode,
                'subtotal'         => $cartData['subtotal'],
                'delivery_fee'     => $cartData['deliveryFee'],
                'tax'              => $cartData['tax'],
                'total'            => $cartData['total'],
                'payment_method'   => $request->payment_method,
                'payment_status'   => $request->payment_method === 'cod' ? 'pending' : 'pending',
                'status'           => 'pending',
                'notes'            => $request->notes,
            ]);

            // Create order items and per-restaurant status
            $restaurantIds = [];
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'             => $order->id,
                    'restaurant_id'        => $item['restaurant_id'],
                    'food_item_id'         => $item['food_item_id'],
                    'food_name'            => $item['name'],
                    'food_price'           => $item['price'],
                    'quantity'             => $item['quantity'],
                    'subtotal'             => $item['price'] * $item['quantity'],
                    'special_instructions' => $item['special_instructions'] ?? '',
                ]);
                $restaurantIds[$item['restaurant_id']] = true;
            }

            // Create per-restaurant status record
            foreach (array_keys($restaurantIds) as $rid) {
                OrderRestaurantStatus::create([
                    'order_id'      => $order->id,
                    'restaurant_id' => $rid,
                    'status'        => 'pending',
                ]);
            }

            DB::commit();
            session()->forget('cart');

            if ($request->payment_method === 'online') {
                return redirect()->route('customer.payment.stripe', $order->id);
            }

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully! Order #' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function stripePayment($orderId)
{
    $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
    $razorpayKey = env('RAZORPAY_KEY');
    return view('customer.cart.stripe', compact('order', 'razorpayKey'));
}

public function razorpayCallback(Request $request)
{
    $orderId = $request->order_id;
    $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

    // Verify signature
    $secret = env('RAZORPAY_SECRET');
    $generated = hash_hmac('sha256', $request->razorpay_order_id . '|' . $request->razorpay_payment_id, $secret);

    if ($generated === $request->razorpay_signature) {
        $order->update([
            'payment_status' => 'paid',
            'payment_id'     => $request->razorpay_payment_id,
        ]);
        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'Payment successful! Order #' . $order->order_number);
    }

    return redirect()->route('customer.payment.failed')->with('error', 'Payment verification failed.');
}

    public function paymentSuccess($orderId)
    {
        $order = Order::where('id',$orderId)->where('user_id',Auth::id())->firstOrFail();
        $order->update(['payment_status'=>'paid','payment_id'=>request('payment_intent')]);
        return redirect()->route('customer.orders.show', $order->id)
            ->with('success','Payment successful! Order #'.$order->order_number);
    }

    public function paymentFailed()
    {
        return redirect()->route('customer.cart')->with('error','Payment failed. Please try again.');
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                    ->with(['items.restaurant'])
                    ->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('id',$id)->where('user_id',Auth::id())
                    ->with(['items.restaurant','items.foodItem','restaurantStatuses.restaurant','deliveryPartner'])
                    ->firstOrFail();
        return view('customer.orders.show', compact('order'));
    }

    public function invoice($id)
    {
        $order = Order::where('id',$id)->where('user_id',Auth::id())
                    ->with(['items.restaurant','user'])
                    ->firstOrFail();
        return view('customer.orders.invoice', compact('order'));
    }
}
