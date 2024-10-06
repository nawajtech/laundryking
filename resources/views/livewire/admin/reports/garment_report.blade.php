<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['garment_report'] ?? 'Garment Report'}}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label>{{$lang->data['start_date'] ?? 'Start Date'}}</label>
                            <input type="date" class="form-control" wire:model="from_date">
                        </div>
                        <div class="col-md-4">
                            <label>{{$lang->data['end_date'] ?? 'End Date'}}</label>
                            <input type="date" class="form-control" wire:model="to_date">
                        </div>
                        <div class="col-md-4">
                            <label>{{$lang->data['outlate_name'] ?? 'Outlet name'}}</label>
                            <select class="form-control" wire:model="outlet">
                                <option value="0">Choose Outlet</option>
                                @foreach($outlets as $outletss)
                                    <option value="{{ $outletss->id }}">{{ $outletss->outlet_name }}</option>
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
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['Garment'] ?? 'Garment'}}</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['category'] ?? 'Category'}}</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['qty'] ?? 'Quantity'}}</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['amount'] ?? 'Total Amount'}}</th>
                                <th style="width: 20%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['addon_amount'] ?? 'Addon Amount'}}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered align-items-center mb-0 ">
                            <tbody>
                            @if($ordDetDet)
                                @foreach($ordDetDet as $row)
                                    <tr>
                                    @php
                                        $a="/admin/service/edit/".$row->service_id;
                                    @endphp
                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 mb-0">
                                            <a href="{{url($a)}}" target="_blank">
                                                <span class="font-weight-bold">{{$row->service->service_name }} </span>
                                            </p>
                                        </td>
                                        @php
                                        $category = App\Models\ServiceCategory::where('id', $row->service->service_category_id)->first();
                                        $a="/admin/service/category/";
                                        @endphp

                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 mb-0">
                                            <a href="{{url($a)}}" target="_blank">
                                                <span class="font-weight-bold">{{ $category->service_category_name }}</span>
                                            </p>
                                        </td>

                                        @php
                                        $quantity = App\Models\OrderDetails::where('service_id', $row->service_id)->sum('service_quantity');
                                        @endphp
                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0">{{ $quantity }}</p>
                                        </td>
                                        @php
                                        $amount = App\Models\OrderDetails::where('service_id', $row->service_id)->sum(\DB::raw('service_quantity * service_price'));
                                        @endphp
                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0"> {{ $amount }}</p>
                                        </td>
                                        @php
                                        $query = App\Models\OrderAddonDetail::query();

                                        $query->whereHas('order_details', function($q) use ($row){
                                                $q->where('service_id', $row->service_id);
                                        });
                                        $addon_price = $query->sum('addon_price');
                                        @endphp

                                        <td style="width: 20%" >
                                            <p class="text-xs px-3 font-weight-bold mb-0">{{ $addon_price }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
                            <a href="{{url('admin/reports/print-report/garment/'.$from_date.'/'.$to_date.'/'.$outlet)}}" target="_blank">
                                <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>