<?php

namespace App\Http\Controllers\GuardianLayout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassTimeTable;
use App\Models\Campus;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Session;
use App\Models\StudentGuardian;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
use App\Models\Exam\ExamSubjectResult;
use App\Models\Exam\ExamGrade;
use App\Models\Curriculum\SubjectChapter;
use Illuminate\Support\Facades\Validator;
use DB;
use File;

class GuardianDashboardController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if ($user->user_type == 'guardian') {
            $child=StudentGuardian::with(['student_guardian','students'])->where('guardian_id', $user->hook_id)->get();
            return view('guardian_layouts.guardian_dashboard.index')->with(compact('child'));
        } else {
            return redirect('/dashboard');
        }
        
    } 
}
