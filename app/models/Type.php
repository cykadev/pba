<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
	protected $table = 'business_type';

    protected $fillable = [

    		  'business_id',
    		  'type'
    ];
}
