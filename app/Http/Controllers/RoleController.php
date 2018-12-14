<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
  
	public function checkUnique($email=null)
    {
        $unique = User::where('email', $email)->first();

        if ($unique) {
            echo 1;
        }else{
            echo 0;
        }
    }

    public function updateStatus($id)
    {
         // For Update Status

        $user = User::find($id);
        if ($id == 1) {
           return response()->json(['success' => false, 'error' => 'Unable to update superadmin'], 400);
        }else{

        if ($user->status == 1) {
            $user->status = 0;
        }else{
            $user->status = 1;
        }

        $user->save();
        }
    }

    public function updateRole($id)
    {
         // For Update Status

        $user = User::find($id);

        if ($id == 1) {
           return response()->json(['success' => false, 'error' => 'Unable to update superadmin'], 400);
        }else{

        if ($user->role == 1) {
            $user->role = 2;
        }else{
            $user->role = 1;
        }

        $user->save();
        }
    }

    public function updatepassword(Request $request)
    {
        $credentials = $request->input('params');
        $rules = [
            'id' => 'required|integer',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($credentials, $rules);
        if(!$validator->fails()) {
            $user = User::find($credentials['id']);

            $user->password = bcrypt($credentials['password']);

            $user->save();

        }else{
            return response()->json(['success' => false, 'error' => $validator->messages()], 400);
        }
    }

    public function removeUser($id)
    {
         // For remove

        $user = User::find($id);

        if ($id != 1) {
             $user->delete();
        }else{
            return response()->json(['success' => false, 'error' => 'Unable to remove superadmin'], 400);
        }
    }


    public function getUsers(Request $filter=null, $per_page=null)
    {
        $filter = isset($_REQUEST['filter']);
        $per_page = isset($_REQUEST['per_page']);

        if ($filter) {

            $condition = ($_REQUEST['filter'] == 'admin') ? 1 : 2;

            if ($_REQUEST['filter'] != '') {
                return $users = DB::table('users')
                                         ->where('name', 'like', $_REQUEST['filter'].'%')
                                         ->OrWhere('email', 'like', $_REQUEST['filter'].'%')
                                         ->OrWhere('role', 'like', $condition.'%')
                                         ->paginate(($per_page) ? $_REQUEST['per_page'] : 20);
            }else{
                return $users = DB::table('users')->paginate(($per_page) ? $_REQUEST['per_page'] : 20);
            }

        }else{
            return $users = DB::table('users')->paginate(($per_page) ? $_REQUEST['per_page'] : 20); 
        }

    }


    public function addUser(Request $request)
    {

        $credentials = $request->input('params');
        $rules = [
            'name' => 'required|',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);

                if(!$validator->fails()) {

						$saved = User::create([

						    'name' => $credentials['name'],
						    'email' => $credentials['email'],
						    'password' => bcrypt($credentials['password']),
						    'role' => $credentials['role'],
                            'image' => 'dummy_image.png',
						    'status' => 0,
						]);

						if ($saved) {
						    echo 1;
						}else{
						    echo 0;
						}
                }else{

                	return response()->json(['success' => false, 'error' => $validator->messages()], 200);

                }
    }

}
