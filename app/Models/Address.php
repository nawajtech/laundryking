<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'customer_addresses';
    protected $primarykey="id";
    protected $fillable = [
        'customer_id',
        'address',
        'pincode',
        'flat_number',
        'area',
        'route_suggestion',
        'address_type',
        'status',
	'other',
	'longitude',
        'latitude',
	'address_name',

    ];
}
