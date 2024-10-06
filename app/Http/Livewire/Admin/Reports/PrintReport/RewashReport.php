<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use \App\Models\OrderDetails;
class RewashReport extends Component
{
    public $from_date,$to_date,$orders,$outlet = 0;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.rewash-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    /* process before render */
    public function mount($from_date = null,$to_date = null, $outlet = null) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->outlet = $outlet;
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
    }
}