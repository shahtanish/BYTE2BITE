<?php
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    private function restaurant()
    {
        return Auth::user()->restaurant;
    }

    public function index()
    {
        $restaurant = $this->restaurant();
        if (!$restaurant) return redirect()->route('home')->with('error','Restaurant not found.');

        $rid = $restaurant->id;
        $totalOrders    = OrderItem::where('restaurant_id',$rid)->distinct('order_id')->count('order_id');
        $pendingOrders  = \App\Models\OrderRestaurantStatus::where('restaurant_id',$rid)->where('status','pending')->count();
        $totalRevenue   = OrderItem::where('restaurant_id',$rid)
                            ->whereHas('order',fn($q)=>$q->where('status','delivered'))
                            ->sum('subtotal');
        $totalItems     = FoodItem::where('restaurant_id',$rid)->count();
        $recentOrders   = \App\Models\OrderRestaurantStatus::where('restaurant_id',$rid)
                            ->with(['order.user','order.items'=>fn($q)=>$q->where('restaurant_id',$rid)])
                            ->latest()->take(10)->get();

        // Revenue last 7 days
        $weeklyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $rev  = OrderItem::where('restaurant_id',$rid)
                        ->whereHas('order',fn($q)=>$q->whereDate('created_at',$date)->where('status','delivered'))
                        ->sum('subtotal');
            $weeklyRevenue[] = ['date'=>now()->subDays($i)->format('D'),'revenue'=>$rev];
        }

        return view('restaurant.dashboard', compact(
            'restaurant','totalOrders','pendingOrders',
            'totalRevenue','totalItems','recentOrders','weeklyRevenue'
        ));
    }

    public function profile()
    {
        $restaurant = $this->restaurant();
        return view('restaurant.profile', compact('restaurant'));
    }

    public function updateProfile(Request $request)
    {
        $restaurant = $this->restaurant();
        $request->validate([
            'name'         => 'required|string|max:200',
            'phone'        => 'required|string|max:20',
            'address'      => 'required|string',
            'city'         => 'required|string',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        $data = $request->only(
            'name','phone','email','address','city','state','pincode',
            'description','cuisine_type','opening_time','closing_time',
            'delivery_fee','min_order_amount','avg_delivery_time'
        );

        if ($request->hasFile('logo')) {
            $logo = time().'_'.$request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('uploads/restaurants'), $logo);
            $data['logo'] = $logo;
        }
        if ($request->hasFile('banner')) {
            $banner = time().'_'.$request->file('banner')->getClientOriginalName();
            $request->file('banner')->move(public_path('uploads/restaurants'), $banner);
            $data['banner'] = $banner;
        }

        $restaurant->update($data);

        // Update user name/password if provided
        $user = Auth::user();
        $user->update(['name' => $request->owner_name ?? $user->name]);
        if ($request->filled('password')) {
            $request->validate(['password'=>'min:6|confirmed']);
            $user->update(['password'=>Hash::make($request->password)]);
        }

        return back()->with('success','Restaurant profile updated!');
    }
}
