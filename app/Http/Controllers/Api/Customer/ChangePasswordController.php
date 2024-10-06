<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
		// Validations
        $rules = [
            'old_password'=>'required|min:6',
            'new_password'=>'required|min:6',
            'confirm_password'=>'required|min:6'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // Validation failed
            return response()->json([
				'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
			// Fetch User
            $customer = Auth::user();
			$user = Customer::where('id',Auth::user()->id)->first();
			if($user) {
                if (!password_verify($request->old_password, $user->password)){
                return response()->json([
                    'status' => 0,
                    'message' => 'You have entered wrong password',
                ]);
                } 
                $confirm_password= Hash::make($request->confirm_password);
                if (!password_verify($request->new_password, $confirm_password)){
                    return response()->json([
                        'status' => 0,
                        'message' => 'Password does not match',
                    ]);
                    } 
				//Update Password
                $data['password'] = Hash::make($request->new_password);
                Customer::where('id', Auth::user()->id)->update($data);
		
				//Mail
				
				//End Mail
				
				return response()->json([
					'status' => 1,
					'message' => 'Your password updated successfully.',
				]);
			} else{
				return response()->json([
					'status' => 0,
					'message' => 'User not found',
				]);
			}
		}
    }
}
