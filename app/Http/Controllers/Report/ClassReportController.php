<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\ClassSection;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\FeeHead;

use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmAttendance;
use App\Models\HumanRM\HrmDesignation;
use DB;
use File;

class ClassReportController extends Controller
{
    public function index()
    {
    
        // $title = 'New assignment added in English ' ;
        // $body = 'asdasdassd';
        // $type = "assignment";
        // $user = Student::where('id',347)->get()->pluck('user_id');
        // send_notification($user, $title, $body, $type);
        // dd($user);
        if (!auth()->user()->can('class_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        return view('Report.classes.index')->with(compact('campuses'));
    }

    public function getClassReport()
    {
        if (!auth()->user()->can('class_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (!empty(request()->get('campus_id'))) {
            $campus_id = request()->get('campus_id');
            $class_id=request()->get('class_id');
            $class_section_id=request()->get('class_section_id');
            $classes=Classes::find($class_id);
            $section=ClassSection::find($class_section_id);
            $student =Student::where('status','active')
            ->where('campus_id', $campus_id)
            ->where('current_class_id', $class_id)
            ->where('current_class_section_id', $class_section_id)->count();
            
            $total_subject =ClassSubject::where('campus_id', $campus_id)
            ->where('class_id', $class_id)
            ->count();
            $fee_heads =FeeHead::where('campus_id', $campus_id)
            ->where('class_id', $class_id)
            ->get();
            
            $assign_subjects =SubjectTeacher::with(['teacher','class_subject'])->where('campus_id', $campus_id)
            ->where('class_id', $class_id)
            ->where('class_section_id', $class_section_id)->get();
            
            
            $output = [];
            $output['success'] = true;
            $output['msg']=  __("english.added_success");
            $output['html_content'] =view('Report.classes.get-class-report')
            ->with(compact('classes','section','student','total_subject','assign_subjects','fee_heads'))->render();
        }
        return $output;
    }
}
