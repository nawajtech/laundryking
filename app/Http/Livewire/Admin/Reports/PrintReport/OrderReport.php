<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use App\Models\Order;
class OrderReport extends Component
{
    public $from_date,$to_date, $outlet, $orders,$status=-1;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.order-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    /* process before render */
    public function mount($from_date = null,$to_date = null, $status = null) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->status = $status;
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
}