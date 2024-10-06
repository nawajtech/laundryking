<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Delivery Master</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addcategory" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add Delivery Type
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control"
                                placeholder="{{ $lang->data['search_here'] ?? 'Search here' }}" wire:model="search">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2"> Delivery Name</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2"> Rate Type</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2"> Amount</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2"> Cutoff Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-xs opacity-7">
                                        {{ $lang->data['status'] ?? 'Status' }}</th>
                                    
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliverytypes as $key => $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ ++$key }} </p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->delivery_name }}</p>
                                            <p style="font-size: 11px; margin-bottom: 0px;"><b>DELIVERY IN-</b> {{ $row->delivery_in_days }} DAYS</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->type }}</p>
                                        </td>
                                        <td >
                                            <p class="text-sm font-weight-bold mb-0">{{ getCurrency() }}{{ $row->amount }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ getCurrency() }}{{ $row->cut_off_amount }}</p>
                                        </td>

                                          <td class="">
                                        <div class="form-check form-switch" wire:click="toggle({{$row->id}})">
                                            <input class="form-check-input" type="checkbox" id="active" @if($row->is_active == 1) checked @endif>
                                            <label class="form-check-label" for="active">&nbsp;</label>
                                        </div>
                                    </td>
                                     
                                        <td>
                                            <a data-bs-toggle="modal" wire:click="edit({{ $row->id }})"
                                                data-bs-target="#editcategory" type="button"
                                                class="badge badge-xs badge-warning fw-600 text-xs">
                                                {{ $lang->data['edit'] ?? 'Edit' }}
                                            </a>

                                            <a data-bs-toggle="modal" data-bs-target="#deleteoutlet" type="button" wire:click="deleteID({{ $row->id }})"
                                                class="ms-2 badge badge-xs badge-danger text-xs fw-600">
                                                {{ $lang->data['delete'] ?? 'Delete' }}
                                            </a>
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

    <div wire:ignore.self class="modal fade" class="modal fade " id="addcategory" tabindex="-1" role="dialog"
        aria-labelledby="addcategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                       Add Delivery Type
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['delivery_name'] ?? 'Delivery Name' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_delivery_name'] ?? 'Enter Delivery Name' }}"
                                    wire:model="delivery_name">
                                @error('delivery_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                             <div class="col-md-6">
                                    <label class="form-label">{{$lang->data['type'] ?? 'Type'}}<span class="text-danger">*</span></label>
                                    <select class="form-control" required wire:model="ratetype">
                                        <option value="0">Select Rate Type</option>
                                        <option value="Flat">Flat</option>
                                        <option value="Percentage">Percentage</option>
                                    </select>
                                    @error('ratetype') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['rate'] ?? 'Rate' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_rate'] ?? 'Enter Rate' }}"
                                    wire:model="rate">
                                @error('rate')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                              <div class="col-md-4">
                                <label class="form-label">{{ $lang->data['cutoff amount'] ?? 'Cutoff Amount' }}
                                    <span class="text-danger"></span></label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_cutoff_amount'] ?? 'Enter Cutoff Amount' }}"
                                    wire:model="cutoffamount">
                                @error('cutoffamount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ $lang->data['cutoff charge'] ?? 'Cutoff Charge' }}
                                    <span class="text-danger"></span></label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_cutoff_charge'] ?? 'Enter Cutoff Charge' }}"
                                    wire:model="cutoffcharge">
                                @error('cutoffcharge')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="col-md-4">
                                <label class="form-label">{{ $lang->data['delivery_in_days'] ?? 'Delivery In Days' }}
                                    </label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_delivery_in_days'] ?? 'Enter Delivery In Days' }}"
                                    wire:model="delivery_day">
                                @error('delivery_day')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['pickup_time_from'] ?? 'Pickup Time From' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_pickup_time_from'] ?? 'Enter Pickup Time From' }}"
                                    wire:model="pickuptimefrom">
                                @error('pickuptimefrom')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['pickup_time_to'] ?? 'Pickup Time To' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_pickup_time_to'] ?? 'Enter Pickup Time To' }}"
                                    wire:model="pickuptimeto">
                                @error('pickuptimeto')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['delivery_time_from'] ?? 'Delivery Time From' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_delivery_time_from'] ?? 'Enter Delivery Time From' }}"
                                    wire:model="deliverytimefrom">
                                @error('deliverytimefrom')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['delivery_time_to'] ?? 'Delivery Time To' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_delivery_time_to'] ?? 'Enter Delivery Time To' }}"
                                    wire:model="deliverytimeto">
                                @error('deliverytimeto')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                          
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="store()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" class="modal fade " id="editcategory" tabindex="-1" role="dialog"
        aria-labelledby="editcategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Edit Delivery Type</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['delivery_name'] ?? 'Delivery Name' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_delivery_name'] ?? 'Enter Delivery Name' }}"
                                    wire:model="delivery_name">
                                @error('delivery_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                             <div class="col-md-6">
                                    <label class="form-label">{{$lang->data['type'] ?? 'Type'}}<span class="text-danger">*</span></label>
                                    <select class="form-control" required wire:model="ratetype">
                                        <option value="0">Select Rate Type</option>
                                        <option value="Flat">Flat</option>
                                        <option value="Percentage">Percentage</option>
                                    </select>
                                    @error('ratetype') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['rate'] ?? 'Rate' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_rate'] ?? 'Enter Rate' }}"
                                    wire:model="rate">
                                @error('rate')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                              <div class="col-md-4">
                                <label class="form-label">{{ $lang->data['cutoff amount'] ?? 'Cutoff Amount' }}
                                    <span class="text-danger"></span></label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_cutoff_amount'] ?? 'Enter Cutoff Amount' }}"
                                    wire:model="cutoffamount">
                                @error('cutoffamount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ $lang->data['cutoff charge'] ?? 'Cutoff Charge' }}
                                    <span class="text-danger"></span></label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_cutoff_charge'] ?? 'Enter Cutoff Charge' }}"
                                    wire:model="cutoffcharge">
                                @error('cutoffcharge')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="col-md-4">
                                <label class="form-label">{{ $lang->data['delivery in days'] ?? 'Delivery In Days' }}
                                    <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_days'] ?? 'Enter Delivery In Days' }}"
                                    wire:model="delivery_day">
                                @error('delivery_day')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['pickup_time_from'] ?? 'Pickup Time From' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_rate'] ?? 'Enter Pickup Time From' }}"
                                    wire:model="pickuptimefrom">
                                @error('pickuptimefrom')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['pickup_time_to'] ?? 'Pickup Time To' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_rate'] ?? 'Enter Pickup Time To' }}"
                                    wire:model="pickuptimeto">
                                @error('pickuptimeto')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['delivery_time_from'] ?? 'Delivery Time From' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_rate'] ?? 'Enter Delivery Time From' }}"
                                    wire:model="deliverytimefrom">
                                @error('deliverytimefrom')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['delivery_time_to'] ?? 'Delivery Time To' }}
                                    </label>
                                <input type="time" required class="form-control"
                                    placeholder="{{ $lang->data['enter_rate'] ?? 'Enter Delivery Time To' }}"
                                    wire:model="deliverytimeto">
                                @error('deliverytimeto')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                          
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="update()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <div wire:ignore.self class="modal fade" class="modal fade " id="deleteoutlet" tabindex="-1" role="dialog"
        aria-labelledby="editcategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Delete Delivery Type</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary"
                        wire:click.prevent="delete()">{{ $lang->data['delete'] ?? 'Delete' }}</button>
                </div>
                
            </div>
        </div>
    </div>  

</div>