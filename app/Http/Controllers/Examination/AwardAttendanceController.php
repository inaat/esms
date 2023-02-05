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

class AwardAttendanceController extends Controller
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
        if (!auth()->user()->can('exam_award_list_attendance.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);

        return view('Examination.award-attendance.index')->with(compact('campuses','sessions'));
    }
      
    public function print(Request $request)
    {
        if (!auth()->user()->can('exam_award_list_attendance.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = ['success' => 0,
                'msg' => trans("messages.something_went_wrong")
                ];
                $input = $request->input();
                $data=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term'])
                ->where('session_id',$input['session_id'])
                ->where('exam_create_id',$input['exam_create_id'])
                ->where('campus_id',$input['campus_id']);
                if(!empty($request->input('class_id')))
                {    
                    $data->where('class_id',$input['class_id']);
                }
                if(!empty($request->input('class_section_id')))
                {    
                    $data->where('class_section_id',$input['class_section_id']);
                }
                $data=$data->get();
                
                $receipt = $this->receiptContent($data);

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
    private function receiptContent($data)
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

        $output['html_content'] = view('Examination.award-attendance.awardlist', compact('data'))->render();
        
        return $output;
    }

    
    

   
}