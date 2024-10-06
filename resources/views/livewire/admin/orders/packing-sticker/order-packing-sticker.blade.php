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

            @media print {
                html, body {
                    width: 100%;
                    height:100%;
                    position:absolute;
                    top:0px;
                    bottom:0px;
                    margin: auto;
                    margin-top: 0px !important;
                    size: auto;
                    margin: 0mm 0 0mm 0;
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

            .invoice-card
            {
                display: flex;
                flex-direction: column;
                width: 80mm;
                padding: 10px;
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
    <div class="wrapper">
        <div class="invoice-card"  style="border:2px solid #000;">
            @if($orderdetails)
            @php
            $service = \App\Models\Service::where('id', $orderdetails->order_details->service_id)->first();
            @endphp
            @if($order_number==1)
            <p style="font-size:26px; text-align:center; margin: 5px 0px 0px;"><b>{{ $orderdetails->order_details->order->order_number }}</b></p>
            @endif
            @if($order_status==1)
            @if ($orderdetails->status == 0)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['pending'] ?? 'Pending' }}</a>
            @elseif($orderdetails->status == 1)
                <a style="text-align:center; text-transform:uppercase; margin:5px;"style="background:#83ce2d;">{{ $lang->data['confirm'] ?? 'Confirm' }}</a>
            @elseif($orderdetails->status == 2)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['picked_up'] ?? 'Picked Up' }}</a>
            @elseif($orderdetails->status == 3)
                <a style="text-align:center; text-transform:uppercase; margin:5px;"style="background:#FF597B;">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</a>
            @elseif($orderdetails->status == 4)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['in_transit'] ?? 'In Transit' }}</a>
            @elseif($orderdetails->status == 5)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['processing'] ?? 'Processing' }}</a>
            @elseif($orderdetails->status == 6)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</a>
            @elseif($orderdetails->status == 7)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['ready'] ?? 'Ready' }}</a>
            @elseif($orderdetails->status == 8)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }}</a>
            @elseif($orderdetails->status == 9)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['delivered'] ?? 'Delivered' }}</a>
            @elseif($orderdetails->status == 10)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['cancel'] ?? 'Cancel' }}</a>
            @elseif($orderdetails->status == 11)
                <a style="text-align:center; text-transform:uppercase; margin:5px;">{{ $lang->data['out_for_pickup'] ?? 'Out for Pickup' }}</a>
            @endif
            @endif
            @if($servic==1)
            <p style="text-align:center; text-transform:uppercase; margin:5px;">{{ $orderdetails->order_details->service_name }}</p>
            @endif
            @if($tag_id==1)
            <p style="font-size:26px; text-align:center; margin: 5px 0px 0px;"><b>{{ $orderdetails->garment_tag_id }}</b></p>
            @endif
            @php
            $garment_name_master = \App\Models\MasterSettings::where('master_title', 'garment_name')->first();
            $srvc = $garment_name_master->master_value;
            @endphp
            @if($srvc==1)
            <p style="text-align:center; text-transform:uppercase; margin:5px;">{{$service->service_name}}</p>
            @endif
            @php
             $outletname = \App\Models\Outlet::where('id',$orderdetails->order_details->order->outlet_id)->first();
            @endphp
            @if($outlet==1)
            <p style="text-align:center; text-transform:uppercase; margin:5px;">{{$outletname->outlet_name}}</p>
            @endif
            @if($customer==1)
            <p style="text-align:center; text-transform:uppercase; margin:5px;">{{$orderdetails->order_details->order->customer_name}}</p>
            @endif
            @php 
                $customer = \App\Models\Customer::where('id', $orderdetails->order_details->order->customer_id)->first();
            @endphp
            @if($address==1 && $customer->address != NULL)
            <p style="text-align:center; text-transform:uppercase; margin:5px;">{{$customer->address}}</p>
            @endif
            @if($delivery_date==1)
            <small style="text-align:center;  margin:5px;">{{ \Carbon\Carbon::parse($orderdetails->order_details->order->delivery_date)->format('d/M/Y') }} <?php echo date('l', strtotime(\Carbon\Carbon::parse($orderdetails->order_details->order->delivery_date)->format('d/M/Y'))); ?></small>
            @endif
            
        </div>
        

        <!-- <button class="btn" style="margin:0px auto;">Back to List</button> -->

    </div>
    </body>
    </html>
    @else
        {{"No order found"}}
    @endif

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

    @page {size: Packing-sticker max-height:100%; max-width:100%; 
    -moz-transform:rotate(180deg) scale(1.2,1.2)}

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