<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\Classes;
use App\Models\Campus;
use App\Models\Session;
use App\Models\Student;
use App\Models\FeeTransaction;
use App\Models\Discount;
use App\Models\FeeHead;
use App\Models\FeeTransactionPayment;
use App\Models\FeeTransactionLine;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\StudentUtil;
use App\Utils\FeeTransactionUtil;
use App\Utils\NotificationUtil;
use App\Events\FeeTransactionPaymentDeleted;

use Illuminate\Support\Facades\Validator;
use DB;
use File;

class OtherFeeAllocationController extends Controller
{
    protected $studentUtil;
    protected $feeTransactionUtil;
    protected $notificationUtil;


    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(StudentUtil $studentUtil, FeeTransactionUtil $feeTransactionUtil, NotificationUtil $notificationUtil)
    {
        $this->studentUtil = $studentUtil;
        $this->feeTransactionUtil = $feeTransactionUtil;
        $this->notificationUtil= $notificationUtil;
        $this->student_status_colors = [
            'active' => 'bg-success',
            'packed' => 'bg-info',
            'shipped' => 'bg-navy',
            'delivered' => 'bg-green',
            'cancelled' => 'bg-red',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('fee.fee_transaction_create')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');


        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown();
        $active_session=$this->studentUtil->getActiveSession();
        $now_month = \Carbon::now()->month;

        return view('other_fee_allocation.create')->with(compact('campuses', 'now_month', 'sessions', 'active_session'));
    }
    public function feesAssignSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
            'month_year' => "required"
        ]);
        $type=$input['type'];

        $month_year_arr = explode('/', request()->input('month_year'));
        $month = $month_year_arr[0];
        $year = $month_year_arr[1];

        $transaction_date = $year . '-' . $month . '-01';
        //check if fees exists for the month year
        $fees = FeeTransaction::where('type','=',$type)->whereDate('transaction_date', $transaction_date)
        ->get('student_id', 'first_name');
        $already_exists = [];
        if (!empty($fees)) {
            foreach ($fees as $key => $value) {
                array_push($already_exists, $value->student_id);
            }
        }    $month_year=$input['month_year'];
        // $month_id=$input['month_id'];
        // $session_id=$input['session_id'];
        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $class_section_id=$input['class_section_id'];
        $system_settings_id = session()->get('user.system_settings_id');
        $due_date=$this->studentUtil->uf_date($input['due_date']);
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        $fee_heads=$this->studentUtil->getFeeHeads($campus_id, $class_id);
        if($fee_heads->count()<0){
            $fee_heads=$this->studentUtil->getFeeHeads($campus_id, $class_id);
        }
        // $sessions=Session::forDropdown();
        $campuses=Campus::forDropdown();
        $students=$this->studentUtil->getStudentList($system_settings_id, $class_id, $class_section_id, 'active',$already_exists );
        return view('other_fee_allocation.fees_assign')->with(compact('type','students', 'fee_heads', 'campuses', 'classes', 'sections', 'campus_id', 'class_id', 'class_section_id', 'month_year', 'due_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('fee.fee_transaction_create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $input=$request->input();
            $type=$input['type'];
            $due_date=$input['due_date'];
            $session_id=$this->studentUtil->getActiveSession();
            $fee_heads=[];
            if(!empty($input['fee_heads'])){
                $fee_heads=$input['fee_heads'];
            }
            $user_id = $request->session()->get('user.id');
            $system_settings_id = $request->session()->get('user.system_settings_id');
            foreach ($input['student_checked'] as $student_id) {
                $student=Student::find($student_id);
                // $discount=Discount::find($student->discount_id);
                $discount=0;
                $lines_formatted = [];
                foreach ($fee_heads as $key => $value) {
                    if (!empty($value['is_enabled'])) {
                        $line=[
                    'fee_head_id'=>$value['fee_head_id'],
                    'amount'=>$this->studentUtil->num_uf($value['amount'])
                ];
                        $lines_formatted[] = new FeeTransactionLine($line);
                    }
                }
                if ($student->is_transport && $type=='transport_fee') {
                    $transport_fee=[
                            'fee_head_id'=>3,
                            'amount'=>$this->studentUtil->num_uf($student->student_transport_fee)
                        ];
                    $lines_formatted[]=new FeeTransactionLine($transport_fee);
                }
              

                $final_total=$this->feeTransactionUtil->getFinalWithoutDiscount($lines_formatted, $discount);

                if ($final_total !=0) {
                    $transaction=$this->feeTransactionUtil->multiFeeTransaction($student, $type, $system_settings_id, $user_id, $lines_formatted, $final_total, $discount, $input['month_year'], $due_date, $session_id);
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
        return redirect('other-fee/create')->with('status', $output);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     
    }


}
