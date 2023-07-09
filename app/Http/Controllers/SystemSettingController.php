<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Utils\Util;
use App\Utils\OrganizationUtil;
use App\Models\Session;
use App\Models\Currency;

class SystemSettingController extends Controller
{
    protected $commonUtil;
    protected $organizationUtil;


    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(Util $commonUtil, OrganizationUtil $organizationUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->organizationUtil = $organizationUtil;
        $this->theme_colors = [
            'blue' => 'Blue',
            'black' => 'Black',
            'purple' => 'Purple',
            'green' => 'Green',
            'red' => 'Red',
            'yellow' => 'Yellow',
            'blue-light' => 'Blue Light',
            'black-light' => 'Black Light',
            'purple-light' => 'Purple Light',
            'green-light' => 'Green Light',
            'red-light' => 'Red Light',
        ];

        $this->mailDrivers = [
                'smtp' => 'SMTP',
                'sendmail' => 'Sendmail',
                'mailgun' => 'Mailgun',
                'mandrill' => 'Mandrill',
                'ses' => 'SES',
                'sparkpost' => 'Sparkpost'
            ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('general_settings.view')) {
            abort(403, 'Unauthorized action.');
        }
        $sessions = Session::forDropdown();
        $month_list=$this->commonUtil->getMonthList();
        $currencies = $this->commonUtil->allCurrencies();
        $timezone_list = $this->commonUtil->allTimeZones();
        $system_settings_id = session()->get('user.system_settings_id');
        $general_settings=SystemSetting::where('id', $system_settings_id)->first();
        $email_settings = empty($general_settings->email_settings) ? $this->organizationUtil->defaultEmailSettings() : $general_settings->email_settings;

        $sms_settings = empty($general_settings->sms_settings) ? $this->organizationUtil->defaultSmsSettings() : $general_settings->sms_settings;
        $common_settings = !empty($general_settings->common_settings) ? $general_settings->common_settings : [];
        $date_formats = SystemSetting::date_formats();
        $mail_drivers = $this->mailDrivers;
        $theme_colors = $this->theme_colors;
        return view('admin.global_configuration.global_configuration')->with(compact('theme_colors','mail_drivers','email_settings','common_settings','sms_settings','timezone_list', 'date_formats', 'month_list', 'currencies', 'general_settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('general_settings.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
        $validated = $request->validate([
                'org_name' => 'required'
            ], [
                'org_name.required' => 'Name is required',
            
        ]);
        $system_details = $request->only(['org_name','org_short_name','tag_line','org_address','page_header_logo','id_card_logo','org_contact_number','currency_id','currency_symbol_placement','start_date',
        'email_settings','sms_settings','ref_no_prefixes', 'theme_color','enable_tooltip','date_format','time_format','time_zone','start_month']);


        //upload logo
        $org_favicon = $this->organizationUtil->uploadFile($request, 'org_favicon', 'business_logos', 'image');
        if (!empty($org_favicon)) {
            $system_details['org_favicon'] = $org_favicon;
        }
        $org_logo = $this->organizationUtil->uploadFile($request, 'org_logo', 'business_logos', 'image');
        if (!empty($org_logo)) {
            $system_details['org_logo'] = $org_logo;
        }
        $page_header_logo = $this->organizationUtil->uploadFile($request, 'page_header_logo', 'business_logos', 'image');
        if (!empty($page_header_logo)) {
            $system_details['page_header_logo'] = $page_header_logo;
        }
        $id_card_logo = $this->organizationUtil->uploadFile($request, 'id_card_logo', 'business_logos', 'image');
        if (!empty($id_card_logo)) {
            $system_details['id_card_logo'] = $id_card_logo;
        }
        if (!empty($system_details['start_date'])) {
            $system_details['start_date'] = $this->organizationUtil->uf_date($system_details['start_date']);
        }
        //dd($request->input('common_settings'));
        $system_details['common_settings'] = !empty($request->input('common_settings')) ? $request->input('common_settings') : [];
        $system_setting = SystemSetting::where('id', 1)->first();
        $system_setting->fill($system_details);
        $system_setting->save();
        //update session data
        $request->session()->put('system_details', $system_setting);

        //Update Currency details
        $currency = Currency::find($system_setting->currency_id);
        $request->session()->put('currency', [
                     'id' => $currency->id,
                     'code' => $currency->code,
                     'symbol' => $currency->symbol,
                     'thousand_separator' => $currency->thousand_separator,
                     'decimal_separator' => $currency->decimal_separator,
                     ]);
          
            $output = ['success' => 1,
                     'msg' => __('english.updated_success')
                 ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
     
            $output = ['success' => 0,
                     'msg' => __('english.something_went_wrong')
                 ];
        }
        return redirect('setting')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Handles the testing of sms configuration
     *
     * @return \Illuminate\Http\Response
     */
    public function testSmsConfiguration(Request $request)
    {
        try {
            $sms_settings = $request->input();
            
            $data = [
                'sms_settings' => $sms_settings,
                'mobile_number' => $sms_settings['test_number'],
                'sms_body' => 'This is a test SMS',
            ];
            if (!empty($sms_settings['test_number'])) {
                $response = $this->organizationUtil->sendSms($data);
            } else {
                $response = __('english.test_number_is_required');
            }

            $output = [
                'success' => true,
                'msg' => $response
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            $output = [
                'success' => 0,
                'msg' => $e->getMessage()
            ];
        }

        return $output;
    }
}
