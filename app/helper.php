<?php 
/* get expense category type */

use App\Models\Customer;
use App\Models\Order;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

function getExpenseCategoryType($type)
{
    if(session()->has('selected_language'))
    {
        $lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
    }
    else{
        $lang = \App\Models\Translation::where('default',1)->first();
    }
    if($lang)
    {
        switch($type)
        {
            case 1:
                return $lang->data['asset'] ?? 'Asset';
            case 2:
                return  $lang->data['liability'] ?? 'Liability';
            default:
                return '';
        }
    }
    switch($type)
    {
        case 1:
            return 'Asset';
        case 2:
            return 'Liability';
        default:
            return '';
    }
}

/* get payment mode */
function getpaymentMode($type)
{
    switch($type)
    {
        case 1:
            return 'CASH';
        case 2:
            return 'UPI';
        case 3:
            return 'CARD';
        case 4:
            return 'CHEQUE';
        case 5:
            return 'BANK TRANSFER';
        case 6:
            return 'LK CREDIT';
        case 7:
            return 'CASH ON DELIVERY';
        case 8:
            return 'RAZOR PAY';
        default:
            return '';
    }
}

/* get financial year */
function getFinancialYearId() {
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if(isset($site['default_financial_year']))
    {
        $year_id = (($site['default_financial_year']) && ($site['default_financial_year'] !=""))? $site['default_financial_year'] : '';
        return $year_id;
    }
    return null;
}

/* get Currency */
function getCurrency() {
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if(isset($site['default_currency']))
    {
        $currency = (($site['default_currency']) && ($site['default_currency'] !=""))? $site['default_currency'] : '$';
        return $currency;
    }
    return '$';
}

/* get Tax percentage */
function getTaxPercentage() {
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if(isset($site['default_tax_percentage']))
    {
        $tax = (($site['default_tax_percentage']) && ($site['default_tax_percentage'] !=""))? $site['default_tax_percentage'] : 0;
        return $tax;
    }
    return 0;
}

/* get order status */
function getOrderStatus($status,$preventlang=null)
{
    if(session()->has('selected_language'))
    {
        $lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
    }
    else{
        $lang = \App\Models\Translation::where('default',1)->first();
    }
    if($lang == null || $preventlang)
    {
        switch($status)
        {
            case -1:
                return 'All Orders';
            case 0:
                return 'Pending';
            case 1:
                return 'Confirm';    
            case 2:
                return 'Picked Up';
            case 3:
                return 'To be Processed';
            case 4:
                return 'In Transit';
            case 5:
                return 'Processing';
            case 6:
                return 'Sent to Store';
            case 7:
                return 'Ready';
            case 8:
                return 'Out for Delivery';
            case 9:
                return 'Delivered';
            case 10:
                return 'Cancel';
            case 11 :
                return 'Out for pickup';
        }
    } else{
        switch($status)
        {
            case -1:
                return 'All Orders';
            case 0:
                return $lang->data['pending'] ?? 'Pending';
            case 1:
                return $lang->data['confirm'] ?? 'Confirm';
            case 2:
                return $lang->data['picked_up'] ?? 'Picked Up';
            case 3:
                return $lang->data['to_be_processed'] ?? 'To be Processed';
            case 4:
                return $lang->data['in_transit'] ?? 'In Transit';
            case 5:
                return $lang->data['processing'] ?? 'Processing';
            case 6:
                return $lang->data['sent_to_store'] ?? 'Sent to Store';
            case 7:
                return $lang->data['ready'] ?? 'Ready';
            case 8:
                return $lang->data['out_for_delivery'] ?? 'Out for Delivery';
            case 9:
                return $lang->data['delivered'] ?? 'Delivered';
            case 10:
                return $lang->data['cancel'] ?? 'Cancel';
            case 11:
                return $lang->data['out_for_pickup'] ?? 'Out for pickup';
        }
    }
}

/* get order status wit color */
function getOrderStatusWithColor($status)
{
    switch($status)
    {
        case 0:
            return 'today-task-pending';
        case 1:
            return 'today-task-confirm';
        case 2:
            return 'today-task-picked_up';
        case 3:
            return 'today-task-to_be_processed';
        case 4:
            return 'today-task-in_transit';
        case 5:
            return 'today-task-processing';
        case 6:
            return 'today-task-sent_to_store';
        case 7:
            return 'today-task-ready';
        case 8:
            return 'today-task-out_for_delivery';
        case 9:
            return 'today-task-delivered';
        case 10:
            return 'today-task-cancel';
        case 11:
            return 'today-task-out_for_pickup';
    }
}

/* get order status with color for change status screen */
function getOrderStatusWithColorKan($status)
{
    switch($status)
    {
        case 0:
            return 'scrum-task-pending';
        case 1:
            return 'scrum-task-confirm';
        case 2:
            return 'scrum-task-picked_up';
        case 3:
            return 'scrum-task-to_be_processed';
        case 4:
            return 'scrum-task-in_transit';
        case 5:
            return 'scrum-task-processing';
        case 6:
            return 'scrum-task-sent_to_store';
        case 7:
            return 'scrum-task-ready';
        case 8:
            return 'scrum-task-out_for_delivery';
        case 9:
            return 'scrum-task-delivered';
        case 10:
            return 'scrum-task-cancel';
        case 11:
            return 'scrum-task-out_for_pickup';
    }
}

/* get priner type */
function getPrinterType() {
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if(isset($site['default_printer']))
    {
        $printerType = (($site['default_printer']) && ($site['default_printer'] !=""))? $site['default_printer'] : 1;
        return $printerType;
    }
    return 1;
}

/* get favicon */
function getFavIcon() {
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if(isset($site['default_favicon']) && file_exists(public_path($site['default_favicon'])))
    {
        $favicon = (($site['default_favicon']) && ($site['default_favicon'] !=""))? $site['default_favicon'] : 'assets/img/favicon.png';
        return $favicon;
    }
    return 'assets/img/logo-ct.png';
}

/* get getAppliation Name */
function getApplicationName() {
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if(isset($site['default_application_name']))
    {
        $favicon = (($site['default_application_name']) && ($site['default_application_name'] !=""))? $site['default_application_name'] : 'Laundry Box';
        return $favicon;
    }
    return 'Laundry App';
}

/* get site logo */
function getSiteLogo() {
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    if(isset($site['default_logo']) && file_exists(public_path($site['default_logo'])))
    {
        $favicon = (($site['default_logo']) && ($site['default_logo'] !=""))? $site['default_logo'] : 'assets/img/logo-ct.png';
        return $favicon;
    }
    return 'assets/img/logo-ct.png';
}

//Checks if Selected language is RTL
function isRTL() {
    if(session()->has('selected_language'))
    {  
        $lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
        if($lang)
        {
            if($lang->is_rtl)
            {
                return true;
            }
        }
    }
    return false;
}

function isSMSEnabled()
{
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
        if(isset($site['sms_enabled']) && ($site['sms_enabled'] == 1))
        {
            return true;
        }
    return false;
}

function sendOrderCreateSMS($order,$to)
{
    if(isSMSEnabled() == true)
    {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        $messageerror = null;
        try{
            $account_sid = (($site['sms_account_sid']) && ($site['sms_account_sid'] !=""))? $site['sms_account_sid'] : '';
            $auth_token = (($site['sms_auth_token']) && ($site['sms_auth_token'] !=""))? $site['sms_auth_token'] : '';
            $twilio_number = (($site['sms_twilio_number']) && ($site['sms_twilio_number'] !=""))? $site['sms_twilio_number'] : '';

            $client = new Client($account_sid, $auth_token);
            $myorder = Order::find($order);
            $customer = Customer::find($to);
          
            $message = getFormatedTextSMS($order,1);
            $client->messages->create($customer->phone, 
                ['from' => $twilio_number, 'body' => $message]);
        }
        catch(\Exception $e)
        {
            $messageerror = $e->getMessage();
            if($e->getCode() == 21211)
            {
                $messageerror = 'Could not send SMS,Because the phone number is invalid';
            }
        }
        return $messageerror;
    }
}

function sendOrderStatusChangeSMS($order,$to_status)
{
    if(isSMSEnabled() == true)
    {
        $settings = new App\Models\MasterSettings();
        $site = $settings->siteData();
        $messageerror = null;
        try{
            $account_sid = (($site['sms_account_sid']) && ($site['sms_account_sid'] !=""))? $site['sms_account_sid'] : '';
            $auth_token = (($site['sms_auth_token']) && ($site['sms_auth_token'] !=""))? $site['sms_auth_token'] : '';
            $twilio_number = (($site['sms_twilio_number']) && ($site['sms_twilio_number'] !=""))? $site['sms_twilio_number'] : '';
            $client = new Client($account_sid, $auth_token);
            $myorder = Order::find($order);
            $customer = Customer::find($myorder->customer_id);
            if($customer)
            {
                $message = getFormatedTextSMS($order,2);
                $client->messages->create($customer->phone, 
                    ['from' => $twilio_number, 'body' => $message]);
            }
        }
        catch(\Exception $e)
        {
            $messageerror = $e->getMessage();
            if($e->getCode() == 21211)
            {
                $messageerror = 'Could not send SMS,Because the phone number is invalid';
            }
        }
        return $messageerror;
    }
}

function getFormatedTextSMS($order,$type)
{
    $myorder = Order::find($order);
    $settings = new App\Models\MasterSettings();
    $site = $settings->siteData();
    $string = null;
    if($type == 1)
    {   
        if(isset($site['sms_createorder']) && $site['sms_createorder'] != '')
        {
            $string = $site['sms_createorder'] ?? 'Hi <name> An Order #<order_number> was created and will be delivered on <delivery_date> Your Order Total is <total>.';
        }
        else{
            $string = 'Hi <name> An Order #<order_number> was created and will be delivered on <delivery_date> Your Order Total is <total>.';
        }
    }
    else{
        if(isset($site['sms_statuschange']) && $site['sms_statuschange'] != '')
        {
            $string = $site['sms_statuschange'] ?? 'Hi <name> Your Order #<order_number> status has been changed to <status> on <current_time>';
        }
        else{
            $string =  'Hi <name> Your Order #<order_number> status has been changed to <status> on <current_time>';
        }
    }

    $replacer = [
        '<name>' => 'Customer Name', 
        '<order_date>' => 'Order Date',
        '<delivery_date>' => 'Delivery Date',
        '<no_of_products>' => 'No Of Products',
        '<total>' => 'Total',
        '<discount>' => 'Discount',
        '<paid>' => 'Paid Amount',
        '<status>'  => 'Status',
        '<order_number>'    => 'Order Number',
        '<current_time>'    => 'Current Time'
    ];
    $count = \App\Models\OrderDetails::where('order_id',$order)->count();
    $paid = \App\Models\Payment::where('order_id',$order)->sum('received_amount');
    $replacement = [
        $myorder->customer_name,
        \Carbon\Carbon::parse($myorder->order_date)->format('d/m/Y'),
        \Carbon\Carbon::parse($myorder->delivery_date)->format('d/m/Y'),
        $count,
        getCurrency().number_format($myorder->total,2),
        getCurrency().number_format($myorder->discount,2),
        getCurrency().number_format($paid,2),
        getOrderStatus($myorder->status),
        $myorder->order_number,
        \Carbon\Carbon::now()->format('d/m/Y h:i A')
    ];
    return str_replace(array_keys($replacer), array_values($replacement), $string);
}

/* get user type */
function getuserType($type, $subType = '')
{
    switch($type)
    {
        case 1:
            switch($subType)
            {
                case 0:
                    return 'Super Admin';
                case 1:
                    return 'Sub Admin';
                default:
                    return 'Admin';
            }
        case 2:
            return 'Outlet';
        case 3:
            return 'Floor Manager';
        case 4:
            return 'Driver';
        default:
            return '';
    }
}

//User Has Permission
function user_has_permission($module){
    $permission = UserPermission::where(array('user_id' => Auth::user()->id, 'module' => $module, 'status' => 1))->first();
    if($permission){
        return true;
    }
    return false;
}
?>