<?php

namespace App\Http\Livewire\Admin\Staff;

use App\Models\Translation;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\Outlet;
use App\Models\Workstation;
use App\Models\OutletDriver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Staff extends Component
{

    public $role,$roletype,$access_module,$user_filter,$outlet,$outletid,$workstationid,$name,$phone,$email,$password,$workstation,$showworkstation,$address,$is_active,$staffs,$staff,$staff_id,$staff_name,$showoutlet,$outletdriver,$assignoutlet,$usertype,$search='';

    public $create_order,
        $view_order,
        $edit_order,
        $order_status,
        $order_status_screen,
        $assign_driver,
        $rewash_request,
        $cancel_request,
        $garment_status_screen,
        $packing_sticker,
        $customer,
        $add_customer,
        $assign_membership,
        $manage_category,
        $manage_service_type,
        $manage_garments,
        $manage_addons,
        $manage_rate_chart,
        $expense_list,
        $expense_category,
        $daily_report,
        $order_report,
        $sales_report,
        $expense_report,
        $tax_report,
        $garment_report,
        $customer_order_report,
        $customer_history_report,
        $workstation_report,
        $workstation_summary_report,
        $outstanding_report,
        $stock_report,
        $rewash_report,
        $service_report,
        $outlet_report,
        $financial_year,
        $mail_settings,
        $master_settings,
        $report_settings,
        $file_tools,
        $sms_settings,
        $membership,
        $manage_user,
        $manage_outlet,
        $manage_workstation,
        $manage_brand,
        $manage_voucher,
        $manage_delivery,
        $manage_promotion,
        $user_permission;


    public $inputs = [];
    public $i = 1;
    public $deleteId = '';

    public function render()
    {
        $this->roletype = User::$roletype;

        $this->showoutlet = Outlet::where('is_active',1)->get();
        $this->showworkstation = Workstation::where('is_active',1)->get();

        $query = User::query();
        if($this->search != '') {
            $query->where('name','like','%'.$this->search.'%');
        }
        if($this->user_filter != 0){
            $query->where('user_type',$this->user_filter);
        }

        $this->staffs = $query->get();
        $this->access_module = UserPermission::$access_module;

        if(session()->has('selected_language'))
        {   /*if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }

        return view('livewire.admin.staff.staff');
    }

    public function resetFields()
    {
        $this->role = '';
        $this->outlet = null;
        $this->outletid = '';
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->password = '';
        $this->address = '';
        $this->is_active = 1;
        $this->staff = null;
        $this->outletdriver = '';
    }

    public function save()
    {
        if($this->outletid == ''){
            $this->outletid = '';
        }elseif($this->workstationid == ''){
            $this->workstationid == '';
        }else{
            $outlet_check = Outlet::where('id', $this->outletid)->first();
            $this->workstationid = $outlet_check ? $outlet_check->workstation_id: '';
        }

        $this->validate([
            'role' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users',
            'password'=> 'required'
        ]);

        if($this->role == 1){ $subadmin = 1; }
        User::create([
            'name'  => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'password'  => Hash::make($this->password),
            'user_type' => $this->role,
            'outlet_id' => $this->outletid,
            'workstation_id' => $this->workstationid ?? '',
            'is_subadmin' => $subadmin ?? NULL,
            'is_active' => $this->is_active ?? 0,
            'created_by' => Auth::user()->id
        ]);

        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Staff was created!']);
    }

    public function toggle($id)
    {
        $staff = User::find($id);
        if($staff->is_active == 1)
        {
            $staff->is_active = 0;
        }
        elseif($staff->is_active == 0)
        {
            $staff->is_active = 1;
        }
        $staff->save();
    }

    public function view($id)
    {
        $this->resetFields();
        $this->staff = User::find($id);
        $this->name = $this->staff->name;
        $this->email = $this->staff->email;
        $this->phone = $this->staff->phone;
        $this->outletid = $this->staff->outlet_id;
        $this->workstationid = $this->staff->workstation_id;
        $this->usertype = $this->staff->user_type;
        $this->is_active = $this->staff->is_active;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users,email,'.$this->staff->id,
        ]);
        $this->staff->name = $this->name;
        $this->staff->email = $this->email;
        $this->staff->phone = $this->phone;
        $this->staff->outlet_id = $this->outletid;
        $this->staff->workstation_id = $this->workstationid;
        $this->staff->is_active = $this->is_active ?? 0;
        if($this->password != '')
        {
            $this->staff->password = Hash::make($this->password);
        }
        $this->staff->save();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Staff was updated!']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function updatedRole($roletype)
    {
        $this->outlet = null;

        if (!is_null($roletype) && $roletype == 2) {
            $this->outlet = Outlet::where('is_active',1)->latest()->get();
        }

        $this->workstation = null;
        if (!is_null($roletype) && $roletype == 3) {
            $this->workstation = Workstation::where('is_active',1)->latest()->get();
        }
    }

    public function deleteID($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        $staff = User::find($this->deleteId);
        $staff->delete();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Staff was deleted!']);
    }

    public function editoutlet($id)
    {
        $this->editMode = true;
        $this->staff = User::where('id', $id)->first();
        $this->staff_id = $this->staff->id;
        $this->staff_name = $this->staff->name;
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs, $i);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public function storeoutlet()
    {
        $validatedDate = $this->validate(
            [
                'outletdriver.0' => 'required',
                'outletdriver.*' => 'required',
            ],
            [
                'outletdriver.0.required' => 'Please select outlet',
                'outletdriver.*.required' => 'Please select outlet',
            ]
        );

        foreach ($this->outletdriver as $key => $value) {
            OutletDriver::create(['user_id'=> $this->staff_id, 'outlet_id' => $this->outletdriver[$key]]);
        }

        $this->inputs = [];

        $this->resetFields();

        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Outlet Assigned successfully']);
    }

    public function deleteoutlet($id)
    {
        $this->pincode = OutletDriver::where('id', $id)->delete();
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Outlet deleted Successfully!']);

        $this->pincode = OutletDriver::latest()->get();
    }

    public function editaccess($id)
    {
        $this->editMode = true;
        $this->staff = User::where('id', $id)->first();
        $this->staff_id = $this->staff->id;
        $this->staff_name = $this->staff->name;
        $this->usertype = $this->staff->user_type;

        $this->resetFieldsAccess();
        $access_module = $this->access_module[$this->usertype];
        $permission = UserPermission::where(array('user_id' => $id, 'status' => 1))->get();
        foreach ($permission as $p){
            if(array_key_exists($p->module, $access_module)) {
                $this->{$p->module} = true;
            }
        }
    }

    public function resetFieldsAccess(){
        $this->create_order = '';
        $this->edit_order = '';
        $this->order_status = '';
        $this->assign_driver = '';
        $this->rewash_request = '';
        $this->cancel_request = '';
        $this->garment_status_screen = '';
        $this->packing_sticker = '';
        $this->customer = '';
        $this->add_customer = '';
        $this->assign_membership = '';
        $this->manage_category = '';
        $this->manage_service_type = '';
        $this->manage_garments = '';
        $this->manage_addons = '';
        $this->manage_rate_chart = '';
        $this->expense_list = '';
        $this->expense_category = '';
        $this->daily_report = '';
        $this->order_report = '';
        $this->sales_report = '';
        $this->expense_report = '';
        $this->tax_report = '';
        $this->garment_report = '';
        $this->customer_order_report = '';
        $this->customer_history_report = '';
        $this->workstation_report = '';
        $this->outstanding_report = '';
        $this->stock_report = '';
        $this->rewash_report = '';
        $this->service_report = '';
        $this->outlet_report = '';
        $this->financial_year = '';
        $this->mail_settings = '';
        $this->master_settings = '';
        $this->report_settings = '';
        $this->file_tools = '';
        $this->sms_settings = '';
        $this->membership = '';
        $this->manage_user = '';
        $this->manage_outlet = '';
        $this->manage_workstation = '';
        $this->manage_brand = '';
        $this->manage_voucher = '';
        $this->manage_delivery = '';
        $this->manage_promotion = '';
        $this->user_permission = '';
    }

    public function storeaccess()
    {
        $this->editMode = true;
        $id = $this->staff_id;
        $staff = User::where('id', $id)->first();
        $access_module = $this->access_module[$staff->user_type];
        if($access_module) {
            foreach ($access_module as $keyam => $am) {
                $permission = UserPermission::where(array('user_id' => $id, 'module' => $keyam))->first();
                if ($this->{$keyam} == true) {
                    $data = array(
                        "user_id" => $id,
                        "module" => $keyam,
                        "status" => 1
                    );

                    UserPermission::updateOrCreate([
                        "user_id" => $id,
                        "module" => $keyam,
                    ], $data);
                } elseif ($permission){
                    UserPermission::where(array('user_id' => $id, 'module' => $keyam))->update(['status' => 0]);
                }
            }
        }

        $this->emit('closemodal');
        $this->dispatchBrowserEvent('alert', ['type' => 'success',  'message' => 'Roles assigned successfully!']);
    }

}
