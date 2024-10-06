<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['service_report'] ?? 'Service Report'}}</title>
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
                <h5 class="fw-500">{{$lang->data['service_report'] ?? 'Service Report'}}</h5>
            </div>
        </div>
    @php
        $outlets = \App\Models\Outlet::where('id', $outlet)->first();
        $outlet_name = $outlets->outlet_name ?? 'All outlet';
    @endphp
        <div class="row">
            <div class="row">
                <div class="col-md-3">
                    
                    <label>{{$lang->data['outelt'] ?? 'Outlet'}}: </label>
                    {{ $outlet_name ?? 'All outlet' }}
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
                                    <th style="width: 40%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['service_name'] ?? 'Service Name'}}</th>
                                    <th style="width: 30%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['quantity'] ?? 'Quantity'}}</th>
                                    <th style="width: 30%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['amount'] ?? 'Total Amount'}}</th>
                                </tr>
                                </thead>
                                    <tbody>
                                    @foreach($ordDetDet as $rows)
                                    <tr>
                                        @php
                                            $servc_type = \App\Models\ServiceType::where('id', $rows->service_type_id)->where('is_active',1)->first();
                                            $id = $rows -> id;
                                            $a="/admin/service/type";
                                        @endphp
                                        <td style="width: 40%">
                                            <p class="text-xs px-4 mb-0">
                                                @if($servc_type)
                                                <a href="{{url($a)}}" target="_blank">
                                                <span class="font-weight-bold">{{$servc_type->service_type_name ??''}}</span>
                                                @endif
                                            </p>
                                        </td>
                                    
                                        @php
                                        $query = \App\Models\OrderDetails::query();
                                            $query->whereHas('order', function($q) {
                                                if($this->outlet){
                                                    $q->where('outlet_id', $this->outlet);
                                                    }
                                                });

                                            $qty = $query->where('service_type_id', $rows->service_type_id)->sum('service_quantity');
                                        @endphp
                                        @if($servc_type)
                                        <td style="width: 30%">
                                            <p class="text-xs px-3 mb-0">{{$qty}}</p>
                                        </td>
                                        @endif
                                        @php
                                        $query = \App\Models\OrderDetails::query();
                                            $query->whereHas('order', function($q) {
                                                if($this->outlet){
                                                    $q->where('outlet_id', $this->outlet);
                                                    }
                                                });

                                            $price = $query->where('service_type_id', $rows->service_type_id)->sum('service_price');
                                        @endphp
                                        @if($servc_type)
                                        <td style="width: 30%">
                                            <p class="text-xs px-3 mb-0">{{getCurrency()}} {{$price}}</p>
                                        </td>
                                        @endif
                                    </tr>
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