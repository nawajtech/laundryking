<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'user_type',
        'country_code',
        'outlet_id',
        'workstation_id',
        'is_subadmin',
        'is_active'
    ];

    public static $roletype = [
        "1" => "Sub Admin",
        "2" => "Outlet",
        "3" => "Floor Manager",
        "4" => "Driver",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function outlet()
    {
        return $this->belongsTo(\App\Models\Outlet::class, 'outlet_id', 'id');
    }

    public function workstation()
    {
        return $this->belongsTo(\App\Models\Workstation::class, 'workstation_id', 'id');
    }
}
