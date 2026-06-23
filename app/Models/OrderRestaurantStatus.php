<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderRestaurantStatus extends Model
{
    protected $table = 'order_restaurant_status';
    protected $fillable = ['order_id','restaurant_id','status','rejection_reason','accepted_at','ready_at'];
    protected $casts = ['accepted_at'=>'datetime','ready_at'=>'datetime'];

    public function order() { return $this->belongsTo(Order::class); }
    public function restaurant() { return $this->belongsTo(Restaurant::class); }
}
