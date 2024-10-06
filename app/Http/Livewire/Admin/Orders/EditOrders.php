<?php
namespace App\Http\Livewire\Admin\Orders;
use App\Models\Addon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderDetailsDetail;
use App\Models\OrderAddonDetail;
use App\Models\DeliveryType;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\ServiceDetail;
use App\Models\ServiceType;
use App\Models\Translation;
use App\Models\Brand;
use App\Models\MasterSettings;
use App\Models\Payment;
use App\Models\Voucher;
use App\Models\CustomerAddresses;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Pincode;
use App\Http\Helper\CommonHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
class EditOrders extends Component
{
    public $services, $search_query, $order_id, $inputs = [],$selservices = [], $customer, $date, $delivery_date, $discount, $paid_amount, $payment_type, $deliverytype, $deliverychrg, $getchrg, $showdeliverytype, $assignoutlet, $deliverychrgamnt, $deliveryday, $cutoffchrge, $cutoffamount, $addonsubtotal, $deliveryname, $orderhistory, $totalorderamount, $totalorderrecvd, $totalorderoutstanding, $order, $outlet, $delivery_outlet, $assigndeliveryoutlet, $getoutlet,$getoutletname,$pickupaddress,$deliveryaddress,$instruction,$workstation, $expresschrg,$expresschrge, $showdeliverytypes;

    public $check_amount, $sgst_percentage,$sgst, $cgst, $cgst_percentage, $check_amounts = 0, $cashback, $discnt, $getcashback, $Payment, $payment_notes, $payment_mode, $orderbalance, $note, $service_types, $service, $inputi, $prices = [], $quantity = [], $selected_type, $service_addon, $addons, $brands, $addonKey, $selected_addons = [],$colors = [], $assignaddon, $assignBrand, $brandKey, $selected_brands = [];

    public $customer_name,$customer_phone,$email,$tax_no, $gst, $salutation, $dob,$address, $company_name, $company_address, $locality, $pin, $custdiscount, $rating, $selected_customer,$customers,$custname, $custid, $addressnew, $flat, $area, $landmark, $latitude, $longitude, $addtype, $other, $custpincode, $voucher, $voucherid, $voucherdiscount, $vouamnt, $vouchercode, $voucer_id, $voucher_code, $vouchr_amount, $getvoucher, $customer_query,$is_active = 1;

    public $total, $sub_total, $addon_total, $tax_percent, $tax, $balance,$flag = 0, $lang, $categories, $service_category_id, $pickupoption, $deliveryoption, $getdiscount, $salutations;

    public $delivery = "";
    
    /* render the page */
    public function render()
    {
        if($this->pickupoption == 2 && $this->pickupaddress > 0)
        {
            $pickupadd = CustomerAddresses::where('id', $this->pickupaddress)->first();
            $pickuppincode = $pickupadd->pincode;
            $selectoutlet = Pincode::where('pincode', $pickuppincode)->first();
            if($selectoutlet != ''){
                $this->outlet = $selectoutlet->outlet_id;
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Outlet not found in this pincode! You can select outlet manually']);
                $this->outlet = '';
            }
        }

        if($this->deliveryoption == 2 && $this->deliveryaddress > 0)
        {
            $deliveryadd = CustomerAddresses::where('id', $this->deliveryaddress)->first();
            $deliverypincode = $deliveryadd->pincode;
            $selectdeliveryoutlet = Pincode::where('pincode', $deliverypincode)->first();
            if($selectdeliveryoutlet != ''){
                $this->delivery_outlet = $selectdeliveryoutlet->outlet_id;
            }else{
                $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Outlet not found in this pincode! You can select outlet manually']);
                $this->delivery_outlet = '';
            }
        }

        if($this->deliveryoption == 2 && $this->pickupaddress == 0){
            $this->outlet = '';
        }
        if($this->deliveryoption == 2 && $this->deliveryaddress == 0){
            $this->delivery_outlet = '';
        }

        $this->categories = ServiceCategory::where('is_active', 1)->get();
        return view('livewire.admin.orders.edit-orders');
    }

    /* process before render */
    public function mount($id)
    {
        //Single Order
        if(Auth::user()->user_type == 1){
            $this->order = Order::where('id', $id)->whereNotIn('status', [9,10])->first();
        }elseif(Auth::user()->user_type == 2){
            $this->order = Order::where('id', $id)->where('outlet_id', Auth::user()->outlet_id)->whereNotIn('status', [9,10])->first();
        }else{
            $this->order = Order::where('created_by', Auth::user()->id)->where('id', $id)->whereNotIn('status', [9,10])->first();
        }
        if(!$this->order){
            abort(404);
        }

        $this->selectCustomer($this->order->customer_id);
        $this->date = $this->order->order_date;

        $order_details = OrderDetails::where('order_id', $id)->get();
        foreach($order_details as $key => $ord_details){
            $this->service = $ord_details->service;
            $this->selected_type = $ord_details->service_type_id;
            $service_addon = ServiceAddon::where('service_id', $this->service->id)->get();
            $this->service_addon = $service_addon;
            $this->addItem();
            $this->prices[$key+1] = $ord_details->service_price;
            $this->quantity[$key+1] = $ord_details->service_quantity;
            $this->selected_brands[$key+1] = $ord_details->brand_id;
            $this->colors[$id] = $ord_details->color_code;
            $OrderAddonDetail = [];
            $order_addon_detail = OrderAddonDetail::where('order_detail_id', $ord_details->id)->pluck('addon_id');
            foreach ($order_addon_detail as $ord_addon_details){
                $OrderAddonDetail[$ord_addon_details] = true;
            }
            $this->selected_addons[$key+1] = $OrderAddonDetail;
        }

        $this->pickupoption = $this->order->pickup_option;
        $this->deliveryoption = $this->order->delivery_option;
        $this->pickupaddress = $this->order->pickup_address_id;
        $this->deliveryaddress = $this->order->delivery_address_id;
        $this->outlet = $this->order->outlet_id;
        $this->delivery_outlet = $this->order->delivery_outlet_id;
        $this->delivery = $this->order->delivery_type_id;
        $this->updatedDelivery($this->delivery);
        $this->payment_notes = $this->order->note;
        $this->instruction = $this->order->instruction;
        //END Single Order

        $this->brands = Brand::where('is_active',1)->latest()->get();
        $this->showdeliverytype = DeliveryType::where('is_active',1)->latest()->get();
        
        if(Auth::user()->user_type==2){
            $this->getoutlet = User::where('id',Auth::user()->id)->latest()->first();
            $this->assignoutlet = Outlet::where('is_active',1)->latest()->get();
            $this->assigndeliveryoutlet = Outlet::where('is_active',1)->latest()->get();
        }else{
            $this->assignoutlet = Outlet::where('is_active',1)->latest()->get();
            $this->assigndeliveryoutlet = Outlet::where('is_active',1)->latest()->get();
        }

        $this->salutations = Customer::$salutations;
        $this->services = Service::where('is_active',1)->latest()->get();
        $this->date = Carbon::today()->toDateString();
        $this->service_types = collect();
        $this->addons = Addon::where('is_active',1)->latest()->get();
        $this->delivery_date = Carbon::today()->toDateString();
        $master_settings = MasterSettings::where('master_title', 'cgst_percentage')->where('is_active', 1)->first();
        $this->cgst_percentage = $master_settings->master_value;
        $master_settingss = MasterSettings::where('master_title', 'sgst_percentage')->where('is_active', 1)->first();
        $this->sgst_percentage = $master_settingss->master_value;
        $this->generateOrderID();
        if(session()->has('selected_language')) {
            /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        } else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }

        $this->calculateTotal();
    }

    public function showservice($id)
    {
        $query = Service::where('is_active', 1);
        if ($id){
            $query->where('service_category_id', $id);
        }
        if($this->search_query){
           $query->where('service_name', 'like' , '%'.$this->search_query.'%'); 
        }
        $this->services = $query->latest()->get();

        $this->service_category_id = $id;
    }

    public function assignBrand($key)
    {
        $this->brandKey = $key;
    }

    public function changeColor($id) {
        $this->colors[$id]=$this->colors[$id];
    }

    public function assignAddon($key)
    {
        $this->addonKey = $key;
    }

    public function storeAssignAddon($key)
    {
        $this->selected_addons[$key] = $this->selected_addons[$key];

        $this->emit('closemodal');
        $this->calculateTotal();
    }

    public function storeAssignBrand($key)
    {
        $this->selected_brands[$key] = $this->selected_brands[$key];

        $this->emit('closemodal');
    }

    /* process while update element */
    public function updated($name,$value)
    {
        /* if updated value is empty set the value as null */
        if ( $value == '' ) data_set($this, $name, null);
        /* if updated elemtnt is search_query */
        if($name == 'search_query' && $value != ''){
            //$this->services = Service::where('service_name', 'like' , '%'.$value.'%')->latest()->get();

            $query = Service::where('is_active', 1)->where('service_name', 'like' , '%'.$value.'%');
            if ($this->service_category_id){
                $query->where('service_category_id', $this->service_category_id);
            }
            $this->services = $query->latest()->get();

            $this->search_query = $value;
        }elseif($name == 'search_query' && $value == ''){
            //$this->services = Service::latest()->get();

            $query = Service::where('is_active',1);
            if ($this->service_category_id){
                $query->where('service_category_id', $this->service_category_id);
            }
            $this->services = $query->latest()->get();

            $this->search_query = $value;
        }

        /* if the updated value is customer_query */
        if($name == 'customer_query' && $value != ''){
            $this->customers = Customer::where(function($query) use ($value) { 
                $query->where('name', 'like', '%' . $value . '%')->orWhere('phone', 'like', '%' . $value . '%');
            })->latest()->limit(5)->get();
        }elseif($name == 'customer_query' && $value == ''){
            $this->customers = collect();
        }

        $this->calculateTotal();
    }

    /* select service */
    public function selectService($id)
    {
        $this->selected_type = '';
        $this->service = Service::where('id',$id)->first();
        $this->service_types = collect();
        /* if service is not empty */
        if($this->service)
        {
            $servicedetails = ServiceDetail::where('service_id',$id)->get();
            foreach($servicedetails as $row)
            {
                $servicetype = ServiceType::where('id',$row->service_type_id)->first();
                $servicetype->service_price = $row->service_price;
                $this->service_types->push($servicetype);
            }
            $this->service_addon = ServiceAddon::where('service_id', $id)->get();
        }
        if($this->service_types)
        {
            if(count($this->service_types ) > 0) 
            {
                $first = $this->service_types->first();
                if($first)
                {
                    $this->selected_type = $first->id;
                }
            }
        }
        $this->calculateTotal();
    }

    /* select services*/
    public function addItem()
    {
        if($this->service){
            if($this->selected_type != ''){
                $this->add($this->inputi);
                $this->selservices[$this->inputi]['service'] = $this->service->id;
                $this->selservices[$this->inputi]['service_type'] = $this->selected_type;
                $this->selservices[$this->inputi]['service_addon'] = $this->service_addon;
                $servicedetail = ServiceDetail::where('service_id',$this->service->id)->where('service_type_id',$this->selected_type)->first();

                /* if service details is not empty */
                if($servicedetail)
                {
                    $this->prices[$this->inputi] = $servicedetail->service_price;
                }
                $this->emit('closemodal');
                $this->calculateTotal();
            }else{
                $this->addError('service_error','Select a service type');
                return 0;
            }
        }
    }

    /* add the item to array */
    public function add($i)
    {
        $this->inputi = $i + 1;
        $this->inputs[$this->inputi] = 1;
        $this->prices[$this->inputi] = 100;
        $this->service_types[$this->inputi] = '';
        $this->quantity[$this->inputi] = 1;
        $this->colors[$this->inputi] = '';
    }

    /* increase the count */
    public function increase($key)
    {
        /* if quantity of key is exist */
        if(isset($this->quantity[$key] ))
        {
            $this->quantity[$key]++;
            $this->calculateTotal();
        }
    }

    /* decrease the count */
    public function decrease($key)
    {
        /* is quantity of key is exist */
        if(isset($this->quantity[$key] ))
        {
            if($this->quantity[$key] > 1) {
                /* if quantity of key is >1 */
                $this->quantity[$key]--;
            } else{
                /* unset the details if quantity of key is 1 */
                unset($this->quantity[$key]);
                unset($this->prices[$key]);
                unset($this->service_types[$key]);
                unset($this->selservices[$key]);
                unset($this->selected_addons[$key]);
                unset($this->selected_brands[$key]);
            }
            $this->calculateTotal();
        }
    }

    /* create customer */
    public function createCustomer()
    {   
        $refer_code = CommonHelper::myrefercode();
        /* validation */
        $this->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'email' => 'unique:customers|nullable'
            
        ]);
        $customer = Customer::create([
            'salutation' => $this->salutation,
            'name' => $this->customer_name,
            'dob' => $this->dob,
            'phone' => $this->customer_phone,
            'email' => $this->email,
            'tax_number' => $this->tax_no,
            'gst' => $this->gst,
            'company_name' => $this->company_name,
            'company_address' => $this->company_address,
            'locality' => $this->locality,
            'pin' => (int)$this->pin,
            'discount' => (int)$this->custdiscount,
            'rating' => (int)$this->rating,
            'address' => $this->address,
            'refer_code' => $refer_code,
            'is_active' => $this->is_active??0,
        ]);
        $this->selected_customer = $customer;
        $this->emit('closemodal');
        $this->dob = '';
        $this->salutation = '';
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->email = '';
        $this->tax_no = '';
        $this->gst = '';
        $this->company_name = '';
        $this->company_address = '';
        $this->locality = '';
        $this->pin = '';
        $this->custdiscount = '';
        $this->rating = '';
        $this->address = '';
        $this->is_active = 1;
    }

    /* select customer */
    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = collect();
        $this->orderhistory = Order::where('customer_id', $id)->get();
        $this->totalorderamount = Order::where('customer_id', $id)->sum('total');
        $this->totalorderrecvd = Payment::where('customer_id', $id)->sum('received_amount');
        $this->totalorderoutstanding = ($this->totalorderamount - $this->totalorderrecvd);
        $this->discount = $this->selected_customer['discount'];
        $this->custid = $this->selected_customer['id'];
        $this->custname = $this->selected_customer['name'];

        $membership_check = Customer::where('id', $id)->first();
        if($membership_check->membership != NULL){
            $membership_check = \App\Models\Membership::where('id',$membership_check->membership)->first();
            if($membership_check){
                $this->membershipimg = $membership_check->icon;
                if($membership_check->discount_type == 1){
                    $this->discnt = $membership_check->discount;
                }
                if($membership_check->discount_type == 2){
                    $this->cashback = $membership_check->discount;
                }
                
            }
        }
    }

    public function updatedPickupoption($value)
    {
        if($value != 2){
            $this->pickupaddress = '';
            $this->outlet = Auth::user()->outlet_id;
        } else{
            $this->outlet = '';
        }
    }

    public function updatedDeliveryoption($value)
    {
        if($value != 2){
            $this->deliveryaddress = '';
            $this->outlet = Auth::user()->outlet_id;
        }  else{
            $this->outlet = '';
        }
    }

    public function updatedGst($value)
    {
        if($value == '')
        {
            $this->company_name = '';
            $this->company_address = '';
        }
    }

    /*get delivery value */
    public function updatedDelivery($value)
    {
        if($value != 0){
            $this->deliverychrg = DeliveryType::where('id',$value)->first()->amount;
            $this->deliverytype = DeliveryType::where('id',$value)->first()->type;
            $this->deliveryday = DeliveryType::where('id',$value)->first()->delivery_in_days;
            $this->deliveryname = DeliveryType::where('id',$value)->first()->delivery_name;
            $this->delivery_date = date('d-m-Y');
            $this->delivery_date = date('Y-m-d', strtotime($this->delivery_date. ' + '. $this->deliveryday. 'days'));
            $this->cutoffamount = DeliveryType::where('id',$value)->first()->cut_off_amount;
            $this->cutoffchrge = DeliveryType::where('id',$value)->first()->cut_off_charge;
        }else{
            $this->deliverychrg = 0;
            $this->deliverychrgamnt = 0;
            $this->cutoffchrge = 0;
            $this->delivery_date = '';
        }
        $this->calculateTotal();
    }

    /* generate order Id */
    public function generateOrderID()
    {
        $this->order_id = $this->order->order_number;
    }

    /* calculate service total */
    public function calculateTotal()
    {
        $this->sub_total = 0;
        $this->addon_total = 0;

        foreach($this->prices as $key => $value) {
            $this->sub_total += $value*$this->quantity[$key];
        }
        /* if any addons selected */
        if($this->selected_addons) {
            foreach($this->selected_addons as $keyAddon => $selectedAddons) {
                if($selectedAddons) {
                    foreach ($selectedAddons as $key => $value) {
                        if ($value === true) {
                            $addon = Addon::where('id', $key)->first();
                            $this->addon_total += $addon->addon_price * $this->quantity[$keyAddon];
                        }
                    }
                }
            }
        }
        $this->addonsubtotal = $this->sub_total + $this->addon_total;

        if($this->custid != ''){
            $membership_customer = Customer::where('id', $this->custid)->first();
            $membership_check = \App\Models\Membership::where('id',$membership_customer->membership)->first();
        if($membership_check){
            $this->discount = $membership_check->discount;
        }else{
            $this->discount = 0;
        }
        if($this->addonsubtotal == 0){
            $this->deliverychrg = 0;
            $this->expresschrge =0;
            $this->deliverychrgamnt = 0;
            $this->cutoffchrge = 0;
            $this->delivery_date = '';
        }else{
            if($membership_check){
                if($this->addonsubtotal >= $this->cutoffamount) {
                    if($this->deliverytype == 'Flat'){
                        $discountexchrg = ($this->deliverychrg * $membership_check->express_fee)/100;
                        $getdiscountbal = ($this->deliverychrg - $discountexchrg);
                        if($this->delivery==3){
                            $this->expresschrge = $getdiscountbal;
                        }else{
                            $this->expresschrge = $this->deliverychrg;
                        }
                        $this->deliverychrgamnt = 0;
                    }
                    if($this->deliverytype == 'Percentage'){
                        $previouschrge = ($this->sub_total + $this->addon_total) * $this->deliverychrg / 100;
                        $discountexchrg = ($previouschrge * $membership_check->express_fee)/100;
                        $getdiscountbal = ($previouschrge - $discountexchrg);
                        if($this->delivery==3){
                            $this->expresschrge = $getdiscountbal;
                        }else{
                            $this->expresschrge = $previouschrge;
                        } 
                        $this->deliverychrgamnt = 0;
                    }
                }
                else{
                    if($this->deliverytype == 'Flat'){
                      $discountexchrg = ($this->deliverychrg * $membership_check->express_fee)/100;
                      $getdiscountbal = ($this->deliverychrg - $discountexchrg); 
                      if($this->delivery==3){
                        $this->expresschrge = $getdiscountbal;
                      }else{
                        $this->expresschrge = $this->deliverychrg;
                      }
                      if($membership_check->delivery_fee == 1){
                      $this->deliverychrgamnt = $this->cutoffchrge;
                      }else{
                        $this->deliverychrgamnt = 0;
                      }
    
                    }
                    if($this->deliverytype == 'Percentage'){
                        //$this->expresschrge = ($this->sub_total + $this->addon_total) * $this->deliverychrg / 100;
                        $previouschrge = ($this->sub_total + $this->addon_total) * $this->deliverychrg / 100;
                        $discountexchrg = ($previouschrge * $membership_check->express_fee)/100;
                        $getdiscountbal = ($previouschrge - $discountexchrg);
                        if($this->delivery==3){
                            $this->expresschrge = $getdiscountbal;
                        }else{
                            $this->expresschrge = $previouschrge;
                        }
                        if($membership_check->delivery_fee == 1){
                            $this->deliverychrgamnt = $this->cutoffchrge;
                        }else{
                            $this->deliverychrgamnt = 0;
                        }
                    }
                }
            }else{
                if($this->addonsubtotal >= $this->cutoffamount) {
                    if($this->deliverytype == 'Flat'){
                        $this->expresschrge = $this->deliverychrg;
                        $this->deliverychrgamnt = 0;
                    }
                    if($this->deliverytype == 'Percentage'){
                        $this->expresschrge = ($this->sub_total + $this->addon_total) * $this->deliverychrg / 100;
                        $this->deliverychrgamnt = 0;
                    }
                }
                else{
                    if($this->deliverytype == 'Flat'){
                      $this->expresschrge = $this->deliverychrg;
                      $this->deliverychrgamnt = $this->cutoffchrge;
    
                    }
                    if($this->deliverytype == 'Percentage'){
                        $this->expresschrge = ($this->sub_total + $this->addon_total) * $this->deliverychrg / 100;
                        $this->deliverychrgamnt = $this->cutoffchrge;
                    }
                }
            }
        }
        }

        $this->getcashback = ($this->sub_total + $this->addon_total + $this->expresschrge)*($this->cashback)/100;
        $this->getdiscount = ($this->sub_total + $this->addon_total + $this->expresschrge)*($this->discnt)/100;
        $this->total = $this->sub_total + $this->addon_total + $this->expresschrge - $this->getdiscount;

        $afterdis = $this->total + $this->deliverychrgamnt;
        $this->cgst = $afterdis * $this->cgst_percentage/100;
        $this->sgst = $afterdis * $this->sgst_percentage/100;
        $this->total = $afterdis + $this->cgst  + $this->sgst;
        $this->balance = $this->total - $this->paid_amount;

        $now = date('Y-m-d');
        $getvoucher = Voucher::where('code', $this->voucher)->where('is_active', '=', 1)->where('valid_from', '<', $now)->where('valid_to', '>', $now)->first();

        if($getvoucher == '') {
            $this->vouamnt = 0;
        } else {
            $this->resetValidation('voucher');

            $this->voucherid = $getvoucher->id;
            $distype = $getvoucher->discount_type;
            $discountamt = $getvoucher->discount_amount;

            $userused = Order::where('customer_id', $this->custid)->where('voucher_id', $this->voucherid)->get();
            $useCount = count($userused);

            $totalused = Order::where('voucher_id', $this->voucherid)->get();
            $totaluseCount = count($totalused);
            $this->total = $this->sub_total + $this->addon_total + $this->deliverychrgamnt + $this->expresschrge - $this->getdiscount;

            if($distype == 1 && $useCount < $getvoucher->each_user_useable && $getvoucher->total_useable > $totaluseCount && $this->total > $getvoucher->cutoff_amount) {
                $this->getdiscount = ($this->sub_total + $this->addon_total + $this->expresschrge )*($this->discnt)/100;
                $this->total = $this->sub_total + $this->addon_total + $this->expresschrge - $this->getdiscount;
                $this->vouamnt = $this->total * $getvoucher->discount_amount/100;
                $this->voucherdiscount = $this->total - $this->vouamnt;

                $afterdiscount = $this->voucherdiscount + $this->deliverychrgamnt;
                $this->cgst = $afterdiscount * $this->cgst_percentage/100;
                $this->sgst = $afterdiscount * $this->sgst_percentage/100;
                $this->total = $afterdiscount + $this->cgst + $this->sgst;
                $this->balance = $this->total - $this->paid_amount;

            } elseif($distype == 2 &&  $useCount < $getvoucher->each_user_useable && $getvoucher->total_useable > $totaluseCount && $this->total > $getvoucher->cutoff_amount) {
                $this->getdiscount = ($this->sub_total + $this->addon_total + $this->expresschrge)*($this->discnt)/100;
                $this->total = ($this->sub_total + $this->addon_total + $this->expresschrge) - $this->getdiscount;
                $this->vouamnt = $getvoucher->discount_amount;
                $this->voucherdiscount = $this->total - $this->vouamnt;

                $afterdiscount = $this->voucherdiscount + $this->deliverychrgamnt;
                $this->cgst = $afterdiscount * $this->cgst_percentage/100;
                $this->sgst = $afterdiscount * $this->sgst_percentage/100;
                $this->total = $afterdiscount + $this->cgst + this->sgst;
                $this->balance = $this->total - $this->paid_amount;

            } else{
                $this->addError('voucher','Voucher not valid');
                $this->total = $this->total+ $this->sgst + $this->cgst;
                $this->vouamnt = 0;
            }
        }
    }

    public function updatedOutlet($value)
    {
        $workstation = Outlet::where('id', $value)->first();
        $this->workstation = $workstation->workstation_id ?? null;
    }

    /* save the order */
    public function save()
    {
        $amount = 0;
        $this->calculateTotal();
        $this->validate([
            'outlet' => 'required',
            'payment_type' => 'required',
            'delivery' => 'required'
        ]);

        /* if selected services > 0  send error alert*/
        if(!count($this->selservices) > 0)
        {
            $this->addError('error','Select a service');
            return 0;
        }

        /* if balance is <0 send error alert*/
        if($this->balance < 0)
        {
            $this->addError('paid_amount','Paid Amount cannot be greater than total.');
            return 0;
        }

        /* if customer not exist and has any balance to pay send the error alert */
        if($this->balance != 0 && $this->selected_customer == null)
        {
            $this->addError('paid_amount','The customer must be registered to use ledger.');
            return 0;
        }

        /* if balance is already paid */
        $this->Payment  = Payment::where('order_id',$this->order->id)->sum('received_amount');
        

        if($this->vouamnt != 0)
        {
            $this->voucer_id = $this->voucherid;
            $this->voucher_code = $this->vouchercode;
            $this->vouchr_amount = $this->vouamnt;
        }

        if($this->pickupaddress > 0)
        {
            $pickupadd = CustomerAddresses::where('id', $this->pickupaddress)->first();

            $pickupflat = $pickupadd->flat_number;
            $pickuparea = $pickupadd->area;
            $pickupaddr = $pickupadd->address;
            $pickuproute = $pickupadd->route_suggestion;
            $pickupaddress_type = $pickupadd->address_type;
            $pickupother = $pickupadd->other;
            $pickuplatitude = $pickupadd->latitude;
            $pickuplongitude = $pickupadd->longitude;
            $pickuppincode = $pickupadd->pincode;
        }

        if($this->deliveryaddress > 0)
        {
            $deliveryadd = CustomerAddresses::where('id', $this->deliveryaddress)->first();
            $deliveryflat = $deliveryadd->flat_number;
            $deliveryarea = $deliveryadd->area;
            $deliveryaddr = $deliveryadd->address;
            $deliveryroute = $deliveryadd->route_suggestion;
            $deliveryaddress_type = $deliveryadd->address_type;
            $deliveryother = $deliveryadd->other;
            $deliverylatitude = $deliveryadd->latitude;
            $deliverylongitude = $deliveryadd->longitude;
            $deliverypincode = $deliveryadd->pincode;
        }

        // status check for admin and outlet
        if(Auth::user()->user_type==2 && $this->pickupoption == 1){
            $status = 2;
        }else{
            $status = 0;
        } 

        $this->generateOrderID();
        if($this->flag == 0)
        {
            $order = Order::where('id', $this->order->id)->update([
                'order_number' => $this->order_id,
                'outlet_id' => $this->outlet,
                'delivery_outlet_id' => $this->delivery_outlet ?? 0,
                'workstation_id' => $this->workstation ?? 0,
                'customer_id' => $this->selected_customer->id ?? null,
                'customer_name' => $this->selected_customer->name ?? null,
                'voucher_id' => (int)$this->voucer_id,
                'voucher_code' => $this->voucher_code,
                'voucher_discount' => $this->vouchr_amount ?? 0,
                'phone_number' => $this->selected_customer->phone ?? null,
                'order_date' => Carbon::parse($this->date)->toDateTimeString(),
                'delivery_type' => $this->deliveryname,
                'delivery_date' => Carbon::parse($this->delivery_date)->toDateTimeString(),

                'pickup_flat_number' => $pickupflat ?? null,
                'pickup_area' => $pickuparea ?? null,
                'pickup_address' => $pickupaddr ?? null,
                'pickup_route_suggestion' => $pickuproute ?? null,
                'pickup_address_type' => $pickupaddress_type ?? null,
                'pickup_other' => $pickupother ?? null,
                'pickup_latitude' => $pickuplatitude ?? null,
                'pickup_longitude' => $pickuplongitude ?? null,
                'pickup_pincode' => $pickuppincode ?? null,
                
                'delivery_flat_number' => $deliveryflat ?? null,
                'delivery_area' => $deliveryarea ?? null,
                'delivery_address' => $deliveryaddr ?? null,
                'delivery_route_suggestion' => $deliveryroute ?? null,
                'delivery_address_type' => $deliveryaddress_type ?? null,
                'delivery_other' => $deliveryother ?? null,
                'delivery_latitude' => $deliverylatitude ?? null,
                'delivery_longitude' => $deliverylongitude ?? null,
                'delivery_pincode' => $deliverypincode ?? null,
                
                'sub_total' => $this->sub_total,
                'addon_total' => $this->addon_total,
                'express_charge' => $this->expresschrge,
                'delivery_charge' => $this->deliverychrgamnt,
                'discount' => $this->discount??0,
                'cashback_amount' => $this->getcashback ?? 0.00,
                'cgst_percentage' => $this->cgst_percentage,
                'sgst_percentage' => $this->sgst_percentage,
                'cgst_amount' => $this->cgst,
                'sgst_amount' => $this->sgst,
                'total' => $this->total,
                'note' => $this->payment_notes,
                'instruction' => $this->instruction,
                'status' => $status,
                'order_type' => 1,
                'created_by' => Auth::user()->id,
                'financial_year_id' => (int)getFinancialYearId()
            ]);

            //Delete Details Data
            OrderDetailsDetail::where('order_id', $this->order->id)->delete();
            OrderAddonDetail::where('order_id', $this->order->id)->delete();
            OrderDetails::where('order_id', $this->order->id)->delete();

            $i = 0;
            foreach($this->selservices as $key => $value)
            {
                if(!isset($this->selected_brands[$key])){
                    $selectedbrand = null;
                }else{
                    $selectedbrand = $this->selected_brands[$key];
                }
                if($selectedbrand){
                    $brand = Brand::where('id', $selectedbrand)->first();
                }
                $service = Service::where('id',$value['service'])->first();
                $service_type = ServiceType::where('id',$value['service_type'])->first();
                $service_type_detail = ServiceDetail::where('service_type_id',$service_type->id)->first();
                $amount += $this->prices[$key];
                $orderdetails = OrderDetails::create([
                    'order_id' => $this->order->id,
                    'service_id' => $service->id,
                    'service_type_id' => $service_type->id,
                    'service_name' => $service_type->service_type_name,
                    'service_quantity' => $this->quantity[$key],
                    'service_detail_total' => $this->prices[$key]*$this->quantity[$key],
                    'service_price' => $this->prices[$key],
                    'color_code' => $this->colors[$key],
                    'brand_id' => $selectedbrand,
                    'brand' => $brand->brand_name ?? '',
                ]);

                for($j = 1; $j <= $this->quantity[$key]; $j++){
                    OrderDetailsDetail::create([
                        'order_id' => $orderdetails->order_id,
                        'order_detail_id' => $orderdetails->id,
                        'garment_tag_id' => $this->order_id .'-'.($j+$i),
                        'image' => null,
                        'remarks' => null,
                        'is_active' => 0,
                        'accepted' => 0,
                        'status' => $this->order->status,
                    ]);
                }
                $i = $this->quantity[$key]+$i;
                
                if($this->selected_addons)
                {
                    if(isset($this->selected_addons[$key]))
                    {
                        foreach($this->selected_addons[$key] as $key => $value)
                        { 
                            if($value === true)
                            {
                                $addon = Addon::where('id',$key)->first();
                                OrderAddonDetail::create([
                                    'order_id' => $this->order->id,
                                    'order_detail_id' => $orderdetails->id,
                                    'addon_id' => $addon->id,
                                    'addon_name' => $addon->addon_name,
                                    'addon_price' => $addon->addon_price,
                                ]);
                            }
                        }
                    }
                }
            }
            
            if($this->voucer_id){
                $this->getvoucher = Voucher::where('id',$this->voucer_id)->first();
                $usedvoucher = $this->getvoucher->total_used + 1 ;
                $this->getvoucher->total_used = $usedvoucher;
                $this->getvoucher->save();
            }
            
            //Add payment
            if($this->total > $this->Payment)
            {
                Payment::create([
                    'payment_date' => $this->date,
                    'customer_id' => $this->selected_customer->id ?? null,
                    'customer_name' => $this->selected_customer->name ?? null,
                    'order_id' => $this->order->id,
                    'payment_type' => $this->payment_type,
                    'payment_note' => $this->payment_notes,
                    'financial_year_id' => (int)getFinancialYearId(),
                    'received_amount' => $this->paid_amount ?? 0,
                    'created_by' => Auth::user()->id,
                ]);
            }

            //credit to wallet amount
            $wallet_rcv_amount = \App\Models\Wallet::where('order_id',$this->order->id)->sum('receive_amount');
            
            if($this->Payment > $this->total){
                \App\Models\Wallet::create([
                    'receive_amount' => $this->Payment - $this->total - $wallet_rcv_amount,
                    'customer_id' => $this->selected_customer->id ?? null,
                    'order_id' => $this->order->id,
                    'remarks' => 'Edit order',
                ]);
            }
            $this->flag = 1;
            if($this->selected_customer)
            {
                $message = sendOrderCreateSMS($this->order->id,$this->selected_customer->id);
                if($message){
                    $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => $message,'title'=>'SMS Error']);
                }
            }
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => $this->order->order_number.' Was Successfully Updated!']);
        }
        $this->emit('printPage',$this->order->id);
    }

    public function payment($id){
        $this->order = Order::where('id',$id)->first();
        $this->customer = Customer::where('id',$this->order->customer_id)->first();
        $this->customer_name = $this->customer->name ?? null;
        $this->paid_amount = Payment::where('order_id',$this->order->id)->sum('received_amount');
        $this->orderbalance = number_format($this->order->total - $this->paid_amount,2);
    }

    private function resetInputFields(){
        $this->orderbalance = '';
        $this->order = '';
        $this->customer = '';
        $this->payment_mode = "";
        $this->flat = '';
        $this->area = '';
        $this->addressnew = '';
        $this->landmark = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->addtype = '';
        $this->other = '';
        $this->custpincode = '';
    }

    public function addPayment() {
        /* if balance is < 0 */
        if($this->orderbalance < 0)
        {
            $this->addError('balance','Pls Provide Valid Amount.');
            return 0;
        }
        /* if the balance is > order total */
        if($this->orderbalance > $this->order->total)
        {
            $this->addError('balance','Paid Amount cannot be greater than total.');
            return 0;
        }
        if($this->order->status == 4)
        {
            return 0;
        }
        $this->validate([
            'payment_mode' => 'required',
        ]);
        /* if any balance */
        if($this->orderbalance)
        {
            Payment::create([
                'payment_date' => \Carbon\Carbon::today()->toDateString(),
                'customer_id' => $this->customer->id ?? null,
                'customer_name' => $this->customer->name ?? null,
                'order_id' => $this->order->id,
                'payment_type' => $this->payment_mode,
                'payment_note' => $this->note,
                'financial_year_id' => getFinancialYearId(),
                'received_amount' => $this->orderbalance,
                'created_by' => Auth::user()->id,
            ]);
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Payment Updated has been updated!']);
        }
    }

    public function adbookstore()
    {
        $this->validate([
            'flat' => 'required',
            'area' => 'required',
            'addressnew' => 'required',
            'custpincode' => 'required',
            'addtype' => 'required'
        ]);
        
        $customeraddress = new CustomerAddresses();
        $customeraddress->customer_id = $this->custid;
        $customeraddress->flat_number = $this->flat;
        $customeraddress->area = $this->area;
        $customeraddress->address = $this->addressnew;
        $customeraddress->route_suggestion = $this->landmark;
        $customeraddress->latitude = $this->latitude;
        $customeraddress->longitude = $this->longitude;
        $customeraddress->address_type = $this->addtype;
        $customeraddress->other = $this->other;
        $customeraddress->pincode = $this->custpincode;
        $customeraddress->save();
        $this->resetInputFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Address  has been added!']);
    }

    public function updatedVoucher($value)
    {
        $now = date('Y-m-d');
        $getvoucher = Voucher::where('code', $value)->where('is_active', '=', 1)->where('valid_from', '<', $now)->where('valid_to', '>', $now)->first();

        if($getvoucher == '') {
            $this->addError('voucher','No Voucher Found');
            $this->vouamnt = 0;
        } else {
            $this->resetValidation('voucher');

            $this->voucherid = $getvoucher->id;
            $this->vouchercode = $getvoucher->code;
            $distype = $getvoucher->discount_type;
            $discountamt = $getvoucher->discount_amount;

            $userused = Order::where('customer_id', $this->custid)->where('voucher_id', $this->voucherid)->get();
            $useCount = count($userused);

            $totalused = Order::where('voucher_id', $this->voucherid)->get();
            $totaluseCount = count($totalused);

            $this->total = $this->sub_total + $this->addon_total + $this->deliverychrgamnt - $this->getdiscount;
            if($distype == 1 && $useCount < $getvoucher->each_user_useable && $getvoucher->total_useable > $totaluseCount && $this->total > $getvoucher->cutoff_amount) {
                $this->addError('voucherfound','Voucher code applied');

                $this->getdiscount = ($this->sub_total + $this->addon_total + $this->expresschrge)*($this->discount)/100;

                $this->total = $this->sub_total + $this->addon_total + $this->expresschrge - $this->getdiscount;

                $this->vouamnt = $this->total * $getvoucher->discount_amount/100;
               
                $this->voucherdiscount = $this->total - $this->vouamnt;

                $afterdiscount = $this->voucherdiscount + $this->deliverychrgamnt;
                
                $this->cgst = $afterdiscount * $this->cgst_percentage/100;
                $this->sgst = $afterdiscount * $this->sgst_percentage/100;
                $this->total = $afterdiscount + $this->cgst + $this->sgst;
                $this->balance = $this->total - $this->paid_amount;

            } elseif($distype == 2 &&  $useCount < $getvoucher->each_user_useable && $getvoucher->total_useable > $totaluseCount && $this->total > $getvoucher->cutoff_amount) {
                $this->addError('voucherfound','Voucher code applied');
                $this->getdiscount = ($this->sub_total + $this->addon_total + $this->expresschrge)*($this->discount)/100;
                $this->total = ($this->sub_total + $this->addon_total + $this->expresschrge) - $this->getdiscount;
               
                $this->vouamnt = $getvoucher->discount_amount;
                $this->voucherdiscount = $this->total - $this->vouamnt;

                $afterdiscount = $this->voucherdiscount + $this->deliverychrgamnt;

                $this->cgst = $afterdiscount * $this->cgst_percentage/100;
                $this->sgst = $afterdiscount * $this->sgst_percentage/100;
                $this->total = $afterdiscount + $this->cgst+ $this->sgst;
                $this->balance = $this->total - $this->paid_amount;
            } else{
                $this->addError('voucher','Voucher not valid');
                $this->vouamnt = 0;
                $this->total = $this->total+$this->cgst + $this->sgst;
            }
        }
    }

    public function magicFill()
    {
        $this->Payment  = Payment::where('order_id',$this->order->id)->sum('received_amount');
        if($this->total > $this->Payment){
            $this->paid_amount = $this->total-$this->Payment;
        }else{
            $this->paid_amount = 0;
        }
       
    }

    //Reload page on clicking clearall
    public function clearAll()
    {
        $this->emit('reloadpage');
    }
}