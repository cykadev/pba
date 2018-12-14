<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class BusinessHirarcy extends Model
{
    protected $table = 'business_hirarcy';

    public $fillable = ['business_id', 'docs'];
}
