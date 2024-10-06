<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $primarykey="id";
    protected $fillable = [
        'color',
        'quantity', 
        'brand_id', 
        'customer_id',
        'service_id',
        'service_type_id',
        'addon_id'
    ];

    public function service() {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }

    public function service_type() {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id', 'id');
    }

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }  
    
    public function addon_cart_items() {
        return $this->hasMany('App\Models\CartItemAddon', 'cart_id', 'id');
    }
    
}
