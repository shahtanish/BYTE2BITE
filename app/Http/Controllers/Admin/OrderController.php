<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user','items.restaurant','deliveryPartner']);
        if ($request->filled('status')) $query->where('status',$request->status);
        if ($request->filled('search')) {
            $query->where('order_number','like','%'.$request->search.'%')
                  ->orWhereHas('user',fn($q)=>$q->where('name','like','%'.$request->search.'%'));
        }
        if ($request->filled('from')) $query->whereDate('created_at','>=',$request->from);
        if ($request->filled('to'))   $query->whereDate('created_at','<=',$request->to);
        $orders = $query->latest()->paginate(20);
        $deliveryPartners = User::where('role','delivery')->where('is_approved',true)->get();
        return view('admin.orders.index', compact('orders','deliveryPartners'));
    }

    public function show($id)
    {
        $order = Order::with(['user','items.restaurant','items.foodItem','deliveryPartner','restaurantStatuses.restaurant'])->findOrFail($id);
        $deliveryPartners = User::where('role','delivery')->where('is_approved',true)->get();
        return view('admin.orders.show', compact('order','deliveryPartners'));
    }

    public function updateLocation(Request $request, $id)
    {
        $request->validate(['latitude'=>'required|numeric','longitude'=>'required|numeric']);
        $order = Order::findOrFail($id);
        $order->update([
            'current_latitude'  => $request->latitude,
            'current_longitude' => $request->longitude,
        ]);
        return response()->json(['success'=>true,'message'=>'Location updated!']);
    }
}
