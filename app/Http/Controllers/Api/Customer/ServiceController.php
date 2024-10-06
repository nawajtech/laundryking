<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\ServiceAddon;
use Auth;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

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
                        $cartitems = CartItem::where('customer_id',$this->customer->id)->where('service_id', $s->id)->sum('quantity');
                         $service_detail_filter = $service_detail->where('service_type_id', $request->service_type_id)->first();
                        // $min =$service_detail->service_price;
                    }
                    $service_detail = $service_detail->where('service_id', $s->id)->get();
                    $service_type = array();
                    $base_price = array();
                    foreach ($service_detail as $a) {
                        $service_type[] = array(
                            'service_type_id' => $a->service_type_id,
                            'service_type_name' => $a->service_type->service_type_name ?? '',
                            'service_price' => number_format($a->service_price, 2),
                        );
                        $base_price[] = $a->service_price;
                        $cartitems = CartItem::where('customer_id',$this->customer->id)->where('service_id', $s->id)->sum('quantity');
                    }
                    //get minimum price 
                    if($base_price!=NULL){
                        $min = min($base_price);
                    }

                    $srv = Service::where('id',$a->service_id)->first();
                    $srv_detl= ServiceDetail::where('service_id',$srv->id)->first();
                    $srv_type= ServiceType::where('id',$srv_detl->service_type_id)->first();
                    if ($service_type) {
                        $service[] = array(
                            'garment_id' => $s->id,
                            'garment_name' => $s->service_name,
                            'image' => asset('assets/img/service-icons/' . $s->icon),
                            'quantity' => (int) $cartitems,
                            'base_price' => number_format($srv_detl->service_price, 2),
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
            $cart = CartItem::where('customer_id',$this->customer->id)->get();

            $cartdetails = CartItem::where('customer_id',$this->customer->id)->get();
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

    public function servicetype()
    {
        $servicetype = ServiceType::where('is_active', '1')->get();
        if ($servicetype) {
            foreach ($servicetype as $s) {
                $data[] = array(
                    'service_type_id' => $s->id,
                    'service_type_name' => $s->service_type_name,
                );
            }
            return response()->json([
                'status' => 1,
                'message' => 'Servicetype Details.',
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Data not found',
            ]);
        }
    }
    
    //selected addons against service
    public function selected_addons(Request $request)
    {
        $service_addon ='';
        $data1=[];
        $srv = Service::where('id',$request->service_id)->first();
        $service_addon = ServiceAddon::where('service_id',$srv->id)->get();
        if ($service_addon) {
            foreach ($service_addon as $addn) {
                $addons = Addon::where('id',$addn->addon_id)->where('is_active', '=', 1)->first();
                $data1[] = array(
                    'addon_id' => $addons->id,
                    'addon_name' => $addons->addon_name,
                    'addon_price' => number_format($addons->addon_price, 2),
                );
            }
            return response()->json([
                'status' => 1,
                'message' => 'Selected Addons',
                'addons' => $data1 ?? '',
            ]);
        }
    }

    public function servicecategory()
    {
        $servicetype = ServiceType::where('is_active', '1')->get();
        if ($servicetype) {
            foreach ($servicetype as $s) {
                $service[] = array(
                    'service_type_id' => $s->id,
                    'service_type_name' => $s->service_type_name,
                );
            }
        }
        $addons = Addon::where('is_active', '=', 1)->get();
        if ($addons) {
            foreach ($addons as $addon) {
                $data1[] = array(
                    'addon_id' => $addon->id,
                    'addon_name' => $addon->addon_name,
                    'addon_price' => number_format($addon->addon_price, 2),
                );
            }
        }
        $brand = Brand::where('is_active', '=', 1)->get();
        if ($brand) {
            foreach ($brand as $b) {
                $data[] = array(
                    'brand_id' => $b->id,
                    'brand_name' => $b->brand_name,
                    'image' => asset('uploads/brand/' . $b->image),
                );
            }
        }
        return response()->json([
            'status' => 1,
            'message' => 'Service_type, Brand and Addon Details.',
            'service_type' => $service ?? '',
            // 'brands' => $data ?? '',
            'addons' => $data1 ?? '',
        ]);
    }

    public function repeatitem(Request $request)
    {
        $data = [];
        $addon_id = '';
        $data1 = [];
        $cart = CartItem::with('service')->orderBy("id", "desc")->where('customer_id',$this->customer->id)->where('service_id', $request->garment_id)->first();
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
            'service_price' => $service_details->service_price ?? 0,
            'service_type_id' => $service_type->id,
            'color' => $cart->color,
            'brand_id' => $cart->brand_id ?? '',
            'addons' => $data1,
        );
        return response()->json([
            'status' => 1,
            'message' => 'Item Details.',
            'response' => $data,
        ]);
    }
}
