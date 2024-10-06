<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use \App\Models\OrderDetails;
use \App\Models\Order;
use \App\Models\Outlet;

class RewashReport extends Component
{
    public $from_date, $to_date, $orderDet, $lang,$query, $outlets, $outlet=0;
    /* render the page */
    public function render()
    {
        $this->report();

        return view('livewire.admin.reports.rewash-report');
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

        $order_details = OrderDetails::query()
        ->selectRaw('service_id, outlets.outlet_name as outlet_name,services.service_name as service_name,
        count(*) as total')
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->join('outlets', 'outlets.id', '=', 'orders.outlet_id')
        ->join('services', 'services.id', '=', 'order_details.service_id');


        if($this->outlet) {
            $order_details = $order_details->where('orders.outlet_id', $this->outlet);
        }

        $order_details = $order_details->whereDate('order_date','>=',$this->from_date)->whereDate('order_date','<=',$this->to_date)->whereNotNull('parent_id');
        $this->orderDet = $order_details->groupBy('order_details.service_id')->get();
// dd($this->orderDet);

    }
    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $outlet = $this->outlet;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.rewash-report', compact('from_date', 'to_date', 'outlet'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "RewashReport_from_" . $from_date . ".pdf");
    }
}