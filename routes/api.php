<?php
use App\Http\Controllers\Api\Customer\AddressController;
use App\Http\Controllers\Api\Customer\CartItemController;
use App\Http\Controllers\Api\Customer\ChangePasswordController;
use App\Http\Controllers\Api\Customer\CustomerQueryController;
use App\Http\Controllers\Api\Customer\DeliveryTypesController;
use App\Http\Controllers\Api\Customer\ForgotpasswordController;
use App\Http\Controllers\Api\Customer\LoginController;
use App\Http\Controllers\Api\Customer\OrderController;
use App\Http\Controllers\Api\Customer\FeedbackController;
use App\Http\Controllers\Api\Customer\ProfileCustomerController;
use App\Http\Controllers\Api\Customer\ServiceController;
use App\Http\Controllers\Api\Customer\DefectItemsController;
use App\Http\Controllers\Api\Customer\slideController;
use App\Http\Controllers\Api\Customer\VoucherController;
use App\Http\Controllers\Api\Customer\RewashController;
use App\Http\Controllers\Api\Customer\NotificationController;
use App\Http\Controllers\Api\User\AddCustomerController;
use App\Http\Controllers\Api\User\GarmentController;
use App\Http\Controllers\Api\User\FloorManagerController;
use App\Http\Controllers\Api\User\DriverVoucherController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\User\DriverQueryController;
use App\Http\Controllers\Api\User\CustomerAddressController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\DriverOrderController;
use App\Http\Controllers\Api\User\OutletsController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApitestController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Customer
Route::post('customer/register', [LoginController::class, 'registercustomer']);
Route::post('customer/login', [LoginController::class, 'logincustomer']);

//customer forgot Password
Route::controller(ForgotpasswordController::class)->group(function () {
    Route::post('customer/otp/generate', 'otpgenerate')->name('otpgenerate');
    Route::post('customer/otp/login', 'otplogin')->name('otplogin');
    Route::post('customer/otp/resend', 'otpresend')->name('otpresend');
    Route::post('update/password', 'update_password')->name('update_password');
});
//Refer code apply
Route::post('apply/refer/code', [LoginController::class, 'refercode']);

//Customer Profile
Route::middleware('auth:customer')->group(function () {
// Route::group(['middleware' => ['auth:customer', 'cors', 'json.response']], function () {
    Route::get('customer/profile', [ProfileCustomerController::class, 'index']);
    Route::post('customer/update/profile', [ProfileCustomerController::class, 'updatecustomer']);
    Route::delete('customer/delete/profile', [ProfileCustomerController::class, 'delete_customer']);
    Route::post('customer/logout', [LoginController::class, 'logoutcustomer']);
    Route::get('outstanding/list', [ProfileCustomerController::class, 'outstanding_list']);
    Route::post('outstanding/payment', [ProfileCustomerController::class, 'outstandingpayment']);
    Route::get('customer/membership', [ProfileCustomerController::class, 'membership']);
    
    //Address Book
    Route::post('customer/address/register', [AddressController::class, 'registeraddress']);
    Route::post('customer/address/update', [AddressController::class, 'updateaddress']);
    Route::post('/customer/address/delete', [AddressController::class, 'deleteaddress']);
    Route::post('customer/address/view', [AddressController::class, 'customer_address']);
    Route::post('customer/address/default', [AddressController::class, 'default_address']);

    
    //Update Device Token
    Route::post('update/device/token', [NotificationController::class, 'update_device_token']);
    
    //Voucher apply
    Route::post('apply/voucher', [VoucherController::class, 'voucher']);

    //Voucher apply
    Route::post('customer/approve/request', [DefectItemsController::class, 'approve_request']);

    //Defected items list 
    Route::get('customer/defected/items', [DefectItemsController::class, 'index']);

    //customer queries
    Route::post('/query', [CustomerQueryController::class, 'index']);

    //Service
    Route::post('/service', [ServiceController::class, 'index']);
    Route::get('repeat/items', [ServiceController::class, 'repeatitem']);

    //Cart items
    Route::get('/cart/item', [CartItemController::class, 'index']);
    Route::post('/cart/calculation', [CartItemController::class, 'calculation']);
    Route::post('/cart/item/update', [CartItemController::class, 'update']);
    Route::post('/cart/item', [CartItemController::class, 'insert']);
    Route::get('/cart/item-edit/{service_id}', [CartItemController::class, 'edit']);
    Route::delete('/cart/item', [CartItemController::class, 'delete']);
    Route::post('remove/item', [CartItemController::class, 'removeitem']);
    Route::post('/add/item', [CartItemController::class, 'additem']);

    //Change password
    Route::post('/change/password', [ChangePasswordController::class, 'index']);

    //Order details
    Route::get('/orders/list', [OrderController::class, 'index']);
    Route::get('/order/details', [OrderController::class, 'orders']);
    Route::post('order/add', [OrderController::class, 'insert']);
    Route::get('cancel/order', [OrderController::class, 'cancelOrder']);

    //Rewash 
    Route::post('rewash', [RewashController::class, 'rewash']);
    Route::post('rewash/image/upload', [RewashController::class, 'rewashimageupload']);
    Route::get('item/list', [RewashController::class, 'list']);

    //Feedback
    Route::post('customer/feedback', [FeedbackController::class, 'insert']);

    //Slides 
    Route::post('notification/delete', [NotificationController::class, 'deletenotification']);

});

// Driver Login
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:user')->group(function () {
    //Profile
    Route::post('driver/profile', [ProfileController::class, 'index']);
    Route::post('driver/update/profile', [ProfileController::class, 'update']);
    Route::post('driver/delete/profile', [ProfileController::class, 'delete']);

    //Order details for driver
    Route::post('driver/insert/order', [DriverOrderController::class, 'insert']);

    //Assign order driver
    Route::get('driver/assign/orders', [DriverOrderController::class, 'assignorder']);
    Route::get('driver/assign/list', [DriverOrderController::class, 'assignorder_listing']);
    Route::get('driver/orders/list', [DriverOrderController::class, 'index']);
    Route::get('driver/order/details', [DriverOrderController::class, 'orders']);
    Route::post('driver/status/change', [DriverOrderController::class, 'status']);

    //search customer using phone number
    Route::get('search/customer', [SearchController::class, 'search']);

//update device token
    Route::post('update/device/token/user', [FloorManagerController::class, 'update_device_token_user']);



    //Cart items for driver
    Route::get('driver/cart/item', [CartController::class, 'index']);
    Route::post('driver/cart/calculation', [CartController::class, 'calculation']);
    Route::post('driver/cart/item/update', [CartController::class, 'update']);
    Route::post('driver/cart/item', [CartController::class, 'insert']);
    Route::post('driver/cart/item-edit/{service_id}', [CartController::class, 'edit']);
    Route::delete('driver/cart/item', [CartController::class, 'delete']);
    Route::post('driver/remove/item', [CartController::class, 'removeitem']);
    Route::post('driver/add/item', [CartController::class, 'additem']);

    //Service
    Route::post('driver/service', [GarmentController::class, 'index']);
    Route::get('driver/repeat/items', [GarmentController::class, 'repeatitem']);

    //driver query
    Route::post('driver/query', [DriverQueryController::class, 'index']);


    //Add customer by driver
    Route::post('add/customer', [AddCustomerController::class, 'register']);

    //Voucher apply
    Route::post('driver/apply/voucher', [DriverVoucherController::class, 'voucher']);

    //Floor manager image upload
    Route::post('floormanager/image/insert', [FloorManagerController::class, 'insert']);
    Route::post('floormanager/order', [FloorManagerController::class, 'orders']);
    Route::post('floormanager/imagecheck', [FloorManagerController::class, 'imagecheck']);
    Route::get('floormanager/accept/order/listing', [FloorManagerController::class, 'listing']);

    //Driver Address Book
    Route::post('driver/address/register', [CustomerAddressController::class, 'registeraddress']);
    Route::post('driver/address/update', [CustomerAddressController::class, 'updateaddress']);
    Route::post('/driver/address/delete', [CustomerAddressController::class, 'deleteaddress']);
    Route::post('driver/address/view', [CustomerAddressController::class, 'driver_address']);
    Route::post('driver/address/default', [CustomerAddressController::class, 'default_address']);

    //Logout Driver
    Route::post('driver/logout', [AuthController::class, 'logout']);
});

//voucher show
Route::get('/voucher', [VoucherController::class, 'index']);

//Outlets
Route::get('/outlet', [OutletsController::class, 'index']);
Route::post('/outlet/available', [OutletsController::class, 'outlet']);

//workstation
Route::post('/workstation', [OutletsController::class, 'workstation']);

//Notifiction 
Route::post('sendNotification', [NotificationController::class, 'sendNotification']);
Route::get('notification/list', [NotificationController::class, 'index']);

//service category
Route::get('servicetype/addons/brand', [ServiceController::class, 'servicecategory']);

//Delivery
Route::get('/delivery', [DeliveryTypesController::class, 'index']);

//OrderInvoicePrint
Route::get('order/print-order/{id}', \App\Http\Livewire\Admin\Orders\PrintInvoice\OrderInvoicePrint::class);

//selected addons 
Route::post('customer/selected/addons', [ServiceController::class, 'selected_addons']);

//sruti
// msg91 webhhok

Route::get('/msg91-webhook', [ApitestController::class, 'get_webhhok']);
Route::post('/msg91-webhook', [ApitestController::class, 'post_webhook']);

Route::get('api/test', [ApitestController::class, 'test']);

//






