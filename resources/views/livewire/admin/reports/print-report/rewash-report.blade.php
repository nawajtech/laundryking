<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['rewash_report'] ?? 'Rewash Report'}}</title>
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
                <h5 class="fw-500">{{$lang->data['rewash_report'] ?? 'Rewash Report'}}</h5>
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
                                    <th style="width: 25%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['outlet_name'] ?? 'Outlet Name'}}</th>
                                    <th style="width: 25%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garment_name'] ?? 'Garment Name'}}</th>
                                    <th style="width: 30%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['rewash_qty'] ?? 'Rewash '}}</th>
                                </tr>
                            </thead>
                                <tbody>
                                    @foreach($orderDet as $row)
                                    <tr>
                                        <td style="width: 25%" >
                                            <p class="text-xs px-3 mb-0">
                                                <span class="font-weight-bold">{{$row->outlet_name}}</span>
                                            </p>
                                        </td>
                                        <td style="width: 25%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0">{{$row->service_name}}</p>
                                        </td>
                                        
                                        <td style="width: 30%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0">{{$row->total}}</p>
                                        </td>
                                    </tr>
                                    @endforeach
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