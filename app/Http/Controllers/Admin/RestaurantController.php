<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::with('user');
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) $query->where('name','like','%'.$request->search.'%');
        $restaurants = $query->latest()->paginate(20);
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function show($id)
    {
        $restaurant = Restaurant::with(['user','foodItems','reviews'])->findOrFail($id);
        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function edit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update($request->only(
            'name','phone','email','address','city','state','pincode',
            'description','cuisine_type','opening_time','closing_time',
            'delivery_fee','min_order_amount','avg_delivery_time','is_open','is_active'
        ));
        return back()->with('success','Restaurant updated!');
    }

    public function destroy($id)
    {
        Restaurant::findOrFail($id)->delete();
        return redirect()->route('admin.restaurants.index')->with('success','Restaurant deleted!');
    }

    public function approve($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['status'=>'approved']);
        $restaurant->user->update(['is_approved'=>true]);
        return back()->with('success','Restaurant approved!');
    }

    public function reject(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['status'=>'rejected','rejection_reason'=>$request->reason]);
        return back()->with('success','Restaurant rejected.');
    }

    public function toggle($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update(['is_active' => !$restaurant->is_active]);
        return back()->with('success','Restaurant status toggled!');
    }

    public function create()
    {
        return view('admin.restaurants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_name'  => 'required',
            'owner_email' => 'required|email|unique:users,email',
            'name'        => 'required',
            'phone'       => 'required',
            'address'     => 'required',
            'city'        => 'required',
        ]);
        $user = User::create([
            'name'        => $request->owner_name,
            'email'       => $request->owner_email,
            'phone'       => $request->owner_phone,
            'password'    => Hash::make($request->password ?? 'password123'),
            'role'        => 'restaurant',
            'is_active'   => true,
            'is_approved' => true,
        ]);
        Restaurant::create([
            'user_id'  => $user->id,
            'name'     => $request->name,
            'slug'     => Str::slug($request->name).'-'.Str::random(4),
            'phone'    => $request->phone,
            'email'    => $request->owner_email,
            'address'  => $request->address,
            'city'     => $request->city,
            'status'   => 'approved',
        ]);
        return redirect()->route('admin.restaurants.index')->with('success','Restaurant created!');
    }
}
