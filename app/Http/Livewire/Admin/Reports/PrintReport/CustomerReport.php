<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use App\Models\Order;
use App\Models\Payment;
class CustomerReport extends Component
{
    public $from_date,$to_date,$orders,$status=-1,$selected_customer_id;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.customer-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    public function selectCustomer($id)
    {
        $this->selected_customerid = $id;
        $this->selected_customer = Customer::where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = collect();
        
        $this->report();
    }
    
    /* process before render */
    public function mount($from_date = null,$to_date = null, $status = null) {
        
        $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->latest()->get();
        
        
        $this->payment = Payment::whereDate('payment_date', '>=', $this->from_date)->whereDate('payment_date', '<=', $this->to_date)->get();
        
    }
}