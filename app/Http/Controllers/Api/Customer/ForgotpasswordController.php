<?php

namespace App\Http\Controllers\Api\Customer;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\MasterSettings;
use App\Http\Controllers\Controller;
use App\Models\CustomerVerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use App\Traits\SMSService;
class ForgotpasswordController extends Controller
{
    use SMSService;

    public function otpgenerate(Request $request)
    {
        $rules = [
            'phone' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        }
        $code = $this->generateOtp($request->phone);
        if ($code) {
            $customer = Customer::where('phone', $request->phone)->first();
            if ($customer) {
                if($request->type == 'login'){
                $verificationCode = CustomerVerificationCode::where('customer_id', $customer->id)->first();
                }elseif($request->type == 'forgotpassword'){
                    $verificationCode = CustomerVerificationCode::where('customer_id', $customer->id)->first();
                }
                return response()->json([
                    'status' => 1,
                    'message' => "Otp Sent Successfully",
                    'otp' => $verificationCode->otp,
                ]);
        }
        } else {
            return response()->json([
                'status' => 0,
                'message' => "Please choose correct phone number",
            ]);
        }
    }

    public function generateOtp($phone)
    {
        $customer = Customer::where('phone', $phone)->first();
        if ($customer) {
            $verificationCode = CustomerVerificationCode::where('customer_id', $customer->id)->first();
            $now = Carbon::now();
            $curdatetime = $now->format('Y-m-d H:i:s');
            $otp= rand(1234, 9999);
            if ($verificationCode) {
                $diff = strtotime($curdatetime) - strtotime($verificationCode->expire_at);
                if ($diff < ENV('OTP_EXPIRE_TIME')) {
                    return $verificationCode;
                }
            }
            if ($verificationCode) {
                $data['otp']         = $otp;
                $data['expire_at'] = $curdatetime;
                $insert = CustomerVerificationCode::where('id', $verificationCode->id)->update($data);
            } else {
                $insert = CustomerVerificationCode::create([
                    'customer_id' => $customer->id,
                    'phone' => $customer->phone,
                    'otp' => $otp,
                    'expire_at' => $curdatetime
                ]);
            }
            // 3rd party Api Integration for otp sms send //
            if($insert){
              $this->sendOTP($customer->phone,$otp); 
            }
            return $insert;
        }
    }

    public function otplogin(Request $request)
    {
        #Validation
        $request->validate([
            'phone' => 'required|exists:customer_verification_codes,phone',
            'otp' => 'required'
        ]);
        $verificationCode  = CustomerVerificationCode::where('phone', $request->phone)->where('otp', $request->otp)->first();
        $now = Carbon::now();
        $curdatetime = $now->format('Y-m-d H:i:s');
        $refer = MasterSettings::where('master_title', 'refer_amount')->where('is_active', 1)->first();
        $joining = MasterSettings::where('master_title', 'joining_bonus')->where('is_active', 1)->first();
        $refer_amount=$refer->master_value;
        $user = Customer::where('phone', $request->phone)->first();
        $joining_amount=$joining->master_value;
        $token = $user->createToken('auth_token')->plainTextToken;
        if ($verificationCode) {
            $diff = strtotime($curdatetime) - strtotime($verificationCode->expire_at);
        }
        if (!$verificationCode) {
            return response()->json([
                'status' => 0,
                'message' => "Your Otp is not Correct",
            ]);
        } elseif ($verificationCode  && $diff > 600) {
            return response()->json([
                'status' => 0,
                'message' => "Otp has Expired",
            ]);
        } else {
            $customer = Customer::where('phone', $request['phone'])->firstOrFail();
            $customer_data = array(
                'customer_id' => $customer->id,
                'name'        => $customer->name,
                'email'       => $customer->email,
                'phone'       => $customer->phone,
                'access_token' => $token,
                'refer_code'   => $user->refer_code ??'',
                'refer_amount' => number_format($refer_amount ??'',2),
                'joing_bonus'  => number_format($joining_amount??'',2),
            );
            return response()->json([
                'status' => 1,
                'message' => "You are successfully login",
                'response' => $customer_data,
            ]);
        }
    }

    public function otpresend(Request $request)
    {
        $rules = [
            'phone' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        }
        $verificationCode = $this->resendgenerateotp($request->phone);
        $customer = CustomerVerificationCode::where('phone', $request->phone)->first();
        if ($customer) {
            if($request->type == 'login'){
                $verificationCode = CustomerVerificationCode::where('id', $customer->id)->first();
            }elseif($request->type == 'forgotpassword'){
                $verificationCode = CustomerVerificationCode::where('id', $customer->id)->first();
            }
            if ($verificationCode) {
                $otp= $verificationCode->otp;
                // 3rd party Api Integration for otp sms send //
                if($request->type == 'login'){
                   $this->sendOTP($request->phone,$otp); 
                }
                if($request->type == 'forgotpassword'){
                    $this->forgetPASSWORD($request->phone,$otp); 
                }
                return response()->json([
                    'status' => 1,
                    'message' => "Otp Resend Successfully",
                    'otp' =>$otp,
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => "Please choose correct phone number",
            ]);
        }
    }

    public function resendgenerateotp($phone)
    {
        $customer = CustomerVerificationCode::where('phone', $phone)->first();
        if ($customer) {
            $verificationCode = CustomerVerificationCode::where('id', $customer->id)->first();
            $now = Carbon::now();
            $curdatetime = $now->format('Y-m-d H:i:s');
            if ($verificationCode) {
                $diff = strtotime($curdatetime) - strtotime($verificationCode->expire_at);
                if ($diff < ENV('OTP_EXPIRE_TIME')) {
                    return $verificationCode;
                }
            }
            $otp = rand(1234, 9999);
            $data['otp']         = $otp;
            $data['expire_at'] = $curdatetime;
            return CustomerVerificationCode::where('id', $verificationCode->id)->update($data);
        }
    }

    //update password
    public function update_password(Request $request)
    {
        $rules = [
            'phone' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            if (!($request->new_password == $request->confirm_password)) {
                return response()->json([
                    'status' => 0,
                    'message' => "Password is mismatch",
                ]);
            } else {
                $customer = Customer::where('phone', $request->phone)->first();
                $hash_password = Hash::make($request->confirm_password);
                $data['password'] = $hash_password;
                Customer::where('id', $customer->id)->update($data);
                return response()->json([
                    'status' => 1,
                    'message' => "Password updated successfully",
                ]);
            }
        }
    }
    // OTP generate
    public function sendOTP($mobile,$otp){
        $method = 'POST';
        $requestUrl = 'otp?mobile=&template_id=';
        $bodyType = 'json';
        $queryParams = '';
        $mobile_no='';
        // check +91 is in mobile no or not //
        if(str_contains($mobile,'91')){
            $mobile_no=$mobile;
        }else{
            $mobile_no='91'.$mobile;
        }
        $formParams = [
           "sender" => env('SMS_PANEL_SENDER_ID'),
           "template_id" => env('SMS_SEND_OTP_TEMPLATE_ID'),
           "otp" => $otp,
           "mobile" => $mobile_no,
           "otp_expiry" => 10,
        ];
        $headers = null;
        $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
    }

    // Forget password OTP
    public function forgetPASSWORD($mobile,$otp){
        $method = 'POST';
        $requestUrl = 'otp?mobile=&template_id=';
        $bodyType = 'json';
        $queryParams = '';
        $mobile_no='';
        // check +91 is in mobile no or not //
        if(str_contains($mobile,'91')){
            $mobile_no=$mobile;
        }else{
            $mobile_no='91'.$mobile;
        }
        $formParams = [
           "sender" => env('SMS_PANEL_SENDER_ID'),
           "template_id" => env('SMS_FORGOT_PASSWORD_TEMPLATE_ID'),
           "otp" => $otp,
           "mobile" => $mobile_no,
           "otp_expiry" => 10,
        ];
        $headers = null;
        $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
    }

}
