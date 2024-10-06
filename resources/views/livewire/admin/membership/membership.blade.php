<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Membership</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addmembership" wire:click="resetInputFields" class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add New Membership
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="{{ $lang->data['search_here'] ?? 'Search here' }}" wire:model="search">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Membership Name</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Min Price</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Max Price</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Type</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Discount(%)</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Express Delivery Fee(%)</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Delivery Charge</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Icon</th>
                                <th class="text-secondary opacity-7"></th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" >Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($members as $key => $row)
                                <tr>
                                    <td>
                                        <p class="text-sm px-3 mb-0">{{ ++$key }} </p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->membership_name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->min_price }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->max_price }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">
                                            <?php if($row->discount_type == 1) { echo "Order Discount"; } elseif($row->discount_type == 2) { echo "Cashback"; } ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->discount }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->express_fee }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->delivery_fee == 1 ? 'Applicable' : "Not applicable"  }}</p>
                                    </td>
                                    <td class="text-center">
                                        <?php if($row->icon != ''){ ?> <img src="{{ asset('uploads/membership/' . $row->icon) }}" class="login-logo"><?php } else{ echo "<span style='color:#ff0000; font-size:12px;'>No image uploaded</span>"; } ?>
                                    </td>
                                    <td class="">
                                        <div class="form-check form-switch" wire:click="toggle({{$row->id}})">
                                            <input class="form-check-input" type="checkbox" id="active" @if($row->is_active == 1) checked @endif>
                                            <label class="form-check-label" for="active">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#editmembership" type="button" wire:click="edit({{ $row->id }})" class="badge badge-xs badge-warning fw-600 text-xs">
                                            {{ $lang->data['edit'] ?? 'Edit' }}
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

    <div wire:ignore.self class="modal fade" id="addmembership" tabindex="-1" role="dialog" aria-labelledby="addmembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Add Membership
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['membership_name'] ?? 'Membership Name' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['membership_name'] ?? 'Membership Name' }}" wire:model="membership_name">
                                @error('membership_name')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['min_price'] ?? 'Min Price' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['min_price'] ?? 'Min Price' }}" wire:model="min_price" min="1">
                                @error('min_price')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['max_price'] ?? 'Max Price' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['max_price'] ?? 'Max Price' }}" wire:model="max_price" min="1">
                                @error('max_price')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount_type'] ?? 'Discount Type' }}<span class="text-danger">*</span></label>
                                <select required class="form-control" wire:model="discounttype">
                                    <option value="">None</option>
                                    <option value="1">Order Discount</option>
                                    <option value="2">Cashback</option>
                                </select>
                                @error('discounttype')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount'] ?? 'Discount (%)' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['discount'] ?? 'Discount' }}" wire:model="discount" min="1">
                                @error('discount')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['express_fee'] ?? 'Express Discount (%)' }}<span class="text-danger"></span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['express_fee'] ?? 'Express Discount' }}" wire:model="express_fee" min="1">
                                @error('express_fee')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['delivery_price'] ?? 'Delivery Price' }}
                                    <span class="text-danger"></span></label>
                                <select class="form-select" wire:model="delivery_fee">
                                    <option value="">  {{ $lang->data['select_one'] ?? 'Select One' }} </option>
                                    <option value="1">  {{ $lang->data['applicable'] ?? 'Applicable' }} </option>
                                    <option value="2"> {{ $lang->data['not_applicable'] ?? 'Not Applicable' }} </option>
                                </select>
                                @error('delivery_fee')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['icon'] ?? 'Icon' }}<span class="text-danger"></span></label>
                                <input type="file" required class="form-control mt-2 image-file" wire:model="icon">
                                @error('icon')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="store()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="deletemembership" tabindex="-1" role="dialog" aria-labelledby="deletemembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">Delete Membership</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" >
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="delete()">{{ $lang->data['delete'] ?? 'Delete' }}</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editmembership" tabindex="-1" role="dialog" aria-labelledby="editmembership" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">Edit Membership</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['membership_name'] ?? 'Membership Name' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['membership_name'] ?? 'Membership Name' }}" wire:model="membership_name" min="1">
                                @error('membership_name')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['min_price'] ?? 'Min Price' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['min_price'] ?? 'Min Price' }}" wire:model="min_price" min="1">
                                @error('min_price')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['max_price'] ?? 'Max Price' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['max_price'] ?? 'Max Price' }}" wire:model="max_price" min="1">
                                @error('max_price')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount_type'] ?? 'Discount Type' }}<span class="text-danger">*</span></label>
                                <select required class="form-control" wire:model="discounttype">
                                    <option value="">None</option>
                                    <option value="1">Order Discount</option>
                                    <option value="2">Cashback</option>
                                </select>
                                @error('discounttype')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount'] ?? 'Discount' }}<span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['discount'] ?? 'Discount' }}" wire:model="discount" min="1">
                                @error('discount')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['express_fee'] ?? 'Express Discount' }}<span class="text-danger"></span></label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['express_fee'] ?? 'Express Discount' }}" wire:model="express_fee" min="1">
                                @error('express_fee')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['delivery_price'] ?? 'Delivery Price' }}<span class="text-danger"></span></label>
                                <select class="form-select" wire:model="delivery_fee">
                                    <option value="">  {{ $lang->data['select_one'] ?? 'Select One' }} </option>
                                    <option value="1">  {{ $lang->data['applicable'] ?? 'Applicable' }} </option>
                                    <option value="2"> {{ $lang->data['not_applicable'] ?? 'Not Applicable' }} </option>
                                </select>
                                @error('delivery_fee')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <input type="file" class="form-control mt-2 image-file" wire:model="icon">
                                @error('icon')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                                <?php if($showicon){ ?>
                                <img src="{{ asset('uploads/membership/' . $showicon) }}" class="login-logo">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="update()">{{ $lang->data['save'] ?? 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>