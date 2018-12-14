<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class GroupHirarcy extends Model
{
    protected $table = 'business_groups_hirarcy';

    public $fillable = ['business_id', 'groups'];


    public function business()
    {
        return $this->belongsTo('App\models\Business');
    }

}
