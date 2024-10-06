<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['cancel_request'] ?? 'Cancel Request' }} </h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-<?php if (Auth::user()->user_type ==2 ) { ?>12<?php } else { ?>12<?php } ?>">
                            <input type="text" class="form-control" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search_query">
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-1">
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
                                    
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ $lang->data['created_by'] ?? 'Created By' }}
                                    </th>

                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ $lang->data['status'] ?? 'Status' }}
                                    </th>

                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ $lang->data['cancel_request'] ?? 'Cancel Request' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody wire:poll="refresh">
                                @if(count($orders) > 0)
                                    @foreach ($orders as $item)
                                        <tr @if($item->status == 10) style="background:rgb(255, 30, 30,.1);" @endif>
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
                                            <td>
                                                <p class="text-sm mb-0 text-uppercase">
                                                    {{ $item->user->name ?? "" }}
                                                </p>
                                            </td>
                                            <td class="text-sm px-3 mb-0">
                                                @if ($item->status == 0)
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
                                            <td>
                                            <a data-bs-toggle="modal" data-bs-target="#approvereq" type="button" wire:click="cancelid({{ $item->id }})"
                                                class="ms-2 badge badge-xs badge-success text-xs fw-600">
                                                {{ $lang->data['approve'] ?? 'Approve' }}
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#rewashreq" type="button" wire:click="cancelid({{ $item->id }})"
                                                class="ms-2 badge badge-xs badge-danger text-xs fw-600">
                                                {{ $lang->data['decline'] ?? 'Decline' }}
                                            </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" style="text-align: center;">
                                            <?php echo "No data found"; ?>
                                        </td>
                                    </tr>
                                @endif
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
                                            <input type="number" class="form-control" placeholder="Enter Amount" wire:model="balance">
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

    <div wire:ignore.self class="modal fade" class="modal fade " id="rewashreq" tabindex="-1" role="dialog"
        aria-labelledby="rewashreq" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Request for Cancel</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure want to decline?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary"
                        wire:click.prevent="decline()">{{ $lang->data['decline'] ?? 'Decline' }}</button>
                </div>
                
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" class="modal fade " id="approvereq" tabindex="-1" role="dialog"
        aria-labelledby="approvereq" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Request for Cancel</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure want to Cancel?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary"
                        wire:click.prevent="approve()">{{ $lang->data['approve'] ?? 'Approve' }}</button>
                </div>
                
            </div>
        </div>
    </div>
</div>