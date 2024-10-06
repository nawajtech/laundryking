<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use App\Models\Order;
use App\Models\Payment;
class SettlementReport extends Component
{
    public $from_date,$to_date,$orders,$status=-1,$selected_customer_id;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.settlement-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    public function selectCustomer($id)
    {
        $this->selected_customer_id = $id;
        $this->selected_customer = Customer::where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = collect();

        $this->mount();
    }
    
    /* process before render */
    public function mount($from_date = null,$to_date = null, $status = null) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $customer_id = $this->selected_customer_id;
        $orders = Order::addSelect(['received_amount_sum' => Payment::selectRaw('sum(received_amount) as total')->whereColumn('order_id', 'orders.id')->groupBy('order_id')])
            ->whereNotIn('status', [10])
            ->whereDate('order_date', '>=', $this->from_date)
            ->whereDate('order_date', '<=', $this->to_date);
        if ($customer_id) {
            $orders = $orders->where('customer_id', $customer_id);
        }
        $orders = $orders->havingRaw('received_amount_sum < orders.total')
            ->groupBy('orders.id')
            ->latest()
            ->get();
        $this->orders = $orders;
    }
}