<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMembershipLog extends Model
{
    use HasFactory;
    protected $table = 'customer_membership_logs';
    protected $fillable = [
        'customer_id',
        'membership_id',
        'membership_start_date',
        'membership_end_date'
    ];
}
