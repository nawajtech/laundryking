<?php
namespace App\Http\Livewire\Admin\Orders;
use Livewire\Component;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\MasterSettings;
use App\Models\Outlet;
use App\Models\Wallet;
use Auth;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;

class ViewOrders extends Component
{
    public $orders;
    public $paid_amount,$customer,$customer_name,$search_query,$outlet, $wallet_amount;
    public $order,$amount_to_pay,$note,$balance,$payment_mode,$order_filter,$outlet_filter,$lang;
    protected $queryString = ['search_query', 'outlet_filter', 'order_filter'];
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;


    protected $listeners = ['orderAdded' => 'incrementOrderCount'];

    public function incrementOrderCount()
    {
        $order = Order::query();

        if(Auth::user()->user_type == 1){

        }elseif(Auth::user()->user_type == 2){
            $order->where(function($q){
                $q->where('outlet_id', Auth::user()->outlet_id)
                    ->orWhere('delivery_outlet_id', Auth::user()->outlet_id);
            });
        }elseif(Auth::user()->user_type == 3){
            $order->where('workstation_id', Auth::user()->workstation_id);
        }else{
            $order->where('created_by', Auth::user()->id);
        }

        if($this->search_query || $this->search_query != '') {
            $order->where(function($q){
                $q->where('order_number','like','%'.$this->search_query.'%')
                    ->orwhere('customer_name','like','%'.$this->search_query.'%');
            });
        }

        if($this->order_filter || $this->order_filter != ''){
            $order->where('status', $this->order_filter);
        }

        if(Auth::user()->user_type != 2){
            if($this->outlet_filter || $this->outlet_filter != ''){
                $order->where(function($q){
                    $q->where('outlet_id', $this->outlet_filter)
                        ->orWhere('delivery_outlet_id',  $this->outlet_filter);
                });
            }
        }

        $orders = $order->latest()->limit(1)->get();
        //dd($orders);

        $this->orders->put(1, ...$orders);
    }


    /* render the page*/
    public function render()
    {
        return view('livewire.admin.orders.view-orders');
    }

    /* process before render */
    public function mount()
    {
        $this->orders = new EloquentCollection();
        $this->loadOrders();

        $this->outlet = Outlet::where('is_active',1)->latest()->get();

        if(session()->has('selected_language')){
            /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
    }

    /* process while update the content */
    public function updated($name,$value)
    {
        $this->orders = new EloquentCollection();
        $this->reloadOrders();
    }

    /* get paid information */
    public function payment($id){
        $this->order = Order::where('id',$id)->first();
        $this->customer = Customer::where('id',$this->order->customer_id)->first();
        $this->customer_name = $this->customer->name ?? null;
        $this->paid_amount = Payment::where('order_id',$this->order->id)->sum('received_amount');
        $this->balance = number_format($this->order->total - $this->paid_amount,2);

        $wallet_rcv_amount = Wallet::where('customer_id', $this->customer->id)->sum('receive_amount');
        $wallet_deduct_amount = Wallet::where('customer_id', $this->customer->id)->sum('deducted_amount');
        $this->wallet_amount = $wallet_rcv_amount - $wallet_deduct_amount;
    }

    /* reset input fields */
    private function resetInputFields(){
        $this->balance = '';
        $this->order = '';
        $this->customer = '';
        $this->payment_mode = "";
    }

    /* add payment information */
    public function addPayment() {
        /* if balance is < 0 */
        if($this->balance < 0)
        {
            $this->addError('balance','Pls Provide Valid Amount.');
            return 0;
        }
        $reedem_amount = MasterSettings::where('master_title','reedem_amount')->first();
        $this->reedemamnt = $reedem_amount->master_value;
        if($this->payment_mode ==6){
            if($this->wallet_amount < $this->reedemamnt)
            {
                $this->addError('wallet_amount','Wallet amount should be greater than '.$this->reedemamnt);
                return 0;
            }
        }
        /* if the balance is > order total */
        if($this->balance > $this->order->total - $this->paid_amount)
        {
            $this->addError('balance','Paid Amount cannot be greater than total.');
            return 0;
        }
        if($this->order->status == 4)
        {
            return 0;
        }
        $this->validate([
            'payment_mode' => 'required',
        ]);

        /* if any balance */
        $receive_amount = \App\Models\Wallet::where('customer_id',$this->customer->id)->sum('receive_amount');
        $deduct_amount = \App\Models\Wallet::where('customer_id',$this->customer->id)->sum('deducted_amount');
        $wallet = $receive_amount - $deduct_amount;
        if($this->balance)
        {
            if($this->balance && ($this->payment_mode !=6||$wallet > $this->balance))
            {
                \App\Models\Payment::create([
                    'payment_date' => \Carbon\Carbon::today()->toDateString(),
                    'customer_id' => $this->customer->id ?? null,
                    'customer_name' => $this->customer->name ?? null,
                    'order_id' =>  $this->order->id,
                    'payment_type' => $this->payment_mode,
                    'payment_note' => $this->note,
                    'financial_year_id' =>  getFinancialYearId(),
                    'received_amount' => $this->balance,
                    'created_by' => Auth::user()->id,
                ]);
            }
            if($this->balance && $this->payment_mode ==6)
            {
                if($wallet < $this->balance && $wallet>$this->reedemamnt){
                    \App\Models\Payment::create([
                        'payment_date' => \Carbon\Carbon::today()->toDateString(),
                        'customer_id' => $this->customer->id ?? null,
                        'customer_name' => $this->customer->name ?? null,
                        'order_id' =>  $this->order->id,
                        'payment_type' => $this->payment_mode,
                        'payment_note' => $this->note,
                        'financial_year_id' =>  getFinancialYearId(),
                        'received_amount' => $wallet,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }

            //for lk credits payment
            if($this->payment_mode==6 && $this->balance)
            {
                if($wallet >0){
                    if($wallet > $this->balance){
                        \App\Models\Wallet::create([
                            'deducted_amount' => $this->balance,
                            'customer_id' => $this->customer->id ?? null,
                            'remarks' => 'Add payment',
                        ]);
                    }
                    if($wallet < $this->balance){
                        \App\Models\Wallet::create([
                            'deducted_amount' => $wallet,
                            'customer_id' => $this->customer->id ?? null,
                            'remarks' => 'Add payment',
                        ]);
                    }
                }
            }
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Payment Updated has been updated!']);
        }
    }

    /* refresh the page */
    public function refresh()
    {
        /* if search query or order filter is empty */
        if( $this->search_query == '' || $this->order_filter == '' || $this->outlet_filter == '')
        {
            $this->orders->fresh();
            //$this->emit('orderAdded');
        }
    }

    public function loadOrders()
    {
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $myorder = $this->filterdata();
        $this->orders->push(...$myorder->items());
        if ($this->hasMorePages = $myorder->hasMorePages()) {
            $this->nextCursor = $myorder->nextCursor()->encode();
        }
        $this->currentCursor = $myorder->cursor();
    }

    public function reloadOrders()
    {
        $this->orders = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $orders = $this->filterdata();
        $this->orders->push(...$orders->items());
        if ($this->hasMorePages = $orders->hasMorePages()) {
            $this->nextCursor = $orders->nextCursor()->encode();
        }
        $this->currentCursor = $orders->cursor();
    }

    public function filterdata()
    {
        $order = Order::query();

        if(Auth::user()->user_type == 1){

        }elseif(Auth::user()->user_type == 2){
            $order->where(function($q){
                $q->where('outlet_id', Auth::user()->outlet_id)
                    ->orWhere('delivery_outlet_id', Auth::user()->outlet_id);
            });
        }elseif(Auth::user()->user_type == 3){
            $order->where('workstation_id', Auth::user()->workstation_id);
        }else{
            $order->where('created_by', Auth::user()->id);
        }

        if($this->search_query || $this->search_query != '') {
            $order->where(function($q){
                $q->where('order_number','like','%'.$this->search_query.'%')
                    ->orwhere('customer_name','like','%'.$this->search_query.'%');
            });
        }

        if($this->order_filter || $this->order_filter != ''){
            $order->where('status', $this->order_filter);
        }

        if(Auth::user()->user_type != 2){
            if($this->outlet_filter || $this->outlet_filter != ''){
                $order->where(function($q){
                    $q->where('outlet_id', $this->outlet_filter)
                        ->orWhere('delivery_outlet_id',  $this->outlet_filter);
                });
            }
        }

        $orders = $order->latest()->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));

        return $orders;
    }
}