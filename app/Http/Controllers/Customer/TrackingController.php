<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function track($id)
    {
        $order = Order::where('id',$id)->where('user_id',Auth::id())
                    ->with(['items.restaurant','deliveryPartner.deliveryPartner','restaurantStatuses.restaurant'])
                    ->firstOrFail();
        $mapsKey = config('services.google_maps.key', '');
        return view('customer.orders.track', compact('order','mapsKey'));
    }

    public function getLocation($id)
    {
        $order = Order::where('id',$id)->where('user_id',Auth::id())->firstOrFail();
        return response()->json([
            'status'            => $order->status,
            'current_latitude'  => $order->current_latitude,
            'current_longitude' => $order->current_longitude,
            'delivery_latitude' => $order->delivery_latitude,
            'delivery_longitude'=> $order->delivery_longitude,
        ]);
    }
}
