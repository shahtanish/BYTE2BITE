<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $totalRevenue   = Order::where('status','delivered')->sum('total');
        $totalOrders    = Order::count();
        $totalCustomers = User::where('role','customer')->count();
        $totalRestaurants = Restaurant::where('status','approved')->count();

        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $rev   = Order::where('status','delivered')
                        ->whereYear('created_at',$month->year)
                        ->whereMonth('created_at',$month->month)->sum('total');
            $monthlyRevenue[] = ['month'=>$month->format('M'),'revenue'=>round($rev,2),'orders'=>Order::whereYear('created_at',$month->year)->whereMonth('created_at',$month->month)->count()];
        }

        $topRestaurants = Restaurant::withSum(['orderItems as revenue'=>fn($q)=>$q->whereHas('order',fn($o)=>$o->where('status','delivered'))],'subtotal')
                            ->orderByDesc('revenue')->take(10)->get();

        return view('admin.reports.index', compact('totalRevenue','totalOrders','totalCustomers','totalRestaurants','monthlyRevenue','topRestaurants'));
    }

    public function users()  { return $this->index(); }
    public function revenue(){ return $this->index(); }
}
