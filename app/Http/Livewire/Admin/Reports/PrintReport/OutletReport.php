<?php

namespace App\Http\Livewire\Admin\Reports\PrintReport;

use Livewire\Component;
use App\Models\OrderDetailsDetail;
use App\Models\Outlet;

class OutletReport extends Component
{
    public $orders, $outlet, $ordDetDet, $from_date, $to_date;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.outlet-report')
            ->extends('layouts.print-layout')
            ->section('content');
    }
    /* process before render */
    public function mount($outlet = null, $from_date = null,$to_date = null)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->outlets = Outlet::get();
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
}
