<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">Outlet</h5>
        </div>
        <div class="col-auto">
            <a data-bs-toggle="modal" data-bs-target="#addoutlet" wire:click="resetInputFields"
               class="btn btn-icon btn-3 btn-white text-primary mb-0">
                <i class="fa fa-plus me-2"></i> Add New Outlet
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
                    <div class="table-responsive" style="height: 500px; overflow: scroll;">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Outlet Name</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Outlet Code</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">UPI QR Code</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Address</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Pincode</th>
                                <th class="text-center text-uppercase text-secondary text-xs opacity-7">{{ $lang->data['status'] ?? 'Status' }}</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($outlets as $key => $row)
                                <tr>
                                    <td>
                                        <p class="text-sm px-3 mb-0">{{ ++$key }} </p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->outlet_name }}</p>
                                        <?php
                                        $workstation_show = App\Models\Workstation::where('id', $row->workstation_id)->first();
                                        if($row->workstation_id != 0){?>
                                        <p class="text-sm font-weight-bold mb-0" style="font-size: 12px;">WORKSTATION: <span class="text-success">{{ $workstation_show->workstation_name }}</span></p>
                                        <?php } else { ?>
                                        <p class="text-sm font-weight-bold mb-0" style="font-size: 12px; color:#ff0000;">No Workstation Added For This Outlet</p>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $row->outlet_code }}</p>
                                    </td>
                                    @if($row->qr_code == NULL)
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0"> Not Added </p>
                                        </td>
                                    @else
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0"> Added </p>
                                        </td>
                                    @endif
                                    <td>

                                    </td>
                                    <td class="align-middle text-center" style="max-width: 245px;">
                                       <div class="pincodes" style="display:grid; grid-template-columns: auto auto;">
                                        @php
                                            $pincode = \App\Models\Pincode::where('outlet_id',$row->id)->get();
                                        @endphp
                                        @foreach ($pincode as $rowpin)
                                        <span class="badge badge-sm bg-dark rounded-pill fw-500" style="margin-right: 2px; margin-bottom: 2px;">{{$rowpin->pincode}}</span>
                                        @endforeach
                                    </div>
                                    </td>
                                    <td class="">
                                        <div class="form-check form-switch" wire:click="toggle({{$row->id}})">
                                            <input class="form-check-input" type="checkbox" id="active" @if($row->is_active == 1) checked @endif>
                                            <label class="form-check-label" for="active">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <a data-bs-toggle="modal" wire:click="editpincode({{ $row->id }})" data-bs-target="#editpincode" type="button" class="badge badge-xs badge-warning fw-600 text-xs">
                                            {{ $lang->data['pincode'] ?? 'Pincode' }}
                                        </a>
                                        <a data-bs-toggle="modal" wire:click="edit({{ $row->id }})" data-bs-target="#editoutlet" type="button" class="badge badge-xs badge-warning fw-600 text-xs">
                                            {{ $lang->data['edit'] ?? 'Edit' }}
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#deleteoutlet" type="button" wire:click="deleteID({{ $row->id }})" class="ms-2 badge badge-xs badge-danger text-xs fw-600">
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

    <div wire:ignore.self class="modal fade" id="addoutlet" tabindex="-1" role="dialog" aria-labelledby="addoutlet" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Add Outlet
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_name'] ?? 'Outlet Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_outlet_name'] ?? 'Enter Outlet Name' }}" wire:model="outlet_name">
                                @error('outlet_name')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_code'] ?? 'Outlet Code' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_outlet_name'] ?? 'Enter Outlet code' }}" wire:model="outlet_code">
                                @error('outlet_code')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{$lang->data['Select Workstation'] ?? 'Select Workstation'}}
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" required wire:model="workstationid">
                                    <option value="0">Select Workstation</option>
                                    @foreach ($showworkstation as $row)
                                        <option value="{{ $row->id }}">{{ $row->workstation_name }}</option>
                                    @endforeach
                                </select>
                                @error('workstationid') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_address'] ?? 'Outlet Address' }}
                                    
                                </label>
                                <div style="display:flex;">
                                    <input type="text" style="height: 42px; margin-right: 5px;" class="form-control" placeholder="{{ $lang->data['enter_outlet_address'] ?? 'Enter Outlet Address' }}" wire:model="outlet_address">
                                    @error('outlet_address')
                                    <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                    <button class="btn btn-primary" wire:click.prevent="searchLocation()">Find</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_phone'] ?? 'Outlet Phone' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_outlet_phone'] ?? 'Enter Outlet phone' }}" wire:model="outlet_phone">
                                @error('outlet_phone')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['latitude'] ?? 'Latitude' }}
                                    
                                </label>
                                <input id="lat" type="text" required class="form-control" placeholder="{{ $lang->data['enter_latitude'] ?? 'Enter Latitude' }}" wire:model="outlet_latitude">
                                @error('outlet_latitude')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['longitude'] ?? 'Longitude' }}
                                    
                                </label>
                                <input id="long" type="text" required class="form-control" placeholder="{{ $lang->data['enter_longitude'] ?? 'Enter Longitude' }}" wire:model="outlet_longitude">
                                @error('outlet_longitude')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['upi_qr_code'] ?? 'UPI Qr Code' }}
                                    
                                </label>
                                <input type="file" required class="form-control" placeholder="{{ $lang->data['enter_qr_code'] ?? 'Enter Qr Code' }}" wire:model="qr_code">
                                @error('qr_code')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{--<div class="col-md-12">
                                <label class="form-label">{{ $lang->data['google_map'] ?? 'Google Map' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" wire:model="searchQuery">
                                <button wire:click="searchLocation">Search</button>


                            </div>--}}

                            <div>
                                <input wire:model="zoom" type="hidden" name="current_zoom" id="current_zoom">
                                <input wire:model="latlng" type="hidden" name="latlng" id="latlng">
                                <div wire:ignore id="map"></div>
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

    <div wire:ignore.self class="modal fade" id="editoutlet" tabindex="-1" role="dialog" aria-labelledby="editoutlet" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Edit Outlet</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_name'] ?? 'Outlet Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_outlet_name'] ?? 'Enter Outlet Name' }}" wire:model="outlet_name">
                                @error('outlet_name')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_code'] ?? 'Outlet Code' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_outlet_code'] ?? 'Enter Outlet code' }}" wire:model="outlet_code">
                                @error('outlet_code')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{$lang->data['Select Workstation'] ?? 'Select Workstation'}}
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" required wire:model="workstationid">
                                    <option value="0">Select Workstation</option>
                                    @foreach ($showworkstation as $row)
                                        <option value="{{ $row->id }}">{{ $row->workstation_name }}</option>
                                    @endforeach
                                </select>
                                @error('workstationid') <span class="text-danger">{{$message}}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_name'] ?? 'Outlet Address' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div style="display:flex;">
                                    <input type="text" style="height: 42px; margin-right: 5px;" required class="form-control" placeholder="{{ $lang->data['enter_outlet_address'] ?? 'Enter Outlet Address' }}" wire:model="outlet_address">
                                    @error('outlet_address')
                                    <span class="error text-danger">{{ $message }}</span>
                                    @enderror

                                    <button class="btn btn-primary" wire:click.prevent="searchLocation()">Find</button>
                                </div>
                            </div>
                            {{-- <div wire:ignore>
                               <div id="map" style="width: 100%; height: 500px;"></div>
                             </div> --}}

                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_phone'] ?? 'Outlet Phone' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_outlet_phone'] ?? 'Enter Outlet phone' }}" wire:model="outlet_phone">
                                @error('outlet_phone')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['latitude'] ?? 'Latitude' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_latitude'] ?? 'Enter Latitude' }}" wire:model="outlet_latitude">
                                @error('outlet_latitude')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $lang->data['longitude'] ?? 'Longitude' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_longitude'] ?? 'Enter Longitude' }}" wire:model="outlet_longitude">
                                @error('outlet_longitude')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['qr_code'] ?? 'Qr Code' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" placeholder="{{ $lang->data['enter_qr_code'] ?? 'Enter Qr Code' }}" wire:model="qr_code">
                                @error('qr_code')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror

                                 <?php if($showphoto){ ?>
                                    <img src="{{ asset('uploads/QrCode/' . $showphoto) }}" class="login-logo">
                                 <?php } ?>
                            </div>


                            {{--<div class="col-md-12">
                                <label class="form-label">{{ $lang->data['google_map'] ?? 'Google Map' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_google_map'] ?? 'Enter Google Map Iframe' }}" wire:model="google_map">
                                @error('google_map')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>--}}
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

    <div wire:ignore.self class="modal fade" id="editpincode" tabindex="-1" role="dialog" aria-labelledby="editcategory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Edit Pincode</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-12">
                                <label class="form-label">{{ $lang->data['outlet_name'] ?? 'Outlet Name' }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="form-control" placeholder="{{ $lang->data['enter_outlet_name'] ?? 'Enter Outlet Name' }}" wire:model="outlet_name">
                                @error('outlet_name')
                                <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs opacity-7">#</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2">Pincode</th>
                                        <th class="text-uppercase text-secondary text-xs opacity-7 ps-2" style="width:150px;">Place Name</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $a=1;
                                        $pincode = \App\Models\Pincode::where('outlet_id',$outlet_id)->get();
                                    @endphp
                                    @foreach ($pincode as $rowpin)
                                        <tr>
                                            <td><p class="text-sm px-3 mb-0">{{ $a }}</p></td>
                                            <td><p class="text-sm font-weight-bold mb-0">{{ $rowpin->pincode }}</p></td>
                                            <td><p class="text-sm font-weight-bold mb-0">{{ $rowpin->place_name }}</p></td>
                                            <td class="align-middle text-center">
                                                <a href="#" type="button" wire:click="deletepin({{ $rowpin->id }})" class="ms-2 badge badge-xs badge-danger text-xs fw-600">
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
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" wire:model="pincode.0" placeholder="Enter Pincode">
                                            @error('pincode.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" wire:model="name.0" placeholder="Place Name">
                                            @error('name.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button style="width: 100%; padding: 10px;" class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
                                    </div>
                                </div>
                            </div>

                            @foreach($inputs as $key => $value)
                                <div class=" add-input container">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Enter Pincode" wire:model="pincode.{{$value}}">
                                                @error('pincode.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control" wire:model="name.{{$value}}" placeholder="Place Name">
                                                @error('name.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
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
                                <button type="button" wire:click.prevent="pinstore()" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteoutlet" tabindex="-1" role="dialog" aria-labelledby="editcategory" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Delete Outlet</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="delete()">{{ $lang->data['delete'] ?? 'Delete' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script>
        let map;

        function initMap() {
            let [latitude, longitude] = @js($latlng).split(',');
            const latLng = {
                lat: parseFloat(latitude),
                lng: parseFloat(longitude)
            };

            // initialize map
            map = new google.maps.Map(document.getElementById("map"), {
                center: latLng,
                zoom: {{ $zoom }},
            });

            // listen to map changes
            const zoomField = document.getElementById("current_zoom");
            const latLngField = document.getElementById("latlng");
            map.addListener("idle", function() {
                // pass zoom to livewire
                zoomField.value = map.getZoom();
                zoomField.dispatchEvent(new Event("input"));
                // pass latitude & longitude to livewire
                latLngField.value = map.getCenter().lat()+','+map.getCenter().lng();
                latLngField.dispatchEvent(new Event("input"));
            });

        };
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChk1fHb6NCqRGvaSfmYRl0r-u7sCFSzYk&callback=initMap">
    </script>

    <script>
        var map;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: {{ $outlet_latitude }}, lng: {{ $outlet_longitude }} },
                zoom: 8
            });
        }

        document.addEventListener("livewire:load", function(event) {
            window.livewire.on('showMap', function(latitude, longitude) {
                initMap();
            });
        });
    </script>

@endpush