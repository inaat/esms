<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassTimeTablePeriod;
use App\Models\RoutineTest;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
use App\Models\Exam\ExamGrade;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\ClassSection;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Session;
use App\Models\Student;
use App\Utils\StudentUtil;
use Carbon;
use App\Models\Barcode;
use Yajra\DataTables\Facades\DataTables;
use DB;

class RoutineTestController extends Controller
{
          /**
     * Constructor
     *
     * @param Util $studentUtil
     * @return void
     */
    public function __construct(StudentUtil $studentUtil)
    {
        $this->studentUtil = $studentUtil;
    }
         /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('exam_mark_entry.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);
        $grades=ExamGrade::forDropdown();
        
        return view('Examination.routine_test.create_mark_print')->with(compact('campuses','sessions','grades'));
    }
    public function markEnteryPrint(Request $request)
    {
        if (!auth()->user()->can('mark_entry_print.print')) {
            abort(403, 'Unauthorized action.');
        }
       try {
            
        $input = $request->input();
        $subs=RoutineTest::with(['student','campuses','session','current_class','current_class_section','subject_name','teacher'])
            ->where('session_id', $input['session_id'])
            ->whereDate('date', $this->studentUtil->uf_date($input['date']))
            ->where('subject_id', $input['subject_id'])
             ->where('campus_id', $input['campus_id'])
             ->where('class_id', $input['class_id'])
             ->where('type', $input['type'])
            ->where('class_section_id', $input['class_section_id'])->orderBy('student_id', 'ASC')->get();
        
        $receipt = $this->receiptContent($subs);
      

        if (!empty($receipt)) {
            $output = ['success' => 1, 'receipt' => $receipt];
        }
       
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
    }

    return response($output);
}

    public function create()
    {
        if (!auth()->user()->can('exam_mark_entry.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);
        $grades=ExamGrade::forDropdown();
        
        return view('Examination.routine_test.create')->with(compact('campuses','sessions','grades'));
    }
    //   /**
    //  * Store a newly created resource in storage.
    //  * @param Request $request
    //  * @return Response
    //  */
    public function store(Request $request)
    {
       
        if (!auth()->user()->can('exam_mark_entry.create')) {
            abort(403, 'Unauthorized action.');
        }
          
           $input = $request->input();
           $system_settings_id = session()->get('user.system_settings_id');


           $subs=RoutineTest::with(['student','campuses','session','current_class','current_class_section','subject_name','teacher'])
            ->where('session_id', $input['session_id'])
            ->whereDate('date', $this->studentUtil->uf_date($input['date']))
            ->where('subject_id', $input['subject_id'])
             ->where('campus_id', $input['campus_id'])
             ->where('class_id', $input['class_id'])
             ->where('type', $input['type'])
            ->where('class_section_id', $input['class_section_id'])->orderBy('student_id', 'ASC')->get();
          //  dd($subs);
            $already_exists = [];
            if (!empty($subs)) {
                foreach ($subs as $key => $value) {
                    array_push($already_exists, $value->student_id);
                }
            }
            $campus_id=$input['campus_id'];
            $class_id=$input['class_id'];
            $class_section_id=$input['class_section_id'];
            $session_id=$input['session_id'];
            $subject_id=$input['subject_id'];
            $date=$this->studentUtil->uf_date($input['date']);
            $type=$input['type'];
            $students=Student::where('campus_id',$campus_id)
                    ->where('current_class_id', $class_id)
                    //->where('cur_session_id', $session_id)
                    ->where('current_class_section_id', $class_section_id)
                    ->where('status','active')
                    ->whereNotIn('id', $already_exists)->get();
            //dd($students);


            //$this->studentUtil->getStudentList($system_settings_id, $class_id, $class_section_id, 'active',$already_exists);
           $campuses=Campus::forDropdown();
           $sessions=Session::forDropdown(false, false);
           $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
           $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
           $classSubject = SubjectTeacher::forDropdown($input['class_id'],$input['class_section_id']);
           $grades=ExamGrade::forDropdown();
           $json_grades=ExamGrade::get();
           //dd($json_grades[0]['id']);
           return view('Examination.routine_test.mark_entry')->with(compact('students','type','campuses','sessions','classes','sections','grades','json_grades','campus_id','class_id','date','class_section_id','session_id','subject_id','classSubject','subs'));

       

    }

    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
    
       
        
    }
    public function postSubjectResult(Request $request)
    {
        if (!auth()->user()->can('exam_mark_entry.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
           
            DB::beginTransaction();
        
            $input = $request->except('_token');
        //dd($request->input());
        $subject_teacher= SubjectTeacher::where('campus_id', $input['campus_id'])->where('class_id', $input['class_id'])
        ->where('class_section_id', $input['class_section_id'])
        ->where('subject_id', $input['subject_id'])
        ->first();
       
        $date=$this->studentUtil->uf_date($input['date']);
        foreach($request->input(['marks']) as $mark){
          //  dd($mark);

            $final_percentage=($mark['obtain_total_mark']*100)/$mark['total_mark'];
            $is_attend=0;
            if (!empty($mark['is_absent'])) {
                $is_attend=1;
            }
            $data=[
                'date'=>$date,
                'session_id'=>$input['session_id'],
                'student_id'=>$mark['student_id'],
                'campus_id'=>$input['campus_id'],
                'class_id'=>$input['class_id'],
                'class_section_id'=>$input['class_section_id'],
                'subject_id'=>$input['subject_id'],
                'teacher_id'=>$subject_teacher->teacher_id,
                'type'=>$input['type'],
                'obtain_mark'=>$mark['obtain_total_mark'],
                'total_mark'=>$mark['total_mark'],
                'is_attend'=>$is_attend,
                'obtain_percentage'=>$final_percentage,
                'grade_id'=>$mark['grade_id'],
                'remark'=>$mark['remark']
            ];
            $check=RoutineTest::where('session_id', $input['session_id'])
            ->whereDate('date', $this->studentUtil->uf_date($input['date']))
            ->where('subject_id', $input['subject_id'])
            ->where('student_id', $mark['student_id'])
             ->where('campus_id', $input['campus_id'])
             ->where('class_id', $input['class_id'])
             ->where('type', $input['type'])
            ->where('class_section_id', $input['class_section_id'])->orderBy('student_id', 'ASC')->first();
           // dd($check);
            if(!empty($check)){
                $check->update($data);
            }else{
            RoutineTest::create($data);
            }
        }
           
        //dd(550);
        DB::commit();
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
    $user = \Auth::user();
if ($user->user_type == 'teacher') {
    return redirect('/teacher_mark_entry')->with('status', $output);

}
else{
    return redirect('exam/routine-test/create')->with('status', $output);

}


    }
  
    
    public function studentCreate()
    {
        if (!auth()->user()->can('exam_mark_entry.create')) {
            abort(403, 'Unauthorized action.');
        }
        
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);
        $grades=ExamGrade::forDropdown();
        
        return view('Examination.routine_test.single_print_create')->with(compact('campuses','sessions','grades'));
    }


    public function routinePrint(Request $request)
    {
        if (!auth()->user()->can('mark_entry_print.print')) {
            abort(403, 'Unauthorized action.');
        }
       //dd($request->input());
       try {
            
        $input = $request->input();
        $_date=explode(' - ', $input['list_filter_date_range']);
        $start_date=Carbon::parse($_date[0])->format('Y-m-d');
        $end_date=Carbon::parse($_date[1])->format('Y-m-d');
        $subs=RoutineTest::with(['student','campuses','session','current_class','current_class_section','subject_name','teacher'])
           // ->where('session_id', $input['session_id'])
           // ->whereDate('date', $this->studentUtil->uf_date($input['date']))
           // ->where('subject_id', $input['subject_id'])
             ->where('campus_id', $input['campus_id'])
             ->where('class_id', $input['class_id'])
             //->where('type', $input['type'])
            ->where('class_section_id', $input['class_section_id']);
            if (!empty($start_date) && !empty($end_date)) {
                $subs->whereDate('date', '>=', $start_date)
                        ->whereDate('date', '<=', $end_date);
            }
        if(!empty($input['subject_id'])){
            $subs->where('subject_id', $input['subject_id']);
        }
        if(!empty($input['sibiling_student_id'])){
            $subs->where('student_id', $input['sibiling_student_id']);
        }
        $subs=$subs->orderBy('class_id', 'ASC')->get();
        $receipt = $this->receiptContent1($subs);
      

        if (!empty($receipt)) {
            $output = ['success' => 1, 'receipt' => $receipt];
        }
       
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
    }

    return response($output);
}

    

    private function receiptContent1($subs)
    {
        $output = ['is_enabled' => false,
                    'print_type' => 'browser',
                    'html_content' => null,
                    'printer_config' => [],
                    'data' => []
                ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];
        $output['html_content'] = view('Examination.routine_test.report_routine_test_print', compact('subs'))->render();
        
        return $output;
    }

    private function receiptContent($subs)
    {
        $output = ['is_enabled' => false,
                    'print_type' => 'browser',
                    'html_content' => null,
                    'printer_config' => [],
                    'data' => []
                ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];
        $output['html_content'] = view('Examination.routine_test.mark_print', compact('subs'))->render();
        
        return $output;
    }
   
}