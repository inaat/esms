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
use App\Models\ClassLevel;
use App\Models\Classes;
use App\Models\Session;
use App\Utils\Util;
use Carbon;
use App\Models\Barcode;
use Yajra\DataTables\Facades\DataTables;
use DB;
use File;

class ExamResultController extends Controller
{
    public function __construct(Util $util)
    {
        $this->util= $util;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //if PASSED IN PAST
    public function index()
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);

        return view('Examination.exam_result.index')->with(compact('campuses', 'sessions'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->only(['campus_id', 'class_id','class_section_id','session_id','exam_create_id']);
        $students=ExamAllocation::with(['student'=>function ($query) {
            $query->orderBy('id', 'DESC');
        },'campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade'])
        ->where('campus_id', $input['campus_id'])
        ->where('class_id', $input['class_id'])
        ->where('class_section_id', $input['class_section_id'])
        ->where('session_id', $input['session_id'])
        ->where('exam_create_id', $input['exam_create_id'])->get();


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
        $classSubject = SubjectTeacher::forDropdown($input['class_id'], $input['class_section_id']);
        $terms=ExamCreate::forDropdown($input['campus_id'], $input['session_id']);

        return view('Examination.exam_result.list')->with(compact('students', 'campuses', 'sessions', 'classes', 'sections', 'terms', 'campus_id', 'class_id', 'exam_create_id', 'class_section_id', 'session_id'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = ['success' => 0,
                'msg' => trans("messages.something_went_wrong")
                ];
                $students=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
                ->findOrFail($id);


                $receipt = $this->receiptContent($students);

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


    public function create()
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);

        return view('Examination.exam_result.create')->with(compact('campuses', 'sessions'));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function bulkMarkSheetPrint(Request $request)
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
             try {
                $output = ['success' => 0,
                'msg' => trans("messages.something_went_wrong")
                ];
                $input= $request->input();
                $students_details=ExamAllocation::with(['student'=>function ($query) {
                    $query->orderBy('id', 'DESC');
                },'campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
                ->where('campus_id', $input['campus_id'])
                ->where('class_id', $input['class_id'])
                ->where('class_section_id', $input['class_section_id'])
                ->where('session_id', $input['session_id'])
                ->where('exam_create_id', $input['exam_create_id'])->orderBy('obtain_mark', 'desc')->get();

                $receipt = $this->bulkReceiptContent($students_details);

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
    private function receiptContent($details)
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

        $output['html_content'] = view('Examination.exam_result.show', compact('details'))->render();

        return $output;
    }
    private function bulkReceiptContent($students_details)
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

        $output['html_content'] = view('Examination.exam_result.mark_sheet', compact('students_details'))->render();

        return $output;
    }
    public function topPositionsCreate()
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);
        $class_level=ClassLevel::forDropdown();

        return view('Examination.top-position.create')->with(compact('campuses', 'sessions', 'class_level'));
    }
    public function topPositionsPost(Request $request)
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = ['success' => 0,
                'msg' => trans("messages.something_went_wrong")
                ];
                $input=$request->input();
                //dd($input);

                $campus_id=$input['campus_id'];
                $session_id=$input['session_id'];
                $exam_create_id=$input['exam_create_id'];
                $class_level_id=$input['class_level_id'];
                $limit=$input['top_position_number'];
                $class_level_name=ClassLevel::findOrFail($class_level_id);
                $class_ids=Classes::where('class_level_id', $class_level_id)->pluck('id')->toArray();
                $string_class_ids=$this->associativeArrayToSimple($class_ids);
                $top_students = DB::table('exam_allocations')
        ->select('id', 'total_mark', 'obtain_mark', 'final_percentage')
        ->selectRaw("FIND_IN_SET( final_percentage, ( SELECT GROUP_CONCAT( DISTINCT `final_percentage` ORDER BY `final_percentage` DESC ) FROM exam_allocations Where 
        campus_id=".$campus_id." And  class_id IN".$string_class_ids." And  session_id=".$session_id." And  exam_create_id=".$exam_create_id."  )) as rank ")
        ->where('campus_id', $campus_id)
        ->whereIn('class_id', $class_ids)
        ->where('session_id', $session_id)
        ->where('exam_create_id', $exam_create_id)
        ->orderBy('final_percentage', 'desc')
        ->get();
                //dd($top_students);
                $students=[];
                foreach ($top_students as $value) {
                    if ($value->rank<=$limit && $value->rank>0) {
                        $data=[ 'rank'=>$value->rank,
                'data'=>ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
                ->findOrFail($value->id)
            ];
                        $students[]=$data;
                    }
                }
                $receipt = $this->topReceiptContent($students, $class_level_name,$limit);

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
        // return view('Examination.top-position.show')->with(compact('students'));
    }

    public function associativeArrayToSimple($data)
    {
        $simple_array ='('; //simple array
        foreach ($data as $key=>$d) {
            if (array_key_last($data)==$key) {
                $simple_array.=$d.')';
            } else {
                $simple_array.=$d.',';
            }
        }

        return $simple_array;
    }
    private function topReceiptContent($students, $class_level_name,$limit)
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

        $output['html_content'] = view('Examination.top-position.show')->with(compact('students', 'class_level_name','limit'))->render();

        return $output;
    }

    public function topPositionListCreate()
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);

        return view('Examination.exam_result.top_position_list_create')->with(compact('campuses', 'sessions'));
    }
    public function topPositionListStore(Request $request)
    {
        if (!auth()->user()->can('exam_result.print')) {
            abort(403, 'Unauthorized action.');
        }
        $input=$request->input();
        $students=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
        ->where('campus_id', $input['campus_id'])
        ->where('session_id', $input['session_id'])
        ->where('exam_create_id', $input['exam_create_id']);
        if($input['type']=='class_wise'){
            $students->whereIn('class_position', [1,2,3]);
        }else{
            $students->whereIn('class_section_position', [1,2,3]);

        }
        if (File::exists(public_path('uploads/pdf/topPosition.pdf'))) {
            File::delete(public_path('uploads/pdf/topPosition.pdf'));
        }
        $students=$students->orderBy('class_id', 'asc')->orderBy('class_position', 'asc')->get();
        
        $type=$input['type'];
        $pdf =  config('constants.mpdf');
        $data=['type' => $type, 'students'=>$students];
        if ($pdf) {
            $this->reportPDF('samplereport.css', $data, 'MPDF.top_position_list', 'view', 'a4');
        } else{
    $pdf_name='topPosition'.'.pdf';
    $snappy  = \WPDF::loadView('Examination.exam_result.top_position_list_print', compact('students', 'type'));
    //dd($students);
    $headerHtml = view()->make('common._header')->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'portrait')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}
        
    }
}
