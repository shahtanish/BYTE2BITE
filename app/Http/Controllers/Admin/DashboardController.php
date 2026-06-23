<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers   = User::where('role','customer')->count();
        $totalRestaurants = Restaurant::where('status','approved')->count();
        $totalOrders      = Order::count();
        $totalRevenue     = Order::where('status','delivered')->sum('total');
        $pendingRestaurants = Restaurant::where('status','pending')->count();
        $pendingDelivery  = User::where('role','delivery')->where('is_approved',false)->count();
        $recentOrders     = Order::with(['user','items.restaurant'])->latest()->take(10)->get();

        // Monthly revenue last 6 months
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $rev   = Order::where('status','delivered')
                        ->whereYear('created_at',$month->year)
                        ->whereMonth('created_at',$month->month)
                        ->sum('total');
            $monthlyRevenue[] = ['month'=>$month->format('M Y'),'revenue'=>round($rev,2)];
        }

        $orderStatusCounts = [
            'pending'    => Order::where('status','pending')->count(),
            'preparing'  => Order::where('status','preparing')->count(),
            'on_the_way' => Order::where('status','on_the_way')->count(),
            'delivered'  => Order::where('status','delivered')->count(),
            'cancelled'  => Order::whereIn('status',['cancelled','rejected'])->count(),
        ];

        return view('admin.dashboard', compact(
            'totalCustomers','totalRestaurants','totalOrders','totalRevenue',
            'pendingRestaurants','pendingDelivery','recentOrders',
            'monthlyRevenue','orderStatusCounts'
        ));
    }
}
