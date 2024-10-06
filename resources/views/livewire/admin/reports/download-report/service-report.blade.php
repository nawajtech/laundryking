<div>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['service_report'] ?? 'Service Report'}}</title>
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
        $query = \App\Models\OrderDetails::query();
        $query->whereHas('service', function($q) use($category){
            if($category){
                $q->where('service_category_id', $category);
                }
            });
        $query->whereHas('order', function($q) use($outlet,$from_date,$to_date){
            if($outlet){
                $q->whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('outlet_id', $outlet);
                }else{
                $q->whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date);
                }
            });
        
        $ordDetDet = $query->groupBy('service_type_id')->get();

        $lang = null;
        if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
        $lang = \App\Models\Translation::where('default', 1)->first();
        }
        @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['service_report'] ?? 'Service Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            @php
            $outlets = \App\Models\Outlet::where('id', $outlet)->first();
            $outlet_name = $outlets->outlet_name ?? 'All outlet';
            @endphp
            <tr>
                <td class="col"> <label>{{$lang->data['outlet'] ?? 'Outlet Name'}}: </label>
                    {{ $outlet_name ?? 'All outlet' }}
            </tr>
        </table>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="table-dark">
            <tr>
                <th style="width: 40%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['service_name'] ?? 'Service Name'}}</th>
                <th style="width: 30%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['quantity'] ?? 'Quantity'}}</th>
                <th style="width: 30%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['amount'] ?? 'Total Amount'}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($ordDetDet as $rows)
                <tr>
                    @php
                        $servc_type = \App\Models\ServiceType::where('id', $rows->service_type_id)->where('is_active',1)->first();
                        $id = $rows -> id;
                        $a="/admin/service/type";
                    @endphp
                    <td style="width: 40%">
                        <p class="text-xs px-4 mb-0">
                            @if($servc_type)
                            <a href="{{url($a)}}" target="_blank">
                            <span class="font-weight-bold">{{$servc_type->service_type_name ??''}}</span>
                            @endif
                        </p>
                    </td>
                    
                    @php
                        $query = \App\Models\OrderDetails::query();
                        $query->whereHas('order', function($q) use($outlet) {
                            if($outlet){
                                $q->where('outlet_id', $outlet);
                                }
                            });

                        $qty = $query->where('service_type_id', $rows->service_type_id)->sum('service_quantity');
                    @endphp
                    @if($servc_type)
                    <td style="width: 30%">
                        <p class="text-xs px-3 mb-0">{{$qty}}</p>
                    </td>
                    @endif
                    @php
                        $query = \App\Models\OrderDetails::query();
                        $query->whereHas('order', function($q) use($outlet) {
                            if($outlet){
                                $q->where('outlet_id', $outlet);
                                }
                            });

                        $price = $query->where('service_type_id', $rows->service_type_id)->sum('service_price');
                    @endphp
                    @if($servc_type)
                    <td style="width: 30%">
                        <p class="text-xs px-3 mb-0">{{getCurrency()}} {{$price}}</p>
                    </td>
                    @endif
                </tr>
                @endforeach

            </tbody>
        </table>
        <br /> <br />
        {{-- <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">{{$lang->data['total_orders'] ?? 'Total Orders'}}:</span>
        <span class="text-sm text-dark ms-2 fw-600 mb-0">{{ count($orders) }}</span>
        </td>
        <td class="col"> <span class="text-sm mb-0 fw-500">{{$lang->data['total_order_amount'] ?? 'Total Order Amount'}}:</span>
            <span class="text-sm text-dark ms-2 fw-600 mb-0">{{ getCurrency() }}{{ number_format($orders->sum('total'), 2) }}</span>
        </td>
        </tr>
        </table>--}}
    </body>

    </html>
</div>