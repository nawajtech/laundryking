<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['customer_report'] ?? 'Customer History Report'}}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{$lang->data['start_date'] ?? 'Start Date'}}</label>
                            <input type="date" class="form-control" wire:model="from_date">
                        </div>
                        <div class="col-md-6">
                            <label>{{$lang->data['end_date'] ?? 'End Date'}}</label>
                            <input type="date" class="form-control" wire:model="to_date">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-items-center mb-0">
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
                                <th style="white-space: break-spaces;" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['membership'] ?? 'Membership'}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered align-items-center mb-0 ">
                            <tbody>
                            @foreach($orders as $row)
                                <tr>
                                    <td style="width: 10%" >
                                        <p class="text-xs px-3 mb-0">
                                            <span class="font-weight-bold">{{$row->customer_name}}</span>
                                        </p>
                                    </td>
                                    @php
                                    $customr_total_ord = App\Models\Order::where('customer_id', $row->customer_id)->count();
                                    @endphp
                                    <td style="width: 10%" >
                                        <p class="text-xs px-3 mb-0">
                                            <span class="font-weight-bold">{{ $customr_total_ord }}</span>
                                        </p>
                                    </td>
                                    @php
                                    $customr_ord_btwn_date = App\Models\Order::whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('customer_id', $row->customer_id)->count();
                                    @endphp
                                    <td style="width: 10%" >
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
                                    <td style="width: 10%" >
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{ $total_garment ?? 0}}</p>
                                    </td>
                                    @php
                                    $query = App\Models\OrderDetails::query();
                                        $query->whereHas('order', function($q) use($row, $from_date, $to_date){
                                            $q->whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('customer_id', $row->customer_id);
                                        });
                                    $total_garment_btw_date = $query->sum('service_quantity');
                                    @endphp
                                    <td style="width: 10%" >
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{ $total_garment_btw_date ?? 0}}</p>
                                    </td>
                                    @php
                                        $total_ord_amnt = App\Models\Order::where('customer_id', $row->customer_id)->sum('total');
                                    @endphp
                                    <td style="width: 10%" >
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{ $total_ord_amnt }}</p>
                                    </td>
                                    @php
                                        $total_ord_amnt_btw_date = App\Models\Order::whereDate('order_date', '>=', $from_date)->whereDate('order_date', '<=', $to_date)->where('customer_id', $row->customer_id)->sum('total');
                                    @endphp
                                    <td style="width: 10%" >
                                        <p  class="text-xs px-3 font-weight-bold mb-0">{{ $total_ord_amnt_btw_date }}</p>
                                    </td>
                                    
                                    @php
                                         $amount = $payment->where('customer_id',$row->customer_id)->sum('received_amount');
                                    @endphp
                                    <td style="width: 10%" >
                                        <p  class="text-xs px-3 font-weight-bold mb-0">{{ $amount }}</p>
                                    </td>
                                    @php
                                         $amount_btwn_date = $payments->where('customer_id',$row->customer_id)->sum('received_amount');
                                    @endphp
                                    <td style="width: 10%" >
                                        <p  class="text-xs px-3 font-weight-bold mb-0">{{ $amount_btwn_date }}</p>
                                    </td>
                                    
                                    @php
                                        $last_order = App\Models\Order::where('customer_id', $row->customer_id)->orderBy('id', 'desc')->first();
                                    @endphp
                                    <td style="width: 10%" >
                                        <p  class="text-xs px-3 font-weight-bold mb-0">{{ date("d-m-Y", strtotime($last_order->order_date)) }}</p>
                                    </td>
                                    @php
                                        $membership = App\Models\Customer::where('id', $row->customer_id)->first();
                                        $membership_name = App\Models\Membership::where('id',$membership->membership)->first();
                                    @endphp
                                    <td style="width: 10%" >
                                        <p  class="text-xs px-3 font-weight-bold mb-0">{{ $membership_name->membership_name ?? ''}}</p>
                                    </td>
                                    
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row align-items-center px-4 mb-3">
                        <div class="col">
                            <span class="text-sm mb-0 fw-500">{{$lang->data['total_order_amount'] ?? 'Total Order Amount'}}:</span>
                            <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{number_format($orders->sum('total'),2)}}</span>
                        </div>
                        <div class="col">
                            <span class="text-sm mb-0 fw-500">{{$lang->data['total_received_amount'] ?? 'Total Received Amount'}}:</span>
                            <span class="text-sm text-dark ms-2 fw-600 mb-0">{{getCurrency()}}{{number_format($payment->sum('received_amount'),2)}}</span>
                        </div>
                        <div class="col-auto">
                            <button type="button" wire:click="downloadFile()" class="btn btn-success me-2 mb-0">{{$lang->data['download_report'] ?? 'Download Report'}}</button>
                            <a href="{{url('admin/reports/print-report/customer-history/'.$from_date.'/'.$to_date.'/')}}" target="_blank">
                                <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>