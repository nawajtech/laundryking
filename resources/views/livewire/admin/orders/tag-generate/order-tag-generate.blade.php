<div>
    @php
        $printer_type = getPrinterType();
    @endphp

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
            @media print {
            html, body {
                height: 100%;
            }
        }

        </style>
    </head>
    <body>
    <div class="page-wrapper" style="padding:36px">
        <div class="invoice-card">
            @php
                $i=1;
            @endphp
            @foreach ($orderdetails as $item)
                    @php
                        $service = \App\Models\Service::where('id', $item->order_details->service_id)->first();
                    @endphp
                    <p style="font-size:26px; text-align:center;margin: 8px 0px 0px;"><b>{{ $order->order_number.'-'.$i }}</b></p>
                    <p style="text-align:center; text-transform:uppercase; margin:5px;">{{$order->outlet->outlet_name ?? ""}}</p>
                    <div class="position-relative">
                        <p class="tag-outlet">{{ $order->deliveryoutlet->outlet_name ?? "" }}</p>
                        <p class="tag-outlet2">{{ $order->deliveryoutlet->outlet_name ?? "" }}</p>
                    </div>
                    <p style="text-align:center; text-transform:uppercase; margin:5px;">{{$customer->name}}</p>
                    <p style="text-align:center; text-transform:uppercase; margin:5px;">{{ $item->order_details->service_name }}</p>
                    <p style="margin:5px; margin-top:-15px; margin-right:30px;"><span style="float:right;"> {{$i}} / {{ $totalitem }} </span></p>
                    @php
                        $a=1;
                        $addon = \App\Models\OrderAddonDetail::where('order_detail_id', $item->order_details->id)->get();
                    @endphp
                    @if($addon)
                        <p style="text-align:center; text-transform:uppercase; margin:5px; font-size:12px;">
                            @if(count($addon) > $a) Addon : @endif
                            @foreach($addon as $viewaddon)
                                {{$viewaddon->addon_name}}
                                @if(count($addon) > $a) , @endif  @php $a++; @endphp
                            @endforeach
                        </p>
                    @endif
                    <p style="text-align:center; text-transform:uppercase; margin:5px;">{{ $delivery_type->delivery_name ?? "" }}</p>
                    <small style="text-align:center;  margin:5px;">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/M/Y') }} <?php echo date('l', strtotime(\Carbon\Carbon::parse($order->delivery_date)->format('d/M/Y'))); ?></small>
                    <h4 style="text-align:center; font-size:20px;" class="item-title">
                        <b>{{ $service->service_name }}</b>
                        @if($item->order_details->color_code!="")
                            <button class="btn" style="margin:0px auto; border:1px solid #000; width:20px; height:20px; background-color: {{$item->order_details->color_code}}!important"></button>
                        @endif
                    </h4>
                    <p style="text-align:center; text-transform:uppercase; margin:5px; font-size:12px; padding-bottom:10px;">{{ $item->order_details->brand }}</p>
                    @php
                        $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                    @endphp
                    <div style="border-bottom:1px solid #000; padding-bottom: 8px;">
                        <img style="margin:0px auto; display:block; margin-bottom:5px;" src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($order->order_number.'-'.$i, $generatorPNG::TYPE_CODE_128)) }}">
                    </div>

                    @php
                        $i++;
                    @endphp
            @endforeach
        </div>
    </div>
    </body>
    </html>

</div>
<style>
    .tag-outlet{
        letter-spacing:3px;
        height:180px;
        display:block;
        text-transform:uppercase;
        margin:5px;
        font-size:12px;
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        position:absolute;
        text-align: center;
    }
    p.tag-outlet2 {
        letter-spacing: 3px;
        height: 180px;
        display: block;
        text-transform: uppercase;
        margin: 5px;
        font-size: 12px;
        writing-mode: vertical-lr;
        transform: rotate(180deg);
        position: absolute;
        right: 0;
        text-align: center;
    }

    .invoice-card {
        position: relative;
    }

    @page {
    size: Tag;
    margin: 5px;
    }
    @media print {
    .invoice-card {
        margin: 5px;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    }
}
</style>

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