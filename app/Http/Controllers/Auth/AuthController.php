<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\DeliveryPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return $this->redirectByRole(Auth::user()->role);
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended. Contact support.']);
            }
            $request->session()->regenerate();
            return $this->redirectByRole($user->role);
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'role'        => 'customer',
            'is_active'   => true,
            'is_approved' => true,
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Welcome to BYTE2BITE, ' . $user->name . '!');
    }

    public function showRestaurantRegister()
    {
        return view('auth.restaurant-register');
    }

    public function restaurantRegister(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users',
            'phone'            => 'required|string|max:20',
            'password'         => 'required|min:6|confirmed',
            'restaurant_name'  => 'required|string|max:200',
            'restaurant_phone' => 'required|string|max:20',
            'address'          => 'required|string',
            'city'             => 'required|string',
            'cuisine_type'     => 'nullable|string',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'role'        => 'restaurant',
            'is_active'   => true,
            'is_approved' => false,
        ]);

        // Handle logo upload
        $logo = null;
        if ($request->hasFile('logo')) {
            $logo = time() . '_' . $request->file('logo')->getClientOriginalName();
            $request->file('logo')->move(public_path('uploads/restaurants'), $logo);
        }

        Restaurant::create([
            'user_id'      => $user->id,
            'name'         => $request->restaurant_name,
            'slug'         => Str::slug($request->restaurant_name) . '-' . Str::random(4),
            'phone'        => $request->restaurant_phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'city'         => $request->city,
            'cuisine_type' => $request->cuisine_type,
            'logo'         => $logo,
            'status'       => 'pending',
        ]);

        Auth::login($user);
        return redirect()->route('restaurant.dashboard')
            ->with('success', 'Restaurant registered! Pending admin approval.');
    }

    public function showDeliveryRegister()
    {
        return view('auth.delivery-register');
    }

    public function deliveryRegister(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users',
            'phone'          => 'required|string|max:20',
            'password'       => 'required|min:6|confirmed',
            'vehicle_type'   => 'required|string',
            'vehicle_number' => 'required|string',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'role'        => 'delivery',
            'is_active'   => true,
            'is_approved' => false,
        ]);

        DeliveryPartner::create([
            'user_id'        => $user->id,
            'vehicle_type'   => $request->vehicle_type,
            'vehicle_number' => $request->vehicle_number,
            'license_number' => $request->license_number,
        ]);

        Auth::login($user);
        return redirect()->route('delivery.dashboard')
            ->with('success', 'Registered! Pending admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    private function redirectByRole($role)
    {
        return match($role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'restaurant' => redirect()->route('restaurant.dashboard'),
            'delivery'   => redirect()->route('delivery.dashboard'),
            default      => redirect()->route('home'),
        };
    }
}
