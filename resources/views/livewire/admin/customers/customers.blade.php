<div>
    <div class="row mb-2">
           <div class="col">
            <div class="card p-3">
                <h6 class="fw-500 text-dark">{{ $lang->data['customers'] ?? 'Total Customers' }} <br><h3>{{ $total_customer }}</h3></h6>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h6 class="fw-500 text-dark">{{ $lang->data['customers'] ?? 'Active Customers' }} <br><h3>{{ $active_customer }}</h3></h6>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h6 class="fw-500 text-dark">{{ $lang->data['customers'] ?? 'Inactive Customers' }} <br><h3>{{ $inactive_customer }}</h3></h6>
            </div>
        </div>
        <div class="col">
            <div class="card p-3">
                <h6 class="fw-500 text-dark">{{ $lang->data['customers'] ?? 'New Customers' }} <br><h3>{{ $new_customer }}</h3></h6>
            </div>
        </div>
    </div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['customers'] ?? 'Customers' }}</h5>
        </div>
     
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addcustomer" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> {{ $lang->data['add_new_customer'] ?? 'Add New Customer' }}
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
                                placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="height:500px; overflow: scroll;">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ $lang->data['customer_name'] ?? 'Customer Details' }}
                                    </th>
                                    
                                    <th class="text-uppercase text-secondary text-xs  opacity-7">
                                        {{ $lang->data['assign membership'] ?? 'Assign Membership' }}
                                    </th>
                                  
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ $lang->data['order_history'] ?? 'Order History' }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ $lang->data['store'] ?? 'Store' }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        {{ $lang->data['status'] ?? 'Status' }}
                                    </th>       
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($customers as $row)
                                    <?php 
                                    $store_id = \App\Models\Order::where('customer_id', $row->id)->groupBy('outlet_id')->get();
                                    $totalorderamount = \App\Models\Order::where('customer_id', $row->id)->sum('total');
                                    $totalorderrecvd = \App\Models\Payment::where('customer_id', $row->id)->sum('received_amount');
                                    $wallet_rcv_amount = \App\Models\Wallet::where('customer_id', $row->id)->sum('receive_amount');
                                    $wallet_deduct_amount = \App\Models\Wallet::where('customer_id', $row->id)->sum('deducted_amount');
                                     $wallet_amount = $wallet_rcv_amount - $wallet_deduct_amount;
                                    //membership tier check
                                    $totalorderoutstanding = ($totalorderamount - $totalorderrecvd);

                                    $query = \App\Models\Membership::query();
                                    $query->where(function ($query) use ($totalorderrecvd) {
                                        $query->where('min_price', '<=', $totalorderrecvd);
                                        $query->where('max_price', '>=', $totalorderrecvd);
                                    });
                                    $membership_check = $query->first();

                                    if($membership_check){
                                        $membership = $membership_check->membership_name ?? '';
                                    }else{
                                        $membership = "";
                                    }
                                    
                                    ?>
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $i++ }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0"><i class="fa fa-user text-warning text-sm opacity-10" style="margin-right: 5px;"></i> {{ $row->salutation }} {{ $row->name }}</p>
                                            <small><i class="fa fa-phone text-warning text-sm opacity-10" style="margin-right: 5px;"></i> {{ $row->phone }}</small><br>
                                           @if($row->email)<small><i class="fa fa-envelope-o text-warning text-sm opacity-10" style="margin-right: 5px;"></i> {{ $row->email }}</small><br>@endif

                                           @if($row->refer_code != '') <span class="badge badge-pill  badge-success ">Refer Code -  {{ $row->refer_code }} </span> @endif
                                         
                                           <br>{{-- @if($membership)<span class="badge badge-pill  badge-danger"> {{ $membership }} </span>@endif --}}

                                            <p style="width: 200px; white-space: pre-wrap;" class="text-sm mb-2">{{ $row->address }}</p>
                                            <p>
                                                <a data-bs-toggle="modal" wire:click="editadbook({{ $row->id }})"
                                                    data-bs-target="#editadbook" type="button"
                                                    class="badge badge-xs badge-warning fw-600 text-xs">
                                                    {{ $lang->data['address_book'] ?? 'Address Book' }}
                                                </a>
                                            </p>
                                        </td>
                                       
                                        @php
                                        $membership = \App\Models\Membership::where('id',$row->membership)->first();
                                        @endphp
                                        
                                        <td>
                                       
                                        @if(user_has_permission('financial_year'))
                                        <a data-bs-toggle="modal" wire:click="membershipinsert({{ $row->id }})" data-bs-target="#assignmember" type="button" class="badge badge-xs badge-warning fw-600 text-xs">
                                             @if($membership)
                                            {{ $membership->membership_name }}
                                             @else
                                             {{ $lang->data['assign_membership'] ?? 'Assign Membership' }}
                                              @endif
                                        </a>
                                        @endif
                                        
                                        
                                           
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;" class="mb-0">Total Order Amount - <b>{{ getCurrency() }}{{number_format($totalorderamount,2)}}</b></p>
                                            <p style="font-size: 14px;" class="mb-0">Outstanding Amount - <b>{{ getCurrency() }}{{number_format($totalorderoutstanding,2)}}</b></p>
                                            <p style="font-size: 14px;" class="mb-0">Wallet Amount - <b>{{ getCurrency() }}{{number_format($wallet_amount,2)}}</b></p>
                                        </td>
                                        <td>
                                        @foreach ($store_id as $s)
                                            @php
                                                $count = '';
                                                $store_name = \App\Models\Outlet::where('id',$s->outlet_id)->first();
                                                if($store_name){
                                                $count = \App\Models\Order::where('outlet_id',$store_name->id)->count();
                                                }
                                            @endphp
                                            <span class="badge rounded-pill bg-dark">{{ $store_name->outlet_name ?? '' }} - {{ $count }}</span>
                                            <br>
                                        @endforeach
                                        </td>
                                        <td>
                                            <div class="form-check form-switch" wire:click="toggle({{$row->id}})">
                                                <input class="form-check-input" type="checkbox" id="active" @if($row->is_active == 1) checked @endif>
                                                <label class="form-check-label" for="active">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>
                                            <a data-bs-toggle="modal" data-bs-target="#editcustomer"
                                                wire:click="edit({{ $row->id }})" type="button"
                                                class="badge badge-xs badge-warning fw-600 text-xs">
                                                {{ $lang->data['edit'] ?? 'Edit' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($hasMorePages)
                        <div
                            x-data="{
                                init () {
                                    let observer = new IntersectionObserver((entries) => {
                                        entries.forEach(entry => {
                                            if (entry.isIntersecting) {
                                                @this.call('loadCustomers')
                                                console.log('loading...')
                                            }
                                        })
                                    }, {
                                        root: null
                                    });
                                    observer.observe(this.$el);
                                }
                            }"
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4"
                        >
                           <div class="text-center pb-2 d-flex justify-content-center align-items-center">
                               Loading...
                               <div class="spinner-grow d-inline-flex mx-2 text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                              </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" class="modal fade " id="editadbook" tabindex="-1" role="dialog"
        aria-labelledby="editadbook" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width:750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Add Addressbookdiscount
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['customer_name'] ?? 'Customer Name' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}"
                                    wire:model="name" readonly>
                                @error('name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Address Book</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $a=1;
                                            $addressbook = \App\Models\CustomerAddresses::where('customer_id',$customerid)->get();
                                        @endphp
                                        @foreach ($addressbook as $rowaddress)
                                            <tr>
                                                <td>
                                                    <p class="text-sm px-3 mb-0">{{ $a }} </p>
                                                </td>
                                                <td>
                                                    <p style="width: 300px; text-wrap: wrap;" class="text-sm font-weight-bold mb-0">{{ $rowaddress->flat_number }}, {{ $rowaddress->area }}<br>{{ $rowaddress->address }}<br>{{ $rowaddress->route_suggestion }}
                                                    </p>
                                                    <small>{{ $rowaddress->address_type }} - {{ $rowaddress->pincode }}</small>
                                                </td>
                                                
                                                <td class="align-middle text-center">
                                                    <a href="#" type="button" wire:click="editaddressbook({{ $rowaddress->id }})"
                                                        class="ms-2 badge badge-xs badge-danger text-xs fw-600">
                                                        {{ $lang->data['edit'] ?? 'Edit' }}
                                                    </a>

                                                    <a data-bs-toggle="modal" data-bs-target="#deleteaddressbook" type="button" wire:click="deleteId({{ $rowaddress->id }})"
                                                        class="ms-2 badge badge-xs badge-danger text-xs fw-600">
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
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" wire:model="flat" placeholder="Flat No.">
                                                    @error('flat') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" wire:model="area" placeholder="Enter Area">
                                                    @error('area') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" wire:model="addressnew" placeholder="Enter Address">
                                                    @error('address') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" wire:model="landmark" placeholder="Enter Route Suggestion">
                                                    @error('landmark') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" wire:model="latitude" placeholder="Enter Latitude">
                                                    @error('pincode') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" wire:model="longitude" placeholder="Enter Longitude">
                                                    @error('longitude') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control" wire:model="addtype">
                                                    <option value="">Choose Address Type</option>
                                                        <option value="Home">Home</option>
                                                        <option value="Office">Office</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    @error('addtype') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" wire:model="pincode" placeholder="Enter Pincode">
                                                    @error('pincode') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            @if($addtype == 'Other')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" wire:model="other">
                                                    @error('other') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click.prevent="closemodal()">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" wire:click.prevent="adbookstore()" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>   
    </div>

    <div wire:ignore.self class="modal fade " id="addcustomer" tabindex="-1" role="dialog"
        aria-labelledby="addcustomer" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width:700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addcustomer">{{ $lang->data['add_customer'] ?? 'Add Customer' }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-2 mb-1">
                                <label class="form-label">{{ $lang->data['salutation'] ?? 'Salutation' }} </label>
                                <select class="form-control" wire:model="salutation">
                                    <option value="">Choose</option>
                                    @if($salutations)
                                        @foreach($salutations as $key => $s)
                                            <option value="{{ $key }}">{{ $s }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('salutation')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-7 mb-1">
                                <label class="form-label">{{ $lang->data['customer_name'] ?? 'Customer Name' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}"
                                    wire:model="name">
                                @error('name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-1">
                                <label class="form-label">{{ $lang->data['dob'] ?? 'DOB' }}</label>
                                <input type="date" required class="form-control" wire:model="dob" id="dt" >
                                @error('dob')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-md-2 mb-1">
                                <label class="form-label">{{ $lang->data['Code'] ?? 'Code' }} </label>
                                <select class="form-control" wire:model="contrycode">
                                    <option class="select-box" value="">Select</option>
                                    @if($country_code)
                                    @foreach($country_code as $key => $c)
                                        <option value="{{ $c->phone_code }}">{{ $c->country_code }} ({{$c->phone_code }})</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('salutation')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}"
                                    wire:model="phone">
                                @error('phone')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">{{ $lang->data['email'] ?? 'Email' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}" wire:model="email">
                                @error('email')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">{{ $lang->data['tax_number'] ?? 'Tax Number' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_tax_number'] ?? 'Enter Tax Number' }}"
                                    wire:model="tax_number">
                                @error('tax_number')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">{{ $lang->data['gst_number'] ?? 'GST Number' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_gst_number'] ?? 'Enter GST Number' }}"
                                    wire:model="gst">
                                @error('gst')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($gst)
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['company_name'] ?? 'Company Name' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_company_name'] ?? 'Enter Company Name' }}"
                                    wire:model="company_name">
                                @error('company_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['company_address'] ?? 'Company Address' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_company_address'] ?? 'Enter Company Address' }}"
                                    wire:model="company_address">
                                @error('company_address')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['locality'] ?? 'Locality' }} </label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_locality'] ?? 'Enter Locality' }}"
                                    wire:model="locality">
                                @error('locality')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['pincode'] ?? 'Pincode' }} </label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_pincode'] ?? 'Enter Pincode' }}"
                                    wire:model="pin">
                                @error('pin')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['customer_rating'] ?? 'Customer Rating' }} </label>
                                <select class="form-control" wire:model="rating">
                                    <option value="">Choose Rating</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                @error('rating')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ $lang->data['address'] ?? 'Address' }}</label>
                                <textarea type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_address'] ?? 'Enter Address' }}"
                                    wire:model="address"></textarea>
                                @error('address')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="employee" checked
                                        wire:model="is_active">
                                    <label class="form-check-label"
                                        for="employee">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                                </div>
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

    <div class="modal fade" wire:ignore.self id="editcustomer" tabindex="-1" role="dialog"
        aria-labelledby="editcustomer" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width:750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="editcustomer">
                        {{ $lang->data['edit_customer'] ?? 'Edit Customer' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-2 mb-1">
                                <label class="form-label">{{ $lang->data['salutation'] ?? 'Salutation' }} </label>
                                <select class="form-control" wire:model="salutation">
                                    <option value="">Choose</option>
                                    @if($salutations)
                                        @foreach($salutations as $key => $s)
                                            <option value="{{ $key }}">{{ $s }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('salutation')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-7 mb-1">
                                <label class="form-label">{{ $lang->data['customer_name'] ?? 'Name' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}"
                                    wire:model="name">
                                @error('name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-1">
                                <label class="form-label">{{ $lang->data['dob'] ?? 'DOB' }}</label>
                                <input type="date" required class="form-control" wire:model="dob" id="dts">
                                @error('dob')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-2 mb-1">
                                <label class="form-label">{{ $lang->data['Code'] ?? 'Code' }} </label>
                                <select class="form-control" wire:model="contrycode">
                                    <option value="">Select</option>
                                    @if($country_code)
                                        @foreach($country_code as $key => $c)
                                            <option value="{{ $c->phone_code }}">{{ $c->country_code }} ({{$c->phone_code }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('salutation')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" required class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}"
                                    wire:model="phone">
                                @error('phone')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">{{ $lang->data['email'] ?? 'Email' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}" wire:model="email">
                                @error('email')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['gst_number'] ?? 'GST Number' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_gst_number'] ?? 'Enter GST Number' }}"
                                    wire:model="tax_number">
                                @error('tax_number')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if($tax_number)
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['company_name'] ?? 'Company Name' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_company_name'] ?? 'Enter Company Name' }}"
                                    wire:model="company_name">
                                @error('company_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1">
                                <label class="form-label">{{ $lang->data['company_address'] ?? 'Company Address' }}</label>
                                <input type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_company_address'] ?? 'Enter Company Address' }}"
                                    wire:model="company_address">
                                @error('company_address')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['locality'] ?? 'Locality' }} </label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_locality'] ?? 'Enter Locality' }}"
                                    wire:model="locality">
                                @error('locality')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['pincode'] ?? 'Pincode' }} </label>
                                <input type="number" required class="form-control"
                                    placeholder="{{ $lang->data['enter_pincode'] ?? 'Enter Pincode' }}"
                                    wire:model="pin">
                                @error('pin')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['customer_rating'] ?? 'Customer Rating' }} </label>
                                <select class="form-control" wire:model="rating">
                                    <option value="">Choose Rating</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                @error('rating')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ $lang->data['address'] ?? 'Address' }}</label>
                                <textarea type="text" class="form-control"
                                    placeholder="{{ $lang->data['enter_address'] ?? 'Enter Address' }}"
                                    wire:model="address"></textarea>
                                @error('address')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            
                            <div class="col-md-12 mb-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="employee" checked
                                        wire:model="is_active">
                                    <label class="form-check-label"
                                        for="employee">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary"
                            wire:click.prevent="update()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" class="modal fade " id="deleteaddressbook" tabindex="-1" role="dialog"
        aria-labelledby="deletebrand" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Delete Address Book</h6>
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

    <div wire:ignore.self class="modal fade" id="assignmember" tabindex="-1" role="dialog" aria-labelledby="assignmember" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Assign Membership</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['customer_name'] ?? 'Customer Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" readonly wire:model="name">
                                
                            </div>
                            

                            <div class=" add-input">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="form-control bg-dark text-white" wire:model="membrs">
                                            <option value="0">Choose Membership</option>
                                            @foreach($memberships as $ms)
                                                <option value="{{$ms->id}}"> {{ $ms->membership_name }} </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" wire:click.prevent="assignmember()" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>                       
</div>

