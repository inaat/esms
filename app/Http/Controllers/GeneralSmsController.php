<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeekendHoliday;
use App\Models\Classes;
use App\Models\Campus;
use App\Models\Student;
use App\Models\ClassSection;
use App\Models\Attendance;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmAttendance;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon;
use App\Utils\Util;
use App\Utils\NotificationUtil;

class GeneralSmsController extends Controller
{
    public function __construct(Util $util, NotificationUtil $notificationUtil)
    {
        $this->util= $util;
        $this->notificationUtil= $notificationUtil;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //if PASSED IN PAST
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_sections =ClassSection::with(['classes','campuses'])->get();

        return view('general-sms.create', compact('class_sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $sms=Sms::get();
        // foreach ($sms as $sm) {
        //     $data=[
        //     'mobile_number' => $sm->mobile,
        //     'sms_body'=>"Hi here is Explainer School software marketing team are you interested in digitalize your school with school management system check out our software demo here http://127.0.0.1:8000     Contact Us:03428927305:  https://www.youtube.com/watch?v=JNaLGOx90d8&ab_channel=ExplaineRKhaN"
        //     ];
        //     $response=$this->notificationUtil->sendSmsOnWhatsapp($data);
        // }
        //         dd('send');
        try {
            $input = $request->only(['description','class_section','employee_include']);
        

         
            if (!empty($request->input('employee_include'))) {
                $input['employee_include']=1;
                $this->employee_attendance($input);
            }
            if (!empty($request->input('class_section'))) {
            $this->student_attendance($input);
            }


            $output = ['success' => true,
                            'msg' => __("english.added_success")
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return redirect('general-sms/create')->with('status', $output);
    }

   
    
  
    public function student_attendance($input)
    {
      
              
        $add_second=0;
            foreach ($input['class_section'] as $key => $section_id) {
                # code...
                $students=Student::where('current_class_section_id', $section_id)->where('status', 'active')->get();
                //dd($students);
                
                foreach ($students as $epe) {
                    $addSecond =  $addSecond +30;
                    $student= Student::where('id', $epe->id)->first();
 
                    $response=$this->notificationUtil->SendNotification(null, $student, null, $input['description'],$add_second);
                }
            }
        
    }
    public function employee_attendance($input)
    {
       
            $employees=HrmEmployee::where('status', 'active')->get();
            //dd($employee);
            $add_second=0;

            foreach ($employees as $std) {
                $addSecond =  $addSecond +30;
                $employee= HrmEmployee::where('id', $std->id)->first();
                $response=$this->notificationUtil->SendNotification(null, $employee, null, $input['description'],$add_second);
            }
        
    }
}
