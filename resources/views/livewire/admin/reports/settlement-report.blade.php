<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['settlement_report'] ?? 'Outstanding Report'}}</h5>
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
                            <label>{{$lang->data['select_customer'] ?? 'Select Customer'}}</label>
                            <input type="text" wire:model="customer_query" class="form-control" placeholder="@if (!$selected_customer) {{ $lang->data['select_a_customer'] ?? 'Select A Customer' }} @else {{ $selected_customer->name }} @endif">
                            @if ($customers && count($customers) > 0)
                                <ul class="list-group customhover">
                                    @foreach ($customers as $rows)
                                        <li class="list-group-item customhover2" wire:click="selectCustomer({{ $rows->id }})">{{ $rows->name }} <br><small style="font-size: 13px;"><i class="fa fa-phone me-2"></i> {{$rows->phone}}</small><br><small style="font-size: 13px;"><i class="fa fa-envelope me-2"></i> {{$rows->email}}</small><br><small style="font-size: 13px;"><i class="fa fa-map-marker me-2"></i> {{$rows->address ?? 'Not provided'}}</small></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-items-center mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th style="width: 5%">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectAll" @if(count($orders) == 0) disabled @endif>
                                    </div>
                                </th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['date'] ?? 'Date'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_id'] ?? 'Order Id'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['customer_details'] ?? 'Customer Details'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['order_amount'] ?? 'Order Amount'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['paid_amount'] ?? 'Paid Amount'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['outlet_name'] ?? 'Outlet Name'}}</th>
                                <th style="width: 5%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['status'] ?? 'Status'}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered align-items-center mb-0 ">
                            <tbody>
                            @foreach($orders as $row)
                                <tr>
                                    <td class="ps-4" style="width: 5%">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="selected_orders" id="selected_orders{{ $row->id }}" wire:model="selected_orders.{{ $row->id }}">
                                        </div>
                                    </td>
                                    <td style="width: 15%">
                                        <p class="text-xs px-3 mb-0">
                                            {{\Carbon\Carbon::parse($row->order_date)->format('d/m/Y')}}
                                        </p>
                                    </td>
                                    @php
                                        $id = $row->id;
                                        $a="/admin/orders/view/" .$id;
                                    @endphp
                                    <td style="width: 15%">
                                        <p class="text-xs px-3 mb-0">
                                            <a href="{{url($a)}}" target="_blank">
                                             <span class="font-weight-bold">{{$row->order_number}}</span>
                                            </a>
                                        </p>
                                    </td>
                                    @php
                                        $customer=App\Models\Customer::where('id',$row->customer_id)->first();
                                    @endphp
                                    <td style="width: 15%">
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->customer_name ?? ""}}</p>
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->phone_number ?? ""}}</p>
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{$customer->email ?? ""}}</p>
                                    </td>
                                    <td style="width: 15%" >
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{getCurrency()}}{{$row->total ?? 0}}</p>
                                    </td>
                                    @php
                                        $rcv_amount=App\Models\Payment::where('order_id', $row->id)->sum('received_amount');
                                        
                                    @endphp
                                    <td style="width: 15%" >
                                        
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{getCurrency()}}{{$rcv_amount ?? 0}}</p>

                                    </td>
                                    <td style="width: 15%" >
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{ $row->outlet ? $row->outlet->outlet_name : '' }}</p>
                                    </td>
                                    <td style="width: 5%" >
                                        <a type="button" class="badge badge-sm bg-secondary text-uppercase">{{getOrderStatus($row->status)}}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

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
                            <span class="text-sm mb-0 fw-500">{{$lang->data['outstanding_amount'] ?? 'Total Outstanding'}}:</span>
                            <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{($orders->sum('total') - $payments)}}</span>
                        </div>
                        @if($selected_orders)
                        <div class="col-md-1"class="row align-items-center px-4 mb-3" class="col-auto">
                            <button type="button" data-bs-toggle="modal" wire:click="performAction()" data-bs-target="#addpayment" class="btn btn-primary me-2 mb-0">{{$lang->data['action'] ?? 'Action'}}</button>
                        </div>
                        @endif
                        <div class="col-auto">
                            <button type="button" wire:click="downloadFile()" class="btn btn-success me-2 mb-0">{{$lang->data['download_report'] ?? 'Download Report'}}</button>
                            <a href="{{url('admin/reports/print-report/settlement/'.$from_date.'/'.$to_date.'/')}}" target="_blank">
                                <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-labelledby="addpayment" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addpayment">
                        {{ $lang->data['payment_details'] ?? 'Payment Details' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    @if ($orders && $selected_orders)
                        <div class="modal-body">
                            <div class="row g-2 align-items-center">
                                <div class=" col-12">
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['order_id'] ?? 'Order ID' }}:
                                        </div>
                                        <div class="col-auto text-sm fw-500">{{ implode(", ", $selected_orders_checked->pluck('order_number')->toArray()) }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['order_amount'] ?? 'Order Amount' }}:
                                        </div>
                                        <div class="col-auto  text-sm fw-500">
                                            {{ getCurrency() }}{{ number_format($selected_orders_checked->sum('total'), 2) }}
                                        </div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['paid_amount'] ?? 'Paid Amount' }}:
                                        </div>
                                        <div class="col-auto text-sm fw-500">
                                            {{ getCurrency() }}{{ number_format($selected_payment->sum('received_amount'), 2) }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row align-items-center">
                                        <div class="col text-sm fw-600">{{ $lang->data['balance'] ?? 'Balance' }}:</div>
                                        <div class="col-auto text-sm fw-600">
                                            {{ getCurrency() }}{{ number_format($selected_orders_checked->sum('total') - $selected_payment->sum('received_amount'), 2) }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}</label>
                                            <input type="number" class="form-control" placeholder="Enter Amount" wire:model="orderbalance" @if(!$selected_customer) disabled @endif>
                                            @error('orderbalance')
                                            <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">{{ $lang->data['payment_type'] ?? 'Payment Type' }}</label>
                                            <select class="form-select" wire:model="payment_mode">
                                                <option value="">{{ $lang->data['choose_payment_type'] ?? 'Choose Payment Type' }}</option>
                                                <option class="select-box" value="1">{{ $lang->data['cash'] ?? 'Cash' }}</option>
                                                <option class="select-box" value="2">{{ $lang->data['upi'] ?? 'UPI' }}</option>
                                                <option class="select-box" value="3">{{ $lang->data['card'] ?? 'Card' }}</option>
                                                <option class="select-box" value="4">{{ $lang->data['cheque'] ?? 'Cheque' }}</option>
                                                <option class="select-box" value="5">{{ $lang->data['bank_transfer'] ?? 'Bank Transfer' }}</option>
                                            </select>
                                            @error('payment_mode')
                                            <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <label class="form-label">{{ $lang->data['notes_remarks'] ?? 'Notes / Remarks' }}</label>
                                        <textarea class="form-control" placeholder="Enter Notes" wire:model="note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="addPayment()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
