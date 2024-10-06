<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['order_report'] ?? 'Order Report'}}</title>
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
        $order_details = \App\Models\OrderDetails::query()
        ->selectRaw('service_id, outlets.outlet_name as outlet_name,services.service_name as service_name,
        count(*) as total')
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->join('outlets', 'outlets.id', '=', 'orders.outlet_id')
        ->join('services', 'services.id', '=', 'order_details.service_id');


        if($outlet) {
            $order_details = $order_details->where('orders.outlet_id', $outlet);
        }

        $order_details = $order_details->whereDate('order_date','>=',$from_date)->whereDate('order_date','<=',$to_date)->whereNotNull('parent_id');
        $orderDet = $order_details->groupBy('order_details.service_id')->get();
    
            $lang = null;
        if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
        @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['rewash_report'] ?? 'Rewash Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            @php
                $outlets = \App\Models\Outlet::where('id', $outlet)->first();
                $outlet_name = $outlets->outlet_name ?? 'All outlet';
            @endphp
            <tr>
                <td class="col"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}</td>
                <td class="col"> <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }} </td>
                <td class="col"> <label>{{$lang->data['outlet'] ?? 'Outlet'}}: </label>
                    {{ $outlet_name ?? 'All Outlet' }}
            </tr>
        </table>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="table-dark">
            <tr>
                <th style="width: 25%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['outlet_name'] ?? 'Outlet Name'}}</th>
                <th style="width: 25%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garment_name'] ?? 'Garment Name'}}</th>
                <th style="width: 30%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['rewash_qty'] ?? 'Rewash '}}</th>
            </tr>
            <tbody>
                @foreach($orderDet as $row)
                <tr>
                    <td style="width: 25%" >
                        <p class="text-xs px-3 mb-0">
                            <span class="font-weight-bold">{{$row->outlet_name}}</span>
                        </p>
                    </td>
                    <td style="width: 25%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->service_name}}</p>
                    </td>
                    <td style="width: 30%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->total}}</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br /> <br />
        <table cellspacing="15">
        </table>
    </body>
    </html>
</div>