<?php
namespace App\Http\Livewire\Admin\Orders;
use App\Models\Customer;
use App\Models\MasterSettings;
use App\Models\Order;
use App\Models\OrderAddonDetail;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Outlet;
use App\Models\User;
use App\Models\OrderDetailsDetail;
use App\Models\Workstation;
use App\Models\OutletDriver;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Traits\SMSService;
use Illuminate\Support\Facades\Crypt;
class ViewSingleOrder extends Component
{
    use SMSService;
    public $order,$orderdetails, $orderss, $orddetdets, $image, $outlate_name,$orderaddons,$lang,$balance,$total,$customer,$payments,$sitename,$address,$phone,$paid_amount,$payment_type,$zipcode,$tax_number,$store_email,$outlet,$workstation,$assign_workstation,$assign_pickupdriver,$assign_deliverydriver,$driver,$deliveryoutlet,$user,$order_items,$totalqty,$ordernumber,$deliverydate,$orderid,$date,$service,$currentDateTime,$expiry_date,$selected_defected = [];

    public $cancelmessage;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.orders.view-single-order');
    }

    /* process before mount */
    public function mount($id)
    {
        //Single Order
        if(Auth::user()->user_type == 1){
            $this->order = Order::where('id', $id)->first();
        }elseif(Auth::user()->user_type == 2){
            $this->order = Order::where('id', $id)->where('outlet_id', Auth::user()->outlet_id)->first();
        }else{
            $this->order = Order::where('created_by', Auth::user()->id)->where('id', $id)->first();
        }
        if(!$this->order){
            abort(404);
        }

        $this->user = User::where('id', $this->order->created_by)->first();
        $this->outlet = Outlet::where('id', $this->order->outlet_id)->first();
        $this->outlate_name=$this->outlet->outlet_name ??'';
        $this->deliveryoutlet = Outlet::where('id', $this->order->delivery_outlet_id)->first();
        $this->workstation = Workstation::where('is_active', 1)->get();
        $this->assign_workstation = $this->order->workstation_id;
        $this->date = Carbon::today()->toDateString();
        $this->driver = OutletDriver::whereHas('user', function($q){
            $q->where('is_active', 1);
        })->where('outlet_id', $this->order->outlet_id)->get();

        $this->assign_pickupdriver = $this->order->pickup_driver_id;
        $this->assign_deliverydriver = $this->order->delivery_driver_id;

        $this->customer = Customer::where('id', $this->order->customer_id)->first();
        $this->orderaddons = OrderAddonDetail::where('order_id', $this->order->id)->get();
        $this->orderdetails = OrderDetailsDetail::where('order_id', $this->order->id)->get();
        $this->payments = Payment::where('order_id', $this->order->id)->get();
        $settings = new MasterSettings();
        $site = $settings->siteData();
        if(isset($site['default_application_name']))
        {
            /* if site  has default application name */
            $sitename = (($site['default_application_name']) && ($site['default_application_name'] !=""))? $site['default_application_name'] : 'Laundry Box';
            $this->sitename = $sitename;
        }
        if(isset($site['default_phone_number']))
        {
            /* if site has default phone number */
            $phone = (($site['default_phone_number']) && ($site['default_phone_number'] !=""))? $site['default_phone_number'] : '123456789';
            $this->phone = $phone;
        }
        if(isset($site['default_address']))
        {
            /* if site has default address */
            $address = (($site['default_address']) && ($site['default_address'] !=""))? $site['default_address'] : 'Address';
            $this->address = $address;
        }
        if(isset($site['default_zip_code']))
        {
            /* if site has default zip code */
            $zipcode = (($site['default_zip_code']) && ($site['default_zip_code'] !=""))? $site['default_zip_code'] : 'ZipCode';
            $this->zipcode = $zipcode;
        }
        if(isset($site['store_tax_number']))
        {
            /* if site has store tax number */
            $tax_number = (($site['store_tax_number']) && ($site['store_tax_number'] !=""))? $site['store_tax_number'] : 'Tax Number';
            $this->tax_number = $tax_number;
        }
        if(isset($site['store_email']))
        {
            /* if site has store email */
            $store_email = (($site['store_email']) && ($site['store_email'] !=""))? $site['store_email'] : 'store@store.com';
            $this->store_email = $store_email;
        }
        $this->balance = $this->order->total - Payment::where('order_id',$this->order->id)->sum('received_amount');
        $this->paid_amount = $this->balance;
        if(session()->has('selected_language')){
            /* session has selected language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        }else{
            $this->lang = Translation::where('default', 1)->first();
        }

        $rewash_date = MasterSettings::where('master_title', 'rewash_time')->where('is_active',1)->first();
        $master_value= $rewash_date->master_value;
        $integer_master_value=(int)$master_value;
        $this->currentDateTime = date("Y-m-d HH:mi:ss", strtotime(Carbon::now()));
        $delivery_date=$this->order->delivered_date;
        $this->expiry_date = date("Y-m-d HH:mi:ss", strtotime("$delivery_date +$integer_master_value days"));

        if($this->order->cancel_request == 1){
            $this->cancelmessage = "Cancel Request Sent Successfully.";
        }
    }

    /* add the payment */
    public function addPayment()
    {
        if($this->order->status == 4)
        {
            return 0;
        }
        $this->validate([
            'paid_amount' => 'required',
            'payment_type' => 'required',
        ]);
        /* if paid amount > balance */
        if($this->paid_amount > $this->balance)
        {
            $this->addError('payment_type','Amount cannot be greater than balance');
            return 0;
        }
        Payment::create([
            'payment_date' => \Carbon\Carbon::today(),
            'customer_id' => $this->customer->id ?? null,
            'customer_name' => $this->customer->name ?? null,
            'order_id' => $this->order->id,
            'payment_type' => $this->payment_type,
            'financial_year_id' => getFinancialYearId(),
            'received_amount' => $this->paid_amount,
            'created_by' => Auth::user()->id,
        ]);
        $this->payments = Payment::where('order_id',$this->order->id)->get();
        $this->balance = $this->order->total - Payment::where('order_id',$this->order->id)->sum('received_amount');
        $this->paid_amount = $this->balance;
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Payment Successfully Added!']);
    }

    public function updatedAssignWorkstation($value)
    {
        $this->order->workstation_id = (int)$value;
        $this->order->save();
        $this->order = Order::where('id', $this->order->id)->first();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Workstation Successfully Assigned!']);
    }

    public function updatedAssignPickupdriver($value)
    {
        $this->order->pickup_driver_id = (int)$value;
        $this->order->save();
        $this->order = Order::where('id', $this->order->id)->first();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Pickup Driver Successfully Assigned!']);
    }

    public function updatedAssignDeliverydriver($value)
    {
        $this->order->delivery_driver_id = (int)$value;
        $this->order->save();
        $this->order = Order::where('id', $this->order->id)->first();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Delivery Driver Successfully Assigned!']);
    }

    /* change the status */
    public function changeStatus($status)
    {
        $orderid = $this->order->id;
        $this->order_items = OrderDetailsDetail::with(['order_details'])->whereHas('order_details', function($q) use($orderid) {
            $q->where('order_id', '=', $orderid);
        })->get();

        foreach ($this->order_items as $items) {
            $orderdd = OrderDetailsDetail::find($items->id);
            $orderdd->status = $status;
            $orderdd->save();
        }

        $current_time=date("Y-m-d h:i:sa");
        $this->order->status = $status;
        if($status==9){
            $data['delivered_date'] =$current_time;
            $data['flag'] =1;
            $data['delivery_time'] =date("h:i:sa");
            $update=Order::where('id', $this->order->id)->update($data);
            //Order delivered third party api sms
            if($update){
                $method = 'POST';
                $requestUrl = 'flow/';
                $bodyType = 'json';
                $queryParams = '';
                // check +91 is in mobile no or not //
                if(str_contains($this->order->phone_number,'91')){
                    $mobile_no=$this->order->phone_number;
                }else{
                    $mobile_no='91'.$this->order->phone_number;
                }
                $formParams = [
                "sender" =>env('SMS_PANEL_SENDER_ID'),
                "template_id" => env('SMS_ORDER_DELIVERED_TEMPLATE_ID'),
                "short_url" => "1",
                "mobiles" =>$mobile_no,
                "order_no" => $this->order->order_number,
                "out_let" => $this->outlate_name,
                ];

                $headers = null;
                $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
            }
            //order delevered end//
        }
        if($status==1){
            // ORDER CONFIRMED
            $method = 'POST';
            $requestUrl = 'flow/';
            $bodyType = 'json';
            $queryParams = '';
            // check +91 is in mobile no or not //
            if(str_contains($this->order->phone_number,'91')){
                $mobile_no=$this->order->phone_number;
            }else{
                $mobile_no='91'.$this->order->phone_number;
            }
            $url = url('/orders/download-invoice/'.Crypt::encryptString($this->order->id));
            $formParams = [
                "sender" => env('SMS_PANEL_SENDER_ID'),
                "template_id" => env('SMS_ORDER_CONFIRMED_TEMPLATE_ID'),
                "short_url" => "1",
                "mobiles" => $mobile_no,
                "order_no" => $this->order->order_number,
                //set here link//
                "invoice_no" =>$url,
            ];
            $headers = null;
            $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
        }

        if($status==2){
            $data['pickup_date'] =$current_time;
            $data['pickup_time'] =date("h:i:sa");
            Order::where('id', $this->order->id)->update($data);
        }
        if($status==7){
            $data['ready_date'] =$current_time;
            $update= Order::where('id', $this->order->id)->update($data);
            // ORDER READY//
            if($update){
                $method = 'POST';
                $requestUrl = 'flow/';
                $bodyType = 'json';
                $queryParams = '';
                // check +91 is in mobile no or not //
                if(str_contains($this->order->phone_number,'91')){
                    $mobile_no=$this->order->phone_number;
                }else{
                    $mobile_no='91'.$this->order->phone_number;
                }
                $formParams = [
                    "sender" => env('SMS_PANEL_SENDER_ID'),
                    "template_id" => env('SMS_ORDER_READY_TEMPLATE_ID'),
                    "short_url" => "1",
                    "mobiles" => $mobile_no,
                    "order_no" => $this->order->order_number,
                    "out_let" => $this->outlate_name,
                ];
                $headers = null;
                $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
            }
            // END//
        }    

        $payment = Payment::where('order_id',$this->order->id)->sum('received_amount');
        if($payment){
            $received_amount = $payment;
        }
        if($status==10){
            Wallet::create([
                'customer_id' => $this->order->customer_id,
                'order_id' => $this->order->id,
                'receive_amount' => $received_amount ?? 0,
                'deducted_amount' => 0,
                'remarks' => "cancel order",
            ]);
            // cancel order
            if(Auth::user()->id == 1){
                $method = 'POST';
                $requestUrl = 'flow/';
                $bodyType = 'json';
                $queryParams = '';
                $mobile_no='';
                // check +91 is in mobile no or not //
                if(str_contains($this->order->phone_number,'91')){
                    $mobile_no=$this->order->phone_number;
                }else{
                    $mobile_no='91'.$this->order->phone_number;
                }
                $formParams = [
                "sender" => env('SMS_PANEL_SENDER_ID'),
                "template_id" => env('SMS_CANCEL_ORDER_TEMPLATE_ID'),
                "short_url" => "1",
                "mobiles" => $mobile_no,
                "order_no" => $this->order->order_number,
                "out_let" => $this->outlate_name,
                ];
                $headers = null;
                $response = $this->makeSMSRequest($method, $requestUrl, $bodyType, $queryParams, $formParams, $headers, false);
                //end//
            }
        }
        $this->order->save();
        $message = sendOrderStatusChangeSMS($this->order->id,$status);
        if($message){
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => $message,'title'=>'SMS Error']);
        }
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Status Successfully Updated!']);
    }

    public function orderdetails($id)
    {
        $this->editMode = true;
        $this->outlet = Outlet::where('id', $this->order->outlet_id)->first();
        $this->deliveryoutlet = Outlet::where('id', $this->order->delivery_outlet_id)->first();
        $this->customer = Customer::where('id', $this->order->customer_id)->first();
        $this->deliverydate = $this->order->delivery_date;
        $this->orderid = $this->order->id;
        $this->ordernumber = $this->order->order_number;
        if($this->customer != ''){
            $this->customername = $this->customer->name;
        }else{
            $this->customername = 'Walk in Customer';
        }

        $this->totalqty = OrderDetails::where('order_id', $this->order->id)->sum('service_quantity');
    }

    public function createRewash()
    {
        $selected_defected = $this->selected_defected;
        foreach($selected_defected as $key => $sel_defected) {

            $sel_def = $this->selected_defected[$key];
            $sel_def_array = array_filter($sel_def, function ($var) {
                return ($var == true);
            });
            if(count($sel_def_array) <= 0)
            {
                $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => "Please choose an item first",'title'=>'rewash error']);
            }

            if($sel_def_array && count($sel_def_array) > 0) {

                $orders = $this->order;
                if($orders){
                    $parents = $orders->parents;
                    $totalElements = count($parents);
                    if($totalElements > 0){
                        $order_number = $parents[$totalElements - 1]['order_number'];
                    }else{
                        $order_number = $this->order->order_number;
                    }

                    $order_number = 'RE'. count($parents) + 1 .':'. $order_number;

                    $order = Order::create([
                        'parent_id' => $this->orderid,
                        'pickup_option' => $orders->pickup_option,
                        'delivery_option' => $orders->delivery_option,
                        'order_number' => $order_number,
                        'outlet_id' => $orders->outlet_id ?? 0,
                        'delivery_outlet_id' => $orders->delivery_outlet_id,
                        'workstation_id' => $orders->workstation_id ?? 0,
                        'customer_id' => $orders->customer_id,
                        'customer_name' => $orders->customer_name,
                        'phone_number' => $orders->phone_number,
                        'order_date' => Carbon::parse($this->date)->toDateTimeString(),
                        'delivery_date' => $orders->delivery_date,
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
                        'delivery_type_id' => $orders->delivery_type_id,
                        'delivery_type' => $orders->delivery_type,
                        'created_by' => Auth::user()->id,
                        'financial_year_id' => 0,
                        'voucher_id' => 0,
                        'voucher_code' => null,
                        'pickup_address_id' => $orders->pickup_address_id ?? null,
                        'pickup_flat_number' => $orders->pickup_flat_number ?? null,
                        'pickup_area' => $orders->pickup_area ?? null,
                        'pickup_address' => $orders->pickup_address ?? null,
                        'pickup_route_suggestion' => $orders->pickup_route_suggestion ?? null,
                        'pickup_address_type' => $orders->pickup_address_type ?? null,
                        'pickup_other' => $orders->pickup_other ?? null,
                        'pickup_latitude' => $orders->pickup_latitude ?? null,
                        'pickup_longitude' => $orders->pickup_longitude ?? null,
                        'pickup_pincode' => $orders->pickup_pincode,
                        'delivery_address_id' => $orders->delivery_address_id ?? null,
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

                    $order_detail = OrderDetails::where('order_id', $this->orderid)->where('id', $key)->first();
                    if ($order_detail && $order_detail->service_quantity > 0) {
                        $order_details = OrderDetails::create([
                            'order_id' => $order->id,
                            'service_id' => $order_detail->service_id,
                            'service_type_id' => $order_detail->service_type_id,
                            'service_name' => $order_detail->service_name,
                            'service_price' => $order_detail->service_price,
                            'service_quantity' => count($sel_def_array),
                            'service_detail_total' => $order_detail->service_price * count($sel_def_array),
                            'color_code' => $order_detail->color_code,
                        ]);

                        foreach ($sel_def_array as $key_sel_def => $sel_def) {
                            $order_detail = OrderDetailsDetail::where('order_id', $this->orderid)->where('id', $key_sel_def)->first();
                            if(Auth::user()->user_type == 1){
                                $rewash_req = 2;
                            }if(Auth::user()->user_type == 2){
                                $rewash_req = 1;
                            }
                            OrderDetailsDetail::create([
                                'order_id' => $order_details->order_id,
                                'order_detail_id' => $order_details->id,
                                'garment_tag_id' => $order_detail->garment_tag_id,
                                'image' => null,
                                'remarks' => null,
                                'is_active' => 0,
                                'accepted' => 0,
                                'rewash_confirm' => $rewash_req ,
                                'status' => $order->status,
                            ]);
                        }

                        //addon details
                        $order_addon_details = OrderAddonDetail::where('order_id', $this->orderid)->where('order_detail_id', $order_detail->id)->get();
                        foreach ($order_addon_details as $add) {
                            OrderAddonDetail::create([
                                'id' => $add->id,
                                'order_id' => $order->id,
                                'addon_id' => $add->addon_id,
                                'order_detail_id' => $order_detail->id,
                                'addon_price' => $add->addon_price,
                                'addon_name' => $add->addon_name,
                            ]);
                        }
                        //end addon details
                    }
                    $data['flag'] = 0;
                    Order::where('id', $this->orderid)->update($data);
                }
            }

            $this->order = Order::where('id', $this->orderid)->first();
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => $this->order->order_number.' Was Successfully Created!']);
        }

        $this->emit('closemodal');
    }

    //Rewash Image 
    public function view_image($id)
    {
        $order_det = Order::where('id',$id)->first();
        $find_order = OrderDetailsDetail::where('order_id',$order_det->parent_id)->first(); 
        $this->image = $find_order->rewash_image;
    }

    //Update cancel request sent
    public function update_cancel()
    {
        $data['cancel_request'] = 1;
        Order::where('id', $this->order->id)->update($data);
        $this->cancelmessage = "Cancel Request Sent Successfully.";
        $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Cancel Request Sent Successfully']);
    }
}
