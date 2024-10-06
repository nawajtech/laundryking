<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerQuery extends Model
{
    protected $table = 'customer_queries';
    protected $fillable = [
        'phone',
        'message', 
        'user_type', 
    ];
}
