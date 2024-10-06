<?php
namespace App\Http\Livewire\Admin\Orders;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetails;
use App\Models\OrderDetailsDetail;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Workstation;
use App\Models\Translation;
use Livewire\Component;
use Auth;

class GarmentStatusScreen extends Component
{
    public $orders,$alloutlet,$deliveryoutlet,$allworkstation,$pending_orders,$processing_orders,$ready_orders,$processed_orders,$transit_orders,$store_orders,$search,$name,$lang,$orderid,$ordernumber,$outlet,$order,$customer,$customername,$deliverydate,$totalqty,$outlet_filter,$workstation_filter,$order_details;

    /* render the page */
    public function render()
    {
        $this->filterdata();

        return view('livewire.admin.orders.garment-status-screen');
    }

    /* process before render */
    public function mount()
    {
        $message = 'You don\'t have permission Garment Status Screen';
        if(!user_has_permission('garment_status_screen')){
            abort(403, $message);
        }

        if(Auth::user()->user_type==3){
            $getworkstation = User::where('id', Auth::user()->id)->first();
            $workstation_id = $getworkstation->workstation_id;
            $this->alloutlet = Outlet::where('workstation_id', $workstation_id)->get();
        }else{
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
        $this->order_details = OrderDetails::where('id',$id)->first();
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

    public function filterdataWithStatus($status = '')
    {
        $query = OrderDetailsDetail::query();

        if(Auth::user()->user_type==2){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                    $q->where('outlet_id', Auth::user()->outlet_id);
                });
            });
        }elseif(Auth::user()->user_type==3){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                    $q->where('workstation_id', Auth::user()->workstation_id);
                });
            });
        }

        if($this->search){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                    // $q->where('order_number', 'like' , '%'.$this->search.'%');
                });
            })->where('garment_tag_id', 'like' , '%'.$this->search.'%');
        }

        if($this->outlet_filter){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                    $q->where(function($q){
                        $q->where('outlet_id', $this->outlet_filter)
                            ->orWhere('delivery_outlet_id', $this->outlet_filter);
                    });
                });
            });
        }

        if($this->workstation_filter){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                    $q->where(function($q){
                        $q->where('workstation_id', $this->workstation_filter);
                    });
                });
            });
        }

        if($status){
            $query->where('status', $status);
        }

        return $query->where('rewash_confirm', '!=' , 3)->latest()->get();
    }

    /* garment status change */
    public function statuschange()
    {
        $this->orders = $this->filterdataWithStatus();
        if($this->orders){
            $OrderDetailsDetail = $this->orders->first();

            if($OrderDetailsDetail){
                if(Auth::user()->user_type==1){
                    if($OrderDetailsDetail->status > 2 && $OrderDetailsDetail->status < 7){
                        $OrderDetailsDetail->update([
                            'status' => $OrderDetailsDetail->status+1,
                        ]);
                    }
                    if($OrderDetailsDetail->status == 7){
                        $OrderDetailsDetail->update([
                            'ready_at' => now(),
                        ]);
                    }
                }
                if(Auth::user()->user_type==2){
                    if($OrderDetailsDetail->status == 3 || $OrderDetailsDetail->status == 6){
                        $OrderDetailsDetail->update([
                            'status' => $OrderDetailsDetail->status+1,
                        ]);
                    }
                    if($OrderDetailsDetail->status == 7){
                        $OrderDetailsDetail->update([
                            'ready_at' => now(),
                        ]);
                    }
                }
                if(Auth::user()->user_type==3){
                    if($OrderDetailsDetail->status > 3 && $OrderDetailsDetail->status < 6 ){
                        $OrderDetailsDetail->update([
                            'status' => $OrderDetailsDetail->status+1,
                        ]);
                    }
                    if($OrderDetailsDetail->status == 7){
                        $OrderDetailsDetail->update([
                            'ready_at' => now(),
                        ]);
                    }
                }
            }

            $OrderDetails = $OrderDetailsDetail->order_details->order->order_details->pluck('id');
            $MinStatus = OrderDetailsDetail::whereIn('order_detail_id', $OrderDetails)->min('status');
            $OrderDetailsDetail->order_details->order->update([
                'status' => $MinStatus,
            ]);
        }
    }

    /* change the order status */
    public function changestatus($order_details,$status)
    {
        $orderdet = OrderDetailsDetail::where('id',$order_details)->first();
        switch($status)
        {
            case 'transit':
                $orderdet->status = 4;
                $orderdet->save();
                $message = sendOrderStatusChangeSMS($orderdet->id,1);
                break;
            case 'processing':
                $orderdet->status = 5;
                $orderdet->save();
                $message = sendOrderStatusChangeSMS($orderdet->id,2);
                break;
            case 'store':
                $orderdet->status = 6;
                $orderdet->save();
                $message = sendOrderStatusChangeSMS($orderdet->id,3);
                break;
            case 'ready':
                $orderdet->status = 7;
                $orderdet->save();
                $message = sendOrderStatusChangeSMS($orderdet->id,4);
                break;
            case 'processed':
                $orderdet->status = 3;
                $orderdet->save();
                $message = sendOrderStatusChangeSMS($orderdet->id,5);
                break;
        }

        if($message) {
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => $message,'title'=>'SMS Error']);
        }
    }
}