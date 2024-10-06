<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['customer_report'] ?? 'Customer History Report'}}</title>
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
            $payments = \App\Models\Payment::whereDate('payment_date', '>=', $from_date)->whereDate('payment_date', '<=', $to_date)->get();
            $orders = \App\Models\Order::groupBy('customer_id')->latest()->get();
            $payment = \App\Models\Payment::groupBy('customer_id')->get();

            $lang = null;
        if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
        @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['customer_report'] ?? 'Customer History Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}</td>
                <td class="col"> <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }} </td>
            </tr>
        </table>
        <table id="main" cellpadding="0" cellspacing="0" >
        <thead class="bg-light">
        <tr>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['customer_name'] ?? 'Customer Name'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->total_order ?? 'Total Order'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['order_between_choosen_date'] ?? 'Order Between Date'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['total_garment'] ?? 'Total Garment'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['grmnt_btw_chsn_date'] ?? 'Garment Between Choosen Date'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['total_order_amnt'] ?? 'Total Order Amount'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['total_ordr_btwn_date'] ?? 'Total Order Amount Between Date'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['revenue'] ?? 'Total Revenue'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['revenue_btwn_date'] ?? 'Total Revenue Amount Between Date'}}</th>
            <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['last_order'] ?? 'Last Order'}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $row)
            <tr >
            @php
                $membership = App\Models\Customer::where('id', $row->customer_id)->first();
                $membership_name = App\Models\Membership::where('id',$membership->membership)->first();
            @endphp
                <td>
                    <p class="text-xs px-3 mb-0">
                        <span class="font-weight-bold">{{$row->customer_name}}</span>
                        <br><span class="font-weight-bold">{{ $membership_name->membership_name ?? ''}}</span>
                    </p>
                </td>
                @php
                $customr_total_ord = App\Models\Order::where('customer_id', $row->customer_id)->count();
                @endphp
                <td >
                    <p class="text-xs px-3 mb-0">
                        <span class="font-weight-bold">{{ $customr_total_ord }}</span>
                    </p>
                </td>
                @php
                $customr_ord_btwn_date = App\Models\Order::whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('customer_id', $row->customer_id)->count();
                @endphp
                <td >
                <p class="text-xs px-3 mb-0">
                <span class="font-weight-bold">{{ $customr_ord_btwn_date }}</span>
                </p>
                </td>
                @php
                $query = App\Models\OrderDetails::query();
                    $query->whereHas('order', function($q) use($row){
                        $q->where('customer_id', $row->customer_id);
                    });
                $total_garment = $query->sum('service_quantity');
                @endphp
                <td >
                    <p class="text-xs px-3 font-weight-bold mb-0">{{ $total_garment ?? 0}}</p>
                </td>
                @php
                $query = App\Models\OrderDetails::query();
                    $query->whereHas('order', function($q) use($row, $from_date, $to_date){
                        $q->whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('customer_id', $row->customer_id);
                    });
                $total_garment_btw_date = $query->sum('service_quantity');
                @endphp
                <td >
                    <p class="text-xs px-3 font-weight-bold mb-0">{{ $total_garment_btw_date ?? 0}}</p>
                </td>
                @php
                    $total_ord_amnt = App\Models\Order::where('customer_id', $row->customer_id)->sum('total');
                @endphp
                <td >
                    <p class="text-xs px-3 font-weight-bold mb-0">{{ $total_ord_amnt }}</p>
                </td>
                @php
                    $total_ord_amnt_btw_date = App\Models\Order::whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('customer_id', $row->customer_id)->sum('total');
                @endphp
                <td >
                    <p  class="text-xs px-3 font-weight-bold mb-0">{{ $total_ord_amnt_btw_date }}</p>
                </td>
                
                @php
                        $amount = $payment->where('customer_id',$row->customer_id)->sum('received_amount');
                @endphp
                <td >
                    <p  class="text-xs px-3 font-weight-bold mb-0">{{ $amount }}</p>
                </td>
                @php
                        $amount_btwn_date = $payments->where('customer_id',$row->customer_id)->sum('received_amount');
                @endphp
                <td >
                    <p  class="text-xs px-3 font-weight-bold mb-0">{{ $amount_btwn_date }}</p>
                </td>
                
                @php
                    $last_order = App\Models\Order::where('customer_id', $row->customer_id)->orderBy('id', 'desc')->first();
                @endphp
                <td >
                    <p  class="text-xs px-3 font-weight-bold mb-0">{{ date("d-m-y", strtotime($last_order->order_date)) }}</p>
                </td>
                
            </tr>
        @endforeach
        </tbody>
        </table>
        <br /> <br />
        <table cellspacing="15">
            <div class="row align-items-center px-4 mb-3">
                <div class="col-auto">
                    <span class="text-sm mb-0 fw-500">{{$lang->data['total_orders'] ?? 'Total Orders'}}:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{count($orders)}}</span>
                </div>
                <div class="col">
                    <span class="text-sm mb-0 fw-500">{{$lang->data['total_order_amount'] ?? 'Total Order Amount'}}:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{number_format($orders->sum('total'),2)}}</span>
                </div>
                <div class="col">
                    <span class="text-sm mb-0 fw-500">{{$lang->data['total_received_amount'] ?? 'Total Received Amount'}}:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{number_format($payment->sum('received_amount'),2)}}</span>
                </div>
            </div>
        </table>
    </body>
    </html>
</div>

<style>
    @page {
        size: auto;
        margin: 1mm 0 1mm 0;
    }
    @media print {
        html, body {
            width: 100%;
            height:100%;
            position:absolute;
            top:0px;
            bottom:0px;
            margin: auto;
            margin-top: 0px !important;
            size: auto;
            margin: 1mm 1mm 1mm 1mm;
        }
    }
</style>