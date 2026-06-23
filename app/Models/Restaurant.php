<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    protected $fillable = [
        'user_id','name','slug','description','phone','email',
        'address','city','state','pincode','latitude','longitude',
        'logo','banner','cuisine_type','opening_time','closing_time',
        'delivery_fee','min_order_amount','avg_delivery_time',
        'rating','total_reviews','status','is_open','is_active','rejection_reason'
    ];

    protected $casts = ['is_open' => 'boolean', 'is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function foodItems() { return $this->hasMany(FoodItem::class); }
    public function orders() { return $this->hasMany(Order::class, 'id', 'id'); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function orderStatuses() { return $this->hasMany(OrderRestaurantStatus::class); }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('uploads/restaurants/' . $this->logo) : asset('images/img-1.png');
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? asset('uploads/restaurants/' . $this->banner) : asset('images/about-bg.png');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($r) {
            $r->slug = $r->slug ?? Str::slug($r->name) . '-' . Str::random(4);
        });
    }
}
