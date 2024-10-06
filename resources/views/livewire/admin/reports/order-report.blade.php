<div>
<div class="row align-items-center justify-content-between mb-4">
    <div class="col">
        <h5 class="fw-500 text-white">{{$lang->data['order_report'] ?? 'Order Report'}}</h5>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header p-4">
                <div class="row">
                    <div class="col-md-3">
                        <label>{{$lang->data['start_date'] ?? 'Start Date'}}</label>
                        <input type="date" class="form-control" wire:model="from_date">
                    </div>
                    <div class="col-md-3">
                        <label>{{$lang->data['end_date'] ?? 'End Date'}}</label>
                        <input type="date" class="form-control" wire:model="to_date">
                    </div>
                    <div class="col-md-3">
                    <label>{{$lang->data['outlate_name'] ?? 'Outlet name'}}</label>
                        <select class="form-control" wire:model="outlet">
                            <option value="0">Choose Outlet</option>
                            @foreach($outlets as $outletss)
                                <option value="{{ $outletss->id }}">{{ $outletss->outlet_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>{{$lang->data['status'] ?? 'Status'}}</label>
                        <select class="form-select" wire:model="status">
                            <option class="select-box" value="-1">{{$lang->data['all_orders'] ?? 'All Orders'}}</option>
                            <option class="select-box" value="0">{{$lang->data['pending'] ?? 'Pending'}}</option>
                            <option class="select-box" value="1">{{$lang->data['confirm'] ?? 'Confirm'}}</option>
                            <option class="select-box" value="2">{{$lang->data['picked_up'] ?? 'Picked Up'}}</option>
                            <option class="select-box" value="3">{{$lang->data['to_be_processed'] ?? 'To be Processed'}}</option>
                            <option class="select-box" value="4">{{$lang->data['in_transit'] ?? 'In Transit'}}</option>
                            <option class="select-box" value="5">{{$lang->data['processing'] ?? 'Processing'}}</option>
                            <option class="select-box" value="6">{{$lang->data['sent_to_store'] ?? 'Sent to Store'}}</option>
                            <option class="select-box" value="7">{{$lang->data['ready'] ?? 'Ready'}}</option>
                            <option class="select-box" value="8">{{$lang->data['out_for_delivery'] ?? 'Out for Delivery'}}</option>
                            <option class="select-box" value="9">{{$lang->data['delivered'] ?? 'Delivered'}}</option>
                            <option class="select-box" value="9">{{$lang->data['cancel'] ?? 'Cancel'}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['date'] ?? 'Date'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_id'] ?? 'Order ID'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['customer'] ?? 'Customer'}}</th>
                                <th style="width: 15%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['outlet'] ?? 'Outlet Code'}}</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['order_amount'] ?? 'Order Amount'}}</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['status'] ?? 'Status'}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-bordered align-items-center mb-0 ">
                        <tbody>
                            @foreach($orders as $row)
                            <tr>
                                <td style="width: 15%" >
                                    <p class="text-xs px-3 mb-0">
                                        {{\Carbon\Carbon::parse($row->order_date)->format('d/m/Y')}}
                                    </p>
                                </td>
                                @php
                                $id = $row->id;
                                $a="/admin/orders/view/" .$id;
                                @endphp
                                <td style="width: 15%" >
                                    <p class="text-xs px-3 mb-0">
                                        <a href="{{url($a)}}" target="_blank">
                                        <span class="font-weight-bold">{{$row->order_number}}</span>
                                        </a>
                                    </p>
                                </td>
                                <td style="width: 15%" >
                                    <p class="text-xs px-3 font-weight-bold mb-0">{{$row->customer_name ?? ""}}</p>
                                </td>
                                <td style="width: 15%" >
                                    <p class="text-xs px-3 font-weight-bold mb-0">{{$row->outlet->outlet_code ?? ""}}</p>
                                </td>
                                <td style="width: 21.3%" >
                                    <p class="text-xs px-3 font-weight-bold mb-0">{{getCurrency()}}{{number_format($row->total,2)}}</p>
                                </td>
                                <td style="width: 20%" >
                                    <a class="text-xs px-3 font-weight-bold mb-0">{{getOrderStatus($row->status)}}</a>
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
                        <span class="text-sm mb-0 fw-500">{{$lang->data['total_order_amount'] ?? 'Total Order Amount'}}:</span>
                        <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{number_format($orders->sum('total'),2)}}</span>
                    </div>
                    <div class="col-auto">
                        <button type="button" wire:click="downloadFile()" class="btn btn-success me-2 mb-0">{{$lang->data['download_report'] ?? 'Download Report'}}</button>
                        <a href="{{url('admin/reports/print-report/order/'.$from_date.'/'.$to_date.'/'.$status.'/'.$outlet)}}" target="_blank">                  
                            <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>