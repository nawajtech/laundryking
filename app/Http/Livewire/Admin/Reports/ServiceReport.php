<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use App\Models\Outlet;
use App\Models\ServiceCategory;
use App\Models\OrderDetails;

class ServiceReport extends Component
{
    public $outlets, $rows, $ordDetDet, $outlet=0, $category=0,  $lang, $from_date, $to_date;
    
    /* render the page */
    public function render()
    {
        return view('livewire.admin.reports.service-report');
    }

    /* processed before render */
    public function mount()
    {
        $this->from_date = \Carbon\Carbon::today()->toDateString();
        $this->to_date = \Carbon\Carbon::today()->toDateString();
        $this->outlets = Outlet::get();
        $this->categories = ServiceCategory::get();
        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
        $this->report();
    }

    /*processed on update of the element */
    public function updated($name, $value)
    {
        $this->report();
    }
    
    /* report section */
    public function report()
    {
        $query = OrderDetails::query();
        $query->whereHas('service', function($q) {
            if($this->category){
                $q->where('service_category_id', $this->category);
                }
            });
        $query->whereHas('order', function($q) {
            if($this->outlet){
                $q->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('outlet_id', $this->outlet);
                }else{
                $q->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date);
                }
            });
        $this->ordDetDet = $query->groupBy('service_type_id')->get();
           
    }

    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $category = $this->category;
        $outlet = $this->outlet;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.service-report', compact('from_date', 'to_date', 'category','outlet'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "serviceReport_from_". ".pdf");
    }
}