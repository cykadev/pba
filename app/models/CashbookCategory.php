<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CashbookCategory extends Model
{
	protected $table = 'cashbook_categories';

    protected $fillable = [

    		  'category',
    ];
}
