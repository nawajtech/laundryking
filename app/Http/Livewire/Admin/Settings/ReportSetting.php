<?php
namespace App\Http\Livewire\Admin\Settings;
use Livewire\Component;
use App\Models\ReportSettings;
use App\Models\Translation;
use Livewire\WithFileUploads;
use App\Models\User;
use Image;
use Hash;
use Session;
use Auth;
class ReportSetting extends Component
{
    public $default_currency,$default_application_name,$daily_report,$service_report,$servicem_report,$stock_report,$stockm_report,$outstanding_report,$outstandingm_report,$outlet_report,$outletm_report,$workstation_report,$workstationm_report,$customer_report,$customerm_report,$garment_report,$garmentm_report,$tax_report,$order_report,$sales_report,$expense_report,$dailym_report,$taxm_report,$orderm_report,$salesm_report,$expensem_report;
    public $default_state,$default_city,$default_district,$default_zip_code,$default_address,$user,$email,$password,$default_logo,$default_favicon;
    public $old_favicon,$order_prefix,$old_logo,$default_printer=1,$status_scrn=1,$lang;
    use WithFileUploads;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.settings.report-settings');
    }
    /* set the rules */
    protected $rules = [
        'daily_report' => 'required',
        'service_report' => 'required',
        'stock_report' => 'required',
        'outstanding_report' => 'required',
        'workstation_report' => 'required',
        'outlet_report' => 'required',
        'customer_report' => 'required',
        'garment_report' => 'required',
        'tax_report' => 'required',
        'expense_report' => 'required',
        'sales_report' => 'required',
        'order_report' => 'required',
    ];
    /* set value at the time of render */
    public function mount()
    {
        $settings = new ReportSettings();
        $site = $settings->siteData();
        $this->daily_report = (isset($site['daily_report']) && !empty($site['daily_report'])) ? $site['daily_report'] : '';
        $this->service_report = (isset($site['service_report']) && !empty($site['service_report'])) ? $site['service_report'] : '';
        $this->stock_report = (isset($site['stock_report']) && !empty($site['stock_report'])) ? $site['stock_report'] : '';
        $this->outstanding_report = (isset($site['outstanding_report']) && !empty($site['outstanding_report'])) ? $site['outstanding_report'] : '';
        $this->workstation_report = (isset($site['workstation_report']) && !empty($site['workstation_report'])) ? $site['workstation_report'] : '';
        $this->outlet_report = (isset($site['outlet_report']) && !empty($site['outlet_report'])) ? $site['outlet_report'] : '';
        $this->customer_report = (isset($site['customer_report']) && !empty($site['customer_report'])) ? $site['customer_report'] : '';
        $this->garment_report = (isset($site['garment_report']) && !empty($site['garment_report'])) ? $site['garment_report'] : '';
        $this->tax_report = (isset($site['tax_report']) && !empty($site['tax_report'])) ? $site['tax_report'] : '';
        $this->expense_report = (isset($site['expense_report']) && !empty($site['expense_report'])) ? $site['expense_report'] : '';
        $this->sales_report = (isset($site['sales_report']) && !empty($site['sales_report'])) ? $site['sales_report'] : '';
        $this->order_report = (isset($site['order_report']) && !empty($site['order_report'])) ? $site['order_report'] : '';
        
        $site = $settings->sitesData();
        $this->dailym_report = (isset($site['daily_report']) && !empty($site['daily_report'])) ? $site['daily_report'] : '';
        $this->servicem_report = (isset($site['service_report']) && !empty($site['service_report'])) ? $site['service_report'] : '';
        $this->stockm_report = (isset($site['stock_report']) && !empty($site['stock_report'])) ? $site['stock_report'] : '';
        $this->outstandingm_report = (isset($site['outstanding_report']) && !empty($site['outstanding_report'])) ? $site['outstanding_report'] : '';
        $this->workstationm_report = (isset($site['workstation_report']) && !empty($site['workstation_report'])) ? $site['workstation_report'] : '';
        $this->outletm_report = (isset($site['outlet_report']) && !empty($site['outlet_report'])) ? $site['outlet_report'] : '';
        $this->customerm_report = (isset($site['customer_report']) && !empty($site['customer_report'])) ? $site['customer_report'] : '';
        $this->garmentm_report = (isset($site['garment_report']) && !empty($site['garment_report'])) ? $site['garment_report'] : '';
        $this->taxm_report = (isset($site['tax_report']) && !empty($site['tax_report'])) ? $site['tax_report'] : '';
        $this->expensem_report = (isset($site['expense_report']) && !empty($site['expense_report'])) ? $site['expense_report'] : '';
        $this->salesm_report = (isset($site['sales_report']) && !empty($site['sales_report'])) ? $site['sales_report'] : '';
        $this->orderm_report = (isset($site['order_report']) && !empty($site['order_report'])) ? $site['order_report'] : '';

        
        if(session()->has('selected_language'))
        {   /*if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
      $user = User::findOrFail(1);
        $this->email = $user->email;
        $this->user=$user;
    }
        /* save the report settings data */
    public function save() {
        $settings = new ReportSettings();
        $site = $settings->siteData();
        $site['daily_report'] = $this->daily_report;
        $site['service_report'] = $this->service_report;
        $site['stock_report'] = $this->stock_report;
        $site['outstanding_report'] = $this->outstanding_report;
        $site['workstation_report'] = $this->workstation_report;
        $site['outlet_report'] = $this->outlet_report;
        $site['customer_report'] = $this->customer_report;
        $site['garment_report'] = $this->garment_report;
        $site['tax_report'] = $this->tax_report;
        $site['expense_report'] = $this->expense_report;
        $site['sales_report'] = $this->sales_report;
        $site['order_report'] = $this->order_report;
        foreach ($site as $key => $value) {
            ReportSettings::updateOrCreate(['report_name' => $key], ['outlet_access' => $value]);
        }
        $site = $settings->sitesData();
        $site['daily_report'] = $this->dailym_report;
        $site['service_report'] = $this->servicem_report;
        $site['stock_report'] = $this->stockm_report;
        $site['outstanding_report'] = $this->outstandingm_report;
        $site['workstation_report'] = $this->workstationm_report;
        $site['outlet_report'] = $this->outletm_report;
        $site['customer_report'] = $this->customerm_report;
        $site['garment_report'] = $this->garmentm_report;
        $site['tax_report'] = $this->taxm_report;
        $site['expense_report'] = $this->expensem_report;
        $site['sales_report'] = $this->salesm_report;
        $site['order_report'] = $this->orderm_report;
        foreach ($site as $key => $value) {
            ReportSettings::updateOrCreate(['report_name' => $key], ['manager_access' => $value]);
        }
        $user = User::findOrFail($this->user->id);
        $user->email = $this->email;
        if($this->password)
        {
            $password = Hash::make($this->password);
            $user->password = $password;
        }
        $user->save();
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Report Settings Updated Successfully!']);
    }
}