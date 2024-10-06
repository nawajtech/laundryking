<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAddon extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'addon_id',
        'service_id',
    ];
}
