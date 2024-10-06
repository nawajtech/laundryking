<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use App\Models\Workstation;
use App\Models\OrderDetails;
use App\Models\Order;

class WorkstationNote extends Component
{
    public $workstations, $from_date, $to_date, $rows, $ordDetDet, $workstation=0, $quantity, $lang, $outlet, $workstationid;
    
    /* render the page */
    public function render()
    {
        $this->report();
        return view('livewire.admin.reports.workstationnote-report');
    }

    /* processed before render */
    public function mount()
    {
        $this->workstations = Workstation::get();
        $this->from_date = \Carbon\Carbon::today()->toDateString();
        $this->to_date = \Carbon\Carbon::today()->toDateString();
        if (session()->has('selected_language')) {
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    /*processed on update of the element */
    public function updated($name, $value)
    {
        $this->report();
    }
    
    /* report section */
    public function report()
    {
        $query = Order::query();
            if($this->workstation){
                $query->where('workstation_id', $this->workstation);
                }
            if($this->workstation){
                $query->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('workstation_id', $this->workstation);
                }else{
                $query->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date);
                }
        $this->ordDetDet = $query->get();
    }

    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $workstation = $this->workstation;
        //dd($workstation);
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.workstationnote-report', compact('from_date','to_date','workstation'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "WorkstationNoteReport_from_". $from_date .".pdf");
    }
}