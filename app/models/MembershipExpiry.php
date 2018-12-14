<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class MembershipExpiry extends Model
{
    protected $table = 'membership_expiry';

    protected $fillable = [

    		  'business_id',
    		  'businessmen_id',
    		  'invoice_id',
              'paid_date',
              'expiry_date',
              'status',
    ];


}
