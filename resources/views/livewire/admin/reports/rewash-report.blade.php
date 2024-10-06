<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['rewash_report'] ?? 'Rewash Report'}}</h5>
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
                                    <th style="width: 25%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['outlet_name'] ?? 'Outlet Name'}}</th>
                                    <th style="width: 25%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['garment_name'] ?? 'Garment Name'}}</th>
                                    <th style="width: 25%" class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['rewash_qty'] ?? 'Rewash '}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered align-items-center mb-0 ">
                            <tbody>
                                @foreach($orderDet as $row)
                                <tr>
                                    <td style="width: 25%">
                                        <p class="text-xs px-3 mb-0">
                                            <span class="font-weight-bold">{{$row->outlet_name}}</span>
                                        </p>
                                    </td>
                                    <td style="width: 25%">
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->service_name}}</p>
                                    </td>
                                    <td style="width: 25%">
                                        <p class="text-xs px-3 font-weight-bold mb-0">{{$row->total}}</p>
                                    </td>
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
                            <a href="{{url('admin/reports/print-report/rewash/'.$from_date.'/'.$to_date.'/'.$outlet)}}" target="_blank">
                                <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>