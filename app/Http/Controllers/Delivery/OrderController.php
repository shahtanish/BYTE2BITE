<?php
namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $userId  = Auth::id();
        $active  = Order::where('delivery_partner_id',$userId)->whereIn('status',['picked_up','on_the_way'])->with('user')->get();
        $completed = Order::where('delivery_partner_id',$userId)->where('status','delivered')->with('user')->latest()->paginate(15);
        $available = Order::whereNull('delivery_partner_id')->whereIn('status',['ready'])->with('user')->latest()->take(20)->get();
        return view('delivery.orders.index', compact('active','completed','available'));
    }

    public function show($id)
    {
        $order = Order::where('id',$id)
                    ->where(fn($q)=>$q->where('delivery_partner_id',Auth::id())->orWhereNull('delivery_partner_id'))
                    ->with(['user','items.restaurant'])
                    ->firstOrFail();
        return view('delivery.orders.show', compact('order'));
    }

    public function pickup($id)
    {
        $order = Order::where('id',$id)->whereNull('delivery_partner_id')->firstOrFail();
        $order->update([
            'delivery_partner_id' => Auth::id(),
            'status'              => 'on_the_way',
            'picked_up_at'        => now(),
        ]);
        return back()->with('success','Order picked up! Start delivery.');
    }

    public function delivered($id)
    {
        $order = Order::where('id',$id)->where('delivery_partner_id',Auth::id())->firstOrFail();
        $order->update([
            'status'       => 'delivered',
            'delivered_at' => now(),
            'payment_status'=> $order->payment_method === 'cod' ? 'paid' : $order->payment_status,
        ]);

        // Log earnings (₹30 flat per delivery or percentage)
        $partner = Auth::user()->deliveryPartner;
        if ($partner) {
            $earning = $order->delivery_fee * 0.8; // 80% of delivery fee to partner
            $earning = max($earning, 30); // minimum ₹30
            DeliveryEarning::create([
                'delivery_partner_id' => $partner->id,
                'order_id'            => $order->id,
                'amount'              => $earning,
                'earned_at'           => now(),
            ]);
            $partner->increment('total_deliveries');
            $partner->increment('earnings_total', $earning);
        }

        return back()->with('success','Delivery confirmed! Earnings credited.');
    }

    public function updateLocation(Request $request, $id)
    {
        $order = Order::where('id',$id)->where('delivery_partner_id',Auth::id())->firstOrFail();
        $order->update([
            'current_latitude'  => $request->latitude,
            'current_longitude' => $request->longitude,
        ]);

        // Also update delivery partner location
        $partner = Auth::user()->deliveryPartner;
        if ($partner) {
            $partner->update([
                'current_latitude'  => $request->latitude,
                'current_longitude' => $request->longitude,
            ]);
        }

        return response()->json(['success'=>true]);
    }
}
