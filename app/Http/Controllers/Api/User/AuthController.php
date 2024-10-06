<?php

namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Validator;
use App\Models\User;
use App\Models\OutletDriver;
use App\Models\Outlet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->user = Auth::guard('user')->user();
    }

    //method for user login
    public function login(Request $request)
    {
        $rules = [
            'phone'=>'required ',
            'password'=>'required',
            'user_type'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json(['status' => 0,'message' => $validator->messages()->first(),
            ]);
        } else {
            $userInfo=User::where('Phone','=',$request->phone)->first();
            if(!$userInfo){
                return response()->json(['status' => 0,'message' => 'We do not recognize your phone number'], 401);
            } 
            if(!password_verify($request->password, $userInfo->password) ){
                return response()->json(['status' => 0,'message' => 'Please enter correct password'], 401);
            } 
            $usertype=User::where('phone', $request['phone'])->where('user_type','=', $request->user_type)->first();
            if(!$usertype){
                return response()->json(['status' => 0,'message' => 'Credential mismatch'], 401);
            } 
            if($userInfo){
                if (Auth::attempt($request->only('phone','password')))
                $user = User::where('phone', $request['phone'])->firstOrFail();
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            if ($userInfo->image) {
                $image = asset('uploads/user/' . $userInfo->image);
            } elseif($userInfo->image == NULL) {
                $image = asset('uploads/user/avatar-370-456322.png');
            }
            if($request->user_type ==4){
                $outlet_drivers = OutletDriver::where('user_id', $user->id)->first();
                if($outlet_drivers){
                $outlet = Outlet::where('id', $outlet_drivers->outlet_id)->first();
                }else{
                    return response()->json([ 'status' => 0, 'message' => 'please choose outlet first']);
                }
            } 
            $user_data = array(
                'user_id'      => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'phone'        => $user->phone,
                'user_type'    => $user->user_type,
                'access_token' => $token,
                'image' => $image,
            );
            return response()->json([ 'status' => 1, 'message' => 'You are Successfully Login '.$user->name.' ',  'response' => $user_data,]);
            
            
        }
    }

    // method for user logout and delete token
    public function logout()
    {

        $logout= auth('user')->user()->tokens()->delete();;

        if($logout) {
                return response()->json([
					'status' => 1,
                    'message' => 'You are Successfully Logout',
                ]);
            }
        else {
            return response()->json([
				'status' => 0,
                'message' => 'User not found',
            ]);
        }
    }


}