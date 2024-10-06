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
    $order_details = \App\Models\OrderDetailsDetail::query();
        $order_details = $order_details->selectRaw('service_id, outlets.outlet_name as outlet_name, outlets.outlet_code as outlets_code,
        count(*) as total')
        ->join('orders', 'orders.id', '=', 'order_details_details.order_id')
        ->join('order_details', 'order_details.id', '=', 'order_details_details.order_detail_id')
        ->join('outlets', 'outlets.id', '=', 'orders.outlet_id');
        if($outlet) {
            $order_details = $order_details->where('orders.outlet_id', $outlet);
        }

    $ordDetDet = $order_details->whereDate('order_date','>=',$from_date)->whereDate('order_date','<=',$to_date)->groupBy('orders.outlet_id')->orderBy('total', 'DESC')->get();
           
        $lang = null;
        if (session()->has('selected_language')) {
            $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
    @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['outlet_report'] ?? 'Outlet Report'}}</h3>
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
                <th style="width: 15%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['outlet_name'] ?? 'Outlet Name'}}</th>
                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['outlet_code'] ?? 'Outlet Code'}}</th>
                <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['quantity'] ?? 'Quantity'}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($ordDetDet as $rows)
                <tr>
                    <td style="width: 15%" >
                    <p class="text-xs px-3 mb-0">
                        <span class="text-xs px-3 font-weight-bold mb-0">{{ $rows->outlet_name }}</span>
                    </p>
                    </td>
                    <td style="width: 20%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{ $rows->outlets_code }}</p>
                    </td>
                    <td style="width: 20%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{ $rows->total }}</p>
                    </td>
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
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{ getCurrency() }}{{ number_format($orders->sum('total'), 2) }}</span>
                </td>
            </tr>
        </table>--}}
    </body>
    </html>
</div>