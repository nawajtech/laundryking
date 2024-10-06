<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['User']??'User'}}</h5>
        </div>
        @if(user_has_permission('add_customer'))
            <div class="col-auto">
                <a wire:click="resetFields" data-bs-toggle="modal" data-bs-target="#addstaff" class="btn btn-icon btn-3 btn-white text-primary mb-0">
                    <i class="fa fa-plus me-2"></i> {{$lang->data['add_staff']??'Add New User'}}
                </a>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" wire:model="user_filter">
                                <option class="select-box" value="0">All User</option>
                                <option class="select-box" value="1">Admin/Sub Admin</option>
                                <option class="select-box" value="2">Outlet</option>
                                <option class="select-box" value="3">Floor Manager</option>
                                <option class="select-box" value="4">Driver</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['staff_name'] ?? 'Name'}}</th>
                                <th class="text-center text-uppercase text-secondary text-xs opacity-7">{{$lang->data['role'] ?? 'Role'}}</th>
                                <th class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['contact'] ?? 'Contact'}}</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">{{$lang->data['status'] ?? 'Status'}}</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($staffs as $item)
                                <tr>
                                    <td>
                                        <p class="text-sm px-3 mb-0">{{$loop->index+1}}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{$item->name}}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a type="button" class="badge badge-sm rounded-pill bg-dark text-uppercase">
                                            {{ getuserType($item->user_type, $item->is_subadmin) }}
                                        </a>
                                        <?php if($item->user_type == 2){?>
                                            <p style="font-size: 11px; margin-bottom: 0px;">{{ $item->outlet ? $item->outlet->outlet_name : "" }}</p>
                                        <?php } ?>
                                        <?php if($item->user_type == 3){?>
                                            <p style="font-size: 11px; margin-bottom: 0px;">{{ $item->workstation ? $item->workstation->workstation_name : "" }}</p>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <p class="text-sm px-3 mb-0">{{$item->phone}}</p>
                                        <p class="text-sm px-3 mb-0">{{$item->email}}</p>
                                    </td>
                                    <td class="">
                                        <div class="form-check form-switch" wire:click="toggle({{$item->id}})">
                                            <input class="form-check-input" type="checkbox" id="active" @if($item->is_active == 1) checked @endif>
                                            <label class="form-check-label" for="active">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->user_type == 4)
                                            <a data-bs-toggle="modal" wire:click="editoutlet({{$item->id}})" data-bs-target="#editoutlet" type="button" class="badge badge-xs badge-primary fw-600 text-xs">
                                                {{ $lang->data['assign_outlet'] ?? 'Assign Outlet' }}
                                            </a>
                                        @endif
                                        @if(user_has_permission('user_permission') && $access_module && $item->user_type)
                                            @if(isset($access_module[$item->user_type]) && count($access_module[$item->user_type]) > 0)
                                                <a data-bs-toggle="modal" wire:click="editaccess({{$item->id}})" data-bs-target="#editaccess" type="button" class="badge badge-xs badge-success fw-600 text-xs">
                                                    <i class="fa fa-key me-2"></i> {{ $lang->data['permission'] ?? 'Permission' }}
                                                </a>
                                            @endif
                                        @endif
                                        <a data-bs-toggle="modal" wire:click="view({{$item->id}})" data-bs-target="#editstaff" type="button" class="badge badge-xs badge-warning fw-600 text-xs">
                                            {{ $lang->data['edit'] ?? 'Edit' }}
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#deleteuser" wire:click="deleteID({{$item->id}})" type="button" class="ms-2 badge badge-xs badge-danger text-xs fw-600">
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

    <div class="modal fade " id="addstaff" tabindex="-1" role="dialog" aria-labelledby="addstaff" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addstaff">{{$lang->data['add_staff']??'Add New User'}}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{$lang->data['User Type'] ?? 'User Type'}}<span class="text-danger">*</span></label>
                                <select class="form-control" id="staff-usertype" required wire:model="role">
                                    <option value="">Select User Type</option>
                                    @if($roletype)
                                        @foreach($roletype as $key=>$r)
                                            <option value="{{$key}}">{{$r}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('name') <span class="text-danger">{{$message}}</span> @enderror
                            </div>

                            @if (!empty($outlet))
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{$lang->data['Select Outlet'] ?? 'Select Outlet'}}<span class="text-danger">*</span></label>
                                    <select class="form-control" required wire:model="outletid">
                                        <option value="0">Select Outlet</option>
                                        @foreach ($outlet as $row)
                                            <option value="{{ $row->id }}">{{ $row->outlet_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            @endif

                            @if (!empty($workstation))
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{$lang->data['Select Workstation'] ?? 'Select Workstation'}}<span class="text-danger">*</span></label>
                                    <select class="form-control" required wire:model="workstationid">
                                        <option value="0">Select Workstation</option>
                                        @foreach ($workstation as $row)
                                            <option value="{{ $row->id }}">{{ $row->workstation_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                </div>
                            @endif

                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{$lang->data['staff_name'] ?? 'Name'}}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{$lang->data['enter_staff_name'] ??'Enter Staff Name'}}" wire:model="name">
                                @error('name') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }} <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control" placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}" wire:model="phone">
                                @error('phone') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['email'] ?? 'Email' }}<span class="text-danger">*</span></label>
                                <input type="email" required class="form-control" placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}" wire:model="email">
                                @error('email') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{$lang->data['password']??'Password'}} <span class="text-danger">*</span></label>
                                <input type="password" required class="form-control" placeholder="{{$lang->data['enter_a_password']??'Enter a Password'}}" wire:model="password">
                                @error('password') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="employee_edit" wire:model="is_active">
                                    <label class="form-check-label" for="employee_edit">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="save">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade " id="editstaff" tabindex="-1" role="dialog" aria-labelledby="editstaff" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="editstaff">{{$lang->data['edit_staff']??'Edit Staff'}}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{$lang->data['staff_name'] ?? 'Staff Name'}}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{$lang->data['enter_staff_name'] ??'Enter Staff Name'}}" wire:model="name">
                                @error('name') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <?php if($usertype == 2){?>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{$lang->data['Select Outlet'] ?? 'Select Outlet'}}<span class="text-danger">*</span></label>
                                <select class="form-control" required wire:model="outletid">
                                    <option value="0">Select Outlet</option>
                                    @foreach ($showoutlet as $row)
                                        <option value="{{ $row->id }}">{{ $row->outlet_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <?php } ?>
                            <?php if($usertype == 3){?>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{$lang->data['Select Workstation'] ?? 'Select Workstation'}}<span class="text-danger">*</span></label>
                                <select class="form-control" required wire:model="workstationid">
                                    <option value="0">Select Workstation</option>
                                    @foreach ($showworkstation as $row)
                                        <option value="{{ $row->id }}">{{ $row->workstation_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <?php }?>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }} <span class="text-danger">*</span></label>
                                <input type="number" required class="form-control" placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}" wire:model="phone">
                                @error('phone') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['email'] ?? 'Email' }}<span class="text-danger">*</span></label>
                                <input type="email" required class="form-control" placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}" wire:model="email">
                                @error('email') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{$lang->data['password']??'Password'}} <span class="text-danger">*</span></label>
                                <input type="password" required class="form-control" placeholder="{{$lang->data['enter_a_password']??'Enter a Password'}}" wire:model="password">
                                @error('password') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="employee_add" wire:model="is_active">
                                    <label class="form-check-label" for="employee_add">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="update">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editoutlet" tabindex="-1" role="dialog" aria-labelledby="editcategory" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Assign Outlet</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['driver_name'] ?? 'Driver Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" disabled placeholder="{{ $lang->data['enter_outlet_name'] ?? 'Enter Driver Name' }}" wire:model="staff_name">
                                @error('staff_name')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">
                                            Outlet Name</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $a=1;

                                        $assignoutlet = \App\Models\OutletDriver::where('user_id', $staff_id)->get();

                                    @endphp

                                    @foreach($assignoutlet as $rowoutlet)
                                        @php
                                            $outlet = \App\Models\Outlet::where('id',$rowoutlet->outlet_id)->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <p class="text-sm px-3 mb-0">{{ $a }} </p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">{{ $outlet->outlet_name }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#" type="button" wire:click="deleteoutlet({{ $rowoutlet->id }})" class="ms-2 badge badge-xs badge-danger text-xs fw-600">
                                                    {{ $lang->data['delete'] ?? 'Delete' }}
                                                </a>
                                            </td>
                                        </tr>
                                        @php $a++;  @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class=" add-input">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <select class="form-control" wire:model="outletdriver.0">
                                                <option value="">Select Outlet</option>
                                                @foreach($showoutlet as $showoutlets)
                                                    <option value="{{ $showoutlets->id }}">{{ $showoutlets->outlet_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('outletdriver.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <button style="width: 100%; padding: 10px;" class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
                                    </div>
                                </div>
                            </div>

                            @foreach($inputs as $key => $value)
                                <div class=" add-input container">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <select class="form-control" wire:model="outletdriver.{{$value}}">
                                                    <option value="">Select Outlet</option>
                                                    @foreach($showoutlet as $showoutlets)
                                                        <option value="{{ $showoutlets->id }}">{{ $showoutlets->outlet_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('outletdriver.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <button style="width: 100%; padding: 10px 6px;" class="btn btn-danger btn-sm" wire:click.prevent="remove({{$key}})">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" wire:click.prevent="storeoutlet()" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editaccess" tabindex="-1" role="dialog" aria-labelledby="editcategory" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 800px;">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Role Module
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['name'] ?? 'Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" disabled placeholder="{{ $lang->data['enter_outlet_name'] ?? 'Sub Admin Name' }}" wire:model="staff_name">
                                @error('staff_name')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 align-items-center px-2 py-3">
                            @if($access_module && $usertype)
                                @if(isset($access_module[$usertype]) && count($access_module[$usertype]) > 0)
                                    @foreach($access_module[$usertype] as $keyam => $am)
                                        <div class="form-check col-md-4">
                                            <input class="form-check-input" wire:model="{{ $keyam }}" type="checkbox" id="inlineCheckbox{{ $loop->index + 1 }}">
                                            <label class="form-check-label" for="inlineCheckbox{{ $loop->index + 1 }}">{{ $am }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" wire:click.prevent="storeaccess()" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteuser" tabindex="-1" role="dialog" aria-labelledby="editcategory" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Delete User
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="delete()">{{ $lang->data['save'] ?? 'Delete' }}</button>
                </div>
            </div>
        </div>
    </div>

</div>


