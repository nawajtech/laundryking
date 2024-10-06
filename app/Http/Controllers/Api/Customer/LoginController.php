<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\Customer;
use App\Models\MasterSettings;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

    //method for User Registration 
    public function registercustomer(Request $request)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required|min:10',
            'password' => 'required|min:6',
            'country_code' => 'required',
            'pin' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            $phone = Customer::where('phone', $request->phone)->first();
            if ($phone) {
                return response()->json([
                    'status' => 0,
                    'message' => "Phone number already exists",
                ]);
            } else {
                $refer_code = CommonHelper::myrefercode();
                $check_refer_code = '';
                if ($request->refer_code) {
                    $check_refer_code = Customer::where('refer_code', $request->refer_code)->first();
                    $c_id = $check_refer_code->id;
                }
                Customer::create([
                    'salutation' => $request->salutation,
                    'name' => $request->name,
                    'country_code' => $request->country_code,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'dob' => $request->dob,
                    'password' => Hash::make($request->password),
                    'tax_number' => $request->tax_number,
                    'company_name' => $request->company_name,
                    'company_address' => $request->company_address,
                    'gst' => $request->gst_number,
                    'locality' => $request->locality,
                    'pin' => (int)$request->pin,
                    'refer_code' => $refer_code,
                    'referrel_customer_id' => $c_id  ?? 0,
                    'address' => $request->address,
                    'is_active' => '1'
                ]);
                return response()->json([
                    'status' => 1,
                    'message' => "You have successfully registered",
                ]);
            }
        }
    }

    public function refercode(Request $request)
    {
        $check_refer_code = Customer::where('refer_code', $request->refer_code)->first();
        if ($check_refer_code) {
            return response()->json([
                'status' => 1,
                'message' => "You have successfully applied",
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => "Invalid refer code",
            ]);
        }
    }

    //method for user login
    public function logincustomer(Request $request)
    {
        $rules = [
            'phone' => 'required ',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->messages()->first(),]);
        } else {
            $user = Customer::where('phone', '=', $request->phone)->first();

            if (!$user) {
                return response()->json(['status' => 0, 'message' => 'We do not recognize your phone number'], 401);
            }

            if (!password_verify($request->password, $user->password)) {
                return response()->json(['status' => 0, 'message' => 'You have entered wrong password'], 401);
            }
            $refer = MasterSettings::where('master_title', 'refer_amount')->where('is_active', 1)->first();
            $joining = MasterSettings::where('master_title', 'joining_bonus')->where('is_active', 1)->first();
            $refer_amount = $refer->master_value;
            $joining_amount = $joining->master_value;
            $user = Customer::where('phone', $request['phone'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            $user_data = array(
                'customer_id'  => $user->id,
                'name'         => $user->name,
                'email'        => $user->email ?? '',
                'phone'        => $user->phone,
                'access_token' => $token,
                'refer_code'   => $user->refer_code ?? '',
                'refer_amount'   => number_format($refer_amount ?? '', 2),
                'joing_bonus'   => number_format($joining_amount ?? '', 2),
            );
            return response()->json(['status' => 1, 'message' => 'You are successfully login ', 'response' => $user_data]);
        }
    }

    // method for user logout and delete token
    public function logoutcustomer()
    {
        $logout = auth('customer')->user()->tokens()->delete();;
        if ($logout) {
            return response()->json([
                'status' => 1,
                'message' => 'You are successfully logout',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'User not found',
            ]);
        }
    }
}
