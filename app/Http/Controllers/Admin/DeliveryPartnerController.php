<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DeliveryPartner;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryPartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role','delivery')->with('deliveryPartner');
        if ($request->filled('search')) $query->where('name','like','%'.$request->search.'%');
        $partners = $query->latest()->paginate(20);
        return view('admin.delivery.index', compact('partners'));
    }

    public function show($id)
    {
        $user    = User::where('role','delivery')->findOrFail($id);
        $partner = $user->deliveryPartner;
        $orders  = Order::where('delivery_partner_id',$id)->with('user')->latest()->take(20)->get();
        return view('admin.delivery.show', compact('user','partner','orders'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved'=>true,'is_active'=>true]);
        return back()->with('success','Delivery partner approved!');
    }

    public function assign(Request $request, $id)
    {
        $order = Order::findOrFail($request->order_id);
        $order->update(['delivery_partner_id'=>$id,'status'=>'on_the_way','picked_up_at'=>now()]);
        return back()->with('success','Order assigned to delivery partner!');
    }

    public function create()  { return view('admin.delivery.create'); }
    public function edit($id) { $user = User::findOrFail($id); return view('admin.delivery.edit',compact('user')); }

    public function update(Request $request, $id)
    {
        User::findOrFail($id)->update($request->only('name','email','phone','is_active','is_approved'));
        return back()->with('success','Partner updated!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.delivery-partners.index')->with('success','Partner deleted!');
    }

    public function store(Request $request) { return redirect()->route('admin.delivery-partners.index'); }
}
