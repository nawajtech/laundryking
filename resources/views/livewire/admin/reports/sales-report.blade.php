<div>
<div class="row align-items-center justify-content-between mb-4">
    <div class="col">
        <h5 class="fw-500 text-white">{{$lang->data['sales_report'] ?? 'Sales Report'}}</h5>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header p-4">
                <div class="row">
                    <div class="col-md-4">
                        <label>{{$lang->data['start_date'] ?? 'Start Date'}}</label>
                        <input type="date" class="form-control" wire:model="from_date">
                    </div>
                    <div class="col-md-4">
                        <label>{{$lang->data['end_date'] ?? 'End Date'}}</label>
                        <input type="date" class="form-control" wire:model="to_date">
                    </div>
                    <div class="col-md-4">
                    <label>{{$lang->data['outlate_name'] ?? 'Outlet name'}}</label>
                        <select class="form-control" wire:model="outlet">
                            <option value="0">Choose Outlet</option>
                            @foreach($outlets as $outletss)
                                <option value="{{ $outletss->id }}">{{ $outletss->outlet_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['date'] ?? 'Date'}}</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order'] ?? 'Order'}} #</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['customer'] ?? 'Customer'}}</th>
                                <th style="width: 13%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['gross_total'] ?? 'Total'}}</th>
                                <th style="width: 13%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['due_amount'] ?? 'Due Amount'}}</th>
                                <th style="width: 14%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['payment_mode'] ?? 'Payment Mode'}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered align-items-center mb-0 ">
                        <tbody>
                            @foreach($orders as $row)
                            <tr>
                                <td style="width: 20%" >
                                    <p class="text-xs px-3  mb-0">
                                        {{\Carbon\Carbon::parse($row->order_date)->format('d/m/Y')}}
                                    </p>
                                </td>
                                @php
                                $id = $row->id;
                                $a="/admin/orders/view/" .$id;
                                @endphp
                                <td style="width: 20%" >
                                    <p class="text-xs px-3 mb-0">
                                        <a href="{{url($a)}}" target="_blank">
                                            <span class="font-weight-bold">{{$row->order_number}}</span>
                                        </a>
                                    </p>
                                </td>
                                <td style="width: 20%" >
                                    <p class="text-xs px-3 font-weight-bold mb-0">{{$row->customer_name}}</p>
                                </td>
                                <td style="width: 13%" >
                                    <p class="text-xs px-3 font-weight-bold mb-0">{{getCurrency()}}{{number_format($row->total,2)}}</p>
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
                </div>
                <div class="row align-items-center px-4 mb-3">
                    <div class="col">
                        <span class="text-sm mb-0 fw-500">{{$lang->data['total_orders'] ?? 'Total Orders'}}:</span>
                        <span class="text-sm text-dark ms-2 fw-600 mb-0">{{count($orders)}}</span>
                    </div>
                    <div class="col">
                        <span class="text-sm mb-0 fw-500">{{$lang->data['total_sales'] ?? 'Total Sales'}}:</span>
                        <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{number_format($orders->sum('total'),2)}}</span>
                    </div>
                    <div class="col">
                        <span class="text-sm mb-0 fw-500">{{$lang->data['total_tax_amount'] ?? 'Total Tax Amount'}}:</span>
                        <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{number_format($orders->sum('tax_amount'),2)}}</span>
                    </div>
                    <div class="col-auto">
                        <button type="button" wire:click="downloadFile()" class="btn btn-success me-2 mb-0">{{$lang->data['download_report'] ?? 'Download Report'}}</button>
                        <a href="{{url('admin/reports/print-report/sales/'.$from_date.'/'.$to_date.'/'.$outlet)}}" target="_blank">                       
                            <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>