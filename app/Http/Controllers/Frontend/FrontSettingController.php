<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use Illuminate\Http\Request;
use App\Models\Frontend\FrontSetting;
use App\Utils\Util;

class FrontSettingController extends Controller
{
    protected $commonUtil;

    /**
    * Constructor
    *
    */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }
   public function index(){
    $front_settings= FrontSetting::first();

    return view('frontend.backend.setting.index')->with(compact('front_settings'));

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
                'school_name' => 'required'
            ], [
                'school_name.required' => 'Name is required',
            
        ]);
        
        $system_details = $request->only(['school_name','address','reg_no','email','phone_no',
        'logo_image','page_banner','main_color','hover_color','linear_gradient','facebook', 'youTube','instagram','linkedin','twitter','skype','facebook_embed','map_url']);


        //upload logo
     
        $logo_image = $this->commonUtil->uploadFile($request, 'logo_image', 'front_image', 'image');
        if (!empty($logo_image)) {
            $system_details['logo_image'] = $logo_image;
        }
        $page_banner = $this->commonUtil->uploadFile($request, 'page_banner', 'front_image', 'image');
        if (!empty($page_banner)) {
            $system_details['page_banner'] = $page_banner;
        }
        
        $system_setting = FrontSetting::first();
        $system_setting->fill($system_details);
        $system_setting->save();
        //update session data
        $request->session()->put('front_details', $system_setting);

   
          
            $output = ['success' => 1,
                     'msg' => __('english.updated_success')
                 ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
     
            $output = ['success' => 0,
                     'msg' => __('english.something_went_wrong')
                 ];
        }
        return redirect('front-settings')->with('status', $output);
    }

   
   
}
