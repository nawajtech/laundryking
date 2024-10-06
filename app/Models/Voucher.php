<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;
   
    protected $fillable = [
    	'code',
        'no_of_users',
        'each_user_useable',
        'total_useable',
        'total_used',
        'discount_type',
        'discount_amount',
        'cutoff_amount',
        'valid_from',
        'valid_to',
        'details',
        'image'
        
    ];
}