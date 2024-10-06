<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['customer_report'] ?? 'Customer Report'}}</title>
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
            $orders = \App\Models\Order::whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->latest()->get();
            $payment = \App\Models\Payment::whereDate('payment_date', '>=', $from_date)->whereDate('payment_date', '<=', $to_date)->get();
            
            $lang = null;
        if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
        @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['customer_report'] ?? 'Customer Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}</td>
                <td class="col"> <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }} </td>
                {{--<td class="col"> <label>{{$lang->data['status'] ?? 'Status'}}: </label>
                    {{ getOrderStatus($status) }}--}}
            </tr>
        </table>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="table-dark">
            <tr>
                <th style="width: 13%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['date'] ?? 'Date'}}</th>
                <th style="width: 14%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_id'] ?? 'Order Id'}}</th>
                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['customer_details'] ?? 'Customer Details'}}</th>
                <th style="width: 13%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['order_amount'] ?? 'Order Amount'}}</th>
                <th style="width: 13%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['paid_amount'] ?? 'Paid Amount'}}</th>
                <th style="width: 18%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['outlet_name'] ?? 'Outlet Name'}}</th>
                <th style="width: 18%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['status'] ?? 'Status'}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $row)
                <tr>
                    <td style="width: 15%" >
                        <p class="text-xs px-3 mb-0">
                            {{\Carbon\Carbon::parse($row->order_date)->format('d/m/Y')}}
                        </p>
                    </td>
                    <td style="width: 15%" >
                        <p class="text-xs px-3 mb-0">
                            <span class="font-weight-bold">{{$row->order_number}}</span>
                        </p>
                    </td>
                    @php 
                    $customer=App\Models\Customer::where('id',$row->customer_id)->first();
                    @endphp
                    <td style="width: 30%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->customer_name ?? ""}}</p>
                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->phone_number ?? ""}}</p>
                        <p class="text-xs px-3 font-weight-bold mb-0">{{$customer->email ?? ""}}</p>
                    </td>
                    <td style="width: 18%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{getCurrency()}}{{$row->total ?? 0}}</p>
                    </td>
                    @php 
                    $rcv_amount=App\Models\Payment::where('order_id',$row->id)->first();
                    @endphp
                    <td style="width: 18%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{getCurrency()}}{{$rcv_amount->received_amount ?? 0}}</p>
                    </td>
                    @php
                    $outlet=App\Models\Outlet::where('id',$row->outlet_id)->first();
                    @endphp
                    <td style="width: 21.3%" >
                        <p class="text-xs px-3 font-weight-bold mb-0">{{ $outlet->outlet_name }}</p>
                    </td>
                    <td style="width: 20%" >
                        <a type="button" class="badge badge-sm bg-secondary text-uppercase">{{getOrderStatus($row->status)}}</a>
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