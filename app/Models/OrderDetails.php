<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrderDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'service_id',
        'service_type_id',
        'service_name',
        'service_price',
        'service_quantity',
        'service_detail_total',
        'color_code',
        'brand',
        'brand_id'
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }

    public function order_details_details()
    {
        return $this->hasMany(\App\Models\OrderDetailsDetail::class, 'order_details_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'service_id', 'id');
    }
}