<?php

namespace App\Http\Controllers\api\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class ChangePassController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $rules = [
            'password'=>'required|min:8'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
				'status' => 0,
                'message' => $validator->messages(),
            ]);
        } else {
			$user = User::where('id',$request->user_id)->first();
			
			if($user) {
				$data['password'] = Hash::make($request->password);
				User::where('id',$request->user_id)->update($data);				
				return response()->json([
					'status' => 1,
					'message' => 'Your password updated successfully.',
				]);
			}else{
				return response()->json([
					'status' => 0,
					'message' => 'User not found',
				]);
			}
		}
    }

    public function forgetpassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();
		
		if($user) {
	
			$password = substr( str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?" ), 0, 8 );
			$data['password'] = Hash::make($password);
            User::where('email',$request->email)->update($data);
			return response()->json([
				'password' => $password,
				'status' => 1,
				'message' => 'Great! Password has send to your email.',
			]);
		}else{
			return response()->json([
				'status' => 0,
				'message' => 'Sorry! this email is not register with us.',
			]);
		}
    }

}
