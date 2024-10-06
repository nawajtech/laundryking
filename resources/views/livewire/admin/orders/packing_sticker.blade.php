<div>
    <div class="row align-items-center justify-content-between mb-6">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['packing_sticker'] ?? 'Generate Packing Sticker' }}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card ">
                <div class="card-header" style="border-bottom:1px solid #ccc;">
                    <div class="row">
                        <div class="col-md-<?php if (Auth::user()->user_type == 2) { ?>11<?php } elseif(Auth::user()->user_type == 3) { ?>8<?php } else { ?>5<?php } ?>">
                            <input type="search" class="form-control" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search">
                        </div>
                        @if($germent_code)
                            <div class="col-md-1">
                                <a href="{{ url('admin/orders/packing-sticker/'.$germent_code) }}" target="_blank" type="button" class="btn btn-primary" >Generate</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_id'] ?? 'Order Info'}}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['garment_tag_id'] ?? 'Garment Code'}}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['garment'] ?? 'Garment Name'}}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['customer'] ?? 'Customer Name'}}</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['status'] ?? 'Status'}}</th>
                                </tr>
                            </thead>
                            <tbody wire:poll="refresh">
                                @foreach ($ordDetDet as $details)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">
                                                Order Id :
                                                <a href="{{url('/admin/orders/view/'.$details->order_details->order->id)}}" target="_blank">
                                                    <span class="font-weight-bold">{{ $details->order_details->order->order_number }}</span>
                                                </a>
                                            </p>
                                            <p class="text-sm px-3 mb-0">
                                                <span class="me-2">{{ $lang->data['order_date'] ?? 'Order Date' }}:</span>
                                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($details->order_details->order->order_date)->format('d/m/y') }}</span>
                                            </p>
                                            <p class="text-sm px-3 mb-0">
                                                <span class="me-2">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:</span>
                                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($details->order_details->order->delivery_date)->format('d/m/y') }}</span>
                                            </p>
                                            @php
                                                $outlet = App\Models\Outlet::where('id',$details->order_details->order->outlet_id)->first();
                                                if($outlet){
                                                $outlet_name = $outlet->outlet_name;
                                                }
                                            @endphp
                                            <p class="text-sm px-3 mb-0"><span class="badge rounded-pill bg-warning">{{ $outlet_name ?? "" }}</span></p>
                                        </td>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $details->garment_tag_id }}</p>
                                        </td>
                                        @php
                                            $service = App\Models\Service::where('id',$details->order_details->service_id)->first();
                                        @endphp
                                        <td>
                                            <p class="text-sm px-3 mb-0"> {{ $service->service_name }} </p>
                                        </td>
                                        <td>
                                            <p class="text-sm px-3 mb-0"> {{ $details->order_details->order->customer_name }} </p>
                                        </td>
                                        <td class="align-middle text-center">
                                            @if ($details->status == 0)
                                                <a type="button" class="badge badge-sm bg-secondary text-uppercase">{{ $lang->data['pending'] ?? 'Pending' }}</a>
                                            @elseif($details->status == 1)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#83ce2d;">{{ $lang->data['confirm'] ?? 'Confirm' }}</a>
                                            @elseif($details->status == 2)
                                                <a type="button" class="badge badge-sm bg-primary text-uppercase">{{ $lang->data['picked_up'] ?? 'Picked Up' }}</a>
                                            @elseif($details->status == 3)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#FF597B;">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</a>
                                            @elseif($details->status == 4)
                                                <a type="button" class="badge badge-sm bg-info text-uppercase">{{ $lang->data['in_transit'] ?? 'In Transit' }}</a>
                                            @elseif($details->status == 5)
                                                <a type="button" class="badge badge-sm bg-light text-uppercase" style="color:#000 !important;">{{ $lang->data['processing'] ?? 'Processing' }}</a>
                                            @elseif($details->status == 6)
                                                <a type="button" class="badge badge-sm bg-dark text-uppercase">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</a>
                                            @elseif($details->status == 7)
                                                <a type="button" class="badge badge-sm bg-warning text-uppercase">{{ $lang->data['ready'] ?? 'Ready' }}</a>
                                            @elseif($details->status == 8)
                                                <a type="button" class="badge badge-sm bg-success text-uppercase" style="background:#8b38b2 !important;">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }}</a>
                                            @elseif($details->status == 9)
                                                <a type="button" class="badge badge-sm bg-success text-uppercase">{{ $lang->data['delivered'] ?? 'Delivered' }}</a>
                                            @elseif($details->status == 10)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#FF1E1E;">{{ $lang->data['cancel'] ?? 'Cancel' }}</a>
                                            @elseif($details->status == 11)
                                                <a type="button" class="badge badge-sm text-uppercase" style="background:#3003fc;">{{ $lang->data['out_for_pickup'] ?? 'Out for Pickup' }}</a>
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
                                                        @this.call('loadStickers')
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
        @media print {
            @page {
                size: Packing-sticker;
            }
        }
    </style>
</div>