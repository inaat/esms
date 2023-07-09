<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Session;
use App\Models\Student;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
use App\Models\Exam\ExamSubjectResult;
use App\Models\Exam\ExamGrade;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\ClassSection;
use App\Models\Classes;

use App\Utils\NotificationUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use DB;

class PromotionController extends Controller
{
    public function __construct(Util $util, NotificationUtil $notificationUtil)
    {
        $this->util = $util;
        $this->notificationUtil = $notificationUtil;
    }

    public function index()
    {
        if (!auth()->user()->can('promotion.without_exam')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, true);

        return view('Examination.promotion.index')->with(compact('campuses', 'sessions'));
    }
    public function withoutGetStudentList(Request $request)
    {
        if (!auth()->user()->can('promotion.without_exam')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->input();
        $campus_id = $input['campus_id'];
        $class_id = $input['class_id'];
        $class_section_id = $input['class_section_id'];
        // dd($input);
        $students = Student::where('campus_id', $campus_id)
            ->where('status', 'active')
            ->where('current_class_id', $class_id)
            ->where('current_class_section_id', $class_section_id)
            ->get();
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        $sessions=Session::forDropdown(false, false);

        return view('Examination.promotion.without_get_student_list')->with(compact('campuses', 'sessions', 'classes', 'sections', 'campus_id', 'class_id', 'class_section_id', 'students'));
    }
 
    public function create()
    {
        if (!auth()->user()->can('promotion.with_exam')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, true);

        return view('Examination.promotion.create')->with(compact('campuses', 'sessions'));
    }

    public function getStudentList(Request $request)
    {
        if (!auth()->user()->can('promotion.with_exam')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->input();
        $campus_id = $input['campus_id'];
        $session_id = $input['session_id'];
        $class_id = $input['class_id'];
        $class_section_id = $input['class_section_id'];
        $exam_create_id = $input['exam_create_id'];

        //dd($request->input());
        $students = Student::leftjoin('exam_allocations', 'exam_allocations.student_id', '=', 'students.id')
            ->where('students.status', 'active')
            ->where('students.campus_id', $campus_id)
            ->where('students.cur_session_id', $session_id)
            ->where('students.current_class_id', $class_id)
            ->where('students.current_class_section_id', $class_section_id)
            ->where('exam_allocations.exam_create_id', $exam_create_id)
            ->select(
                'students.id',
                'students.roll_no',
                DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
                'students.father_name',
                'students.birth_date',
                'exam_allocations.final_percentage'
            )
            ->get();
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        $terms=ExamCreate::forDropdown($input['campus_id'], $input['session_id']);
        
        return view('Examination.promotion.student_list')->with(compact('campuses', 'sessions', 'classes', 'sections', 'terms', 'campus_id', 'class_id', 'exam_create_id', 'class_section_id', 'session_id', 'students'));
    }

    public function store(Request $request)
    {
if (!auth()->user()->can('promotion.with_exam')) {
    abort(403, 'Unauthorized action.');
}
        
        try {
           

     
            DB::beginTransaction();
        $input = $request->input();

        foreach ($input['promotion'] as $key => $value) {
            if ($value['promote']=='continue') {
                $student = Student::find($value['student_id']);
                $student->campus_id=$input['campus_id'];
                $student->cur_session_id=$input['session_id'];
                $student->current_class_id=$input['class_id'];
                $student->current_class_section_id=$input['class_section_id'];            
                $student->save();
            }
        }
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
    return redirect('students')->with('status', $output);

    }
    public function passOutCreate()
    {
        if (!auth()->user()->can('promotion.pass_out')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, true);

        return view('Examination.promotion.pass_out_create')->with(compact('campuses', 'sessions'));
    }
    public function passOutPost(Request $request)
    {
        if (!auth()->user()->can('promotion.pass_out')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->input();
        $campus_id = $input['campus_id'];
        $class_id = $input['class_id'];
        $class_section_id = $input['class_section_id'];
        // dd($input);
        $students = Student::where('campus_id', $campus_id)
            ->where('status', 'active')
            ->where('current_class_id', $class_id)
            ->where('current_class_section_id', $class_section_id)
            ->get();
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        $sessions=Session::forDropdown(false, false);

        return view('Examination.promotion.pass_out_get_student_list')->with(compact('campuses', 'sessions', 'classes', 'sections', 'campus_id', 'class_id', 'class_section_id', 'students'));
    }
    public function passOut(Request $request)
    {
        if (!auth()->user()->can('promotion.pass_out')) {
            abort(403, 'Unauthorized action.');
        }
        try {
           

     
            DB::beginTransaction();
        $input = $request->input();
        //dd($input);

        foreach ($input['promotion'] as $key => $value) {
            if ($value['promote']=='continue') {
                $student = Student::find($value['student_id']);
                $student->status = $input['status'];
                $student->save();
            }
        }
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
    return redirect('students')->with('status', $output);

    }


    
    public function withoutExamPromotion(Request $request)
    {
        if (!auth()->user()->can('promotion.without_exam')) {
            abort(403, 'Unauthorized action.');
        }
        try {
           

     
            DB::beginTransaction();
        $input = $request->input();

        foreach ($input['promotion'] as $key => $value) {
            if ($value['promote']=='continue') {
                $student = Student::find($value['student_id']);
                $student->campus_id=$input['campus_id'];
                $student->cur_session_id=$input['session_id'];
                $student->current_class_id=$input['class_id'];
                $student->current_class_section_id=$input['class_section_id'];            
                $student->save();
            }
        }
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
    return redirect('students')->with('status', $output);

    }
}

