<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;
/* login */
Route::group(['middleware' => ['installed']], function () {
    Route::get('/',[\App\Http\Livewire\Login::class, '__invoke'])->middleware('installed')->name('login');
});
//download invoice
Route::get('orders/download-invoice/{id}', \App\Http\Livewire\Admin\Orders\DownloadInvoice\DownloadInvoicePrint::class);

/* Reset Password */
Route::get('/reset-password/{token}',[\App\Http\Livewire\ResetPassword::class, '__invoke']);
/* admin section */
Route::group(['prefix' => 'admin','middleware' => ['store','installed','StoreRestrictedTime']], function () {
    /* Admin Dashboard */
    Route::get('dashboard', \App\Http\Livewire\Admin\Dashboard::class)->name('admin.dashboard')->middleware('admin');
    Route::group(['prefix' => 'expense/'], function () {
        Route::get('categories', \App\Http\Livewire\Admin\Expense\ExpenseCategories::class)->name('admin.expense_categories');
        Route::get('/', \App\Http\Livewire\Admin\Expense\Expenses::class)->name('admin.expenses');
    });
    /* service management*/
    Route::group(['prefix' => 'service/','middleware' => 'admin'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Service\ServiceList::class)->name('admin.service_list');
        Route::get('create', \App\Http\Livewire\Admin\Service\ServiceCreate::class)->name('admin.service_create');
        Route::get('edit/{id}', \App\Http\Livewire\Admin\Service\ServiceEdit::class)->name('admin.service_edit');
        Route::get('addons', \App\Http\Livewire\Admin\Service\ServiceAddons::class)->name('admin.service_addons');
        Route::get('type', \App\Http\Livewire\Admin\Service\ServiceType::class)->name('admin.service_type');
        Route::get('rate-chart', \App\Http\Livewire\Admin\Service\RateChart::class)->name('admin.rate_chart');
        Route::get('category', \App\Http\Livewire\Admin\Service\ServiceCategories::class)->name('admin.service_category');
    });

    /* customers */
    Route::get('customers', \App\Http\Livewire\Admin\Customers\Customers::class)->name('admin.customers');
    /* orders */
    Route::group(['prefix' => 'orders/'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Orders\ViewOrders::class)->name('admin.view_orders');
        Route::get('create', \App\Http\Livewire\Admin\Orders\AddOrders::class)->name('admin.create_orders');
        Route::get('view/{id}', \App\Http\Livewire\Admin\Orders\ViewSingleOrder::class)->name('admin.view_single_order');
        Route::get('edit/{id}', \App\Http\Livewire\Admin\Orders\EditOrders::class)->name('admin.edit_single_order');
        Route::get('print-order/{id}', \App\Http\Livewire\Admin\Orders\PrintInvoice\OrderInvoicePrint::class);
        Route::get('tag-generate/{id}', \App\Http\Livewire\Admin\Orders\TagGenerate\OrderTagGenerate::class);
        Route::get('packing-sticker/{id}', \App\Http\Livewire\Admin\Orders\PackingSticker\OrderPackingSticker::class);
        // Route::get('export', \App\Http\Livewire\Admin\Orders\ExportCustomer::class)->name('exportdata');
    });
    Route::get('order-rewash-request', \App\Http\Livewire\Admin\Orders\RewashRequest::class)->name('admin.rewash_requested');
    Route::get('order-cancel-request', \App\Http\Livewire\Admin\Orders\CancelRequest::class)->name('admin.cancel_request');

    Route::get('pos', \App\Http\Livewire\Admin\Orders\AddOrders::class)->name('admin.pos');
    /* order status */
    Route::get('order-status', \App\Http\Livewire\Admin\Orders\OrderStatusScreen::class)->name('admin.status_screen_order');
    Route::get('garment-status', \App\Http\Livewire\Admin\Orders\GarmentStatusScreen::class)->name('admin.status_screen_garment');
    Route::get('packing-sticker', \App\Http\Livewire\Admin\Orders\PackingSticker::class)->name('admin.packing_sticker');

    /* settings */
    Route::group(['prefix' => 'settings/','middleware' => 'admin'], function () {
        Route::get('master', \App\Http\Livewire\Admin\Settings\MasterSetting::class)->name('admin.master_settings');
        Route::get('report', \App\Http\Livewire\Admin\Settings\ReportSetting::class)->name('admin.report_settings');
        Route::get('membership', \App\Http\Livewire\Admin\Membership\Memberships::class)->name('admin.membership');
        Route::get('translations/add', \App\Http\Livewire\Admin\Translations\AddTranslations::class)->name('admin.add_translations');
        Route::get('translations/', \App\Http\Livewire\Admin\Translations\Translations::class)->name('admin.translations');
        Route::get('translations/edit/{id}', \App\Http\Livewire\Admin\Translations\EditTranslations::class)->name('admin.edit_translations');
        Route::get('financial-year', \App\Http\Livewire\Admin\Settings\FinancialYearSettings::class)->name('admin.financial_year_settings');
        Route::get('mail', \App\Http\Livewire\Admin\Settings\MailSettings::class)->name('admin.mail_settings');
        Route::get('file-tools', \App\Http\Livewire\Admin\Settings\FileTools::class)->name('admin.filetools');
        Route::get('sms', \App\Http\Livewire\Admin\Settings\SMSSettings::class)->name('admin.sms');
        Route::get('staff', \App\Http\Livewire\Admin\Staff\Staff::class)->name('admin.staff');
        Route::get('outlet', \App\Http\Livewire\Admin\Outlet\Outlets::class)->name('admin.outlet');
        Route::get('voucher', \App\Http\Livewire\Admin\Voucher\Vouchers::class)->name('admin.voucher');
        Route::get('brand', \App\Http\Livewire\Admin\Brand\Brands::class)->name('admin.brand');
        Route::get('workstation', \App\Http\Livewire\Admin\Workstation\Workstations::class)->name('admin.workstation');
        Route::get('delivery-master', \App\Http\Livewire\Admin\Delivery\Delivery::class)->name('admin.delivery_master');
        Route::get('promotion', \App\Http\Livewire\Admin\Promotion\Promotions::class)->name('admin.promotion');
    });

    /* reports section */
    Route::group(['prefix' => 'reports/'], function () {
        Route::get('daily', \App\Http\Livewire\Admin\Reports\DailyReport::class)->name('admin.daily_report');
        Route::get('expense', \App\Http\Livewire\Admin\Reports\ExpenseReport::class)->name('admin.expense_report');
        Route::get('order', \App\Http\Livewire\Admin\Reports\OrderReport::class)->name('admin.order_report');
        Route::get('sales', \App\Http\Livewire\Admin\Reports\SalesReport::class)->name('admin.sales_report');
        Route::get('tax', \App\Http\Livewire\Admin\Reports\TaxReport::class)->name('admin.tax_report');
        Route::get('customer-order', \App\Http\Livewire\Admin\Reports\CustomerReport::class)->name('admin.customer_report');
        Route::get('customer-history', \App\Http\Livewire\Admin\Reports\CustomerHistoryReport::class)->name('admin.customer_history_report');
        Route::get('outlet', \App\Http\Livewire\Admin\Reports\OutletReport::class)->name('admin.outlet_report');
        Route::get('workstation', \App\Http\Livewire\Admin\Reports\WorkstationReport::class)->name('admin.workstation_report');
        Route::get('rewash', \App\Http\Livewire\Admin\Reports\RewashReport::class)->name('admin.rewash_report');
        Route::get('stock', \App\Http\Livewire\Admin\Reports\StockReport::class)->name('admin.stock_report');
        Route::get('service', \App\Http\Livewire\Admin\Reports\ServiceReport::class)->name('admin.service_report');
        Route::get('garment', \App\Http\Livewire\Admin\Reports\GarmentReport::class)->name('admin.garment_report');
        Route::get('settlement', \App\Http\Livewire\Admin\Reports\SettlementReport::class)->name('admin.settlement_report');
        Route::get('workstation-note', \App\Http\Livewire\Admin\Reports\WorkstationNote::class)->name('admin.workstationnote_report');

        /* print reports */
        Route::group(['prefix' => 'print-report/'], function () {
            Route::get('expense/{from_date}/{to_date}', \App\Http\Livewire\Admin\Reports\PrintReport\ExpenseReport::class);
            Route::get('sales/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\SalesReport::class);
            Route::get('tax/{from_date}/{to_date}/{category}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\TaxReport::class);
            Route::get('order/{from_date}/{to_date}/{status}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\OrderReport::class);
            Route::get('outlet/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\OutletReport::class);
            Route::get('workstation/{from_date}/{to_date}/{workstation}', \App\Http\Livewire\Admin\Reports\PrintReport\WorkstationReport::class);
            Route::get('workstationnote/{from_date}/{to_date}/{workstation}', \App\Http\Livewire\Admin\Reports\PrintReport\WorkstationNoteReport::class);
            Route::get('rewash/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\RewashReport::class);
            Route::get('stock/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\StockReport::class);
            Route::get('service/{from_date}/{to_date}/{category}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\ServiceReport::class);
            Route::get('garment/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\PrintReport\GarmentReport::class);
            Route::get('settlement/{from_date}/{to_date}', \App\Http\Livewire\Admin\Reports\PrintReport\SettlementReport::class);
            Route::get('customer/{from_date}/{to_date}', \App\Http\Livewire\Admin\Reports\PrintReport\CustomerReport::class);
            Route::get('customer-history/{from_date}/{to_date}', \App\Http\Livewire\Admin\Reports\PrintReport\CustomerHistoryReport::class);
        
        });
        /* download reports */
        Route::group(['prefix' => 'download-report/'], function () {
            Route::get('expense/{from_date}/{to_date}', \App\Http\Livewire\Admin\Reports\DownloadReport\ExpenseReport::class);
            Route::get('sales/{from_date}/{to_date}', \App\Http\Livewire\Admin\Reports\DownloadReport\SalesReport::class);
            Route::get('tax/{from_date}/{to_date}/{category}', \App\Http\Livewire\Admin\Reports\DownloadReport\TaxReport::class);
            Route::get('order/{from_date}/{to_date}/{status}', \App\Http\Livewire\Admin\Reports\DownloadReport\OrderReport::class);
        Route::get('outlet/{outlet}', \App\Http\Livewire\Admin\Reports\DownloadReport\OutletReport::class);
            Route::get('workstation/{workstation}', \App\Http\Livewire\Admin\Reports\DownloadReport\WorkstationReport::class);
            Route::get('workstationnote/{workstation}', \App\Http\Livewire\Admin\Reports\DownloadReport\WorkstationNoteReport::class);
            Route::get('rewash/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\DownloadReport\RewashReport::class);
        Route::get('stock/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\DownloadReport\StockReport::class);
            Route::get('service/{outlet}', \App\Http\Livewire\Admin\Reports\DownloadReport\ServiceReport::class);
        Route::get('garment/{from_date}/{to_date}/{outlet}', \App\Http\Livewire\Admin\Reports\DownloadReport\GarmentReport::class);

        });
    });
});

Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return '<center><h1>All Cleared</h1></center>';
});

/* Installation */
Route::get('/install', [InstallController::class,'install'])->name('install');
Route::get('/install/dbsettings', [InstallController::class,'dbsettings'])->name('dbsettings');
Route::post('/install/postDatabase', [InstallController::class,'postDatabase'])->name('postDatabase');
Route::get('/install/completed', [InstallController::class,'install_completed'])->name('install_completed');

Route::get('update', \App\Http\Livewire\Update\Updater::class)->name('update');
