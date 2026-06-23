<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role','customer');
        if ($request->filled('search')) $query->where('name','like','%'.$request->search.'%')->orWhere('email','like','%'.$request->search.'%');
        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show($id) {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update($request->only('name','email','phone','is_active'));
        return back()->with('success','User updated!');
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return back()->with('success','User deleted!');
    }

    public function toggle($id) {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success','User status updated!');
    }

    public function create() { return view('admin.users.create'); }

    public function store(Request $request) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:6','role'=>'required']);
        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'is_active'   => true,
            'is_approved' => true,
        ]);
        return redirect()->route('admin.users.index')->with('success','User created!');
    }
}
