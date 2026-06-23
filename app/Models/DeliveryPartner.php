<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeliveryPartner extends Model
{
    protected $fillable = [
        'user_id','vehicle_type','vehicle_number','license_number',
        'id_proof_type','id_proof_number','id_proof_image',
        'earnings_total','total_deliveries','rating','is_available',
        'current_latitude','current_longitude'
    ];
    protected $casts = ['is_available'=>'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function earnings() { return $this->hasMany(DeliveryEarning::class); }
}
