<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','restaurant_id','food_item_id',
        'food_name','food_price','quantity','subtotal','special_instructions'
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function foodItem() { return $this->belongsTo(FoodItem::class); }
}
