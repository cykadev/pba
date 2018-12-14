<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class BusinessPartner extends Model
{
    protected $table = 'business_partners';
    public $timestamps = false;

    protected $fillable = [

            'business_id',
            'businessmen_id',

    ];
}
