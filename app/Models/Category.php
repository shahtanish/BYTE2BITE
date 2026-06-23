<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'image', 'description', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function foodItems() { return $this->hasMany(FoodItem::class); }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('uploads/categories/' . $this->image) : asset('images/img-1.png');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($c) {
            $c->slug = $c->slug ?? Str::slug($c->name);
        });
    }
}
