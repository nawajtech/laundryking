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
        $order_details = $order_details->selectRaw('service_id, workstations.workstation_name as workstation_name,
        count(*) as total')
        ->join('orders', 'orders.id', '=', 'order_details_details.order_id')
        ->join('order_details', 'order_details.id', '=', 'order_details_details.order_detail_id')
        ->join('workstations', 'workstations.id', '=', 'orders.workstation_id');
        if($workstation) {
            $order_details = $order_details->where('orders.workstation_id', $workstation);
        }

        $ordDetDet = $order_details->whereDate('order_date','>=',$from_date)->whereDate('order_date','<=',$to_date)->groupBy('orders.workstation_id')->orderBy('total', 'DESC')->get();
            $lang = null;
        if (session()->has('selected_language')) {
            $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
    @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['workstation_report'] ?? 'Workstation Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            
        @php
        $workstations = \App\Models\Workstation::where('id', $workstation)->first();
        $workstation_name = $workstations->workstation_name ?? 'All workstation';
    @endphp
        <tr>
            <td class="col"> <label>{{$lang->data['workstation'] ?? 'Workstation Name'}}: </label>
                {{ $workstation_name ?? 'All workstation' }}
        </tr>
        </table>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="table-dark">
            <tr>
                <th style="width: 50%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['workstation_name'] ?? 'Workstation Name'}}</th>
                <th style="width: 50%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['qty'] ?? 'Quantity'}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($ordDetDet as $rows)
                <tr>
                    <td style="width: 50%">
                        <p class="text-xs px-3 mb-0">
                            <span class="text-xs px-3 mb-0">{{$rows->workstation_name ??''}}</span>
                        </p>
                    </td>
                    <td style="width: 50%">
                        <p class="text-xs px-3 mb-0">{{ $rows->total }}</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br /> <br />
       
    </body>
    </html>
</div>