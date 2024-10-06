<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['order_status_screen'] ?? 'Order Status Screen' }}</h5>
        </div>
        <div class="col-auto">
            @if(Auth::user()->user_type==1)
                <a href="{{ route('admin.create_orders') }}" class="btn btn-icon btn-3 btn-white text-primary mb-0">
                    <i class="fa fa-plus me-2"></i> {{ $lang->data['add_new_order'] ?? 'Add New Order' }}
                </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4" style="border-bottom:1px solid #ccc;">
                    <div class="row">
                        <div class="col-md-<?php if (Auth::user()->user_type ==2 ) { ?>12<?php } elseif(Auth::user()->user_type ==3){ ?>9<?php }else { ?>6 <?php } ?>">
                            <input type="text" class="form-control"
                                   placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search">
                        </div>
                        <?php if (Auth::user()->user_type !=2 ) { ?>
                        <div class="col-md-3">
                            <select class="form-select" wire:model="outlet_filter">
                                <option class="select-box" value="">
                                    {{ $lang->data['all_outlets'] ?? 'All Outlets' }}
                                </option>
                                @foreach($alloutlet as $showoutlet)
                                    <option value="{{ $showoutlet->id }}">{{ $showoutlet->outlet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <?php } ?>

                        <?php if (Auth::user()->user_type ==1 ) { ?>
                        <div class="col-md-3">
                            <select class="form-select" wire:model="workstation_filter">
                                <option class="select-box" value="">
                                    {{ $lang->data['all_workstations'] ?? 'All Workstations' }}
                                </option>
                                @foreach($allworkstation as $showworkstation)
                                    <option value="{{ $showworkstation->id }}">{{ $showworkstation->workstation_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="scrum-board-container">
                    <div class="flex">
                        <div class="scrum-board-processed">
                            <h5 class="text-uppercase text-danger text-center">
                                {{ $lang->data['to_be_processed'] ?? 'To be Processed' }}
                            </h5>
                            <div class="scrum-board-column" id="processed">
                                @foreach ($processed_orders as $item)
                                    <div class="{{ getOrderStatusWithColorKan($item->status) }} overflow"
                                         id="{{ $item->id }}"  data-bs-toggle="modal" wire:click="orderdetails({{ $item->id }})" data-bs-target="#orderdetails">
                                        <div class="d-flex flex-column justify-content-between mb-0">
                                            <div>
                                                <span class="fw-600 text-dark text-sm d-block">{{ $item->order_number }}</span>
                                                <span class="fw-600 text-sm text-dark d-block mb-1">{{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}</span>
                                                <span class="text-xs d-block mb-0">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}: {{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        @php
                                            $services = \App\Models\OrderDetails::where('order_id', $item->id)
                                                ->limit(10)
                                                ->get();
                                        @endphp
                                        <div class="pt-1 mb-0">
                                            @foreach ($services as $row)
                                                @php
                                                    $service = \App\Models\Service::where('id', $row->service_id)->first();
                                                @endphp
                                                <a class="avatar avatar-sm me-3 mt-3 p-1 bg-light" style="position: relative;">
                                                    <span class="point_bedge"> {{$row->service_quantity}} </span>
                                                    <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="scrum-board-transit transit">
                            <h5 class="text-uppercase text-info text-center">
                                {{ $lang->data['in_transit'] ?? 'In Transit' }}
                            </h5>
                            <div class="scrum-board-column" id="transit">
                                @foreach ($transit_orders as $item)
                                    <div class="{{ getOrderStatusWithColorKan($item->status) }} overflow"
                                         id="{{ $item->id }}" data-bs-toggle="modal" wire:click="orderdetails({{ $item->id }})" data-bs-target="#orderdetails">
                                        <div class="d-flex flex-column justify-content-between mb-0">
                                            <div>
                                                <span class="fw-600 text-dark text-sm d-block">{{ $item->order_number }}</span>
                                                <span class="fw-600 text-sm text-dark d-block mb-1">{{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}</span>
                                                <span class="text-xs d-block mb-0">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}: {{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        @php
                                            $services = \App\Models\OrderDetails::where('order_id', $item->id)
                                                ->limit(10)
                                                ->get();
                                        @endphp
                                        <div class="pt-1 mb-0">
                                            @foreach ($services as $row)
                                                @php
                                                    $service = \App\Models\Service::where('id', $row->service_id)->first();
                                                @endphp
                                                <a class="avatar avatar-sm me-3 mt-3 p-1 bg-light" style="position: relative;">
                                                    <span class="point_bedge"> {{$row->service_quantity}} </span>
                                                    <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="scrum-board-processing">
                            <h5 class="text-uppercase text-light text-center">
                                {{ $lang->data['processing'] ?? 'Processing' }}
                            </h5>
                            <div class="scrum-board-column" id="processing">
                                @foreach ($processing_orders as $item)
                                    <div class="{{ getOrderStatusWithColorKan($item->status) }} overflow"
                                         id="{{ $item->id }}" data-bs-toggle="modal" wire:click="orderdetails({{ $item->id }})" data-bs-target="#orderdetails">
                                        <div class="d-flex justify-content-between mb-0">
                                            <div>
                                                <span class="fw-600 text-dark text-sm d-block">{{ $item->order_number }}</span>
                                                <span class="fw-600 text-sm text-dark d-block mb-1">{{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}</span>
                                                <span class="text-xs d-block mb-0">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}: {{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        @php
                                            $services = \App\Models\OrderDetails::where('order_id', $item->id)
                                                ->limit(10)
                                                ->get();
                                        @endphp
                                        <div class="pt-1 mb-0">
                                            @foreach ($services as $row)
                                                @php
                                                    $service = \App\Models\Service::where('id', $row->service_id)->first();
                                                @endphp
                                                <a class="avatar avatar-sm me-3 mt-3 p-1 bg-light" style="position: relative;">
                                                    <span class="point_bedge">
                                                        {{$row->service_quantity}}
                                                    </span>
                                                    <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="scrum-board-store">
                            <h5 class="text-uppercase text-dark text-center text-center">
                                {{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}
                            </h5>
                            <div class="scrum-board-column" id="store">
                                @foreach ($store_orders as $item)
                                    <div class="{{ getOrderStatusWithColorKan($item->status) }} overflow" id="{{ $item->id }}" data-bs-toggle="modal" wire:click="orderdetails({{ $item->id }})" data-bs-target="#orderdetails">
                                        <div class="d-flex flex-column justify-content-between mb-0">
                                            <div>
                                                <span class="fw-600 text-sm d-block text-dark">{{ $item->order_number }}</span>
                                                <span class="fw-600 text-sm text-dark d-block mb-1">{{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}</span>
                                                <span class="text-xs d-block mb-0">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}: {{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        @php
                                            $services = \App\Models\OrderDetails::where('order_id', $item->id)
                                                ->limit(10)
                                                ->get();
                                        @endphp
                                        <div class="pt-1 mb-0">
                                            @foreach ($services as $row)
                                                @php
                                                    $service = \App\Models\Service::where('id', $row->service_id)->first();
                                                @endphp
                                                <a class="avatar avatar-sm me-3 mt-3 p-1 bg-light" style="position: relative;">
                                                    <span class="point_bedge"> {{$row->service_quantity}} </span>
                                                    <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="scrum-board ready">
                            <h5 class="text-uppercase text-warning text-center text-center">
                                {{ $lang->data['ready'] ?? 'Ready' }}</h5>
                            <div class="scrum-board-column" id="ready">
                                @foreach ($ready_orders as $item)
                                    <div class="{{ getOrderStatusWithColorKan($item->status) }} overflow"
                                         id="{{ $item->id }}" data-bs-toggle="modal" wire:click="orderdetails({{ $item->id }})" data-bs-target="#orderdetails">
                                        <div class="d-flex justify-content-between mb-0">
                                            <div>
                                                <span class="fw-600 text-sm text-dark d-block">{{ $item->order_number }}</span>
                                                <span class="fw-600 text-sm text-dark d-block mb-1">{{ $item->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}</span>
                                                <span class="text-xs d-block">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}: {{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        @php
                                            $services = \App\Models\OrderDetails::where('order_id', $item->id)
                                                ->limit(10)
                                                ->get();
                                        @endphp
                                        <div class="pt-1 mb-0">
                                            @foreach ($services as $row)
                                                @php
                                                    $service = \App\Models\Service::where('id', $row->service_id)->first();
                                                @endphp
                                                <a class="avatar avatar-sm me-3 mt-3 p-1 bg-light" style="position: relative;">
                                                    <span class="point_bedge"> {{$row->service_quantity}} </span>
                                                    <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div wire:ignore.self class="modal fade" class="modal fade " id="orderdetails" tabindex="-1" role="dialog" aria-labelledby="orderdetails" aria-hidden="true">
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
                                                            <th class="text-center text-uppercase text-secondary text-xs  opacity-7">
                                                                {{ $lang->data['qty'] ?? 'QTY' }}
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $orderdetails = \App\Models\OrderDetails::where('order_id', $orderid)->get();
                                                        @endphp
                                                        @foreach ($orderdetails as $item)
                                                            @php
                                                                $service = \App\Models\Service::where('id', $item->service_id)->first();
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
                                                                            <h6 class="mb-1 text-sm">{{ $service->service_name }}</h6>
                                                                            <span class="text-xs fw-600 text-primary">[{{ $item->service_name }}]</span>
                                                                            <small style="text-transform:uppercase">{{ $item->brand }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $addon = \App\Models\OrderAddonDetail::where('order_detail_id', $item->id)->get();
                                                                    @endphp

                                                                    @foreach($addon as $viewaddon)
                                                                        <span style="font-weight: 500;" class="badge badge-warning">{{$viewaddon->addon_name}}: {{ getCurrency() }}{{$viewaddon->addon_price}}</span><br>
                                                                    @endforeach
                                                                </td>
                                                                <td class="px-4">
                                                                    @if($item->color_code!="")
                                                                        <button class="btn" style="background-color: {{$item->color_code}}">
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                                <td class="align-middle text-center">
                                                                    <p class="text-sm px-3 mb-0">{{ $item->service_quantity }}</p>
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
                                        <button type="button" class="btn btn-secondary" wire:click.prevent="closemodal()">{{ $lang->data['close'] ?? 'Close' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
    $master_settings=\App\Models\MasterSettings::where('master_title', 'garmnt_scrn')->first();
    $garment_scrn_drag_n_drop=$master_settings->master_value;
    @endphp
    @if($garment_scrn_drag_n_drop==1)
    @push('js')

        <style>
            span.point_bedge {
                width: 20px;
                height: 20px;
                background: #000000;
                color: #fff;
                font-weight: bold;
                border-radius: 50%;
                font-size: 10px;
                position: absolute;
                text-align: center;
                line-height: 20px;
                right: -10px;
                top: -10px;
            }

        </style>
        <script>
            "use strict";
            var drake = dragula([document.querySelector('#ready'), document.querySelector('#store'), document.querySelector('#processing'), document.querySelector('#transit'), document
                .querySelector('#processed')
            ]);
            drake.on("drop", function(el, target, source, sibling) {

            @this.changestatus(el.id, target.id);
            });
        </script>
    @endpush
    @endif
</div>