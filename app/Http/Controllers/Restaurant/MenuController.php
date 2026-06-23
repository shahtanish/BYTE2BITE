<?php
namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    private function restaurantId()
    {
        return Auth::user()->restaurant->id;
    }

    public function index()
    {
        $rid   = $this->restaurantId();
        $items = FoodItem::where('restaurant_id',$rid)->with('category')->orderBy('sort_order')->paginate(20);
        return view('restaurant.menu.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('is_active',true)->get();
        return view('restaurant.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'food_type'   => 'required|in:veg,non_veg,vegan',
        ]);

        $data = $request->only('name','category_id','description','price','discount_price','food_type','preparation_time','sort_order');
        $data['restaurant_id']  = $this->restaurantId();
        $data['slug']           = Str::slug($request->name).'-'.Str::random(4);
        $data['is_available']   = $request->has('is_available');
        $data['is_featured']    = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $img = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/food'), $img);
            $data['image'] = $img;
        }

        FoodItem::create($data);
        return redirect()->route('restaurant.menu.index')->with('success','Menu item added!');
    }

    public function edit($id)
    {
        $item       = FoodItem::where('id',$id)->where('restaurant_id',$this->restaurantId())->firstOrFail();
        $categories = Category::where('is_active',true)->get();
        return view('restaurant.menu.edit', compact('item','categories'));
    }

    public function update(Request $request, $id)
    {
        $item = FoodItem::where('id',$id)->where('restaurant_id',$this->restaurantId())->firstOrFail();
        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'food_type'   => 'required|in:veg,non_veg,vegan',
        ]);

        $data = $request->only('name','category_id','description','price','discount_price','food_type','preparation_time','sort_order');
        $data['is_available'] = $request->has('is_available');
        $data['is_featured']  = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $img = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/food'), $img);
            $data['image'] = $img;
        }

        $item->update($data);
        return redirect()->route('restaurant.menu.index')->with('success','Menu item updated!');
    }

    public function destroy($id)
    {
        $item = FoodItem::where('id',$id)->where('restaurant_id',$this->restaurantId())->firstOrFail();
        $item->delete();
        return back()->with('success','Menu item deleted!');
    }

    public function toggle($id)
    {
        $item = FoodItem::where('id',$id)->where('restaurant_id',$this->restaurantId())->firstOrFail();
        $item->update(['is_available' => !$item->is_available]);
        return response()->json(['success'=>true,'available'=>$item->is_available]);
    }
}
