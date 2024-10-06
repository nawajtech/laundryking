<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use App\Models\Workstation;
use App\Models\OrderDetailsDetail;

class WorkstationReport extends Component
{
    public $workstations, $from_date, $to_date, $rows, $ordDetDet, $workstation=0, $quantity, $lang, $outlet, $workstationid;
    
    /* render the page */
    public function render()
    {
        $this->report();
        return view('livewire.admin.reports.workstation-report');
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
        $order_details = OrderDetailsDetail::query();
        $order_details = $order_details->selectRaw('service_id, workstations.workstation_name as workstation_name,
        count(*) as total')
        ->join('orders', 'orders.id', '=', 'order_details_details.order_id')
        ->join('order_details', 'order_details.id', '=', 'order_details_details.order_detail_id')
        ->join('workstations', 'workstations.id', '=', 'orders.workstation_id');
        if($this->workstation) {
            $order_details = $order_details->where('orders.workstation_id', $this->workstation);
        }

        $this->ordDetDet = $order_details->whereDate('order_date','>=',$this->from_date)->whereDate('order_date','<=',$this->to_date)->groupBy('orders.workstation_id')->orderBy('total', 'DESC')->get();
    }

    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $workstation = $this->workstation;
        //dd($workstation);
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.workstation-report', compact('from_date','to_date','workstation'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "WorkstationReport_from_". $from_date .".pdf");
    }
}