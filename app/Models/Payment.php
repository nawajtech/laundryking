<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_date',
        'customer_id',
        'customer_name',
        'order_id',
        'received_amount',
        'payment_type',
        'payment_note',
	    'transaction_id',
        'financial_year_id',
        'created_by'
    ];

    public function orderz()
    {
        return $this->hasMany(\App\Models\Order::class, 'id', 'order_id');
    }
}