<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Cashbook extends Model
{
	protected $table = 'cashbook';
	public $timestamps = false;

    protected $fillable = [

    		  'type',
    		  'category',
    		  'payer',
    		  'method',
    		  'ref',
    		  'total',
    		  'description',
    		  'date',
              'status'
    ];
}
