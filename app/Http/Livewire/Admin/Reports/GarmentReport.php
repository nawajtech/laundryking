<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use DB;
use App\Models\Translation;
use App\Models\OrderDetailsDetail;
use App\Models\OrderDetails;
use App\Models\Outlet;
use App\Models\Service;

class GarmentReport extends Component
{
    public $from_date, $to_date, $outlet=0, $ordDetDet, $lang, $count, $service_price;
    
    /* render the page */
    public function render()
    {
        $this->report();

        return view('livewire.admin.reports.garment_report');
    }

    /* processed before render */
    public function mount()
    {
        $this->outlets = Outlet::get();
        $this->from_date = \Carbon\Carbon::today()->toDateString();
        $this->to_date = \Carbon\Carbon::today()->toDateString();
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
        // $order_details = OrderDetailsDetail::query()
        // ->selectRaw('service_id, services.service_name as service_name, services.garment_code as garment_code,
        // sum(case when order_details_details.status = 3 then 1 else 0 end) as "3",
        // sum(case when order_details_details.status = 4 then 1 else 0 end) as "4",
        // sum(case when order_details_details.status = 5 then 1 else 0 end) as "5", 
        // sum(case when order_details_details.status = 6 then 1 else 0 end) as "6", 
        // sum(case when order_details_details.status = 7 then 1 else 0 end) as "7", 
        // count(*) as total')
        // ->join('order_details', 'order_details.id', '=', 'order_details_details.order_detail_id')
        // ->join('orders', 'orders.id', '=', 'order_details.order_id')
        // ->join('services', 'services.id', '=', 'order_details.service_id');

        // if($this->outlet) {
        //     $order_details = $order_details->where('orders.outlet_id', $this->outlet);
        // }

        // $order_details = $order_details->whereDate('order_date','>=',$this->from_date)->whereDate('order_date','<=',$this->to_date);
        // $this->ordDetDet = $order_details->groupBy('order_details.service_id')->get();

        $query = OrderDetails::query();

        $query->whereHas('order', function($q) {
            if($this->outlet){
                $q->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('outlet_id', $this->outlet);
                }else{
                $q->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date);
                }
            });
        
        $this->ordDetDet = $query->groupBy('service_id')->get();
    }

    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $outlet = $this->outlet;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.garment-report', compact('from_date', 'to_date', 'outlet'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "GarmentReport_from_" . $from_date . ".pdf");
    }
}