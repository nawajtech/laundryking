<?php

namespace App\Http\Livewire\Admin\Reports;

use Livewire\Component;
use PDF;
use App\Models\Translation;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class SettlementReport extends Component
{
    public $from_date, $to_date, $orders,$payments, $isChecked, $status = -1, $lang, $customer_query , $selected_customer, $selected_customer_id, $customers, $selected_orders = [], $selected_orders_checked, $selected_payment, $orderbalance, $payment_mode, $note;
    /* render the page */
    public $selectAll = false;

    public function render()
    {
        return view('livewire.admin.reports.settlement-report');
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

        if(in_array($name, ['from_date', 'to_date', 'customer_query'])) {
            $this->selectAll = false;
            $this->selected_orders = [];
        }

        $this->report();
    }

    /* select customer */
    public function selectCustomer($id)
    {
        $this->selected_customer_id = $id;
        $this->selected_customer = Customer::where('id',$id)->first();
        $this->customer_query = '';
        $this->customers = collect();

        $this->report();
    }

    /* report section */
    public function report()
    {
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
        // dd( $this->orders);
    }

    public function UpdatedSelectAll(){
        if ($this->selectAll && count($this->orders) > 0) {
            $selected_orders = $this->orders->pluck('id')->toArray();
            $selected_orders_flip = array_flip($selected_orders);

            $this->selected_orders = array_map(function($val) { return true; }, $selected_orders_flip);
        } else {
            $this->selectAll = false;
            $this->selected_orders = [];
        }

        $this->calculation();
    }

    public function UpdatedSelectedOrders(){
        //Return only checked value
        $selected_orders = array_filter($this->selected_orders, function ($var) {
            return ($var == true);
        });

        $this->selected_orders = $selected_orders;

        if(count($this->orders) == count($selected_orders)){
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }

        $this->calculation();
    }

    public function performAction()
    {
        $this->calculation();
    }

    /* add paymentinformation */
    public function addPayment() {
        /* if balance is < 0 */
        if($this->orderbalance < 0) {
            $this->addError('orderbalance','Pls Provide Valid Amount.');
            return 0;
        }

        $orderbalance = $this->selected_orders_checked->sum('total') - $this->selected_payment->sum('received_amount');
        $orderbalance = number_format($orderbalance, 2, '.', '');
        /* if the balance is > order total */
        if($this->orderbalance > $orderbalance) {
            $this->addError('orderbalance','Paid Amount cannot be greater than total.');
            return 0;
        }

        $this->validate([
            'payment_mode' => 'required',
        ]);

        /* if any balance */
        if($this->orderbalance)
        {
            foreach ($this->selected_orders_checked as $sel_ord) {

                $payment = Payment::where('order_id', $sel_ord->id)->sum('received_amount');
                $received_amount = $sel_ord->total - $payment;

                if($received_amount) {
                    Payment::create([
                        'payment_date' => \Carbon\Carbon::today()->toDateString(),
                        'customer_id' => $sel_ord->customer_id ?? null,
                        'customer_name' => $sel_ord->customer_name ?? null,
                        'order_id' => $sel_ord->id,
                        'payment_type' => $this->payment_mode,
                        'payment_note' => $this->note,
                        'financial_year_id' => getFinancialYearId(),
                        'received_amount' => $received_amount,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Payment Updated has been updated!']);
        }
    }

    public function calculation(){
        $selected_orders = array_keys($this->selected_orders);
        $this->selected_orders_checked = Order::whereIn('id', $selected_orders)->get();
        $this->selected_payment = Payment::whereIn('order_id', $selected_orders)->get();
        $orderbalance = $this->selected_orders_checked->sum('total') - $this->selected_payment->sum('received_amount');
        $this->orderbalance = number_format($orderbalance, 2, '.', '');
    }

    /* download report */
    public function downloadFile()
    {
        $from_date = $this->from_date;
        $to_date = $this->to_date;
        // $customer_query = $this->customer_query;
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.settlement-report', compact('from_date', 'to_date',))->output();
        return response()->streamDownload(fn () => print($pdfContent), "OutstandingReport_from_" . $from_date . ".pdf");
    }
}