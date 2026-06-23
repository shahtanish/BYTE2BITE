<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $cartData = $this->buildCartData($cart);
        return view('customer.cart.index', compact('cart','cartData'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'quantity'     => 'required|integer|min:1|max:20',
        ]);

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Please login first.'], 401);
        }

        $item = FoodItem::with('restaurant')->findOrFail($request->food_item_id);

        if (!$item->is_available) {
            return response()->json(['success' => false, 'message' => 'Item is currently unavailable.']);
        }

        $cart = session('cart', []);
        $key  = 'item_' . $item->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'food_item_id'         => $item->id,
                'restaurant_id'        => $item->restaurant_id,
                'restaurant_name'      => $item->restaurant->name,
                'name'                 => $item->name,
                'price'                => $item->effective_price,
                'image'                => $item->image_url,
                'quantity'             => $request->quantity,
                'special_instructions' => $request->special_instructions ?? '',
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => $item->name . ' added to cart!',
            'count'   => count($cart),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate(['key'=>'required','quantity'=>'required|integer|min:0']);
        $cart = session('cart', []);

        if ($request->quantity == 0) {
            unset($cart[$request->key]);
        } else {
            $cart[$request->key]['quantity'] = $request->quantity;
        }

        session(['cart' => $cart]);
        $cartData = $this->buildCartData($cart);
        return response()->json([
            'success'  => true,
            'count'    => count($cart),
            'subtotal' => number_format($cartData['subtotal'], 2),
            'total'    => number_format($cartData['total'], 2),
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->key]);
        session(['cart' => $cart]);
        return response()->json(['success'=>true,'count'=>count($cart)]);
    }

    public function clear()
    {
        session()->forget('cart');
        return response()->json(['success'=>true]);
    }

    public function count()
    {
        return response()->json(['count' => count(session('cart', []))]);
    }

    public function buildCartData($cart)
    {
        $subtotal = 0;
        $byRestaurant = [];

        foreach ($cart as $key => $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $subtotal += $lineTotal;

            $rid = $item['restaurant_id'];
            if (!isset($byRestaurant[$rid])) {
                $byRestaurant[$rid] = [
                    'restaurant_name' => $item['restaurant_name'],
                    'items'           => [],
                    'subtotal'        => 0,
                    'delivery_fee'    => 0,
                ];
                // Fetch delivery fee
                $rest = Restaurant::find($rid);
                if ($rest) $byRestaurant[$rid]['delivery_fee'] = $rest->delivery_fee;
            }
            $byRestaurant[$rid]['items'][$key] = $item + ['line_total' => $lineTotal];
            $byRestaurant[$rid]['subtotal'] += $lineTotal;
        }

        $deliveryFee = 0;
        foreach ($byRestaurant as $r) { $deliveryFee += $r['delivery_fee']; }

        $tax   = round($subtotal * 0.05, 2); // 5% tax
        $total = $subtotal + $deliveryFee + $tax;

        return compact('subtotal','deliveryFee','tax','total','byRestaurant');
    }
}
