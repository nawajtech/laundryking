<?php
namespace App\Http\Livewire\Admin\Orders\TagGenerate;
use Livewire\Component;
use App\Models\Customer;
use App\Models\MasterSettings;
use App\Models\Order;
use App\Models\OrderAddonDetail;
use App\Models\OrderDetailsDetail;
use App\Models\OrderDetails;
use App\Models\DeliveryType;
use App\Models\Payment;
use App\Models\Translation;

class OrderTagGenerate extends Component
{
    public $order,$orderdetails,$orderaddons,$balance,$total,$delivery_charge,$delivery_type,$customer,$payments,$sitename,$address,$phone,$paid_amount,$payment_type,$zipcode,$tax_number,$store_email,$from_date,$to_date,$deliver_type,$totalitem;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.orders.tag-generate.order-tag-generate')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    /* process before render */
    public function mount($id)
    {
        $this->order = Order::where('id',$id)->firstOrFail();

        $this->delivery_type = DeliveryType::where('delivery_name',$this->order->delivery_type)->first();
        $this->customer = Customer::where('id',$this->order->customer_id)->first();
        $this->orderaddons = OrderAddonDetail::where('order_id',$this->order->id)->get();
        $this->orderdetails = OrderDetailsDetail::where('order_id',$this->order->id)->where('rewash_confirm', '!=' , 3)->get();
        $this->payments = Payment::where('order_id',$this->order->id)->get();
        $settings = new MasterSettings();
        $site = $settings->siteData();


        $this->totalitem = OrderDetails::where('order_id',$this->order->id)->sum('service_quantity');

        /* if site has default appplication name */
        if(isset($site['default_application_name']))
        {
            $sitename = (($site['default_application_name']) && ($site['default_application_name'] !=""))? $site['default_application_name'] : 'Laundry Box';
            $this->sitename = $sitename;
        }
        /* if site has default phone number */
        if(isset($site['default_phone_number']))
        {
            $phone = (($site['default_phone_number']) && ($site['default_phone_number'] !=""))? $site['default_phone_number'] : '123456789';
            $this->phone = $phone;
        }
        /* if site has default address */
        if(isset($site['default_address']))
        {
            $address = (($site['default_address']) && ($site['default_address'] !=""))? $site['default_address'] : 'Address';
            $this->address = $address;
        }
        /* if site has default zip code */
        if(isset($site['default_zip_code']))
        {
            $zipcode = (($site['default_zip_code']) && ($site['default_zip_code'] !=""))? $site['default_zip_code'] : 'ZipCode';
            $this->zipcode = $zipcode;
        }
        /* if site has store tax number */
        if(isset($site['store_tax_number']))
        {
            $tax_number = (($site['store_tax_number']) && ($site['store_tax_number'] !=""))? $site['store_tax_number'] : 'Tax Number';
            $this->tax_number = $tax_number;
        }
        /* if site has store email */
        if(isset($site['store_email']))
        {
            $store_email = (($site['store_email']) && ($site['store_email'] !=""))? $site['store_email'] : 'store@store.com';
            $this->store_email = $store_email;
        }
        $this->balance = $this->order->total -  Payment::where('order_id',$this->order->id)->sum('received_amount');
        $this->paid_amount = $this->balance;
        if(session()->has('selected_language'))
        {  /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
    }
}