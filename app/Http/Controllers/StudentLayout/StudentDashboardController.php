<?php

namespace App\Http\Controllers\StudentLayout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassTimeTable;
use App\Models\Campus;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Session;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
use App\Models\Exam\ExamSubjectResult;
use App\Models\Exam\ExamGrade;
use App\Models\Curriculum\SubjectChapter;
use Illuminate\Support\Facades\Validator;
use DB;
use File;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if ($user->user_type == 'student') {
            $student=Student::find($user->hook_id);
            $timetables=ClassTimeTable::with(['campuses','classes','section','subjects','teacher','periods'])
            ->where('class_id', $student->current_class_id)
            ->where('class_section_id', $student->current_class_section_id)
            ->orderBy('period_id')->get();
            $all_subjects=ClassSubject::allSubjectDropdown();
 
            return view('student_layouts.student_dashboard.index')->with(compact('timetables','student','all_subjects'));
        } else {
            return redirect('/dashboard');
        }
    } 
}
