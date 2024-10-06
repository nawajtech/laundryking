<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\OrderDetailsDetail;
use App\Models\OrderAddonDetail;
use App\Models\OrderDetails;
use App\Models\ServiceCategory;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class RewashController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

    public function rewash(Request $request)
    { 
        $customer = Auth::user();
        $orders = Order::where('id', $request->order_id)->first();
        $delivery = DeliveryType::where('id', $orders->delivery_type_id)->where('is_active', 1)->first();
        $rewash = Order::where('parent_id' ,$request->order_id)->where('customer_id' , $customer->id)->count();
        $rewash_check = OrderDetailsDetail::whereIn('rewash_confirm',[1,2,3])->where('garment_tag_id' , $request->garment_tag_id)->first();
        if($rewash_check){
            return response()->json([
                'status' => 0,
                'message' => "Already request Sent",
            ]);
        }
        $currentDateTime='';
        $newDateTime='';
        if ($delivery) {
            if ($delivery->id == 1) {
                $currentDateTime = Carbon::now();
                $newDateTime = Carbon::now()->addDays(7);
            }
            if ($delivery->id == 2) {
                $currentDateTime = Carbon::now();
                $newDateTime = Carbon::now()->addDays(2);
            }
            if ($delivery->id == 3) {
                $currentDateTime = Carbon::now();
                $newDateTime = Carbon::now()->addDays(1);
            }}
        $rules = [
            'rewash_note' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        }
        if($orders){
            $parents = $orders->parents;
            $totalElements = count($parents);
            if($totalElements > 0){
                $order_number = $parents[$totalElements - 1]['order_number'];
            }else{
                $order_number = $orders->order_number;
            }
            $order_number = 'RE'. count($parents) + 1 .':'. $order_number;

        $order = Order::create([
            'order_number' => $order_number,
            'parent_id' => $request->order_id,
            'outlet_id' => $orders->outlet_id ?? 0,
            'workstation_id' => $orders->workstation_id,
            'delivery_outlet_id' => $orders->delivery_outlet_id,
            'customer_id' => $customer->id,
            'pickup_option' => 2,
            'delivery_option' => 2,
            'delivery_type_id' => $orders->delivery_type_id,
            'pickup_address_id' => $orders->pickup_address_id,
            'delivery_address_id' => $orders->delivery_address_id,
            'customer_name' => $customer->name,
            'phone_number' => $customer->phone,
            'order_date' => $currentDateTime,
            'delivery_date' => $newDateTime,
            'sub_total' => 0,
            'addon_total' => 0,
            'discount' => 0,
            'voucher_discount' => 0,
            'delivery_charge' => 0,
            'tax_percentage' => 0,
            'tax_amount' => 0,
            'total' => 0,
            'note' => null,
            'status' => 0,
            'order_type' => 1,
            'delivery_type' => $orders->delivery_type,
            'created_by' => $this->customer->id,
            'financial_year_id' => 0,
            'voucher_id' => 0,
            'voucher_code' => 0,
            'flag' => 1,
            'pickup_flat_number' => $orders->pickup_flat_number ?? null,
            'pickup_area' => $orders->pickup_area ?? null,
            'pickup_address' => $orders->pickup_address ?? null,
            'pickup_route_suggestion' => $orders->pickup_route_suggestion ?? null,
            'pickup_address_type' => $orders->pickup_address_type ?? null,
            'pickup_other' => $orders->pickup_other ?? null,
            'pickup_latitude' => $orders->pickup_latitude ?? null,
            'pickup_longitude' => $orders->pickup_longitude ?? null,
            'pickup_pincode' => $orders->pickup_pincode,
            'delivery_flat_number' => $orders->delivery_flat_number ?? null,
            'delivery_area' => $orders->delivery_area ?? null,
            'delivery_address' => $orders->delivery_address ?? null,
            'delivery_route_suggestion' => $orders->delivery_route_suggestion ?? null,
            'delivery_address_type' => $orders->delivery_address_type ?? null,
            'delivery_other' => $orders->delivery_other ?? null,
            'delivery_latitude' => $orders->delivery_latitude ?? null,
            'delivery_longitude' => $orders->delivery_longitude ?? null,
            'delivery_pincode' => $orders->delivery_pincode,
        ]);
        //items details
        // $i=0;
        $order_details_detail_id = $request->order_details_detail_id;
        if($request->order_details_detail_id) {
            $ord_detdet_id = explode(',', $order_details_detail_id);
            $ordDetDet=OrderDetailsDetail::whereIn('id', $ord_detdet_id)->get();
            foreach ($ordDetDet as $d) {
                $order_detail = OrderDetails::where('order_id', $request->order_id)->where('id', $d->order_detail_id)->first();
                $OrderDetailsDetail = OrderDetailsDetail::where('order_id', $request->order_id)->where('id', $d->id)->where('order_detail_id',  $d->order_detail_id)->first();
                $count = OrderDetailsDetail::where('order_id', $request->order_id)->where('order_detail_id',  $order_detail->id)->where('id', $d->id)->count();
                // dd($count);
                if ($order_detail) {
                    $order_details = OrderDetails::create([
                        'order_id' => $order->id,
                        'service_id' => $order_detail->service_id,
                        'service_type_id' => $order_detail->service_type_id,
                        'service_name' => $order_detail->service_name,
                        'service_price' => $order_detail->service_price,
                        'service_quantity' => $count,
                        'service_detail_total' => $order_detail->service_price * $count,
                        'color_code' => $order_detail->color_code,
                    ]);
                    
                        OrderDetailsDetail::create([
                            'order_detail_id'  => $order_details->id,
                            'order_id'  => $order_details->order_id,
                            'garment_tag_id'   => $OrderDetailsDetail->garment_tag_id ,
                            'image' => null,
                            'remarks'  => null,
                            'is_active'  => 0,
                            'rewash_image'  => null,
                            'rewash_confirm'  => 1,
                            'rewash_note'  => $request->rewash_note,
                            'accepted'  => 0,
                            'status' => $order->status,
                        ]);
                    }
                // }
                // $i = $count+$i;
                //addon details
                $order_addon_details = OrderAddonDetail::where('order_id', $request->order_id)->where('order_detail_id', $d->id)->get();
                $addon_details = array();
                foreach ($order_addon_details as $add) {
                    $addon_details = OrderAddonDetail::create([
                        'id' => $add->id,
                        'order_id' => $request->order_id,
                        'addon_id' => $add->addon_id,
                        'order_detail_id' => $order_details->id,
                        'addon_price' => $add->addon_price,
                        'addon_name' => $add->addon_name,
                    ]);
                }
                //end addon detials
            // }
        
        $data['flag'] = 0;
        Order::where('id', $request->order_id)->update($data);
            }
        }
        }
        $customer_id = $this->customer->id;
        $user_type = 5;
        $title = "Rewash request successfully submitted";
        $image = '';
        $body = "Your order " . $order->order_number . " request received. Please wait until we process your request";
        $data = array(
            "orderId" => $order->id,
            "orderNumber" => $order->order_number,
            "orderDate" => date('d-m-Y h:i A', strtotime($order->created_at ?? '')),
            "type" => "Order",
        );
        $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
        return response()->json([
            'status' => 1,
            'message' => "Return request accepted",
        ]);
        Order::where(['customer_id' => $this->customer->id])->delete();
    }

    //particular item listing 
    public function list(Request $request){
        $data=[];
        $data1 =[];
        $ordDetDet = OrderDetailsDetail::where('order_id',$request->order_id)->get();
        foreach($ordDetDet as $ord){
            $category=ServiceCategory::where('id',$ord->order_details->service->service_category_id)->first();
            $addon = OrderAddonDetail::where('order_id',$request->order_id)->get();
            foreach($addon as $add){
                $data1[] = array(
                    'addon_name' => $add->addon_name,
                );
            }
            $data[] = array(
                'order_details_detail_id' => $ord->id,
                'garment_tag_id' => $ord->garment_tag_id,
                'garment_name' => $ord->order_details->service->service_name,
                'category_name' => $category->service_category_name,
                'service_type_name' => $ord->order_details->service_name ,
                'addons' => $data1,
            );
        }
            return response()->json([
                'status' => 1,
                'message' => 'Item Listing.',
                'response' => $data,
            ]);
    }

    public function rewashimageupload(Request $request){
        $OrderDetailsDetail = OrderDetailsDetail::where('order_id', $request->order_id)->where('garment_tag_id', $request->garment_tag_id)->first();
        $images = [];
        $i = 1;
            if($request->file('images')){
                foreach ($request->file('images') as $image) {
                    $imageName = time() . $i . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/rewash'), $imageName);
                    $images[] = $imageName;
                    $image_path[] = asset('uploads/rewash' . $imageName);
                    $i++;
                }
            }
            if($request->garment_tag_id == $OrderDetailsDetail->garment_tag_id){
                $img = implode(",",$images);
            }
            $data['rewash_image'] = $img;
            OrderDetailsDetail::where('garment_tag_id', $request->garment_tag_id)->where('order_id',$request->order_id)->update($data);
            return response()->json([
                'status' => 1,
                'message' => 'Image Uploaded successfully',
            ]);
    }
}
