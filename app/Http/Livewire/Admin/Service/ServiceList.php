<?php
namespace App\Http\Livewire\Admin\Service;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\Translation;
use Livewire\Component;
class ServiceList extends Component
{
    public $services,$search_query,$lang,$deleteId;

    /* render the page */
    public function render()
    {
        return view('livewire.admin.service.service-list');
    }

    /* process before render */
    public function mount()
    { 
        if(session()->has('selected_language'))
        {   /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
  
        $this->services = Service::with(['servicedetails' => function ($query) {
            $query->with('service_type');
        }])->latest()->get();
    }

    public function deleteID($id)
    {
        $this->deleteId = $id;
    }

    /* delete the service */
    public function delete()
    {
        $doesntExist = \App\Models\OrderDetails::where('service_id', $this->deleteId)->doesntExist();

        if ($doesntExist) {
            /* if addon has any childen */
            $addon = Service::where('id', $this->deleteId)->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Service deleted Successfully!']);
        } else {
            /* if addon has no children */
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Service deletion restricted!']);
        }
        $this->services = Service::latest()->get();
        $this->emit('closemodal');
    }

    /* process while update the content */
    public function updated($name,$value)
    {   /* if the updated element is search_query */
        if($name == 'search_query' && $value != '') {
            $this->services = Service::where('service_name', 'like' , '%'.$value.'%')->get();
        } elseif($name == 'search_query' && $value == ''){
            $this->services = Service::latest()->get();
        }
    }
}