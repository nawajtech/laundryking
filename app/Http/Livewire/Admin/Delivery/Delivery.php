<?php
namespace App\Http\Livewire\Admin\Delivery;
use Livewire\Component;
use App\Models\DeliveryType;
use App\Models\Workstation;
use App\Models\Translation;
use Auth;
class Delivery extends Component
{
	public $delivery_name,$ratetype,$rate,$cutoffamount,$delivery_day,$search,$lang,$deliverytypes,$cutoffcharge,$pickuptimefrom,$pickuptimeto,$deliverytimefrom,$deliverytimeto ;

	public $editMode = false;
     /* validation rules */
    protected $rules = [
        'delivery_name' => 'required',
        'ratetype' => 'required',
        'rate' => 'required',
        'delivery_day' => 'required',

    ];

    /* called before render */
    public function mount(){
        $this->deliverytypes = DeliveryType::latest()->get();
        
        if(session()->has('selected_language'))
        { /* if session has selected_language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
    }

	public function render()
    {
        return view('livewire.admin.delivery.delivery');
    }

    public function resetInputFields()
    {
        $this->delivery_name = '';
        $this->ratetype = '';
        $this->rate = '';
        $this->cutoffamount = '';
        $this->cutoffcharge = '';
        $this->delivery_day = '';
        $this->pickuptimefrom = '';
        $this->pickuptimeto = '';
        $this->deliverytimefrom = '';
        $this->deliverytimeto = '';

    }

    public function store()
    {
        /* if editmode is false */
        if($this->editMode == false)
        {
            $this->validate();
            $deliverytype = new DeliveryType();
            $deliverytype->delivery_name = $this->delivery_name;
            $deliverytype->type = $this->ratetype;
            $deliverytype->amount = $this->rate;
            $deliverytype->cut_off_amount = $this->cutoffamount;
            $deliverytype->cut_off_charge = $this->cutoffcharge;
            $deliverytype->delivery_in_days = $this->delivery_day;
            $deliverytype->pickup_time_from = $this->pickuptimefrom;
            $deliverytype->pickup_time_to = $this->pickuptimeto;
            $deliverytype->delivery_time_from = $this->deliverytimefrom;
            $deliverytype->delivery_time_to = $this->deliverytimeto;
            $deliverytype->save();
            
            $this->deliverytypes = DeliveryType::where('is_active', 1)->latest()->get();
            
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Delivery type has been created!']);
        }
    }

    public function updated($name,$value)
    {
        /* if the updated element is search */
        if($name == 'search' && $value != '')
        {
            $this->deliverytypes = DeliveryType::where(function($query) use ($value) { 
                $query->where('delivery_name', 'like', '%' . $value . '%');
            })->get();   
        } else {
            $this->deliverytypes = DeliveryType::where('is_active', 1)->latest()->get();
        }
    }


    /* set the content to edit */
    public function edit($id)
    {   
        $this->editMode = true;
        $this->deliverytype = DeliveryType::where('id',$id)->first();
        $this->delivery_name = $this->deliverytype->delivery_name;
        $this->ratetype = $this->deliverytype->type;
        $this->rate = $this->deliverytype->amount;
        $this->cutoffamount = $this->deliverytype->cut_off_amount;
        $this->cutoffcharge = $this->deliverytype->cut_off_charge;
        $this->delivery_day = $this->deliverytype->delivery_in_days;
        $this->pickuptimefrom = $this->deliverytype->pickup_time_from;
        $this->pickuptimeto = $this->deliverytype->pickup_time_to;
        $this->deliverytimefrom = $this->deliverytype->delivery_time_from;
        $this->deliverytimeto = $this->deliverytype->delivery_time_to;
    }

    /* Update Outlet*/
    public function update()
    {
        $this->validate();
        if($this->editMode == true)
        {
            $this->deliverytype->delivery_name = $this->delivery_name;
            $this->deliverytype->type = $this->ratetype;
            $this->deliverytype->amount = $this->rate;
            $this->deliverytype->cut_off_amount = $this->cutoffamount;
            $this->deliverytype->cut_off_charge = $this->cutoffcharge;
            $this->deliverytype->delivery_in_days = $this->delivery_day;
            $this->deliverytype->pickup_time_from = $this->pickuptimefrom;
            $this->deliverytype->pickup_time_to = $this->pickuptimeto;
            $this->deliverytype->delivery_time_from = $this->deliverytimefrom;
            $this->deliverytype->delivery_time_to = $this->deliverytimeto;

            $this->deliverytype->save();
           
            $this->deliverytypes = DeliveryType::where('is_active', 1)->latest()->get();
            
            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Delivery type has been updated!']);
        }
    }

    public function toggle($id)
    {
        $deliverytype = DeliveryType::find($id);
        if($deliverytype->is_active == 1)
        {
            $deliverytype->is_active = 0;
        }
        elseif($deliverytype->is_active == 0)
        {
            $deliverytype->is_active = 1;
        }
        $deliverytype->save();
    }

    public function deleteID($id)
    {
        $this->deleteId = $id;
    }

    /* expense category delete */
    public function delete()
    {   

        $this->deliverytype = DeliveryType::find($this->deleteId)->delete();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Delivery type deleted Successfully!']);
   
        $this->deliverytypes = DeliveryType::where('is_active', 1)->latest()->get(); 
    }



}