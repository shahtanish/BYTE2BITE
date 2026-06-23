<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id','restaurant_id','order_id','rating','comment','is_approved'];
    protected $casts = ['is_approved'=>'boolean'];
    public function user() { return $this->belongsTo(User::class); }
    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
