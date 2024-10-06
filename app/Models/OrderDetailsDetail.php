<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailsDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'order_detail_id',
        'image',
        'garment_tag_id',
        'remarks',
        'rewash_image',
        'rewash_note',
        'is_active',
        'accepted',
	    'ready_at',
        'status',
	    'rewash_confirm'
    ];

    public function order_details()
    {
        return $this->belongsTo(\App\Models\OrderDetails::class, 'order_detail_id', 'id');
    }
}
