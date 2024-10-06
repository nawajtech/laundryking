<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\Notification;
use App\Http\Helper\CommonHelper;
use App\Models\OrderDetailsDetail;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Auth;
use Validator;

class FloorManagerController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::guard('user')->user();
        // dd($this->user);
    }

    public function insert(Request $request)
    { 
        $rules = [
            'remarks' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } else {
            // $order_details = OrderDetailsDetail::where('garment_tag_id', $request->garment_tag_id)->where('image' , NULL)->first();
            $order_details = OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
                $q->with('order')->whereHas('order', function($q) {
                    $q->where('workstation_id', $this->user->workstation_id);
                });
            })->where('garment_tag_id', $request->garment_tag_id)->where('image' , NULL)
            ->whereIn('status', [3,4,5])->first(); 
            if ($order_details) {
                $images = [];
                $i = 1;
                foreach ($request->file('images') as $image) {
                    $imageName = time() . $i . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/defect_item'), $imageName);
                    $images[] = $imageName;
                    $image_path[] = asset('uploads/defect_item/' . $imageName);
                    $i++;
                }
                // dd($images);
                $data['image'] = implode(",",$images);
                $data['remarks'] = $request->remarks;
                $data['is_active'] = 1;
                $data['accepted'] = 0;
                OrderDetailsDetail::where('id', $order_details->id)->update($data);
            $order_Det = OrderDetailsDetail::where('garment_tag_id',$request->garment_tag_id)->first();
            $order = Order::where('id',$order_Det->order_id)->first();
            $customer_id = $order->customer_id;
            $title = "Approved Request";
            $image = '';
            $body = "Check your defected item " . $request->garment_tag_id ;
            $user_type = $request->user_type;
            $data = array(
                "Garment id" => $request->garment_tag_id,
                "type" => "Defect item",
            );
            $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => "Provided order is not correct",
                ]);
            }
        }
        return response()->json([
            'status' => 1,
            'message' => "You have successfully sent",
            'images' => $image_path,
        ]);
    }

    public function orders(Request $request){
        $order = Order::where('order_number', $request->order_number)->first();
        $order_details= OrderDetails::where('order_id', $order->id)->get();
        if($order_details){
            foreach ($order_details as $ord){
            $orddet_det=OrderDetailsDetail::where('is_active', 0)->where('accepted', 1)->where('order_details_id', $ord->id)->count();
        if($orddet_det){
            $data1= array(
                'Defected_quantity'=>$orddet_det,
                'service_quantity'=>$ord->service_quantity,
            );
        }
            $service = Service::where('id', $ord->service_id)->first();
                $id = $ord->id;
                $service_name = $ord->service_name;
                $service_name = $ord->service_name;
                $data[]= array(
                    'id'=>$id,
                    'service_name'=>$service_name,
                    'garment_name'=>$service->service_name,
                    'garment_image'=>asset('/assets/img/service-icons/' .$service->icon),
                    'quantity'=>$data1 ?? '',
                );
            }
        return response()->json([
            'status' => 1,
            'message' => 'Order Details.',
            'response' => $data,
        ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Data Found.',
            ]);
        }
    }

    public function listing(){
        $data=[];
        $data1=[];
        $data2=[];
        $images = array();
        $accepted_list= OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
            $q->with('order')->whereHas('order', function($q) {
                $q->where('workstation_id', $this->user->workstation_id);
            });
        })->where('is_active',0)->where('accepted',1)->get(); 
        $declined_list= OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
            $q->with('order')->whereHas('order', function($q) {
                $q->where('workstation_id', $this->user->workstation_id);
            });
        })->where('is_active',1)->where('accepted',1)->get(); 
        $ongoing_list= OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
            $q->with('order')->whereHas('order', function($q) {
                $q->where('workstation_id', $this->user->workstation_id);
            });
        })->where('is_active',1)->where('accepted',0)->get(); 
        foreach($accepted_list as $a){
            $image = explode(',', $a->image);
            $images = array();
            foreach($image as $m){
                $image = asset('uploads/defect_item/'.$m); 
                $images[] = array(
                    'image' => $image,
                );
            }

            $data[] = array(
                'order_detail_id' => $a->garment_tag_id,
                'remarks' => $a->remarks ?? '',
                'images' => $images,
            );
        }
        foreach($declined_list as $d){
            $image = explode(',', $d->image);
            $images = array();
            foreach($image as $m){
                $image = asset('uploads/defect_item/'.$m); 
                $images[] = array(
                    'image' => $image,
                );
            }

            $data1[] = array(
                'order_detail_id' => $d->garment_tag_id,
                'remarks' => $d->remarks ?? '',
                'images' => $images,
            );
        }
        foreach($ongoing_list as $e){
            $image = explode(',', $e->image);
            $images = array();
            foreach($image as $m){
                $image = asset('uploads/defect_item/'.$m); 
                $images[] = array(
                    'image' => $image,
                );
            }
            $data2[] = array(
                'order_detail_id' => $e->garment_tag_id,
                'remarks' => $e->remarks ?? '',
                'images' => $images,
            );
        }
        return response()->json([
            'status' => 1,
            'message' => " item list",
            'accepted_list' => $data1,
            'declined_list' => $data,
            'ongoing_list' => $data2,
        ]);
    }

    public function imagecheck(Request $request){
        $order_det_det = OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
            $q->with('order')->whereHas('order', function($q) {
                $q->where('workstation_id', $this->user->workstation_id);
            });
        })->where('garment_tag_id', $request->garment_tag_id)->where('image' , NULL)
        ->whereIn('status', [3,4,5])->first(); 


        if($order_det_det){
            return response()->json([
                'status' => 0,
                'message' =>'You can upload image',
            ]);
        }else{
            return response()->json([
                'status' => 1,
                'message' =>'You can not upload image',
            ]);
        }     
    }

    public function update_device_token_user(Request $request)
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
            $data['auth_token'] = $request->device_token;
            User::where('id', $this->user->id)->update($data);
            $notification = Notification::where('customer_id', $this->user->id)->where('status',1)->count();
            return response()->json([
                'status' => 1,
                'message' => 'Device token updated',
                'notification_count' => $notification,
            ]);
        }
    }

}
