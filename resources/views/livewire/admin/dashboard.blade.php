<div>
     
<div class="row" wire:poll>
    <div class="col-lg-12">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{$lang->data['pending_order'] ?? 'Pending Orders'}}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$pending_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-secondary text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=0') }}"><i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{$lang->data['confirm_order'] ?? 'Confirm Orders'}}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$confirm_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape text-center rounded-circle" style="background:#83ce2d;">
                                    <a href="{{ url('/admin/orders?order_filter=1') }}"><i class="ni ni-basket text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase" >{{$lang->data['out_for_pickup'] ?? 'OUT FOR PICKUP'}}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$outforpickup_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape text-center rounded-circle" style="background:#3003fc;">
                                    <a href="{{ url('/admin/orders?&order_filter=11') }}"><i  class="ni ni-bus-front-12"  aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{ $lang->data['picked_up'] ?? 'Picked Up' }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$pickup_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-primary text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=2') }}"><i class="ni ni-box-2 text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$tobeprocessed_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape text-center rounded-circle" style="background:#FF597B;">
                                    <a href="{{ url('/admin/orders?&order_filter=3') }}"><i class="ni ni-bulb-61 text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{ $lang->data['in_transit'] ?? 'In Transit' }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$intransit_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-info text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=4') }}"><i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{ $lang->data['processing'] ?? 'Processing' }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$processing_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-light text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=5') }}"><i class="ni ni-atom text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$senttostore_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-dark text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=6') }}"><i class="ni ni-shop text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{ $lang->data['ready_count'] ?? 'Ready' }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$ready_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-warning text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=7') }}"><i class="ni ni-user-run text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$outfordelivery_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-success text-center rounded-circle" style="background:#8b38b2 !important;">
                                    <a href="{{ url('/admin/orders?order_filter=8') }}"><i class="ni ni-spaceship text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{$lang->data['delivered_orders'] ?? 'Delivered Orders'}}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$delivered_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-success text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=9') }}"><i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row align-items-center px-2">
                            <div class="col-8">
                                <div class="numbers py-2">
                                    <p class="text-sm mb-3 text-uppercase">{{$lang->data['cancel_orders'] ?? 'Cancel Orders'}}</p>
                                    <h5 class="font-weight-bolder">
                                        {{$cancel_count}}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-danger text-center rounded-circle">
                                    <a href="{{ url('/admin/orders?order_filter=10') }}"><i class="ni ni-fat-remove text-lg opacity-10" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header pb-4 pt-3">
                <div class="row g-2 align-items-center">
                    <div class="col-4">
                        <h5 class="pb-0 fw-500">{{$lang->data['todays_delivery'] ?? "Today's Delivery"}}</h5>
                    </div>
                    <div class="col-5">
                        <input type="text" class="form-control" placeholder="{{$lang->data['search_here'] ?? 'Search Here...'}}" wire:model="search_query">
                    </div>
                    <div class="col-3">
                        <select class="form-select" wire:model="order_filter">
                                <option class="select-box" value="">{{$lang->data['all_orders'] ?? 'All Orders'}}</option>
                                <option class="select-box" value="0">{{ $lang->data['pending'] ?? 'Pending' }} </option>
                                <option class="select-box" value="1">{{ $lang->data['confirm'] ?? 'Confirm' }} </option>
                                <option class="select-box" value="11">{{ $lang->data['out_for_pickup'] ?? 'Out for Pickup' }} </option>
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
            <div class="card-body pt-0">
                <div class="row g-2">
                    @foreach ($orders as $item)
                    <div class="col-lg-4 col-12">
                        <div class="{{getOrderStatusWithColor($item->status)}}">
                            <div class="d-flex justify-content-between mb-2">
                                <a href="{{route('admin.view_single_order',$item->id)}}" type="button" class="text-dark">
                                    <span class="fw-600 ms-2 text-dark text-xs mb-0">{{$item->customer_name?? $lang->data['walk_in_customer'] ?? 'Walk In Customer'}}</span>
                                </a>
                                <a href="{{route('admin.view_single_order',$item->id)}}" type="button">
                                    <span class="fw-600 text-dark text-xs me-2">{{$item->order_number}}</span>
                                </a>
                            </div>
                            @php
                                $services = \App\Models\OrderDetails::where('order_id',$item->id)->limit(4)->get();
                            @endphp
                            <div class="pt-1 mb-0">
                                @foreach ($services as $row)
                                    @php
                                        $service = \App\Models\Service::where('id',$row->service_id)->first();
                                    @endphp
                                <a class="avatar avatar-sm ms-2 p-1 bg-white">
                                    <img src="{{asset('assets/img/service-icons/'.$service->icon)}}">
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4" >
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="pb-4 fw-500">{{$lang->data['overview'] ?? 'Overview'}}</h5>
            </div>
            <div class="card-body pt-0 pb-2">
                <div class="row">
                    <div class="col-12 text-start mb-4" wire:ignore>
                        <div class="chart">
                            <canvas id="doughnut-chart" class="chart-canvas" height="300px"></canvas>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-secondary"></i>
                                <span class="text-dark text-xs">{{$lang->data['pending'] ?? 'Pending'}}</span>
                            </span>

                            <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-primary" style="background:#83ce2d !important;"></i>
                                <span class="text-dark text-xs">{{ $lang->data['confirm'] ?? 'Confirm' }}</span>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                        <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-primary"></i>
                                <span class="text-dark text-xs">{{ $lang->data['out_for_pickup'] ?? 'Out for Pickup' }}</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="" style="background:#3003fc;"></i>
                                <span class="text-dark text-xs">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</span>
                            </span>
                            
                            
                        </div>
                        <div class="d-flex justify-content-between">
                        <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-primary"></i>
                                <span class="text-dark text-xs">{{ $lang->data['picked_up'] ?? 'Picked Up' }}</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="" style="background:#FF597B;"></i>
                                <span class="text-dark text-xs">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</span>
                            </span>
                            
                            
                        </div>
                        <div class="d-flex justify-content-between">
                        <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-info"></i>
                                <span class="text-dark text-xs">{{ $lang->data['in_transit'] ?? 'In Transit' }}</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-light"></i>
                                <span class="text-dark text-xs">{{ $lang->data['processing'] ?? 'Processing' }}</span>
                            </span>
                            
                        </div>
                        <div class="d-flex justify-content-between">
                        <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-dark"></i>
                                <span class="text-dark text-xs">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-warning"></i>
                                <span class="text-dark text-xs">{{ $lang->data['ready'] ?? 'Ready' }}</span>
                            </span>
                            
                        </div>
                        <div class="d-flex justify-content-between">
                        <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-danger" style="background:#8b38b2 !important;"></i>
                                <span class="text-dark text-xs">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }}</span>
                            </span>
                            <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="bg-success"></i>
                                <span class="text-dark text-xs">{{ $lang->data['delivered'] ?? 'Delivered' }}</span>
                            </span>
                            
                        </div>
                        <div class="d-flex justify-content-between">
                        <span class="badge badge-md badge-dot me-4 text-start">
                                <i class="" style="background:#FF1E1E;"></i>
                                <span class="text-dark text-xs">{{ $lang->data['cancel'] ?? 'Cancel' }}</span>
                            </span>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="" id="chartdata" value="{{$array}}">
@push('js')
<script>
    "use strict";
    var ctx3 = document.getElementById("doughnut-chart").getContext("2d");
    var chartdata = document.getElementById("chartdata").value;
    new Chart(ctx3, {
        type: "doughnut",
        data: {
            datasets: [{
                label: "Projects",
                weight: 9,
                cutout: 60,
                tension: 0.9,
                pointRadius: 2,
                borderWidth: 2,
                backgroundColor: ['#8392ab', '#83ce2d', '#3003fc', '#9b313d', '#f5365c', '#11cdef', '#e9ecef', '#535560', '#faae42', '#8b38b2', '#2dce89', '#ff1e1e'],
                data: JSON.parse(chartdata),
                fill: false
            }],
            labels: ['Pending', 'Confirm', 'Out for Pickup','Pick Up', 'To be Processed', 'In Transit', 'Processing', 'Sent to Store', 'Ready', 'Out for Delivery', 'Delivered', 'Cancel'],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: true,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: false,
                    }
                },
            },
        },
    });
</script>
@endpush
</div>