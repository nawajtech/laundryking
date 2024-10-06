<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Membership extends Model
{
    use HasFactory;
    protected $fillable=['membership_name','min_price','max_price', 'discount_type', 'discount', 'express_fee', 'delivery_fee', 'icon'];
    public $timestamps = false;
    
}