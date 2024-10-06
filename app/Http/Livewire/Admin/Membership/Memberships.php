<?php
namespace App\Http\Livewire\Admin\Membership;

use App\Models\Membership;
use App\Models\Translation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Session;

class Memberships extends Component
{
    use WithFileUploads; 
    public $membership_name, $min_price, $discounttype, $max_price, $icon, $members, $search,$showicon, $discount, $express_fee, $delivery_fee;
    public $deleteId = '';
    public $editMode = false;

    /* called before render */
    public function mount()
    {
        $this->members = Membership::get();
        if (session()->has('selected_language')) {
            /* if session has selected_language */
            $this->lang = Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $this->lang = Translation::where('default', 1)->first();
        }
    }

    /* render the page */
    public function render()
    {
        return view('livewire.admin.membership.membership');
    }

    /* set the rules */
    protected $rules = [
        'membership_name' => 'required',
        'min_price' => 'required',
        'max_price' => 'required',
        'icon' => 'required',
        'discount' => 'required',
        'express_fee' => 'required',
        'delivery_fee' => 'required',
    ];

    public function resetInputFields()
    {
        $this->membership_name = '';
        $this->min_price = '';
        $this->icon = '';
        $this->max_price = '';
        $this->discount = '';
        $this->express_fee = '';
        $this->delivery_fee = '';
        $this->icon = '';
        $this->discounttype = '';
    }

    public function store()
    {
        /* if editmode is false */
        if ($this->editMode == false) {
            $this->validate();
            $filename = time().$this->icon->getClientOriginalName();
            $this->icon->storeAs('uploads/membership', $filename, 'public');
            $membership = new Membership();
            $membership->membership_name = $this->membership_name;
            $membership->min_price = $this->min_price;
            $membership->max_price = $this->max_price;
            $membership->discount = $this->discount;
            $membership->discount_type = $this->discounttype;
            $membership->express_fee = $this->express_fee;
            $membership->delivery_fee = $this->delivery_fee;
            $membership->icon = $filename;
            $membership->save();
            $this->members = Membership::get();
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Membership Settings Updated Successfully!']);
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function updated($name, $value)
    {
        /* if the updated element is search */
        if ($name == 'search' && $value != '') {
            $this->members = Membership::where(function ($query) use ($value) {
                $query->where('membership_name', 'like', '%' . $value . '%');
            })->get();
        }
    }

    /* set the content to edit */
    public function edit($id)
    {
        $this->editMode = true;
        $this->membership = Membership::where('id', $id)->first();

        $this->membership_name = $this->membership->membership_name;
        $this->min_price = $this->membership->min_price;
        $this->max_price = $this->membership->max_price;
        $this->showicon = $this->membership->icon;
        $this->discount = $this->membership->discount;
        $this->express_fee = $this->membership->express_fee;
        $this->delivery_fee = $this->membership->delivery_fee;
    }

    public function update()
    {
        if ($this->editMode == true) {
            if ($this->icon == '') {
                $this->membership->membership_name = $this->membership_name;
                $this->membership->min_price = $this->min_price;
                $this->membership->max_price = $this->max_price;
                $this->membership->discount = $this->discount;
                $this->membership->express_fee = $this->express_fee;
                $this->membership->delivery_fee = $this->delivery_fee;
                $this->membership->save();
            } else {
                $filename = time().$this->icon->getClientOriginalName();
                $this->icon->storeAs('uploads/membership', $filename, 'public');
                $this->membership->membership_name = $this->membership_name;
                $this->membership->min_price = $this->min_price;
                $this->membership->discount = $this->discount;
                $this->membership->express_fee = $this->express_fee;
                $this->membership->delivery_fee = $this->delivery_fee;
                $this->membership->icon = $filename;
                $this->membership->save();
            }
            $this->members = Membership::latest()->get();

            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Membership has been updated!']);
        }
    }

    public function toggle($id)
    {
        $membership = Membership::find($id);
        if ($membership->is_active == 1) {
            $membership->is_active = 0;
        } elseif ($membership->is_active == 0) {
            $membership->is_active = 1;
        }
        $membership->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Membership deleted Successfully!']);
        $this->members = Membership::find($this->deleteId)->delete();
        $this->emit('closemodal');
        $this->members = Membership::latest()->get();
    }
}
