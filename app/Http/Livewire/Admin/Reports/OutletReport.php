<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use App\Models\Outlet;
use App\Models\OrderDetailsDetail;

class OutletReport extends Component
{
    public $from_date, $to_date, $outlets, $rows, $ordDetDet, $outlet, $lang;
    
    /* render the page */
    public function render()
    {
        $this->report();

        return view('livewire.admin.reports.outlet-report');
    }

    /* processed before render */
    public function mount()
    {
        $this->from_date = \Carbon\Carbon::today()->toDateString();
        $this->to_date = \Carbon\Carbon::today()->toDateString();
        $this->outlets = Outlet::get();
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
        $order_details = $order_details->selectRaw('service_id, outlets.outlet_name as outlet_name, outlets.outlet_code as outlets_code,
        count(*) as total')
        ->join('orders', 'orders.id', '=', 'order_details_details.order_id')
        ->join('order_details', 'order_details.id', '=', 'order_details_details.order_detail_id')
        ->join('outlets', 'outlets.id', '=', 'orders.outlet_id');
        if($this->outlet) {
            $order_details = $order_details->where('orders.outlet_id', $this->outlet);
        }

        $this->ordDetDet = $order_details->whereDate('order_date','>=',$this->from_date)->whereDate('order_date','<=',$this->to_date)->groupBy('orders.outlet_id')->orderBy('total', 'DESC')->get();
    }

    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $outlet = $this->outlet;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.outlet-report', compact('from_date','to_date','outlet'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "outletReport_from_" . $from_date. ".pdf");
    }
}