<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class EmailsFoundation extends Model
{
    
    protected $table = 'emails_foundation';
    protected $fillable = [

            'business_id',
            'businessmen_id',
            'is_business',
            'email',

    ];

}
