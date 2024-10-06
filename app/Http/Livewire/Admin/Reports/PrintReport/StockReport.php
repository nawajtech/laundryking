<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
class StockReport extends Component
{
    public $from_date,$to_date,$orders,$outlet = 0;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.stock-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    /* process before render */
    public function mount($from_date = null,$to_date = null, $outlet = null) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->outlet = $outlet;
        if($this->outlet == 0) {
            $this->orders = \App\Models\Order::whereDate('order_date','>=',$this->from_date)->whereDate('order_date','<=',$this->to_date)->latest()->groupBy('outlet_id')->get();
       } else {
            $this->orders = \App\Models\Order::whereDate('order_date','>=',$this->from_date)->whereDate('order_date','<=',$this->to_date)->where('outlet_id',$this->outlet)->latest()->groupBy('outlet_id')->get();
       }
    }
}