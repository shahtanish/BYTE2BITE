<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number','user_id','delivery_partner_id',
        'delivery_name','delivery_phone','delivery_address',
        'delivery_city','delivery_pincode','delivery_latitude','delivery_longitude',
        'subtotal','delivery_fee','tax','discount','total',
        'payment_method','payment_status','payment_id',
        'status','notes','accepted_at','picked_up_at','delivered_at',
        'current_latitude','current_longitude'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function deliveryPartner() { return $this->belongsTo(User::class, 'delivery_partner_id'); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function restaurantStatuses() { return $this->hasMany(OrderRestaurantStatus::class); }
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'order_items')
            ->distinct();
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending'    => '<span class="badge-status badge-pending">Pending</span>',
            'accepted'   => '<span class="badge-status badge-accepted">Accepted</span>',
            'preparing'  => '<span class="badge-status badge-preparing">Preparing</span>',
            'ready'      => '<span class="badge-status badge-accepted">Ready</span>',
            'picked_up'  => '<span class="badge-status badge-on_the_way">Picked Up</span>',
            'on_the_way' => '<span class="badge-status badge-on_the_way">On The Way</span>',
            'delivered'  => '<span class="badge-status badge-delivered">Delivered</span>',
            'cancelled'  => '<span class="badge-status badge-rejected">Cancelled</span>',
            'rejected'   => '<span class="badge-status badge-rejected">Rejected</span>',
        ];
        return $badges[$this->status] ?? $this->status;
    }

    public static function generateOrderNumber()
    {
        return 'B2B-' . strtoupper(Str::random(8));
    }
}
