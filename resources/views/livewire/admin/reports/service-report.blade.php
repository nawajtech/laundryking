<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['service_report'] ?? 'Service Report'}}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label>{{$lang->data['start_date'] ?? 'Start Date'}}</label>
                            <input type="date" class="form-control" wire:model="from_date">
                        </div>
                        <div class="col-md-3">
                            <label>{{$lang->data['end_date'] ?? 'End Date'}}</label>
                            <input type="date" class="form-control" wire:model="to_date">
                        </div>
                        <div class="col-md-3">
                            <label>{{$lang->data['outlate_name'] ?? 'Outlet name'}}</label>
                            <select class="form-control" wire:model="outlet">
                                <option value="0">Choose Outlet</option>
                                @foreach($outlets as $outletss)
                                <option value="{{ $outletss->id }}">{{ $outletss->outlet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>{{$lang->data['category'] ?? 'Category name'}}</label>
                            <select class="form-control" wire:model="category">
                                <option value="0">Choose Category</option>
                                @foreach($categories as $categoriess)
                                <option value="{{ $categoriess->id }}">{{ $categoriess->service_category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 40%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['service_name'] ?? 'Service Name'}}</th>
                                    <th style="width: 30%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['quantity'] ?? 'Quantity'}}</th>
                                    <th style="width: 30%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['amount'] ?? 'Total Amount'}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered align-items-center mb-0 ">
                            <tbody>
                                @foreach($ordDetDet as $rows)
                                <tr>
                                    @php
                                        $servc_type = \App\Models\ServiceType::where('id', $rows->service_type_id)->where('is_active',1)->first();
                                        $id = $rows -> id;
                                        $a="/admin/service/type";
                                    @endphp
                                    <td style="width: 40%">
                                        <p class="text-xs px-4 mb-0">
                                            @if($servc_type)
                                            <a href="{{url($a)}}" target="_blank">
                                            <span class="font-weight-bold">{{$servc_type->service_type_name ??''}}</span>
                                            @endif
                                        </p>
                                    </td>
                                   
                                    @php
                                       $query = \App\Models\OrderDetails::query();
                                        $query->whereHas('order', function($q) {
                                            if($this->outlet){
                                                $q->where('outlet_id', $this->outlet);
                                                }
                                            });

                                        $qty = $query->where('service_type_id', $rows->service_type_id)->sum('service_quantity');
                                    @endphp
                                    @if($servc_type)
                                    <td style="width: 30%">
                                        <p class="text-xs px-3 mb-0">{{$qty}}</p>
                                    </td>
                                    @endif
                                    @php
                                       $query = \App\Models\OrderDetails::query();
                                        $query->whereHas('order', function($q) {
                                            if($this->outlet){
                                                $q->where('outlet_id', $this->outlet);
                                                }
                                            });

                                        $price = $query->where('service_type_id', $rows->service_type_id)->sum('service_price');
                                    @endphp
                                    @if($servc_type)
                                    <td style="width: 30%">
                                        <p class="text-xs px-3 mb-0">{{getCurrency()}} {{$price}}</p>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="row align-items-center px-4 mb-3">
                        <div class="col">
                        </div>
                        <div class="col">
                        </div>
                        <div class="col-auto">
                            <button type="button" wire:click="downloadFile()" class="btn btn-success me-2 mb-0">{{$lang->data['download_report'] ?? 'Download Report'}}</button>
                            <a href="{{url('admin/reports/print-report/service/'.$from_date.'/'.$to_date.'/'.$category.'/'.$outlet)}}" target="_blank">
                                <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>