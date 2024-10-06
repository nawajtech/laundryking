<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use HasFactory, SoftDeletes;
    
      /* user relation */
      protected $fillable = [
        'service_category_name', 
        'image'
    ];  
       public function service() {
        return $this->hasMany('App\Models\Service', 'service_category_id', 'id');
      }

}