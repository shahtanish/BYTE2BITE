<?php
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function restaurantId() { return Auth::user()->restaurant->id; }

    public function index()
    {
        return $this->sales(request());
    }

    public function sales(Request $request)
    {
        $rid   = $this->restaurantId();
        $from  = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to    = $request->get('to',   now()->format('Y-m-d'));

        $totalRevenue = OrderItem::where('restaurant_id',$rid)
            ->whereHas('order', fn($q)=>$q->whereBetween('created_at',[$from.' 00:00:00',$to.' 23:59:59'])->where('status','delivered'))
            ->sum('subtotal');

        $totalOrders = OrderItem::where('restaurant_id',$rid)
            ->whereHas('order', fn($q)=>$q->whereBetween('created_at',[$from.' 00:00:00',$to.' 23:59:59']))
            ->distinct('order_id')->count('order_id');

        $topItems = OrderItem::where('restaurant_id',$rid)
            ->whereHas('order', fn($q)=>$q->whereBetween('created_at',[$from.' 00:00:00',$to.' 23:59:59']))
            ->selectRaw('food_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('food_name')->orderByDesc('total_qty')->take(10)->get();

        // Daily revenue
        $dailyRevenue = [];
        $current = \Carbon\Carbon::parse($from);
        $end     = \Carbon\Carbon::parse($to);
        while ($current <= $end) {
            $date = $current->format('Y-m-d');
            $rev  = OrderItem::where('restaurant_id',$rid)
                        ->whereHas('order',fn($q)=>$q->whereDate('created_at',$date)->where('status','delivered'))
                        ->sum('subtotal');
            $dailyRevenue[] = ['date'=>$current->format('d M'),'revenue'=>round($rev,2)];
            $current->addDay();
        }

        return view('restaurant.reports.index', compact(
            'totalRevenue','totalOrders','topItems','dailyRevenue','from','to'
        ));
    }

    public function revenue(Request $request)
    {
        return $this->sales($request);
    }
}
