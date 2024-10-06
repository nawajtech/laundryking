<?php
namespace App\Http\Controllers\Api\user;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\MasterSettings;
use App\Models\Service;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Payment;
use App\Models\ServiceDetail;
use App\Models\Voucher;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::guard('user')->user();
    }

    public function index(Request $request)
    {
        $cart = CartItem::where('customer_id', $request->customer_id)->get();
        $cartlist = [];
        if (!$cart->isEmpty()) {
            $cartlist = [];
            $subtotal = 0;
            $addon_sum = 0;
            $service_detail = [];
            foreach ($cart as $c) {
                $service_detail = ServiceDetail::where('service_id', $c->service_id)->get();
                $service_type = array();
                foreach ($service_detail as $a) {
                    $service_type[] = array(
                        'service_type_id' => $a->service_type_id,
                        'service_type_name' => $a->service_type->service_type_name,
                        'service_price' => number_format($a->service_price, 2),
                    );
                }
                $addon = array();
                if ($c->addon_id) {
                    $addon_id = explode(',', $c->addon_id);
                    $addon_details = Addon::whereIn('id', $addon_id)->get();
                    if ($addon_details) {
                        foreach ($addon_details as $add) {
                            $addon_sum += $add->addon_price * $c->quantity;
                        }
                    }
                }
                $service = Service::where('id', $c->service_id, )->first();
                $service_details = '';
                $service_details = ServiceDetail::where(['service_id' => $c->service_id, 'service_type_id' => $c->service_type_id])->first();
                if ($service_details) {
                    $subtotal += $service_details->service_price * $c->quantity;
                }
                $Total_price = $subtotal + $addon_sum;
                $brand = Brand::where(['id' => $c->brand_id])->first();
                $image = $brand ? asset('uploads/brand/' . $brand->image) : '';
                $cartlist['cartitem'][] = array(
                    'id' => $c->id,
                    'quantity' => $c->quantity,
                    'color' => $c->color ?? '',
                    'brand_id' => $c->brand_id ?? '',
                    'brand_name' => $brand->brand_name ?? '',
                    'garment_id' => $c->service_id ?? '',
                    'garment_name' => $c->service->service_name ?? '',
                    'service_type_id' => $c->service_type_id,
                    'service_type_name' => $c->service_type->service_type_name,
                    'service_price' => number_format($service_details->service_price ?? 0, 2),
                    'image' => asset('assets/img/service-icons/' . $service->icon),
                    'addons' => $c->addon_id ??'',
                    'service_type' => $service_type,
                );
            }
            $tax = MasterSettings::where('master_title', 'default_tax_percentage')->where('is_active', 1)->first();
            $tax_percentage = $tax->master_value;
            $tax_price = $tax_percentage * $Total_price / 100;
            $cartlist['Payment'] = array(
                'Sub_total' => number_format($subtotal, 2),
                'Addon_total' => number_format($addon_sum, 2),
                'Tax' => number_format($tax_price, 2),
                'Total_price' => number_format($subtotal + $addon_sum + $tax_price, 2),
            );
            return response()->json([
                'status' => 1,
                'message' => 'Cart Item Details.',
                'response' => $cartlist,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Item found',
            ]);
        }
    }

    //Total cartitem calculation
    public function calculation(Request $request)
    {
        $delivered_order = Order::where('customer_id', $request->customer_id)->where('status', 9)->get();
        $outstanding_amount = 0;
        foreach ($delivered_order as $u) {            
            $payment_done = Payment::where('order_id', $u->id)->sum('received_amount');
            $outstanding_amount = $u->total -  $payment_done?? 0; 
        }
        if($request->outstanding && $request->outstanding != (string)$outstanding_amount){
            $msg = "Please reload cart page";
        }else{
            $msg = "";
        }

        $cart = CartItem::where('customer_id', $request->customer_id)->get();
        $voucher = Voucher::where('id', $request->voucher_id)->first();
        $delivery_type = DeliveryType::where('id', $request->delivery_id)->first();
        $cartlist = [];
        if (!$cart->isEmpty()) {
            $cartlist = [];
            $subtotal = 0;
            $addon_sum = 0;
            $delivery_charge = 0;

            foreach ($cart as $c) {
                $addon = array();
                if ($c->addon_id) {
                    $addon_id = explode(',', $c->addon_id);
                    $addon_details = Addon::whereIn('id', $addon_id)->get();
                    if ($addon_details) {
                        $sum = 0;
                        foreach ($addon_details as $add) {
                            $sum += $add->addon_price * $c->quantity;
                            $addon['addon'][] = array(
                                'addon_id' => $add->id,
                                'addon_name' => $add->addon_name,
                                'addon_price' => number_format($add->addon_price, 2),
                            );
                        }
                        $addon['addon_sum'] = array(
                            'sum' => number_format($sum, 2),
                        );
                        $addon_sum += $sum;
                    }
                }
                $service_details = ServiceDetail::where(['service_id' => $c->service_id, 'service_type_id' => $c->service_type_id])->first();
                $subtotal += $service_details->service_price * $c->quantity;
            }
            $item_charge = $subtotal + $addon_sum;
            //delivery calculation
            $customer = Customer::where('id', $request->customer_id)->first();
            $membership_id = $customer->membership;
            $memberships = Membership::where('id',$membership_id)->first();
            $exp_chrge = $item_charge * $delivery_type['amount'] / 100;
            if ($request->delivery_id == 3 && $membership_id) {
                $exp_disc_percentage = $exp_chrge * $memberships->express_fee /100;
                $express_charge = $exp_chrge - $exp_disc_percentage;
            }elseif($request->delivery_id){
                $express_charge = $delivery_type->type == 'Flat' ? $delivery_type['amount'] : $item_charge * $delivery_type['amount'] / 100 ;
                if ($delivery_type['cut_off_amount'] <= $item_charge) {
                    $delivery_charge = 0;
                } else {
                    $delivery_charge = $delivery_type->cut_off_charge;
                }
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Please select delivery type',
                ]);
            }
            $Total_price = $subtotal + $addon_sum + $express_charge;
            //membership discount 
            $customer = Customer::where('id', $request->customer_id)->first();
            $membership_id = $customer->membership;
            $cashback_amount = 0;
            $special_discount = 0;
            if ($membership_id != '') {
            $membership = Membership::where('id',$membership_id)->first();
            if($membership->discount > 0 && $membership->discount_type ==1){
                $special_discount = $membership->discount * $Total_price / 100;
            } else{
                $cashback_amount = $membership->discount * $Total_price / 100;
            }}
            $amount_after_discount = $Total_price - $special_discount;
            //voucher calculation
            if ($request->voucher_id) {
                $discount_amount = 0;
                if ($voucher->cutoff_amount <= $amount_after_discount) {
                    $discount_type = $voucher['discount_type'];
                    $discount_amount = $discount_type == 1 ? $amount_after_discount * $voucher['discount_amount'] / 100 : $voucher['discount_amount'];
                }}else {
                    $discount_amount =0;
                }
            $neet_total_price = $amount_after_discount - $discount_amount;
            
            $after_delivery_charge = $neet_total_price + $delivery_charge;
            $ord_total = Order::where('customer_id', $request->customer_id)->sum('total');
            $payment_done = Payment::where('customer_id', $request->customer_id)->sum('received_amount');
            $wallet_rcv = Wallet::where('customer_id', $request->customer_id)->sum('receive_amount');
            $wallet_dedct = Wallet::where('customer_id', $request->customer_id)->sum('deducted_amount');
            $wallet_amount = $wallet_rcv - $wallet_dedct;
            //tax calculation
            $tax = MasterSettings::where('master_title', 'default_tax_percentage')->where('is_active', 1)->first();
            $tax_percentage = $tax->master_value;
            $tax_price = $after_delivery_charge * $tax_percentage / 100;
            if($request->outstanding){
                $outstnd = $request->outstanding;
            }else{
                $outstnd=0;
            }
            $reedem_amnt = MasterSettings::where('master_title', 'reedem_amount')->where('is_active', 1)->first();
                $redem_amount = $reedem_amnt->master_value;
            if($request->credit > $redem_amount){
                $wallt=$request->credit;
            }else{
                $wallt=0;
            }
            $cartlist['Total_Payment'] = array(
                'Delivery_type' => $delivery_type->delivery_name ?? '',
                'Sub_total' => number_format($subtotal, 2),
                'Addon_total' => number_format($addon_sum, 2),
                'Express_charge' => number_format($express_charge , 2),
                'Total_without_discount' => number_format($Total_price, 2),
                'Discount' => number_format($special_discount, 2),
                'Cashback' => number_format($cashback_amount, 2),
                'Cut_of_amount' => number_format($amount_after_discount , 2),
                'Voucher_discount' => number_format($discount_amount, 2 ?? 0),
                'Delivery_charge' => number_format($delivery_charge, 2 ?? 0),
                'Tax percentage'=>$tax_percentage,
                'Tax' => number_format($tax_price, 2),
                'Total_price' => number_format($neet_total_price + $tax_price + $delivery_charge+$outstnd-$wallt, 2 ?? 0),
                'Lk min reedem amount' => $redem_amount,
                'Outstanding amount' => number_format($outstanding_amount, 2), 
                'Wallet Amount' => number_format($wallet_amount, 2), 
            );
            return response()->json([
                'status' => 1,
                'message' => 'Total Calculation',
                'response' => $cartlist,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Please Add An Item',
            ]);
        }
    }

    //insert cart items
    public function insert(Request $request)
    {
        // brand dependency
        // if ($request->brand_id) {
        //     $cart = CartItem::where(['customer_id' => $request->customer_id, 'service_id' => $request->garment_id, 'service_type_id' => $request->service_type_id, 'brand_id' => $request->brand_id, 'color' => $request->color])
        //         ->whereIn('addon_id', [array($request->addon)])->first();
        // } else {
            if($request->addon){
                $cart = CartItem::where(['customer_id' => $request->customer_id, 'service_id' => $request->garment_id, 'service_type_id' => $request->service_type_id,])
                    ->whereIn('addon_id', [array($request->addon)])->first();
            }else{
                $cart = CartItem::where(['customer_id' => $request->customer_id, 'service_id' => $request->garment_id, 'service_type_id' => $request->service_type_id,])->first();
            }
        // }
        $rules = [
            'quantity' => 'required',
            'garment_id' => 'required',
            'service_type_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        }if ($cart) {
            $data['quantity'] = $cart->quantity + $request->quantity;
            CartItem::where('id', $cart->id)->update($data);
            return response()->json([
                'status' => 1,
                'message' => "Item Updated successfully",
            ]);
        } else {
            $cartitem = CartItem::create([
                'addon_id' => $request->addon,
                'quantity' => $request->quantity,
                'color' => $request->color,
                'brand_id' => $request->brand_id ?? 0,
                'customer_id' => $request->customer_id,
                'service_id' => $request->garment_id,
                'service_type_id' => $request->service_type_id,
            ]);
            return response()->json([
                'status' => 1,
                'message' => "Item added successfully",
            ]);
        }
    }
    
    //cart item edit
    public function edit(Request $request, $service_id)
    {
        $cart = CartItem::where(['customer_id' => $request->customer_id, 'service_id' => $service_id])->first();
        return response()->json([
            'status' => 1,
            'data' => $cart,
        ]);
    }

    //cart item update
    public function update(Request $request)
    {
        if($request->addon){
            $cart = CartItem::where(['customer_id' => $request->customer_id, 'service_id' => $request->garment_id, 'service_type_id' => $request->service_type_id,])
                ->whereIn('addon_id', [array($request->addon)])->first();
        }else{
            $cart = CartItem::where(['customer_id' => $request->customer_id, 'service_id' => $request->garment_id, 'service_type_id' => $request->service_type_id,])->where('addon_id', '')->first();
        }
        $rules = [
            'quantity' => 'required',
            'id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        }
        if ($cart) {
            $data['quantity'] = $request->quantity + $cart['quantity'];
            CartItem::where('id', $cart->id)->update($data);
            $requestitemquantity = CartItem::where('id', $request->id)->first();
            $dataitem['quantity'] = $requestitemquantity['quantity'] - $request->quantity;
            $a = CartItem::where('id', $request->id)->update($dataitem);
            if ($dataitem['quantity'] <= 0) {
                $requestitemquantity->delete();
            }
            return response()->json([
                'status' => 1,
                'message' => ' updated successfully.',
            ]);
        } else {
            $cartitem = CartItem::where('id', $request->id)->count();
            if ($cartitem > 0) {
                $data['quantity'] = $request->quantity;
                $data['color'] = $request->color;
                $data['brand_id'] = $request->brand_id;
                $data['addon_id'] = $request->addon;
                $data['service_type_id'] = $request->service_type_id;
                CartItem::where('id', $request->id)->update($data);
                return response()->json([
                    'status' => 1,
                    'message' => 'updated successfully.',
                ]);
            }
        }
    }

    // cart delete item
    public function delete(Request $request)
    {
        $cart = CartItem::where('id', $request->id)->first();
        if ($cart) {
            CartItem::where('id', $cart->id)->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Deleted Successfully',
            ]);} else {
            return response()->json([
                'status' => 0,
                'message' => 'No data found',
            ]);
        }
    }

    //remove item
    public function removeitem(Request $request)
    {
        $cartitem = CartItem::where('id', $request->id)->first();
        $quantity = 0;
        if ($cartitem) {
            $quantity = $cartitem->quantity - 1;
        }
        if ($quantity < 1) {
            CartItem::where('id', $request->id)->delete();
        } else {
            $data['quantity'] = $quantity;
            CartItem::where('id', $cartitem->id)->update($data);
        }
        return response()->json([
            'status' => 1,
            'message' => 'One Item Remove',
        ]);

    }

    // add item
    public function additem(Request $request)
    {
        $cart = CartItem::where('id', $request->id)->first();
        if ($cart) {
            $data['quantity'] = $cart->quantity + 1;
            CartItem::where('id', $cart->id)->update($data);
        }
        return response()->json([
            'status' => 1,
            'message' => 'One Item Added Successfully',
        ]);

    }
}
