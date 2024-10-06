<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSettings extends Model
{
    use HasFactory;
    protected $fillable = [
    	'report_name',
    	'outlet_access',
    	'manager_access',
    ];
    public $timestamps = false;

    /* master settings value update settings */
    public function siteData(){
        $siteInfo=array();
        foreach($this->get() as $key=>$value){
            $siteInfo[$value['report_name']]=$value['outlet_access'];
        }
        return $siteInfo;
    }
    public function sitesData(){
        $siteInfo=array();
        foreach($this->get() as $key=>$value){
            $siteInfo[$value['report_name']]=$value['manager_access'];
        }
        return $siteInfo;
    }
}
