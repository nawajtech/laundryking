<?php
namespace App\Http\Livewire\Admin\Orders;
use Livewire\Component;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\OrderDetailsDetail;
use App\Models\MasterSettings;
use App\Models\Outlet;
use App\Models\Wallet;
use Auth;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;
class RewashRequest extends Component
{
    public $orders;
    public $paid_amount, $image, $customer,$customer_name,$search_query,$outlet, $wallet_amount, $deleteId;
    public $order,$amount_to_pay,$note,$balance,$payment_mode,$order_filter,$outlet_filter,$lang;
    protected $queryString = ['search_query', 'outlet_filter', 'order_filter'];
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;
    
    /* render the page*/
    public function render()
    {
        return view('livewire.admin.orders.rewash_requested');
    }

    /* process before render */
    public function mount()
    {
        $usertype = Auth::user()->user_type;
        $this->orders = new EloquentCollection();
        $this->outlet = Outlet::where('is_active',1)->latest()->get();

        $this->loadOrders();
        if(session()->has('selected_language')){   
            /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
    }
    
    public function updated($name,$value)
    {
        $this->orders = new EloquentCollection();
        $this->reloadOrders();
    }

    /* refresh the page */
    public function refresh()
    {
        /* if search query or order filter is empty */
        if( $this->search_query == '' || $this->order_filter == '' || $this->outlet_filter == '')
        {
            $this->orders->fresh();
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

        $query = OrderDetailsDetail::query();
        if($this->search_query || $this->search_query!= ''){
        if(Auth::user()->user_type==2 || Auth::user()->user_type==1){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                    $q->where('order_number','like','%'.$this->search_query.'%');
                });
            });
        }
        $order = $query->where('rewash_confirm', 1);
    } else {
        if(Auth::user()->user_type==2 || Auth::user()->user_type==1){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                });
            });
        }
        $order = $query->where('rewash_confirm', 1);
    }

        $orders = $order->latest()->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));

        return $orders;
    }
    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    //Approve Rewash Request accept 
    public function approve()
    {
        $data['rewash_confirm'] = 2;
        OrderDetailsDetail::find($this->deleteId)->update($data);
        
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Rewash approved successfully!']);
        $this->orders = OrderDetailsDetail::where('rewash_confirm',1)->latest()->get();
    }

    //Decline Rewash Request accept 
    public function decline()
    {
        $data['rewash_confirm'] = 3;
        OrderDetailsDetail::find($this->deleteId)->update($data);
        
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Rewash declined successfully!']);
        $this->orders = OrderDetailsDetail::where('rewash_confirm',1)->latest()->get();
    }

    //Rewash Image 
    public function view_image($id)
    {
        $order_det = Order::where('id',$id)->first();
        $find_order = OrderDetailsDetail::where('order_id',$order_det->parent_id)->first(); 
        $this->image = $find_order->rewash_image;
        $this->orders = OrderDetailsDetail::where('rewash_confirm',1)->latest()->get();
    }
}