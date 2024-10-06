<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Helper\CommonHelper;
use App\Models\Customer;
use App\Models\Order;
use App\Models\MasterSettings;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Wallet;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ProfileCustomerController extends Controller
{
    protected $customer;

    public function __construct()
    {
        $this->customer = Auth::guard('customer')->user();
    }

    public function index(Request $request)
    {
        $customer = $this->customer;
        $membership = Membership::where('id',$this->customer->membership)->first(); 
        $lk_credit = Wallet::where('customer_id',$this->customer->id)->sum('receive_amount');
        $lk_debit = Wallet::where('customer_id',$this->customer->id)->sum('deducted_amount');
        $credit = $lk_credit - $lk_debit;
        $order = Order::where('customer_id', $this->customer->id)->get();
        $total_amnt = Order::where('status', 9)->sum('total');
        $outstanding_amount = 0;
        $payment_done = 0;
        $query = Payment::query();
            $query->whereHas('orderz', function($q) {
                    $q->where('status', 9);
            });
        $payment_done = $query->sum('received_amount');
        $outstanding_amount = $total_amnt -  $payment_done; 
        if($membership){
            $icon = url('uploads/membership/'.$membership->icon);
        }else{
            $icon = '';
        }
        if($customer->pin == 0){ 
            $pincode = '';
        } else {
            $pincode = $customer->pin;
        }
        if ($customer) {
            $file = 'uploads/customer/' . $customer->image;
            $image = CommonHelper::file_exists_path($file);
            $customer_data = array(
                'customer_id' => $customer->id,
                'name' => $customer->name,
                'membership' => $membership->membership_name ?? '',
                'membership_image' => $icon,
                'email' => $customer->email,
                'country_code' => $customer->country_code ?? '',
                'phone' => $customer->phone,
                'address' => $customer->address ?? '',
                'pincode' => (string)$pincode ?? '' ,
                'rating' => $customer->rating ?? '',
                'locality' => $customer->locality ?? '',
                'discount' =>number_format($customer->discount ?? '',2),
                'date-of-birth' => $customer->dob ?? '',
                'gst' => $customer->gst ?? '',
                'company_name' => $customer->company_name ?? '',
                'company_address' => $customer->company_address ?? '',
                'outstanding_amount' => number_format($outstanding_amount, 2),
                'Lk_Credit' => number_format($credit, 2),
                'orders' => $order->count(),
                'image' => $image,
            );
            return response()->json([
                'status' => 1,
                'message' => 'Profile Details.',
                'response' => $customer_data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Data Found.',
            ]);
        }
    }

    public function updatecustomer(Request $request)
    {
        //Validation
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()->first(),
            ]);
        } 
        if($request->gst)  {
            $rules = [
                'company_name' => 'required',
                'company_address' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->messages()->first(),
                ]);
            } 
        }
        
        //Update
        $image = '';
        if ($request->has('image')) {
            $path = 'customer';
            $image = CommonHelper::imageupload($request->file('image'), $path);
        } else {
            $customer = Customer::where('id', $this->customer->id)->first();
            $image = $customer->image;
        }
        $data['name'] = $request->name;
        $data['image'] = $image;
        $data['address'] = $request->address;
        $data['pin'] = $request->pincode;
        $data['locality'] = $request->locality;
        $data['dob'] = $request->dob;
        $data['gst'] = $request->gst;
        $data['company_name'] = $request->company_name;
        $data['company_address'] = $request->company_address;
        Customer::where('id', $this->customer->id)->update($data);

        return response()->json([
            'status' => 1,
            'message' => 'Profile updated successfully.',
        ]);
    }

    public function delete_customer(Request $request)
    {
        $checkuser = Customer::where('id', $this->customer->id)->where('is_active', '1')->first();
        if ($checkuser) {
            $data['is_active'] = 0;
            Customer::where('id', $this->customer->id)->update($data);
            // Customer::where('id', $this->customer->id)->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Deleted Successfully',
            ]);
        }
    }

    public function outstanding_list()
    {
        $data = [];
        $order = Order::where('customer_id', $this->customer->id)->where('status', 9)->orWhere('status', 10)->Orderby('id', 'desc')->get();
        if ($order) {
            foreach ($order as $u) {            
                $payment= Payment::where('order_id', $u->id)->sum('received_amount');
                $wallet = Wallet::where('order_id', $u->id)->sum('receive_amount');
                if(($payment - $u->total) != 0 || $wallet){
                    $data[] = array(
                        'id' => $u->id,
                        'order_number' => $u->order_number,
                        'order_date' => date('Y-m-d h:i:s', strtotime($u->created_at ?? '')),
                        'customer_name' => $u->customer_name,
                        'sub_total' => number_format($u->sub_total,2),
                        'addon_total' => number_format($u->addon_total,2),
                        'discount' => number_format($u->discount,2),
                        'express_charge' => number_format($u->express_charge,2),
                        'delivery_charge' => number_format($u->delivery_charge, 2),
                        'voucher_discount' => number_format($u->voucher_discount, 2),
                        'tax_amount' => number_format($u->tax_amount, 2),
                        'total_amount' => number_format($u->total,2),
                        'paid_amount' => number_format($payment ,2),
                        'outstanding_amount' => number_format($payment- $u->total,2)
                    ); 
                }   
            }   
        }
        return response()->json([
            'status' => 1,
            'message' => 'Lk credit amount.',
            'response' => $data,
        ]);
    }
 

    public function outstandingpayment(Request $request){
        $data = [];
        $financial_year = MasterSettings::where('master_title', 'default_financial_year')->where('is_active', 1)->first();
        $order = Order::with('payment')->where('customer_id', $this->customer->id)->where('status', 9)->get();
        if ($order) {
            foreach ($order as $u) {            
                if(($u->total - $u->payment->received_amount) > 0 && $request->outstanding_amount > 0){
                    $data[] = array(
                    'receive_amount' => number_format($u->payment->received_amount ,2),
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
                    'received_amount' =>$u->total - $u->payment->received_amount,
                    'created_by' => Auth::user()->id,
                ]);
            }   
        }
        return response()->json([
            'status' => 1,
            'message' => 'Outstanding Payment Successfull',
            'response' => $data,
        ]);
        }else {
            return response()->json([
                'status' => 1,
                'message' => 'Nothing to be paid',
            ]);
        } 
    }
 
    //membership tier 
    public function membership()
    {
        $data = array();
        $membership = Membership::where('is_active', 1)->get();
        foreach($membership as $m){
            if($m->delivery_fee==1)
            {
                $del_applicable = 'Applicable';
            }else{
                $del_applicable = 'Not Applicable';
            }
            $data[] = array(
                'membership_name'=>$m->membership_name,
                'min_price'=>$m->min_price,
                'max_price'=>$m->max_price,
                'discount'=>$m->discount ,
                'express_fee'=>$m->express_fee,
                'delivery_fee'=>$del_applicable,
                'icon'=>asset('/uploads/membership/'.$m->icon),
            );
        }
        return response()->json([
            'status' => 1,
            'message' => 'Membership Tier.',
            'response' => $data,
        ]);
    }
}
