<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DeliveryType extends Model
{
    use HasFactory;
    protected $fillable = [
    	'delivery_name',
        'type',
        'amount',
        'cut_off_amount',
        'delivery_in_days',
        'pickup_time_from',
        'pickup_time_to',
        'delivery_time_from',
        'delivery_time_to',
        'is_active'
    ];
}