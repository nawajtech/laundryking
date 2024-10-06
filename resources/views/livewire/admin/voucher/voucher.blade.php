<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Voucher</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addvoucher" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add New Voucher
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
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Voucher Code
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Image</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">
                                        Discount Type
                                    </th>
                                    <th class="align-middle text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Discount Amount
                                    </th>
                                    <th class="align-middle text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Expiry Date
                                    </th>
                                    <th class="align-middle text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Cutoff Amount
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs opacity-7">
                                        {{ $lang->data['status'] ?? 'Status' }}
                                    </th>
                                    
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($vouchers) > 0)
                                @foreach ($vouchers as $key => $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ ++$key }} </p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->code }}</p>
                                        </td>
                                        <td>
                                             <p class="text-sm font-weight-bold mb-0">
                                               <?php if($row->image != ''){ ?> <img src="{{ asset('uploads/voucher/' . $row->image) }}" class="login-logo"><?php } else{ echo "<span style='color:#ff0000; font-size:12px;'>No image uploaded</span>"; } ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0"><?php if($row->discount_type == 1){ echo "Percentage Discount"; }else{ echo "Flat Discount"; } ?></p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-sm font-weight-bold mb-0" style="font-size: 12px;">{{ getCurrency() }} {{ $row->discount_amount }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-sm font-weight-bold mb-0" style="font-size: 12px;">{{ $row->valid_to }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-sm font-weight-bold mb-0" style="font-size: 12px;">{{ getCurrency() }} {{ $row->cutoff_amount }}</p>
                                        </td>
                                        <td class="">
                                            <div class="form-check form-switch" wire:click="toggle({{$row->id}})">
                                                <input class="form-check-input" type="checkbox" id="active" @if($row->is_active == 1) checked @endif>
                                                <label class="form-check-label" for="active">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>
                                            <a data-bs-toggle="modal" wire:click="edit({{ $row->id }})"
                                                data-bs-target="#editvoucher" type="button"
                                                class="badge badge-xs badge-warning fw-600 text-xs">
                                                {{ $lang->data['edit'] ?? 'Edit' }}
                                            </a>

                                            <a data-bs-toggle="modal" data-bs-target="#deletevoucher"  type="button" wire:click="deleteId({{ $row->id }})"
                                                class="ms-2 badge badge-xs badge-danger text-xs fw-600">
                                                {{ $lang->data['delete'] ?? 'Delete' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" style="text-align: center;">
                                            <?php echo "No data found"; ?>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" class="modal fade " id="addvoucher" tabindex="-1" role="dialog"
        aria-labelledby="addvoucher" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                       Add Voucher
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['voucher_code'] ?? 'Voucher Code' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" onkeypress="return event.charCode != 32" oninput="this.value = this.value.toUpperCase()" placeholder="{{ $lang->data['enter_voucher_code'] ?? 'Enter Voucher Code' }}"
                                    wire:model="code">
                                @error('code')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['no_of_users'] ?? 'No. Of Users Can Use' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['no_of_users'] ?? 'No of user can use' }}"
                                    wire:model="no_of_users" min="1">
                                @error('no_of_users')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['each_user_useable'] ?? 'Useable For Each User' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['each_user_useable'] ?? 'Each User Useable' }}"
                                    wire:model="each_user_useable" min="1">
                                @error('each_user_useable')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['membership'] ?? 'Membership' }}
                                <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="membership">
                                    <option value="0">  {{ $lang->data['all'] ?? 'All Member' }} </option>
                                    <option value="1">  {{ $lang->data['silver_member'] ?? 'Silver Member' }} </option>
                                    <option value="2"> {{ $lang->data['gold_member'] ?? 'Gold Member' }} </option>
                                    <option value="3"> {{ $lang->data['platinum_member'] ?? 'Platinum Member' }} </option>
                                    <option value="4"> {{ $lang->data['none'] ?? 'None' }} </option>
                                </select>
                                @error('delivery_fee')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                             <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['total_useable'] ?? 'Total Useable' }}
                                    <span class="text-danger">*</span></label>
                                <input disabled type="number" required class="form-control"
                                    placeholder="{{ $lang->data['total_useable'] ?? 'Total Useable' }}"
                                    wire:model="total_useable">
                                @error('total_useable')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount_type'] ?? 'Discount Type' }}
                                    <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="discount_type">
                                    <option value="">Choose Discount Type</option>
                                    <option value="1">Percentage Discount</option>
                                    <option value="2">Flat Discount</option> 
                                </select>
                                @error('discount_type')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount_amount'] ?? 'Discount Amount' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_discount_amount'] ?? 'Enter Discount Amount' }}"
                                    wire:model="discount_amount">
                                @error('discount_amount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['cutoff_amount'] ?? 'Cutoff Amount' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_cutoff_amount'] ?? 'Enter Cutoff Amount' }}"
                                    wire:model="cutoff_amount">
                                @error('cutoff_amount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                              <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['valid_from'] ?? 'Valid Form' }}
                                    <span class="text-danger">*</span></label>
                                <input type="date" required class="form-control"
                                    placeholder="{{ $lang->data['enter_latitude'] ?? '' }}"
                                    wire:model="valid_from">
                                @error('valid_from')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['valid_to'] ?? 'Valid To' }}
                                    <span class="text-danger">*</span></label>
                                <input type="date" required class="form-control"
                                    placeholder="{{ $lang->data['enter_longitude'] ?? '' }}"
                                    wire:model="valid_to">
                                @error('valid_to')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['details'] ?? 'Details' }}
                                    <span class="text-danger">*</span></label>
                                <textarea required class="form-control"
                                    placeholder="{{ $lang->data['enter_details'] ?? 'Enter Details' }}"
                                    wire:model="details"></textarea>
                                @error('details')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                            <input type="file" class="form-control mt-2 image-file" wire:model="photo">
                            @error('photo')
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
    <div wire:ignore.self class="modal fade" class="modal fade " id="editvoucher" tabindex="-1" role="dialog"
        aria-labelledby="editvoucher" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Edit Voucher</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['voucher_code'] ?? 'Voucher Code' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control" onkeypress="return event.charCode != 32" oninput="this.value = this.value.toUpperCase()" placeholder="{{ $lang->data['enter_voucher_code'] ?? 'Enter Voucher Code' }}"
                                    wire:model="code">
                                @error('code')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['no_of_users'] ?? 'No. Of Users Can Use' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['no_of_users'] ?? 'No of user can use' }}"
                                    wire:model="no_of_users" min="1">
                                @error('no_of_users')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['each_user_useable'] ?? 'Useable For Each User' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['each_user_useable'] ?? 'Each User Useable' }}"
                                    wire:model="each_user_useable" min="1">
                                @error('each_user_useable')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['membership'] ?? 'Membership' }}
                                <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="membership">
                                    <option value="0">  {{ $lang->data['all'] ?? 'All Member' }} </option>
                                    <option value="1">  {{ $lang->data['silver_member'] ?? 'Silver Member' }} </option>
                                    <option value="2"> {{ $lang->data['gold_member'] ?? 'Gold Member' }} </option>
                                    <option value="3"> {{ $lang->data['platinum_member'] ?? 'Platinum Member' }} </option>
                                </select>
                                @error('delivery_fee')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                             <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['total_useable'] ?? 'Total Useable' }}
                                    <span class="text-danger">*</span></label>
                                <input type="number" disabled required class="form-control"
                                    placeholder="{{ $lang->data['total_useable'] ?? 'Total Useable' }}"
                                    wire:model="total_useable">
                                @error('total_useable')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount_type'] ?? 'Discount Type' }}
                                    <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="discount_type">
                                    <option value="">Choose Discount Type</option>
                                    <option value="1">Percentage Discount</option>
                                    <option value="2">Flat Discount</option> 
                                </select>
                                @error('discount_type')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['discount_amount'] ?? 'Discount Amount' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_discount_amount'] ?? 'Enter Discount Amount' }}"
                                    wire:model="discount_amount">
                                @error('discount_amount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['cutoff_amount'] ?? 'Cutoff Amount' }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" required class="form-control"
                                    placeholder="{{ $lang->data['enter_cutoff_amount'] ?? 'Enter Cutoff Amount' }}"
                                    wire:model="cutoff_amount">
                                @error('cutoff_amount')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                              <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['valid_from'] ?? 'Valid Form' }}
                                    <span class="text-danger">*</span></label>
                                <input type="date" required class="form-control"
                                    placeholder="{{ $lang->data['enter_latitude'] ?? '' }}"
                                    wire:model="valid_from">
                                @error('valid_from')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['valid_to'] ?? 'Valid To' }}
                                    <span class="text-danger">*</span></label>
                                <input type="date" required class="form-control"
                                    placeholder="{{ $lang->data['enter_longitude'] ?? '' }}"
                                    wire:model="valid_to">
                                @error('valid_to')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['details'] ?? 'Details' }}
                                    <span class="text-danger">*</span></label>
                                <textarea required class="form-control"
                                    placeholder="{{ $lang->data['enter_details'] ?? 'Enter Details' }}"
                                    wire:model="details"></textarea>
                                @error('details')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                            <input type="file" class="form-control mt-2 image-file" wire:model="photo">
                            @error('photo')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                                <?php if($showphoto){ ?>
                                <img src="{{ asset('uploads/voucher/' . $showphoto) }}" class="login-logo">
                            <?php } ?>
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

    <div wire:ignore.self class="modal fade" class="modal fade " id="deletevoucher" tabindex="-1" role="dialog"
        aria-labelledby="editcategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Delete Voucher</h6>
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