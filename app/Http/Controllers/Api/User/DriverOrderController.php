<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use App\Models\Addon;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\MasterSettings;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\OrderAddonDetail;
use App\Models\OrderDetails;
use App\Models\Outlet;
use App\Models\OrderDetailsDetail;
use App\Models\Payment;
use App\Models\Pincode;
use App\Models\Service;
use App\Models\Membership;
use App\Models\ServiceCategory;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Voucher;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\SMSService;
class DriverOrderController extends Controller
{
    use SMSService;
    public function __construct()
    {
        $this->user = Auth::guard('user')->user();
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
        } else {
            return $code_prefix . '0001';
        }
    }

    public function insert(Request $request)
    {
        $addon_sum = 0;
        $subtotal = 0;
        $discount_amount = 0;
        $special_discount = 0;
        $delivery_charge = 0;
        $service_price = 0;
        $customer = Customer::where('id', $request->customer_id)->first();
        $cart = CartItem::where('customer_id', $request->customer_id)->get();
        $voucher = Voucher::where('id', $request->voucher_id)->first();
        $cutoff_amount = 0;
        $delivery_type = DeliveryType::where('id', $request->delivery_type_id)->first();
        if (!$delivery_type) {
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
        
            $pamnt_rcv = Wallet::where('customer_id',$request->customer_id)->sum('receive_amount');
            $pamnt_ddct = Wallet::where('customer_id',$request->customer_id)->sum('deducted_amount');
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
                $customer = Customer::where('id', $request->customer_id)->first();
                $membership_ids = $customer->membership;
                $memberships = Membership::where('id',$membership_ids)->first();
                $exp_chrge = $item_charge * $delivery_type['amount'] / 100;
                if ($request->delivery_type_id == 3 && $membership_ids) {
                    $exp_disc_percentage = $exp_chrge * $memberships->express_fee /100;
                    $express_charge = $exp_chrge - $exp_disc_percentage;
                }elseif($request->delivery_type_id){ 
                    $express_charge = $delivery_type->type == 'Flat' ? $delivery_type['amount'] : $item_charge * $delivery_type['amount'] / 100;
                    $Total_price = $subtotal + $addon_sum + $express_charge;
                    if ($delivery_type['cut_off_amount'] < $Total_price) {
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
                $membership_id=0;
                $membership_id = $customer->membership ?? '';
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
                if ($request->voucher_id) {
                    $cutoff_amount = $voucher->cutoff_amount;
                    if ($cutoff_amount <= $Total_price) {
                        $discount_type = $voucher['discount_type'];
                        $discount_amount = $discount_type == 1 ? $amount_after_discount * $voucher['discount_amount'] / 100 : $voucher['discount_amount'];
                    }} else {
                    $discount_amount = 0;
                }
                $neet_total_price = $amount_after_discount - $discount_amount;
                $after_delivery_charge = $neet_total_price + $delivery_charge;
                $tax = MasterSettings::where('master_title', 'default_tax_percentage')->where('is_active', 1)->first();
                $tax_percentage = $tax->master_value;
                $tax_price = $tax_percentage * $after_delivery_charge / 100;
                $financial_year = MasterSettings::where('master_title', 'default_financial_year')->where('is_active', 1)->first();
            }

            // outstanding amount payment
            $data = [];
            $financial_year = MasterSettings::where('master_title', 'default_financial_year')->where('is_active', 1)->first();
            $orderss = Order::with('payment')->where('customer_id', $request->customer_id)->get();
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
                        'customer_id' => $request->customer_id ?? null,
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
            if ($this->generateOrderID()) {
                $gen_ord = $this->generateOrderID();
                // return response()->json($gen_ord);
            }
 
            //Place Order
            $order = Order::create([
                'order_number' => $gen_ord,
                'outlet_id' => $pickup_outlets->id ?? '',
                'workstation_id' => $pickup_outlets->workstation_id ?? '',
                'delivery_outlet_id' => $delivery_outlets->id ?? '',
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'pickup_option' => 2,
                'delivery_option' => 2,
                'delivery_type_id' => (int)$request->delivery_type_id,
                'pickup_address_id' => $pickup_address->id,
                'delivery_address_id' => $delivery_address->id,
                'phone_number' => $customer->phone,
                'order_date' => date("Y-m-d h:i:sa"),
                'pickup_date' => date("Y-m-d h:i:sa"),
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
                'status' => 2,
                'order_type' => 1,
                'pickup_driver_id' => 0,
                'delivery_driver_id' => $this->user->id,
                'delivery_type' => $delivery_type->delivery_name,
                'created_by' => $this->user->id,
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
                        'order_id' => $order_details->order_id,
                        'order_detail_id' => $order_details->id,
                        'garment_tag_id' => $order->order_number .'-'.($j+$i),
                        'image' => null,
                        'remarks' => null,
                        'is_active' => 0,
                        'accepted' => 0,
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

        //Payment Details
        $outsrnd_amnt = $request->outstanding_amount ?? 0;
        $total_amnt = $neet_total_price + $tax_price + $delivery_charge;
            if($request->wallet_amount - $outsrnd_amnt >= $total_amnt){
            $payment_details = Payment::create([
                'payment_date' => Carbon::now(),
                'customer_id' => $request->customer_id,
                'customer_name' => $customer->name,
                'order_id' => $order->id,
                'payment_type' => 6,
                'received_amount' => $request->amount_received,
                'transaction_id' => $request->transaction_id,
                'payment_note' => $request->payment_note,
                'financial_year_id' => $financial_year->id,
            ]);
            Wallet::create([
                'deducted_amount' => $request->amount_received,
                'customer_id' => $request->customer_id,
                'order_id' => $order->id,
                'remarks' =>"Lk Credit transaction",
            ]);

            }elseif($request->wallet_amount && $request->wallet_amount - $outsrnd_amnt < $total_amnt){
                Payment::create([
                    'payment_date' => Carbon::now(),
                    'customer_id' => $request->customer_id,
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
                    'customer_id' => $request->customer_id,
                    'order_id' => $order->id,
                    'remarks' =>"Lk Credit transaction",
                ]);
                
                Payment::create([
                    'payment_date' => Carbon::now(),
                    'customer_id' => $request->customer_id,
                    'customer_name' => $customer->name,
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type ,
                    'received_amount' => $request->amount_received - $outsrnd_amnt ?? 0,
                    'transaction_id' => $request->transaction_id,
                    'payment_note' => $request->payment_note,
                    'financial_year_id' => $financial_year->id,
                ]);
            }else{
                $payment_details = Payment::create([
                    'payment_date' => Carbon::now(),
                    'customer_id' => $request->customer_id,
                    'customer_name' => $customer->name,
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type ,
                    'received_amount' => $request->amount_received - $outsrnd_amnt ?? 0,
                    'transaction_id' => $request->transaction_id ?? 0,
                    'payment_note' => $request->payment_note,
                    'financial_year_id' => $financial_year->id,
                ]);
            }
            //Delete Cart Items After Order
            CartItem::where(['customer_id' => $request->customer_id])->delete();

            if ($request->payment_type == 1) {
                $payment_mode = "Cash";
            } elseif($request->payment_type == 7) {
                $payment_mode = "Cash on delivery";
            }elseif($request->payment_type == 6) {
                $payment_mode = "Lk Credit";
            }
            $delivery = DeliveryType::where('delivery_name', $order->delivery_type)->first();

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
                'payment_type' => $payment_mode ?? null,
                'pickup_time' => $pickup_time,
                'pickup_date' => $request->pickup_date ?? '',
                'delivery_type_id' => $delivery_type->id,
                'delivery_type' => $delivery_type->delivery_name,
                'delivery_time' => $delivery_time,
                'cashback' => $cashback_message,
                'invoice' => url('admin/orders/print-order/' . $order->id),
            );
            $customer_id = $request->customer_id;
            $user_type = 4;
            $title = "Your order has been placed";
            $image = '';
            $body = "Your order " . $order->order_number . " received successfully. Please wait until we process your request";
            $data = array(
                "orderId" => $order->id,
                "orderNumber" => $order->order_number,
                "type" => "Order",
            );
            $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
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

    //driver assign orders
    public function assignorder()
    {
        $data = '';
        $delivered_order = 0;
        $user = User::where('is_active', 1)->where('id', $this->user->id)->first();
        $order = Order::where('pickup_driver_id', $this->user->id)->orwhere('delivery_driver_id', $user->id)->count();
        $pick_up_order = Order::where('status', 2)->where('delivery_driver_id', $user->id)->count();
        $deliver_order = Order::where('status', 2)->where('pickup_driver_id', $user->id)->count();
        $pick = Order::where('status', 11)->where('delivery_driver_id', $user->id)->count();
        $deliver = Order::where('status', 11)->where('pickup_driver_id', $user->id)->count();
        if ($order) {
            $data = array(
                'assign_order' => $order ?? '',
                'picked_up_order' => $pick_up_order + $deliver_order ?? '',
                'delivered_order' => $pick + $deliver ?? '',
                'pending_order' => $order - ($pick + $deliver + $pick_up_order + $deliver_order),
            );
            return response()->json([
                'status' => 1,
                'message' => "You have assign orders",
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => "You have not assigned any orders",
            ]);
        }
    }

    //Assign order listing
    public function assignorder_listing()
    {
        $data = [];
        $user = User::where('is_active', 1)->where('id', $this->user->id)->first();
        $order = Order::where('pickup_driver_id', $user->id)->orWhere('delivery_driver_id', $user->id)
            ->whereIn('status', [8, 11, 2, 9])->orderBy('id', 'DESC')->get();
        if ($order) {
            foreach ($order as $o) {
                $status = getOrderStatus($o->status);
                $delivery = DeliveryType::where('is_active', 1)->where('delivery_name', $o->delivery_type)->get();
                $date = '';
                foreach ($delivery as $d) {
                    if ($o->status == 2) {
                        $pickup_address = $o->pickup_address;
                        $date = $o->pickup_date;
                        $time = date('h:i', strtotime($d->pickup_time_from)) . 'am' . ' - ' . date('h:i', strtotime($d->pickup_time_to)) . 'pm';
                    }elseif ($o->status == 8) {
                        $pickup_time = $o->delivery_time;
                        $date = $o->delivery_date;
                        $delivery_address = $o->delivery_address;
                        $time = date('h:i', strtotime($d->delivery_time_from)) . 'am' . ' - ' . date('h:i', strtotime($d->delivery_time_to)) . 'pm';
                    }elseif ($o->status == 9) {
                        $pickup_time = $o->delivery_time;
                        $date = $o->delivered_date;
                        $delivery_address = $o->delivery_address;
                        $time = date('h:i', strtotime($d->delivery_time_from)) . 'am' . ' - ' . date('h:i', strtotime($d->delivery_time_to)) . 'pm';
                    }
                }

                $payment_details = Payment::where('order_id', $o->id)->first();
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
                }elseif ($payment_details && $payment_details->payment_type == 7) {
                    $payment_mode = "Cash On Delivery";
                }
                elseif ($payment_details && $payment_details->payment_type == 8) {
                    $payment_mode = "Razor Pay";
                }
                $address = Address::where('status', 1)->where('customer_id', $o->customer_id)->first();
                if ($address) {
                    $latitude = $address->latitude;
                    $longitude = $address->longitude;
                }
                $image = asset('uploads/customer/avatar-370-456322.png');
                $customer = Customer::where('is_active', 1)->where('id', $o->customer_id)->orWhere('id', $o->parent_id)->get();
                foreach ($customer as $c) {
                    if ($c->image) {
                        $image = asset('uploads/customer/' . $c->image);
                    } elseif ($c->image == null) {
                        $image = asset('uploads/customer/avatar-370-456322.png');
                    }
                }
                $payment_receive = Payment::where('order_id',$o->id)->sum('received_amount');
                $data[] = array(
                    'order_number' => $o->order_number,
                    'order_id' => $o->id,
                    'customer_id' => $o->customer_id,
                    'customer_name' => $o->customer_name,
                    'customer_number' => $o->phone_number,
                    'image' => $image,
                    'pickup_address' => $pickup_address ?? '',
                    'delivery_address' => $delivery_address ?? '',
                    'latitude' => $latitude ?? '',
                    'longitude' => $longitude ?? '',
                    'status' => $status,
                    'time' => $time ?? '',
                    'pickup_time' => $o->pickup_time ?? '',
                    'delivery_time' => $o->delivery_time ??'',
                    'note' => $o->note ?? '',
                    'date' => date('Y-m-d', strtotime($date)),
                    'payment_type' => strval($payment_mode ?? '') ,
                    'paid_amount' => strval($payment_receive ?? '') ,
                    'total_amount' => number_format($o->total, 2),
                );
            }
            return response()->json([
                'status' => 1,
                'message' => "Assign orders list",
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => "No orders found",
            ]);
        }
    }

    //order listing for driver
    public function index()
    {
        $data = [];
        $delivery = [];
        $order = Order::with('delivery_driver', 'pickup_driver')->where('created_by', $this->user->id)->Orderby('id', 'desc')->get();
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
                }elseif ($payment_details && $payment_details->payment_type == 7) {
                    $payment_mode = "Cash On Delivery";
                }
                elseif ($payment_details && $payment_details->payment_type == 8) {
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
                        'Pickup' => 1,
                        'Processing' => 1,
                        'Out for Delivery' => 1,
                        'Delivered' => 1,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Out for Delivery") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
                        'Pickup' => 1,
                        'Processing' => 1,
                        'Out for Delivery' => 1,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Processing" || $status == "To be Processed" || $status == "In Transit" || $status == "Sent to Store" || $status == "Ready") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
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
                        'Pickup' => 1,
                        'Processing' => 0,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 0,
                    );
                } elseif ($status == "Confirm") {
                    $check = array(
                        'Pending' => 1,
                        'Confirm' => 1,
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
                        'Pickup' => 0,
                        'Processing' => 0,
                        'Out for Delivery' => 0,
                        'Delivered' => 0,
                        'Cancel' => 1,
                    );
                }
                $phone = '';
                if ($u->pickup_driver) {
                    $phone = $u->pickup_driver->phone;
                } else if ($u->delivery_driver) {
                    $phone = $u->delivery_driver->phone;
                }
                $master_setting = MasterSettings::where('master_title', 'default_phone_number')->where('is_active', 1)->first();
                $default_number = $master_setting->master_value;
                $data[] = array(
                    'id' => $u->id,
                    'order_number' => '#' . $u->order_number,
                    'customer_name' => $u->customer_name,
                    'phone_number' => $u->phone_number,
                    'order_date' => date('Y-m-d h:i:s', strtotime($u->created_at ?? '')),
                    'pickup_date' => $u->order_date ?? '',
                    'delivery_date' => $u->delivery_date ?? '',
                    'delivery_time_between' => $delivery_time ?? '',
                    'pickup_time_between' => $pickup_time ?? '',
                    'pickup_address' => $u->pickup_address ?? 'Store pickup',
                    'pickedup_time' => $u->pickup_time ?? '',
                    'delivered_time' => $u->delivery_time ?? '',
                    'delivery_agent_number' => $phone,
                    'support_phone_number' => $default_number,
                    'sub_total' => number_format($u->sub_total, 2),
                    'addon_total' => number_format($u->addon_total, 2),
                    'delivery_charge' => number_format($u->delivery_charge, 2),
                    'discount' => number_format($u->discount, 2),
                    'tax_percantage' => $u->tax_percentage,
                    'tax_amount' => number_format($u->tax_amount, 2),
                    'payment_type' => $payment_mode,
                    'total' => number_format($u->total, 2),
                    'outstanding_amount' => $due_amount,
                    'status_track' => $check,
                );
            }
            return response()->json([
                'status' => 1,
                'message' => 'Orders List.',
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Data Found.',
            ]);
        }
    }

    //order details for driver
    public function orders(Request $request)
    {
        $data = [];
        $item = [];
        $delivery = '';
        if ($request->id) {
            $driver_id = $this->user->id;
            $order = Order::query();
            $order->where('id', $request->id)->where(function ($query) use ($driver_id) {
                $query->where('delivery_driver_id', '=', $driver_id)
                    ->orWhere('pickup_driver_id', '=', $driver_id);
            });
            $order = $order->first();
            if ($order) {
                $order_details = OrderDetails::where('order_id', $request->id)->get();
                foreach ($order_details as $u) {
                    $delivery = DeliveryType::where('delivery_name', $order->delivery_type)->first();
                    if ($delivery) {
                        $delivery_time = date('h:i', strtotime($delivery->delivery_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery->delivery_time_to)) . 'pm';
                        $pickup_time = date('h:i', strtotime($delivery->pickup_time_from)) . 'am' . ' - ' . date('h:i', strtotime($delivery->pickup_time_to)) . 'pm';
                    }
                    $services = Service::where('id', $u->service_id)->first();
                    $service_category = ServiceCategory::where('id', $services->service_category_id)->first();
                    $tax = MasterSettings::where('master_title', 'default_tax_percentage')->where('is_active', 1)->first();
                    $tax_percentage = $tax->master_value;
                    //qr image 
                    $outlet = Outlet::where('id', $order->outlet_id)->first();
                    $payment_done = Payment::where('order_id',$order->id)->sum('received_amount');
                    if($order->total > $payment_done){
                        $qr_image = $outlet->qr_code ;
                    }else{
                        $qr_image= '';
                    }
                    //Get Order Status
                    // $status = getOrderStatus($order->status);
                    $addon_details = OrderAddonDetail::where(['order_id' => $u->order_id, 'order_detail_id' => $u->id])->get();
                    $addon = [];
                    if ($addon_details) {
                        foreach ($addon_details as $add) {
                            $addon[] = array(
                                'id' => $add->addon_id,
                                'addon_name' => $add->addon_name,
                                'addon_price' => $add->addon_price,
                            );
                        }
                        $image = $service_category->image;
                        $item[] = array(
                            'order_detail_id' => $u->id,
                            'service_types' => $u->service_name,
                            'garment_id' => $services->id,
                            'garment_name' => $services->service_name,
                            'service_category' => $service_category->service_category_name,
                            'image' => asset('assets/img/service-icons/' . $services->icon),
                            'service_price' => number_format($u->service_price ?? 0, 2),
                            'service_quantity' => $u->service_quantity,
                            'service_detail_total' => number_format($u->service_detail_total, 2),
                            'color_code' => $u->color_code ?? '',
                            'addon' => $addon,
                        );
                    }
                }
                $phone = '';
                if ($order->pickup_driver) {
                    $phone = $order->pickup_driver->phone;
                } else if ($order->delivery_driver) {
                    $phone = $order->delivery_driver->phone;
                }
                $master_setting = MasterSettings::where('master_title', 'default_phone_number')->where('is_active', 1)->first();
                $default_number = $master_setting->master_value;
                $payment_receive = Payment::where('order_id',$order->id)->sum('received_amount');
                $due_amount = $order->total - $payment_receive;
                $data['order details'] = array(
                    'order_id' => $order->id,
                    'order_number' => '#' . $order->order_number,
                    'customer_name' => $order->customer_name,
                    'phone_number' => $order->phone_number,
                    'order_date' => date('Y-m-d', strtotime($order->order_date)) ?? '',
                    'delivery_date' => date('Y-m-d', strtotime($order->delivery_date)) ?? '',
                    'delivery_time' => $delivery_time ?? '',
                    'pickup_time' => $pickup_time ?? '',
                    'pickedup_time' => $order->pickup_time ?? '',
                    'delivered_time' => $order->delivery_time ?? '',
                    'pickup_address' => $order->pickup_address ?? 'Store pickup',
                    'delivery_address' => $order->delivery_address ?? 'Store delivery',
                    'delivery_agent_number' => $phone,
                    'note' => $order->note ?? '',
                    'status' => getOrderStatus($order->status),
                    'flag' => $order->flag,
                    'upi_qr_code' => $qr_image,
                    'items' => $item,
                );
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
                
                elseif ($payment_details && $payment_details->payment_type == 8) {
                    $payment_mode = "Razor Pay";
                }else {
                    $payment_mode = "Cash On Delivery";
                }
                $paidbylkcredit = Wallet::where('order_id',$order->id)->sum('deducted_amount');
                $data['Payment'] = array(
                    'sub_total' => number_format($order->sub_total, 2),
                    'addon_total' => number_format($order->addon_total, 2),
                    'discount' => number_format($order->discount, 2),
                    'express_charge' => number_format($order->express_charge, 2),
                    'delivery_charge' => number_format($order->delivery_charge, 2),
                    'voucher_discount' => number_format($order->voucher_discount, 2),
                    'tax_percentage'=>$tax_percentage,
                    'tax_amount' => number_format($order->tax_amount, 2),
                    'total_amount' => number_format($order->total, 2),
                    'due_amount' => number_format($due_amount, 2) ?? '',
                    'payment_type' => $payment_mode,
                    'Lk_credit_payment' => number_format($paidbylkcredit,2)?? '',
                    'Cashback' => number_format($order->cashback_amount ,2)?? '',
                );
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
            }}
    }

    //order status change
    public function status(Request $request)
    {
        $financial_year = MasterSettings::where('master_title', 'default_financial_year')->where('is_active', 1)->first();
        $order = Order::with('outlet')->where('id', $request->order_id)->first();
        $current_date=date("Y-m-d h:i:sa");
        if ($order && $order->status == 11) {
            $data['status'] = 2;
            $data['pickup_time'] = now();
            $data['pickup_date'] = $current_date;
            Order::where('id', $request->order_id)->update($data);
            //notification after picked up
            $customer_id = $order->customer_id;
            $title = "Order Picked up successfully";
            $image = '';
            $body = "Your order " . $order->order_number . " Picked up successfully.";
            $user_type = $request->user_type;
            $data = array(
                "orderId" => $order->id,
                "orderNumber" => $order->order_number,
                "type" => "Order"
            );
            $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
            
            return response()->json([
                'status' => 1,
                'message' => 'Order Picked up successfully',
            ]);
        } elseif ($order && $order->status == 8) {
            $data['status'] = 9;
            $data['flag'] = 1;
            $data['delivery_time'] = now();
            $data['delivered_date'] = $current_date;
            Order::where('id', $request->order_id)->update($data);
            if($request->amount_received){
                $payment_details = Payment::create([
                    'payment_date' => Carbon::now(),
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer_name,
                    'order_id' => $order->id,
                    'payment_type' => $request->payment_type,
                    'received_amount' => $request->amount_received ?? 0,
                    'transaction_id' => $request->transaction_id,
                    'payment_note' => $request->payment_note,
                    'financial_year_id' => $financial_year->id,
                ]);
            }

            $mstrStngs = MasterSettings::where('master_title', 'refer_amount')->where('is_active', 1)->first();
            $refer_amnt=  $mstrStngs->master_value;
            $mstrStng = MasterSettings::where('master_title', 'joining_bonus')->where('is_active', 1)->first();
            $joinee_bonus =  $mstrStng->master_value;
            $frst_order = Order::where('customer_id',$order->customer_id)->count();
            $cstmr_frst = Customer::where('id',$order->customer_id)->first();
            if($cstmr_frst){
                $refer_code_id = $cstmr_frst->referrel_customer_id;
                // $checking = Customer::where('refer_code',$refer_code)->first();
                if($frst_order == 1 && $refer_code_id != null){
                    Wallet::create([
                        'receive_amount' => $refer_amnt,
                        'customer_id' => $refer_code_id,
                        'remarks' =>"Refer Amount",
                    ]);
                    Wallet::create([
                        'receive_amount' => $joinee_bonus,
                        'customer_id' => $cstmr_frst->id,
                        'remarks' =>"New Joinee Bonus Amount",
                    ]);
                }
            }

            //notification after delivered
            $customer_id = $order->customer_id;
            $title = "Order delivered successfully";
            $image = '';
            $body = "Your order " . $order->order_number. " Delivered successfully. Thank you for choosing us";
            $user_type = $request->user_type;
            $data = array(
                "orderId" => $order->id,
                "orderNumber" => $order->order_number,
                "type" => "Order"
            );
            $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
            //Order delivered third party api sms
            $method = 'POST';
            $requestUrl = 'flow/';
            $bodyType = 'json';
            $queryParams = '';
            // check +91 is in mobile no or not //
            if(str_contains($order->phone_number,'91')){
                $mobile_no=$order->phone_number;
            }else{
                $mobile_no='91'.$order->phone_number;
            }
            $formParams = [
               "sender" =>env('SMS_PANEL_SENDER_ID'),
               "template_id" => env('SMS_ORDER_DELIVERED_TEMPLATE_ID'),
               "short_url" => "1",
               "mobiles" =>$mobile_no,
               "order_no" => $order->order_number,
               "out_let" => $order->outlet->outlet_name,
            ];
           
            $headers = null;
            $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
            
            return response()->json([
                'status' => 1,
                'message' => 'Order delivered successfully',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Something wrong',
            ]);
        }
    }
}
