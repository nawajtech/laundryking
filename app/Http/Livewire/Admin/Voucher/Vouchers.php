<?php
namespace App\Http\Livewire\Admin\Voucher;
use Livewire\Component;
use App\Models\Voucher;
use App\Models\Pincode;
use App\Models\Customer;
use App\Http\Helper\CommonHelper;
use App\Models\Translation;
use App\Models\Order;
use Auth;
use File;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
class Vouchers extends Component
{
    use WithFileUploads;
    public $code,$no_of_users, $membership, $each_user_useable,$total_useable,$discount_type,$discount_amount,$cutoff_amount,$valid_from,$valid_to,$details,$vouchers,$photo,$showphoto,$search,$lang,$name;
    public $inputs = [];
    public $i = 1;
    public $deleteId = '';

    public $editMode = false;

    /* validation rules */
    protected $rules = [
        'code' => 'required|unique:vouchers,code',
        'no_of_users' => 'required',
        'each_user_useable' => 'required',
        'discount_type' => 'required',
        'discount_amount' => 'required',
        'cutoff_amount' => 'required',
        'valid_from' => 'required',
        'valid_to' => 'required',
        'photo' => 'image|mimes:jpg,jpeg,png,svg,gif|max:1024',
 
    ];

    /* called before render */
    public function mount()
    {
        $this->vouchers = Voucher::where('is_deleted', 0)->latest()->get();
        if(session()->has('selected_language')){ 
            /* if session has selected_language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }else{
            $this->lang = Translation::where('default',1)->first();
        }
    }

    /* render the page */
    public function render()
    {
        return view('livewire.admin.voucher.voucher');
    }

    public function resetInputFields(){
        $this->code = '';
        $this->no_of_users = '';
        $this->each_user_useable = '';
        $this->total_useable = '';
        $this->discount_type = '';
        $this->discount_amount = '';
        $this->cutoff_amount = '';
        $this->valid_from = '';
        $this->valid_to = '';
        $this->details = '';
        $this->photo = '';
    
    }

    public function store()
    {
        /* if editmode is false */
        if($this->editMode == false)
        {
            $this->validate();
            $filename = time().$this->photo->getClientOriginalName();
            $this->photo->storeAs('uploads/voucher', $filename, 'public');          
            $voucher = new Voucher();
            $voucher->code = $this->code;
            $voucher->membership = $this->membership;
            $voucher->no_of_users = $this->no_of_users;
            $voucher->each_user_useable = $this->each_user_useable;
            $voucher->total_useable = $this->total_useable;
            $voucher->discount_type = $this->discount_type;
            $voucher->discount_amount = $this->discount_amount;
            $voucher->cutoff_amount = $this->cutoff_amount;
            $voucher->valid_from = $this->valid_from;
            $voucher->valid_to = $this->valid_to;
            $voucher->details = $this->details;
            $voucher->image = $filename;
            $voucher->save(); 
            //push notification
            $customers = Customer::get();
            foreach ($customers as $c){
                $customer_id = $c->id;
                $user_type = 5;
                $title = "LK Voucher";
                $image = url('')."/uploads/voucher/".$voucher->image;
                $body ="Get ". $voucher->discount_amount. " discount for using Voucher Code ". $voucher->code." Valid Upto ".$voucher->valid_from ." - ".$voucher->valid_to;
                $data = array(
                    "type" => "Voucher",
            );
                $notification = CommonHelper::push_notification($title, $body, $user_type, $image,  $customer_id, $data);
            }
            $this->vouchers = Voucher::where('is_deleted', 0)->latest()->get();       
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Voucher has been created!']);
        }

    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function updatedEachUserUseable($each_user_useable)
    {
        $this->each_user_useable = $each_user_useable;

        $this->total_useable = (int)$this->no_of_users*(int)$this->each_user_useable;
    }

        /**
     * Write code on Method
     *
     * @return response()
     */
    public function updatedNoofUsers($no_of_users)
    {
        $this->no_of_users = $no_of_users;

        $this->total_useable = (int)$this->no_of_users*(int)$this->each_user_useable;
        
    }

    public function updated($name,$value)
    {
        /* if the updated element is search */
        if($name == 'search' && $value != '')
        {
            $this->vouchers = Voucher::where(function($query) use ($value) { 
                $query->where('code', 'like', '%' . $value . '%')->where('is_deleted', 0);
            })->get();   
        } else {
            $this->vouchers = Voucher::where('is_deleted', 0)->latest()->get(); 
        }
    }

    /* set the content to edit */
    public function edit($id)
    {   
        $this->editMode = true;
        $this->voucher = Voucher::where('id',$id)->first();
            $this->code = $this->voucher->code;
            $this->no_of_users = $this->voucher->no_of_users;
            $this->membership = $this->voucher->membership;
            $this->each_user_useable = $this->voucher->each_user_useable;
            $this->total_useable = $this->voucher->total_useable;
            $this->discount_type = $this->voucher->discount_type;
            $this->discount_amount = $this->voucher->discount_amount;
            $this->cutoff_amount = $this->voucher->cutoff_amount;
            $this->valid_from = $this->voucher->valid_from;
            $this->valid_to = $this->voucher->valid_to;
            $this->details = $this->voucher->details;
            $this->showphoto = $this->voucher->image;
    }

    public function update()
    {
        
        if($this->editMode == true)
        {
            
            if($this->photo == ''){
                $this->voucher->code = $this->code;
                $this->voucher->no_of_users = $this->no_of_users;
                $this->voucher->each_user_useable = $this->each_user_useable;
                $this->voucher->membership = $this->membership;
                $this->voucher->total_useable = $this->total_useable;
                $this->voucher->discount_type = $this->discount_type;
                $this->voucher->discount_amount = $this->discount_amount;
                $this->voucher->cutoff_amount = $this->cutoff_amount;
                $this->voucher->valid_from = $this->valid_from;
                $this->voucher->valid_to = $this->valid_to;
                $this->voucher->details = $this->details;
                $this->voucher->save();
            }else{
                $filename = time().$this->photo->getClientOriginalName();
                $this->photo->storeAs('uploads/voucher', $filename, 'public');      
                $this->voucher->code = $this->code;
                $this->voucher->no_of_users = $this->no_of_users;
                $this->voucher->each_user_useable = $this->each_user_useable;
                $this->voucher->membership = $this->membership;
                $this->voucher->total_useable = $this->total_useable;
                $this->voucher->discount_type = $this->discount_type;
                $this->voucher->discount_amount = $this->discount_amount;
                $this->voucher->cutoff_amount = $this->cutoff_amount;
                $this->voucher->valid_from = $this->valid_from;
                $this->voucher->valid_to = $this->valid_to;
                $this->voucher->details = $this->details;
                $this->voucher->image = $filename;
                $this->voucher->save();

            }
           
            $this->vouchers = Voucher::where('is_deleted', 0)->latest()->get(); 
            
            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Voucher has been updated!']);
        }
    }

    public function toggle($id)
    {
        $voucher = Voucher::find($id);
        if($voucher->is_active == 1)
        {
            $voucher->is_active = 0;
        }
        elseif($voucher->is_active == 0)
        {
            $voucher->is_active = 1;
        }
        $voucher->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function delete() {   
        $doesntExist = Order::where('voucher_id', $this->deleteId)->doesntExist();
        if($doesntExist){
            /* if order  has any childen */
            $voucher = Voucher::where('id', $this->deleteId)->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Voucher deleted Successfully!']);
        }else{
             /* if order has no children */
             $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Voucher deletion restricted!']);
        }
        $this->vouchers = Voucher::latest()->get();
        $this->emit('closemodal');
    }

}