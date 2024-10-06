<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['workstationnote_report'] ?? 'Workstation Note Summary Report'}}</h5>
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
                            <label>{{$lang->data['workstation_name'] ?? 'Workstation name'}}</label>
                            <select class="form-control" wire:model="workstation">
                                <option value="0">Choose Workstation</option>
                                @foreach($workstations as $workstationss)
                                <option value="{{ $workstationss->id }}">{{ $workstationss->workstation_name }}</option>
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
                                    <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_no'] ?? 'Order No'}}</th>
                                    <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['order_date'] ?? 'Order Date'}}</th>
                                    <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['delivery_date'] ?? 'Delivery Date'}}</th>
                                    <th style="width: 14%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['delivery_type'] ?? 'Delivery Type'}}</th>
                                    <th style="width: 9%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['pcs'] ?? 'Pcs'}}</th>
                                    <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garment_details'] ?? 'Garment Details'}}</th>
                                    <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['services'] ?? 'Services'}}</th>
                                    <th style="width: 7%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['defects'] ?? 'Defects'}}</th>
                                    <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['colors'] ?? 'Colors'}}</th>
                                    <th style="width: 10%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['brand'] ?? 'Brand'}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered align-items-center mb-0 ">
                            <tbody>
                                @foreach($ordDetDet as $rows)
                                <tr>
                                    <td style="width: 10%">
                                        <p class="text-xs px-3 mb-0">
                                            <span class="text-xs px-3 mb-0">{{$rows->order_number ??''}}</span>
                                        </p>
                                    </td>
                                    <td style="width: 10%">
                                        <p class="text-xs px-3 mb-0">{{ \Carbon\Carbon::parse($rows->order_date)->format('d/m/Y') ?? '' }}</p>
                                    </td>
                                    <td style="width: 10%">
                                        <p class="text-xs px-3 mb-0">{{ \Carbon\Carbon::parse($rows->delivery_date)->format('d/m/Y') ?? '' }}</p>
                                    </td>
                                    <td style="width: 10%">
                                        <p class="text-xs px-3 mb-0">{{ $rows->delivery_type ??'' }}</p>
                                    </td>
                                    @php
                                    $quantity = App\Models\OrderDetails::where('order_id', $rows->id)->count();
                                    @endphp
                                    <td style="width: 10%">
                                        <p class="text-xs px-3 mb-0">{{ $quantity ?? '' }}</p>
                                    </td>
                                    @php 
                                    $ord_details = App\Models\OrderDetails::where('order_id', $rows->id)->get();
                                    @endphp
                                    <td style="width: 10%">
                                        @foreach($ord_details as $ord_det)
                                        @php 
                                           $service = App\Models\Service::where('id', $ord_det->service_id)->first();
                                        @endphp
                                        <p class="text-xs px-3 mb-0">{{ $service->service_name }}</p>
                                        @endforeach
                                    </td>
                                    <td style="width: 10%">
                                        @foreach($ord_details as $ord_det)
                                        <p class="text-xs px-3 mb-0">{{ $ord_det->service_name }}</p>
                                        @endforeach
                                    </td>
                                    <td style="width: 10%">
                                        @foreach($ord_details as $ord_det)
                                        @php
                                        $defect_remark = App\Models\OrderDetailsDetail::where('order_detail_id', $ord_det->id)->first();
                                        @endphp
                                        <p class="text-xs px-3 mb-0">{{ $defect_remark->remarks }}</p>
                                        @endforeach
                                    </td>
                                    <td style="width: 10%">
                                        @foreach($ord_details as $ord_det)
                                        @if($ord_det->color_code !='')
                                        <button class="clr-btn" style="margin:0px auto; border:1px solid #000; width:66%; height:10px; display: block; margin-bottom: 5px; background-color: {{$ord_det->color_code ?? 'No color'}}!important"></button>
                                        @else
                                        <p class="text-xs px-3 mb-0">{{ 'No Color' }}</p>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td style="width: 10%">
                                        @foreach($ord_details as $ord_det)
                                        @php
                                            $brand = App\Models\Brand::where('id', $ord_det->brand_id)->first();
                                        @endphp
                                        <p class="text-xs px-3 mb-0">{{ $brand->brand_name ?? 'No Brand' }}</p>
                                        @endforeach
                                    </td>
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
                            <a href="{{url('admin/reports/print-report/workstationnote/'.$from_date.'/'.$to_date.'/'.$workstation)}}" target="_blank">
                                <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>