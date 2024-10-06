<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['order_details'] ?? 'Order Details' }}</h5>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.view_orders') }}" class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-arrow-left me-2"></i> {{ $lang->data['back'] ?? 'Back' }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="text-uppercase fw-500">{{ $outlet->outlet_name ?? 'No Outlet Select' }}</h5>
                            <p style="width:150px" class="text-sm mb-0">{{ $outlet->outlet_address ??''}}</p>
                            <p class="text-sm mb-0">{{ $outlet->outlet_phone  ?? ''}}</p>
                        </div>
                        <div class="col-auto mt-4">
                            <h6 class="text-uppercase fw-500">
                                <span> {{ $lang->data['order_id'] ?? 'Order ID' }}:</span>
                                <span class="ms-2 fw-600">#{{ $order->order_number }}</span>
                            </h6>

                            <p class="text-sm mb-1">
                                <span> {{ $lang->data['pickup outlet'] ?? 'Pickup Outlet' }}:</span>
                                <span class="fw-600 ms-2">{{ $outlet->outlet_name ?? 'No Outlet Select' }}</span>
                            </p>

                            <p class="text-sm mb-1">
                                <span> {{ $lang->data['delivery_outlet'] ?? 'Delivery Outlet' }}:</span>
                                <span class="fw-600 ms-2">{{ $deliveryoutlet->outlet_name ?? 'Store Delivery' }}</span>
                            </p>

                            <p class="text-sm mb-1">
                                <span> {{ $lang->data['order_date'] ?? 'Order Date' }}:</span>
                                <span class="fw-600 ms-2">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y h:i: A') }} </span>
                            </p>

                            <p class="text-sm mb-1">
                                <span> {{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:</span>
                                <span class="fw-600 ms-2">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y')}} {{ \Carbon\Carbon::parse($order->delivery_time)->format('h:i: A') }}</span>
                            </p>
                            <p class="text-sm mb-1">
                                <span> {{ $lang->data['delivered_date'] ?? 'Delivered Date' }}:</span>
                                @if($order->delivered_date != '')
                                <span class="fw-600 ms-2">{{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y h:i: A') }}</span>
                                @endif
                            </p>
                            <p class="text-sm mb-1">
                                <span> {{ $lang->data['delivery_type'] ?? 'Delivery Type' }}:</span>
                                <span class="fw-600 ms-2">{{ $order->delivery_type }}</span>
                            </p>
                            @if($cancelmessage)
                                <p class="text-sm mb-1" >
                                    <span class="fw-600 " style="color:tomato" > {{ $cancelmessage }}</span>
                                </p>
                            @else

                            @if($order->status == 9)
                                <p class="text-sm mb-1">
                                    <span> {{ $lang->data['order_status'] ?? 'Order Status' }}:</span>
                                    <span class="fw-600 ms-2">{{ $lang->data['Delivered'] ?? 'Delivered' }}</span>
                                </p>
                            @elseif($order->status == 10)
                                <p class="text-sm mb-1">
                                    <span> {{ $lang->data['order_status'] ?? 'Order Status' }}:</span>
                                    <span class="fw-600 ms-2">{{ $lang->data['Cancelled'] ?? 'Cancelled' }}</span>
                                </p>
                            @else
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="text-sm">{{ $lang->data['order_status'] ?? 'Order Status' }}:</span>
                                </div>
                                <div class="dropdown ms-2">
                                    @if(($order->status >= 0 && $order->status <=8) || $order->status ==11)
                                        <button class="btn btn-xs bg-secondary dropdown-toggle mb-0 text-white" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ getOrderStatus($order->status) }}
                                        </button>
                                    @endif
                                    @if(user_has_permission('order_status'))
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if(Auth::user()->user_type==1 || Auth::user()->user_type==2)
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(1)">{{ $lang->data['confirm'] ?? 'Confirm' }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(11)">{{ $lang->data['out_for_pickup'] ?? 'Out for Pickup' }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(2)">{{ $lang->data['picked_up'] ?? 'Picked Up' }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(3)">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(4)">{{ $lang->data['in_transit'] ?? 'In Transit' }}</a></li>
                                            @endif
                                            @if(Auth::user()->user_type==1)
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(5)">{{ $lang->data['processing'] ?? 'Processing' }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(6)">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</a></li>
                                            @endif
                                            @if(Auth::user()->user_type==1 || Auth::user()->user_type==2)
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(7)">{{ $lang->data['ready'] ?? 'Ready' }}</a></li>
                                            @endif
                                            @if(Auth::user()->user_type==1 || Auth::user()->user_type==2)
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(8)">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(9)">{{ $lang->data['delivered'] ?? 'Delivered' }}</a></li>
                                            @endif
                                            @if(Auth::user()->user_type==1)
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus(10)">{{ $lang->data['cancel'] ?? 'Cancel' }}</a></li>
                                            @endif
                                            @if(Auth::user()->user_type != 1)
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="update_cancel">{{ $lang->data['cancel'] ?? 'Cancel' }}</a></li>
                                            @endif
                                        </ul>
                                    @endif

                                </div>
                            </div>
                            @endif
                            @endif
                            <div>
                                @if($order->cancel_request == 2)
                                    <p class="text-sm mb-1">
                                        <span style="color:tomato" class="fw-600 "> {{ $lang->data['cancel_by'] ?? 'Cancel By' }}</span>
                                        <span style="color:tomato" class="fw-600 ">{{ $order->cancel_by }}</span>
                                    </p>
                                @endif

                                @if($order->cancel_request == 3)
                                    <p class="text-sm mb-1">
                                        <span style="color:tomato" class="fw-600 "> {{ $lang->data['declined_by'] ?? 'Order Declined By' }}.</span>
                                        <span style="color:tomato" class="fw-600">{{ $order->cancel_by }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7" style="width:50px;">#</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['service_name'] ?? 'Service Name' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['addon'] ?? 'Addons' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['ready_on'] ?? 'Ready on' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['rate'] ?? 'Rate' }}
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xs  opacity-7" style="width:50px;">
                                    {{ $lang->data['qty'] ?? 'QTY' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['total'] ?? 'Total' }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orderdetails as $item)
                                @php
                                    $query = \App\Models\OrderDetailsDetail::query();

                                    $query->whereHas('order_details', function($q) {
                                        $q->whereHas('order', function($q) {
                                            $q->whereNotNull('parent_id');
                                        });
                                    });
                                    $req_approval = $query->where('rewash_confirm' , 1)->where('order_id', $item->order_details->order_id)->latest()->first();
                                    $req_approval_id = $req_approval->id ?? 0;
                                    $service = \App\Models\Service::where('id', $item->order_details->service_id)->first();
                                    $decline = \App\Models\OrderDetailsDetail::where('rewash_confirm',3)->where('id', $item->id)->first();
                                    $decline_id = $decline->id ?? 0;
                                    $defect_requested = $item->where('is_active',1)->where('accepted',0)->where('id', $item->id)->first();
                                    $defect_decline = $item->where('is_active',0)->where('accepted',1)->where('id', $item->id)->first();
                                    $defect_accept = $item->where('is_active',1)->where('accepted',1)->where('id', $item->id)->first();
                                @endphp
                                <tr>
                                    <td>
                                        <p class="text-sm px-3 mb-0">{{ $loop->index + 1 }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div>
                                                <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}" class="avatar avatar-sm me-3">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-1 text-sm">{{ $service->service_name }} <small><?php if($service->size !='') { ?> ({{$service->size}} in feet) <?php } ?></small>
                                                </h6>

                                                <span class="text-xs fw-600 text-primary">[{{ $item->order_details->service_name }}]</span>

                                                @if($item->order_details->brand)
                                                    <small style="text-transform:uppercase">{{ $item->order_details->brand }}</small>
                                                @endif

                                                @if($decline_id == $item->id)
                                                    <h7 style="color:Tomato;">Rewash Request Declined</h7>
                                                @endif
                                                @if($req_approval_id == $item->id)
                                                    <h7 style="color:blue;">Pending Approval Request</h7>
                                                @endif
                                                @if($defect_requested)
                                                    <h7 style="color:Tomato;">Request Sent for Defected</h7>
                                                @endif
                                                @if($defect_decline)
                                                    <h7 style="color:Tomato;">Defected Item Declined</h7>
                                                @endif
                                                @if($defect_accept)
                                                    <h7 style="color:blue;">Defect Item Approved</h7>
                                                @endif
                                                @if($item->rewash_confirm ==1 || $item->rewash_confirm ==2 || $item->rewash_confirm ==3)
                                                    <a data-bs-toggle="modal" data-bs-target="#viewimage"  wire:click="view_image({{ $item->order_details->order_id }})"
                                                    class="badge badge-xs badge-success text-xs fw-500" style="max-width:110px">
                                                        <i class="ni ni-image" ></i> View Images
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                       
                                    </td>
                                    <td class="align-middle text-center">
                                        @php
                                            $addon = \App\Models\OrderAddonDetail::where('order_detail_id', $item->order_details->id)->get();
                                        @endphp
                                        <div style="display: grid; grid-template-columns: auto; width: fit-content;">
                                            @foreach($addon as $viewaddon)
                                                <span style="font-weight: 500; margin-bottom: 5px;" class="badge badge-warning">{{$viewaddon->addon_name}}: {{ getCurrency() }}{{$viewaddon->addon_price}}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($item->ready_at != NULL)
                                            <p class="text-sm px-3 mb-0"> {{ Carbon\Carbon::parse($item->ready_at)->format('d/m/Y') }}</p>
                                        @endif
                                    </td>
                                    <td class="">
                                        <p class="text-sm px-3 mb-0">{{ getCurrency() }}
                                            {{ number_format($item->order_details->service_price, 2) }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-sm px-3 mb-0"> 1 </p>
                                    </td>
                                    <td class="">
                                        <p class="text-sm px-3 mb-0">{{ getCurrency() }}
                                            {{ number_format($item->order_details->service_price, 2) }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr class="mb-0 mt-0 bg-secondary">
                <div class="card-footer px-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <h6 class="mb-2 fw-500">{{ $lang->data['invoice_to'] ?? 'Invoice To' }}:</h6>
                            <h6 class="mb-1 fw-500 text-sm">{{ $customer->name ?? 'Walk-In Customer' }}</h6>
                            <p class="text-sm mb-0">{{ $customer->phone ?? 'Phone' }}</p>
                            <p class="text-sm mb-0">{{ $customer->email ?? 'Email' }}</p>
                            @if($customer->address!='NULL')
                            <p class="text-sm mb-3">{{ $customer->address ?? 'Customer' }}</p>
                            @endif
                            @if($customer->tax_number != null || $customer->tax_number !='NULL')
                            <p class="text-sm mb-0">{{ $lang->data['vat'] ?? 'GST No' }}:
                                {{ $customer->tax_number }}
                            @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="fw-500 mb-2">{{ $lang->data['payment_details'] ?? 'Payment Details' }}:
                            </h6>
                            <div class="">
                                <div class="row mb-50 align-items-center">
                                    @if($order->sub_total>0)
                                        <div class="col text-sm">{{ $lang->data['sub_total'] ?? 'Sub Total' }}:</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($order->sub_total, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-50 align-items-center">
                                    @if($order->addon_total>0)
                                        <div class="col text-sm">{{ $lang->data['addon'] ?? 'Addon' }}:</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($order->addon_total, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-50 align-items-center">
                                    @if($order->express_charge>0)
                                        <div class="col text-sm">{{ $lang->data['express_charge'] ?? 'Express Charge' }}:</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($order->express_charge, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <?php $discount = ($order->sub_total + $order->addon_total + $order->express_charge) * $order->discount / 100  ?>
                                    
                                    @if($discount>0)
                                        <div class="col text-sm">{{ $lang->data['discount'] ?? 'Discount' }} ({{ $order->discount }}%):</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($discount, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-50 align-items-center">
                                    @if($order->voucher_discount>0)
                                        <div class="col text-sm">{{ $lang->data['discount'] ?? 'Voucher Discount' }}:</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($order->voucher_discount, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-50 align-items-center">
                                    @if($order->delivery_charge>0)
                                        <div class="col text-sm">{{ $lang->data['delivery_charge'] ?? 'Delivery Charge' }}:</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($order->delivery_charge, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-50 align-items-center">
                                    @if($order->cgst_amount>0)
                                        <div class="col text-sm">{{ $lang->data['cgst'] ?? 'CGST' }}
                                            ({{ $order->cgst_percentage }}%):</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($order->cgst_amount, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row mb-3 align-items-center">
                                    @if($order->sgst_amount>0)
                                        <div class="col text-sm">{{ $lang->data['sgst'] ?? 'SGST' }}
                                            ({{ $order->sgst_percentage }}%):</div>
                                        <div class="col-auto text-sm">{{ getCurrency() }}
                                            {{ number_format($order->sgst_amount, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="row align-items-center">
                                    <div class="col text-sm fw-600">
                                        {{ $lang->data['gross_total'] ?? 'Gross Total' }}:
                                    </div>
                                    <div class="col-auto text-sm text-dark fw-600">{{ getCurrency() }}
                                        {{ number_format($order->total, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="bg-secondary">
                        <p><b>Pickup Location -</b><?php if ($order->pickup_address != '') { ?> {{ $order->pickup_flat_number }},{{ $order->pickup_area }} {{ $order->pickup_address }} Pin -{{ $order->pickup_pincode }} <span class="badge rounded-pill bg-warning text-white"><?php if($order->pickup_address_type == 'Other'){ echo $order->pickup_other; }else{ echo $order->pickup_address_type; } ?></span> <?php } else { ?> <span style="color:#ff0000;">No pickup loation selected</span> <?php } ?></p>
                        <p><b>Delivery Location -</b> <?php if ($order->delivery_address != '') { ?> {{ $order->delivery_flat_number }},{{ $order->delivery_area }} {{ $order->delivery_address }} Pin -{{ $order->delivery_pincode }} <span class="badge rounded-pill bg-warning text-white"><?php if($order->delivery_address_type == 'Other'){ echo $order->delivery_other; }else{ echo $order->delivery_address_type; } ?></span> <?php } else { ?> <span style="color:#ff0000;">No delivery loation selected</span> <?php } ?></p>
                        <hr class="bg-secondary">
                        <div class="col-md-2">
                            <h6 class="mb-2 text-sm fw-500">{{ $lang->data['notes'] ?? 'Notes' }}:</h6>
                        </div>
                        <div class="col-md-10">
                            <p class="text-sm mb-0">{{ $order->note }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-2 text-sm fw-500">{{ $lang->data['feedback'] ?? 'Feedback' }}:</h6>
                        </div>
                        <div class="col-md-10">
                            <p class="text-sm mb-0">{{ $order->feedback }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-2 text-sm fw-500">{{ $lang->data['instruction'] ?? 'Instructions' }}:</h6>
                        </div>
                        <div class="col-md-10">
                            <p class="text-sm mb-0">{{ $order->instruction }}</p>
                        </div>
                        
                        @if($order->cashback_amount > 1 )

                            <div class="alert alert-success text-white" role="alert">
                              Congratulations! Cashback of {{ getCurrency() }} {{ number_format($order->cashback_amount, 2) }} will be credited in customer wallet after completion of the order.
                            </div>
                        @endif
                        <div class="mt-4 position-relative text-center">
                            <p class="text-sm fw-500 mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                                Powered by <a href="{{url('/')}}" class="text-dark fw-600" target="_blank">{{ getApplicationName() }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card mb-4">
                <div class="card-body p-4">
                    @if($order->status)
                        <p class="text-sm mb-1">
                            <span> Order Created By:</span>
                            <span class="fw-600">{{ $user->name ?? '' }}</span>
                        </p>
                        <h6 class="mb-3 fw-500 mt-2">{{ $lang->data['workstation'] ?? 'Workstation' }}</h6>
                        <p class="text-sm mb-1">
                            <span class="fw-600">{{ $order->workstation->workstation_name ?? '' }}</span>
                        </p>

                        @if($order->workstation_id == 0)
                            <select wire:model="assign_workstation" class="form-control bg-dark text-white">
                                <option value="0">Choose Workstation</option>
                                @foreach($workstation as $ws)
                                    <option value="{{$ws->id}}">{{ $ws->workstation_name }}</option>
                                @endforeach
                            </select>
                        @endif
                        <hr>
                        @if($order->pickup_address != '')
                            @If($order->status==1 )
                                <h6 class="mb-3 fw-500 mt-2">{{ $lang->data['assign_pickup_driver'] ?? 'Assign Pickup Driver' }}</h6>
                                @if($order->pickup_driver)
                                    <p class="text-sm mb-1">
                                        <span> Name:</span>
                                        <span class="fw-600">{{ $order->pickup_driver->name ?? '' }}</span>
                                    </p>
                                    <p class="text-sm mb-1">
                                        <span> PH:</span>
                                        <span class="fw-600">{{ $order->pickup_driver->phone ?? '' }}</span>
                                    </p>
                                @endif

                                @if(user_has_permission('financial_year'))
                                    <select wire:model="assign_pickupdriver" class="form-control bg-dark text-white">
                                        <option value="0">Choose Driver</option>
                                        @foreach($driver as $pd)
                                            <option value="{{$pd->user->id}}">{{ $pd->user->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <hr>
                            @endif
                            @If($order->status >1)
                                <h6 class="mb-3 fw-500 mt-2">{{ $lang->data['assign_pickup_driver'] ?? 'Assign Pickup Driver' }}</h6>
                                @if($order->pickup_driver)
                                    <p class="text-sm mb-1">
                                        <span> Name:</span>
                                        <span class="fw-600">{{ $order->pickup_driver->name ?? '' }}</span>
                                    </p>
                                    <p class="text-sm mb-1">
                                        <span> PH:</span>
                                        <span class="fw-600">{{ $order->pickup_driver->phone ?? '' }}</span>
                                    </p>
                                @endif
                                <hr>
                            @endif
                        @else
                            <p style='color:#ff0000' class="mt-2">{{ $lang->data['This order pickup from outlet'] ?? 'This order pickup from Outlet'}}</p>
                        @endif
                        @if($order->delivery_address != '')
                            @if($order->status ==7)
                                <h6 class="mb-3 fw-500 mt-2">{{ $lang->data['assign_delivery_driver'] ?? 'Assign Delivery Driver' }}</h6>
                                @if($order->delivery_driver)
                                    <p class="text-sm mb-1">
                                        <span> Name:</span>
                                        <span class="fw-600">{{ $order->delivery_driver->name ?? '' }}</span>
                                    </p>
                                    <p class="text-sm mb-1">
                                        <span> PH:</span>
                                        <span class="fw-600">{{ $order->delivery_driver->phone ?? '' }}</span>
                                    </p>
                                @endif
                                @if(user_has_permission('financial_year'))
                                    <select wire:model="assign_deliverydriver" class="form-control bg-dark text-white">
                                        <option value="0">Choose Driver</option>
                                        @foreach($driver as $dd)
                                            <option value="{{$dd->user->id}}">{{ $dd->user->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <hr>
                            @endif
                        @endif
                        @if($order->status >=8 && $order->status !=11 && $order->delivery_address != '')
                            <h6 class="mt-2">{{ $lang->data['assign_delivery_driver'] ?? 'Assign Delivery Driver' }}</h6>
                            @if($order->delivery_driver)
                                <p class="text-sm mb-1">
                                    <span> Name:</span>
                                    <span class="fw-600">{{ $order->delivery_driver->name ?? '' }}</span>
                                </p>
                                <p class="text-sm mb-1">
                                    <span> PH:</span>
                                    <span class="fw-600">{{ $order->delivery_driver->phone ?? '' }}</span>
                                </p>
                            @endif
                            <hr>
                        @endif
                    @endif
                    <h6 class="mb-3 fw-500 mt-2">{{ $lang->data['payments'] ?? 'Payments' }}</h6>
                    <div class="timeline timeline-one-side">
                        @foreach ($payments as $item)
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fa fa-dot-circle-o text-secondary"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">{{ getCurrency() }}
                                        {{ number_format($item->received_amount, 2) }}
                                    </h6>
                                    <p class="text-secondary text-xs mt-1 mb-0">
                                        <span>{{ Carbon\Carbon::parse($item->payment_date)->format('d/m/Y') }}</span>
                                        <span class="ms-2 fw-600 text-uppercase">[{{ getpaymentMode($item->payment_type) }}]</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        @if ($balance > 0)
                            @if($order->status != 4)
                                <div class="col-12">
                                    <a data-bs-toggle="modal" data-bs-target="#addpayment" type="button" class="badge badge-success mb-3 w-100 py-3 fw-600">
                                        {{ $lang->data['add_payment'] ?? 'Add Payment' }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="col-12">
                                <a type="button" class="badge badge-light disabled mb-3 w-100 py-3 fw-600">
                                    {{ $lang->data['fully_paid'] ?? 'Fully Paid' }}
                                </a>
                            </div>
                        @endif

                        <div class="col-12">
                            <a href="{{ url('admin/orders/print-order/' . $order->id) }}" target="_blank" type="button" class="btn btn-icon btn-warning mb-0 w-100">
                                {{ $lang->data['print_invoice'] ?? 'Print Invoice' }}
                            </a>
                        </div>

                        @if($order->status >=2 && $order->status !==11)
                            <div class="col-12" style="margin-top:15px;">
                                <a href="{{ url('admin/orders/tag-generate/' . $order->id) }}" target="_blank" type="button" class="btn btn-icon btn-success mb-0 w-100">
                                    {{ $lang->data['print_tag'] ?? 'Print Tag' }}
                                </a>
                            </div>
                        @endif

                        @if($order->status == 9 && $order->children->isEmpty() && ($expiry_date>$currentDateTime))
                            <div class="col-12" style="margin-top:15px;">
                                <a data-bs-toggle="modal" wire:click="orderdetails({{ $order->id }})" data-bs-target="#orderdetails" target="_blank" type="button" class="btn btn-icon btn-danger mb-0 w-100">
                                    {{ $lang->data['Rewash'] ?? 'Rewash' }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $rcv_amount = \App\Models\Wallet::where('order_id', $order->id)->sum('receive_amount');
                $deducted_amount = \App\Models\Wallet::where('order_id', $order->id)->sum('deducted_amount');
                $refund_amount = $rcv_amount;
            @endphp
            @if ($refund_amount)
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="col-md-10">
                        <p class="text-sm mb-1" style="color:red">
                            <span class="me-2">{{ $lang->data['refund_amount'] ?? 'Refunded Amount' }}:</span>
                            <span class="font-weight-bold">{{ getCurrency() }}
                                {{ number_format($refund_amount, 2) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
            
        </div>
    <div class="modal fade" id="image" tabindex="-1" role="dialog" aria-labelledby="image" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="image">{{ $lang->data['image'] ?? 'Image' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-12">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['close'] ?? 'Close' }}</button>
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
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class=" col-12">
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['customer_name'] ?? 'Customer Name' }}:</div>
                                    <div class="col-auto text-sm fw-500">{{ $customer->name ?? '' }}</div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['order_id'] ?? 'Order ID' }}:</div>
                                    <div class="col-auto text-sm fw-500">{{ $order->order_number }}</div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['order_date'] ?? 'Order Date' }}:</div>
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
                                    <div class="col-auto  text-sm fw-500">{{ getCurrency() }}
                                        {{ number_format($order->total, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">
                                        {{ $lang->data['paid_amount'] ?? 'Paid Amount' }}:
                                    </div>
                                    <div class="col-auto text-sm fw-500">{{ getCurrency() }}
                                        {{ number_format($order->total - $balance, 2) }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col text-sm fw-600">{{ $lang->data['balance'] ?? 'Balance' }}:</div>
                                    <div class="col-auto text-sm fw-600">{{ getCurrency() }}
                                        {{ number_format($balance, 2) }}
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}</label>
                                        <input type="number" class="form-control" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}" wire:model="paid_amount" min="0" oninput="validity.valid||(value='');">
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label">{{ $lang->data['payment_type'] ?? 'Payment Type' }}</label>
                                        <select class="form-select" wire:model="payment_type">
                                            <option value="">
                                                {{ $lang->data['choose_payment_type'] ?? 'Choose Payment Type' }}
                                            </option>
                                            <option class="select-box" value="1">
                                                {{ $lang->data['cash'] ?? 'Cash' }}
                                            </option>
                                            <option class="select-box" value="2">
                                                {{ $lang->data['upi'] ?? 'UPI' }}
                                            </option>
                                            <option class="select-box" value="3">
                                                {{ $lang->data['card'] ?? 'Card' }}
                                            </option>
                                            <option class="select-box" value="4">
                                                {{ $lang->data['cheque'] ?? 'Cheque' }}
                                            </option>
                                            <option class="select-box" value="5">
                                                {{ $lang->data['bank_transfer'] ?? 'Bank Transfer' }}
                                            </option>
                                        </select>
                                    </div>
                                    @error('payment_type')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('paid_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="button" class="btn btn-primary" wire:click.prevent="addPayment">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="orderdetails" tabindex="-1" role="dialog" aria-labelledby="orderdetails" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document" style="max-width:750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600"> Order Details &nbsp;<span style="font-weight: 500;" class="badge badge-info">Total Order Quantity - {{ $totalqty }} item(s) </span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-12">
                            <table class="table align-items-center mb-0">
                                <tr>
                                    <td><b>Order Number</b></td>
                                    <td>{{ $ordernumber }}</td>
                                    <td><b>Customer Name</b></td>
                                    <td>{{ $customername ?? 'Walk in Customer' }}</td>
                                </tr>
                                <tr>
                                    <td><b>Pickup Outlet</b></td>
                                    <td>{{ $outlet->outlet_name ?? 'No Outlet Select' }}</td>
                                    <td><b>Delivery Date</b></td>
                                    <td>{{ \Carbon\Carbon::parse($deliverydate)->format('d/M/Y') }} - <?php echo date('l', strtotime(\Carbon\Carbon::parse($deliverydate)->format('d/M/Y'))); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Delivery Outlet</b></td>
                                    <td>{{ $deliveryoutlet->outlet_name ?? 'Store Delivery' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7"></th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7">
                                            {{ $lang->data['service_name'] ?? 'Service Name' }}
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7">
                                            {{ $lang->data['addon_name'] ?? 'Addon' }}
                                        </th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7">
                                            {{ $lang->data['color'] ?? 'Color' }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $orderdetails = \App\Models\OrderDetailsDetail::where('order_id', $orderid)->where('is_active', 0)->where('accepted', 0)->get();
                                    @endphp
                                    @foreach ($orderdetails as $item)
                                        @php
                                            $service = \App\Models\Service::where('id', $item->order_details->service_id)->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="addon" id="addon{{ $item->id }}" wire:model="selected_defected.{{ $item->order_detail_id }}.{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm px-3 mb-0">{{ $loop->index + 1 }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div>
                                                        <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}" class="avatar avatar-sm me-3">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h3 class="mb-1 text-sm">{{ $item->garment_tag_id }}</h3>
                                                        <h6 class="mb-1 text-sm">{{ $service->service_name }}</h6>
                                                        <span class="text-xs fw-600 text-primary">[{{ $item->order_details->service_name }}]</span>
                                                        <small style="text-transform:uppercase">{{ $item->order_details->brand }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $addon = \App\Models\OrderAddonDetail::where('order_detail_id', $item->order_details->id)->get();
                                                @endphp

                                                @foreach($addon as $viewaddon)
                                                    <span style="font-weight: 500;" class="badge badge-warning">{{$viewaddon->addon_name}}: {{ getCurrency() }}{{$viewaddon->addon_price}}</span><br>
                                                @endforeach
                                            </td>
                                            <td class="px-4">
                                                @if($item->order_details->color_code!="")
                                                    <button class="btn" style="background-color: {{$item->order_details->color_code}}"></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['close'] ?? 'Close' }}</button>
                    <button type="button" class="btn btn-primary" wire:click="createRewash()">{{ $lang->data['save'] ?? 'Save' }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmstatus" tabindex="-1" role="dialog" aria-labelledby="confirmstatus" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Confirm Change Status
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Are you sure want to change status of all products for this order?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="confirmstatus()">{{ $lang->data['delete'] ?? 'Confirm' }}</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" class="modal fade " id="viewimage" tabindex="-1" role="dialog"
        aria-labelledby="viewimage" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                    Images for Rewash</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @php 
                        $i=1;
                        @endphp
                        @foreach (explode(',', $image) as $img)
                        <div class="col-md-4" >
                            <a href="#img{{$i}}">
                                <img style="width:100%;height:100px" src="{{ asset('uploads/rewash/' .$img) }}">
                            </a>
                            <a href="#" class="lightbox" id="img{{$i}}">
                                <span style="background-image: url({{ asset('uploads/rewash/' .$img) }})"></span>
                            </a>
                        </div>
                        @php 
                        $i++;
                        @endphp
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                </div>
            </div>
        </div>
    </div>
    
</div>
