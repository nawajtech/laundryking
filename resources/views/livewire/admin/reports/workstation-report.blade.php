<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{$lang->data['workstation_report'] ?? 'Workstation Report'}}</h5>
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
                                    <th style="width: 50%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['workstation_name'] ?? 'Workstation Name'}}</th>
                                    <th style="width: 50%" class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['qty'] ?? 'Quantity'}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive mb-4 table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered align-items-center mb-0 ">
                            <tbody>
                                @foreach($ordDetDet as $rows)
                                <tr>
                                    <td style="width: 50%">
                                        <p class="text-xs px-3 mb-0">
                                            <span class="text-xs px-3 mb-0">{{$rows->workstation_name ??''}}</span>
                                        </p>
                                    </td>
                                    <td style="width: 50%">
                                        <p class="text-xs px-3 mb-0">{{ $rows->total }}</p>
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
                            <a href="{{url('admin/reports/print-report/workstation/'.$from_date.'/'.$to_date.'/'.$workstation)}}" target="_blank">
                                <button type="submit" class="btn btn-warning mb-0">{{$lang->data['print_report'] ?? 'Print Report'}}</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>