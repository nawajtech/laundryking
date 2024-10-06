<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Service Category</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addcategory" wire:click="resetInputFields"
                class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add New Category
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
                                        Service Category</th>
                                    <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">
                                        Category Image</th>
                                    <th class="text-center text-uppercase text-secondary text-xs opacity-7">
                                        {{ $lang->data['status'] ?? 'Status' }}</th>
                                    
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($categories as $row)
                                    <tr>
                                        <td>
                                            <p class="text-sm px-3 mb-0">{{ $i++ }} </p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $row->service_category_name }}
                                            </p>
                                        </td>
                                         <td>
                                            <p class="text-sm font-weight-bold mb-0">
                                               <?php if($row->image != ''){ ?> <img src="{{ asset('uploads/category/' . $row->image) }}" class="login-logo"><?php } else{ echo "<span style='color:#ff0000; font-size:12px;'>No image uploaded</span>"; } ?>
                                            </p>
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
                                             <a data-bs-toggle="modal" data-bs-target="#deletecategory" type="button" wire:click="deleteId({{ $row->id }})"
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
                       Add Service Category
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['category_name'] ?? 'Category Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_category_name'] ?? 'Enter Category Name' }}" wire:model="service_category_name">
                                @error('service_category_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <input type="file" class="form-control mt-2 image-file" wire:model="photo" id="upload{{ $iteration }}">
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
    <div wire:ignore.self class="modal fade" class="modal fade " id="editcategory" tabindex="-1" role="dialog"
        aria-labelledby="editcategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Edit Service Category</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['category_name'] ?? 'Category Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_category_name'] ?? 'Enter Category Name' }}" wire:model="service_category_name">
                                @error('service_category_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <input type="file" class="form-control mt-2 image-file" wire:model="photo">
                                @error('photo')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                                <?php if($showphoto){ ?>
                                    <img src="{{ asset('uploads/category/' . $showphoto) }}" class="login-logo">
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

    <div wire:ignore.self class="modal fade" class="modal fade " id="deletecategory" tabindex="-1" role="dialog"
        aria-labelledby="deletecategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Delete Service Category</h6>
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