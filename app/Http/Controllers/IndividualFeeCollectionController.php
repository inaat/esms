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

use App\Models\Exam\ExamCreate;


use Illuminate\Support\Facades\Validator;
use DB;
use File;

class IndividualFeeCollectionController extends Controller
{
    protected $feeTransactionUtil;
    protected $notificationUtil;
    protected $studentUtil;

    /**
    * Constructor
    *
    * @param ModuleUtil $moduleUtil
    * @return void
    */
    public function __construct(FeeTransactionUtil $feeTransactionUtil, NotificationUtil $notificationUtil, StudentUtil $studentUtil)
    {
        $this->feeTransactionUtil = $feeTransactionUtil;
        $this->notificationUtil= $notificationUtil;
        $this->studentUtil= $studentUtil;
        $this->student_status_colors = [
            'active' => 'bg-success',
            'inactive' => 'bg-info',
            'struct_up' => 'bg-warning',
            'pass_out' => 'bg-danger',
             'took_slc' => 'bg-secondary',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campuses=Campus::forDropdown();
        $students=Student::with(['campuses','current_class','current_class_section']);
        // ->where('status', 'active');
        // Check for permitted campuses of a user
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $students->whereIn('campus_id', $permitted_campuses);
        }
        $students=$students->get();
        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');

            $student_list=Student::leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
            ->leftjoin('fee_transactions AS t', 'students.id', '=', 't.student_id')
           ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
           ->leftJoin('class_sections as c-section', 'students.current_class_section_id', '=', 'c-section.id')
           // ->where('students.status', 'active')
            ->select(
                'students.father_name',
                'students.id as id',
                'students.roll_no',
                'students.status',
                'c-class.title as current_class',
                'c-section.section_name as current_section',
                'students.student_transport_fee',
                'students.student_tuition_fee',
                DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name")
            );
            // Check for permitted campuses of a user
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $student_list->whereIn('students.campus_id', $permitted_campuses);
            }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $student_list->where('students.campus_id', $campus_id);
                }
            }
            if (request()->has('class_id')) {
                $class_id = request()->get('class_id');
                if (!empty($class_id)) {
                    $student_list->where('students.current_class_id', $class_id);
                }
            }
            if (request()->has('class_section_id')) {
                $class_section_id = request()->get('class_section_id');
                if (!empty($class_section_id)) {
                    $student_list->where('students.current_class_section_id', $class_section_id);
                }
            }
            if (request()->has('status')) {
                $status = request()->get('status');
                if (!empty($status)) {
                    $student_list->where('students.status', $status);
                }
            }
            if (request()->has('father_name')) {
                $father_name = request()->get('father_name');
                if (!empty($father_name)) {
                    $student_list->where('students.father_name', 'like', "%{$father_name}%");
                }
            }
            if (request()->has('roll_no')) {
                $roll_no = request()->get('roll_no');
                if (!empty($roll_no)) {
                    $student_list->where('students.roll_no', 'like', "%{$roll_no}%");
                }
            }
            if (request()->has('student_name')) {
                $student_name = request()->get('student_name');
                if (!empty($student_name)) {
                    $student_list->where('students.first_name', 'like', "%{$student_name}%");
                }
            }


            $student_list->groupBy('students.id');
            $student_list->orderBy('students.id', 'asc');
            $datatable = Datatables::of($student_list)


            ->editColumn('student_name', function ($row) {
            
               
               // return ucwords($row->student_name);
               $status_color = !empty($this->student_status_colors[$row->status]) ? $this->student_status_colors[$row->status] : 'bg-gray';
               $status= ucwords($row->student_name);
               $status .='<span class="badge badge-mark ' . $status_color .'">' .__('english.'.$row->status).   '</span>';
               return $status;
            })

            ->filterColumn('roll_no', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('students.roll_no', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('students.first_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('father_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('students.father_name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('student_tuition_fee', '{{@num_format($student_tuition_fee)}}')

            ->editColumn('student_transport_fee', '{{@num_format($student_transport_fee)}}')
            ->setRowAttr([
                'data-href' => function ($row) {
                    if (auth()->user()->can('fee.add_fee_payment')) {
                        return  action('IndividualFeeCollectionController@show', [$row->id]) ;
                    } else {
                        return '';
                    }
                }])

            ->removeColumn('id');



            $rawColumns = ['roll_no','father_name','student_name','student_tuition_fee','student_transport_fee'];

            return $datatable->rawColumns($rawColumns)
                  ->make(true);
        }
        //
        $sessions=Session::forDropdown();
        $now_month = \Carbon::now()->month;
        $active_session=$this->feeTransactionUtil->getActiveSession();

        //dd($sessions);
        return view('individual-fee-collection.create')->with(compact('campuses', 'students', 'sessions', 'now_month', 'active_session'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student_id = $request->input('student_id');
        try {
            DB::beginTransaction();
            if (!empty($request->input('update_transaction_fee'))) {
                $input=$request->input();
                $fee_heads=$input['fee_heads'];
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
                $student=Student::find($student_id);
                $student->student_tuition_fee=$this->studentUtil->num_uf($input['student_tuition_fee']);
                if ($this->studentUtil->num_uf($input['student_transport_fee'])>0) {
                    $student->is_transport=1;
                    $student->student_transport_fee=$this->studentUtil->num_uf($input['student_transport_fee']);
                } else {
                    $student->is_transport=0;
                    $student->student_transport_fee=$this->studentUtil->num_uf($input['student_transport_fee']);
                }
                $student->save();
                $discount=$this->studentUtil->num_uf($input['transaction_discount_amount']);
                $final_total=$this->feeTransactionUtil->getFinalWithoutDiscount($lines_formatted, $discount);
                $transaction=FeeTransaction::find($input['fee_transaction_id']);
                $transaction->before_discount_total=$this->feeTransactionUtil->num_uf($input['before_discount_total']);
                $transaction->final_total=$final_total;
                $payment_status=$this->feeTransactionUtil->calculatePaymentStatus($transaction->id);
                $transaction->payment_status=$payment_status;
                $transaction->class_id=$input['transaction_class_id'];
                $transaction->class_section_id=$input['transaction_class_section_id'];
                $transaction->save();
                $transaction->fee_lines()->delete();
                if (!empty($lines_formatted)) {
                    $transaction->fee_lines()->saveMany($lines_formatted);
                }
                // dd($transaction);
                $total_paid = $this->feeTransactionUtil->getTotalPaid($transaction->id);
                if ($total_paid>$final_total) {
                    DB::rollBack();
                    $output['success'] = false;
                    $output['msg'] = __('english.paid_amount_exceeds_on_final_total_amount');
                    return $output;
                }
            }
            $advance_balance=$this->feeTransactionUtil->num_uf($request->input('balance'));
            $check_advance_cash=$request->input('method');


            if ($advance_balance>0) {
                $output=$this->advanceTransaction($student_id, $advance_balance, $request);
                if ($output['success']==false) {
                    DB::rollBack();
                    $output['success'] = false;
                    $output['msg'] = __('english.invalid_amount');
                    return $output;
                }
            }
            if ($this->feeTransactionUtil->num_uf($request->input('amount'))>0) {
                $inputs = $request->only(['amount', 'method','discount_amount', 'note', 'card_number', 'card_holder_name',
                'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
                'cheque_number', 'bank_account_number','paid_on','account_id']);
                $inputs['document'] = $this->feeTransactionUtil->uploadFile($request, 'document', 'documents');
                $data=[
                    'amount'=>$inputs['amount'],
                    'method'=>$inputs['method'],
                    'discount_amount'=>$inputs['discount_amount'],
                    'note'=>$inputs['note'],
                    'card_number'=>$inputs['card_number'],
                    'card_holder_name'=>$inputs['card_holder_name'],
                    'card_transaction_number'=>$inputs['card_transaction_number'],
                    'card_type'=>$inputs['card_type'],
                    'card_month'=>$inputs['card_month'],
                    'card_year'=>$inputs['card_year'],
                    'card_security'=>$inputs['card_security'],
                    'cheque_number'=>$inputs['cheque_number'],
                    'bank_account_number'=>$inputs['bank_account_number'],
                    'paid_on'=>$inputs['paid_on'],
                    'account_id'=>$inputs['account_id'],
                    'document'=>$inputs['document']
                    
                ];
                $parent_payment=$this->feeTransactionUtil->payStudentIndividual($request,$data,['opening_balance', 'fee']);


                $student = $this->studentUtil->getStudentDue($student_id);
                $this->notificationUtil->autoSendStudentPaymentNotification('owner_payment_received', $student, $parent_payment);
            }
            if ($this->feeTransactionUtil->num_uf($request->input('other_amount'))>0) {
                $inputs = $request->only(['other_amount', 'other_method','other_discount_amount', 'other_note', 'other_card_number', 'other_card_holder_name',
                'other_card_transaction_number', 'other_card_type', 'other_card_month', 'other_card_year', 'other_card_security',
                'other_cheque_number', 'other_bank_account_number','other_paid_on','other_account_id']);
                $inputs['other_document'] = $this->feeTransactionUtil->uploadFile($request, 'other_document', 'documents');
                $data=[
                    'amount'=>$inputs['other_amount'],
                    'method'=>$inputs['other_method'],
                    'discount_amount'=>$inputs['other_discount_amount'],
                    'note'=>$inputs['other_note'],
                    'card_number'=>$inputs['other_card_number'],
                    'card_holder_name'=>$inputs['other_card_holder_name'],
                    'card_transaction_number'=>$inputs['other_card_transaction_number'],
                    'card_type'=>$inputs['other_card_type'],
                    'card_month'=>$inputs['other_card_month'],
                    'card_year'=>$inputs['other_card_year'],
                    'card_security'=>$inputs['other_card_security'],
                    'cheque_number'=>$inputs['other_cheque_number'],
                    'bank_account_number'=>$inputs['other_bank_account_number'],
                    'paid_on'=>$inputs['other_paid_on'],
                    'account_id'=>$inputs['other_account_id'],
                    'document'=>$inputs['other_document']
                    
                ];
                $parent_payment=$this->feeTransactionUtil->payStudentIndividual($request,$data,['admission_fee', 'other_fee']);


                $student = $this->studentUtil->getStudentDue($student_id);
                $this->notificationUtil->autoSendStudentPaymentNotification('owner_payment_received', $student, $parent_payment);
            }
            if ($this->feeTransactionUtil->num_uf($request->input('transport_amount'))>0) {
                $inputs = $request->only(['transport_amount', 'transport_method','transport_discount_amount', 'transport_note', 'transport_card_number', 'transport_card_holder_name',
                'transport_card_transaction_number', 'transport_card_type', 'transport_card_month', 'transport_card_year', 'transport_card_security',
                'transport_cheque_number', 'transport_bank_account_number','transport_paid_on','transport_account_id']);
                $inputs['transport_document'] = $this->feeTransactionUtil->uploadFile($request, 'transport_document', 'documents');
                $data=[
                    'amount'=>$inputs['transport_amount'],
                    'method'=>$inputs['transport_method'],
                    'discount_amount'=>$inputs['transport_discount_amount'],
                    'note'=>$inputs['transport_note'],
                    'card_number'=>$inputs['transport_card_number'],
                    'card_holder_name'=>$inputs['transport_card_holder_name'],
                    'card_transaction_number'=>$inputs['transport_card_transaction_number'],
                    'card_type'=>$inputs['transport_card_type'],
                    'card_month'=>$inputs['transport_card_month'],
                    'card_year'=>$inputs['transport_card_year'],
                    'card_security'=>$inputs['transport_card_security'],
                    'cheque_number'=>$inputs['transport_cheque_number'],
                    'bank_account_number'=>$inputs['transport_bank_account_number'],
                    'paid_on'=>$inputs['transport_paid_on'],
                    'account_id'=>$inputs['transport_account_id'],
                    'document'=>$inputs['transport_document']
                    
                ];
                $parent_payment=$this->feeTransactionUtil->payStudentIndividual($request,$data,['transport_fee']);


                $student = $this->studentUtil->getStudentDue($student_id);
                $this->notificationUtil->autoSendStudentPaymentNotification('owner_payment_received', $student, $parent_payment);
            }
            
            DB::commit();

            $output = ['success' => true,
                    'msg' => __('english.payment_added_successfully'),
                ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                          'msg' => "File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage()
                      ];
        }

        return $output;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $output = [];

        try {
            $student_id=$id;

            $output['success'] = true;
            $system_settings_id = session()->get('user.system_settings_id');
            $student_details = $this->studentUtil->getStudentDue($student_id);
            $payment_line = new FeeTransactionPayment();
            $student_details->total_fee = empty($student_details->total_fee) ? 0 : $student_details->total_fee;
            $student_details->total_admission_fee = empty($student_details->total_admission_fee) ? 0 : $student_details->total_admission_fee;
            $student_details->total_other_fee = empty($student_details->total_other_fee) ? 0 : $student_details->total_other_fee;
            $payment_line->amount = $student_details->total_fee -
                                    $student_details->total_fee_paid ;
                                    //+($student_details->total_other_fee - $student_details->total_other_fee_paid);

            $total_other_fee_due=$student_details->total_other_fee - $student_details->total_other_fee_paid+($student_details->total_admission_fee - $student_details->total_admission_fee_paid);
            //If opening balance due exists add to payment amount
            $student_details->opening_balance = !empty($student_details->opening_balance) ? $student_details->opening_balance : 0;
            $student_details->opening_balance_paid = !empty($student_details->opening_balance_paid) ? $student_details->opening_balance_paid : 0;
            $ob_due = $student_details->opening_balance - $student_details->opening_balance_paid;
            if ($ob_due > 0) {
                $payment_line->amount += $ob_due;
            }

            $amount_formatted = $this->feeTransactionUtil->num_f($payment_line->amount);

            $student_details->total_fee_paid = empty($student_details->total_fee_paid) ? 0 : $student_details->total_fee_paid;

            $payment_line->method = 'cash';
            $payment_line->paid_on = \Carbon::now()->toDateTimeString();

            $payment_types = $this->feeTransactionUtil->payment_types();

            //Accounts
            $accounts = $this->feeTransactionUtil->accountsDropdown($system_settings_id, $student_details->campus_id, false, false, true, true);
            $now_month = \Carbon::now()->month;
            $active_session=$this->feeTransactionUtil->getActiveSession();

            if (request()->has('month_id')) {
                $now_month  = request()->get('month_id');
            }
            if (request()->has('session_id')) {
                $active_session  = request()->get('session_id');
            }
            $fee_transaction = $this->feeTransactionUtil->getFeeTransaction($student_id, $active_session, $now_month);
            $fee_payments=  $this->feeTransactionUtil->__paymentQuery($student_id, $active_session);
            //dd($payment_line->amount);
            if (!empty($fee_transaction)) {
                $fee_heads=$this->getFeeHeads($fee_transaction->campus_id, $fee_transaction->class_id, $fee_transaction->fee_lines);
            }
            ///other_payment

            $other_payment_line = new FeeTransactionPayment();
            $other_payment_line->amount=$total_other_fee_due;
            
            $other_payment_line->method = 'cash';
            $other_payment_line->paid_on = \Carbon::now()->toDateTimeString();
            ///
            ///transport_payment
            $total_transport_fee_due=$student_details->total_transport_fee - $student_details->total_transport_fee_paid;

            $transport_payment_line = new FeeTransactionPayment();
            $transport_payment_line->amount=$total_transport_fee_due;
            
            $transport_payment_line->method = 'cash';
            $transport_payment_line->paid_on = \Carbon::now()->toDateTimeString();
            ///
            $terms=ExamCreate::forDropdown($student_details->campus_id, $active_session);
            $campuses=Campus::forDropdown();
            $classes=Classes::forDropdown($system_settings_id, false, $fee_transaction->campus_id);
            $sections=ClassSection::forDropdown($system_settings_id, false, $fee_transaction->class_id);
            $output['html_content'] =  view('individual-fee-collection.fee_payment_row')
            ->with(compact(
                'fee_transaction',
                'campuses',
                'classes',
                'sections',
                'fee_heads',
                'fee_payments',
                'student_details',
                'payment_types',
                'payment_line',
                'ob_due',
                'amount_formatted',
                'accounts',
                'terms',
                'other_payment_line',
                'transport_payment_line'
            ))
            ->render();

            $output['data'] =
            ['fee_transaction'=>$fee_transaction,'student_details'=>$student_details];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output['success'] = false;
            $output['msg'] = __('english.something_went_wrong');
        }

        return $output;
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
        //
    }

    public function getFeeHeads($campus_id, $class_id, $fee_lines)
    {
        //$query=FeeHead::whereNotIn('description',['Admission','Prospectus','Security','Tuition','Transport']);
        $not_include=[];
        if (!empty($fee_lines)) {
            foreach ($fee_lines as $fee_line) {
                $not_include[]=$fee_line->fee_head_id;
            }
        }

        $query=FeeHead::where('campus_id', $campus_id)
        ->where('class_id', $class_id)->whereNotIn('id', $not_include);

        $fee_heads = $query->get();
        return $fee_heads;
    }
    public function advanceTransaction($student_id, $advance_balance, $request)
    {
        $student = Student::findOrFail($student_id);
        $month_fee=$student->student_tuition_fee;
        // if ($student->is_transport) {
        //     $month_fee +=$student->student_transport_fee;
        // }
        $transaction=FeeTransaction::where('student_id', $student_id)->latest('id')->first();
        $check_divide=$advance_balance/$month_fee;
        if (in_array($check_divide, [1,2,3,4,5,6,7,8,9,10,11,12])) {
            for ($x = 1; $x <= $check_divide; $x++) {
                $lines_formatted=[];

                $tuition_fee=[
            'fee_head_id'=>2,
            'amount'=>$this->studentUtil->num_uf($student->student_tuition_fee)
        ];
                $lines_formatted[]=new FeeTransactionLine($tuition_fee);
            //     if ($student->is_transport) {
            //         $transport_fee=[
            //     'fee_head_id'=>3,
            //     'amount'=>$this->studentUtil->num_uf($student->student_transport_fee)
            // ];
            //         $lines_formatted[]=new FeeTransactionLine($transport_fee);
            //     }
                //dd($lines_formatted);
                $user_id = $request->session()->get('user.id');
                $system_settings_id = $request->session()->get('user.system_settings_id');
                $discount=null;
                $final_total=$this->feeTransactionUtil->getFinalWithoutDiscount($lines_formatted, null);
                $date = \Carbon::parse($transaction->transaction_date)->addMonth($x);
                $month_year= $date->month.'/'.$date->year;
                if ($final_total !=0) {
                    $fee_transaction=$this->feeTransactionUtil->multiFeeTransaction($student, 'fee', $system_settings_id, $user_id, $lines_formatted, $final_total, $discount, $month_year);
                }
            }
            $output['success'] = true;
            return $output;
        } else {
            DB::rollBack();
            $output['success'] = false;
            $output['msg'] = __('english.invalid_amount');
            return $output;
        }
    }
}
