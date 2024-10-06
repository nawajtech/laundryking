<?php
namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use Auth;
use Illuminate\Http\Request;

class GarmentController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::guard('user')->user();
    }

    //Garment listing 
    public function index(Request $request)
    {
        $sub_total = 0;
        $cartitems = 0;
        $total_quantity = 0;
        $category = ServiceCategory::with('service')->where('is_active', '=', 1)->get();
        if ($category) {
            foreach ($category as $c) {
                $service = array();
                foreach ($c->service as $s) {
                    $service_detail = ServiceDetail::with('service_type');
                    if ($request->service_type_id) {
                        $service_detail = $service_detail->where('service_type_id', $request->service_type_id);
                        $cartitems = CartItem::where('customer_id',$request->customer_id)->where('service_id', $s->id)->sum('quantity');
                         $service_detail_filter = $service_detail->where('service_type_id', $request->service_type_id)->first();
                        // $min =$service_detail->service_price;
                    }
                    $service_detail = $service_detail->where('service_id', $s->id)->get();
                    $service_type = array();
                    $base_price = array();
                    foreach ($service_detail as $a) {
                        $service_type[] = array(
                            'service_type_id' => $a->service_type_id,
                            'service_type_name' => $a->service_type->service_type_name,
                            'service_price' => number_format($a->service_price, 2),
                        );
                        $base_price[] = $a->service_price;
                        $cartitems = CartItem::where('customer_id',$request->customer_id)->where('service_id', $s->id)->sum('quantity');
                    }
                    $srv = Service::where('id',$a->service_id)->first();
                    $srv_detl= ServiceDetail::where('service_id',$srv->id)->first();
                    $srv_type= ServiceType::where('id',$srv_detl->service_type_id)->first();
                    //get minimum price 
                    if($base_price!=NULL){
                        $min = min($base_price);
                    }
                    if ($service_type) {
                        $service[] = array(
                            'garment_id' => $s->id,
                            'garment_name' => $s->service_name,
                            'image' => asset('assets/img/service-icons/' . $s->icon),
                            'quantity' => (int) $cartitems,
                            'base_price' => number_format($min, 2),
                            'service_name'=>$srv_type->service_type_name,
                            'information' => $s->information ?? '',
                            'service_type' => $service_type,
                        );
                    }
                    $total_quantity += $cartitems;
                }
                  $data['category'][] = array(
                    'category_id' => $c->id,
                    'category_name' => $c->service_category_name,
                    'garments' => $service,
                );
            }
            $total_amount = 0;
            $quantity = 0;
            $addon_sum = 0;
            $cart = CartItem::where('customer_id',$request->customer_id)->get();

            $cartdetails = CartItem::where('customer_id',$request->customer_id)->get();
            foreach ($cartdetails as $cart) {
                $quantity = $cart->quantity;
                $addon = array();
                if ($cart->addon_id) {
                    $addon_id = explode(',', $cart->addon_id);
                    $addon_details = Addon::whereIn('id', $addon_id)->get();
                    if ($addon_details) {
                        foreach ($addon_details as $add) {
                            $addon_sum += $add->addon_price * $cart->quantity;
                        }
                    }
                }
                $service_detail_customer = ServiceDetail::with('service_type')->where('service_id', $cart->service_id)->where('service_type_id', $cart->service_type_id)->get();
                foreach ($service_detail_customer as $b) {
                    $amount = $b->service_price;
                    $total_amount += $quantity * $amount;
                }
            }
            $sub_total = $total_amount + $addon_sum;
            $data['Payment'] = array(
                'Sub_total' => (string) $sub_total,
                'total_quantity' => $total_quantity,
            );
            return response()->json([
                'status' => 1,
                'message' => 'Category Details.',
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Data not found',
            ]);
        }
    }

    //Repeat last items
    public function repeatitem(Request $request)
    {
        $data = [];
        $data1 = [];
        $addon_id = '';
        $cart = CartItem::with('service')->orderBy("id", "desc")->where('customer_id',$request->customer_id)->where('service_id', $request->garment_id)->first();
        if($cart){
        $service_type = ServiceType::where('id', $cart->service_type_id)->first();
        $service_details = ServiceDetail::where(['service_id' => $cart->service_id, 'service_type_id' => $cart->service_type_id])->first();
        $addon_id = explode(',', $cart->addon_id);
        foreach ($addon_id as $a) {
            $addons = Addon::where('id', $a)->first();
            if ($addons) {
                $data1[] = array(
                    'addon_id' => $addons->id,
                    'addon_name' => $addons->addon_name,
                    'addon_price' => $addons->addon_price,
                );
            }
        }
        $data[] = array(
            'garment_id' => $cart->service->id,
            'garment_name' => $cart->service->service_name,
            'image' => asset('assets/img/service-icons/' . $cart->service->icon),
            'service_type_name' => $service_type->service_type_name,
            'service_price' => number_format($service_details->service_price ,2) ?? 0,
            'service_type_id' => $service_type->id,
            'color' => $cart->color ?? '',
            'brand_id' => $cart->brand_id ?? '',
            'addons' => $data1,
        );
        return response()->json([
            'status' => 1,
            'message' => 'Item Details.',
            'response' => $data,
        ]);
        }  else {
        return response()->json([
            'status' => 0,
            'message' => 'Please add cart item first.',
        ]);
        }
    }
    
}