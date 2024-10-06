<?php
namespace App\Http\Livewire\Admin\Promotion;
use Livewire\Component;
use App\Models\Slide;
use App\Models\Customer;
use App\Models\Translation;
use App\Http\Helper\CommonHelper;
use Auth;
use File;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
class Promotions extends Component
{
    use WithFileUploads;
    public $title,$promotions,$photo,$showphoto,$search,$lang,$promotion;
     public $deleteId = '';
    public $editMode = false;
     /* validation rules */
    protected $rules = [
        'title' => 'required',
        'photo' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:1024',
    ];
    /* called before render */
    public function mount(){

        
        $this->promotions = Slide::latest()->get();
        

        if(session()->has('selected_language'))
        { /* if session has selected_language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
    }
    /* render the page */
    public function render()
    {
        return view('livewire.admin.promotion.promotion');
    }
    /* reset input fields */
    public function resetInputFields(){
        $this->title = '';
        $this->photo = '';
    }
    /* store promotion details */
    public function store()
    {
        /* if editmode is false */
        if($this->editMode == false)
        {
            $this->validate();
            $promotion = new Slide();

            $filename =time().$this->photo->getClientOriginalName();
            $this->photo->storeAs('uploads/slide', $filename, 'public'); 

            $promotion->title = $this->title;
            $promotion->image = $filename;
            $promotion->save();

            $customers = Customer::get();
            foreach ($customers as $c){
                $customer_id = $c->id;
                $user_type = 5;
                $title = $promotion->title;
                $image = url('')."/uploads/slide/".$promotion->image;
                $body = "Laundry king promotion page";;
                $data = array(
                    "type" => "Promotion",
            );
            $notification = CommonHelper::push_notification($title, $body, $user_type, $image, $customer_id, $data);
        } 
            $this->promotions = Slide::latest()->get();
            
            $this->resetInputFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Promotion has been created!']);
        }
    }
    /* set category type value while change the category type */
    
    /* process when update the element */
    public function updated($name,$value)
    {
        /* if the updated element is search */
        if($name == 'search' && $value != '')
        {
        
            
            $this->promotions = Slide::where(function($query) use ($value) { 
                $query->where('title', 'like', '%' . $value . '%');
            })->get();   
        
            
        } else {
            
                $this->promotions = Slide::latest()->get();
            
        }
    }
      /* set the content to edit */
    public function edit($id)
    {
        $this->editMode = true;
        $this->promotion = Slide::where('id',$id)->first();
        $this->title = $this->promotion->title;
        $this->showphoto = $this->promotion->image;

    }
    /* update promotion*/
    public function update()
    {
        //$this->validate();
        if($this->editMode == true)
        {
            if($this->photo == ''){
            $this->promotion->title = $this->title;
            $this->promotion->save();
            }else{

           $filename = $this->photo->getClientOriginalName();
           $this->photo->storeAs('uploads/slide', $filename, 'public');

            $this->promotion->title = $this->title;
            $this->promotion->image = $filename;
            $this->promotion->save();
            }
            $this->promotions = Slide::latest()->get();
            $this->resetInputFields();
            $this->editMode = false;
            $this->emit('closemodal');
            $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Promotions has been updated!']);
        }
    }

     public function toggle($id)
    {
        $promotion = Slide::find($id);
        if($promotion->is_active == 1)
        {
            $promotion->is_active = 0;
        }
        elseif($promotion->is_active == 0)
        {
            $promotion->is_active = 1;
        }
        $promotion->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    /* promotion soft delete */
    public function delete()
    {   
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Promotion deleted Successfully!']);
        $this->promotion = Slide::find($this->deleteId)->delete();
        $this->emit('closemodal');
        $this->promotions = Slide::latest()->get();
    }
}