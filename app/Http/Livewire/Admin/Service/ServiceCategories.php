<?php
namespace App\Http\Livewire\Admin\Service;
use Livewire\Component;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Translation;
use Auth;
use File;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ServiceCategories extends Component
{
    use WithFileUploads;
    public $service_category_name,$categories,$photo,$iteration,$showphoto,$search,$lang;
    public $deleteId = '';
    public $editMode = false;
     
    /* validation rules */
    protected $rules = [
        'service_category_name' => 'required',
        'photo' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:1024',
    ];
    
    /* called before render */
    public function mount(){

        $this->categories = ServiceCategory::latest()->get();

        if(session()->has('selected_language')){ 
            /* if session has selected_language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }else{
            $this->lang = Translation::where('default',1)->first();
        }
    }

    /* render the page */
    public function render()
    {
        return view('livewire.admin.service.service-category');
    }

    /* reset input fields */
    public function resetInputFields(){
        $this->service_category_name = '';
        $this->photo = '';
        $this->iteration++;
    }

    /* store expense category details */
    public function store()
    {
        /* if editmode is false */
        if($this->editMode == false)
        {
            $this->validate();
            $category = new ServiceCategory();

            $filename = time().$this->photo->getClientOriginalName();
            $this->photo->storeAs('uploads/category', $filename, 'public');

            $category->service_category_name = $this->service_category_name;
            $category->image = $filename;
            $category->save();
            
            $this->categories = ServiceCategory::latest()->get();
            
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Service Category has been created!']);
        }
    }
    /* set category type value while change the category type */
    
    /* process when update the element */
    public function updated($name,$value)
    {
        /* if the updated element is search */
        if($name == 'search' && $value != ''){ 
            $this->categories = ServiceCategory::where(function($query) use ($value) { 
                $query->where('service_category_name', 'like', '%' . $value . '%');
            })->get();    
        } else {
            $this->categories = ServiceCategory::latest()->get();  
        }
    }
    
    /* set the content to edit */
    public function edit($id)
    {
        $this->editMode = true;
        $this->category = ServiceCategory::where('id',$id)->first();
        $this->service_category_name = $this->category->service_category_name;
        $this->showphoto = $this->category->image;
    }

    /* update expense category*/
    public function update()
    {
        $this->validate();
        if($this->editMode == true)
        {
            if($this->photo == ''){
                $this->category->service_category_name = $this->service_category_name;
                $this->category->save();
            }else{
                $filename = $this->photo->getClientOriginalName();
                $this->photo->storeAs('uploads/category', $filename, 'public');   

                $this->category->service_category_name = $this->service_category_name;
                $this->category->image = $filename;
                $this->category->save();
            }
           
            $this->categories = ServiceCategory::latest()->get();
            
            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Service Category has been updated!']);
        }
    }

    public function toggle($id)
    {
        $service = ServiceCategory::find($id);
        if($service->is_active == 1){
            $service->is_active = 0;
        }elseif($service->is_active == 0){
            $service->is_active = 1;
        }
        $service->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function delete(){   
        $doesntExist = Service::where('service_category_id', $this->deleteId)->doesntExist();

        if($doesntExist){
            /* if service  category  has any childen */
            $service_cat = ServiceCategory::where('id', $this->deleteId)->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Service Category deleted Successfully!']);
        }else{
             /* if service category has no children */
             $this->dispatchBrowserEvent('alert', ['type' => 'error',  'message' => 'Service Category deletion restricted!']);
        }
        $this->categories = ServiceCategory::latest()->get();
        $this->emit('closemodal');
    }
}