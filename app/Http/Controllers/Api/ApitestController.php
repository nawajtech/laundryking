<?php

namespace App\Http\Controllers\Api;

use App\Http\Helper\SmsHelper;
use Illuminate\Support\Facades\Log;
use App\Traits\SMSService;

class ApitestController
{
   use SMSService;

   public function test()
   {
      // OTP
      // $method = 'POST';
      // $requestUrl = 'otp?mobile=&template_id=';
      // $bodyType = 'json';
      // $queryParams = '';
      // $formParams = [
      //    "sender" => "LaKing",
      //    "template_id" => env('SMS_SEND_OTP_TEMPLATE_ID'),
      //    "otp" => '12345678',
      //    "mobile" => '917008744416',
      //    "otp_expiry" => 10,
      // ];
      // $headers = null;
      // $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
      // dd($response);


      // VERIFY OTP
      // $method = 'GET';
      // $requestUrl = 'otp/verify';
      // $bodyType = 'json';
      // $queryParams = [
      //    "otp" => '5306',
      //    "mobile" => '917008744416',
      // ];
      // $formParams = null;
      // $headers = null;
      // $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
      // dd($response);


      //CANCEL ORDER
      // $method = 'POST';
      // $requestUrl = 'flow/';
      // $bodyType = 'json';
      // $queryParams = '';
      // $formParams = [
      //    "sender" => "LaKing",
      //    "template_id" => env('SMS_CANCEL_ORDER_TEMPLATE_ID'),
      //    "short_url" => "1",
      //    "mobiles" => "917008744416",
      //    "order_no" => '12345678',
      //    "out_let" => '84785HVGG',
      // ];
      // $headers = null;
      // $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
      // dd($response);


      // ORDER DELEVERED
      // $method = 'POST';
      // $requestUrl = 'flow/';
      // $bodyType = 'json';
      // $queryParams = '';
      // $formParams = [
      //    "sender" => "LaKing",
      //    "template_id" => env('SMS_ORDER_DELIVERED_TEMPLATE_ID'),
      //    "short_url" => "1",
      //    "mobiles" => "917008744416",
      //    "order_no" => '12345678',
      //    "out_let" => '84785HVGG',
      // ];
      // $headers = null;
      // $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
      // dd($response);


      //SEND BULK SMS
      // $method = 'POST';
      // $requestUrl = 'flow/';
      // $bodyType = 'json';
      // $queryParams = '';
      // $recipients=[];
      // $recipients_data = array();
      //   if ($recipients) {
      //       foreach ($recipients as $r) {
      //           $recipients_data[] = array(
      //               'mobiles' => $r->mobile,
      //               'VAR1' => $r->var1,
      //           );
      //       }
      //   }
      // $formParams = [
      //    "template_id" => "646b43a8d6fc0579271f3b03",
      //    "recipients" => $recipients_data,
      // ];
      // $headers = null;
      // $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
      // dd($response);

      // ORDER CONFIRMED
      // $method = 'POST';
      // $requestUrl = 'flow/';
      // $bodyType = 'json';
      // $queryParams = '';
      // $formParams = [
      //    "sender" => "LaKing",
      //    "template_id" => "64819a58d6fc05422311d323",
      //    "short_url" => "1",
      //    "mobiles" => "917008744416",
      //    "order_no" => '12345678',
      //    "invoice_no" => '84785HVGG',
      // ];
      // $headers = null;
      // $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
      // dd($response);

      // ORDER READY
      $method = 'POST';
      $requestUrl = 'flow/';
      $bodyType = 'json';
      $queryParams = '';
      $formParams = [
         "sender" => "LaKing",
         "template_id" => "64819abdd6fc05637f258da3",
         "short_url" => "1",
         "mobiles" => "917008744416",
         "order_no" => '12345678',
         "out_let" => '84785HVGG',
      ];
      $headers = null;
      $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
      dd($response);
   }

   public function get_webhhok()
   {
      Log::info('get webhook');
      Log::info(request()->all());
   }

   public function post_webhook()
   {
      Log::info('post webhook');
      Log::info(request()->all());
   }
}
