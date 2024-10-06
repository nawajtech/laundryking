<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVerificationCode extends Model
{
    use HasFactory;
    protected $table = 'customer_verification_codes';
    protected $primarykey="id";

    protected $fillable = ['customer_id', 'phone', 'otp', 'expire_at'];
}
