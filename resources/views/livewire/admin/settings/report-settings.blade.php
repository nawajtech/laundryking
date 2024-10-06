<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['report_setting'] ?? 'Report Settings' }}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <form class="row g-3 align-items-center">
                        <div><span
                            class="text-sm text-uppercase">{{ $lang->data['report_setting'] ?? 'Report Settings' }}</span>
                        </div>
                        <hr>

                        <table class="table table-bordered align-items-center mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['report_name'] ?? 'Report Name'}}</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['outlet_access'] ?? 'Outlet Access'}}</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['manager_access'] ?? 'Manager Access'}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $lang->data['daily_report'] ?? 'Daily Report' }}</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="daily_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="dailym_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $lang->data['order_report'] ?? 'Order Report' }}</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="order_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="orderm_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $lang->data['sales_report'] ?? 'Sales Report' }}</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="sales_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="salesm_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $lang->data['expense_report'] ?? 'Expense Report' }}</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="expense_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="expensem_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $lang->data['tax_report'] ?? 'Tax Report' }}</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="tax_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-3">
                                        <select class="form-select" wire:model="taxm_report">
                                            <option value="1">  Yes </option>
                                            <option value="2"> No </option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="col-md-3">
                                    <label class="form-label">{{ $lang->data['garment_report'] ?? 'Garment Report' }}</label>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="garment_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="garmentm_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                            </tr>
                        
                            <tr>
                                <td>
                                <div class="col-md-3">
                                    <label class="form-label">{{ $lang->data['customer_report'] ?? 'Customer Report' }}</label>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="customer_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="customerm_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="col-md-3">
                                    <label class="form-label">{{ $lang->data['outlet_report'] ?? 'Outlet Report' }}</label>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="outlet_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="outletm_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="col-md-3">
                                    <label class="form-label">{{ $lang->data['workstation_report'] ?? 'Workstation Report' }}</label>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="workstation_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="workstationm_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="col-md-3">
                                    <label class="form-label">{{ $lang->data['outstanding_report'] ?? 'Outstanding Report' }}</label>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="outstanding_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="outstandingm_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="col-md-3">
                                    <label class="form-label">{{ $lang->data['stock_report'] ?? 'Stock Report' }}</label>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="stock_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="stockm_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="col-md-3">
                                    <label class="form-label">{{ $lang->data['service_report'] ?? 'Service Report' }}</label>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="service_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class="col-md-3">
                                    <select class="form-select" wire:model="servicem_report">
                                        <option value="1">  Yes </option>
                                        <option value="2"> No </option>
                                    </select>
                                </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            <button type="submit" class="btn btn-primary ms-4"
                                wire:click.prevent="save()">{{ $lang->data['save'] ?? 'Save' }}</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>