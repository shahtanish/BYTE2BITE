<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->take(5)->get();
        return view('customer.profile', compact('user','orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $data = $request->only('name','email','phone','address','city','state','pincode');

        if ($request->hasFile('profile_image')) {
            $img = time().'_'.$request->file('profile_image')->getClientOriginalName();
            $request->file('profile_image')->move(public_path('uploads/profiles'), $img);
            $data['profile_image'] = $img;
        }

        $user->update($data);
        return back()->with('success','Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success','Password changed successfully!');
    }
}
