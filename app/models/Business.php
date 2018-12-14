<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'business';

    protected $fillable = [

            'businessmen_id',
            'br_number',
            'name',
            'address',
            'office',
            'website',
            'product',
            'group',
            'gst',
            'category',
            'criteria',
            'aop',
            'status'

    ];


    /**
     * The groups that belong to the user.
     */
    public function groups()
    {
        return $this->hasMany('App\models\GroupHirarcy', 'business_id');
    }
     /**
     * The Emails that belong to the user/Business.
     */
    public function emails()
    {
        return $this->hasMany('App\models\EmailsFoundation', 'business_id');
    }
    public function types()
    {
        return $this->hasMany('App\models\Type');
    }

}
