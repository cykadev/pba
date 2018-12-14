<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Businessmen extends Model
{
    protected $table = 'businessmen';
    public $timestamps = false;

    protected $fillable = [

            'bmr_number',
            'membership',
            'ntn',
            'executive_class',
            'name',
            'gender',
            'father_name',
            'phone_number',
            'mobile_number',
            'mobile_number2',
            'emergency_number',
            'relation',
            'email',
            'address',
            'postal',
            'nationality',
            'cnic',
            'overseas_cnic',
            'overseas_country',
            'dob',
            'province',
            'city',
            'passport_number',
            'issue_date',
            'expiry_date',
            'since',
            'image',
            'status',
            'date',
    ];

    public function emails()
    {
        return $this->hasMany( 'App\models\EmailsFoundation', 'businessmen_id' );
    }

    public function businessemails()
    {
        return $this->hasMany('App\models\EmailsFoundation', 'businessmen_id');
    }

    public function renewal()
    {
        return $this->hasOne('App\models\MembershipExpiry', 'businessmen_id')->where('status', true)->latest();
    }

    public function business()
    {
        return $this->hasMany('App\models\Business', 'business_id');
    }    


    /**
    * Get the parent record associated with the children.
    */
    // public function hirarcy()
    // {
    //     return $this->belongsTo('App\models\Hirarcy', 'businessmen_id');
    // }
}
