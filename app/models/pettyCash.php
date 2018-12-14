<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class pettyCash extends Model
{
	protected $table = 'pettycash';
	public $timestamps = false;

    protected $fillable = [

    		  'category',
    		  'total',
    		  'description',
    		  'date'
    ];
}
