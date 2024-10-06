<?php
namespace App\Http\Livewire\Admin\Workstation;
use Livewire\Component;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Workstation;
use App\Models\Translation;
use App\Models\Order;

use Auth;
class Workstations extends Component
{
    public $workstation_name,$workstation_address,$workstation_phone,$workstations,$search,$lang,$floormanager;
    public $inputs = [];
    public $i = 1;
    public $deleteId = '';

    public $editMode = false;
     /* validation rules */
    protected $rules = [
        'workstation_name' => 'required',
        'workstation_address' => 'required',
        'workstation_phone' => 'required',

    ];

    /* called before render */
    public function mount(){
        $this->workstations = Workstation::latest()->get();
        
        if(session()->has('selected_language'))
        { /* if session has selected_language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
    }

    /* render the page */
    public function render()
    {
        $this->floormanager = User::where('user_type',3)->get();
        return view('livewire.admin.workstation.workstation');
    }

    /* reset input fields */
    public function resetInputFields(){
        $this->workstation_name = '';
        $this->workstation_address = '';
        $this->workstation_phone = '';
    }

    /* store expense category details */
    public function store()
    {
        /* if editmode is false */
        if($this->editMode == false)
        {
            $this->validate();
            $workstation = new Workstation();
            $workstation->workstation_name = $this->workstation_name;
            $workstation->address = $this->workstation_address;
            $workstation->phone = $this->workstation_phone;

            $workstation->save();
            
            $this->workstations = Workstation::latest()->get();
            
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Workstation has been created!']);
        }
    }
    /* set category type value while change the category type */
    
    /* process when update the element */
    public function updated($name,$value)
    {
        /* if the updated element is search */
        if($name == 'search' && $value != '')
        {
            $this->workstations = Workstation::where(function($query) use ($value) { 
                $query->where('workstation_name', 'like', '%' . $value . '%');
            })->get();   
        } else {
            $this->workstations = Workstation::latest()->get();
        }
    }

    /* set the content to edit */
    public function edit($id)
    {   
        $this->editMode = true;
        $this->workstation = Workstation::where('id',$id)->first();
        $this->workstation_name = $this->workstation->workstation_name;
        $this->workstation_address = $this->workstation->address;
        $this->workstation_phone = $this->workstation->phone;
       
    }

    /* Update Outlet*/
    public function update()
    {
        $this->validate();
        if($this->editMode == true)
        {
            $this->workstation->workstation_name = $this->workstation_name;
            $this->workstation->address = $this->workstation_address;
            $this->workstation->phone = $this->workstation_phone;

            $this->workstation->save();
           
            $this->workstations = Workstation::latest()->get();
            
            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Workstation has been updated!']);
        }
    }

    public function toggle($id)
    {
        $workstation = Workstation::find($id);
        if($workstation->is_active == 1)
        {
            $workstation->is_active = 0;
        }
        elseif($workstation->is_active == 0)
        {
            $workstation->is_active = 1;
        }
        $workstation->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    /* expense category delete */
    public function delete(){ 
        $doesntExist = Order::where('workstation_id', $this->deleteId)->doesntExist();

        if($doesntExist){
            /* if Workstation has any childen */
            $workstation = Workstation::where('id', $this->deleteId)->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Workstation  deleted Successfully!']);
        }else{
             /* if Workstation has no children */
             $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Workstation  deletion restricted!']);
        }
        $this->workstation = Workstation::latest()->get();
        $this->emit('closemodal');
    }

}