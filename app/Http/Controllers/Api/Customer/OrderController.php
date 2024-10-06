<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\MasterSettings;
use App\Models\Order;
use App\Models\Membership;
use App\Models\OrderAddonDetail;
use App\Models\OrderDetails;
use App\Models\Outlet;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Pincode;
use App\Models\Service;
use App\Models\OrderDetailsDetail;
use App\Http\Helper\CommonHelper;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\Voucher;
use Auth;
use URL;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

    //order listing for customer
    public function index()
    {
        $data = [];
        $delivery = [];
        $order = Order::with('delivery_driver','pickup_driver')->where('customer_id', $this->customer->id)->Orderby('id', 'desc')->get();
        if (!$order->isEmpty()) {
            foreach ($order as $u) {
                $delivery = DeliveryType::where('delivery_name', $u->delivery_type)->first();
                $payment_details = Payment::where('order_id', $u->id)->first();
                $payment_mode = '';
                if ($payment_details && $payment_details->payment_type == 1) {
                    $payment_mode = "Cash";
                } elseif ($payment_details && $payment_details->payment_type == 2) {
                    $payment_mode = "Upi";
                } elseif ($payment_details && $payment_details->payment_type == 3) {
                    $payment_mode = "Card";
                } elseif ($payment_details && $payment_details->payment_type == 4) {
                    $payment_mode = "Cheque";
                } elseif ($payment_details && $payment_details->payment_type == 5) {
                    $payment_mode = "Bank Transfer";
                } elseif ($payment_details && $payment_details->payment_type == 6) {
                    $payment_mode = "Lk Credit";
                }
                elseif ($payment_details && $payment_details->payment_type == 7) {
                    $payment_mode = "Cash on Delivery";
                }
                elseif ($payment_details && $payment_details->payment_type == 7) {
                    $payment_mode = "Razor Pay";
                }
                $due_amount = '';
                if ($payment_details) {
                    $due_amount = number_format($u->total - $payment_details->received_amount, 2);
                }
                if ($delivery) {
                    $delivery_time = date('h:i', strtotime($delivery->delivery_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery->delivery_time_to)) . 'pm';
                    $pickup_time = date('h:i', strtotime($delivery->pickup_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery->pickup_time_to)) . 'pm';
                }
                //order status check
                $status = getOrderStatus($u->status);
                if ($status == "Delivered") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
                        'Out for pickup'=>1,
                        'Pickup' => 1,
                        'Processing' => 1,
                        'Out for Delivery' => 1,
                        'Delivered' => 1,
                        'Cancel' => 0,
                    );
                } elseif ($status =="Out for Delivery") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
                        'Out for pickup'=>1,
                        'Pickup' => 1,
                        'Processing' => 1,
                        'Out for Delivery' => 1,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Processing"||$status =="To be Processed"||$status =="In Transit"||$status == "Sent to Store"||$status =="Ready") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
                        'Out for pickup'=>1,
                        'Pickup' => 1,
                        'Processing' => 1,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Picked Up") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
                        'Out for pickup'=>1,
                        'Pickup' => 1,
                        'Processing' => 0,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Out for pickup") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
                        'Out for pickup'=>1,
                        'Pickup' => 0,
                        'Processing' => 0,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Confirm") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
                        'Out for pickup'=>0,
                        'Pickup' => 0,
                        'Processing' => 0,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Pending") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 0,
                        'Out for pickup'=>0,
                        'Pickup' => 0,
                        'Processing' => 0,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } else {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 0,
                        'Out for pickup'=>0,
                        'Pickup' => 0,
                        'Processing' => 0,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 1,
                    );
                }
                $phone = '';
                if($u->pickup_driver) {
                    $phone = $u->pickup_driver->phone;
                } else if($u->delivery_driver) {
                    $phone = $u->delivery_driver->phone;
                }
                $master_setting = MasterSettings::where('master_title', 'default_phone_number')->where('is_active', 1)->first();
                $default_number = $master_setting->master_value;
                $order_details = OrderDetails::where('order_id',$u->id)->first();
                $order_details_detail='';
                $order_details_detail= OrderDetailsDetail::where('order_id', $u->id)->where('is_active', 1)->where('accepted', 0)->first();
                if($order_details_detail){
                    $defected_status=1;
                }else{
                    $defected_status=0;
                }

                $total_defected_quantity = OrderDetailsDetail::with('order_details')->whereHas('order_details', function($q) {
                    $q->with('order')->whereHas('order', function($q) {
                        $q->where('customer_id', $this->customer->id);
                    });
                })->where('is_active', 1)->where('accepted', 0)->whereNotIn("status", [9,10])->count();
                if($u->id!=NULL){
                $order_dt_dts= OrderDetailsDetail::where('order_id', $u->id)->first();
                }

                $data[] = array(
                    'id' => $u->id,
                    'order_detail_id' => $order_details->id ?? 0,
                    'order_number' => '#'.$u->order_number,
                    // 'customer_name' => $u->customer_name,
                    // 'phone_number' => $u->phone_number,
                    'order_date' => date('Y-m-d h:i:s', strtotime($u->created_at ?? '')),
                    // 'pickup_date' => $u->order_date ?? '',
                    // 'delivery_date' => $u->delivery_date ?? '',
                    // 'delivery_time_between' => $delivery_time ?? '',
                    // 'pickup_time_between' => $pickup_time ?? '',
                    'pickup_address' => $u->pickup_address ?? 'Store pickup',
                    // 'pickedup_time' => $u->pickup_time ?? '',
                    // 'delivered_time' => $u->delivery_time ?? '',
                    'delivery_agent_number' => $phone,
                    'defected_quantity' => $order_details->defected_quantity ?? 0,
                    'support_phone_number' => $default_number,
                    // 'sub_total' => number_format($u->sub_total, 2),
                    // 'addon_total' => number_format($u->addon_total, 2),
                    // 'delivery_charge' => number_format($u->delivery_charge, 2),
                    // 'discount' => number_format($u->discount, 2),
                    // 'tax_percantage' => $u->tax_percentage,
                    // 'tax_amount' => number_format($u->tax_amount, 2),
                    // 'payment_type' => $payment_mode,
                    'status_defected'=>$defected_status ?? 0,
                    'total' => number_format($u->total, 2),
                    'outstanding_amount' => $due_amount,
                    'rewash_status' => $order_dt_dts -> rewash_confirm ?? 0,
                    'status_track' => $check,
                );
            }
            return response()->json([
                'status' => 1,
                'message' => 'Orders List.',
                'response' => $data,
                'total_defected_quantity' => (int)$total_defected_quantity,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Data Found.',
            ]);
        }
    }

    //order details for customer
    public function orders(Request $request)
    {
        $order = Order::where(['customer_id' => $this->customer->id, 'id' => $request->id])->first();
        //rewash time check
        if($order){
            $rewash_date = MasterSettings::where('master_title', 'rewash_time')->where('is_active',1)->first();
            $master_value= $rewash_date->master_value;
            $integer_master_value=(int)$master_value;
            $currentDateTime = date("Y-m-d HH:mi:ss", strtotime(Carbon::now()));
            $delivered_date=$order->delivered_date;
            $expiry_date = date("Y-m-d HH:mi:ss", strtotime("$delivered_date +$integer_master_value days"));
            if($order->status ==9 && $order->children->isEmpty() && ($expiry_date>$currentDateTime)){
                $datas['flag'] = 1;
                Order::where('id', $order->id)->update($datas);
            }
            elseif($order->status ==9 && ($expiry_date<$currentDateTime)){
                $datas['flag'] = 0;
                Order::where('id', $order->id)->update($datas);
            }
            if($order->status ==9){
                $delivery_date = $order->delivered_date;
            }else{
                $delivery_date = $order->delivery_date;
            }
            $data = [];
            $item = [];
            $delivery = '';
            if ($order) {
                $order_details = OrderDetailsDetail::where('order_id', $request->id)->get();
                foreach ($order_details as $u) {
                    $delivery = DeliveryType::where('delivery_name', $order->delivery_type)->first();
                    // if ($delivery) {
                    $delivery_time = date('h:i', strtotime($delivery->delivery_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery->delivery_time_to)) . 'pm';
                    $pickup_time = date('h:i', strtotime($delivery->pickup_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery->pickup_time_to)) . 'pm';
                    // }
                    $services = Service::where('id', $u->order_details->service_id)->first();
                    $service_category = ServiceCategory::where('id', $services->service_category_id)->first();

                    //Get Order Status
                    // $status = getOrderStatus($order->status);
                    $addon_details = OrderAddonDetail::where(['order_id' => $u->order_id, 'order_detail_id' => $u->order_details->id])->get();
                    $addon = [];
                    if ($addon_details) {
                        foreach ($addon_details as $add) {
                            $addon[] = array(
                                'id' => $add->addon_id,
                                'addon_name' => $add->addon_name,
                                'addon_price' => $add->addon_price,
                            );
                        }
                        $item[] = array(
                            'service_types' => $u->order_details->service_name,
                            'garment_id' => $services->id,
                            'garment_name' => $services->service_name,
                            'service_category' => $service_category->service_category_name,
                            'image' => asset('assets/img/service-icons/' . $services->icon),
                            'service_price' => number_format($u->order_details->service_price ?? 0, 2),
                            'service_quantity' => $u->order_details->service_quantity,
                            'service_detail_total' => number_format($u->order_details->service_detail_total, 2),
                            'color_code' => $u->order_details->color_code ?? '',
                            'rewash_status'=>$u->rewash_confirm,
                            'addon' => $addon,
                        );
                    }
                }
                $phone = '';
                if($order->pickup_driver) {
                    $phone = $order->pickup_driver->phone;
                } else if($order->delivery_driver) {
                    $phone = $order->delivery_driver->phone;
                }
                $master_setting = MasterSettings::where('master_title', 'default_phone_number')->where('is_active', 1)->first();
                $default_number = $master_setting->master_value;
                $data['order details'] = array(
                    'id' => $order->id,
                    'order_number' => '#'.$order->order_number,
                    'customer_name' => $order->customer_name,
                    'phone_number' => $order->phone_number,
                    'order_date' =>date('Y-m-d', strtotime($order->order_date)) ??'',
                    'delivery_date' =>date('Y-m-d', strtotime($order->delivery_date)) ??'',
                    'delivery_time' => $delivery_time ?? '',
                    'pickup_time' => $pickup_time ?? '',
                    'pickedup_time' => $order->pickup_time ?? '',
                    'delivered_time' => $order->delivery_time?? '',
                    'pickup_address' => $order->pickup_address ?? 'Store pickup',
                    'delivery_address' => $order->delivery_address ?? 'Store Delivery',
                    'delivery_agent_number' => $phone,
                    'note' => $order->note ?? '',
                    'status' => getOrderStatus($order->status),
                    'flag' => $order->flag,
                    'items' => $item,
                );
                $tax = MasterSettings::where('master_title', 'default_tax_percentage')->where('is_active', 1)->first();
                $tax_percentage = $tax->master_value;
                $payment_details = Payment::where('order_id', $order->id)->first();
                $payment_mode = '';
                if ($payment_details && $payment_details->payment_type == 1) {
                    $payment_mode = "Cash";
                } elseif ($payment_details && $payment_details->payment_type == 2) {
                    $payment_mode = "Upi";
                } elseif ($payment_details && $payment_details->payment_type == 3) {
                    $payment_mode = "Card";
                } elseif ($payment_details && $payment_details->payment_type == 4) {
                    $payment_mode = "Cheque";
                } elseif ($payment_details && $payment_details->payment_type == 5) {
                    $payment_mode = "Bank Transfer";
                } elseif ($payment_details && $payment_details->payment_type == 6) {
                    $payment_mode = "Lk Credit";
                }
                elseif ($payment_details && $payment_details->payment_type == 7) {
                    $payment_mode = "Cash on Delivery";
                }
                elseif ($payment_details && $payment_details->payment_type == 8) {
                    $payment_mode = "Razor Pay";
                }

                $paidbylkcredit = Wallet::where('order_id',$order->id)->sum('deducted_amount');
                $data['Payment'] = array(
                    'sub_total' => number_format($order->sub_total, 2),
                    'addon_total' => number_format($order->addon_total, 2),
                    'discount' => number_format($order->discount, 2),
                    'express_charge' => number_format($order->express_charge, 2),
                    'delivery_charge' => number_format($order->delivery_charge, 2),
                    'voucher_discount' => number_format($order->voucher_discount, 2),
                    'Tax percentage'=>$tax_percentage,
                    'tax_amount' => number_format($order->tax_amount, 2),
                    'total_amount' => number_format($order->total, 2),
                    'payment_type' => $payment_mode,
                    'Lk_credit_payment' => number_format($paidbylkcredit,2)?? '',
                    'Cashback' => number_format($order->cashback_amount ,2)?? '',
                );
                return response()->json([
                    'status' => 1,
                    'message' => 'Order Details.',
                    'response' => $data,
                ]);
            } }else {
            return response()->json([
                'status' => 0,
                'message' => 'No Data Found.',
            ]);
        }
    }

    /* generate order Id */
    public function generateOrderID()
    {
        $order_prefix = MasterSettings::where('master_title', 'order_prefix')->where('is_active', 1)->first();
        $prefix_value=  $order_prefix->master_value .'-';
        $code_prefix = $prefix_value;
        $ordernumber = Order::whereNull('parent_id')->Orderby('id', 'desc')->first();
        if ($ordernumber && $ordernumber->order_number != "") {
            $code = explode("-", $ordernumber->order_number);
            $new_code = $code[1] + 1;
            $new_code = str_pad($new_code, 4, "0", STR_PAD_LEFT);
            return $code_prefix . $new_code;
        }else {
            return $code_prefix . '0001';
        }
    }

    //insert order for customer
    public function insert(Request $request)
    {
        $addon_sum = 0;
        $subtotal = 0;
        $discount_amount = 0;
        $special_discount = 0;
        $delivery_charge = 0;
        $service_price = 0;
        $customer = Auth::user();
        $cart = CartItem::where('customer_id', $this->customer->id)->get();
        $voucher = Voucher::where('id', $request->voucher_id)->first();
        $cutoff_amount = 0;
        $delivery_type = DeliveryType::where('id', $request->delivery_type_id)->first();
        if(!$delivery_type){
            return response()->json([
                'status' => 0,
                'message' => "Please Select Delivery Type",
            ]);
        }

        if (!$cart->isEmpty()) {

            //Validate Pickup Address
            $pickup_address = Address::where('id', $request->pickup_address_id)->first();
            if ($pickup_address) {
                $pincode = Pincode::where('pincode', $pickup_address->pincode)->first();
                if ($pincode) {
                    $pickup_outlets = Outlet::where('id', $pincode->outlet_id)->first();
                } else {
                    return response()->json([
                        'status' => 0,
                        'message' => "Pickup not available in your area",
                    ]);
                }
            }

            //Validate Delivery Address
            $delivery_address = Address::where('id', $request->delivery_address_id)->first();
            if ($delivery_address) {
                $pincode = Pincode::where('pincode', $delivery_address->pincode)->first();
                if ($pincode) {
                    $delivery_outlets = Outlet::where('id', $pincode->outlet_id)->first();
                } else {
                    return response()->json([
                        'status' => 0,
                        'message' => "Delivery not available in your area",
                    ]);
                }
            }
            $reedem = MasterSettings::where('master_title', 'default_tax_percentage')->where('is_active', 1)->first();
                $reedem_amount = $reedem->reedem_amount;
                if($reedem_amount > $request->wallet_amount){
                    return response()->json([
                        'status' => 0,
                        'message' => "Min Reedem amount is".$reedem_amount,
                    ]);
                }
            
            $pamnt_rcv = Wallet::where('customer_id',$this->customer->id)->sum('receive_amount');
            $pamnt_ddct = Wallet::where('customer_id',$this->customer->id)->sum('deducted_amount');
            $pamnt = $pamnt_rcv - $pamnt_ddct;
            if($pamnt < $request->wallet_amount){
                return response()->json([
                    'status' => 0,
                    'message' => "You have insufficient balance",
                ]);
            }

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
                                'addon_price' => $add->addon_price,
                            );
                        }
                        $addon['addon_sum'] = array(
                            'sum' => $sum,
                        );
                        $addon_sum += $sum;
                    }
                }
                $service_details = ServiceDetail::where(['service_id' => $c->service_id, 'service_type_id' => $c->service_type_id])->first();
                $service_price = $service_details->service_price ?? 0;
                $subtotal += $service_price * $c->quantity;
                $item_charge = $subtotal + $addon_sum;
                //delivery calculation
                $customer = Customer::where('id', $this->customer->id)->first();
                $membership_ids = $customer->membership;
                $memberships = Membership::where('id',$membership_ids)->first();
                $exp_chrge = $item_charge * $delivery_type['amount'] / 100;
                if ($request->delivery_type_id == 3 && $membership_ids) {
                    $exp_disc_percentage = $exp_chrge * $memberships->express_fee /100;
                    $express_charge = $exp_chrge - $exp_disc_percentage;
                }elseif($request->delivery_type_id){ 
                    $express_charge = $delivery_type->type == 'Flat' ? $delivery_type['amount'] : $item_charge * $delivery_type['amount'] / 100 ;
                    $item_charge = $subtotal + $addon_sum;
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
                $discount_percentage = '';
                //membership discount
                $customer = Customer::where('id', $this->customer->id)->first();
                $membership_id = $customer->membership;
                $cashback_amount = 0;
                if ($membership_id != '') {
                $membership = Membership::where('id',$membership_id)->first();
                if($membership->discount > 0 && $membership->discount_type ==1){
                    $special_discount = $membership->discount * $Total_price / 100;
                } else{
                    $cashback_amount = $membership->discount * $Total_price / 100;
                }
                } 
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
                //tax calculation
                $tax = MasterSettings::where('master_title', 'default_tax_percentage')->where('is_active', 1)->first();
                $tax_percentage = $tax->master_value;
                $tax_price = $after_delivery_charge * $tax_percentage / 100;
                $financial_year = MasterSettings::where('master_title', 'default_financial_year')->where('is_active', 1)->first();
            }
            $total_amnt = $neet_total_price + $tax_price + $delivery_charge;
          
            // outstanding amount payment
            $data = [];
            $financial_year = MasterSettings::where('master_title', 'default_financial_year')->where('is_active', 1)->first();
            $orderss = Order::with('payment')->where('customer_id', $this->customer->id)->get();
            if ($orderss && $request->outstanding_amount > 0) {
                foreach ($orderss as $u) { 
                    if(($u->total - ($u->payment->received_amount ??0)) > 0 && $request->outstanding_amount > 0){
                        $data[] = array(
                        'receive_amount' => number_format(($u->payment->received_amount ??0) ,2),
                        'total_amount'=> number_format($u->total ,2),
                        'order_id' => $u->id,
                    ); 
                    Payment::create([
                        'payment_date' => \Carbon\Carbon::today()->toDateString(),
                        'customer_id' => $this->customer->id ?? null,
                        'customer_name' => $u->customer_name ?? null,
                        'order_id' => $u->id,
                        'payment_type' => $request->payment_type,
                        'payment_note' => $request->note,
                        'financial_year_id' => $financial_year->id,
                        'received_amount' =>$u->total - ($u->payment->received_amount ??0),
                        'created_by' => Auth::user()->id,
                    ]);
                }   
            }}
                        
            //generate Order ID
            if($this->generateOrderID() ){
                $gen_ord = $this->generateOrderID();
                // return response()->json($gen_ord);
            }

            //Place Order
            $order = Order::create([
                'order_number' => $gen_ord,
                'outlet_id' => $pickup_outlets->id,
                'workstation_id' => $pickup_outlets->workstation_id,
                'delivery_outlet_id' => $delivery_outlets->id,
                'customer_id' => $customer->id,
                'pickup_option' => 2,
                'delivery_option' => 2,
                'delivery_type_id' => $delivery_type->id,
                'pickup_address_id' => $pickup_address->id,
                'delivery_address_id' => $delivery_address->id,
                'customer_name' => $customer->name,
                'phone_number' => $customer->phone,
                'order_date' => $request->pickup_date,
                'delivery_date' => $request->delivery_date,
                'sub_total' => $subtotal,
                'addon_total' => $addon_sum,
                'express_charge' => $express_charge,
                'discount' => $special_discount,
                'voucher_discount' => $discount_amount,
                'delivery_charge' => $delivery_charge,
                'tax_percentage' => $tax_percentage,
                'tax_amount' => $tax_price,
                'total' => $neet_total_price + $tax_price + $delivery_charge,
                'note' => $request->note,
                'instruction' => $request->instruction,
                'status' => 0,
                'order_type' => 1,
                'delivery_type' => $delivery_type->delivery_name,
                'created_by' => 0,
                'financial_year_id' => $financial_year->id,
                'voucher_id' => $voucher->id ?? 0,
                'voucher_code' => $voucher->code ?? 0,
                'pickup_flat_number' => $pickup_address->flat_number ?? null,
                'pickup_area' => $pickup_address->area ?? null,
                'pickup_address' => $pickup_address->address ?? null,
                'pickup_route_suggestion' => $pickup_address->route_suggestion ?? null,
                'pickup_address_type' => $pickup_address->address_type ?? null,
                'pickup_other' => $pickup_address->other ?? null,
                'pickup_latitude' => $pickup_address->latitude ?? null,
                'pickup_longitude' => $pickup_address->longitude ?? null,
                'pickup_pincode' => $pickup_address->pincode,
                'delivery_flat_number' => $delivery_address->flat_number ?? null,
                'delivery_area' => $delivery_address->area ?? null,
                'delivery_address' => $delivery_address->address ?? null,
                'delivery_route_suggestion' => $delivery_address->route_suggestion ?? null,
                'delivery_address_type' => $delivery_address->address_type ?? null,
                'delivery_other' => $delivery_address->other ?? null,
                'delivery_latitude' => $delivery_address->latitude ?? null,
                'delivery_longitude' => $delivery_address->longitude ?? null,
                'delivery_pincode' => $delivery_address->pincode,
                'cashback_amount' => $cashback_amount,
                'cashback_flag' => 0,
            ]);

            //Voucher Used Update
            if ($request->voucher_id) {
                $total_used = $voucher['total_used'];
                $voucher['total_used'] = $total_used + 1;
                $voucher->save();
            }
            $i=0;
            //Cart Item Add
            foreach ($cart as $d) {
                $service_type = ServiceType::where('id', $d->service_type_id)->first();
                $service_details = ServiceDetail::where(['service_id' => $d->service_id, 'service_type_id' => $d->service_type_id])->first();

                $order_details = OrderDetails::create([
                    'order_id' => $order->id,
                    'service_id' => $d->service_id,
                    'service_type_id' => $service_type->id,
                    'service_name' => $service_type->service_type_name,
                    'service_price' => $service_details->service_price,
                    'service_quantity' => $d->quantity,
                    'service_detail_total' => $service_details->service_price * $d->quantity,
                    'color_code' => $request->color_code,
                ]);
                for($j = 1; $j <= $d->quantity; $j++){
                    OrderDetailsDetail::create([
                        'order_detail_id'  => $order_details->id,
                        'order_id'  => $order->id,
                        'garment_tag_id'   => $order->order_number .'-'.($j+$i),
                        'image' => null,
                        'remarks'  => null,
                        'is_active'  => 0,
                        'accepted'  => 0,
                        'status' => $order->status,
                    ]);
                }
                $i = $d->quantity+$i;

                //Addon Details
                if ($d->addon_id) {
                    $addon_id = explode(',', $d->addon_id);
                    $addon_details = Addon::whereIn('id', $addon_id)->get();
                    if ($addon_details) {
                        foreach ($addon_details as $add) {
                            $addon_details = OrderAddonDetail::create([
                                'order_id' => $order->id,
                                'addon_id' => $add->id,
                                'order_detail_id' => $order_details->id,
                                'addon_price' => $add->addon_price,
                                'addon_name' => $add->addon_name,
                            ]);
                        }
                    }
                }
                //END Addon Detials
            }

            $outsrnd_amnt = $request->outstanding_amount ?? 0;
            // if($total_amnt < $request->wallet_amount){
            //     return response()->json([
            //         'status' => 0,
            //         'message' => 'Exceeds your total amount',
            //     ]);
            // }
            if($request->wallet_amount - $outsrnd_amnt >= $total_amnt){
            $payment_details = Payment::create([
                'payment_date' => Carbon::now(),
                'customer_id' => $this->customer->id,
                'customer_name' => $customer->name,
                'order_id' => $order->id,
                'payment_type' => 6,
                'received_amount' => $request->wallet_amount - $outsrnd_amnt,
                'transaction_id' => $request->transaction_id,
                'payment_note' => $request->payment_note,
                'financial_year_id' => $financial_year->id,
            ]);
            Wallet::create([
                'deducted_amount' => $request->wallet_amount,
                'customer_id' => $this->customer->id,
                'order_id' => $order->id,
                'remarks' =>"Lk Credit transaction",
            ]);
            }elseif($request->wallet_amount && $request->wallet_amount - $outsrnd_amnt < $total_amnt){
                Payment::create([
                    'payment_date' => Carbon::now(),
                    'customer_id' => $this->customer->id,
                    'customer_name' => $customer->name,
                    'order_id' => $order->id,
                    'payment_type' => 6 ,
                    'received_amount' => $request->wallet_amount,
                    'transaction_id' => $request->transaction_id,
                    'payment_note' => $request->payment_note,
                    'financial_year_id' => $financial_year->id,
                ]);

                Wallet::create([
                    'deducted_amount' => $request->wallet_amount,
                    'customer_id' => $this->customer->id,
                    'order_id' => $order->id,
                    'remarks' =>"Lk Credit transaction",
                ]);
                
                Payment::create([
                    'payment_date' => Carbon::now(),
                    'customer_id' => $this->customer->id,
                    'customer_name' => $customer->name,
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type ,
                    'received_amount' => $request->amount_received - $outsrnd_amnt,
                    'transaction_id' => $request->transaction_id,
                    'payment_note' => $request->payment_note,
                    'financial_year_id' => $financial_year->id,
                ]);
            }elseif($request->amount_received){
                $payment_details = Payment::create([
                    'payment_date' => Carbon::now(),
                    'customer_id' => $this->customer->id,
                    'customer_name' => $customer->name,
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type ,
                    'received_amount' => $request->amount_received - $outsrnd_amnt,
                    'transaction_id' => $request->transaction_id ?? 0,
                    'payment_note' => $request->payment_note,
                    'financial_year_id' => $financial_year->id,
                ]);
            }

             
            //Delete Cart Items After Order
            CartItem::where(['customer_id' => $this->customer->id])->delete();

            if($request->payment_type == 7){
                $payment_mode = "Cash on delivery";
            } elseif($request->payment_type == 8) {
                $payment_mode = "Razor Pay";
            } 
            elseif($request->payment_type == 6) {
                $payment_mode = "Lk Credit";
            }else {
                $payment_mode = "Other";
            }
            $delivery = DeliveryType::where('id', $delivery_type->id)->first();

            $delivery_time = date('h:i', strtotime($delivery_type->delivery_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery_type->delivery_time_to)) . 'pm';
            $pickup_time = date('h:i', strtotime($delivery_type->pickup_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery_type->pickup_time_to)) . 'pm';
            
            if($cashback_amount > 0){
            $cashback_message = 'Congratulations! Cashback of Rs ' .$cashback_amount . ' will be credited in your wallet after completion of the order.';
            } else{
                $cashback_message = '';
            }
            $order_summary = array(
                'order_number' => $order->order_number,
                'order_id' => $order->id,
                'total_amount' => number_format($order->total, 2),
                'payment_type' => $payment_mode,
                'pickup_time' => $pickup_time,
                'pickup_date' => $request->pickup_date,
                'delivery_type' => $delivery_type->delivery_name,
                'delivery_time' => $delivery_time,
                'cashback' => $cashback_message,
                'invoice' => url('/api/order/print-order/' . $order->id),
            );

            //Notification
            $customer_id = $this->customer->id;
            $user_type = 5;
            $title = "Your order has been placed";
            $image = '';
            $body = "Your order " . $order->order_number ." received successfully. Please wait until we process your request";
            $data = array(
                "orderId" => $order->id,
                "orderNumber" => $order->order_number,
                "type" => "Order"
            );
            $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
            //END Notification

            return response()->json([
                'status' => 1,
                'message' => "Order Successfully placed",
                'response' => $order_summary,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => "Your cart is empty",
            ]);
        }
    }

    //cancel order for customer
    public function cancelOrder(Request $request)
    {
        $order = Order::where('id', $request->id)->first();
        $payment = Payment::where('order_id',$request->id)->sum('received_amount');
        if($payment > 0){
            Wallet::create([
                'receive_amount' => $payment,
                'order_id' => $request->id,
                'customer_id' => $order->customer_id,
                'remarks' => "Cancel Order  for Order Number".$order->order_number,
            ]);
        }
        if ($order) {
            if ($order->status <= 1) {
                $order['status'] = 10;
                $order['flag'] = 0;
                $order->save();
                return response()->json([
                    'status' => 1,
                    'message' => 'Order has been Cancelled',
                ]);

                $customer_id = $this->customer->id;
                $user_type = 5;
                $title = "Order cancelled";
                $image = '';
                $body = "Your order " . $order->order_number ." cancelled successfully.";
                $data = array(
                    "orderId" => $order->id,
                    "orderNumber" => $order->order_number,
                    "type" => "Cancel Order"
                );
                $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
                
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Not to be cancelled',
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No order found',
            ]);
        }
    }


}
