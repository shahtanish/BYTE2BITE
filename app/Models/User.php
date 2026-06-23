<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role',
        'address', 'city', 'state', 'pincode',
        'profile_image', 'is_active', 'is_approved',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }

    public function deliveryPartner()
    {
        return $this->hasOne(DeliveryPartner::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Order::class, 'delivery_partner_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isAdmin()    { return $this->role === 'admin'; }
    public function isRestaurant() { return $this->role === 'restaurant'; }
    public function isDelivery() { return $this->role === 'delivery'; }
    public function isCustomer() { return $this->role === 'customer'; }

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image
            ? asset('uploads/profiles/' . $this->profile_image)
            : asset('images/client-img.png');
    }
}
