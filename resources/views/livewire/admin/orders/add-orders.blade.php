<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['add_order'] ?? 'Add Order' }}</h5>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.view_orders') }}" class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-arrow-left me-2"></i> {{ $lang->data['back'] ?? 'Back' }}
            </a>
        </div>
    </div>
    <div class="row match-height">
        <div class="col-lg-6 col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button style="margin-bottom: 5px !important;" class="btn btn-{{ $service_category_id ? 'warning' : 'primary' }} btn-sm mb-0" type="button" wire:click="showservice(null)">All</button>
                            @foreach ($categories as $servicecate)
                                <button style="margin-bottom: 5px !important;" class="btn btn-{{ $service_category_id == $servicecate->id ? 'primary' : 'warning' }} btn-sm mb-0" type="button" wire:click="showservice({{$servicecate->id}})">{{ $servicecate->service_category_name }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search_query">
                        </div>
                    </div>
                </div>
                <div class="pos-card-wrapper-scroll-y my-custom-scrollbar-pos-card  mb-3">
                    <div class="row align-items-center g-3 px-4 ">
                        @foreach ($services as $item)
                            <div class="col-lg-3 col-6 text-center">
                                <div class="border-dashed border-1 border-secondary border-radius-md py-2 position-relative">
                                    @if($item->information!='')
                                        <i class="fa fa-info-circle end-3 position-absolute top-3" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $item->information }}"></i>
                                    @endif
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#servicetype" wire:click="selectService({{ $item->id }})">
                                        <div class="avatar avatar-xl mb-2">
                                            <img src="{{ asset('assets/img/service-icons/' . $item->icon) }}" class="rounded p-2">
                                        </div>
                                        <p class="text-xs font-weight-bold mb-0"><i class="bi bi-info-circle"></i></p>
                                        <p class="text-xs font-weight-bold mb-0">{{ $item->service_name }} <?php if($item->size !='') { ?> ({{$item->size}} in feet) <?php } ?></p>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <div class="input_overlay_btn">
                                <input type="text" wire:model="customer_query" class="form-control" placeholder="@if (!$selected_customer) {{ $lang->data['select_a_customer'] ?? 'Select A Customer' }} @else {{ $selected_customer->name }} @endif">
                                @if ($selected_customer)
                                    <button type="button" class="btn btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#viewdetails" style="padding: 10px 15px;">
                                        <i class="fa fa-eye me-2"></i> {{ $lang->data['view'] ?? 'View' }}
                                    </button>
                                @endif
                            </div>
                            @if ($customers && count($customers) > 0)
                                <ul class="list-group customhover">
                                    @foreach ($customers as $row)
                                        <li class="list-group-item customhover2" wire:click="selectCustomer({{ $row->id }})">{{ $row->name }} <br><small style="font-size: 13px;"><i class="fa fa-phone me-2"></i> {{$row->phone}}</small><br>@if($row->email != '')<small style="font-size: 13px;"><i class="fa fa-envelope me-2"></i> {{$row->email}}</small><br>@endif @if($row->address != '')<small style="font-size: 13px;"><i class="fa fa-map-marker me-2"></i> {{$row->address ?? 'Not provided'}}</small>@endif</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="col-md-3 mb-3">
                            <button type="button" class="btn btn-primary mb-0 w-100" data-bs-toggle="modal" data-bs-target="#addcustomer" style="display:block; margin:0px auto;">
                                <i class="fa fa-plus me-2"></i> {{ $lang->data['add'] ?? 'Add' }}
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" required class="form-control" readonly value="{{ $order_id }}">
                        </div>
                        <div class="col-md-6">
                            <input type="date" min="<?php echo date("Y-m-d"); ?>" class="form-control" wire:model="date">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-light align-items-center mb-3">
                            <thead class="">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['service'] ?? 'Service' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['brand'] ?? 'Brand' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['color'] ?? 'Color' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['addon'] ?? 'Addon' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['rate'] ?? 'Rate' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['qty'] ?? 'QTY' }}
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="order-list-wrapper-scroll-y my-custom-scrollbar-order-list">
                        <div class="row align-items-center g-3 px-4 ">
                            @foreach ($selservices as $key => $item)
                                <div class="col-lg-12 col-12">
                                    <div class="row ms-2 align-items-center">
                                        <div class="col-2" style="padding:0px;">
                                            <h6 class="text-xs h6 mb-0">
                                                @php
                                                    $serviceinline = null;
                                                    if (isset($item['service'])) {
                                                        $serviceinline = \App\Models\Service::where('is_active',1)->where('id', $item['service'])->first();
                                                    }
                                                    if (isset($item['service_type'])) {
                                                        $servicetypeinline = \App\Models\ServiceType::where('is_active',1)->where('id', $item['service_type'])->first();
                                                    }
                                                @endphp
                                                {{ $serviceinline->service_name }}
                                            </h6>
                                            <p class="text-xxs fw-600 text-primary mb-0"><?php if($serviceinline->size !='') { ?> ({{$serviceinline->size}} in feet) <?php } ?></p>
                                            <span class="text-xxs fw-600 text-primary">[{{ $servicetypeinline->service_type_name }}]</span>
                                        </div>

                                        <div class="col-2" style="padding-left: 0px;">
                                            <a data-bs-toggle="modal" wire:click="assignBrand({{ $key }})" data-bs-target="#brand" type="button" class="badge badge-xs badge-warning fw-600 text-xs">
                                                @if(isset($selected_brands[$key]))
                                                    @php $selected_brand_name = \App\Models\Brand::where('id',$selected_brands[$key])->first() @endphp
                                                    @if($selected_brand_name)
                                                        <i class="fa fa-check-circle text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $selected_brand_name->brand_name }}"></i>
                                                    @endif
                                                @else + @endif Brand
                                            </a>
                                        </div>

                                        <div class="col-2 text-center" style="padding-left: 0px;">
                                            <input class="form-control" type="color"  pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"  wire:model="colors.{{ $key }}" wire:change="changeColor({{$key}})" style="">
                                        </div>

                                        <div class="col-2 text-center" style="padding:0px;">
                                            @if($item['service_addon'] && count($item['service_addon']) > 0)
                                                <a data-bs-toggle="modal" wire:click="assignAddon({{ $key }})" data-bs-target="#addon" type="button" class="badge badge-xs badge-warning fw-600 text-xs">
                                                    @if(isset($selected_addons[$key]))
                                                        @php
                                                            $selected_addon = array_keys(array_filter($selected_addons[$key]));
                                                            $selected_addon = \App\Models\Addon::where('is_active',1)->whereIn('id', $selected_addon)->pluck('addon_name')->toArray();
                                                        @endphp
                                                        @if($selected_addon && count($selected_addon) > 0)
                                                            <i class="fa fa-check-circle text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ implode(', ', $selected_addon) }}"></i>
                                                        @endif
                                                    @else + @endif Addons
                                                </a>
                                            @endif
                                        </div>

                                        <div class="col-2" style="padding-right: 0px;">
                                            <input type="number" class="form-control form-control-sm text-center" wire:model="prices.{{ $key }}" min="1" oninput="validity.valid||(value='');">
                                        </div>

                                        <div class="col-2" style="padding-right:0px;">
                                            <div class="input-group align-items-center">
                                                <div class="badge bg-secondary text-xxs text-center p-66" type="button" wire:click="decrease({{ $key }})"><i class="fa fa-minus"></i></div>
                                                <input type="number" class="form-control form-control-sm text-center" wire:model="quantity.{{ $key }}" min="1" oninput="validity.valid||(value='');">
                                                <div class="badge bg-primary text-xxs text-center p-66" type="button" wire:click="increase({{ $key }})"><i class="fa fa-plus"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row align-items-center px-4 mb-3">
                    @if($selected_customer)
                        <div class="col">
                            <p class="text-sm mb-0 fw-500">{{ $lang->data['gross_total'] ?? 'Gross Total' }}</p>
                            <p class="text-sm text-success fw-600 mb-0">{{ getCurrency() }}
                                {{ number_format($sub_total, 2) }}
                            </p>
                        </div>
                        <div class="col-auto">
                            <button type="button" wire:click="clearAll" class="btn btn-danger me-2 mb-0">{{ $lang->data['clear_all'] ?? 'Clear All' }}</button>
                            <button type="submit" class="btn btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#payment">{{ $lang->data['save_continue'] ?? 'Save and Continue' }}</button>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            You need to select customer for new order.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="servicetype" tabindex="-1" role="dialog" aria-labelledby="servicetype" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="servicetype">
                        {{ $lang->data['select_service_type'] ?? 'Select Service Type' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center" x-data="{servtypes : @entangle('service_types'),seltype : @entangle('selected_type')}">
                            <template x-for="item in servtypes">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" :id="'test'+item.id" name="test" :value="item.id" x-model="seltype">
                                    <label class="form-check-label" :for="'test'+item.id"></label>
                                    <span x-text="item.service_type_name"> </span> - {{ getCurrency() }}<span x-text="item.service_price"></span>
                                </div>
                            </template>
                            @error('service_error') <span class="text-danger"> {{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="addItem">{{ $lang->data['add'] ?? 'Add' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addcustomer" tabindex="-1" role="dialog" aria-labelledby="addcustomer" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document" style="max-width:700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addcustomer">
                        {{ $lang->data['add_customer'] ?? 'Add Customer' }}
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
                                <label class="form-label">{{ $lang->data['customer_name'] ?? 'Customer Name' }} <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}" wire:model="customer_name">
                                @error('customer_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-1">
                                <label class="form-label">{{ $lang->data['dob'] ?? 'DOB' }}</label>
                                <input type="date" required class="form-control" wire:model="dob" id="dt">
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
                                <label class="form-label">{{ $lang->data['phone_number'] ?? 'Phone Number' }} <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_phone_number'] ?? 'Enter Phone Number' }}" wire:model="phone">
                                @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">{{ $lang->data['email'] ?? 'Email' }}</label>
                                <input type="text" class="form-control" placeholder="{{ $lang->data['enter_email'] ?? 'Enter Email' }}" wire:model="email">
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">{{ $lang->data['tax_number'] ?? 'Tax Number' }}</label>
                                <input type="text" class="form-control" placeholder="{{ $lang->data['enter_tax_number'] ?? 'Enter Tax Number' }}" wire:model="tax_no">
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">{{ $lang->data['gst_number'] ?? 'GST Number' }}</label>
                                <input type="text" class="form-control" placeholder="{{ $lang->data['enter_gst_number'] ?? 'Enter GST Number' }}" wire:model="gst">
                                @error('gst')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if($gst)
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ $lang->data['company_name'] ?? 'Company Name' }}</label>
                                    <input type="text" class="form-control" placeholder="{{ $lang->data['enter_company_name'] ?? 'Enter Company Name' }}" wire:model="company_name">
                                    @error('company_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-1">
                                    <label class="form-label">{{ $lang->data['company_address'] ?? 'Company Address' }}</label>
                                    <input type="text" class="form-control" placeholder="{{ $lang->data['enter_company_address'] ?? 'Enter Company Address' }}" wire:model="company_address">
                                    @error('company_address')
                                    <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['locality'] ?? 'Locality' }} </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_locality'] ?? 'Enter Locality' }}" wire:model="locality">
                                @error('locality')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-1">
                                <label class="form-label">{{ $lang->data['pincode'] ?? 'Pincode' }} </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_pincode'] ?? 'Enter Pincode' }}" wire:model="pin">
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
                                <textarea type="text" class="form-control" placeholder="{{ $lang->data['enter_address'] ?? 'Enter Address' }}" wire:model="address"></textarea>
                            </div>

                            <div class="col-md-12 mb-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="employee" checked wire:model="is_active">
                                    <label class="form-check-label" for="employee">{{ $lang->data['is_active'] ?? 'Is Active' }} ?</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="button" class="btn btn-primary" wire:click.prevent="createCustomer()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="payment" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document" style="max-width:850px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="payment">
                        {{ $lang->data['payment_details'] ?? 'Payment Details' }}
                        <?php if($membershipimg != ''){ ?> <img src="{{ asset('uploads/membership/' . $membershipimg) }}" class="login-logo" style="position: absolute; right: -25px;top: -25px; width: 75px !important;"><?php } ?>
                    </h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class=" col-12">
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label">{{ $lang->data['pickup_option'] ?? 'Pickup Option' }}</label>
                                        <select class="form-control" wire:model="pickupoption">
                                            <option value="">Choose Pickup Option </option>
                                            <option value="1">In Store Pickup </option>
                                            <option value="2">Home Pickup </option>
                                        </select>
                                        @error('pickupoption')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <button style="width:auto; margin-top: -10px; float:right" type="button" class="btn btn-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#addcustomeraddress" style="display:block; margin:0px auto;">
                                            <i class="fa fa-plus me-2"></i> {{ $lang->data['add_address'] ?? 'Add Address' }}
                                        </button>
                                        <label class="form-label">{{ $lang->data['delivery_option'] ?? 'Delivery Option' }}</label>
                                        <select class="form-control" wire:model="deliveryoption">
                                            <option value=""> Choose Delivery Option </option>
                                            <option value="1"> In Store Delivery </option>
                                            <option value="2"> Home Delivery </option>
                                        </select>
                                        @error('deliveryoption')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <?php if($selected_customer && $pickupoption==2){
                                            $getaddressbook = \App\Models\CustomerAddresses::where('customer_id', $selected_customer->id)->get();
                                        ?>
                                        <label class="form-label">{{ $lang->data['pickup_address'] ?? 'Pickup Address' }}</label>
                                        <select class="form-control" wire:model="pickupaddress" style="white-space: pre-wrap;">
                                            <option value="0">Choose Pickup Address</option>
                                            @foreach($getaddressbook as $pickupadd)
                                                <option value="{{ $pickupadd->id }}">{{ $pickupadd->flat_number }},{{ $pickupadd->area }}, {{ $pickupadd->address }} pin-{{ $pickupadd->pincode }}</option>
                                            @endforeach
                                        </select>
                                        @error('pickupaddress')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                        <?php }?>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <?php if($selected_customer && $deliveryoption==2){
                                            $getaddressbook = \App\Models\CustomerAddresses::where('customer_id', $selected_customer->id)->get();
                                        ?>
                                        <label class="form-label">{{ $lang->data['delivery_address'] ?? 'Delivery Address' }}</label>
                                        <select class="form-control" wire:model="deliveryaddress" style="white-space: pre-wrap;">
                                            <option value="0">Choose Delivery Address</option>
                                            @foreach($getaddressbook as $deliveryadd)
                                                <option value="{{ $deliveryadd->id }}">{{ $deliveryadd->flat_number }},{{ $deliveryadd->area }}, {{ $deliveryadd->address }} pin-{{ $deliveryadd->pincode }}</option>
                                            @endforeach
                                        </select>
                                        @error('deliveryaddress')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label">{{ $lang->data['assign_pickup_outlet'] ?? 'Assign Pickup Outlet' }}</label>
                                        <select class="form-control" wire:model="outlet">
                                            @if(Auth::user()->user_type==1)
                                                <option value="0">Choose Outlet</option>
                                                @foreach($assignoutlet as $outlets)
                                                    <option value="{{ $outlets->id }}">{{ $outlets->outlet_name }}</option>
                                                @endforeach
                                            @endif

                                            @if(Auth::user()->user_type == 2 && $pickupoption==1)
                                                <?php $getoutletname = \App\Models\Outlet::where('id', $getoutlet->outlet_id)->first(); ?>
                                                <option value="{{$getoutletname->id}}">{{ $getoutletname->outlet_name }}</option>
                                            @endif

                                            @if(Auth::user()->user_type==2 && $deliveryoption==2)
                                                <option value="0">Choose Outlet</option>
                                                @foreach($assigndeliveryoutlet as $outletss)
                                                    <option value="{{ $outletss->id }}">{{ $outletss->outlet_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('outlet')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label">{{ $lang->data['assign_delivery_outlet'] ?? 'Assign Delivery Outlet' }}</label>
                                        <select class="form-control" wire:model="delivery_outlet">
                                            @if(Auth::user()->user_type==1)
                                                <option value="0">Choose Outlet</option>
                                                @foreach($assigndeliveryoutlet as $outletss)
                                                    <option value="{{ $outletss->id }}">{{ $outletss->outlet_name }}</option>
                                                @endforeach
                                            @endif

                                            @if(Auth::user()->user_type==2 && $deliveryoption==1)
                                                <?php  $getoutletname = \App\Models\Outlet::where('id', $getoutlet->outlet_id)->first(); ?>
                                                <option value="{{$getoutletname->id}}">{{ $getoutletname->outlet_name }}</option>
                                            @endif

                                            @if(Auth::user()->user_type==2 && $deliveryoption==2)
                                                <option value="0">Choose Outlet</option>
                                                @foreach($assigndeliveryoutlet as $outletss)
                                                    <option value="{{ $outletss->id }}">{{ $outletss->outlet_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('delivery_outlet')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label">{{ $lang->data['delivery_type'] ?? 'Delivery Type' }}</label>
                                        <select class="form-control" wire:model="delivery">
                                            <option value="0">Choose Type</option>
                                            @foreach($showdeliverytype as $deliverytype)
                                                <option value="{{ $deliverytype->id }}">{{ $deliverytype->delivery_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('delivery')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="form-label">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}</label>
                                        <input type="date" min="{{ $date }}" class="form-control" wire:model="delivery_date">
                                    </div>
                                    <div class="col-md-3 mb-1">
                                        <label class="form-label">{{ $lang->data['exppress_charge'] ?? 'Express Charge' }}</label>
                                        <input readonly type="number" class="form-control" wire:model="expresschrge">
                                    </div>
                                    <div class="col-md-3 mb-1">
                                        <label class="form-label">{{ $lang->data['delivery_amount'] ?? 'Delivery Amount' }}</label>
                                        <input readonly type="number" class="form-control" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}" wire:model="deliverychrgamnt">
                                    </div>

                                    @if($cashback == '')
                                        <div class="col-md-2 mb-1">
                                            <label class="form-label">{{ $lang->data['discount'] ?? 'Discount' }} ( % )</label>
                                            <input type="text" id="discount" class="form-control" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" wire:model="discnt">
                                        </div>
                                    @endif

                                    @if($cashback)
                                        <div class="col-md-2 mb-1">
                                            <label class="form-label">{{ $lang->data['cashback'] ?? 'Cashback' }} ( % )</label>
                                            <input readonly type="number" class="form-control" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}" wire:model="cashback">
                                        </div>
                                    @endif

                                    <div class="col-md-4 mb-1">
                                        <label class="form-label">{{ $lang->data['voucher'] ?? 'Voucher' }}</label>
                                        <input type="text" class="form-control" placeholder="{{ $lang->data['enter_voucher'] ?? 'Enter Voucher' }}" wire:model="voucher">
                                        @error('voucher')
                                            <span class="error text-danger">{{ $message }}</span>
                                        @enderror
                                        @error('voucherfound')
                                            <span class="error text-success">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['sub_total'] ?? 'Sub Total' }}:</div>
                                    <div class="col-auto  text-sm fw-500">{{ getCurrency() }}
                                        {{ number_format($sub_total, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['addon'] ?? 'Addon' }}:</div>
                                    <div class="col-auto text-sm fw-500">{{ getCurrency() }}
                                        {{ number_format($addon_total, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['exppress_charge'] ?? 'Express Charge' }}:</div>
                                    <div class="col-auto text-sm fw-500">(+) {{ getCurrency() }}
                                        {{ number_format($expresschrge, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['discount'] ?? 'Discount' }}:</div>
                                    <div class="col-auto  text-sm fw-500">(-) {{ getCurrency() }}
                                        {{ number_format($getdiscount, 2) }}
                                    </div>
                                </div>
                                @if($cashback)
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['cashback'] ?? 'Cashback' }}:</div>
                                        <div class="col-auto  text-sm fw-500">(<span style="color:green;">Cashback</span>) {{ getCurrency() }}
                                            {{ number_format($getcashback, 2) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['voucher'] ?? 'Voucher' }}:</div>
                                    <div class="col-auto  text-sm fw-500">(-) {{ getCurrency() }}
                                        <?php if($vouamnt != 0) { ?> {{ number_format($vouamnt, 2) }} <?php } else { echo "0.00"; } ?>
                                    </div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['delivery_charge'] ?? 'Delivery Charge' }}:</div>
                                    <div class="col-auto text-sm fw-500">(+) {{ getCurrency() }}
                                        {{ number_format($deliverychrgamnt, 2) }}
                                    </div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['cgst'] ?? 'CGST' }}:
                                        ({{ $cgst_percentage }}%)
                                    </div>
                                    <div class="col-auto text-sm fw-500">(+) {{ getCurrency() }}{{ number_format($cgst, 2) }}</div>
                                </div>
                                <div class="row mb-50 align-items-center">
                                    <div class="col text-sm fw-500">{{ $lang->data['sgst'] ?? 'SGST' }}:
                                        ({{ $sgst_percentage }}%)
                                    </div>
                                    <div class="col-auto text-sm fw-500">(+) {{ getCurrency() }}{{ number_format($sgst, 2) }}</div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col text-sm fw-600">{{ $lang->data['gross_total'] ?? 'Gross Total' }}:</div>
                                    <div class="col-auto text-sm fw-600">{{ getCurrency() }}{{ number_format($total, 2) }}</div>
                                </div>
                                <hr>
                                <div class="row ">
                                    <div class="col-md-4 mb-1 pr-0">
                                        <label class="form-label">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}</label>
                                        <input type="number" class="form-control" placeholder="{{ $lang->data['enter_amount'] ?? 'Enter Amount' }}" wire:model="paid_amount" min="0" oninput="validity.valid||(value='');">
                                    </div>
                                    <div class="col-md-1 m-0 p-0">
                                        <label for="" class="form-label"> &nbsp; </label>
                                        <button style="margin-top: 33px;" class="btn btn-icon btn-2 btn-primary " type="button" wire:click="magicFill">
                                            <span class="btn-inner--icon px-0 mx-0"><i class="fa fa-magic m-0 p-0"></i></span>
                                        </button>
                                    </div>
                                    <?php if($selected_customer){
                                        $wallet_rcv_amount = \App\Models\Wallet::where('customer_id', $selected_customer->id)->sum('receive_amount');
                                        $wallet_deduct_amount = \App\Models\Wallet::where('customer_id', $selected_customer->id)->sum('deducted_amount');
                                        $wallet_amount = ($wallet_rcv_amount - $wallet_deduct_amount) ?? 0;
                                        ?>

                                        <div class="col-6 mx-2 mb-1 " >
                                            <label class="form-label">{{ $lang->data['payment_type'] ?? 'Payment Type' }}</label>
                                            <select class="form-select" wire:model="payment_type">
                                                <option value="">{{ $lang->data['choose_payment_mode'] ?? 'Choose Payment Mode' }}</option>
                                                <option class="select-box" value="1">{{ $lang->data['cash'] ?? 'Cash' }}</option>
                                                <option class="select-box" value="2">{{ $lang->data['upi'] ?? 'UPI' }}</option>
                                                <option class="select-box" value="3">{{ $lang->data['card'] ?? 'Card' }}</option>
                                                <option class="select-box" value="4">{{ $lang->data['cheque'] ?? 'Cheque' }}</option>
                                                <option class="select-box" value="5">{{ $lang->data['bank_transfer'] ?? 'Bank Transfer' }}</option>
                                                <option class="select-box" value="6">{{ $lang->data['lk_credit'] ?? 'LK Credit '.getCurrency() .$wallet_amount}}</option>
                                            </select>
                                        </div>
                                    <?php }?>
                                    @error('paid_amount')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('payment_type')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col text-sm fw-600">{{ $lang->data['balance'] ?? 'Balance' }}:</div>
                                    <div class="col-auto text-sm fw-600">{{ getCurrency() }}{{ number_format($balance, 2) }}</div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <label class="form-label">{{ $lang->data['notes_remarks'] ?? 'Notes / Remarks' }}</label>
                                    <textarea class="form-control" placeholder="{{ $lang->data['enter_notes'] ?? 'Enter Notes' }}" wire:model="payment_notes"></textarea>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <label class="form-label">{{ $lang->data['special_instruction'] ?? 'Special Instruction' }}</label>
                                    <textarea class="form-control" placeholder="Enter Notes" wire:model="instruction"></textarea>
                                </div>
                                @error('error')
                                <div class="col-12 mt-2">
                                    <div class="alert alert-danger" role="alert">
                                        <strong class="text-white">
                                            <span class="mx-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
                                            {{$message}}
                                        </strong>
                                    </div>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="save">{{ $lang->data['save_print'] ?? 'Save & Print' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addon" tabindex="-1" role="dialog" aria-labelledby="addon" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Add Addons
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            @if($selservices && isset($selservices[$addonKey]) && isset($selservices[$addonKey]['service_addon']))
                                @foreach ($selservices[$addonKey]['service_addon'] as $rows)
                                    @php
                                        $row = \App\Models\Addon::where('id', $rows['addon_id'])->where('is_active', 1)->first();
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="addon" id="addon{{ $row->id }}" wire:model="selected_addons.{{ $addonKey }}.{{ $row->id }}">
                                            <label class="custom-control-label" for="addon{{ $row->id }}">{{ $row->addon_name }} - {{ getCurrency() }}{{ number_format($row->addon_price,2) }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['close'] ?? 'Close' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="brand" tabindex="-1" role="dialog" aria-labelledby="brand" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Add Brand
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-12">
                                <div class="">
                                    <label class="custom-control-label" for="">Choose Brand</label>
                                    <select class="form-control" wire:model="selected_brands.{{ $brandKey }}">
                                        <option>Select Brand</option>
                                        @foreach ($brands as $row)
                                            <option value="{{ $row->id }}">{{ $row->brand_name }}</option>
                                        @endforeach
                                        <option value="Others">Others</option>
                                    </select>
                                    <br>
                                    @if($selected_brands && $brandKey)
                                        @if(isset($selected_brands[$brandKey]) && $selected_brands[$brandKey] == 'Others')
                                            <input type="text" class="form-control" wire:model="otherbrand.{{ $brandKey }}">
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['close'] ?? 'Close' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewdetails" tabindex="-1" role="dialog" aria-labelledby="viewdetails" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:950px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        View User History
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php if($selected_customer){ ?>
                <div class="container" style="padding-top: 10px;">
                    <div class="mb-2">
                        <div class="row">
                            <div class="col-auto">
                                <strong class="text-dark" style="margin-right:20px;">{{ $selected_customer->name }}</strong>
                            </div>
                            <div class="col-auto text-dark" style="font-size:14px;">
                                <span><strong>Order Amount- {{ getCurrency() }}{{ number_format($totalorderamount,2) }}</strong> </span>
                            </div>
                            <div class="col-auto text-dark" style="font-size:14px;">
                                <span><strong>Received Amount- {{ getCurrency() }}{{ number_format($totalorderrecvd,2) }}</strong> </span>
                            </div>
                            <div class="col-auto text-dark" style="font-size:14px;">
                                <span><strong>Outstanding Amount- {{ getCurrency() }}{{ number_format($totalorderoutstanding,2) }}</strong> </span>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="text-xs">
                                <i class="fa fa-phone me-2"></i> {{$selected_customer->phone}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="text-xs">
                                <i class="fa fa-envelope me-2"></i>@if($selected_customer->email != '') {{$selected_customer->email}}@else Not Provided @endif
                            </div>
                        </div>
                        @if($selected_customer->address)
                            <div class="col-auto">
                                <div class="text-xs">
                                    <i class="fa fa-map-marker me-2"></i> {{$selected_customer->address}}
                                </div>
                            </div>
                        @endif
                        <div class="col-auto">
                            <div class="text-xs">
                                <i class="fa fa-birthday-cake me-2"></i> @if($selected_customer->dob != ''){{ date("d-M-Y", strtotime($selected_customer->dob)) }}@else Not Provided @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="badge badge-pill <?php if($selected_customer->rating >= 2 && $selected_customer->rating < 4){ ?>badge-warning<?php }elseif($selected_customer->rating >= 4){ ?> badge-success<?php } else{ ?> badge-danger <?php } ?>">Rating {{$selected_customer->rating}} <i class="fa fa-star"></i></span>
                        </div>
                    </div>
                </div>
                <?php }?>

                <form>
                    <div class="modal-body">
                        <div class="row g-2 align-items-center">
                            @if($selected_customer)
                                @if($orderhistory)
                                    <table class="table align-items-center mb-0">
                                        <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs opacity-7">
                                                {{ $lang->data['order_info'] ?? 'Order Info' }}
                                            </th>
                                            <th class="text-uppercase text-secondary text-xs  opacity-7">
                                                {{ $lang->data['order_amount'] ?? 'Order Amount' }}
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xs opacity-7">
                                                {{ $lang->data['status'] ?? 'Status' }}
                                            </th>
                                            <th class="text-uppercase text-secondary text-xs opacity-7">
                                                {{ $lang->data['payment'] ?? 'Payment' }}
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orderhistory as $item)
                                                <tr @if($item->status == 10) style="background:rgb(255, 30, 30,.1);" @endif>
                                                    <td>
                                                        <p class="text-sm px-3 mb-0">
                                                            <span class="me-2">{{ $lang->data['order_id'] ?? 'Order ID' }}:</span>
                                                            <span class="font-weight-bold">{{ $item->order_number }}</span>
                                                        </p>
                                                        <p class="text-sm px-3 mb-0">
                                                            <span class="me-2">{{ $lang->data['order_date'] ?? 'Order Date' }}:</span>
                                                            <span class="font-weight-bold">{{ \Carbon\Carbon::parse($item->order_date)->format('d/m/y') }}</span>
                                                        </p>
                                                        <p class="text-sm px-3 mb-0">
                                                            <span class="me-2">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:</span>
                                                            <span class="font-weight-bold">{{ \Carbon\Carbon::parse($item->delivery_date)->format('d/m/y') }}</span>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm px-3 font-weight-bold mb-0">{{ getCurrency() }}
                                                            {{ number_format($item->total, 2) }}
                                                        </p>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        @if ($item->status == 0)
                                                            <a type="button" class="badge badge-sm bg-secondary text-uppercase">{{ $lang->data['pending'] ?? 'Pending' }}</a>
                                                        @elseif($item->status == 1)
                                                            <a type="button" class="badge badge-sm text-uppercase" style="background:#83ce2d;">{{ $lang->data['confirm'] ?? 'Confirm' }}</a>
                                                        @elseif($item->status == 2)
                                                            <a type="button" class="badge badge-sm bg-primary text-uppercase">{{ $lang->data['picked_up'] ?? 'Picked Up' }}</a>
                                                        @elseif($item->status == 3)
                                                            <a type="button" class="badge badge-sm text-uppercase" style="background:#FF597B;">{{ $lang->data['to_be_processed'] ?? 'To be Processed' }}</a>
                                                        @elseif($item->status == 4)
                                                            <a type="button" class="badge badge-sm bg-info text-uppercase">{{ $lang->data['in_transit'] ?? 'In Transit' }}</a>
                                                        @elseif($item->status == 5)
                                                            <a type="button" class="badge badge-sm bg-light text-uppercase" style="color:#000 !important;">{{ $lang->data['processing'] ?? 'Processing' }}</a>
                                                        @elseif($item->status == 6)
                                                            <a type="button" class="badge badge-sm bg-dark text-uppercase">{{ $lang->data['sent_to_store'] ?? 'Sent to Store' }}</a>
                                                        @elseif($item->status == 7)
                                                            <a type="button" class="badge badge-sm bg-warning text-uppercase">{{ $lang->data['ready'] ?? 'Ready' }}</a>
                                                        @elseif($item->status == 8)
                                                            <a type="button" class="badge badge-sm bg-success text-uppercase" style="background:#8b38b2 !important;">{{ $lang->data['out_for_delivery'] ?? 'Out for Delivery' }}</a>
                                                        @elseif($item->status == 9)
                                                            <a type="button" class="badge badge-sm bg-success text-uppercase">{{ $lang->data['delivered'] ?? 'Delivered' }}</a>
                                                        @elseif($item->status == 10)
                                                            <a type="button" class="badge badge-sm text-uppercase" style="background:#FF1E1E;">{{ $lang->data['cancel'] ?? 'Cancel' }}</a>
                                                        @endif
                                                    </td>
                                                    <td class="px-3">
                                                        @php
                                                            $paidamount = \App\Models\Payment::where('order_id', $item->id)->sum('received_amount');
                                                        @endphp
                                                        <p class="text-sm mb-0">
                                                            <span class="me-2">{{ $lang->data['total_amount'] ?? 'Total Amount' }}:</span>
                                                            <span class="font-weight-bold">{{ getCurrency() }}
                                                                {{ number_format($item->total, 2) }}
                                                        </span>
                                                        </p>
                                                        <p class="text-sm mb-1">
                                                            <span class="me-2">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}:</span>
                                                            <span class="font-weight-bold">{{ getCurrency() }}
                                                                {{ number_format($paidamount, 2) }}
                                                        </span>
                                                        </p>
                                                        @if ($paidamount < $item->total)
                                                            @if($item->status != 4)
                                                                <a data-bs-toggle="modal" data-bs-target="#addpayment" wire:click="payment({{ $item->id }})" type="button" class="badge badge-xs badge-success text-xs fw-600">
                                                                    {{ $lang->data['add_payment'] ?? 'Add Payment' }}
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a data-bs-toggle="modal" type="button" class="badge badge-xs badge-dark text-xs fw-600">
                                                                {{ $lang->data['fully_paid'] ?? 'Fully Paid' }}
                                                            </a>
                                                        @endif

                                                        <a target="_blank" href="{{ route('admin.view_single_order', $item->id) }}" type="button" class="badge badge-xs badge-primary text-xs fw-600">
                                                            {{ $lang->data['view'] ?? 'View' }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Sorry!</strong> No data found.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-labelledby="addpayment" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addpayment">
                        {{ $lang->data['payment_details'] ?? 'Payment Details' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    @if ($order)
                        <div class="modal-body">
                            <div class="row g-2 align-items-center">
                                <div class=" col-12">
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['payment_details'] ?? 'Payment Details' }}:</div>
                                        <div class="col-auto text-sm fw-500">{{ $customer_name }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['order_id'] ?? 'Order ID' }}:</div>
                                        <div class="col-auto text-sm fw-500">{{ $order->order_number }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['order_date'] ?? 'Order Detail' }}:</div>
                                        <div class="col-auto  text-sm fw-500">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:</div>
                                        <div class="col-auto  text-sm fw-500">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</div>
                                    </div>
                                    <hr>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['order_amount'] ?? 'Order Amount' }}:</div>
                                        <div class="col-auto  text-sm fw-500">{{ getCurrency() }}{{ number_format($order->total, 2) }}</div>
                                    </div>
                                    <div class="row mb-50 align-items-center">
                                        <div class="col text-sm fw-500">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}:</div>
                                        <div class="col-auto text-sm fw-500">{{ getCurrency() }}{{ number_format($paid_amount, 2) }}</div>
                                    </div>
                                    <hr>
                                    <div class="row align-items-center">
                                        <div class="col text-sm fw-600">{{ $lang->data['balance'] ?? 'Balance' }}:</div>
                                        <div class="col-auto text-sm fw-600">{{ getCurrency() }}{{ number_format($order->total - $paid_amount, 2) }}</div>
                                    </div>
                                    <hr>
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">{{ $lang->data['paid_amount'] ?? 'Paid Amount' }}</label>
                                            <input type="number" class="form-control" placeholder="Enter Amount" wire:model="orderbalance">
                                            @error('orderbalance')
                                            <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">{{ $lang->data['payment_type'] ?? 'Payment Type' }}</label>
                                            <select class="form-select" wire:model="payment_mode">
                                                <option value="">{{ $lang->data['choose_payment_type'] ?? 'Choose Payment Type' }}</option>
                                                <option class="select-box" value="1">{{ $lang->data['cash'] ?? 'Cash' }}</option>
                                                <option class="select-box" value="2">{{ $lang->data['upi'] ?? 'UPI' }}</option>
                                                <option class="select-box" value="3">{{ $lang->data['card'] ?? 'Card' }}</option>
                                                <option class="select-box" value="4">{{ $lang->data['cheque'] ?? 'Cheque' }}</option>
                                                <option class="select-box" value="5">{{ $lang->data['bank_transfer'] ?? 'Bank Transfer' }}</option>
                                            </select>
                                            @error('payment_mode')
                                            <span class="error text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <label class="form-label">{{ $lang->data['notes_remarks'] ?? 'Notes / Remarks' }}</label>
                                        <textarea class="form-control" placeholder="Enter Notes" wire:model="note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="addPayment()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addcustomeraddress" tabindex="-1" role="dialog" aria-labelledby="addcustomeraddress" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document" style="max-width:750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Add Addressbook
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['customer_name'] ?? 'Customer Name' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_customer_name'] ?? 'Enter Customer Name' }}" wire:model="custname" readonly>
                                @error('custname')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror

                                <input type="hidden" wire:model="custid">
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
                                                    <input type="text" class="form-control" wire:model="custpincode" placeholder="Enter Pincode">
                                                    @error('custpincode') <span class="text-danger error">{{ $message }}</span>@enderror
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
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

    <script>
        "use strict";
        Livewire.on('printPage', orderId => {
            var $id = orderId;
            window.open(
                '{{ url('admin/orders/print-order/') }}' + '/' + $id,
                '_blank'
            );
            window.onfocus = function () { setTimeout(function () { window.location.reload(); }, 100); }
        })

        window.livewire.on('tooltipHydrate', () => {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</div>