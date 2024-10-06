<?php

namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use Auth;
use Str;
use Validator;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AddCustomerController extends Controller
{

//Method For Driver Customer Registration 
    public function register(Request $request)
    { 
        $rules = [
            'name'=>'required',
            'phone'=>'required|min:10|unique:customers',
            'country_code'=>'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) 
        {
            return response()->json([
				'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else 
        {
           $random_password = Str::random(6);
           $refer_code = CommonHelper::myrefercode();
           $customer=Customer::create([
            'salutation' => $request->salutation,
            'country_code' => $request->country_code,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($random_password),
            'refer_code' => $refer_code,
            'is_active' => '1'
       ]);
            $data=array(
                'customer_id' =>$customer->id,
                'Name' =>$request->name,
                'phone' =>$request->phone,
                'password' => $random_password,
            );
            return response()->json([
                'status' => 1,
                'message' => "You have Successfully Registered",
                'response' => $data,
            ]);
        }
    }
}