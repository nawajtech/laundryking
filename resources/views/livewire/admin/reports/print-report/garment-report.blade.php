<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['garment_report'] ?? 'Garment Report'}}</title>
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
                <h5 class="fw-500">{{$lang->data['garment_report'] ?? 'Garment Report'}}</h5>
            </div>
        </div>
        <div class="row">
            <div class="row">
                <div class="col-md-3">
                    <label>{{$lang->data['start_date'] ?? 'Start Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}
                </div>
                <div class="col-md-3">
                    <label>{{$lang->data['end_date'] ?? 'End Date'}}: </label>
                    {{ \Carbon\Carbon::parse($to_date)->format('d/m/Y') }}
                </div>
                @php
                $outlets = \App\Models\Outlet::where('id', $outlet)->first();
                $outlet_name = $outlets->outlet_name ?? 'All outlet';
                @endphp
                <div class="col-md-3">
                    <label>{{$lang->data['outlet'] ?? 'outlet'}}: </label>
                    {{ $outlet_name ?? 'All outlet'}}
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-4 shadow-none">
                    <div class="card-header p-4">
                    </div>
                    <div class="card-body p-0 shadow-none">
                        <div class="table-responsive">
                            <table class="table table-bordered align-items-center mb-0">
                                <thead class="table-dark">
                                <tr>
                                    <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['Garment'] ?? 'Garment'}}</th>
                                    <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['category'] ?? 'Category'}}</th>
                                    <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['qty'] ?? 'Quantity'}}</th>
                                    <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['amount'] ?? 'Total Amount'}}</th>
                                    <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['addon_amount'] ?? 'Addon Amount'}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($ordDetDet)
                                @foreach($ordDetDet as $row)
                                    <tr>
                                    @php
                                        $a="/admin/service/edit/".$row->service_id;
                                    @endphp
                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 mb-0">
                                            <a href="{{url($a)}}" target="_blank">
                                                <span class="font-weight-bold">{{$row->service->service_name }} </span>
                                            </p>
                                        </td>
                                        @php
                                        $category = App\Models\ServiceCategory::where('id', $row->service->service_category_id)->first();
                                        $a="/admin/service/category/";
                                        @endphp

                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 mb-0">
                                            <a href="{{url($a)}}" target="_blank">
                                                <span class="font-weight-bold">{{ $category->service_category_name }}</span>
                                            </p>
                                        </td>

                                        @php
                                        $quantity = App\Models\OrderDetails::where('service_id', $row->service_id)->sum('service_quantity');
                                        @endphp
                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0">{{ $quantity }}</p>
                                        </td>
                                        @php
                                        $amount = App\Models\OrderDetails::where('service_id', $row->service_id)->sum(\DB::raw('service_quantity * service_price'));
                                        @endphp
                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0"> {{ $amount }}</p>
                                        </td>
                                        @php
                                        $query = App\Models\OrderAddonDetail::query();

                                        $query->whereHas('order_details', function($q) use ($row){
                                                $q->where('service_id', $row->service_id);
                                        });
                                        $addon_price = $query->sum('addon_price');
                                        @endphp

                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0">{{ $addon_price }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                            </table>
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