<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Notification;
use Auth;
use Illuminate\Http\Request;
use Validator;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

    public function update_device_token(Request $request)
    {
        $rules = [
            'device_token' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            $data['device_token'] = $request->device_token;
            Customer::where('id', $this->customer->id)->update($data);
            $notification = Notification::where('customer_id', $this->customer->id)->where('status',1)->count();
            return response()->json([
                'status' => 1,
                'message' => 'Device token updated',
                'notification_count' => $notification,
            ]);
        }
    }

    public function index()
    {
        $data = [];
        $notification = Notification::where('customer_id', $this->customer->id)->where('is_active', 1)->Orderby('id', 'desc')->get();
        if (count($notification) > 0) {
            foreach ($notification as $s) {
                $customer_data = $s->data;
                $create_time = strtotime($s->created_at,);
                $ist_time = $create_time + 19800;
                $notification_date = date('d-m-Y h:i A', $ist_time);
                $data[] = array(
                    'title' => $s->title,
                    'body' => $s->body,
                    'date' => $notification_date,
                    'data' => json_decode($customer_data),
                );
            }
            $notification_count = Notification::where('customer_id', $this->customer->id)->where('status',1)->count();
            if($notification_count>0){
                $status['status'] = 0;
                Notification::where('customer_id', $this->customer->id)->update($status);
            }
            return response()->json([
                'status' => 1,
                'message' => 'Notification Details.',
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'NO notification found.',
            ]);
        }

    }
    // notification Delete
    public function deletenotification()
    {
        $customer_id = $this->customer->id;
        $checnotification = Notification::where('customer_id', $customer_id)->where('is_active', 1)->get();
        if (count($checnotification) > 0) {
            foreach ($checnotification as $c) {
                $data['is_active'] = 0;
                Notification::where('customer_id', $this->customer->id)->update($data);
            }
            return response()->json([
                'status' => 1,
                'message' => 'Removed all notifiction',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Dont have any notifiction',
            ]);
        }
    }
}
