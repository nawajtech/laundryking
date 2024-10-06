<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['stock_report'] ?? 'Stock Report'}}</title>
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
            if ($outlet == 0) {
                $orders = \App\Models\Order::whereDate('order_date', '>=', $from_date)
                    ->whereDate('order_date', '<=', $to_date)
                    ->groupBy('outlet_id')->latest()
                    ->get();
            } else {
                $orders = \App\Models\Order::whereDate('order_date', '>=', $from_date)
                    ->whereDate('order_date', '<=', $to_date)
                    ->where('outlet_id', $outlet)
                    ->groupBy('outlet_id')->latest()
                    ->get();
            }
            $lang = null;
        if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
        @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['stock_report'] ?? 'Stock Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}</td>
                <td class="col"> <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }} </td>
                @php
                $outlets = \App\Models\Outlet::where('id', $outlet)->first();
                $outlet_name = $outlets->outlet_name ?? 'All outlet';
                @endphp
                <td class="col"> <label>{{$lang->data['outlet'] ?? 'Outlet'}}: </label>
                    {{ $outlet_name ?? 'All outlet' }}</td>
            </tr>
        </table>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="table-dark">
            <tr>
                <th style="width: 15%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['outlet_name'] ?? 'Outlet Name'}}</th>
                <th style="width: 30%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['Stock'] ?? 'Stock'}}</th>
                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['delivered'] ?? 'Delivered'}}</th>
            </tr>
            <tbody>
                @foreach($orders as $row)
                    @php
                    $stock = \App\Models\Order::where('outlet_id', $row->outlet_id)->whereIn('status', [0,1,2,3,4,5,6,7,8])->count();
                    $delivered = \App\Models\Order::where('outlet_id', $row->outlet_id)->where('status',9)->count();
                    @endphp
                    <tr>
                        <td style="width: 15%" >
                            <p class="text-xs px-3 mb-0">
                                <span class="font-weight-bold">{{$row->outlet->outlet_name}}</span>
                            </p>
                        </td>
                        <td style="width: 30%" >
                            <p class="text-xs px-3 font-weight-bold mb-0">{{ $stock }}</p>
                        </td>
                        <td style="width: 20%" >
                            <a type="button" class="badge badge-sm bg-secondary text-uppercase">{{ $delivered }}</a>
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