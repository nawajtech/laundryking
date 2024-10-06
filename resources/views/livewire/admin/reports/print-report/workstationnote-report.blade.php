<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['workstation_report'] ?? 'Workstation Report'}}</title>
        <link href="https://fonts.googleapis.com/css?family=Calibri:400,700,400italic,700italic">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap"
            rel="stylesheet">
        <script src="{{ asset('assets/vendors/font-awesome/css/font-awesome.css') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" crossorigin="anonymous"></script>
        <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
        <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.min28b5.css?v=2.0.0') }}" rel="stylesheet" />
        <link id="pagestyle" href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    </head>
    <body onload="">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col">
                <h5 class="fw-500">{{$lang->data['workstationnote_report'] ?? 'Workstation Note Summary Report'}}</h5>
            </div>
        </div>
        @php
            $workstations = \App\Models\Workstation::where('id', $workstation)->first();
            $workstation_name = $workstations->workstation_name ?? 'All workstation';
        @endphp
        <div class="row">
            <div class="row">
                <div class="col-md-3">
                    <label>{{$lang->data['workstation'] ?? 'Workstation'}}: </label>
                    {{ $workstation_name ?? 'All workstation' }}
                    <div class="col-md-3">
                    <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}
                    </div>
                    <div class="col-md-3">
                        <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                        {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4 shadow-none">
                    <div class="card-header p-4">
                    </div>
                    <div class="card-body p-0 shadow-none">
                        <div class="table-responsive">
                        <table class="table table-bordered align-items-center mb-0 ">
                        <thead class="table-dark">
                        <tr>
                            <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_no'] ?? 'Order No'}}</th>
                            <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_date'] ?? 'Order Date'}}</th>
                            <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['delivery_date'] ?? 'Delivery Date'}}</th>
                            <th style="width: 14%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['delivery_type'] ?? 'Delivery Type'}}</th>
                            <th style="width: 9%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['pcs'] ?? 'Pcs'}}</th>
                            <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garment_details'] ?? 'Garment Details'}}</th>
                            <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['services'] ?? 'Services'}}</th>
                            <th style="width: 7%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['defects'] ?? 'Defects'}}</th>
                            <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['colors'] ?? 'Colors'}}</th>
                            <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['brand'] ?? 'Brand'}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($ordDetDet as $rows)
                            <tr>
                                <td style="width: 10%">
                                    <p class="text-xs px-3 mb-0">
                                        <span class="text-xs px-3 mb-0">{{$rows->order_number ??''}}</span>
                                    </p>
                                </td>
                                <td style="width: 10%">
                                    <p class="text-xs px-3 mb-0">{{ \Carbon\Carbon::parse($rows->order_date)->format('d/m/Y') ?? '' }}</p>
                                </td>
                                <td style="width: 10%">
                                    <p class="text-xs px-3 mb-0">{{ \Carbon\Carbon::parse($rows->delivery_date)->format('d/m/Y') ?? '' }}</p>
                                </td>
                                <td style="width: 10%">
                                    <p class="text-xs px-3 mb-0">{{ $rows->delivery_type ??'' }}</p>
                                </td>
                                @php
                                $quantity = App\Models\OrderDetails::where('order_id', $rows->id)->count();
                                @endphp
                                <td style="width: 10%">
                                    <p class="text-xs px-3 mb-0">{{ $quantity ?? '' }}</p>
                                </td>
                                @php 
                                $ord_details = App\Models\OrderDetails::where('order_id', $rows->id)->get();
                                @endphp
                                <td style="width: 10%">
                                    @foreach($ord_details as $ord_det)
                                    @php 
                                        $service = App\Models\Service::where('id', $ord_det->service_id)->first();
                                    @endphp
                                    <p class="text-xs px-3 mb-0">{{ $service->service_name }}</p>
                                    @endforeach
                                </td>
                                <td style="width: 10%">
                                    @foreach($ord_details as $ord_det)
                                    <p class="text-xs px-3 mb-0">{{ $ord_det->service_name }}</p>
                                    @endforeach
                                </td>
                                <td style="width: 10%">
                                    @foreach($ord_details as $ord_det)
                                    @php
                                    $defect_remark = App\Models\OrderDetailsDetail::where('order_detail_id', $ord_det->id)->first();
                                    @endphp
                                    <p class="text-xs px-3 mb-0">{{ $defect_remark->remarks }}</p>
                                    @endforeach
                                </td>
                                <td style="width: 10%">
                                    @foreach($ord_details as $ord_det)
                                    @if($ord_det->color_code !='')
                                    <button class="clr-btn" style="margin:0px auto; border:1px solid #000; width:66%; height:10px; display: block; margin-bottom: 5px; background-color: {{$ord_det->color_code ?? 'No color'}}!important"></button>
                                    @else
                                    <p class="text-xs px-3 mb-0">{{ 'No Color' }}</p>
                                    @endif
                                    @endforeach
                                </td>
                                <td style="width: 10%">
                                    @foreach($ord_details as $ord_det)
                                    @php
                                        $brand = App\Models\Brand::where('id', $ord_det->brand_id)->first();
                                    @endphp
                                    <p class="text-xs px-3 mb-0">{{ $brand->brand_name ?? 'No Brand' }}</p>
                                    @endforeach
                                </td>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</body>
</html>
</div>
<script type="text/javascript">
 "use strict";
    window.onload = function() {
        window.open('', '', 'left=0,top=0,width=800,height=600,toolbar=0,scrollbars=0,status=0');
        window.print();
        setTimeout(function() {
            window.close();
        }, 1);
    }
</script>