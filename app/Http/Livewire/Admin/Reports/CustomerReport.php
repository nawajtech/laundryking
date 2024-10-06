<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Customer;
use App\Models\Payment;

class CustomerReport extends Component
{
    public $from_date, $wallet, $to_date, $orders,$payment ,$isChecked, $status = -1, $lang, $customer_query , $selected_customer, $selected_customerid, $customers;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.reports.customer-report');
    }
    /* processed before render */
    public function mount()
    {
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
        /* if the updated value is customer_query */
        if($name == 'customer_query' && $value != ''){
            $this->customers = Customer::where(function($query) use ($value) { 
                $query->where('name', 'like', '%' . $value . '%')->orWhere('phone', 'like', '%' . $value . '%')->orWhere('email', 'like', '%' . $value . '%');
            })->latest()->get();
        }elseif($name == 'customer_query' && $value == ''){
            $this->customers = collect();
        }
        // dd($this->customers);
        $this->report();
    }

    /* select customer */
    public function selectCustomer($id)
    {
        $this->selected_customerid = $id;
        $this->selected_customer = Customer::where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = collect();
        
        $this->report();
    }
    
    /* report section */
    public function report()
    {
        $customer_id = $this->selected_customerid;
        if($customer_id==''){
            $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->latest()->get();
        }
        else{
        $this->orders = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('customer_id',$customer_id)->latest()->get();
        }
        if($customer_id){
        $this->payment = Payment::whereDate('payment_date', '>=', $this->from_date)->whereDate('payment_date', '<=', $this->to_date)->where('customer_id',$customer_id)->get();
        }else{
        $this->payment = Payment::whereDate('payment_date', '>=', $this->from_date)->whereDate('payment_date', '<=', $this->to_date)->get();
        }
    }

    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.customer-report', compact('from_date', 'to_date'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "CustomerReport_from_" . $from_date . ".pdf");
    }
}