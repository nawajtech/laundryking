<?php

namespace App\Http\Helper;
use GuzzleHttp\Client;

class SmsHelper
{
    protected  $BASE_URL = "https://control.msg91.com/api/v5/";
    
    //common//
    public static function commonData($method, $url, $array = array())
    {
        $client = new Client();
        $common_data = [
            "sender" => "LaKing",
        ];
        $result = array_merge($common_data, $array);
        $response = $client->request($method, $url, [
            'body' => json_encode($result),
            'headers' => [
                'accept' => 'application/json',
                'authkey' => '345153A21USUbw3yx05faac6dfP1',
                'content-type' => 'application/json',
            ],
        ]);
        echo $response->getBody();
    }
    //End common //

    //----------------------- Send Sms -------------//
    public static function sendSms($mobile = '', $var1 = 'order_no', $var2='out_let', $value1='123456', $value2='32534dfgdfh')
    {
        $self = new static; 
        $base_url = $self->BASE_URL;
        $method = 'POST';
        $url = $base_url.'flow/';
        $data = [
            "template_id" => "646b43a8d6fc0579271f3b03",
            "short_url" => "1",
            "mobiles" => "917008744416",
            "$var1" => $value1,
            "$var2" => $value2,
        ];
        self::commonData($method, $url, $data);
    }
    //----------------------- Send Sms End -------------//

    //----------------------- Send  Bulk Sms -------------//
    public static function sendBulkSms($recipients = array())
    {
        //recipients
        $recipients_data = array();
        if ($recipients) {
            foreach ($recipients as $r) {
                $recipients_data[] = array(
                    'mobiles' => $r->mobile,
                    'VAR1' => $r->var1,
                    'VAR2' => $r->var2,
                );
            }
        }
        $method = 'POST';
        $url = 'https://control.msg91.com/api/v5/flow/';
        $data = [
            "template_id" => "629b3fc1891f102bc1631eb3",
            "recipients" => $recipients_data,
        ];
        self::commonData($method, $url, $data);
    }
    //----------------------- Send  Bulk Sms End -------------//

    //----------------------- Send  Otp Sms -------------//
    public static function sendOtp($mobile = '917008744416')
    {
        $self = new static; 
        $base_url = $self->BASE_URL;
        $method = 'POST';
        $url = $base_url.'otp?mobile=&template_id=';
        $data = [
            //param value//
            "template_id" => "64771db5d6fc052c0f5114d3",
            "mobile" => $mobile,
            "otp_expiry"=>10,
        ];
        self::commonData($method, $url, $data);
    }
    //----------------------- Send  Otp Sms End -------------//

    //----------------------- Verify Otp -------------//
    public static function verifyOtp($otp = '4550', $mobile = '917008744416')
    {
        $method = 'GET';
        $url = 'https://control.msg91.com/api/v5/otp/verify?otp=' . $otp . '&mobile=' . $mobile . '';
        $data = array();
        self::commonData($method, $url, $data);
    }
    //----------------------- Verify Otp End -------------//

}
