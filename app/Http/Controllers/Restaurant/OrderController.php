<?php
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRestaurantStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function restaurantId() { return Auth::user()->restaurant->id; }

    public function index(Request $request)
    {
        $rid   = $this->restaurantId();
        $query = OrderRestaurantStatus::where('restaurant_id',$rid)
                    ->with(['order.user','order.items'=>fn($q)=>$q->where('restaurant_id',$rid)])
                    ->latest();

        if ($request->filled('status')) {
            $query->where('status',$request->status);
        }

        $statuses = $query->paginate(15);
        return view('restaurant.orders.index', compact('statuses'));
    }

    public function show($id)
    {
        $rid    = $this->restaurantId();
        $status = OrderRestaurantStatus::where('order_id',$id)->where('restaurant_id',$rid)->firstOrFail();
        $order  = Order::with([
            'user',
            'items' => fn($q) => $q->where('restaurant_id',$rid)
        ])->findOrFail($id);
        return view('restaurant.orders.show', compact('order','status'));
    }

    public function accept($id)
    {
        $rid    = $this->restaurantId();
        $status = OrderRestaurantStatus::where('order_id',$id)->where('restaurant_id',$rid)->firstOrFail();
        $status->update(['status'=>'accepted','accepted_at'=>now()]);

        // If all restaurants accepted, update main order
        $this->syncOrderStatus($id);
        return back()->with('success','Order accepted!');
    }

    public function reject(Request $request, $id)
    {
        $rid    = $this->restaurantId();
        $status = OrderRestaurantStatus::where('order_id',$id)->where('restaurant_id',$rid)->firstOrFail();
        $status->update(['status'=>'rejected','rejection_reason'=>$request->reason]);
        Order::find($id)->update(['status'=>'rejected']);
        return back()->with('success','Order rejected.');
    }

    public function updateStatus(Request $request, $id)
    {
        $rid    = $this->restaurantId();
        $status = OrderRestaurantStatus::where('order_id',$id)->where('restaurant_id',$rid)->firstOrFail();
        $status->update(['status'=>$request->status]);
        if ($request->status === 'ready') $status->update(['ready_at'=>now()]);
        $this->syncOrderStatus($id);
        return back()->with('success','Status updated!');
    }

    private function syncOrderStatus($orderId)
    {
        $allStatuses = OrderRestaurantStatus::where('order_id',$orderId)->pluck('status');
        $order       = Order::find($orderId);

        if ($allStatuses->contains('rejected')) {
            // Keep as is
        } elseif ($allStatuses->every(fn($s) => $s === 'ready')) {
            $order->update(['status'=>'ready']);
        } elseif ($allStatuses->contains('preparing')) {
            $order->update(['status'=>'preparing']);
        } elseif ($allStatuses->every(fn($s) => $s === 'accepted')) {
            $order->update(['status'=>'accepted','accepted_at'=>now()]);
        }
    }
}
