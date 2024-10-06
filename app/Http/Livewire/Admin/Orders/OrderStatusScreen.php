<?php
namespace App\Http\Livewire\Admin\Orders;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Workstation;
use App\Models\Translation;
use Livewire\Component;
use Auth;
class OrderStatusScreen extends Component
{
    public $orders,$alloutlet, $deliveryoutlet, $allworkstation, $pending_orders,$processing_orders,$ready_orders,$processed_orders,$transit_orders,$store_orders,$search,$name,$lang,$orderid,$ordernumber,$outlet,$order,$customer,$customername,$deliverydate,$totalqty, $outlet_filter, $workstation_filter;

    /* render the page */
    public function render()
    {
        $this->filterdata();

        return view('livewire.admin.orders.order-status-screen');
    }

    /* process before render */
    public function mount()
    {
        if(Auth::user()->user_type==3) {
            $getworkstation = Workstation::first();
            $workstation_id = $getworkstation->id;
            $this->alloutlet = Outlet::where('workstation_id', $workstation_id)->get();
        }else {
            $this->alloutlet = Outlet::get();
        }

        $this->allworkstation = Workstation::get();

        $this->filterdata();

        if(session()->has('selected_language')) {
            /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        } else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
    }

    public function orderdetails($id)
    {
        $this->editMode = true;
        $this->order = Order::where('id',$id)->first();
        $this->outlet = Outlet::where('id', $this->order->outlet_id)->first();
        $this->deliveryoutlet = Outlet::where('id', $this->order->delivery_outlet_id)->first();
        $this->customer = Customer::where('id', $this->order->customer_id)->first();
        $this->deliverydate = $this->order->delivery_date;
        $this->orderid = $this->order->id;
        $this->ordernumber = $this->order->order_number;
        if($this->customer != ''){
            $this->customername = $this->customer->name;
        }else{
            $this->customername = 'Walk in Customer';
        }

        $this->totalqty = OrderDetails::where('order_id', $this->order->id)->sum('service_quantity');
    }

    public function closemodal()
    {
        $this->emit('closemodal');
    }

    public function filterdata()
    {
        $this->processed_orders = $this->filterdataWithStatus(3);
        $this->transit_orders = $this->filterdataWithStatus(4);
        $this->processing_orders = $this->filterdataWithStatus(5);
        $this->store_orders = $this->filterdataWithStatus(6);
        $this->ready_orders = $this->filterdataWithStatus(7);
    }

    public function filterdataWithStatus($status)
    {
        $query = Order::query();

        if(Auth::user()->user_type==2){
            $query->where('outlet_id', Auth::user()->outlet_id);
        }elseif(Auth::user()->user_type==3){
            $query->where('workstation_id', Auth::user()->workstation_id);
        }

        if($this->search){
            $query->where('order_number', 'like' , '%'.$this->search.'%');
        }

        if($this->outlet_filter){
            $query->where(function($q){
                $q->where('outlet_id', $this->outlet_filter)
                    ->orWhere('delivery_outlet_id', $this->outlet_filter);
            });
        }

        if($this->workstation_filter){
            $query->where('workstation_id', $this->workstation_filter);
        }

        return $query->where('status', $status)->latest()->get();
    }

    /* change the order status */
    public function changestatus($order,$status)
    {
        $orderz = Order::where('id',$order)->first();
        switch($status)
        {
            case 'transit':
                $orderz->status = 4;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,1);
                break;
            case 'processing':
                $orderz->status = 5;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,2);
                break;
            case 'store':
                $orderz->status = 6;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,3);
                break;
            case 'ready':
                $orderz->status = 7;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,4);
                break;
            case 'processed':
                $orderz->status = 3;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,5);
                break;

        }

        if($message)
        {
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => $message, 'title'=>'SMS Error']);
        }
    }
}