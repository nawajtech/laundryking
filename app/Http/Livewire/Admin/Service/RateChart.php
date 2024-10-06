<?php
namespace App\Http\Livewire\Admin\Service;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\Translation;
use Livewire\Component;
class RateChart extends Component
{
    public $services,$search_query,$lang,$details,$service_category,$service_type,$service_price=[];
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    
    /* render the page */
    public function render()
    {
        return view('livewire.admin.service.rate-chart');
    }
  
    /* called before render */
    public function mount(){
       
        $this->details = ServiceDetail::whereHas('service')->whereHas('service_type')->with('service')->with('service_type')->orderBy('service_id')->get();
        foreach($this->details as $row)
        {
            $this->service_price[$row->id] = $row->service_price;
        }

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

    public function updated($name,$value)
    {   
        /* if the updated element is search_query */
        $query = ServiceDetail::query();
        
        $query->whereHas('service', function($q) {
            if($this->search_query){
                $q->where('service_name', 'like' , '%'.$this->search_query.'%');
            }
            if($this->service_category){
                $q->where('service_category_id', $this->service_category);
            }
        })->with('service')->with('service_type');

        if($this->service_type){
            $query->where('service_type_id', $this->service_type);
        }
        
        $this->details = $query->orderBy('service_id')->get();
    }

    public function updateServicePrice($id)
    {
        if($this->service_price[$id]){
            $service_details = ServiceDetail::find($id);
            $service_details->service_price = $this->service_price[$id];
            $service_details->save();

            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Service Price has been updated!']);
        }
    }

}