<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Customer extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'customers';
    protected $guard = 'customer';
    use HasFactory;

    protected $fillable = [
        'salutation',
        'name',
        'dob',
        'email',
        'phone',
	 'country_code',
        'password',
        'image',
	'auth_token',
	'update_device_token',
        'tax_number',
        'gst',
        'company_name',
        'company_address',
        'locality',
        'pin',
        'discount',
        'rating',
        'address',
        'is_active',
        'created_by',
	'referrel_customer_id',
        'refer_code',

    ];

    public static $salutations = [
        "Mr." => "Mr.",
        "Ms." => "Ms.",
        "Mrs." => "Mrs.",
        "Miss." => "Miss.",
        "Dr." => "Dr."
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}