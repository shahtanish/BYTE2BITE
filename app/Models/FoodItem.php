<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FoodItem extends Model
{
    protected $fillable = [
        'restaurant_id','category_id','name','slug','description',
        'price','discount_price','image','food_type',
        'is_available','is_featured','preparation_time','sort_order'
    ];

    protected $casts = ['is_available' => 'boolean', 'is_featured' => 'boolean'];

    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/food/' . $this->image) : asset('images/img-2.png');
    }

    public function getEffectivePriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($f) {
            $f->slug = $f->slug ?? Str::slug($f->name) . '-' . Str::random(4);
        });
    }
}
