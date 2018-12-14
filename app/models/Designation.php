<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
	protected $table = 'businessmen_designation';
	public $timestamps = false;

    protected $fillable = ['business_id','businessmen_id','designation'];
}
