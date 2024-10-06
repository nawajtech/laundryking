<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['sales_report'] ?? 'Sales Report'}}</title>
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
        if ($outlet){
            $orders = \App\Models\Order::whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('status', 3)->where('outlet_id',$outlet)->latest()->get();
        } else{
            $orders = \App\Models\Order::whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('status', 3)->latest()->get();
        }  
        $lang = null;
        if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
        @endphp
        <h3 class="fw-500 text-dark">{{$lang->data['sales_report'] ?? 'Sales Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}
                </td>
                <td class="col">
                    <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }}
                </td>
            </tr>
        </table>
        <br /> <br />
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead>
                <tr class="table-dark">
                    <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['date'] ?? 'Date'}}</th>
                    <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order'] ?? 'Order'}} #</th>
                    <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['customer'] ?? 'Customer'}}</th>
                    <th style="width: 13%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['gross_total'] ?? 'Total'}}</th>
                    <th style="width: 13%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['due_amount'] ?? 'Due Amount'}}</th>
                    <th style="width: 14%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['payment_mode'] ?? 'Payment Mode'}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $row)
                    <tr>
                        <td>
                            <p class="text-xs px-3  mb-0">
                                {{ \Carbon\Carbon::parse($row->order_date)->format('d/m/Y') }}
                            </p>
                        </td>
                        <td>
                            <p class="text-xs px-3 mb-0">
                                <span class="font-weight-bold">{{ $row->order_number }}</span>
                            </p>
                        </td>
                        <td>
                            <p class="text-xs px-3 font-weight-bold mb-0">
                                {{ $row->customer_name ?? 'Walk In Customer' }}</p>
                        </td>
                        <td>
                            <p class="text-xs px-3 font-weight-bold mb-0">
                                {{ getCurrency() }}{{ number_format($row->total, 2) }}</p>
                        </td>
                        @php
                        $payment = App\Models\Payment::where('order_id', $row->id)->sum('received_amount');
                        $payments = App\Models\Payment::where('order_id', $row->id)->get();
                        $due_amount = ($row->total - $payment);
                        @endphp
                        <td style="width: 13%" >
                            <p class="text-xs px-3 font-weight-bold mb-0">{{getCurrency()}}{{number_format($due_amount)}}</p>
                        </td>
                        <td style="width: 14%" >
                            @foreach ($payments as $p)
                            @if($p->payment_type ==1)
                            @php $type = 'CASH'; @endphp
                            @elseif($p->payment_type ==2)
                            @php $type = 'UPI'; @endphp
                            @elseif($p->payment_type ==3)
                            @php $type = 'CARD'; @endphp
                            @elseif($p->payment_type ==4)
                            @php $type = 'CHEQUE'; @endphp
                            @elseif($p->payment_type ==5)
                            @php $type = 'BANK TRANSFER'; @endphp
                            @elseif($p->payment_type ==6)
                            @php $type = 'RAZOR PAY'; @endphp
                            @else
                            @php $type = 'CASH ON DELIVERY'; @endphp
                            @endif
                            <p class="text-xs px-3 font-weight-bold mb-0">{{ $p->received_amount }} -> {{ $type }}</p>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br> <br>
        <table cellspacing="15">
            <tr>
                <td class="col">
                    <span class="text-sm mb-0 fw-500">{{$lang->data['total_orders'] ?? 'Total Orders'}}:</span>
                    <span class="text-sm text-dark ms-2 fw-600 mb-0">{{ count($orders) }}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">{{$lang->data['total_sales'] ?? 'Total Sales'}}:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{ getCurrency() }}{{ number_format($orders->sum('total'), 2) }}</span>
                </td>
                <td class="col"> <span class="text-sm mb-0 fw-500">{{$lang->data['total_tax_amount'] ?? 'Total Tax Amount'}}:</span>
                    <span
                        class="text-sm text-dark ms-2 fw-600 mb-0">{{ getCurrency() }}{{ number_format($orders->sum('tax_amount'), 2) }}</span>
                </td>
            </tr>
        </table>
    </body>
    </html>
</div>