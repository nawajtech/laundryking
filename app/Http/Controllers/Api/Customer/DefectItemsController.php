<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\Wallet;
use App\Models\OrderDetailsDetail;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Auth;
use Validator;

class DefectItemsController extends Controller
{
    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

//Defected items image shown in customer 
public function index(Request $request){
    $data=[];
    $images = array();
    $declined_list= OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
        $q->with('order')->whereHas('order', function($q) {
            $q->where('customer_id', $this->customer->id);
        });
    })->where('is_active',0)->where('accepted',1)->where('order_id',$request->order_id)->get(); 
    $accepted_list= OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
        $q->with('order')->whereHas('order', function($q) {
            $q->where('customer_id', $this->customer->id);
        });
    })->where('is_active',1)->where('accepted',1)->where('order_id',$request->order_id)->get(); 
    $ongoing_list= OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
        $q->with('order')->whereHas('order', function($q) {
            $q->where('customer_id', $this->customer->id);
        });
    })->where('is_active',1)->where('accepted',0)->where('order_id',$request->order_id)->get(); 
    foreach($accepted_list as $a){
        $service = Service::where('id', $a->order_details->service_id, )->first();
        $image = explode(',', $a->image);
        $images = array();
        foreach($image as $m){
            $image = asset('uploads/defect_item/'.$m); 
            $images[] = array(
                'image' => $image,
            );
        }

        $data[] = array(
            'order_detail_id' => $a->order_detail_id,
            'garment_id' => $a->garment_tag_id,
            'service_type_name' => $a->order_details->service_name,
            'quantity' => 1,
            'remarks' => $a->remarks ?? '',
            'garment_name' => $service->service_name,
            'category_image' => asset('assets/img/service-icons/' . $service->icon),
            'status' => 1,
            'images' => $images,
        );
    }
    foreach($declined_list as $d){
        $service = Service::where('id', $d->order_details->service_id, )->first();
        $image = explode(',', $d->image);
        $images = array();
        foreach($image as $m){
            $image = asset('uploads/defect_item/'.$m); 
            $images[] = array(
                'image' => $image,
            );
        }

        $data[] = array(
            'order_details_id' => $d->order_details_id,
            'garment_id' => $d->garment_tag_id,
            'service_type_name' => $d->order_details->service_name,
            'quantity' => 1,
            'remarks' => $d->remarks ?? '',
            'garment_name' => $service->service_name,
            'category_image' => asset('assets/img/service-icons/' . $service->icon),
            'status' => 2,
            'images' => $images,
        );
    }
    foreach($ongoing_list as $e){
        $service = Service::where('id', $e->order_details->service_id, )->first();
        $image = explode(',', $e->image);
        $images = array();
        foreach($image as $m){
            $image = asset('uploads/defect_item/'.$m); 
            $images[] = array(
                'image' => $image,
            );
        }
        $data[] = array(
            'order_details_id' => $e->order_details_id,
            'garment_id' => $e->garment_tag_id,
            'service_type_name' => $e->order_details->service_name,
            'quantity' => 1,
            'remarks' => $e->remarks ?? '',
            'garment_name' => $service->service_name,
            'category_image' => asset('assets/img/service-icons/' . $service->icon),
            'status' => 0,
            'images' => $images,
        );
    }
    return response()->json([
        'status' => 1,
        'message' => " item list",
        'accepted_list' => $data,
    ]);
}

public function approve_request(Request $request){
    $rules = [
        'garment_tag_id' => 'required',
        'accepted' => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => $validator->messages()->first(),
        ]);
    } 
    $defect_items= OrderDetailsDetail::where('garment_tag_id',$request->garment_tag_id)->where('is_active' ,1)->where('accepted', 0)->first();
    if($defect_items){
    // foreach($defect_items as $d){
    if($request->accepted ==1){
        $data['is_active'] = 1;
        $data['accepted'] = 1;
        OrderDetailsDetail::where('garment_tag_id', $request->garment_tag_id)->update($data);
        return response()->json([
            'status' => 1,
            'message' => 'Accepted.',
        ]);
    }
    if($request->accepted ==0){
        $a = OrderDetailsDetail::where('garment_tag_id', $request->garment_tag_id)->first();
        $service_price = OrderDetails::where('id', $a->order_detail_id)->first();
        $srvc_prc = $service_price->service_price;


        Wallet::create([
            'order_id' =>  $a->order_id,
            'receive_amount' => $srvc_prc,
            'customer_id' => $service_price->order->customer_id,
            'remarks' => "Defected Items Return".$request->garment_tag_id,

        ]);

        $data['is_active'] = 0;
        $data['accepted'] = 1;
        OrderDetailsDetail::where('garment_tag_id', $request->garment_tag_id)->update($data);
        return response()->json([
            'status' => 1,
            'message' => 'Declined.',
        ]);

        $details = OrderDetails::where('id', $defect_items->order_details_id)->first();
        $qty=$details->service_quantity;
        $quantity=$details->defected_quantity;
        $data1['service_quantity'] = $qty-1;
        $data1['defected_quantity'] = $quantity+1;
        OrderDetails::where('id', $request->order_details_id)->update($data1);
    }
    } else{
        return response()->json([
            'status' => 0,
            'message' => 'No order found.',
        ]);
    }
}
}