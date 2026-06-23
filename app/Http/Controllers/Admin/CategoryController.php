<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('foodItems')->orderBy('sort_order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create() { return view('admin.categories.create'); }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required|string|max:100']);
        $data = $request->only('name','description','sort_order');
        $data['slug']      = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        if ($request->hasFile('image')) {
            $img = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/categories'), $img);
            $data['image'] = $img;
        }
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success','Category created!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->only('name','description','sort_order');
        $data['is_active'] = $request->has('is_active');
        if ($request->hasFile('image')) {
            $img = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/categories'), $img);
            $data['image'] = $img;
        }
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success','Category updated!');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success','Category deleted!');
    }

    public function show($id) { return redirect()->route('admin.categories.index'); }
}
