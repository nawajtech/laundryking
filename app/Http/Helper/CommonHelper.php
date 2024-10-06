<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Notification;

class CommonHelper {
    /**
     * @param int $slug Slug
     * 
     * @return string
     */
    
	public static function imageupload($file,$path) {
        $fileNameWithExtension   = $file->getClientOriginalName();
        $fileName                = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
        $fileExtension           = $file->getClientOriginalExtension();
        $database_path           = $fileName . '-' . time() . '.' . $fileExtension;
        $path = public_path('/uploads/'.$path);
        $file-> move($path, $database_path);
        $image = $database_path;
        return $image;
	}

    //image is null default image
	public static function file_exists_path($file) {
        if (!file_exists($file) || !is_file($file)) {
            $file = asset('uploads/customer/avatar-370-456322.png');
        }
        return asset($file);
	}
    
    //Our recursive function.
    public static function myrefercode(){
        //Print out the number.
        //$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = substr(str_shuffle($permitted_chars), 0, 6);
        $exist = Customer::where('refer_code', $num)->count();
        //If the number is exists.
        if ($exist == 0) {
            return $num;
        } else {
            //Call the function again. Generate another number.
            return static::myrefercode();
        }
    }

    //----------------------- Push Notification -----------------//
    public static function push_notification($title, $body, $user_type, $image,$customer_id, $data = array())
    {
		if($customer_id){
			//Insert Notification
			$notification = [
				'customer_id'=> $customer_id,
				'title'      => $title,
				'body' 		 => $body,
				'$user_type' => $user_type,
                'data'       => json_encode($data),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			];

			Notification::create($notification);
			$DEVICE_TOKEN = Customer::select('device_token')->where('id', $customer_id)->whereNotNull('device_token')->get();
		} else {
        	$DEVICE_TOKEN = Customer::select('device_token')->whereNotNull('device_token')->get();
		}
        // dd($DEVICE_TOKEN);
        
		if ($DEVICE_TOKEN) {
            foreach ($DEVICE_TOKEN as $DT) {
                $json_data = array(
                    "to" => $DT->device_token,
                    "content_available" => true,
                    "mutable_content" => true,
                    "priority" => "high",
                    "notification" => array(
                        "title" => $title,
                        "body" => $body,
                        'icon'  => 'ic_launcher_foreground',
                        "image" => $image,
                        //'message_icon' => base_url() . "assets/front/images/home_logo.png",
                        //"click_action" => "https://mridayaitservices.com/demo/qcharge",
                    ),
                    "data" => $data
                );

                $data = json_encode($json_data);
                //FCM API end-point
                $url = 'https://fcm.googleapis.com/fcm/send';
                //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
                $server_key = 'AAAAkVaHXho:APA91bHuYn7biORAghd_DJ6WZMDexgUOK_okxIKTDLlCrRkiWneJg0YgMNIpW6yqshj43i92se1QMSTUXSw5XjWM_Cf86-O-nQaImDb_OQMDCgrDqTz0ZtuvlOFQJI1nuvCq9ZOfkbm-'; 
                //header with content_type api key
                $headers = array(
                    'Content-Type:application/json',
                    'Authorization:key=' . $server_key
                );
                //CURL request to route notification to FCM connection server (provided by Google)
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Oops! FCM Send Error: ' . curl_error($ch));
                } else {
                    //echo $result;
                }
                curl_close($ch);
            }
        }
    }
    //----------------------- End Push Notification -------------//
}

