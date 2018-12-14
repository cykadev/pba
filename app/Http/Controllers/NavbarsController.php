<?php

namespace App\Http\Controllers;

use App\models\Navbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NavbarsController extends Controller
{


	public function authuser()
	{
		return Auth::user()->role;
	}

    public function index()
    {

    	if (Auth::user()->role == 1) {
	    	$navbars = Navbar::with('ancestors')->get();
			return response()->json($navbars);
    	}else{
	    	$navbars = Navbar::where('is_admin', 0)->with('ancestors')->get();
			return response()->json($navbars);
    	}

    }
}
