<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassTimeTablePeriod;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
use App\Models\Exam\ExamSubjectResult;
use App\Models\Exam\ExamGrade;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\ClassSection;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Session;
use App\Utils\Util;
use Carbon;
use App\Models\Barcode;
use Yajra\DataTables\Facades\DataTables;
use DB;

class ExamMarkController extends Controller
{
          /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
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
        
        return view('Examination.exam_mark.create_mark_print')->with(compact('campuses','sessions','grades'));
    }
    public function markEnteryPrint(Request $request)
    {
        if (!auth()->user()->can('mark_entry_print.print')) {
            abort(403, 'Unauthorized action.');
        }
       try {
            
        $input = $request->input();
        $subs=ExamSubjectResult::with(['student'=> function($query) {
            $query->orderBy('id', 'DESC');
        }
     ,'campuses','session','current_class','current_class_section','exam_create','exam_create.term','subject_name','teacher'])
         ->where('session_id', $input['session_id'])
         ->where('exam_create_id', $input['exam_create_id'])
         ->where('subject_id', $input['subject_id'])
         ->where('campus_id', $input['campus_id'])
         ->where('class_id', $input['class_id'])
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
        
        return view('Examination.exam_mark.create')->with(compact('campuses','sessions','grades'));
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
           $subs=ExamSubjectResult::with(['student'=> function($query) {
               $query->orderBy('id', 'DESC');
           }
        ,'campuses','session','current_class','current_class_section','exam_create','exam_create.term','subject_name','teacher'])
            ->where('session_id', $input['session_id'])
            ->where('exam_create_id', $input['exam_create_id'])
            ->where('subject_id', $input['subject_id'])
            ->where('campus_id', $input['campus_id'])
            ->where('class_id', $input['class_id'])
            ->where('class_section_id', $input['class_section_id'])->orderBy('student_id', 'ASC')->get();
            if ($subs->isEmpty()) {
                $output = ['success' => false,
                'msg' => __("english.this_subject_has_no_data")
            ];
                return redirect()->back()->with('status', $output);
            }
            $campus_id=$input['campus_id'];
            $class_id=$input['class_id'];
            $class_section_id=$input['class_section_id'];
            $session_id=$input['session_id'];
            $subject_id=$input['subject_id'];
            $exam_create_id=$input['exam_create_id'];

           $system_settings_id = session()->get('user.system_settings_id');
           $campuses=Campus::forDropdown();
           $sessions=Session::forDropdown(false, false);
           $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
           $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
           $classSubject = SubjectTeacher::forDropdown($input['class_id'],$input['class_section_id']);
           $terms=ExamCreate::forDropdown($input['campus_id'],$input['session_id']);
           $grades=ExamGrade::forDropdown();
           $json_grades=ExamGrade::get();
           //dd($json_grades[0]['id']);
           return view('Examination.exam_mark.mark_entry')->with(compact('campuses','sessions','classes','sections','grades','json_grades','terms','campus_id','class_id','exam_create_id','class_section_id','session_id','subject_id','classSubject','subs'));

       

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

        foreach($request->input(['marks']) as $mark){
            $sub=ExamSubjectResult::findOrFail($mark['subject_result_id']);
            if (!empty($mark['is_absent'])) {
                $sub->is_attend=1;
                $sub->save();
            }else{
                if (!empty($mark['obtain_theory_mark'])) {
                    $sub->obtain_theory_mark=$mark['obtain_theory_mark'];
                }
                if(!empty($mark['obtain_parc_mark'])){
                    $sub->obtain_parc_mark=$mark['obtain_parc_mark'];
                }
                $sub->total_obtain_mark=$mark['obtain_total_mark'];
                $sub->grade_id=$mark['grade_id'];
                $sub->remark=$mark['remark'];
                $obtain_percentage=($mark['obtain_total_mark']*100)/$sub->total_mark;
                $sub->obtain_percentage=$obtain_percentage;
                $sub->save();
            }
            $exam_allocation=ExamAllocation::findOrFail($sub->exam_allocation_id);
            $subject=ExamSubjectResult::where('exam_allocation_id', $exam_allocation->id)->get();
            $total_mark=0;
            $total_obtain_mark=0;
            foreach ($subject as $subj) {
                $total_mark+=$subj->total_mark;
                $total_obtain_mark+=$subj->total_obtain_mark;
            }
          $exam_allocation->total_mark=$total_mark;
          $exam_allocation->obtain_mark=$total_obtain_mark;
          $final_percentage=($total_obtain_mark*100)/$total_mark;
          $exam_allocation->final_percentage=$final_percentage;
          $grades=ExamGrade::get();
          foreach ($grades as $grade) {
              if ($final_percentage >= $grade->percentag_from && $final_percentage <= $grade->percentage_to) {
                $exam_allocation->grade_id=$grade->id;
                $exam_allocation->remark=$grade->remark;
              }
          }
          $exam_allocation->save();
        }
        
        $input = $request->only(['campus_id', 'class_id','class_section_id','session_id','exam_create_id','subject_id']);    
        $this->schoolPosition($input['campus_id'],$input['session_id'],$input['exam_create_id']);
        $this->classPosition($input['campus_id'],$input['class_id'],$input['session_id'],$input['exam_create_id']);
        $this->classSectionPosition($input['campus_id'],$input['class_id'],$input['class_section_id'],$input['session_id'],$input['exam_create_id']);
        $this->position_in_subject($input['campus_id'],$input['class_id'],$input['session_id'],$input['exam_create_id'],$input['subject_id']);
        //SELECT `total_mark`, `obtain_mark`, FIND_IN_SET( `obtain_mark`, ( SELECT GROUP_CONCAT( DISTINCT `obtain_mark` ORDER BY `obtain_mark` DESC ) FROM `exam_allocations` ) ) AS rank FROM `exam_allocations`;
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
    return redirect('exam/mark/create')->with('status', $output);

}


    }
  
    
   


    
    
    
    /**
     * Returns the content for the receipt
     *
     * @param  int  $business_id
     * @param  int  $location_id
     * @param  int  $transaction_id
     * @param string $printer_type = null
     *
     * @return array
     */
    private function schoolPosition($campus_id, $session_id,$exam_create_id)
    {
        $top_students = DB::table('exam_allocations')
        ->select('id','total_mark','obtain_mark','final_percentage')
        ->selectRaw("FIND_IN_SET( final_percentage, ( SELECT GROUP_CONCAT( DISTINCT `final_percentage` ORDER BY `final_percentage` DESC ) FROM exam_allocations )) as rank ")
        ->where('campus_id',$campus_id)
        ->where('session_id',$session_id)
        ->where('exam_create_id',$exam_create_id)
        ->orderBy('rank','ASC')
        ->get();
       // dd($top_students);
        foreach ($top_students as $key => $top_student) {
            $exam_allocation=ExamAllocation::findOrFail($top_student->id);
            $exam_allocation->merit_rank_in_school=$top_student->rank;
            $exam_allocation->save();
        }
    }
    private function classPosition($campus_id,$class_id, $session_id,$exam_create_id)
    {

        $top_students = DB::table('exam_allocations')
        ->select('id','total_mark','obtain_mark')
        ->selectRaw("FIND_IN_SET( obtain_mark, ( SELECT GROUP_CONCAT( DISTINCT `obtain_mark` ORDER BY `obtain_mark` DESC ) FROM exam_allocations Where 
        campus_id=".$campus_id." And  class_id=".$class_id." And  session_id=".$session_id." And  exam_create_id=".$exam_create_id."  )) as rank ")
        ->where('campus_id',$campus_id)
        ->where('class_id',$class_id)
        ->where('session_id',$session_id)
        ->where('exam_create_id',$exam_create_id)
        ->orderBy('rank','ASC')
        ->get();
        foreach ($top_students as $key => $top_student) {
            $exam_allocation=ExamAllocation::findOrFail($top_student->id);
            $exam_allocation->class_position=$top_student->rank;
            $exam_allocation->save();
        }
    }
    private function classSectionPosition($campus_id,$class_id,$class_section_id, $session_id,$exam_create_id)
    {
        $top_students = DB::table('exam_allocations')
        ->select('id','total_mark','obtain_mark')
        ->selectRaw("FIND_IN_SET( obtain_mark, ( SELECT GROUP_CONCAT( DISTINCT `obtain_mark` ORDER BY `obtain_mark` DESC ) FROM exam_allocations Where 
        campus_id=".$campus_id." And  class_id=".$class_id." And  class_section_id=".$class_section_id." And  session_id=".$session_id." And  exam_create_id=".$exam_create_id."  )) as rank ")
        ->where('campus_id',$campus_id)
        ->where('class_id',$class_id)
        ->where('class_section_id',$class_section_id)
        ->where('session_id',$session_id)
        ->where('exam_create_id',$exam_create_id)
        ->orderBy('rank','ASC')
        ->get();
        foreach ($top_students as $key => $top_student) {
            $exam_allocation=ExamAllocation::findOrFail($top_student->id);
            $exam_allocation->class_section_position=$top_student->rank;
            $exam_allocation->save();
        }
    }
    private function position_in_subject($campus_id,$class_id, $session_id,$exam_create_id,$subject_id)
    {
        $top_students = DB::table('exam_subject_results')
        ->select('id','total_mark','total_obtain_mark')
        ->selectRaw("FIND_IN_SET( total_obtain_mark, ( SELECT GROUP_CONCAT( DISTINCT `total_obtain_mark` ORDER BY `total_obtain_mark` DESC ) FROM exam_subject_results Where 
        campus_id=".$campus_id." And  class_id=".$class_id." And  subject_id=".$subject_id." And  session_id=".$session_id." And  exam_create_id=".$exam_create_id."  )) as rank ")
        ->where('campus_id',$campus_id)
        ->where('class_id',$class_id)
        ->where('session_id',$session_id)
        ->where('exam_create_id',$exam_create_id)
        ->where('subject_id',$subject_id)
        ->orderBy('rank','ASC')
        ->get();
        foreach ($top_students as $key => $top_student) {
            $exam_subject=ExamSubjectResult::findOrFail($top_student->id);
            $exam_subject->position_in_subject=$top_student->rank;
            $exam_subject->save();
        }
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
        $output['html_content'] = view('Examination.exam_mark.mark_print', compact('subs'))->render();
        
        return $output;
    }
   
}