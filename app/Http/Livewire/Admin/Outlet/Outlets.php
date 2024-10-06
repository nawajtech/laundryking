<?php
namespace App\Http\Livewire\Admin\Outlet;
use Livewire\Component;
use App\Models\Outlet;
use App\Models\Pincode;
use App\Models\Workstation;
use App\Models\Translation;
use File;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Auth;

class Outlets extends Component
{
    use WithFileUploads;
    public $outlet_name,$outlet_code,$outlet_address,$outlet_phone,$outlet_latitude,$outlet_longitude,$google_map,$showphoto, $qr_code,$outlets,$search,$lang,$name,$pincode,$outlet_id,$showworkstation, $workstationid , $address_address, $address_latitude, $address_longitude;
    public $inputs = [];
    public $i = 1;
    public $deleteId = '';
    public $zoom;
    public $latlng;
    public $searchQuery;
    public $latitude;
    public $longitude;

    protected $queryString = [
        'zoom' => [
            'except' => 2,
            'as' => 'z',
        ],
        'latlng' => [
            'as' => 'p'
        ],
    ];

    public $editMode = false;
     /* validation rules */
    protected $rules = [
        'outlet_name' => 'required',
        'outlet_code' => 'required|unique:outlets,outlet_code',
        'outlet_phone' => 'required',
        'qr_code' => 'image|mimes:jpg,jpeg,png,svg,gif|max:1024',

    ];

    /* called before render */
    public function mount(){
        $this->outlets = Outlet::latest()->get();
        
        if(session()->has('selected_language'))
        { /* if session has selected_language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
        $this->zoom = request('z') ?? 2;
        $this->latlng = request('p') ?? '15,0';
    }

    /* render the page */
    public function render()
    {
        $this->showworkstation = Workstation::where('is_active',1)->get();

        return view('livewire.admin.outlet.outlet');
    }

    /* reset input fields */
    public function resetInputFields(){
        $this->outlet_name = '';
        $this->outlet_code = '';
        $this->outlet_address = '';
        $this->outlet_phone = '';
        $this->google_map = '';
        $this->qr_code = '';
        $this->outlet_latitude = '';
        $this->outlet_longitude = '';
        $this->is_active = 1;
        $this->staff = null;

        $this->name = '';
        $this->pincode = '';
    }

    /* store expense category details */
    public function store()
    {
        /* if editmode is false */
        if($this->editMode == false)
        {
            $this->validate();

            if($this->qr_code != ''){
                $filename = time().$this->qr_code->getClientOriginalName();
                $this->qr_code->storeAs('uploads/QrCode', $filename, 'public');  
            }

            $outlet = new Outlet();
            $outlet->outlet_name = $this->outlet_name;
            $outlet->outlet_code = $this->outlet_code;
            $outlet->workstation_id = $this->workstationid;
            $outlet->outlet_address = $this->outlet_address;
            $outlet->outlet_phone = $this->outlet_phone;
            $outlet->outlet_latitude = $this->outlet_latitude;
            $outlet->outlet_longitude = $this->outlet_longitude;
            $outlet->google_map = $this->google_map;
            $outlet->qr_code = $filename ?? null;

            $outlet->save();
            
            $this->outlets = Outlet::latest()->get();
            
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Outlet has been created!']);
        }
    }
    /* set category type value while change the category type */
    
    /* process when update the element */
    public function updated($name,$value)
    {
        /* if the updated element is search */
        if($name == 'search' && $value != '')
        {
            $this->outlets = Outlet::where(function($query) use ($value) { 
                $query->where('outlet_name', 'like', '%' . $value . '%');
            })->get();   
        } else {
            $this->outlets = Outlet::latest()->get();
        }
    }

    /* set the content to edit */
    public function edit($id)
    {   
        $this->resetInputFields();
        
        $this->editMode = true;
        $this->outlet = Outlet::where('id',$id)->first();
        $this->outlet_name = $this->outlet->outlet_name;
        $this->outlet_code = $this->outlet->outlet_code;
        $this->workstationid = $this->outlet->workstation_id;
        $this->outlet_address = $this->outlet->outlet_address;
        $this->outlet_phone = $this->outlet->outlet_phone;
        $this->outlet_latitude = $this->outlet->outlet_latitude;
        $this->outlet_longitude = $this->outlet->outlet_longitude;
        $this->google_map = $this->outlet->google_map;
        $this->showphoto = $this->outlet->qr_code;

    }

    /* Update Outlet*/
    public function update()
    {
        
        if($this->editMode == true)
        {
            if($this->qr_code == ''){
                $this->outlet->outlet_name = $this->outlet_name;
                $this->outlet->outlet_code = $this->outlet_code;
                $this->outlet->workstation_id = $this->workstationid;
                $this->outlet->outlet_address = $this->outlet_address;
                $this->outlet->outlet_phone = $this->outlet_phone;
                $this->outlet->outlet_latitude = $this->outlet_latitude;
                $this->outlet->outlet_longitude = $this->outlet_longitude;
                $this->outlet->google_map = $this->google_map;
                
                $this->outlet->save();
            }else{
                $filename = time().$this->qr_code->getClientOriginalName();
                $this->qr_code->storeAs('uploads/QrCode', $filename, 'public');  
                $this->outlet->outlet_name = $this->outlet_name;
                $this->outlet->outlet_code = $this->outlet_code;
                $this->outlet->workstation_id = $this->workstationid;
                $this->outlet->outlet_address = $this->outlet_address;
                $this->outlet->outlet_phone = $this->outlet_phone;
                $this->outlet->outlet_latitude = $this->outlet_latitude;
                $this->outlet->outlet_longitude = $this->outlet_longitude;
                $this->outlet->google_map = $this->google_map;
                $this->outlet->qr_code = $filename;

                $this->outlet->save();
            }
           
            $this->outlets = Outlet::latest()->get();
            
            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Outlet has been updated!']);
        }
    }

    public function toggle($id)
    {
        $outlet = Outlet::find($id);
        if($outlet->is_active == 1)
        {
            $outlet->is_active = 0;
        }
        elseif($outlet->is_active == 0)
        {
            $outlet->is_active = 1;
        }
        $outlet->save();
    }

    public function deleteID($id)
    {
        $this->deleteId = $id;
    }

    /* expense category delete */
    public function delete()
    {
        $doesntExist = \App\Models\Order::where(function ($query) {
            $query->where('outlet_id', $this->deleteId)
                ->orWhere('delivery_outlet_id', $this->deleteId);
        })->doesntExist();

        if ($doesntExist) {
            /* if expense category has any children */
            $this->outlet = Outlet::find($this->deleteId)->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Outlet deleted Successfully!']);
        } else {
            /* if addon has no children */
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Outlet deletion restricted!']);
        }
   
        $this->outlets = Outlet::latest()->get();
        $this->emit('closemodal');
    }

    /* set the content to pincode edit */
    public function editpincode($id)
    {
        $this->editMode = true;
        $this->outlet = Outlet::where('id',$id)->first();
        $this->outlet_id = $this->outlet->id;
        $this->outlet_name = $this->outlet->outlet_name;
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs, $i);
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public function pinstore()
    {
        $validatedDate = $this->validate([
                'pincode.0' => 'required',
                'name.0' => 'required',
                'pincode.*' => 'required',
                'name.*' => 'required',
            ],
            [
                'pincode.0.required' => 'pincode field is required',
                'name.0.required' => 'name field is required',
                'pincode.*.required' => 'pincode field is required',
                'name.*.required' => 'phone field is required',
            ]
        );
   
        foreach ($this->pincode as $key => $value) {
            Pincode::create(['outlet_id'=> $this->outlet_id, 'pincode' => $this->pincode[$key], 'place_name' => $this->name[$key]]);
        }
  
        $this->inputs = [];
   
        $this->resetInputFields();
   
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Pincode Added successfully']);
    }

    public function deletepin($id)
    {
        $this->pincode = Pincode::where('id',$id)->delete();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Pincode deleted Successfully!']);
   
        $this->pincode = Pincode::latest()->get(); 
    }

    public function searchLocation()
    {
        if($this->outlet_address != '')
        {
            $apiKey = 'AIzaSyChk1fHb6NCqRGvaSfmYRl0r-u7sCFSzYk';
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($this->outlet_address)."&key=".$apiKey;
            $response = json_decode(file_get_contents($url), true);

            if ($response['status'] == 'OK') {
                $this->outlet_latitude = $response['results'][0]['geometry']['location']['lat'];
                $this->outlet_longitude = $response['results'][0]['geometry']['location']['lng'];
            }
        }
    }
}