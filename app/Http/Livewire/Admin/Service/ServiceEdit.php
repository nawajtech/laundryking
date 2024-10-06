<?php
namespace App\Http\Livewire\Admin\Service;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\ServiceCategory;
use Livewire\Component;
use File;
use App\Models\ServiceType;
use App\Models\Addon;
use App\Models\ServiceAddon;
use App\Models\Translation;
class ServiceEdit extends Component
{
    public $services,$files,$imageicon,$inputs=[],$service_types, $selected_addons = [], $addons, $prices = [],$information, $servicetypes =[],$inputi=1,$service_category_id,$size, $service_name,$is_active=1,$service,$itempieces,$lang,$serviceitempieces, $garmentcode;

    /* render the page */
    public function render()
    {
        return view('livewire.admin.service.service-edit');
    }

    /* process before render */
    public function mount($id)
    {
        $this->addons = Addon::where('is_active', 1)->get();
        $selected_addons = ServiceAddon::where(array('service_id' => $id))->pluck('addon_id');
        foreach($selected_addons as $selected_addons) {
            $this->selected_addons[$selected_addons] = true;
        }
        $this->serviceitempieces = Service::$serviceitempieces;
        $files = File::files(public_path('assets/img/service-icons'));
        $i = 0;
        $this->service_types = ServiceType::latest()->get();
        foreach($files as $value)
        {
            $i++;
            $this->files[$i]['path'] = $value->getfilename();
        }
        $this->service = Service::where('id',$id)->first();
        /* if service is not exist */
        if(!$this->service)
        {
            abort(404);
        }
        $details = ServiceDetail::where('service_id',$this->service->id)->get();
        foreach($details as $row)
        {
            $this->add($this->inputi);
            $this->servicetypes[$this->inputi] = $row->service_type_id;
            $this->prices[$this->inputi] = $row->service_price;
        }

        $category = ServiceCategory::where('id',$this->service->service_category_id)->first();

        $this->service_addons = ServiceAddon::where('service_id',$this->service->id)->first();
        $this->service_category_id = $category->id;
        $this->service_name = $this->service->service_name;
        $this->size = $this->service->size;
        $this->garmentcode = $this->service->garment_code;
        $this->itempieces = $this->service->pieces;
        $this->information = $this->service->information;
        $this->is_active = $this->service->is_active;
        $this->imageicon['path'] = $this->service->icon;
        if(session()->has('selected_language'))
        {
            /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
    }

    /* select the icon */
    public function selectIcon($data)
    {
        $this->imageicon = $this->files[$data];
        $this->emit('closemodal');
    }

    /* add the content for upcoming process */
    public function add($i)
    {
        $i = $i + 1;
        $this->inputi = $i;
        array_push($this->inputs ,$i);
        $this->prices[$i]    = 100;
        $this->servicetypes[$i] = '';
    }

    /* save the service */
    public function save()
    {
        $this->validate([
            'servicetypes.*' => 'required',
            'prices.*'  => 'numeric|required',
            'service_name'  => 'required',
        ]);
        if(!$this->imageicon)
        {
            $this->addError('icon',"Please select an icon");
            return 1;
        }
        if(count($this->inputs) <= 0)
        {
            $this->addError('inputerror',"You must add atleast one service type");
            return 1;
        }
        $this->service->service_category_id = $this->service_category_id;
        $this->service->service_name = $this->service_name;
        $this->service->size = $this->size;
        $this->service->garment_code = $this->garmentcode;
        $this->service->pieces = $this->itempieces;
        $this->service->information = $this->information;
        $this->service->icon = $this->imageicon['path'];
        $this->service->is_active = $this->is_active ?? 0;
        $this->service->save();
        $details = ServiceDetail::where('service_id',$this->service->id)->delete();
        foreach($this->inputs as $key => $value)
        {
            $servicetype = ServiceType::where('id',$this->servicetypes[$value])->first();
            /* if service type is exist */
            if($servicetype)
            {
                ServiceDetail::create([
                    'service_id' => $this->service->id,
                    'service_type_id'    => $servicetype->id,
                    'service_price'  => $this->prices[$value],
                ]);
            }
        }
        //Addon
        foreach ($this->selected_addons as $key => $selected_addon){
            if($selected_addon == true){
                $exist_selected_addons = ServiceAddon::where(array('service_id' => $this->service->id, 'addon_id' => $key))->count();
                if($exist_selected_addons == 0){
                    \App\Models\ServiceAddon::create([
                        'service_id' => $this->service->id,
                        'addon_id' => $key,  
                    ]);
                }
            } else{
                \App\Models\ServiceAddon::where(array('addon_id' => $key))->delete();

            }
        }
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Service has been updated!']);
        return redirect('/admin/service');
    }

    /* remove the service */
    public function remove($id,$value)
    {   /* if the service input is exist */
        if(isset($this->inputs[$id]))
        {
            unset($this->inputs[$id]);
            unset($this->servicetypes[$value]);
            unset($this->prices[$value]);
        }
    }
}