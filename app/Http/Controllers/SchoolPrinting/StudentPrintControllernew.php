<?php

namespace App\Http\Controllers\SchoolPrinting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Campus;
use App\Models\ClassSection;
use Carbon\Carbon;
use App\Utils\Util;
use Yajra\DataTables\Facades\DataTables;
use DB;
use PDF;
use File;
use App\Utils\FeeTransactionUtil;

class StudentPrintController extends Controller
{
    /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
    public function __construct(Util $util, FeeTransactionUtil $feeTransactionUtil)
    {
        $this->util= $util;
        $this->feeTransactionUtil = $feeTransactionUtil;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('HrmEmployee.view') && !auth()->user()->can('HrmEmployee.create')) {
            abort(403, 'Unauthorized action.');
        }
        // dd(Carbon::now()->format("l") );
        // dd($this->util->generateDateRange(Carbon::parse('2022-02-01'), Carbon::parse('2022-02-28')));

        $campuses=Campus::forDropdown();

        return view('school-printing.student-print.index')->with(compact('campuses'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //try {
            $input = $request->input();
            
            $_date=explode(' - ', $input['month_list_filter_date_range']);
            $start_date=Carbon::parse($_date[0])->format('Y-m-d');
            $end_date=Carbon::parse($_date[1])->format('Y-m-d');
            $campus_id=$input['campus_id'];
            if (!empty($request->input('class_ids'))) {
                $class_section=ClassSection::whereIn('class_id', [3])->orderBy('class_id', 'asc')->get();
            } else {
                $class_section=ClassSection::orderBy('class_id', 'asc')->get();
            }
            $student_list=[];

            foreach ($class_section as $section) {
                $students =Student::with(['campuses','current_class','current_class_section'])
        ->leftjoin('fee_transactions AS t', 'students.id', '=', 't.student_id')
        ->where('students.campus_id', $campus_id)
        ->where('students.current_class_section_id', $section->id)
        ->where('students.status', $input['status'])
       // ->Where('student_transport_fee','>',0)
        ->select([
                 'students.*',
                 DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            +COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due")
        ])->groupBy('students.id');
                if (request()->has('only_transport')) {
                    if ($input['only_transport']==1) {
                        $students->Where('student_transport_fee', '>', 0);
                    }
                }
                $students=$students->get();

                if (!$students->isEmpty()) {
                    $section_list=[];
                    foreach ($students as $std) {
                        $section_list[]=[
                            'campus_name'=>$std->campuses->campus_name,
                            'class_name'=>$std->current_class->title,
                            'section_name'=>$std->current_class_section->section_name,
                            'roll_no'=>$std->roll_no,
                            'student_name'=>$std->first_name . $std->last_name,
                            'father_name'=>$std->father_name,
                            'birth_date'=>$std->birth_date,
                             'mobile_no'=>$std->mobile_no ,
                             'father_cnic_no'=>$std->father_cnic_no,
                             'std_permanent_address'=>$std->std_permanent_address,
                             'student_tuition_fee'=>$std->student_tuition_fee,
                            'student_transport_fee'=>$std->student_transport_fee,
                            'total_due'=>$std->total_due,
                            'paid'=>$this->feeTransactionUtil->getTotalFeePaid($start_date, $end_date, $std->id)

                        ];
                    }
                    $student_list[]=$section_list;
                }
            }

            
            $receipt = $this->receiptContent($student_list);
          

            if (!empty($receipt)) {
                $output = ['success' => 1, 'receipt' => $receipt];
            }
           
        // } catch (\Exception $e) {
        //     \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        //     $output = ['success' => false,
        //                     'msg' => __("english.something_went_wrong")
        //                 ];
        // }

        return response($output);
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
    private function receiptContent($student_list)
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
        $output['html_content'] = view('school-printing.student-print.student_particular', compact('student_list'))->render();
        
        return $output;
    }
}
