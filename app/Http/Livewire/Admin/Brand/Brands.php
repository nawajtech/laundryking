<?php
namespace App\Http\Livewire\Admin\Brand;
use Livewire\Component;
use App\Models\Brand;
use App\Models\Translation;
use App\Models\OrderDetails;
use Auth;
use File;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Brands extends Component
{
    use WithFileUploads;
    public $brand_name,$brands,$photo,$showphoto,$search,$lang;
     public $deleteId = '';
    public $editMode = false;

    /* validation rules */
    protected $rules = [
        'brand_name' => 'required',
        'photo' => 'image|mimes:jpg,jpeg,png,svg,gif|max:1024',
    ];

    /* called before render */
    public function mount(){

        $this->brands = Brand::latest()->get();

        if(session()->has('selected_language'))
        { /* if session has selected_language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }else{
            $this->lang = Translation::where('default',1)->first();
        }
    }

    /* render the page */
    public function render()
    {
        return view('livewire.admin.brand.brand');
    }

    /* reset input fields */
    public function resetInputFields(){
        $this->brand_name = '';
        $this->photo = '';
    }

    /* store expense category details */
    public function store()
    {
        /* if editmode is false */
        if($this->editMode == false)
        {
            $this->validate();
            $brand = new Brand();

            if($this->photo != ''){
                $filename = time().$this->photo->getClientOriginalName();
                $this->photo->storeAs('uploads/brand', $filename, 'public'); 
            }

            $brand->brand_name = $this->brand_name;
            $brand->image = $filename ?? null;
            $brand->save();
            
            $this->brands = Brand::latest()->get();
            
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Brand has been created!']);
        }
    }
    /* set category type value while change the category type */
    
    /* process when update the element */
    public function updated($name,$value)
    {
        /* if the updated element is search */
        if($name == 'search' && $value != ''){
            $this->brands = Brand::where(function($query) use ($value) { 
                $query->where('brand_name', 'like', '%' . $value . '%');
            })->get();   
        } else {
            $this->brands = Brand::latest()->get(); 
        }
    }
    
    /* set the content to edit */
    public function edit($id)
    {
        $this->editMode = true;
        $this->brand = Brand::where('id',$id)->first();
        $this->brand_name = $this->brand->brand_name;
        $this->showphoto = $this->brand->image;
    }

    /* update brand*/
    public function update()
    {
        //$this->validate();
        if($this->editMode == true)
        {
            if($this->photo == ''){
                $this->brand->brand_name = $this->brand_name;
                $this->brand->save();
            }else{
                $filename = time().$this->photo->getClientOriginalName();
                $this->photo->storeAs('uploads/brand', $filename, 'public');

                $this->brand->brand_name = $this->brand_name;
                $this->brand->image = $filename;
                $this->brand->save();
            }
           
            $this->brands = Brand::latest()->get();
            
            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Brand has been updated!']);
        }
    }

    public function toggle($id)
    {
        $brand = Brand::find($id);
        if($brand->is_active == 1){
            $brand->is_active = 0;
        }elseif($brand->is_active == 0){
            $brand->is_active = 1;
        }
        $brand->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    /* brand delete */
    public function delete(){   
        $doesntExist = OrderDetails::where('brand_id', $this->deleteId)->doesntExist();
        if($doesntExist){
            /* if Brand has any children */
            Brand::where('id', $this->deleteId)->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Brand deleted Successfully!']);
        }else{
            /* if Brand has no children */
            $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Brand deletion restricted!']);
        }
        $this->emit('closemodal');
        $this->brands = Brand::latest()->get();
    }
}