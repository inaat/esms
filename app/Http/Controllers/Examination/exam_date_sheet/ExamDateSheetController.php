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

class ExamDateSheetController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }


        if (request()->ajax()) {
            $date_sheets = ExamDateSheet::leftjoin('campuses as cam', 'exam_date_sheets.campus_id', '=', 'cam.id')
                ->leftJoin('classes as c-class', 'exam_date_sheets.class_id', '=', 'c-class.id')
                ->leftJoin('class_sections', 'exam_date_sheets.class_section_id', '=', 'class_sections.id')
                ->leftJoin('exam_creates', 'exam_date_sheets.exam_create_id', '=', 'exam_creates.id')
                ->leftJoin('exam_terms', 'exam_creates.exam_term_id', '=', 'exam_terms.id')
                ->leftJoin('sessions', 'exam_creates.session_id', '=', 'sessions.id')
                ->leftjoin('class_subjects as sub', 'exam_date_sheets.subject_id', '=', 'sub.id')
                ->select([

                    'exam_date_sheets.id',
                    'exam_date_sheets.day',
                    'exam_date_sheets.date',
                    'sessions.title as session_title',
                    'c-class.title',
                    'class_sections.section_name',
                    'exam_date_sheets.start_time',
                    'exam_date_sheets.end_time',
                    'sub.name',
                    'exam_date_sheets.type',
                    'exam_terms.name as exam_term',
                    'cam.campus_name as campus_name'

                ]);
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $date_sheets->whereIn('exam_date_sheets.campus_id', $permitted_campuses);
            }
            $campus_id = request()->get('campus_id');
            if (!empty($campus_id)) {
                $date_sheets->where('cam.id', $campus_id);
            }
            $class_id = request()->get('class_id');
            if (!empty($class_id)) {
                $date_sheets->where('c-class.id', $class_id);
            }
            $class_section_id = request()->get('class_section_id');
            if (!empty($class_section_id)) {
                $date_sheets->where('class_sections.id', $class_section_id);
            }
            $session_id = request()->get('session_id');
            if (!empty($session_id)) {
                $date_sheets->where('exam_creates.session_id', $session_id);
            }
            $exam_create_id = request()->get('exam_create_id');
            if (!empty($exam_create_id)) {
                $date_sheets->where('exam_creates.id', $exam_create_id);
            }

            return Datatables::of($date_sheets)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">' . __("english.actions") . '</button>
                             <ul class="dropdown-menu" style="">';
                        $html .= '<li><a class="dropdown-item  edit_date_sheet_button"data-href="' . action('Examination\ExamDateSheetController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                        $html .= '<li><a class="dropdown-item  delete_date_sheet_button"data-href="' . action('Examination\ExamDateSheetController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';

                        $html .= '</ul></div>';

                        return $html;
                    }
                )
                ->editColumn('start_time', function ($row) {
                    $start_time_formatted = $this->commonUtil->format_time($row->start_time);
                    return $start_time_formatted;
                })
                ->editColumn('name', function ($row) {
                    return $row->name . ' ' . $row->type;
                })
                ->editColumn('end_time', function ($row) {
                    $end_time_formatted = $this->commonUtil->format_time($row->end_time);
                    return $end_time_formatted;
                })
                ->removeColumn('id', 'type')
                ->rawColumns(['action', 'campus_name', 'title', 'section_name', 'name', 'session_title', 'exam_term', 'day'])
                ->make(true);
        }
        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, false);
        return view('Examination.exam_date_sheet.index')->with(compact('campuses', 'sessions'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }

        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, false);

        return view('Examination.exam_date_sheet.create')->with(compact('campuses', 'sessions'));
    }
    //   /**
    //  * Store a newly created resource in storage.
    //  * @param Request $request
    //  * @return Response
    //  */
    public function store(Request $request)
    {
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }

        try {
           // $input = $request->only(['campus_id', 'class_id', 'class_section_id', 'session_id', 'exam_create_id', 'subject_id', 'type', 'date', 'start_time', 'end_time', 'topic']);
            $input = $request->only(['campus_id', 'class_id','session_id', 'exam_create_id', 'subject_id', 'type', 'date', 'start_time', 'end_time', 'topic']);
            $input['start_time'] = $this->commonUtil->uf_time($request->input('start_time'));
            $input['end_time'] = $this->commonUtil->uf_time($request->input('end_time'));
            $input['date'] = $this->commonUtil->uf_date($input['date']);
            $day = Carbon::parse($input['date'])->format('l');
            $input['day'] = $day;
            ExamDateSheet::create($input);

            $output = [
                'success' => true,
                'msg' => __("english.added_success")
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("english.something_went_wrong")

            ];
        }

        return $output;
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }

        $date_sheet = ExamDateSheet::findOrFail($id);

        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, false);
        $classes = Classes::forDropdown(1, false, $date_sheet->campus_id);
        $class_sections = ClassSection::forDropdown(1, false, $date_sheet->class_id);
        $terms = ExamCreate::forDropdown($date_sheet->campus_id, $date_sheet->session_id);

        $classSubject = ClassSubject::forDropdown($date_sheet->class_id);
        return view('Examination.exam_date_sheet.edit')->with(compact('campuses', 'classes', 'class_sections', 'classSubject', 'terms', 'sessions', 'date_sheet'));
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
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }


        if (request()->ajax()) {
            try {
               // $input = $request->only(['campus_id', 'class_id', 'class_section_id', 'session_id', 'exam_create_id', 'subject_id', 'type', 'date', 'start_time', 'end_time', 'topic']);
                $input = $request->only(['campus_id', 'class_id', 'session_id', 'exam_create_id', 'subject_id', 'type', 'date', 'start_time', 'end_time', 'topic']);
                $input['start_time'] = $this->commonUtil->uf_time($request->input('start_time'));
                $input['end_time'] = $this->commonUtil->uf_time($request->input('end_time'));
                $input['date'] = $this->commonUtil->uf_date($input['date']);
                $day = Carbon::parse($input['date'])->format('l');
                $input['day'] = $day;
                $date_sheet = ExamDateSheet::findOrFail($id);
                $date_sheet->fill($input);
                $date_sheet->save();

                $output = [
                    'success' => true,
                    'msg' => __("english.updated_success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }


        if (request()->ajax()) {
            try {

                $date_sheet = ExamDateSheet::findOrFail($id);
                $date_sheet->delete();

                $output = [
                    'success' => true,
                    'msg' => __("english.deleted_success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function createClassWiseRollSlipPrint()
    {
        if (!auth()->user()->can('roll_no_slip.print')) {
            abort(403, 'Unauthorized action.');
        }

        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, false);

        return view('Examination.exam_date_sheet.date_sheet')->with(compact('campuses', 'sessions'));
    }




    public function postClassWiseRollSlipPrint(Request $request)
    {
        if (!auth()->user()->can('roll_no_slip.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
                $input = $request->input();
                $data = ExamAllocation::with(['student', 'campuses', 'session', 'current_class', 'current_class_section', 'exam_create', 'exam_create.term'])
                    ->where('session_id', $input['session_id'])
                    ->where('exam_create_id', $input['exam_create_id'])
                    ->where('campus_id', $input['campus_id']);
                if (!empty($request->input('class_id'))) {
                    $data->where('class_id', $input['class_id']);
                }
                if (!empty($request->input('class_section_id'))) {
                    $data->where('class_section_id', $input['class_section_id']);
                }
                $data = $data->get();
                $details = [];
                foreach ($data as $dt) {
                    $date_sheet = ExamDateSheet::with(['subject'])
                        ->where('session_id', $dt->session->id)
                        ->where('exam_create_id', $dt->exam_create_id)
                        ->where('campus_id', $dt->campus_id)
                        ->where('class_id', $dt->class_id)
                       // ->where('class_section_id', $dt->class_section_id)
                        ->orderBy('date')->get();
                    if (!$date_sheet->isEmpty()) {
                        $details[] = [
                            'student_exam' => $dt,
                            'dateSheet' => $date_sheet
                        ];
                    }
                }
                $receipt = $this->receiptContent($data, $details);

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
            }

            return $output;
        }

    }

    public function RollSlipPrint(Request $request)
    {
        if (!auth()->user()->can('roll_no_slip.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
                $input = $request->input();
                $data = ExamAllocation::with(['student', 'campuses', 'session', 'current_class', 'current_class_section', 'exam_create', 'exam_create.term'])
                    ->where('session_id', $input['session_id'])
                    ->where('exam_create_id', $input['exam_create_id'])
                    ->where('student_id', $input['student_id']);
                $data = $data->get();
                $details = [];
                foreach ($data as $dt) {
                    $date_sheet = ExamDateSheet::with(['subject'])
                        ->where('session_id', $dt->session->id)
                        ->where('exam_create_id', $dt->exam_create_id)
                        ->where('campus_id', $dt->campus_id)
                        ->where('class_id', $dt->class_id)
                       // ->where('class_section_id', $dt->class_section_id)
                        ->orderBy('date')->get();
                    if (!$date_sheet->isEmpty()) {
                        $details[] = [
                            'student_exam' => $dt,
                            'dateSheet' => $date_sheet
                        ];
                    }
                }
                $receipt = $this->receiptContent($data, $details);

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => 0,
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
    private function receiptContent($data, $details)
    {
        $output = [
            'is_enabled' => false,
            'print_type' => 'browser',
            'html_content' => null,
            'printer_config' => [],
            'data' => []
        ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details = [];

        $output['html_content'] = view('Examination.exam_date_sheet.roll_no_slip', compact('data', 'details'))->render();

        return $output;
    }


    public function lablePrint(Request $request)
    {
        if (!auth()->user()->can('roll_no_slip.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
                $input = $request->input();
                $data = ExamAllocation::with(['student', 'campuses', 'session', 'current_class', 'current_class_section', 'exam_create', 'exam_create.term'])
                    ->where('session_id', $input['session_id'])
                    ->where('exam_create_id', $input['exam_create_id'])
                    ->where('campus_id', $input['campus_id']);
                if (!empty($request->input('class_id'))) {
                    $data->where('class_id', $input['class_id']);
                }
                if (!empty($request->input('class_section_id'))) {
                    $data->where('class_section_id', $input['class_section_id']);
                }
                $data = $data->get();

                $product_details = [];
                $total_qty = 0;
                foreach ($data as $value) {
                    $details = $value;
                    $product_details[] = ['details' => $details, 'qty' => 1];
                    $total_qty += 1;
                }

                $receipt = $this->lableReceiptContent($total_qty, $product_details);

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => 0,
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
    private function lableReceiptContent($total_qty, $product_details)
    {

        $output = [
            'is_enabled' => false,
            'print_type' => 'browser',
            'html_content' => null,
            'printer_config' => [],
            'data' => []
        ];
        $barcode_details = Barcode::find(7);
        $page_height = null;
        if ($barcode_details->is_continuous) {
            $rows = ceil($total_qty / $barcode_details->stickers_in_one_row) + 0.4;
            $barcode_details->paper_height = $barcode_details->top_margin + ($rows * $barcode_details->height) + ($rows * $barcode_details->row_distance);
        }
        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details = [];

        $output['html_content'] = view('Examination.exam_date_sheet.lable', compact('product_details', 'barcode_details', 'page_height'))->render();

        return $output;
    }


    public function bulkCreate()
    {
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }

        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, false);

        return view('Examination.exam_date_sheet.bulk_create')->with(compact('campuses', 'sessions'));
    }
    public function getSubjectDateSheet(Request $request)
    {
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }

        $input = $request->input();
        //check if payrolls exists for the month year
        $date_sheet = ExamDateSheet::with(['subject'])
            ->where('session_id', $input['session_id'])
            ->where('exam_create_id', $input['exam_create_id'])
            ->where('campus_id', $input['campus_id'])
            ->where('class_id', $input['class_id'])
           // ->where('class_section_id', $input['class_section_id'])
            ->orderBy('date')->get();
        $already_exists = [];
        if (!empty($date_sheet)) {
            foreach ($date_sheet as $key => $value) {
                array_push($already_exists, $value->subject_id);
            }
        }

        $subjects = ClassSubject::where('class_id', $input['class_id'])
            ->whereNotIn('id', $already_exists)
            ->get();
        //dd($subjects);
        $campuses = Campus::forDropdown();
        $sessions = Session::forDropdown(false, false);
        $session_id = $input['session_id'];
        $exam_create_id = $input['exam_create_id'];
        $campus_id = $input['campus_id'];
        $class_id = $input['class_id'];
       // $class_section_id = $input['class_section_id'];
        $classes = Classes::forDropdown(1, false, $campus_id);
        $class_sections = ClassSection::forDropdown(1, false, $class_id);
        $terms = ExamCreate::forDropdown($campus_id, $session_id);
        return view('Examination.exam_date_sheet.get_subjects')->with(
            compact(
                'campuses',
                'sessions',
                'subjects',
                'date_sheet',
                'session_id',
                'exam_create_id',
                'campus_id',
                'class_id',
               // 'class_section_id',
                'classes',
                'class_sections',
                'terms'

            )
        );
    }
    public function bulkPostDateSheet(Request $request)
    {
        if (!auth()->user()->can('exam_date_sheet.view')) {
            abort(403, 'Unauthorized action.');
        }
        $output = [
            'success' => false,
            'msg' => __("english.something_went_wrong")
        ];
        try{
        $input = $request->input();
        $data=$input['data'];
        foreach($data as $value){
            if(!empty($value['date'])){
                $value['start_time'] = $this->commonUtil->uf_time($value['start_time']);
                $value['end_time'] = $this->commonUtil->uf_time($value['end_time']);
               
                $value['date'] = $this->commonUtil->uf_date($value['date']);
               // dd($value['date']);
                $day = Carbon::parse($value['date'])->format('l');
                $value['day'] = $day;
                $value['class_id']=$input['class_id'];
              //  $value['class_section_id']=$input['class_section_id'];
                $value['session_id']=$input['session_id'];
                $value['exam_create_id']=$input['exam_create_id'];
                $value['campus_id']=$input['campus_id'];
                //dd($value);
                $date_sheet = ExamDateSheet::where('class_id', $input['class_id'])
                ->where('subject_id',$value['subject_id'])
                ->where('type',$value['type'])->first();
                if(!empty($date_sheet)){
                    $date_sheet->fill($value);
                    $date_sheet->save();
                }else{
                    ExamDateSheet::create($value);
                }
            
            }
        }
        $output = [
            'success' => true,
            'msg' => __("english.added_success")
        ];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

        $output = [
            'success' => false,
            'msg' => __("english.something_went_wrong")
        ];
    }

    return redirect('/exam/date_sheets_bulk_create')->with('status', $output);

    }
}