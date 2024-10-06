<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_category_id',
        'service_name',
        'garment_code',
        'pieces',
        'icon',
        'size',
        'information',
        'is_active'
    ];

    public static $serviceitempieces = [
        "1" => "Single Pieces",
        "2" => "Pair",
        "3" => "Group Of Three"
    ];

    public function servicedetails() {
        return $this->hasMany('App\Models\ServiceDetail', 'service_id', 'id');
    }

}