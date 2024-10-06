<div>
    @php
        $printer_type = getPrinterType();
    @endphp
    @if ($printer_type == 1)
        <!DOCTYPE html
            PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>{{$lang->data['print_invoice'] ?? 'Print Invoice'}}</title>
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
            <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.min28b5.css?v=2.0.0') }}"
                rel="stylesheet" />
            <link id="pagestyle" href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
        </head>
        <body onload="">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card mb-4">
                        <div class="card-header p-4">
                            <div class="row align-items-center">
                                <div class="col-8">
                                     <div>
                                        <img src="{{ $logo }}"
                                             width="100" height="90">
                                    </div>
                                    @if($outlet)
                                        <h5 class="text-uppercase fw-500">{{ $outlet->outlet_name ?? 'No Outlet Select' }}</h5>
                                        <p style="width:150px" class="text-sm mb-0">{{ $outlet->outlet_address }}</p>
                                        <p class="text-sm mb-0">{{ $outlet->outlet_phone }}</p>
                                    @endif
                                </div>
                                
                                <div class="col-4 mt-4">
                                    <h6 class="text-uppercase fw-500">
                                        <span> {{ $lang->data['order_id'] ?? 'Order ID' }}:</span>
                                        <span class="ms-2 fw-600">#{{ $order->order_number }}</span>
                                    </h6>
                                    <p class="text-sm mb-1">
                                        <span> {{ $lang->data['order_date'] ?? 'Order Date' }}:</span>
                                        <span
                                            class="fw-600 ms-2">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</span>
                                    </p>
                                    <p class="text-sm mb-1">
                                        <span> {{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:
                                            <span
                                                class="fw-600 ms-2">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }} {{ $order->delivery_time }}</span></span>
                                    </p>
                                    <p class="text-sm mb-1">
                                        <span> {{ $lang->data['delivered_date'] ?? 'Delivered Date' }}:</span>
                                        @if($order->delivered_date != '')
                                        <span class="fw-600 ms-2">{{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y h:i: A') }}</span>
                                         @endif
                                    </p>
                                     <p class="text-sm mb-1">
                                        <span> {{ $lang->data['delivery_type'] ?? 'Delivery Type' }}:
                                            <span
                                                class="fw-600 ms-2">{{ $order->delivery_type }}</span></span>
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <div><span class="text-sm">
                                                {{ $lang->data['order_status'] ?? 'Order Status' }}:</span></div>
                                        <div class="dropdown ms-2">
                                            <button class="btn btn-xs bg-secondary  mb-0 text-white" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ getOrderStatus($order->status) }}
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a class="dropdown-item" href="#"
                                                        wire:click.prevent="changeStatus(1)">{{ $lang->data['processing'] ?? 'Processing' }}</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#"
                                                        wire:click.prevent="changeStatus(2)">{{ $lang->data['ready_to_deliver'] ?? 'Ready To Deliver' }}</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#"
                                                        wire:click.prevent="changeStatus(3)">{{ $lang->data['delivered'] ?? 'Delivered' }}</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#"
                                                        wire:click.prevent="changeStatus(4)">{{ $lang->data['returned'] ?? 'Returned' }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <h4 class="text-center mt-0" style="margin-top:-30px !important;">Tax Invoice</h4>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th class="text-uppercase text-white text-xs " style="width:50px;">#</th>
                                            <th class="text-uppercase text-white text-xs ">
                                                {{ $lang->data['service_name'] ?? 'Service Name' }}</th>
                                            <th class="text-uppercase text-white text-xs ">
                                                {{ $lang->data['addon'] ?? 'Addons' }}</th>
                                            <th class="text-uppercase text-white text-xs ">
                                                {{ $lang->data['rate'] ?? 'Rate' }}</th>
                                            <th class="text-center text-uppercase text-white text-xs " style="width:50px;">
                                                {{ $lang->data['qty'] ?? 'QTY' }}</th>
                                            <th class="text-uppercase text-white text-xs ">
                                                {{ $lang->data['total'] ?? 'Total' }}</th>
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
                                                            <img src="{{ asset('assets/img/service-icons/' . $service->icon) }}"
                                                                class="avatar avatar-sm me-3">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-1 text-sm">{{ $service->service_name }} <small><?php if($service->size !='') { ?> ({{$service->size}} in feet) <?php } ?></small>
                                                            </h6>
                                                            <span
                                                                class="text-xs fw-600 text-primary">[{{ $item->order_details->service_name }}]</span>
                                                        </div>
                                                        @php
                                                    $addon = \App\Models\OrderAddonDetail::where('order_detail_id', $item->order_details->id)->get();
                                                    @endphp
                                                    
                                                    </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div style="display: grid; grid-template-columns: auto; width: fit-content;">
                                                    @foreach($addon as $viewaddon)
                                                     
                                                        <span style="font-weight: 500; margin-bottom: 5px; max-width: 100px;white-space: normal" class="badge badge-warning">{{$viewaddon->addon_name}}: {{ getCurrency() }}{{$viewaddon->addon_price}}</span>
                                                    
                                                    @endforeach
                                                </td>

                                                @php
                                                    $order_date = \App\Models\Order::where('id', $item->order_id)->first();
                                                    @endphp
                                                   
                                               
                                                <td class="">
                                                     <p class="text-sm px-3 mb-0">{{ getCurrency() }}
                                                        {{ number_format($item->order_details->service_price, 2) }}
                                                    </p>
                                                </td>
                                               
                                                <td class="align-middle text-center">
                                                    <p class="text-sm px-3 mb-0">{{ 1 }}</p>
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
                                <div class="col-4 mb-3">
                                    <h6 class="mb-2 fw-500">{{$lang->data['addons']??'Addons'}}:</h6>
                                    @foreach ($orderaddons as $item)
                                        <p class="text-sm mb-1">{{ $item->addon_name ?? '' }} : <b>
                                                {{ getCurrency() }}{{ $item->addon_price }} </b></p>
                                    @endforeach
                                </div>
                                <div class="col-4 mb-3">
                                    <h6 class="mb-2 fw-500">{{ $lang->data['invoice_to'] ?? 'Invoice To' }}:</h6>
                                    <h6 class="mb-1 fw-500 text-sm">{{ $customer->name ?? 'Walk-In Customer' }}</h6>
                                    <p class="text-sm mb-0">{{ $customer->phone ?? '' }}</p>
                                    <p class="text-sm mb-0">{{ $customer->email ?? '' }}</p>
                                    @if($customer->address !='NULL')
                                    <p class="text-sm mb-3">{{ $customer->address ?? '' }}</p>
                                    @endif
                                </div>
                                <div class="col-4 mb-3">
                                    <h6 class="fw-500 mb-2">
                                        {{ $lang->data['payment_details'] ?? 'Payment Details' }}:</h6>
                                    <div class="">
                                        <div class="row mb-50 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['sub_total'] ?? 'Sub Total' }}:
                                            </div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                {{ number_format($order->sub_total, 2) }}</div>
                                        </div>
                                        @if($order->addon_total)
                                        <div class="row mb-50 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['addon'] ?? 'Addon' }}:</div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                {{ number_format($order->addon_total, 2) }}</div>
                                        </div>
                                        @endif
                                        @if($order->express_charge)
                                        <div class="row mb-50 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['express_charge'] ?? 'Express Charge' }}:</div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                {{ number_format($order->express_charge, 2) }}
                                            </div>
                                        </div>
                                        @endif
                                        @if($order->discount)
                                        <div class="row mb-50 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['discount'] ?? 'Discount' }} ({{ $order->discount }}%):</div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                <?php $discount= ($order->sub_total+$order->addon_total+$order->express_charge) * $order->discount/100  ?>
                                                {{ number_format($discount, 2) }}</div>
                                        </div>
                                        @endif
                                        @if($order->voucher_discount)
                                        <div class="row mb-50 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['discount'] ?? 'Voucher Discount' }}:</div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                {{ number_format($order->voucher_discount, 2) }}</div>
                                        </div>
                                        @endif
                                        @if($order->delivery_charge)
                                        <div class="row mb-50 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['delivery_charge'] ?? 'Delivery Charge' }}:
                                            </div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                {{ number_format($order->delivery_charge, 2) }}</div>
                                        </div>
                                        @endif
                                        
                                
                                        <div class="row mb-50 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['cgst'] ?? 'CGST' }}
                                                ({{ $order->cgst_percentage }}%):</div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                {{ number_format($order->cgst_amount, 2) }}</div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col text-sm">{{ $lang->data['sgst'] ?? 'SGST' }}
                                                ({{ $order->sgst_percentage }}%):</div>
                                            <div class="col-auto text-sm">{{ getCurrency() }}
                                                {{ number_format($order->sgst_amount, 2) }}</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col text-sm fw-600">
                                                {{ $lang->data['gross_total'] ?? 'Gross Total' }}:</div>
                                            <div class="col-auto text-sm text-dark fw-600">{{ getCurrency() }}
                                                {{ number_format($order->total, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="bg-secondary">
                                <div class="col-md-1">
                                    <h6 class="mb-2 text-sm fw-500">{{ $lang->data['notes'] ?? 'Notes' }}:</h6>
                                </div>
                                <div class="col-md-11">
                                    <p class="text-sm mb-0">{{ $order->note }}</p>
                                </div>
                                <div class="mt-4 position-relative text-center">
                                    <p class="text-sm mb-0">Terms and Conditions printed overleaf</p>
                                    <p
                                        class="text-sm fw-500 mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                                        {{$lang->data['powered_by']??'Powered by'}} <a href="{{url('/')}}" class="text-dark fw-600" target="_blank">{{ getApplicationName() }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>

        </html>
    @endif
    @if ($printer_type == 2)
        <!DOCTYPE html
            PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>{{$lang->data['print_invoice'] ?? 'Print Invoice'}}</title>
            <link href="https://fonts.googleapis.com/css?family=Calibri:400,700,400italic,700italic">
            <style>
                @page {
                    size: auto;

                    margin: 0mm 0 0mm 0;
                }

                body {

                    margin: 0px;
                    font-family: Calibri;
                }

                @media screen {

                    .header,
                    .footer {
                        display: none;
                    }
                }

            </style>
            <style>
                .mb-0 {
                    margin-bottom: 0;
                }

                .my-50 {
                    margin-top: 50px;
                    margin-bottom: 50px;
                }

                .my-0 {
                    margin-top: 0;
                    margin-bottom: 0;
                }

                .my-5 {
                    margin-top: 5px;
                    margin-bottom: 5px;
                }

                .mt-10 {
                    margin-top: 10px;
                }

                .mb-15 {
                    margin-bottom: 15px;
                }

                .mr-18 {
                    margin-right: 18px;
                }

                .mr-25 {
                    margin-right: 25px;
                }

                .mb-25 {
                    margin-bottom: 25px;
                }

                .h4,
                .h5,
                .h6,
                h4,
                h5,
                h6 {
                    margin-top: 10px;
                    margin-bottom: 10px;
                }

                .login-wrapper {
                    background-size: 100% 100%;
                    height: 100vh;
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }

                .login-wrapper:before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    display: block;
                    background: rgba(0, 0, 0, 0.5);
                }

                .login_box {
                    text-align: center;
                    position: relative;
                    width: 400px;
                    background: #343434;
                    padding: 40px 30px;
                    border-radius: 10px;
                }

                .login_box .form-control {
                    height: 60px;
                    margin-bottom: 25px;
                    padding: 12px 25px;
                }

                .btn-login {
                    color: #fff;
                    background-color: #45C203;
                    border-color: #45C203;
                    width: 100%;
                    line-height: 45px;
                    font-size: 17px;
                }

                .btn-login:hover,
                .btn-login:focus {
                    color: #fff;
                    background-color: transparent;
                    border-color: #fff;
                }

                .invoice-card {
                    display: flex;
                    flex-direction: column;
                    width: 80mm;
                    background-color: #fff;
                    border-radius: 5px;

                    margin: 35px auto;
                }

                .invoice-head,
                .invoice-card .invoice-title {
                    display: -webkit-flex;
                    display: -moz-flex;
                    display: -ms-flex;
                    display: -o-flex;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .invoice-title {
                    background-color: #000000 !important;
                    color: #ffffff !important;
                    padding: 10px;
                    -webkit-print-color-adjust: exact;
                }

                .invoice-head {
                    flex-direction: column;
                    margin-bottom: 4px;
                }

                .invoice-card .invoice-title {
                    margin: 15px 0;
                }

                .invoice-details {
                    border-top: 0.5px dashed #747272;
                    border-bottom: 0.5px dashed #747272;
                }

                .invoice-list {
                    width: 100%;
                    border-collapse: collapse;
                    border-bottom: 1px dashed #858080;
                }

                .invoice-list .row-data {
                    border-bottom: 1px dashed #858080;
                }

                .invoice-list .row-data:last-child {
                    border-bottom: 0;
                    margin-bottom: 0;
                }

                .invoice-list .heading {
                    font-size: 16px;
                    font-weight: 600;
                    margin: 0;
                }

                .invoice-list .heading1 {
                    font-size: 14px;
                    font-weight: 500;
                    margin: 0;
                }

                .invoice-list thead tr td {
                    font-size: 15px;
                    font-weight: 600;
                    padding: 5px 0;
                }

                .invoice-list tbody tr td {
                    line-height: 25px;
                }

                .row-data {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    width: 100%;
                }

                .middle-data {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .item-info {
                    max-width: 200px;
                }

                .item-title {
                    font-size: 14px;
                    margin: 0;
                    line-height: 19px;
                    font-weight: 500;
                }

                .item-size {
                    line-height: 19px;
                }

                .item-size,
                .item-number {
                    margin: 5px 0;
                }

                .invoice-footer {
                    margin-top: 20px;
                }

                .gap_right {
                    border-right: 1px solid #ddd;
                    padding-right: 15px;
                    margin-right: 15px;
                }

                .b_top {
                    border-top: 1px solid #ddd;
                    padding-top: 12px;
                }

                .food_item {
                    display: -webkit-flex;
                    display: -moz-flex;
                    display: -ms-flex;
                    display: -o-flex;
                    display: flex;
                    align-items: center;
                    border: 1px solid #ddd;
                    border-top: 5px solid #1DB20B;
                    padding: 15px;
                    margin-bottom: 25px;
                    transition-duration: 0.4s;
                }

                .bhojon_title {
                    margin-top: 6px;
                    margin-bottom: 6px;
                    font-size: 14px;
                }

                .food_item .img_wrapper {
                    padding: 15px 5px;
                    background-color: #ececec;
                    border-radius: 6px;
                    position: relative;
                    transition-duration: 0.4s;
                }

                .food_item .table_info {
                    font-size: 11px;
                    background: #1db20b;
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    padding: 4px 8px;
                    color: #fff;
                    border-radius: 15px;
                    text-align: center;
                }

                .food_item:focus,
                .food_item:hover {
                    background-color: #383838;
                }

                .food_item:focus .bhojon_title,
                .food_item:hover .bhojon_title {
                    color: #fff;
                }

                .food_item:hover .img_wrapper,
                .food_item:focus .img_wrapper {
                    background-color: #383838;
                }

                .btn-4 {
                    border-radius: 0;
                    padding: 15px 22px;
                    font-size: 16px;
                    font-weight: 500;
                    color: #fff;
                    min-width: 130px;
                }

                .btn-4.btn-green {
                    background-color: #1DB20B;
                }

                .btn-4.btn-green:focus,
                .btn-4.btn-green:hover {
                    background-color: #3aa02d;
                    color: #fff;
                }

                .btn-4.btn-blue {
                    background-color: #115fc9;
                }

                .btn-4.btn-blue:focus,
                .btn-4.btn-blue:hover {
                    background-color: #305992;
                    color: #fff;
                }

                .btn-4.btn-sky {
                    background-color: #1ba392;
                }

                .btn-4.btn-sky:focus,
                .btn-4.btn-sky:hover {
                    background-color: #0dceb6;
                    color: #fff;
                }

                .btn-4.btn-paste {
                    background-color: #0b6240;
                }

                .btn-4.btn-paste:hover,
                .btn-4.btn-paste:focus {
                    background-color: #209c6c;
                    color: #fff;
                }

                .btn-4.btn-red {
                    background-color: #eb0202;
                }

                .btn-4.btn-red:focus,
                .btn-4.btn-red:hover {
                    background-color: #ff3b3b;
                    color: #fff;
                }

                .text-center {
                    text-align: center;
                }

                .border-top {
                    border-top: 2px dashed #858080;
                    background: #ececec;
                }

                .text-bold {
                    font-weight: bold !important;
                }

            </style>
        </head>
        <body>
            <div class="page-wrapper" style="padding:36px">
                <div class="invoice-card">
                    <div class="invoice-head">
                        <img src="logo.png" alt="">
                        <h4>{{ $sitename }}</h4>
                        <p class="my-0">{{ $address }} - {{ $zipcode }}</p>
                        <p class="my-0">{{ $phone }}</p><br>
                        <b>{{ $store_email }}</b>
                    </div>
                    <div class="invoice-details" style="border-top:none;">
                        <div class="invoice-list">
                            <div class="invoice-title">
                                <h4 class="heading">{{$lang->data['order_invoice']??'Order Invoice'}}</h4>
                                <h4 class="heading heading-child"></h4>
                            </div>
                            <div class="row-data" style="border:none; margin-bottom: 1px">
                                <div class="item-info">
                                    <h5 class="item-title"><b>{{$lang->data['order_no']??'Order No'}}</b></h5>
                                </div>
                                <h5 class="my-5"><b>{{ $order->order_number }}</b></h5>
                            </div>
                            <div class="row-data" style="border:none;">
                                <div class="item-info">
                                    <h5 class="item-title"><b>{{$lang->data['order_date']??'Order Date'}}</b></h5>
                                </div>
                                <h5 class="my-5">
                                    <b>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</b></h5>
                            </div>
                            <div class="row-data" style="border:none;">
                                <div class="item-info">
                                    <h5 class="item-title"><b>{{$lang->data['delivery_date']??'Delivery Date'}}</b></h5>
                                </div>
                                <h5 class="my-5">
                                    <b>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }} {{ $order->delivery_time }}</b>
                                </h5>
                            </div>
                            <div class="row-data" style="border:none;">
                                <div class="item-info">
                                    <h5 class="item-title"><b>{{$lang->data['delivered_date']??'Delivered Date'}}</b></h5>
                                </div>
                                <h5 class="my-5">
                                    @if($order->delivered_date != '')
                                    <b>{{ \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y h:i: A') }}</b>
                                    @endif
                                </h5>
                            </div>

                            <div class="row-data" style="border:none;">
                                <div class="item-info">
                                    <h5 class="item-title"><b>{{ $lang->data['delivery_type'] ?? 'Delivery Type' }}</b></h5>
                                </div>
                                <h5 class="my-5">
                                    <b>{{ $order->delivery_type }}</b>
                                   
                                </h5>
                            </div>
                           

                            <div class="invoice-title" style="text-align: right">
                                <h6 class="heading1">{{ $lang->data['service_name'] ?? 'Service Name' }}</h6>
                                <h6 class="heading1 heading-child">{{ $lang->data['rate'] ?? 'Rate' }}</h6>
                                <h6 class="heading1 heading-child">{{ $lang->data['qty'] ?? 'QTY' }}</h6>
                                <h6 class="heading1 heading-child">{{ $lang->data['total'] ?? 'Total' }}</h6>
                            </div>
                            @php
                                $qty = 0;
                            @endphp
                            @foreach ($orderdetails as $item)
                                @php
                                    //$service = \App\Models\Service::where('id', $item->service_id)->first();
                                    $service = \App\Models\Service::where('id', $item->order_details->service_id)->first();
                                @endphp
                                <div class="row-data"
                                    style="text-align: center;margin-top: 5px; padding-bottom: 8px; align-items: center">
                                    <div class="item-info" style="width: 82px;text-align: initial;">

                                        <h5 class="item-title"><b>{{ $service->service_name }}</b> <small><?php if($service->size !='') { ?> ({{$service->size}} in feet) <?php } ?></small></h5>
                                        <h5 class="item-title"><b>[{{ $item->order_details->service_name }}]</b></h5>
                                    </div>
                                    {{-- <h5 class="my-5"><b><img src="{{asset('assets/img/service-icons/'.$service->icon)}}" class="avatar avatar-sm me-3 d-flex px-3 py-1" height="25" width="35"></b></h5> --}}
                                    <h5 class="my-5"><b>{{ getCurrency() }}
                                            {{ number_format($item->order_details->service_price, 2) }}</b></h5>
                                    <h5 class="my-5"><b>1</b></h5>
                                    <h5 class="my-5"><b>{{ getCurrency() }}
                                            {{ number_format($item->order_details->service_price, 2) }}</b></h5>
                                </div>
                            @endforeach
                            @php
                                $addons = \App\Models\OrderAddonDetail::where('order_id', $order->id)->get();
                            @endphp
                            @if ($addons)
                                @if (count($addons) > 0)
                                    <h4 style="padding-top: 5px;">{{$lang->data['addons']??'Addons'}}</h4>
                                    @foreach ($addons as $row)
                                        <div class="row-data"
                                            style="text-align: center;margin-top: 5px; padding-bottom: 8px;">
                                            <h5 class="my-5" style="text-align: initial; width: 82px; word-wrap: break-word;">
                                                <b>{{ $row->addon_name }}</b>
                                            </h5>
                                            <h5 class="my-5 "><b>-</b></h5>
                                            <h5 class="my-5"><b>-</b></h5>
                                            <h5 class="my-5"><b>{{ getCurrency() }}
                                                    {{ number_format($row->addon_price, 2) }}</b></h5>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                        <div class="invoice-footer mb-15">
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">{{ $lang->data['invoice_to'] ?? 'Invoice To' }}:</h5>
                                </div>
                                <h5 class="my-5">{{ $customer->name ?? ($lang->data['walk_in_customer'] ?? 'Walk-In Customer') }}<br />
                                    {{ $customer->phone ?? '' }}<br />
                                    {{ $customer->email ?? '' }}<br />
                                    {{ $customer->address ?? '' }}<br />
                                   
                                </h5>
                            </div>
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">{{ $lang->data['sub_total'] ?? 'Sub Total' }}:</h5>
                                </div>
                                <h5 class="my-5">{{ getCurrency() }}
                                    {{ number_format($order->sub_total, 2) }}</h5>
                            </div>
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">{{ $lang->data['addon'] ?? 'Addon' }}:</h5>
                                </div>
                                <h5 class="my-5">
                                    {{ getCurrency() }} {{ number_format($order->addon_total, 2) }}
                                </h5>
                            </div>
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">{{ $lang->data['delivery_charge'] ?? 'Delivery Charge' }}:</h5>
                                </div>
                                <h5 class="my-5">{{ getCurrency() }}
                                    {{ number_format($order->delivery_charge, 2) }}</h5>
                            </div>

                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">{{ $lang->data['discount'] ?? 'Discount' }}:</h5>
                                </div>
                                <h5 class="my-5">{{ getCurrency() }}
                                    {{ number_format($order->discount, 2) }}</h5>
                            </div>
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">{{ $lang->data['tax'] ?? 'Tax' }}
                                        ({{ $order->tax_percentage }}%):</h5>
                                </div>
                                <h5 class="my-5">{{ getCurrency() }}
                                    {{ number_format($order->tax_amount, 2) }}</h5>
                            </div>
                            <div class="row-data">
                                <div class="item-info">
                                    <h5 class="item-title">{{ $lang->data['gross_total'] ?? 'Gross Total' }}:
                                    </h5>
                                </div>
                                <h5 class="my-5">{{ getCurrency() }} {{ number_format($order->total, 2) }}
                                </h5>
                            </div>
                            <hr>
                        </div>
                        <div class="invoice_address">
                            <div class="text-center">
                                <h3 class="mt-10">
                                    {{ isset($site['default_thanks_message']) && !empty($site['default_thanks_message'])? $site['default_thanks_message']: '' }}
                                </h3>
                                <p class="b_top">{{$lang->data['powered_by']??'Powered By   '}} <b>{{ getApplicationName() }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
        </body>
        <div>
        <a href="https://notebrains.com/dev/laundryking/public/api/order/print-order/16" target="_blank">
        <button class="btn"><i class="fa fa-download"></i> Download File</button>
        </a>
        </div>
        </html>
    @endif
</div>
<script type="text/javascript">
 "use strict";
    window.onload = function() {
        window.open('', '', 'left=0,top=0,width=800,height=600,toolbar=0,scrollbars=0,status=0');
        try {
          // Print for Safari browser
          document.execCommand('print', false, null)
        } catch {
          window.print()
        }
        setTimeout(function() {
            window.close();
        }, 1);
    }
</script>