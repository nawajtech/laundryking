<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use App\Models\OrderDetails;
class ServiceReport extends Component
{
    public $orders,$outlet=0,$ordDetDe,$from_datet,$to_date, $category=0;
    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.service-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
    /* process before render */
    public function mount($from_date = null,$to_date = null, $outlet= null, $category=null) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->outlet = $outlet;
        $this->category = $category;
        $query = OrderDetails::query();
        $query->whereHas('service', function($q) {
            if($this->category){
                $q->where('service_category_id', $this->category);
                }
            });
        $query->whereHas('order', function($q) {
            if($this->outlet){
                $q->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('outlet_id', $this->outlet);
                }else{
                $q->whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date);
                }
            });
        

        $this->ordDetDet = $query->groupBy('service_type_id')->get();
    }
}