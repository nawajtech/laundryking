<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use \App\Models\Order;
use \App\Models\Outlet;
use App\Models\Translation;

class OrderReport extends Component
{
    public $from_date,$outlet=0, $to_date, $orders, $status = -1, $lang;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.reports.order-report');
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

        if ($this->outlet != 0 && $this->status != -1) {
            $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('outlet_id', $this->outlet)->where('status', $this->status)->latest()->get();
        } elseif($this->outlet != 0) {
            $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('outlet_id', $this->outlet)->latest()->get();
        } elseif($this->status != -1) {
            $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('status', $this->status)->latest()->get();
        } else{
            $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->latest()->get();
        }
    }
    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $status = $this->status;
        $outlet = $this->outlet;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.order-report', compact('from_date', 'to_date', 'status','outlet'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "OrderReport_from_" . $from_date . ".pdf");
    }
}