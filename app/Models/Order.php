<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'pickup_option',
        'delivery_option',
	    'parent_id',
	    'cgst_percentage',
	    'cgst_amount',
	    'sgst_percentage',
	    'sgst_amount',
	    'pickup_date',
	    'delivered_date',
        'outlet_id',
	    'pickup_driver_id',
        'delivery_driver_id',
        'delivery_outlet_id',
        'workstation_id',
        'customer_id',
        'voucher_id',
        'customer_name',
        'phone_number',
        'voucher_code',
        'express_charge',
        'rating',
        'feedback',
        'order_date',
        'delivery_type_id',
        'delivery_type',
        'delivery_date',
        'pickup_address_id',
        'pickup_flat_number',
        'pickup_area',
        'pickup_address',
        'pickup_route_suggestion',
        'pickup_address_type',
        'pickup_other',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_pincode',
        'garment_tag_id',
        'delivery_address_id',
        'delivery_flat_number',
        'delivery_area',
        'delivery_address',
        'delivery_route_suggestion',
        'delivery_address_type',
        'delivery_other',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_pincode',
        'pickup_time',
        'delivery_time',
        'sub_total',
        'addon_total',
        'delivery_charge',
        'discount',
        'voucher_discount',
        'cashback_amount',
        'cashback_flag',
        'tax_percentage',
        'tax_amount',
        'total',
        'note',
        'instruction',
        'status',
        'order_type',
        'created_by',
        'cancel_request',
	'cancel_by',
        'financial_year_id'
    ];

    /* user relation */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet::class, 'outlet_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class, 'id', 'order_id');
    }
    
    public function deliveryoutlet()
    {
        return $this->belongsTo(\App\Models\Outlet::class, 'delivery_outlet_id', 'id');
    }

    public function workstation()
    {
        return $this->belongsTo(\App\Models\Workstation::class, 'workstation_id', 'id');
    }

    public function pickup_driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'pickup_driver_id', 'id');
    }

    public function delivery_driver()
    {
        return $this->belongsTo(\App\Models\User::class, 'delivery_driver_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function order_details()
    {
        return $this->hasMany(\App\Models\OrderDetails::class, 'order_id', 'id');
    }

}