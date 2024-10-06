<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use App\Models\Order;
class WorkstationNoteReport extends Component
{
    public $orders,$workstation=0,$ordDetDet;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.workstationnote-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    
    /* process before render */
    public function mount($from_date = null,$to_date = null,$workstation = null) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->workstation;

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
}