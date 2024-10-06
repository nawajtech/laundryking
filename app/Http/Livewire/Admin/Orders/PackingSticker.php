<?php
namespace App\Http\Livewire\Admin\Orders;
use App\Models\OrderDetailsDetail;
use App\Models\Translation;
use Livewire\Component;
use App\Http\Helper\CommonHelper;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\Cursor;
use Auth;

class PackingSticker extends Component
{
    public $orders, $order_details, $ordDet, $lang, $generatesticker, $search, $ordDetDet, $orderdetails, $germent_code;
    public $nextCursor;
    protected $currentCursor;
    public $hasMorePages;

    /* render the page */
    public function render()
    {
        return view('livewire.admin.orders.packing_sticker');
    }

    /* process before render */
    public function mount()
    {
        $this->ordDetDet = new EloquentCollection();
        $this->loadStickers();

        if(session()->has('selected_language')) {
            /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        } else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
    }

    /* process while update the content */
    public function updated($name,$value)
    {
        $this->ordDetDet = new EloquentCollection();
        $this->reloadStickers();
    }

    /* refresh the page */
    public function refresh()
    {
        /* if search query or order filter is empty */
        if( $this->search == '')
        {
            $this->ordDetDet->fresh();
        }
    }

    public function loadStickers()
    {
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $stickerlist = $this->filterdata();
        $this->ordDetDet->push(...$stickerlist->items());
        if ($this->hasMorePages = $stickerlist->hasMorePages()) {
            $this->nextCursor = $stickerlist->nextCursor()->encode();
        }
        $this->currentCursor = $stickerlist->cursor();
    }

    public function reloadStickers()
    {
        $this->ordDetDet = new EloquentCollection();
        $this->nextCursor = null;
        $this->hasMorePages = null;
        if ($this->hasMorePages !== null  && ! $this->hasMorePages) {
            return;
        }
        $printsticker = $this->filterdata();
        $this->ordDetDet->push(...$printsticker->items());
        if ($this->hasMorePages = $printsticker->hasMorePages()) {
            $this->nextCursor = $printsticker->nextCursor()->encode();
        }
        $this->currentCursor = $printsticker->cursor();
    }

    public function filterdata()
    {
        $query = OrderDetailsDetail::query();
        if(Auth::user()->user_type == 1){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                });
            })->where('status','!=', 9)->where('status','!=', 10)->where('garment_tag_id', 'like' , '%'.$this->search.'%');

        }elseif(Auth::user()->user_type == 3){
            $query->whereHas('order_details', function($q) {
                $q->whereHas('order', function($q) {
                    $q->where('workstation_id', Auth::user()->workstation_id);
                });
            })->where('status','!=', 9)->where('status','!=', 10)->whereIn('status', [3,4,5])->where('garment_tag_id', 'like' , '%'.$this->search.'%');
        }

        $ordDetDet = $query->latest()->cursorPaginate(10, ['*'], 'cursor', Cursor::fromEncoded($this->nextCursor));

        return $ordDetDet;
    }

    public function updatedSearch()
    {
        $query = OrderDetailsDetail::query();
        $query->whereHas('order_details', function($q) {
            $q->whereHas('order', function($q) {
                if(Auth::user()->user_type == 2){
                    $q->where('outlet_id', Auth::user()->outlet_id)
                        ->orWhere('delivery_outlet_id', Auth::user()->outlet_id);
                }elseif(Auth::user()->user_type == 3){
                   $q->where('workstation_id', Auth::user()->workstation_id);
                }
            });
        });
        $orderdetails = $query->where('status','!=', 9)->where('status','!=', 10)->where('garment_tag_id', $this->search)->first();
        
        if($orderdetails){
            $this->germent_code = $orderdetails->id;
        } else {
            $this->germent_code = '';
        }
    }
}