<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeliveryEarning extends Model
{
    protected $fillable = ['delivery_partner_id','order_id','amount','earned_at'];
    protected $casts = ['earned_at'=>'datetime'];
    public function deliveryPartner() { return $this->belongsTo(DeliveryPartner::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
