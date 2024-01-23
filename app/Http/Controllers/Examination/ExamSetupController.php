<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam\ExamTerm;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamSubjectResult;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassSubject;
use App\Models\Classes;
use App\Models\Campus;
use App\Models\Student;
use App\Models\ClassSection;
use App\Models\Attendance;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon;
use App\Utils\Util;
use App\Utils\NotificationUtil;

class ExamSetupController extends Controller
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
    public function index()
    {
        if (!auth()->user()->can('exam_setup.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $exam_creates =ExamCreate::leftJoin('campuses', 'exam_creates.campus_id', '=', 'campuses.id')
                             ->leftJoin('exam_terms', 'exam_creates.exam_term_id', '=', 'exam_terms.id')
                             ->leftJoin('sessions', 'exam_creates.session_id', '=', 'sessions.id')
                             ->select(
                                 'exam_creates.id',
                                 'campuses.campus_name',
                                 'sessions.title',
                                 'exam_terms.name',
                                 'exam_creates.roll_no_type',
                                 'exam_creates.order_type',
                                 'exam_creates.start_from',
                                 'exam_creates.from_date',
                                 'exam_creates.to_date',
                                 'exam_creates.description'
                             );
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $exam_creates->whereIn('exam_creates.campus_id', $permitted_campuses);
            }
            $datatable = Datatables::of($exam_creates)
            ->addColumn(
                'action',
                function ($row) {
                    $html= '<div class="dropdown">
                         <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                         <ul class="dropdown-menu" style="">';


                    $html .= '<li><a href="' . action('Examination\ExamSetupController@edit', [$row->id]) . '" class="dropdown-item "><i class="fa-solid fa-person"></i> ' . __("english.missing_students") . '</a></li>';
                    $html .= '<li><a href="' . action('Examination\ExamSetupController@enrolledStudents', [$row->id]) . '" class="dropdown-item "><i class="fa-solid fa-arrow-right"></i> ' . __("english.enrolled_students") . '</a></li>';
                    $html .= '<li><a href="' . action('Examination\ExamSetupController@missingSubjects', [$row->id]) . '" class="dropdown-item "><i class="fa-solid fa-arrow-left"></i> ' . __("english.missing_subjects") . '</a></li>';
                    $html .= '<li><a href="' . action('Examination\ExamSetupController@enrolledSubjects', [$row->id]) . '" class="dropdown-item "><i class="fa-solid fa-arrow-left"></i> ' . __("english.enrolled_subjects") . '</a></li>';
                    $html .= '<li><a href="' . action('Examination\ExamSetupController@updateSubjectsMark', [$row->id]) . '" class="dropdown-item "><i class="fas fa-edit"></i> ' . __("english.update_subjects_marks") . '</a></li>';

                    /*$html .= '<li><a href="' . action('Examination\ExamSetupController@destroy', [$row->id]) . '" class="dropdown-item btn-danger delete_exam_setup_button"><i class="bx bxs-trash f-16 "></i> ' . __("english.delete") . '</a></li>';*/


                    $html .= '</ul></div>';

                    return $html;
                }
            )
            ->removeColumn('id');

            $rawColumns=['action','campus_name','title','name','roll_no_type','order_type','start_from','from_date','to_date','description'];
            //,'title','name','roll_no_type','order_type','start_from','to_date','description'];
            return $datatable->rawColumns($rawColumns)
                                   ->make(true);
        }

        return view('Examination.exam_setup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('exam_setup.view')) {
            abort(403, 'Unauthorized action.');
        }
        $class_sections =ClassSection::with(['classes'])->get();
        $exam_terms=ExamTerm::forDropdown();
        $campuses=Campus::forDropdown();


        return view('Examination.exam_setup.create', compact('class_sections', 'exam_terms', 'campuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('exam_setup.view')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            // dd($request);
            DB::beginTransaction();

            $input = $request->only(['campus_id','exam_term_id','roll_no_type','order_type','start_from']);
            $input['session_id']=$this->util->getActiveSession();
            $date=explode(' - ', $request->input('list_filter_date_range'));
            $start_date=Carbon::parse($date[0])->format('Y-m-d');
            $end_date=Carbon::parse($date[1])->format('Y-m-d');
            $input['from_date']=$start_date;
            $input['to_date']=$end_date;
            $start_from=$input['start_from'];
            $input['class_ids'] = !empty($request->input('class_ids')) ? $request->input('class_ids') : null;
            $check_exam_create=ExamCreate::where('exam_term_id', $input['exam_term_id'])->where('session_id', $input['session_id'])->first();

            if (empty($check_exam_create)) {
                $exam_create= ExamCreate::create($input);

                if (!empty($request->input('class_ids'))) {
                    $this->selectedClasses($request->input('class_ids'), $exam_create, $input);
                    DB::commit();
                    $output = ['success' => true,
                    'msg' => __("english.added_success")
                        ];
                } else {
                    $classes=Classes::where('campus_id', $input['campus_id'])->get();
                    foreach ($classes as $class) {
                        $students =Student::where('current_class_id', $class->id)->where('status', 'active')->get();
                        if (!$students->isEmpty()) {
                            foreach ($students as $student) {
                                $exam_roll_no=null;
                                if ($input['roll_no_type']=='custom_roll_no') {
                                    $exam_roll_no=$this->util->getExamRollNo($start_from);
                                    $start_from++;
                                }
                                $allocation= ExamAllocation::create([
                            'campus_id' => $student->campus_id,
                            'exam_create_id'=>$exam_create->id,
                            'session_id'=>$input['session_id'],
                            'class_id'=>$student->current_class_id,
                            'class_section_id'=>$student->current_class_section_id,
                            'student_id'=>$student->id,
                            'roll_type'=>$input['roll_no_type'],
                            'exam_roll_no'=>$exam_roll_no
                        ]);
                                $subject_teachers= SubjectTeacher::where('campus_id', $student->campus_id)->where('class_id', $student->current_class_id)
                                         ->where('class_section_id', $student->current_class_section_id)->get();

                                foreach ($subject_teachers as $student_teacher) {
                                    $subject_mark=ClassSubject::find($student_teacher->subject_id);
                                    $subject=[
                                        'campus_id' => $student->campus_id,
                                        'exam_create_id'=>$exam_create->id,
                                        'session_id'=>$input['session_id'],
                                        'class_id'=>$student->current_class_id,
                                        'class_section_id'=>$student->current_class_section_id,
                                        'student_id'=>$student->id,
                               'exam_allocation_id'=>$allocation->id,
                               'subject_id'=>$student_teacher->subject_id,
                               'teacher_id'=>$student_teacher->teacher_id,
                               'theory_mark'=>$subject_mark->theory_mark,
                               'parc_mark'=>$subject_mark->parc_mark,
                               'total_mark'=>$subject_mark->total,
                               'pass_percentage'=>$subject_mark->passing_percentage];
                                    ExamSubjectResult::create($subject);
                                }
                            }
                        }
                    }
                    $output = ['success' => true,
                    'msg' => __("english.added_success")
                ];
                    DB::commit();
                }
            } else {
                $output = ['success' => false,
                'msg' => __("english.already_exist")
            ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return redirect('exam/setup')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }


  /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('exam_setup.view')) {
            abort(403, 'Unauthorized action.');
        }
        $exam_create=ExamCreate::find($id);

        // if ($this->canBeEdited($exam_create->to_date==false)) {
        //     return back()
        //         ->with('status', ['success' => 0,
        //             'msg' => __('english.transaction_edit_not_allowed')]);
        // }
        $exam_allocation=ExamAllocation::where('exam_create_id', $id)->pluck('student_id');
        $students=Student::leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
        ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
         ->select(
             'campuses.campus_name',
             'c-class.title as current_class',
             'students.father_name',
             'students.roll_no',
             'students.admission_no',
             'students.gender',
             'students.id as id',
             'students.student_image',
             'students.admission_date',
             DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name")
         )->whereNotIn('students.id', $exam_allocation)->where('status', 'active')
         ->orderBy('c-class.id')->get();
        return view('Examination.exam_setup.edit', compact('exam_create', 'students'));
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
        if (!auth()->user()->can('exam_setup.view')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();

            $input = $request->input();
            $exam_create= ExamCreate::findOrFail($id);
            $start_from=$exam_create->start_from;
            foreach ($input['student_checked'] as $student_id) {
                $student=Student::find($student_id);

                $exam_roll_no=null;
                if ($exam_create->roll_no_type=='custom_roll_no') {
                    $exam_roll_no=$this->util->getExamRollNo($start_from);
                    $start_from++;
                }
                $allocation= ExamAllocation::create([
                'campus_id' => $student->campus_id,
                'exam_create_id'=>$exam_create->id,
                'session_id'=>$exam_create->session_id,
                'class_id'=>$student->current_class_id,
                'class_section_id'=>$student->current_class_section_id,
                'student_id'=>$student->id,
                'roll_type'=>$exam_create->roll_no_type,
                'exam_roll_no'=>$exam_roll_no
            ]);
                $subject_teachers= SubjectTeacher::where('campus_id', $student->campus_id)->where('class_id', $student->current_class_id)
                             ->where('class_section_id', $student->current_class_section_id)->get();

                foreach ($subject_teachers as $student_teacher) {
                    $subject_mark=ClassSubject::find($student_teacher->subject_id);
                    $subject=[
                        'campus_id' => $student->campus_id,
                        'exam_create_id'=>$exam_create->id,
                        'session_id'=>$exam_create->session_id,
                        'class_id'=>$student->current_class_id,
                        'class_section_id'=>$student->current_class_section_id,
                        'student_id'=>$student->id,
                   'exam_allocation_id'=>$allocation->id,
                   'subject_id'=>$student_teacher->subject_id,
                   'teacher_id'=>$student_teacher->teacher_id,
                   'theory_mark'=>$subject_mark->theory_mark,
                   'parc_mark'=>$subject_mark->parc_mark,
                   'total_mark'=>$subject_mark->total,
                   'pass_percentage'=>$subject_mark->passing_percentage];
                    ExamSubjectResult::create($subject);
                }
            }



            DB::commit();

            $output = ['success' => true,
                        'msg' => __("english.updated_success")
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
        }

        return redirect('exam/setup')->with('status', $output);
    }
    public function selectedClasses($class_ids, $exam_create, $input)
    {
        $classes=Classes::whereIn('id', $class_ids)->get();
        $start_from=$input['start_from'];
        foreach ($classes as $class) {
            $students =Student::where('current_class_id', $class->id)->where('status', 'active')->get();
            if (!$students->isEmpty()) {
                foreach ($students as $student) {
                    $exam_roll_no=null;
                    if ($input['roll_no_type']=='custom_roll_no') {
                        $exam_roll_no=$this->util->getExamRollNo($start_from);
                        $start_from++;
                    }
                    $allocation= ExamAllocation::create([
                'campus_id' => $student->campus_id,
                'exam_create_id'=>$exam_create->id,
                'session_id'=>$input['session_id'],
                'class_id'=>$student->current_class_id,
                'class_section_id'=>$student->current_class_section_id,
                'student_id'=>$student->id,
                'roll_type'=>$input['roll_no_type'],
                'exam_roll_no'=>$exam_roll_no
            ]);
                    $subject_teachers= SubjectTeacher::where('campus_id', $student->campus_id)->where('class_id', $student->current_class_id)
                             ->where('class_section_id', $student->current_class_section_id)->get();

                    foreach ($subject_teachers as $student_teacher) {
                        $subject_mark=ClassSubject::find($student_teacher->subject_id);
                        $subject=[
                            'campus_id' => $student->campus_id,
                            'exam_create_id'=>$exam_create->id,
                            'session_id'=>$input['session_id'],
                            'class_id'=>$student->current_class_id,
                            'class_section_id'=>$student->current_class_section_id,
                            'student_id'=>$student->id,
                   'exam_allocation_id'=>$allocation->id,
                   'subject_id'=>$student_teacher->subject_id,
                   'teacher_id'=>$student_teacher->teacher_id,
                   'theory_mark'=>$subject_mark->theory_mark,
                   'parc_mark'=>$subject_mark->parc_mark,
                   'total_mark'=>$subject_mark->total,
                   'pass_percentage'=>$subject_mark->passing_percentage];
                        ExamSubjectResult::create($subject);
                    }
                }
            }
        }
    }

    public function updateSubjectsMark($id)
    {
        $exam_create=ExamCreate::find($id);

        $class_wise_subjects=[];
        foreach ($exam_create->class_ids as $class) {
            $classes=Classes::find($class);
            $subjects=ClassSubject::where('class_id', $class)->get();
            $class_wise_subjects[]=[
                'class'=>$classes,
                'subjects'=>$subjects
            ];
        }
        return view('Examination.exam_setup.update_subjects_marks', compact('exam_create', 'class_wise_subjects'));
    }
public function updateSubjectsMarkPost(Request $request)
{
    try {
        DB::beginTransaction();
        $input = $request->input();
        // dd($input);
        foreach ($input['subjects'] as  $sub) {
            $subject_results=ExamSubjectResult::where('exam_create_id', $input['exam_create_id'])->where('subject_id', $sub['subject_id'])->get();
            foreach ($subject_results as $subject) {
                $subject_result=ExamSubjectResult::find($subject->id);
                $subject_result->theory_mark=$sub['theory_mark'];
                $subject_result->parc_mark=$sub['parc_mark'];
                $subject_result->total_mark=$sub['theory_mark'] +$sub['parc_mark'];
                $subject_result->save();

                //dd($subject_result);
            }
            //dd($subject_results);
        }

        DB::commit();

        $output = ['success' => true,
                    'msg' => __("english.updated_success")
                    ];
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
    }

    return redirect('exam/setup')->with('status', $output);
}

    public function getExamTerm(Request $request)
    {
        if (!empty($request->input('campus_id'))) {
            $campus_id = $request->input('campus_id');
            $session_id = $request->input('session_id');

            $terms=ExamCreate::forDropdown($campus_id, $session_id);

            $html = '<option value="">' . __('english.please_select') . '</option>';

            if (!empty($terms)) {
                foreach ($terms as $id => $title) {
                    $html .= '<option value="' . $id .'">' . $title. '</option>';
                }
            }
            return $html;
        }
    }

    public function enrolledStudents($id)
    {
        $students=ExamAllocation::leftJoin('students', 'exam_allocations.student_id', '=', 'students.id')
        ->leftJoin('classes', 'exam_allocations.class_id', '=', 'classes.id')
        ->leftJoin('class_sections', 'exam_allocations.class_section_id', '=', 'class_sections.id')
        ->where('exam_create_id', $id)
        ->select(
            'classes.title as class',
            'class_sections.section_name as section_name',
            'students.father_name',
            'students.roll_no',
            'exam_allocations.id as id',
            DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name")
        )->get();


        return view('Examination.exam_setup.enrolled_students', compact('students'));
    }
  public function destroy($id)
    {
        if (!auth()->user()->can('exam_setup.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $student=ExamAllocation::findOrFail($id);
                $student->delete();

                $output = ['success' => true,
                            'msg' => __("english.deleted_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
public function missingSubjects($id)
{
    $exam_create=ExamCreate::findOrFail($id);
    $sections =ClassSection::whereIn('class_id', $exam_create->class_ids)->get();
    $missing_subjects=[];
    foreach ($sections as $section) {
        $subject_enrolled = ExamSubjectResult::where('exam_create_id', $id)->where('class_id', $section->class_id)->where('class_section_id', $section->id)->select('subject_id')->distinct()->get()->toArray() ;
        $subject_ids=array();
        foreach ($subject_enrolled as $sub) {
            array_push($subject_ids, $sub['subject_id']);
        }
        $missing_subject=SubjectTeacher::leftjoin('campuses as cam', 'subject_teachers.campus_id', '=', 'cam.id')
        ->leftjoin('classes as c', 'subject_teachers.class_id', '=', 'c.id')
        ->leftjoin('class_sections as cs', 'subject_teachers.class_section_id', '=', 'cs.id')
        ->leftjoin('class_subjects as sub', 'subject_teachers.subject_id', '=', 'sub.id')
        ->leftjoin('hrm_employees as th', 'subject_teachers.teacher_id', '=', 'th.id')
        ->where('subject_teachers.class_id', $section->class_id)
        ->where('subject_teachers.class_section_id', $section->id)
        ->whereNotIn('subject_teachers.subject_id', $subject_ids)

        ->select([
            'subject_teachers.id',
            'subject_teachers.class_id',
            'cam.campus_name as campus_name',
            'c.title as class_name',
            'cs.section_name as section_name',
            DB::raw("CONCAT(COALESCE(sub.name, ''),' (',COALESCE(sub.code,''),')') as subject_name"),
            DB::raw("CONCAT(COALESCE(th.first_name, ''),' ',COALESCE(th.last_name,'') ,'(',COALESCE(th.employeeID,''),')' ) as teacher_name")
            ])->get();
        if ($missing_subject->isNotEmpty()) {
            $missing_subjects[]=$missing_subject;
        }
    }
    return view('Examination.exam_setup.missing_subjects', compact('missing_subjects', 'exam_create'));
}
public function postMissingSubjects(Request $request)
{
    try {
        DB::beginTransaction();
        foreach ($request->input('subject_checked') as $sub) {
            $subject_teacher=SubjectTeacher::find($sub);

            $exam_allocation=ExamAllocation::where('exam_create_id', $request->input('exam_create_id'))
            ->where('campus_id', $subject_teacher->campus_id)
            ->where('class_id', $subject_teacher->class_id)
            ->where('class_section_id', $subject_teacher->class_section_id)->get();
            if ($exam_allocation->isNotEmpty()) {
                foreach ($exam_allocation as $exam) {
                    $subject_mark=ClassSubject::find($subject_teacher->subject_id);
                    $subject=[
                        'campus_id' => $exam->campus_id,
                        'exam_create_id'=>$exam->exam_create_id,
                        'session_id'=>$exam->session_id,
                        'class_id'=>$exam->class_id,
                        'class_section_id'=>$exam->class_section_id,
                        'student_id'=>$exam->student_id,
                        'exam_allocation_id'=>$exam->id,
                        'subject_id'=>$subject_teacher->subject_id,
                        'teacher_id'=>$subject_teacher->teacher_id,
                        'theory_mark'=>$subject_mark->theory_mark,
                        'parc_mark'=>$subject_mark->parc_mark,
                        'total_mark'=>$subject_mark->total,
                        'pass_percentage'=>$subject_mark->passing_percentage];
                    ExamSubjectResult::create($subject);
                }
            }
        }
        DB::commit();

        $output = ['success' => true,
                    'msg' => __("english.updated_success")
                    ];
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
    }

    return redirect('exam/setup')->with('status', $output);
}

public function enrolledSubjects($id)
{
      $exam_create=ExamCreate::findOrFail($id);
   
        $subject_enrolled = ExamSubjectResult::where('exam_create_id', $id)->select('subject_id')->distinct()->get()->toArray() ;
        $subject_ids=array();
        foreach ($subject_enrolled as $sub) {
            array_push($subject_ids, $sub['subject_id']);
        }
        $missing_subjects=SubjectTeacher::leftjoin('campuses as cam', 'subject_teachers.campus_id', '=', 'cam.id')
        ->leftjoin('classes as c', 'subject_teachers.class_id', '=', 'c.id')
        ->leftjoin('class_sections as cs', 'subject_teachers.class_section_id', '=', 'cs.id')
        ->leftjoin('class_subjects as sub', 'subject_teachers.subject_id', '=', 'sub.id')
        ->leftjoin('hrm_employees as th', 'subject_teachers.teacher_id', '=', 'th.id')
        // ->where('subject_teachers.class_id', $exam_create->class_ids)
        ->whereIn('subject_teachers.subject_id', $subject_ids)

        ->select([
            'subject_teachers.id',
            'subject_teachers.class_id',
            'cam.campus_name as campus_name',
            'c.title as class_name',
            'cs.section_name as section_name',
            DB::raw("CONCAT(COALESCE(sub.name, ''),' (',COALESCE(sub.code,''),')') as subject_name"),
            DB::raw("CONCAT(COALESCE(th.first_name, ''),' ',COALESCE(th.last_name,'') ,'(',COALESCE(th.employeeID,''),')' ) as teacher_name")
            ])->orderBy('subject_teachers.class_section_id','asc')->get();
        
    return view('Examination.exam_setup.enrolled_subjects', compact('missing_subjects', 'exam_create'));
}


public function postDeleteSubjects(Request $request)
{
    try {
        DB::beginTransaction();
        foreach ($request->input('subject_checked') as $sub) {
            $subject_teacher=SubjectTeacher::find($sub);

            $exam_allocation=ExamAllocation::where('exam_create_id', $request->input('exam_create_id'))
            ->where('campus_id', $subject_teacher->campus_id)
            ->where('class_id', $subject_teacher->class_id)
            ->where('class_section_id', $subject_teacher->class_section_id)->get();
            if ($exam_allocation->isNotEmpty()) {
                foreach ($exam_allocation as $exam) {
                    $subject_mark=ClassSubject::find($subject_teacher->subject_id);
                   $subject_result=ExamSubjectResult::
                   where( 'campus_id' , $exam->campus_id)
                   ->where('exam_create_id',$exam->exam_create_id)
                   ->where('session_id',$exam->session_id)
                   ->where('class_id',$exam->class_id)
                   ->where('class_section_id',$exam->class_section_id)
                   ->where('student_id',$exam->student_id)
                   ->where('exam_allocation_id',$exam->id)
                   ->where('subject_id',$subject_teacher->subject_id)->first();
                   $subject_result->delete();    
                }
            }
        }
        DB::commit();

        $output = ['success' => true,
                    'msg' => __("english.updated_success")
                    ];
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
    }

    return redirect('exam/setup')->with('status', $output);
}


}
