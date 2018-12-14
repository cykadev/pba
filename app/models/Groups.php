<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'business_groups';
    public $timestamps = false;

    public $fillable = ['code', 'title'];

}
