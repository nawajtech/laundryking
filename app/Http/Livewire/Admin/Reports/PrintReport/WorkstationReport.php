<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use App\Models\OrderDetails;
class WorkstationReport extends Component
{
    public $orders,$workstation=0,$ordDetDet;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.workstation-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    
    /* process before render */
    public function mount($from_date = null,$to_date = null,$workstation = null) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->workstation;
        $order_details = \App\Models\OrderDetailsDetail::query();
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
}