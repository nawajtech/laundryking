<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\OrderDetailsDetail;

class StockReport extends Component
{
    public $from_date, $to_date, $orders, $status = -1, $lang, $ordersss , $outlets, $outlet=0;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.reports.stock-report');
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
        if($this->outlet){
            $this->orders = Order::where('outlet_id',$this->outlet)->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->groupBy('outlet_id')->get();
        }else{
            $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->groupBy('outlet_id')->get();
        }
        
    }
    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $outlet = $this->outlet;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.stock-report', compact('from_date', 'to_date', 'outlet'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "StockReport_from_" . $from_date . ".pdf");
    }
}