<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ServiceDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'service_type_id',
        'service_price'
    ];

    public function service() {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }

    public function service_type() {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id', 'id');
    }
}