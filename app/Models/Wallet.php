<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
    	'customer_id',
    	'order_id',
        'deducted_amount',
        'receive_amount',
        'remarks',
    ];
}
