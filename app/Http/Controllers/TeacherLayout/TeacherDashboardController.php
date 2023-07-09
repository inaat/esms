<?php

namespace App\Http\Controllers\TeacherLayout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassTimeTable;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Session;
use App\Models\HumanRM\HrmEmployee;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
use App\Models\Exam\ExamSubjectResult;
use App\Models\Exam\ExamGrade;
use App\Models\HumanRM\HrmAttendance;
use App\Models\Curriculum\SubjectChapter;
use Illuminate\Support\Facades\Validator;
use DB;
use File;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;
            $timetables=ClassTimeTable::with(['campuses','classes','section','subjects','teacher','periods'])
            ->where('teacher_id', $teacher_id)
            ->orderBy('period_id')->get();
            return view('teacher_layouts.teacher_dashboard.index')->with(compact('timetables'));
        } else {
            return redirect('/dashboard');
        }
    }
    public function show($id)
    {
        if (!auth()->user()->can('chapter.view')) {
            abort(403, 'Unauthorized action.');
        }
       // dd(request()->input('class_id'));
        $user = \Auth::user();
        $teacher_id=$user->hook_id;
        $ClassSubjects = SubjectTeacher::where('teacher_id', $teacher_id)->where('subject_id', $id)->first();
        
        //dd($ClassSubjects);
        if (!empty($ClassSubjects)) {
            $class_subject = ClassSubject::with(['classes'])->find($id);
            $chapters=SubjectChapter::forDropdown($id);
            return view('Curriculum.manage_subject.show')->with(compact('class_subject','ClassSubjects', 'chapters'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function teacherSubjectMarkEntry()
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;

            $campuses= $this->get_teacher_campus($teacher_id);
            $sessions=Session::forDropdown(false, true);
            return view('teacher_layouts.teacher_dashboard.teacher_mark_entry')->with(compact('campuses', 'sessions'));
        } else {
            return redirect('/home');
        }
    }
    public function store(Request $request)
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;

            $input = $request->input();
            $subs=ExamSubjectResult::with(['student'=> function ($query) {
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
            $campuses=$this->get_teacher_campus($teacher_id);
            $sessions=Session::forDropdown(false, true);
            $classes=$this->get_teacher_classes_dropdown($teacher_id, $input['campus_id']);
            $sections=$this->get_teacher_class_section_dropdown($teacher_id, $input['campus_id'], $input['class_id']);
            $classSubject = $this->get_teacher_class_section_subjects_dropdown($teacher_id, $input['campus_id'], $input['class_id'], $input['class_section_id']);
            $terms=ExamCreate::forDropdown($input['campus_id'], $input['session_id']);
            $grades=ExamGrade::forDropdown();
            $json_grades=ExamGrade::get();
            // dd($json_grades[0]['id']);
            return view('teacher_layouts.teacher_dashboard.mark_entry')->with(compact('campuses', 'sessions', 'classes', 'sections', 'grades', 'json_grades', 'terms', 'campus_id', 'class_id', 'exam_create_id', 'class_section_id', 'session_id', 'subject_id', 'classSubject', 'subs'));
        } else {
            return redirect('/dashboard');
        }
    }

    public function teacher_subject_marks_print()
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;

            $campuses=$this->get_teacher_campus($teacher_id);
            $sessions=Session::forDropdown(false, true);

            return view('teacher_layouts.teacher_dashboard.create_mark_print')->with(compact('campuses', 'sessions'));
        } else {
            return redirect('/dashboard');
        }
    }


    public function get_teacher_campus($teacher_id)
    {
        $subjects = SubjectTeacher::with(['campus','classes','class_section','class_subject','teacher'])->where('teacher_id', $teacher_id)->get();

        $campuses = [];
        if (!empty($subjects)) {
            foreach ($subjects as $subject) {
                $campuses[$subject->campus->id] = $subject->campus->campus_name;
            }
        }
        return $campuses;
    }

    public function get_teacher_classes(Request $request)
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;

            if (!empty($request->input('campus_id'))) {
                $campus_id = $request->input('campus_id');
                $subjects = SubjectTeacher::with(['campus','classes','class_section','class_subject','teacher'])
                ->where('campus_id', $campus_id)->where('teacher_id', $teacher_id)->get();
                $classes = [];
                if (!empty($subjects)) {
                    foreach ($subjects as $subject) {
                        $classes[$subject->classes->id] = $subject->classes->title;
                    }
                }
                $html = '<option value="">' . __('english.please_select') . '</option>';
                //$html = '';
                if (!empty($subjects)) {
                    foreach ($classes as $key=>$value) {
                        $html .= '<option value="' . $key .'">' . $value. '</option>';
                    }
                }

                return $html;
            }
        }
    }
    public function get_teacher_class_section(Request $request)
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;

            if (!empty($request->input('campus_id'))) {
                $campus_id = $request->input('campus_id');
                $class_id = $request->input('class_id');
                $subjects = SubjectTeacher::with(['campus','classes','class_section','class_subject','teacher'])
                ->where('campus_id', $campus_id)
                ->where('class_id', $class_id)
                ->where('teacher_id', $teacher_id)->get();
                $class_section = [];
                if (!empty($subjects)) {
                    foreach ($subjects as $subject) {
                        $class_section[$subject->class_section->id] = $subject->class_section->section_name;
                    }
                }
                $html = '<option value="">' . __('english.please_select') . '</option>';
                //$html = '';
                if (!empty($subjects)) {
                    foreach ($class_section as $key=>$value) {
                        $html .= '<option value="' . $key .'">' . $value. '</option>';
                    }
                }

                return $html;
            }
        }
    }
    public function get_teacher_subjects(Request $request)
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;

            if (!empty($request->input('campus_id'))) {
                $campus_id = $request->input('campus_id');
                $class_id = $request->input('class_id');
                $class_section_id = $request->input('class_section_id');
                $subjects = SubjectTeacher::with(['campus','classes','class_section','class_subject','teacher'])
                ->where('campus_id', $campus_id)
                ->where('class_id', $class_id)
                ->where('class_section_id', $class_section_id)
                ->where('teacher_id', $teacher_id)->get();
                $class_subject = [];
                if (!empty($subjects)) {
                    foreach ($subjects as $subject) {
                        $class_subject[$subject->class_subject->id] = $subject->class_subject->name;
                    }
                }
                $html = '<option value="">' . __('english.please_select') . '</option>';
                //$html = '';
                if (!empty($subjects)) {
                    foreach ($class_subject as $key=>$value) {
                        $html .= '<option value="' . $key .'">' . $value. '</option>';
                    }
                }

                return $html;
            }
        }
    }



    public function get_teacher_classes_dropdown($teacher_id, $campus_id)
    {
        $subjects = SubjectTeacher::with(['campus','classes','class_section','class_subject','teacher'])
        ->where('campus_id', $campus_id)->where('teacher_id', $teacher_id)->get();

        $classes = [];
        if (!empty($subjects)) {
            foreach ($subjects as $subject) {
                $classes[$subject->classes->id] = $subject->classes->title;
            }
        }
        return $classes;
    }
    public function get_teacher_class_section_dropdown($teacher_id, $campus_id, $class_id)
    {
        $subjects = SubjectTeacher::with(['campus','classes','class_section','class_subject','teacher'])
        ->where('campus_id', $campus_id)
        ->where('teacher_id', $teacher_id)->get();

        $class_section = [];
        if (!empty($subjects)) {
            foreach ($subjects as $subject) {
                $class_section[$subject->class_section->id] = $subject->class_section->section_name;
            }
        }
        return $class_section;
    }

    public function get_teacher_class_section_subjects_dropdown($teacher_id, $campus_id, $class_id, $class_section_id)
    {
        $subjects = SubjectTeacher::with(['campus','classes','class_section','class_subject','teacher'])
        ->where('campus_id', $campus_id)
        ->where('class_id', $class_id)
        ->where('class_section_id', $class_section_id)
        ->where('teacher_id', $teacher_id)->get();

        $class_subject = [];
        if (!empty($subjects)) {
            foreach ($subjects as $subject) {
                $class_subject[$subject->class_subject->id] = $subject->class_subject->name;
            }
        }
        return $class_subject;
    }

    public function getTeacherAttendance()
    {
        
        $year = request()->input('year');
        $month = request()->input('month');
    
        $result = array();
        $new_date = "01-" . $month . "-" . $year;
        $totalDays = \Carbon::createFromDate($year, $month)->daysInMonth;//cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $first_day_this_month = date('01-m-Y');
        $fst_day_str = strtotime(date('d-m-Y', strtotime($new_date)));
        $array = array();
        for ($day = 1; $day <= $totalDays; $day++) {
            $date = date('Y-m-d', $fst_day_str);
            $employe_attendance =HrmAttendance::where('employee_id',\Auth::user()->hook_id)->whereDate('clock_in_time',$date)->first();
            if (!empty($employe_attendance)) {
                $s = array();
                //'present','late','absent','half_day','holiday','weekend','leave'  
                $s['date'] = $date;
                $s['badge'] = false;
                $s['footer'] = "Extra information";
                $type = $employe_attendance->type;
                $s['title'] = $type;
                if ($type == 'present') {
                    $s['classname'] = "badge bg-success";
                } else if ($type == 'absent') {
                    $s['classname'] = "badge bg-danger";
                } else if ($type == 'late') {
                    $s['classname'] = "badge bg-warning";
                } else if ($type == 'half_day') {
                    $s['classname'] = "badge bg-dark";
                } else if ($type == 'holiday') {
                    $s['classname'] = "badge holiday";
                } else if ($type == 'weekend') {
                    $s['classname'] = "badge weekend";
                
                } else if ($type == 'leave') {
                    $s['classname'] = "badge bg-info";
                }
                $array[] = $s;
            }
            $fst_day_str = ($fst_day_str + 86400);
        }
        if (!empty($array)) {
            echo json_encode($array);
        } else {
            echo false;
        }
    }




    public function teacherRoutineTestSubjectMarkEntry()
    {
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            $teacher_id=$user->hook_id;

            $campuses= $this->get_teacher_campus($teacher_id);
            $sessions=Session::forDropdown(false, true);
            return view('teacher_layouts.teacher_dashboard.teacher_routine_test_mark_entry')->with(compact('campuses', 'sessions'));
        } else {
            return redirect('/home');
        }
    }
}
