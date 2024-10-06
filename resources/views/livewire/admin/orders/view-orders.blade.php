<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['orders'] ?? 'Orders' }} </h5>
        </div>
        <div class="col-auto">
            @if(user_has_permission('create_order'))
                <a href="{{ route('admin.create_orders') }}" class="btn btn-icon btn-3 btn-white text-primary mb-0">
                    <i class="fa fa-plus me-2"></i>{{ $lang->data['add_new_order'] ?? 'Add New Order' }}
                </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-<?php if (Auth::user()->user_type ==2 ) { ?>9<?php } else { ?>6<?php } ?>">
                            <input type="text" class="form-control" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search_query">
                        </div>
                        <?php if (Auth::user()->user_type !=2 ) { ?>
                            <div class="col-md-3">
                                <select class="form-select" wire:model="outlet_filter">
                                    <option class="select-box" value="">
                                        {{ $lang->data['all_outlets'] ?? 'All Outlets' }}
                                    </option>
                                    @foreach($outlet as $showoutlet)
                                        <option value="{{ $showoutlet->id }}">{{ $showoutlet->outlet_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        <?php } ?>

                        <div class="col-md-3">
                            <select class="form-select" wire:model="order_filter">
                                <option class="select-box" value="-1">{{ $lang->data['all_orders'] ?? 'All Orders' }}</option>
                                <option class="select-box" value="0">{{ $lang->data['pending'] ?? 'Pending' }} </option>
                                <option class="select-box" value="1"> {{ $lang->data['confirm'] ?? 'Confirm' }} </option>
                                <option class="select-box" value="11"> {{ $lang->data['out_for_pickup'] ?? 'Out for Pickup' }} </option>
                                <option class="select-box" value="2"> {{ $lang->data['picked_up'] ?? 'Picked Up' }} </option>
                                <option class="select-box" value="3"> {{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</option>
                                <option class="select-box" value="4">{{ $lang->data['in_transit'] ?? 'In Transit' }}</option>
                                <option class="select-box" value="5">{{ $lang->data['processing'] ?? 'Processing' }}</option>
                                <option class="select-box" value="6">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</option>
                                <option class="select-box" value="7">{{ $lang->data['ready'] ?? 'Ready' }}</option>
                                <option class="select-box" value="8">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }} </option>
                                <option class="select-box" value="9">{{ $lang->data['delivered'] ?? 'Delivered' }}</option>
                                <option class="select-box" value="10">{{ $lang->data['cancel'] ?? 'Cancel' }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['order_info'] ?? 'Order Info' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs  opacity-7">
                                    {{ $lang->data['customer'] ?? 'Customer' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs  opacity-7">
                                    {{ $lang->data['order_amount'] ?? 'Order Amount' }}
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['status'] ?? 'Status' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['payment'] ?? 'Payment' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                    {{ $lang->data['created_by'] ?? 'Created By' }}
                                </th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                            </thead>
                            <tbody wire:poll="refresh">
                                @foreach ($orders as $item)
                                    <tr @if($item->status == 10) style="background:rgb(255, 30, 30,.1);" @elseif($item->cancel_request == 1) style="background:rgb(240, 173, 78, 0.5);" @endif>
                                        <td>
                                            <p class="text-sm px-3 mb-0">
                                                <span class="me-2">{{ $lang->data['order_id'] ?? 'Order ID' }}:</span>
                                                <span class="font-weight-bold">{{ $item->order_number }}</span>
                                            </p>
                                            <p class="text-sm px-3 mb-0">
                                                <span class="me-2">{{ $lang->data['order_date'] ?? 'Order Date' }}:</span>
                                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($item->order_date)->format('d/m/y') }}</span>
                                            </p>
                                            <p class="text-sm px-3 mb-0">
                                                <span class="me-2">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:</span>
                                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/y') }}</span>
                                            </p>
                                            <p class="text-sm px-3 mb-0"><span class="badge rounded-pill bg-warning">{{ $item->outlet->outlet_name ?? "" }}</span></p>
                                        </td>
                                        <td>
                                            <p class="text-sm px-3 font-weight-bold mb-0">
                                                {{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}
                                            </p>
                                            <p class="text-sm px-3 mb-0">{{ $item->phone_number ?? '-' }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm px-3 font-weight-bold mb-0">{{ getCurrency() }}
                                                {{ number_format($item->total, 2) }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($item->cancel_request == 1)
                                                 <a type="button" class="badge badge-sm text-uppercase" style="background: #ef7d04;">{{ $lang->data['pending'] ?? 'Cancel Request Sent' }}</a>
                                            
                                            @elseif ($item->status == 0)
                                                <a type="button" class="badge badge-sm bg-secondary text-uppercase">{{ $lang->data['pending'] ?? 'Pending' }}</a>
                                            @elseif($item->status == 1)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#83ce2d;">{{ $lang->data['confirm'] ?? 'Confirm' }}</a>
                                            @elseif($item->status == 2)
                                                <a type="button" class="badge badge-sm bg-primary text-uppercase">{{ $lang->data['picked_up'] ?? 'Picked Up' }}</a>
                                            @elseif($item->status == 3)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#FF597B;">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</a>
                                            @elseif($item->status == 4)
                                                <a type="button" class="badge badge-sm bg-info text-uppercase">{{ $lang->data['in_transit'] ?? 'In Transit' }}</a>
                                            @elseif($item->status == 5)
                                                <a type="button" class="badge badge-sm bg-light text-uppercase" style="color:#000 !important;">{{ $lang->data['processing'] ?? 'Processing' }}</a>
                                            @elseif($item->status == 6)
                                                <a type="button" class="badge badge-sm bg-dark text-uppercase">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</a>
                                            @elseif($item->status == 7)
                                                <a type="button" class="badge badge-sm bg-warning text-uppercase">{{ $lang->data['ready'] ?? 'Ready' }}</a>
                                            @elseif($item->status == 8)
                                                <a type="button" class="badge badge-sm bg-success text-uppercase" style="background:#8b38b2 !important;">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }}</a>
                                            @elseif($item->status == 9)
                                                <a type="button" class="badge badge-sm bg-success text-uppercase">{{ $lang->data['delivered'] ?? 'Delivered' }}</a>
                                            @elseif($item->status == 10)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#FF1E1E;">{{ $lang->data['cancel'] ?? 'Cancel' }}</a>
                                            @elseif($item->status == 11)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#3003fc;">{{ $lang->data['out_for_pickup'] ?? 'Out for Pickup' }}</a>
                                            @endif
                                        </td>
                                        <td class="px-3">
                                            @php
                                                $paidamount = \App\Models\Payment::where('order_id', $item->id)->sum('received_amount');
                                                $totalpaidamount = $paidamount;
                                                $rcv_amount = \App\Models\Wallet::where('order_id', $item->id)->sum('receive_amount');
                                                $deducted_amount = \App\Models\Wallet::where('order_id', $item->id)->sum('deducted_amount');
                                                $refund_amount = $rcv_amount;
                                            @endphp
                                            <p class="text-sm mb-0">
                                                <span class="me-2">{{ $lang->data['total_amount'] ?? 'Total Amount' }}:</span>
                                                <span class="font-weight-bold">{{ getCurrency() }}
                                                    {{ number_format($item->total, 2) }}
                                                </span>
                                            </p>
                                            <p class="text-sm mb-0">
                                                <span class="me-2">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}:</span>
                                                <span class="font-weight-bold">{{ getCurrency() }}
                                                    {{ number_format($totalpaidamount, 2) }}
                                                </span>
                                            </p>
                                            @if ($refund_amount)
                                            <p class="text-sm mb-1" style="color:red">
                                                <span class="me-2">{{ $lang->data['refund_amount'] ?? 'Refunded Amount' }}:</span>
                                                <span class="font-weight-bold">{{ getCurrency() }}
                                                    {{ number_format($refund_amount, 2) }}
                                                </span>
                                            </p>
                                            @endif
                                            @if ($totalpaidamount < $item->total)
                                                @if($item->status == 10)
                                                
                                                @elseif($item->status != 4)
                                                    <a data-bs-toggle="modal" data-bs-target="#addpayment" wire:click="payment({{ $item->id }})" type="button" class="badge badge-xs badge-success text-xs fw-600">
                                                        {{ $lang->data['add_payment'] ?? 'Add Payment' }}
                                                    </a>
                                                @endif
                                            @else
                                                <a data-bs-toggle="modal" type="button" class="badge badge-xs badge-dark text-xs fw-600">
                                                    {{ $lang->data['fully_paid'] ?? 'Fully Paid' }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0 text-uppercase">
                                                {{ $item->user->name ?? "" }}
                                            </p>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.view_single_order', $item->id) }}" type="button" class="badge badge-xs badge-primary text-xs fw-600">
                                                {{ $lang->data['view'] ?? 'View' }}
                                            </a>
                                            @if(user_has_permission('edit_order'))
                                            @if(Auth::user()->user_type ==1 && $item->status < 9 || $item->status == 11)
                                                <a href="{{ route('admin.edit_single_order', $item->id) }}" type="button" class="badge badge-xs badge-warning text-xs fw-600">
                                                    {{ $lang->data['edit'] ?? 'Edit' }}
                                                </a>
                                            @endif
                                            @endif
                                            @if(user_has_permission('edit_order'))
                                            @if(Auth::user()->user_type ==2 && $item->status > 3 && $item->status < 7)
                                                <a href="{{ route('admin.edit_single_order', $item->id) }}" type="button" class="badge badge-xs badge-warning text-xs fw-600">
                                                    {{ $lang->data['edit'] ?? 'Edit' }}
                                                </a>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($hasMorePages)
                            <div
                                x-data="{
                                    init () {
                                        let observer = new IntersectionObserver((entries) => {
                                            entries.forEach(entry => {
                                                if (entry.isIntersecting) {
                                                    @this.call('loadOrders')
                                                    console.log('loading...')
                                                }
                                            })
                                        }, {
                                            root: null
                                        });
                                        observer.observe(this.$el);
                                    }
                                }"
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
                                <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                                    Loading...
                                    <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                        {{ $lang->data['payment_details'] ?? 'Payment Details' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    @if ($order)
                        <div class="modal-body">
                            <div class="row g-2 align-items-center">
                                <div class=" col-12">
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['payment_details'] ?? 'Payment Details' }}:
                                        </div>
                                        <div class="col-auto text-sm fw-500">{{ $customer_name }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['order_id'] ?? 'Order ID' }}:</div>
                                        <div class="col-auto text-sm fw-500">{{ $order->order_number }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['order_date'] ?? 'Order Detail' }}:
                                        </div>
                                        <div class="col-auto  text-sm fw-500">
                                            {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:
                                        </div>
                                        <div class="col-auto  text-sm fw-500">
                                            {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['order_amount'] ?? 'Order Amount' }}:
                                        </div>
                                        <div class="col-auto  text-sm fw-500">
                                            {{ getCurrency() }}{{ number_format($order->total, 2) }}
                                        </div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">
                                            {{ $lang->data['paid_amount'] ?? 'Paid Amount' }}:
                                        </div>
                                        <div class="col-auto text-sm fw-500">
                                            {{ getCurrency() }}{{ number_format($paid_amount, 2) }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row align-items-center">
                                        <div class="col text-sm fw-600">{{ $lang->data['balance'] ?? 'Balance' }}:</div>
                                        <div class="col-auto text-sm fw-600">
                                            {{ getCurrency() }}{{ number_format($order->total - $paid_amount, 2) }}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}</label>
                                            <input type="number" class="form-control" placeholder="Enter Amount" wire:model="balance" min="0" oninput="validity.valid||(value='');">
                                            @error('balance')
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
                                                <option class="select-box" value="6">{{ $lang->data['lk_credit'] ?? 'LK Credit '.getCurrency() .$wallet_amount}}</option>
                                            </select>
                                            @error('payment_mode')
                                            <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                            @error('wallet_amount')
                                                <span class="error text-danger err">{{ $message }}</span>
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