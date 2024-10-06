<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_name',
        'outlet_code',
        'outlet_address',
        'outlet_phone',
        'outlet_latitude',
        'outlet_longitude',
        'google_map',
        'is_active'
    ];

    public function workstation()
    {
        return $this->belongsTo(\App\Models\Workstation::class, 'workstation_id', 'id');
    }

    public function pincode()
    {
        return $this->hasMany(\App\Models\Pincode::class, 'outlet_id', 'id');
    }
}