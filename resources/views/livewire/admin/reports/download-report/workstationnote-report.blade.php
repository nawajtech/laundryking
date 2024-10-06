<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['outlet_report'] ?? 'Outlet Report'}}</title>
        <style>
            #main {
                border-collapse: collapse;
                line-height: 1rem;
                text-align: center;
            }
            th {
                background-color: rgb(101, 104, 101);
                Color: white;
                font-size: 0.75rem;
                line-height: 1rem;
                font-weight: bold;
                text-transform: uppercase;
                text-align: center;
                padding: 10px;
            }
            td {
                text-align: center;
                border: 1px solid;
                font-size: 0.75rem;
                line-height: 1rem;
            }
            .col {
                border: none;
                text-align: left;
                padding: 10px;
                font-size: 0.75rem;
                line-height: 1rem;
            }
        </style>
    </head>
    <body onload="">
    @php
    $query = \App\Models\Order::query();
            if($workstation){
                $query->where('workstation_id', $workstation);
                }
            if($workstation){
                $query->whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('workstation_id', $workstation);
                }else{
                $query->whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date);
                }
        $ordDetDet = $query->get();
        
        $lang = null;
        if (session()->has('selected_language')) {
            $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
    @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['workstation_report'] ?? 'Workstation Note Summary Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            
        @php
            $workstations = \App\Models\Workstation::where('id', $workstation)->first();
            $workstation_name = $workstations->workstation_name ?? 'All workstation';
        @endphp
        <tr>
                <td class="col"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}</td>
                <td class="col"> <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }} </td>
            <td class="col"> <label>{{$lang->data['workstation'] ?? 'Workstation Name'}}: </label>
                {{ $workstation_name ?? 'All workstation' }}
        </tr>
        </table>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="table-dark">
            <tr>
                <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_no'] ?? 'Order No'}}</th>
                <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_date'] ?? 'Order Date'}}</th>
                <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['delivery_date'] ?? 'Delivery Date'}}</th>
                <th style="width: 14%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['delivery_type'] ?? 'Delivery Type'}}</th>
                <th style="width: 9%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['pcs'] ?? 'Pcs'}}</th>
                <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garment_details'] ?? 'Garment Details'}}</th>
                <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['services'] ?? 'Services'}}</th>
                <th style="width: 7%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['defects'] ?? 'Defects'}}</th>
                <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['colors'] ?? 'Colors'}}</th>
                <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['brand'] ?? 'Brand'}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($ordDetDet as $rows)
                <tr>
                    <td style="width: 10%">
                        <p class="text-xs px-3 mb-0">
                            <span class="text-xs px-3 mb-0">{{$rows->order_number ??''}}</span>
                        </p>
                    </td>
                    <td style="width: 10%">
                        <p class="text-xs px-3 mb-0">{{ \Carbon\Carbon::parse($rows->order_date)->format('d/m/Y') ?? '' }}</p>
                    </td>
                    <td style="width: 10%">
                        <p class="text-xs px-3 mb-0">{{ \Carbon\Carbon::parse($rows->delivery_date)->format('d/m/Y') ?? '' }}</p>
                    </td>
                    <td style="width: 10%">
                        <p class="text-xs px-3 mb-0">{{ $rows->delivery_type ??'' }}</p>
                    </td>
                    @php
                    $quantity = App\Models\OrderDetails::where('order_id', $rows->id)->count();
                    @endphp
                    <td style="width: 10%">
                        <p class="text-xs px-3 mb-0">{{ $quantity ?? '' }}</p>
                    </td>
                    @php 
                    $ord_details = App\Models\OrderDetails::where('order_id', $rows->id)->get();
                    @endphp
                    <td style="width: 10%">
                        @foreach($ord_details as $ord_det)
                        @php 
                            $service = App\Models\Service::where('id', $ord_det->service_id)->first();
                        @endphp
                        <p class="text-xs px-3 mb-0">{{ $service->service_name }}</p>
                        @endforeach
                    </td>
                    <td style="width: 10%">
                        @foreach($ord_details as $ord_det)
                        <p class="text-xs px-3 mb-0">{{ $ord_det->service_name }}</p>
                        @endforeach
                    </td>
                    <td style="width: 10%">
                        @foreach($ord_details as $ord_det)
                        @php
                        $defect_remark = App\Models\OrderDetailsDetail::where('order_detail_id', $ord_det->id)->first();
                        @endphp
                        <p class="text-xs px-3 mb-0">{{ $defect_remark->remarks }}</p>
                        @endforeach
                    </td>
                    <td style="width: 10%">
                        @foreach($ord_details as $ord_det)
                        @if($ord_det->color_code !='')
                        <button class="clr-btn" style="margin:0px auto; border:1px solid #000; width:66%; height:10px; display: block; margin-bottom: 5px; background-color: {{$ord_det->color_code ?? 'No color'}}!important"></button>
                        @else
                        <p class="text-xs px-3 mb-0">{{ 'No Color' }}</p>
                        @endif
                        @endforeach
                    </td>
                    <td style="width: 10%">
                        @foreach($ord_details as $ord_det)
                        @php
                            $brand = App\Models\Brand::where('id', $ord_det->brand_id)->first();
                        @endphp
                        <p class="text-xs px-3 mb-0">{{ $brand->brand_name ?? 'No Brand' }}</p>
                        @endforeach
                    </td>
                @endforeach
            </tbody>
        </table>
        <br /> <br />
       
    </body>
    </html>
</div>