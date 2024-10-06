<?php
namespace App\Http\Livewire\Admin\Customers;
use Livewire\Component;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use App\Models\Translation;
use App\Models\Country;
use App\Models\Membership;
use Carbon\Carbon;
use App\Http\Helper\CommonHelper;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;
use Auth;
class Customers extends Component
{
    public $customers,$customer, $total_customer,$active_customer,$inactive_customer,$new_customer, $default_country_code, $country_code, $memberships, $membrs, $name, $salutations, $salutation, $dob, $email, $tax_number,$gst, $company_name, $company_address, $locality, $pin, $rating, $is_active = 1, $phone, $address, $addressnew, $search, $customerid, $addressbook, $addressbookid, $flat, $area, $landmark, $latitude, $longitude, $addtype, $pincode, $other, $contrycode, $lang;
    public $inputs = [];
    public $i = 1;
    public $deleteId = '';
    public $editMode = false;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    
    /* rule settings*/
    protected $rules = [
        'name' => 'required',
        'email' => 'nullable|email|unique:customers',
        'phone' => 'required|integer|unique:customers',
        'pin' => 'required|integer',
    ];

    /* called before render */
    public function mount(){
        $this->customers = new EloquentCollection();
        $this->country_code = Country::get();
        $default_country= Country::where('country_code', 'IN')->first();
        $this->default_country_code =  $default_country->phone_code;
        $this->loadCustomers();
        $this->assign_membership = 0;
        $this->salutations = Customer::$salutations;
        $this->total_customer = Customer::count();
        $this->active_customer = Customer::where('is_active',1)->count();
        $this->inactive_customer = Customer::where('is_active',0)->count();
        $this->new_customer = Customer::whereMonth('created_at', Carbon::now()->month)->count();
        
        
        if(session()->has('selected_language'))
        { /* if session has selected laugage*/
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }

    }

    /* render the page */
    public function render()
    {
        $this->memberships = Membership::where('is_active', 1)->get();
        return view('livewire.admin.customers.customers');
    }

    /* reset input file */
    public function resetInputFields(){
        $this->customer = '';
        $this->name = '';
        $this->contrycode = '';
        $this->phone = '';
        $this->email ='';
        $this->tax_number = '';
        $this->gst = '';
        $this->salutation = '';
        $this->dob = '';
        $this->company_name = '';
        $this->company_address = '';
        $this->locality = '';
        $this->pin = '';
        $this->rating = '';
        $this->address = '';
        $this->addressbookid = '';
        $this->flat = '';
        $this->area = '';
        $this->addressnew = '';
        $this->landmark = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->addtype = '';
        $this->other = '';
        $this->pincode = '';
        $this->is_active = 1;
        $this->resetErrorBag();
    }

    /* store customer data */
    public function store()
    {
        /* if edit mode is false */
        $refer_code = CommonHelper::myrefercode();

        $this->validate();
        $date_of_birth = Carbon::parse($this->dob)->toDateTimeString();
        $today = Carbon::today()->toDateString();
        if($date_of_birth > $today)
        {
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Date of birth not greater than current date .']);
            return 0;
        }      
        $customer = new Customer();
        $customer->salutation = $this->salutation;
        $customer->name = $this->name;
        $customer->dob = $this->dob;
        $customer->country_code = $this->contrycode;
        $customer->phone = $this->phone;
        $customer->email = $this->email;
        $customer->tax_number = $this->tax_number;
        $customer->gst = $this->gst;
        $customer->company_name = $this->company_name;
        $customer->company_address = $this->company_address;
        $customer->locality = $this->locality;
        $customer->pin = (int)$this->pin;
        $customer->rating = (int)$this->rating;
        $customer->address = ($this->address);
        $customer->refer_code = $refer_code;
        $customer->created_by = Auth::user()->id;
        $customer->is_active = ($this->is_active)?"1":"0";
        $customer->save();
        $this->customers = Customer::latest()->get();
        $this->resetInputFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Customer  has been created!']);  
    }

    /* process while update */
    public function updated($name,$value)
    {
        if($name == 'search' && $value != '')
        {
            $this->customers = Customer::where('name', 'like','%'.$value)->latest()->get();
            $this->reloadCustomers();
        }elseif($name == 'search' && $value == ''){
            $this->customers = new EloquentCollection();
            $this->reloadCustomers();
        }

        /*if the updated element is address */
        if($name == 'address' && $value != '')
        {
            $this->address = $value;
        }
    }

    /* view customer details to update */
    public function edit($id)
    {
        $this->editMode = true;
        $this->customer = Customer::where('id',$id)->first();
        $this->customerid = $this->customer->id;
        $this->contrycode = $this->customer->country_code;
        $this->phone = $this->customer->phone;
        $this->email = $this->customer->email;
        $this->tax_number = $this->customer->tax_number;
        $this->gst = $this->customer->gst;
        $this->address = $this->customer->address;
        $this->name = $this->customer->name;
        $this->company_name = $this->customer->company_name;
        $this->company_address = $this->customer->company_address;
        $this->locality = $this->customer->locality;
        $this->pin = $this->customer->pin;
        $this->rating = $this->customer->rating;
        $this->salutation = $this->customer->salutation;
        $this->dob = $this->customer->dob;
        $this->is_active = $this->customer->is_active;
    }

    public function editadbook($id)
    {
        $this->editMode = true;
        $this->customer = Customer::where('id',$id)->first();
        $this->customerid = $this->customer->id;
        $this->name = $this->customer->name;

    }
    
    /* update customer details */
    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'nullable|email|',
            'phone' => 'required|',
            'pin' => 'required|integer',
        ]);
        $date_of_birth = Carbon::parse($this->dob)->toDateTimeString();
        $today = Carbon::today()->toDateString();
        if($date_of_birth > $today)
        {
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Date of birth not greater than current date .']);
            return 0;
        }   
        
        $this->customer->salutation = $this->salutation;
        $this->customer->dob = $this->dob;
        $this->customer->name = $this->name;
        $this->customer->country_code = $this->contrycode;
        $this->customer->phone = $this->phone;
        $this->customer->email = $this->email;
        $this->customer->tax_number = $this->tax_number;
        $this->customer->gst = $this->gst;
        $this->customer->company_name = $this->company_name;
        $this->customer->company_address = $this->company_address;
        $this->customer->locality = $this->locality;
        $this->customer->pin = $this->pin;
        $this->customer->rating = $this->rating;
        $this->customer->salutation = $this->salutation;

        $this->customer->address = $this->address;
        $this->customer->is_active = ($this->is_active)?"1":"0";
        
        $this->customer->save();
        $this->refresh();
        $this->resetInputFields();
        $this->editMode = false;
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Customer has been updated!']);
    }

    /* refresh the page */
    public function refresh()
    {
        /* if search query or order filter is empty */
        if( $this->search == '')
        {
            $this->customers = $this->customers->fresh();
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

    public function loadCustomers()
    {
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $customerlist = $this->filterdata();
        $this->customers->push(...$customerlist->items());
        if ($this->hasMorePages = $customerlist->hasMorePages()) {
            $this->nextCursor = $customerlist->nextCursor()->encode();
        }
        $this->currentCursor = $customerlist->cursor();
    }

    public function filterdata()
    {
        if($this->search || $this->search != ''){
            $customers = \App\Models\Customer::where('name','like','%'.$this->search.'%')
            ->latest()
            ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $customers;
        }else{
            $customers = \App\Models\Customer::latest()
            ->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));
            return $customers;
        }
    }

    public function toggle($id)
    {
        $customer = Customer::find($id);
        if($customer->is_active == 1)
        {
            $customer->is_active = 0;
        }
        elseif($customer->is_active == 0)
        {
            $customer->is_active = 1;
        }
        $customer->save();
    }
 
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function adbookstore()
    {
        $this->validate([
            'flat' => 'required',
            'area' => 'required',
            'addressnew' => 'required',
            'pincode' => 'required',
            'addtype' => 'required'
        ]);
        
        if($this->addressbookid){
            $customeraddress = CustomerAddresses::where('id',$this->addressbookid)->first();
        }else{
            
            $customeraddress = new CustomerAddresses();
        }
        $customeraddress->customer_id = $this->customerid;
        $customeraddress->flat_number = $this->flat;
        $customeraddress->area = $this->area;
        $customeraddress->address = $this->addressnew;
        $customeraddress->route_suggestion = $this->landmark;
        $customeraddress->latitude = $this->latitude;
        $customeraddress->longitude = $this->longitude;
        $customeraddress->address_type = $this->addtype;
        $customeraddress->other = $this->other;
        $customeraddress->pincode = $this->pincode;
        $customeraddress->save();
        $this->resetInputFields();
        //$this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Address  has been added!']);
    }

    public function closemodal()
    {
        $this->resetInputFields();
        $this->emit('closemodal');
    }

    public function editaddressbook($id)
    {
        $customeraddress = CustomerAddresses::findOrFail($id);
        //dd($customeraddress);
        $this->addressbookid = $customeraddress->id;
        $this->customerid = $customeraddress->customer_id;
        $this->flat = $customeraddress->flat_number;
        $this->area = $customeraddress->area;
        $this->addressnew = $customeraddress->address;
        $this->landmark = $customeraddress->route_suggestion;
        $this->latitude = $customeraddress->latitude;
        $this->longitude = $customeraddress->longitude;
        $this->addtype = $customeraddress->address_type;
        $this->other = $customeraddress->other;
        $this->pincode = $customeraddress->pincode;
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    /* Addressbook delete */
    public function delete()
    {  
        $this->brand = CustomerAddresses::find($this->deleteId)->delete();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Addressbook deleted Successfully!']);
    }


    public function reloadCustomers()
    {
        $this->customers = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $customers = $this->filterdata();
        $this->customers->push(...$customers->items());
        if ($this->hasMorePages = $customers->hasMorePages()) {
            $this->nextCursor = $customers->nextCursor()->encode();
        }
        $this->currentCursor = $customers->cursor();
    }

    public function membershipinsert($id)
    {
        $this->editMode = true;
        $this->customer = Customer::where('id',$id)->first();
        $this->customerid = $this->customer->id;
        $this->name = $this->customer->name;
        $this->membrs = $this->customer->membership;
    }

    public function assignmember()
    {
        $data['membership_start_date'] =  \Carbon\Carbon::today()->toDateString();
        $data['membership'] = $this->membrs;
        Customer::where('id',$this->customerid)->update($data);


        \App\Models\CustomerMembershipLog::create([
            'membership_id' => $this->membrs,
            'customer_id' => $this->customerid,
            'membership_start_date' => \Carbon\Carbon::today()->toDateString(),
        ]);
        $this->refresh();
    $this->resetInputFields();
    $this->emit('closemodal');
    $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Membership  has been added!']);
    }
  
}