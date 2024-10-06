<div>
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col">
            <h5 class="fw-500 text-white">{{ $lang->data['rewash_request'] ?? 'Rewash Request' }} </h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header p-4">
                    <div class="row">
                        <div class="col-md-<?php if (Auth::user()->user_type ==2 || Auth::user()->user_type ==1 ) { ?>12<?php } ?>">
                            <input type="text" class="form-control" placeholder="{{ $lang->data['search_here'] ?? 'Search Here' }}" wire:model="search_query">
                        </div>
                    </div>
                </div>
                <div class="card-body p-1">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['order_info'] ?? 'Order Info' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['garment_id'] ?? 'Garment Tag Id' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs  opacity-7">
                                    {{ $lang->data['customer'] ?? 'Customer' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs  opacity-7">
                                    {{ $lang->data['order_amount'] ?? 'Order Amount' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                    {{ $lang->data['garment'] ?? 'Garment Name' }}
                                </th>
                                <th class="text-uppercase text-secondary text-xs opacity-7">
                                {{ $lang->data['rewash_request'] ?? 'Rewash Request' }}</th>
                            </tr>
                            </thead>
                            <tbody wire:poll="refresh">
                            @if(count($orders) > 0)
                            @foreach ($orders as $item)
                                <tr>
                                    <td>
                                        <p class="text-sm px-3 mb-0">
                                            <span class="me-2">{{ $lang->data['order_id'] ?? 'Order ID' }}:</span>
                                            <span class="font-weight-bold">{{ $item->order_details->order->order_number }}</span>
                                        </p>
                                        <p class="text-sm px-3 mb-0">
                                            <span class="me-2">{{ $lang->data['order_date'] ?? 'Order Date' }}:</span>
                                            <span class="font-weight-bold">{{ \Carbon\Carbon::parse($item->order_details->order->order_date)->format('d/m/y') }}</span>
                                        </p>
                                        <p class="text-sm px-3 mb-0">
                                            <span class="me-2">{{ $lang->data['delivery_date'] ?? 'Delivery Date' }}:</span>
                                            <span class="font-weight-bold">{{ \Carbon\Carbon::parse($item->order_details->order->delivery_date)->format('d/m/y') }}</span>
                                        </p>
                                        <p class="text-sm px-3 mb-0"><span class="badge rounded-pill bg-warning">{{ $item->order_details->order->outlet->outlet_name ?? "" }}</span></p>
                                    </td>
                                    <td>
                                        <p class="text-sm px-3 font-weight-bold mb-0">
                                            {{ $item->garment_tag_id }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm px-3 font-weight-bold mb-0">
                                            {{ $item->order_details->order->customer_name ?? ($lang->data['walk_in_customer'] ?? 'Walk In Customer') }}
                                        </p>
                                        <p class="text-sm px-3 mb-0">{{ $item->order_details->order->phone_number ?? '-' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm px-3 font-weight-bold mb-0">{{ getCurrency() }}
                                            {{ number_format($item->order_details->order->total, 2) }}
                                        </p>
                                    </td>
                                    @php 
                                        $garment = \App\Models\Service::where('id',$item->order_details->service_id)->first();
                                    @endphp
                                    @php 
                                        $service = \App\Models\ServiceType::where('id',$item->order_details->service_type_id)->first();
                                    @endphp
                                    <td>
                                    <p class="text-sm px-2 font-weight-bold mb-0" >
                                        {{ $garment->service_name }}
                                    </p>
                                    <p class="text-sm px-3 font-weight-bold mb-0">
                                        {{ $service->service_type_name }}
                                    </p>    
                                    </td>
                                    <td>
                                        <a data-bs-toggle="modal" data-bs-target="#approvereq" type="button" wire:click="deleteId({{ $item->id }})"
                                            class="ms-0.5 badge badge-xs badge-success text-xs fw-500">
                                            {{ $lang->data['approve'] ?? 'Approve' }}
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#rewashreq" type="button" wire:click="deleteId({{ $item->id }})"
                                            class="ms-0.5 badge badge-xs badge-danger text-xs fw-500">
                                            {{ $lang->data['decline'] ?? 'Decline' }}
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#viewimage" type="button"  wire:click="view_image({{ $item->order_id }})"
                                            class="ms-0.5 badge badge-xs badge-success text-xs fw-500">
                                            {{ $lang->data['view'] ?? 'View Images' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="7" style="text-align: center;">
                                        <?php echo "No data found"; ?>
                                    </td>
                                </tr>
                            @endif
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
    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-labelledby="addpayment" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600" id="addpayment">
                        {{ $lang->data['payment_details'] ?? 'Payment Details' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>     
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" class="modal fade " id="rewashreq" tabindex="-1" role="dialog"
        aria-labelledby="rewashreq" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Request for Rewash</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure want to decline?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal"
                        wire:click.prevent="decline()">{{ $lang->data['decline'] ?? 'Decline' }}</button>
                </div>
                
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" class="modal fade " id="approvereq" tabindex="-1" role="dialog"
        aria-labelledby="approvereq" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                        Request for Rewash</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <p>Are you sure want to Approve?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal"
                        wire:click.prevent="approve()">{{ $lang->data['approve'] ?? 'Approve' }}</button>
                </div>
                
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" class="modal fade " id="viewimage" tabindex="-1" role="dialog"
        aria-labelledby="viewimage" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">
                    Images for Rewash</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @php 
                        $i=1;
                        @endphp
                        @foreach (explode(',', $image) as $img)
                        <div class="col-md-4" >
                            <a href="#img{{$i}}">
                                <img style="width:100%;height:100px" src="{{ asset('uploads/rewash/' .$img) }}">
                            </a>
                            <a href="#" class="lightbox" id="img{{$i}}">
                                <span style="background-image: url({{ asset('uploads/rewash/' .$img) }})"></span>
                            </a>
                        </div>
                        @php 
                        $i++;
                        @endphp
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ $lang->data['cancel'] ?? 'Cancel' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .lightbox {
  display: none;

  position: fixed;
  z-index: 999;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  
  padding: 1em;

  background: rgba(0, 0, 0, 0.8);
}

.lightbox:target {
  display: block;
}

.lightbox span {
  display: block;
  width: 70%;
  height: 70%;
  margin: 0px auto;
  margin-top:5%;
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;
}
</style>
