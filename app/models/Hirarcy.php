<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Hirarcy extends Model
{
    protected $table = 'businessmen_hirarcy';
    public $timestamps = false;

    public $fillable = ['businessmen_id', 'file'];



}
