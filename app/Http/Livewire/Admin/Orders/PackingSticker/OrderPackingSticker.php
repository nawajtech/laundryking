<?php
namespace App\Http\Livewire\Admin\Orders\PackingSticker;
use Livewire\Component;
use App\Models\MasterSettings;
use App\Models\Order;
use App\Models\OrderDetailsDetail;
use App\Models\Translation;
use Illuminate\Support\Facades\Auth;

class OrderPackingSticker extends Component
{
    public $search,$order,$orderdetails,$tag_id,$order_number,$order_status,$srvc,$customer,$address,$servic,$delivery_date ;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.orders.packing-sticker.order-packing-sticker')
            ->extends('layouts.print-layout')
            ->section('content');
    }
    /* process before render */
    public function mount($id)
    {
        $query = OrderDetailsDetail::query();
        $query->whereHas('order_details', function($q) {
            $q->whereHas('order', function($q) {
                 if(Auth::user()->user_type == 2){
                     $q->where('outlet_id', Auth::user()->outlet_id)
                         ->orWhere('delivery_outlet_id', Auth::user()->outlet_id);
                 }elseif(Auth::user()->user_type == 3){
                    $q->where('workstation_id', Auth::user()->workstation_id);
                 }
            });
        });
        $this->orderdetails = $query->where('id', $id)->firstOrFail();

        $tag_id_master = MasterSettings::where('master_title', 'tag_id')->first();
        $this->tag_id = $tag_id_master->master_value;

        $order_number_master = MasterSettings::where('master_title', 'order_number')->first();
        $this->order_number = $order_number_master->master_value;

        $order_status_master = MasterSettings::where('master_title', 'order_status')->first();
        $this->order_status = $order_status_master->master_value;

        $customer_name_master = MasterSettings::where('master_title', 'customer_name')->first();
        $this->customer = $customer_name_master->master_value;

        $address_master = MasterSettings::where('master_title', 'address')->first();
        $this->address = $address_master->master_value;

        $service_master = MasterSettings::where('master_title', 'service')->first();
        $this->servic = $service_master->master_value;

        $delivery_date_master = MasterSettings::where('master_title', 'delivery_date')->first();
        $this->delivery_date = $delivery_date_master->master_value;
    }
}