<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories   = Category::where('is_active', true)->orderBy('sort_order')->get();
        $restaurants  = Restaurant::where('status','approved')->where('is_active',true)
                            ->withCount('foodItems')->with('reviews')->latest()->take(8)->get();
        $featuredItems = FoodItem::where('is_featured',true)->where('is_available',true)
                            ->with(['restaurant','category'])->take(8)->get();
        return view('customer.home', compact('categories','restaurants','featuredItems'));
    }

    public function about()
    {
        return view('customer.about');
    }

    public function contact()
    {
        return view('customer.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->only('name','email','phone','subject','message'));
        return back()->with('success','Your message has been sent. We\'ll get back to you soon!');
    }

    public function restaurants(Request $request)
    {
        $query = Restaurant::where('status','approved')->where('is_active',true)->with('reviews');

        if ($request->filled('city')) {
            $query->where('city','like','%'.$request->city.'%');
        }
        if ($request->filled('cuisine')) {
            $query->where('cuisine_type','like','%'.$request->cuisine.'%');
        }
if ($request->filled('search')) {
    $query->where(function($q) use ($request) {
        $q->where('name', 'like', '%'.$request->search.'%')
          ->orWhere('cuisine_type', 'like', '%'.$request->search.'%')
          ->orWhereHas('foodItems', function($q2) use ($request) {
              $q2->where('name', 'like', '%'.$request->search.'%')
                 ->orWhereHas('category', function($q3) use ($request) {
                     $q3->where('name', 'like', '%'.$request->search.'%');
                 });
          });
    });
}
        $restaurants = $query->withCount('foodItems')->paginate(12);
        $cities = Restaurant::where('status','approved')->distinct()->pluck('city');
        return view('customer.restaurants', compact('restaurants','cities'));
    }

    public function restaurantMenu($id)
    {
        $restaurant = Restaurant::where('id',$id)->where('status','approved')->firstOrFail();
        $categories = Category::whereHas('foodItems', function($q) use($id) {
            $q->where('restaurant_id',$id)->where('is_available',true);
        })->get();

        $menuByCategory = [];
        foreach ($categories as $cat) {
            $menuByCategory[$cat->id] = [
                'category' => $cat,
                'items'    => FoodItem::where('restaurant_id',$id)
                                ->where('category_id',$cat->id)
                                ->where('is_available',true)
                                ->orderBy('sort_order')
                                ->get()
            ];
        }
        $reviews = $restaurant->reviews()->with('user')->where('is_approved',true)->latest()->take(10)->get();
        return view('customer.restaurant-menu', compact('restaurant','menuByCategory','reviews'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q','');
        $restaurants = Restaurant::where('status','approved')
            ->where(function($query) use($q) {
                $query->where('name','like',"%$q%")
                      ->orWhere('cuisine_type','like',"%$q%")
                      ->orWhere('city','like',"%$q%");
            })->take(10)->get();

        $foods = FoodItem::where('is_available',true)
            ->where('name','like',"%$q%")
            ->with('restaurant')->take(10)->get();

        return view('customer.search', compact('restaurants','foods','q'));
    }

    public function ajaxSearch(Request $request)
    {
        $q = $request->get('q','');
        $results = Restaurant::where('status','approved')
            ->where('name','like',"%$q%")
            ->take(5)->get(['id','name','city','logo']);
        return response()->json($results);
    }
}
