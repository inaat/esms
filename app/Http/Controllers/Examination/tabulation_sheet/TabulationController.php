<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassTimeTablePeriod;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
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

class TabulationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);

        return view('Examination.tabulation_sheet.index')->with(compact('campuses', 'sessions'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $input=$request->input();

        $get_subjects = SubjectTeacher::subjects_list($input['campus_id'], $input['class_id'], $input['class_section_id']);
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);
    
        $students=ExamAllocation::with(['student','campuses','subject_result','subject_result.subject_name','session','current_class','current_class_section','exam_create','exam_create.term','grade'])
    ->where('campus_id', $input['campus_id'])
    ->where('class_id', $input['class_id'])
    ->where('class_section_id', $input['class_section_id'])
    ->where('session_id', $input['session_id'])
    ->where('exam_create_id', $input['exam_create_id'])->orderBy('class_section_position', 'ASC')->get();
  
        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $class_section_id=$input['class_section_id'];
        $session_id=$input['session_id'];
        $exam_create_id=$input['exam_create_id'];

        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        $terms=ExamCreate::forDropdown($input['campus_id'], $input['session_id']);

        return view('Examination.tabulation_sheet.tabulation_sheet')->with(compact('campuses', 'sessions', 'get_subjects', 'students', 'classes', 'sections', 'terms', 'campus_id', 'class_id', 'exam_create_id', 'class_section_id', 'session_id'));
    }
    public function create()
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);

        return view('Examination.tabulation_sheet.create')->with(compact('campuses', 'sessions'));
    }
    public function tabulationSheetPrint(Request $request)
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = ['success' => 0,
            'msg' => trans("messages.something_went_wrong")
            ];
                $details=[];
                $input= $request->input();
            
                $exam =ExamCreate::with(['term','session'])->findOrFail($input['exam_create_id']);
                $classes=Classes::where('campus_id', $input['campus_id']);
                if (!empty($input['class_id'])) {
                    $classes=Classes::whereIn('id', $input['class_id']);
                }
                $classes=$classes->get();
                foreach ($classes as $class) {
                    $class_section=ClassSection::where('class_id', $class->id)->get();
                    //  dd($class_section);
                    if (!$class_section->isEmpty()) {
                        foreach ($class_section as $section) {
                            $get_subjects = SubjectTeacher::subjects_list($input['campus_id'], $class->id, $section->id);
                    
                            $students=ExamAllocation::with(['student','campuses','subject_result','subject_result.subject_name','session','current_class','current_class_section','exam_create','exam_create.term','grade'])
                            ->where('campus_id', $input['campus_id'])
                            ->where('class_id', $class->id)
                            ->where('class_section_id', $section->id)
                            ->where('session_id', $input['session_id'])
                            ->where('exam_create_id', $input['exam_create_id'])->orderBy('class_position', 'ASC')->get();
  
                            $data=['exam'=>$exam,'subjects'=>$get_subjects, 'class'=>$class, 'section'=>$section, 'students'=>$students];
                            $details[]=$data;
                        }
                    }
                }
            
                if(Empty($details)) {
                    $output = ['success' => 0,
                    'msg' => trans("messages.this_class_have_no_section")
                    ];
                    return $output;
                }     
                $receipt = $this->bulkReceiptContent($details);

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                    ];
            }

            return $output;
        }
    }
    
    private function bulkReceiptContent($details)
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
    
        $output['html_content'] = view('Examination.tabulation_sheet.tabulation-sheet-print', compact('details'))->render();
        
        return $output;
    }
}
