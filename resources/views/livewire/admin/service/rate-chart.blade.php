<div>
<div class="row align-items-center justify-content-between mb-4">
    <div class="col">
        <h5 class="fw-500 text-white">{{$lang->data['rate_chart'] ?? 'Rate Chart'}}</h5>
    </div>
    <div class="col-auto">
        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header p-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="{{$lang->data['search_here'] ?? 'Search Here'}}" wire:model="search_query">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control"  wire:model="service_category">
                            <option value="0">Chosse Category</option>
                            @php
                                $category = \App\Models\ServiceCategory::get();
                            @endphp   
                            @foreach($category as $showcate)
                                <option value="{{ $showcate->id }}">{{ $showcate->service_category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                    <select class="form-control"  wire:model="service_type">
                            <option value="0">Chosse Service Type</option>
                            @php
                                $servicetype = \App\Models\ServiceType::get();
                            @endphp   
                            @foreach($servicetype as $showtype)
                                <option value="{{ $showtype->id }}">{{ $showtype->service_type_name }}</option>
                            @endforeach
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
                                <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garement_name'] ?? 'Garment Name'}}</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garment_code'] ?? 'Garment Code'}}</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['category'] ?? 'Category'}}</th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['service_types'] ?? 'Service Types'}}</th>
                                <th class="text-center text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['rate'] ?? 'Rate'}}</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($details as $item) 
                                <tr>
                                    <td>
                                        <p class="text-sm px-3 mb-0">{{$loop->index+1}}</p>
                                    </td>

                                    <td>
                                        @if($loop->index > 0 && $item->service->id == $details[$loop->index-1]->service->id)
                                            <div class="d-flex px-3 py-1">
                                                
                                            </div>
                                        @else
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{asset('assets/img/service-icons/'.$item->service->icon)}}" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{$item->service->service_name}}</h6>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if($loop->index > 0 && $item->service->id == $details[$loop->index-1]->service->id)
                                            <div class="d-flex px-3 py-1">
                                                
                                            </div>
                                        @else
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{$item->service->garment_code}}</h6>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                
                                    <td class="align-middle">
                                        @if($loop->index > 0 && $item->service->id == $details[$loop->index-1]->service->id)
                                            <div class="d-flex px-3 py-1">
                                                
                                            </div>
                                        @else
                                            @php
                                                $service_category = \App\Models\ServiceCategory::where('id',$item->service->service_category_id)->first();
                                            @endphp
                                            @if($service_category)                            
                                                <span class="badge badge-sm bg-dark rounded-pill fw-500">{{$service_category->service_category_name}}</span>                                    
                                            @endif
                                        @endif
                                    </td>

                                    <td class="align-middle text-center">
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">  {{$item->service_type!='' ? $item->service_type->service_type_name : ''}}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="align-middle text-center" style="width:100px;">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">
                                            <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control"  value="{{$item->service_price}}"  wire:model="service_price.{{ $item->id }}" wire:keyup="updateServicePrice({{ $item->id }})"></h6>
                                        </div>
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
                                                @this.call('loadOrders')
                                                console.log('loading...')
                                            }
                                        })
                                    }, {
                                        root: null
                                    });
                                    observer.observe(this.$el);
                                }
                            }"
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-4">
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