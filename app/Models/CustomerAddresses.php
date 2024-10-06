<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CustomerAddresses extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'flat_number',
        'area',
        'address',
        'route_suggestion',
        'address_type',
        'other',
        'latitude',
        'longitude',
        'pincode'
    ];
}
