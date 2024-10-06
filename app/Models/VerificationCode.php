<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;
    protected $table = 'verification_codes';
    protected $primarykey="id";

    protected $fillable = ['user_id', 'phone', 'otp', 'expire_at'];
}
