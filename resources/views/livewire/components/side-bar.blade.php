
<aside class="@if(isRTL() == true) sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-4 rotate-caret @else  sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 @endif"  data-color="primary">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{route('admin.dashboard')}}">
            <img src="{{asset(getSiteLogo())}}" class="navbar-brand-img" alt="main_logo">
        </a>
    </div>
    <div class="collapse navbar-collapse w-auto h-auto h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if(Auth::user()->user_type==1)
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}" href="{{route('admin.dashboard')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['dashboard'] ?? 'Dashboard'}}</span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('create_order'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/pos') ? 'active' : '' }}" href="{{ route('admin.pos') }}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-shop text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['pos'] ?? 'Create Order'}}</span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('view_order'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}" href="{{route('admin.view_orders')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-basket text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['orders'] ?? 'Orders'}}</span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('rewash_request'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/order-rewash-request') ? 'active' : '' }}" href="{{route('admin.rewash_requested')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-basket text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['order_rewash_requested'] ?? 'Order Rewash Request'}}</span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('cancel_request'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/order-cancel-request') ? 'active' : '' }}" href="{{route('admin.cancel_request')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-basket text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['order_cancel_request'] ?? 'Order Cancel Request'}}</span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('order_status_screen'))
                <li class="nav-item">
                    <a class="nav-link  {{ Request::is('admin/order-status*') ? 'active' : '' }}" href="{{route('admin.status_screen_order')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['order_status_screen'] ?? 'Order Status Screen'}}</span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('garment_status_screen'))
                <li class="nav-item">
                    <a class="nav-link  {{ Request::is('admin/garment-status*') ? 'active' : '' }}" href="{{route('admin.status_screen_garment')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['garment_status_screen'] ?? 'Garment Status Screen'}}</span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('packing_sticker'))
                <li class="nav-item">
                    <a class="nav-link  {{ Request::is('admin/packing-sticker*') ? 'active' : '' }}" href="{{route('admin.packing_sticker')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-tag text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['sticker'] ?? 'Packing Sticker'}} </span>
                    </a>
                </li>
            @endif

            @if(user_has_permission('expense_list') || user_has_permission('expense_category'))
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#expense" class="nav-link {{ Request::is('admin/expense*') ? 'active' : '' }}" aria-controls="settings" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['expense'] ?? 'Expense'}}</span>
                    </a>
                    <div class="collapse {{ Request::is('admin/expense*') ? 'show' : '' }}" id="expense">
                        <ul class="nav ms-4">
                            @if(user_has_permission('expense_list'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/expense') ? 'active' : '' }} " href="{{route('admin.expenses')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> E </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['expense_list'] ?? 'Expense List'}}</span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('expense_category'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/expense/categories') ? 'active' : '' }}" href="{{route('admin.expense_categories')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> E </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['expense_category'] ?? 'Expense Category'}}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(user_has_permission('customer'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/customers*') ? 'active' : '' }}" href="{{route('admin.customers')}}">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-pink text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['customers'] ?? 'Customers'}}</span>
                    </a>
                </li>
            @endif

            
            @if(user_has_permission('manage_category') || user_has_permission('manage_service_type') || user_has_permission('manage_garments') || user_has_permission('manage_addons') || user_has_permission('manage_rate_chart'))
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#services" class="nav-link  {{ Request::is('admin/service*') ? 'active' : '' }}" aria-controls="settings" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['services'] ?? 'Services'}}</span>
                    </a>
                    <div class="collapse {{ Request::is('admin/service*') ? 'show' : '' }}" id="services">
                        <ul class="nav ms-4">
                            @if(user_has_permission('manage_category'))
                                <li class="nav-item {{ Request::is('admin/service/category') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{route('admin.service_category')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> S </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['Category'] ?? 'Category'}}</span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_service_type'))
                                <li class="nav-item {{ Request::is('admin/service/type') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{route('admin.service_type')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> S </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['service_type'] ?? 'Service Type'}}</span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_garments'))
                                <li class="nav-item {{ Request::is('admin/service') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{route('admin.service_list')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> S </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['garments'] ?? 'Garments'}}</span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_addons'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('admin/service/addons') ? 'active' : '' }}" href="{{route('admin.service_addons')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> A </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['addons'] ?? 'Addons'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_rate_chart'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('admin/service/rate-chart') ? 'active' : '' }}" href="{{route('admin.rate_chart')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> R </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['ratechart'] ?? 'Rate Chart'}} </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(user_has_permission('daily_report') || user_has_permission('order_report') || user_has_permission('sales_report') || user_has_permission('expense_report') || user_has_permission('tax_report') || user_has_permission('garment_report') || user_has_permission('customer_order_report') || user_has_permission('customer_history_report') || user_has_permission('outlet_report') || user_has_permission('workstation_report') || user_has_permission('outstanding_report') || user_has_permission('stock_report') || user_has_permission('rewash_report') || user_has_permission('service_report'))
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#tasks" class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}" aria-controls="tasks" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-chart-bar-32 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['reports'] ?? 'Reports'}}</span>
                    </a>

                    <div class="collapse {{ Request::is('admin/reports*') ? 'show' : '' }}" id="tasks">
                        <ul class="nav ms-4">
                            @if(user_has_permission('daily_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/daily') ? 'active' : '' }}" href="{{route('admin.daily_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> D </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['daily_report'] ?? 'Daily Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('order_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/order') ? 'active' : '' }}" href="{{route('admin.order_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> O </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['order_report'] ?? 'Order Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('sales_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/sales') ? 'active' : '' }}" href="{{route('admin.sales_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> S </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['sales_report'] ?? 'Sales Report'}}</span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('expense_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/expense') ? 'active' : '' }}" href="{{route('admin.expense_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> E </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['expense_report'] ?? 'Expense Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('tax_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/tax') ? 'active' : '' }}" href="{{route('admin.tax_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['tax_report'] ?? 'Tax Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('garment_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/garment') ? 'active' : '' }}" href="{{route('admin.garment_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['garment_report'] ?? 'Garment Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('customer_order_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/customer-order') ? 'active' : '' }}" href="{{route('admin.customer_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['customer_report'] ?? 'Customer Order Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('customer_history_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/customer-history') ? 'active' : '' }}" href="{{route('admin.customer_history_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['customer_report'] ?? 'Customer History Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('outlet_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/outlet') ? 'active' : '' }}" href="{{route('admin.outlet_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['outlet_report'] ?? 'Outlet Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('workstation_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/workstation') ? 'active' : '' }}" href="{{route('admin.workstation_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['workstation_report'] ?? 'Workstation Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('workstation_summary_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/workstation-note') ? 'active' : '' }}" href="{{route('admin.workstationnote_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['workstationnote_report'] ?? 'Workstation Summary Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('outstanding_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/settlement') ? 'active' : '' }}" href="{{route('admin.settlement_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['settlement_report'] ?? 'Outstanding Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('stock_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/stock') ? 'active' : '' }}" href="{{route('admin.stock_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['stock_report'] ?? 'Stock Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('rewash_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/rewash') ? 'active' : '' }}" href="{{route('admin.rewash_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['rewash_report'] ?? 'Rewash Report'}} </span>
                                    </a>
                                </li>
                            @endif
                            @if(user_has_permission('service_report'))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::is('admin/reports/service') ? 'active' : '' }}" href="{{route('admin.service_report')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['service_report'] ?? 'Service Report'}} </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(user_has_permission('financial_year') || user_has_permission('mail_settings') || user_has_permission('master_settings') || user_has_permission('file_tools') || user_has_permission('sms_settings') || user_has_permission('membership') || user_has_permission('manage_user') || user_has_permission('manage_outlet') || user_has_permission('manage_workstation') || user_has_permission('manage_brand') || user_has_permission('manage_voucher') || user_has_permission('manage_delivery') || user_has_permission('manage_promotion'))
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#settings" class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}" aria-controls="settings" role="button" aria-expanded="false">
                        <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                            <i class="ni ni-settings-gear-65 text-orange text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{$lang->data['tools'] ?? 'Tools'}}</span>
                    </a>
                    <div class="collapse {{ Request::is('admin/settings*') ? 'show' : '' }}" id="settings">
                        <ul class="nav ms-4">
                            @if(user_has_permission('financial_year'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/financial-year') ? 'active' : '' }}" href="{{route('admin.financial_year_settings')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> F </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['financial_year'] ?? 'Financial Year'}} </span>
                                    </a>
                                </li>
                            @endif

                            <!-- <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/translations') ? 'active' : '' }}" href="{{route('admin.translations')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['translations'] ?? 'Translations'}} </span>
                                    </a>
                                </li> -->

                            @if(user_has_permission('mail_settings'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/mail') ? 'active' : '' }}" href="{{route('admin.mail_settings')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> T </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['mail_settings'] ?? 'Mail Settings'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('master_settings'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/master') ? 'active' : '' }}" href="{{route('admin.master_settings')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> M </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['master_settings'] ?? 'Master Settings'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('file_tools'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/file-tools') ? 'active' : '' }}" href="{{route('admin.filetools')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> F </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['file_tools'] ?? 'File Tools'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('sms_settings'))
                                <li class="nav-item">
                                    <a class="nav-link  {{ Request::is('admin/settings/sms') ? 'active' : '' }}" href="{{route('admin.sms')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> SMS </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['sms_settings'] ?? 'SMS Settings'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('membership'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/membership') ? 'active' : '' }}" href="{{route('admin.membership')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Membership </span>
                                        <span class="sidenav-normal side-bar-inner"> {{$lang->data['membership'] ?? 'Membership'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_user'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/staff') ? 'active' : '' }}" href="{{route('admin.staff')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Staff </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['User'] ?? 'User'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_outlet'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/outlet') ? 'active' : '' }}" href="{{route('admin.outlet')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Outlet </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['Outlet'] ?? 'Outlet'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_workstation'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/workstation') ? 'active' : '' }}" href="{{route('admin.workstation')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Workstation </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['Workstation'] ?? 'Workstation'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_brand'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/brand') ? 'active' : '' }}" href="{{route('admin.brand')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Brand </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['Brand'] ?? 'Brand'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_voucher'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/voucher') ? 'active' : '' }}" href="{{route('admin.voucher')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Voucher </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['Voucher'] ?? 'Voucher'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_delivery'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/delivery-master') ? 'active' : '' }}" href="{{route('admin.delivery_master')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Delivery </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['Delivery Master'] ?? 'Delivery Master'}} </span>
                                    </a>
                                </li>
                            @endif

                            @if(user_has_permission('manage_promotion'))
                                <li class="nav-item ">
                                    <a class="nav-link  {{ Request::is('admin/settings/promotion') ? 'active' : '' }}" href="{{route('admin.promotion')}}">
                                        <span class="sidenav-mini-icon side-bar-inner"> Promotion </span>
                                        <span class="sidenav-normal side-bar-inner">{{$lang->data['promotion'] ?? 'Promotion'}} </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" wire:click.prevent="logout" href="#">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-button-power text-secondary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{$lang->data['logout'] ?? 'Logout'}}</span>
                </a>
            </li>
        </ul>
    </div>
    <hr class="horizontal dark mt-2">
</aside>