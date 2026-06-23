<?php
namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $partner = $user->deliveryPartner;

        $active = Order::where('delivery_partner_id', $user->id)
                       ->whereIn('status', ['picked_up', 'on_the_way'])
                       ->with('user')
                       ->get();

        $myOrders = Order::where('delivery_partner_id', $user->id)
                         ->latest()->take(5)->get();

        $pendingOrders = Order::whereNull('delivery_partner_id')
                              ->where('status', 'ready')
                              ->latest()->take(10)->get();

        $totalEarnings   = DeliveryEarning::where('delivery_partner_id', $partner->id ?? 0)->sum('amount');
        $totalDeliveries = Order::where('delivery_partner_id', $user->id)->where('status', 'delivered')->count();

        return view('delivery.dashboard', compact(
            'user', 'partner', 'active', 'myOrders', 'pendingOrders', 'totalEarnings', 'totalDeliveries'
        ));
    }

    public function earnings()
    {
        $user    = Auth::user();
        $partner = $user->deliveryPartner;
        $earnings = DeliveryEarning::where('delivery_partner_id', $partner->id ?? 0)
                        ->with('order')->latest()->paginate(20);
        $totalEarnings = DeliveryEarning::where('delivery_partner_id', $partner->id ?? 0)->sum('amount');
        return view('delivery.earnings', compact('earnings', 'totalEarnings'));
    }

    public function profile()
    {
        $user    = Auth::user();
        $partner = $user->deliveryPartner;
        return view('delivery.profile', compact('user', 'partner'));
    }

    public function updateProfile(Request $request)
    {
        $user    = Auth::user();
        $partner = $user->deliveryPartner;

        $request->validate(['name' => 'required', 'phone' => 'required']);

        $user->update($request->only('name', 'phone', 'address', 'city'));

        if ($partner) {
            $partner->update($request->only('vehicle_type', 'vehicle_number', 'license_number'));
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}