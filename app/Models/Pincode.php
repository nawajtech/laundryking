<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    use HasFactory;

    protected $fillable = [
    	'outlet_id',
        'pincode',
        'place_name'
    ];
}