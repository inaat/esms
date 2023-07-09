<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\FeeTransaction;
use App\Models\FeeTransactionPayment;
use App\Models\FeeTransactionLine;
use App\Events\FeeTransactionPaymentUpdated;
use App\Events\FeeTransactionPaymentAdded;
use App\Models\Campus;
use App\Utils\NotificationUtil;
use App\Utils\FeeTransactionUtil;
use App\Utils\StudentUtil;
use DB;

class FeeTransactionPaymentController extends Controller
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
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
      

        if (request()->ajax()) {
            $transaction = FeeTransaction::where('id', $id)
                                        ->with(['student'])
                                        ->first();
            // dd($transaction);
            $payments_query = FeeTransactionPayment::where('fee_transaction_id', $id);

        
            $accounts_enabled = true;
            $payments_query->with(['payment_account']);
            $payments = $payments_query->get();
            $payment_types = $this->feeTransactionUtil->payment_types();
            return view('fee_transaction_payment.show_payments')
                    ->with(compact('transaction', 'payments', 'payment_types', 'accounts_enabled'));
        }
    }

    /**
     * Adds new payment to the given transaction.
     *
     * @param  int  $transaction_id
     * @return \Illuminate\Http\Response
     */
    public function addPayment($transaction_id)
    {
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
       

        if (request()->ajax()) {
            $system_settings_id = request()->session()->get('user.system_settings_id');

            $transaction = FeeTransaction::where('system_settings_id', $system_settings_id)
                                        ->with(['student', 'campus'])
                                        ->findOrFail($transaction_id);
            if ($transaction->payment_status != 'paid') {
                $payment_types = $this->feeTransactionUtil->payment_types();

                $paid_amount = $this->feeTransactionUtil->getTotalPaid($transaction_id);
                $amount = $transaction->final_total - $paid_amount;
                if ($amount < 0) {
                    $amount = 0;
                }

                $amount_formated = $this->feeTransactionUtil->num_f($amount);

                $payment_line = new FeeTransactionPayment();
                $payment_line->amount = $amount;
                $payment_line->method = 'cash';
                $payment_line->paid_on = \Carbon::now()->toDateTimeString();

                //Accounts
                $accounts =$this->feeTransactionUtil->accountsDropdown($system_settings_id, $transaction->campus_id, false, false, true, true);

                $view = view('fee_transaction_payment.payment_row')
                ->with(compact('transaction', 'payment_types', 'payment_line', 'amount_formated', 'accounts'))->render();

                $output = [ 'status' => 'due',
                                    'view' => $view];
            } else {
                $output = [ 'status' => 'paid',
                                'view' => '',
                                'msg' => __('english.amount_already_paid')  ];
            }

            return json_encode($output);
        }
    }
    /**
     * Shows contact's payment due modal
     *
     * @param  int  $student_id
     * @return \Illuminate\Http\Response
     */
    public function Due($student_id)
    {
    }
    public function getPayStudentDue($student_id)
    {
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $query = Student::where('students.id', $student_id)
                            ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
                            ->join('fee_transactions AS t', 'students.id', '=', 't.student_id');
      
            $query->select(
                DB::raw("SUM(IF(t.type = 'admission_fee' AND t.status = 'final', final_total, 0)) as total_admission_fee"),
                DB::raw("SUM(IF(t.type = 'admission_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as total_admission_fee_paid"),
            );
            $query->addSelect(
                DB::raw("SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)) as total_fee"),
                DB::raw("SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as total_fee_paid"),
            );
            $query->addSelect(
                DB::raw("SUM(IF(t.type = 'other_fee' AND t.status = 'final', final_total, 0)) as total_other_fee"),
                DB::raw("SUM(IF(t.type = 'other_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as total_other_fee_paid"),
            );
            
            

            //Query for opening balance details
            $query->addSelect(
                DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(amount) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as opening_balance_paid"),
                DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
                'c-class.title as current_class',
                'students.roll_no',
                'students.campus_id',
                'students.father_name',
                'students.advance_amount',
                'students.id as student_id'
            );
            $query->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'other_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due")
            ]);
            $query->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_paid")
            ]);
            $student_details = $query->first();
            $payment_line = new FeeTransactionPayment();
           
            $student_details->total_fee = empty($student_details->total_fee) ? 0 : $student_details->total_fee;
            $student_details->total_admission_fee = empty($student_details->total_admission_fee) ? 0 : $student_details->total_admission_fee;
            $student_details->total_other_fee = empty($student_details->total_other_fee) ? 0 : $student_details->total_other_fee;

     
            $payment_line->amount = $student_details->total_fee -
                                    $student_details->total_fee_paid +($student_details->total_admission_fee - $student_details->total_admission_fee_paid)+($student_details->total_other_fee - $student_details->total_other_fee_paid);


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
            $accounts = $this->feeTransactionUtil->accountsDropdown(1, $student_details->campus_id, false, false, true, true);

            return view('fee_transaction_payment.pay_student_due_modal')
                        ->with(compact('student_details', 'payment_types', 'payment_line', 'ob_due', 'amount_formatted', 'accounts'));
        }
    }


    /**
     * Adds Payments for Student due
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPayStudentDue(Request  $request)
    {
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
       
        $student_id = $request->input('student_id');
        try {
            DB::beginTransaction();
            $this->feeTransactionUtil->payStudent($request);

            DB::commit();
            $output = ['success' => true,
                            'msg' => __('english.added_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                          'msg' => "File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage()
                      ];
        }
    
        return redirect('students')->with(['status' => $output]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
      

        if (request()->ajax()) {
            $system_settings_id = request()->session()->get('user.system_settings_id');

            $payment_line = FeeTransactionPayment::where('method', '!=', 'advance')->findOrFail($id);

            $transaction = FeeTransaction::where('id', $payment_line->fee_transaction_id)
                                        ->with(['student', 'campus'])
                                        ->first();
            $payment_types = $this->feeTransactionUtil->payment_types();

            //Accounts
            $accounts =$this->feeTransactionUtil->accountsDropdown($system_settings_id, $payment_line->campus_id, false, false, true, true);

            return view('fee_transaction_payment.edit_payment_row')
                        ->with(compact('transaction', 'payment_types', 'payment_line', 'accounts'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $transaction_id = $request->input('transaction_id');
            $transaction = FeeTransaction::where('system_settings_id', $system_settings_id)->with(['student'])->findOrFail($transaction_id);
            
            $transaction_before = $transaction->replicate();
            
          
            if ($transaction->payment_status != 'paid') {
                $inputs = $request->only(['amount','discount_amount', 'method', 'note', 'card_number', 'card_holder_name',
                'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
                'cheque_number', 'bank_account_number']);
                $discount_amount=$this->feeTransactionUtil->num_uf($inputs['discount_amount']);
                $inputs['paid_on'] = $this->feeTransactionUtil->uf_date($request->input('paid_on'), true);
                $inputs['fee_transaction_id'] = $transaction->id;
                $inputs['amount'] = $this->feeTransactionUtil->num_uf($inputs['amount']);
                $inputs['discount_amount'] = $discount_amount;
                $inputs['created_by'] = auth()->user()->id;
                $inputs['payment_for'] = $transaction->student_id;
                $inputs['campus_id'] = $transaction->campus_id;
                $inputs['account_id'] = $request->input('account_id');
                $inputs['session_id']=$this->feeTransactionUtil->getActiveSession();
               
                $transaction->final_total=$transaction->final_total-$discount_amount;
                $transaction->discount_type='fixed';
                $transaction->discount_amount=$discount_amount;
                $transaction->save();

                $prefix_type = 'fee_payment';
                DB::beginTransaction();

                $ref_count = $this->feeTransactionUtil->setAndGetReferenceCount($prefix_type, false, true);
                //Generate reference number
                $inputs['payment_ref_no'] = $this->feeTransactionUtil->generateReferenceNumber($prefix_type, $ref_count, $system_settings_id);

                $inputs['system_settings_id'] = $request->session()->get('system_details.id');
                $inputs['document'] = $this->feeTransactionUtil->uploadFile($request, 'document', 'documents');

                //Pay from advance balance
                $payment_amount = $inputs['amount'];
                if (!empty($inputs['amount'])) {
                    $tp = FeeTransactionPayment::create($inputs);
                    $inputs['transaction_type'] = $transaction->type;
                    event(new FeeTransactionPaymentAdded($tp, $inputs));
                }

                //update payment status
                $payment_status = $this->feeTransactionUtil->updatePaymentStatus($transaction_id, $transaction->final_total);
                
                DB::commit();
            }

            $output = ['success' => true,
                            'msg' => __('english.added_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = __('english.something_went_wrong');
            $output = ['success' => false,
                          'msg' => $msg
                      ];
        }
        return redirect()->back()->with(['status' => $output]);
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
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $inputs = $request->only(['amount','discount_amount', 'method', 'note', 'card_number', 'card_holder_name',
            'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
            'cheque_number', 'bank_account_number']);
            $inputs['paid_on'] = $this->feeTransactionUtil->uf_date($request->input('paid_on'), true);
            $inputs['amount'] = $this->feeTransactionUtil->num_uf($inputs['amount']);

         

            if (!empty($request->input('account_id'))) {
                $inputs['account_id'] = $request->input('account_id');
            }

            $payment = FeeTransactionPayment::where('method', '!=', 'advance')->findOrFail($id);
            //Update parent payment if exists
            if (!empty($payment->parent_id)) {
                $parent_payment = FeeTransactionPayment::find($payment->parent_id);
                $parent_payment->amount = $parent_payment->amount - ($payment->amount - $inputs['amount']);

                $parent_payment->save();
            }

            $system_settings_id = $request->session()->get('user.system_settings_id');
            $transaction = FeeTransaction::where('system_settings_id', $system_settings_id)
                                ->find($payment->fee_transaction_id);
            $transaction_before = $transaction->replicate();

            $document_name = $this->feeTransactionUtil->uploadFile($request, 'document', 'documents');
            if (!empty($document_name)) {
                $inputs['document'] = $document_name;
            }
                               
            DB::beginTransaction();

            $payment->update($inputs);

            //update payment status
            $payment_status = $this->feeTransactionUtil->updatePaymentStatus($payment->fee_transaction_id);
            $transaction->payment_status = $payment_status;

            //dd($payment);
            DB::commit();
            if (!empty($payment->parent_id)) {
                $adjust_discount=FeeTransactionPayment::find($payment->parent_id);
                //dd($adjust_discount);
                $payment->amount=$payment->amount-$adjust_discount->discount_amount;
            }
            //event
            event(new FeeTransactionPaymentUpdated($payment, $transaction->type));


            $output = ['success' => true,
                            'msg' => __('english.updated_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                          'msg' => __('english.something_went_wrong')
                      ];
        }

       return redirect()->back()->with(['status' => $output]);
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('fee.fee_payment_delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
           try {
                $payment = FeeTransactionPayment::findOrFail($id);

                // dd($payment);
                
                DB::beginTransaction();
                $discount_amount=0;
                if ($payment->discount_amount>0) {
                    $discount_amount=$payment->discount_amount;
                }
                if (!empty($payment->transaction_id)) {
                    FeeTransactionPayment::deletePayment($payment);
                } else { //advance payment
                    $adjusted_payments = FeeTransactionPayment::where(
                        'parent_id',
                        $payment->id
                    )->get();

                    $total_adjusted_amount = $adjusted_payments->sum('amount');

                   

                    //Delete all child payments
                    foreach ($adjusted_payments as $adjusted_payment) {
                        //Make parent payment null as it will get deleted
                        if($discount_amount>0){
                            $transaction = FeeTransaction::find($adjusted_payment->fee_transaction_id);
                            $discount_amount -=$transaction->discount_amount;
                            $transaction->final_total=$transaction->final_total+$transaction->discount_amount;
                            $transaction->discount_amount=0;
                            $transaction->discount_type=null;
                            $transaction->save();
                        }
                        $adjusted_payment->parent_id = null;
                        FeeTransactionPayment::deletePayment($adjusted_payment);
                    }

                    //Delete advance payment
                    FeeTransactionPayment::deletePayment($payment);
                }
                
                DB::commit();

                $output = ['success' => true,
                                'msg' => __('english.deleted_success')
                            ];
            } catch (\Exception $e) {
                DB::rollBack();

                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => false,
                                'msg' => __('english.something_went_wrong')
                            ];
            }

            return $output;
        }
    }

    public function addStudentAdvanceAmountPayment($student_id)
    {
      

        if (request()->ajax()) {
            $system_settings_id = request()->session()->get('user.system_settings_id');

            $student = Student::findOrFail($student_id);
            $payment_types = $this->feeTransactionUtil->payment_types();
            $payment_line=[];

            return view('fee_transaction_payment.post_student_advance_amount')
                        ->with(compact('student', 'payment_types', 'payment_line'));
        }
    }
    public function postAdvanceAmount(Request $request)
    {
        try {
            $system_settings_id = $request->session()->get('user.system_settings_id');

            $inputs = $request->only(['amount', 'method', 'note', 'card_number', 'card_holder_name',
                'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
                'cheque_number', 'bank_account_number']);
            $inputs['paid_on'] = $this->feeTransactionUtil->uf_date($request->input('paid_on'), true);
            $inputs['amount'] = $this->feeTransactionUtil->num_uf($inputs['amount']);
            $inputs['created_by'] = auth()->user()->id;
            $inputs['payment_for'] = $request->input('student_id');
            $inputs['session_id']=$this->feeTransactionUtil->getActiveSession();


            $prefix_type = 'student_advance_payment';
            DB::beginTransaction();

            $ref_count = $this->feeTransactionUtil->setAndGetReferenceCount($prefix_type, false, true);
            //Generate reference number
            $inputs['payment_ref_no'] = $this->feeTransactionUtil->generateReferenceNumber($prefix_type, $ref_count, $system_settings_id);

            $inputs['system_settings_id'] = $request->session()->get('system_details.id');

            //Pay from advance balance
            $payment_amount = $inputs['amount'];
            if (!empty($inputs['amount'])) {
                $payment = FeeTransactionPayment::create($inputs);
                $student_details = Student::findOrFail($payment->payment_for);
                $student_details->advance_amount = $student_details->advance_amount + $payment->amount;
                $student_details->save();
            }
                
            DB::commit();
            

            $output = ['success' => true,
                            'msg' => __('english.added_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = __('english.something_went_wrong');
            $output = ['success' => false,
                          'msg' => $msg
                      ];
        }
        return redirect()->back()->with(['status' => $output]);
    }
    public function feeReceipt()
    {
        // $transaction=FeeTransaction::get();
        // foreach($transaction as $value) {
        //     //dd($value->id);
        //     $up=FeeTransaction::find($value->id);
        //     $up->before_discount_total=$value->final_total;
        //     $up->save();
        // }
        // dd($transaction);
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        return view('fee_transaction_payment.fee_receipt')->with(compact('campuses'));
    }
    public function getStudentPaymentDetails($student_id, $campus_id)
    {
        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
        $output = [];

        try {
            $output['success'] = true;

            $student_details = $this->studentUtil->getStudentDue($student_id);
            $payment_line = new FeeTransactionPayment();
           
            $student_details->total_fee = empty($student_details->total_fee) ? 0 : $student_details->total_fee;
            $student_details->total_admission_fee = empty($student_details->total_admission_fee) ? 0 : $student_details->total_admission_fee;

            $payment_line->amount = $student_details->total_fee -
                                    $student_details->total_fee_paid +($student_details->total_admission_fee - $student_details->total_admission_fee_paid);
            

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
            $accounts = $this->feeTransactionUtil->accountsDropdown(1, $student_details->campus_id, false, false, true, true);
            // dd($student);
            $output['html_content'] =  view('fee_transaction_payment.student_details')
            ->with(compact('student_details', 'payment_types', 'payment_line', 'ob_due', 'amount_formatted', 'accounts'))
            ->render();
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output['success'] = false;
            $output['msg'] = __('english.something_went_wrong');
        }

        return $output;
    }

    /**
     * Adds Payments for Student due
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function feeReceiptPayStudentDue(Request  $request)
    {

        if (!auth()->user()->can('fee.add_fee_payment')) {
            abort(403, 'Unauthorized action.');
        }
        $student_id = $request->input('student_id');
        try {
            $advance_balance=$this->feeTransactionUtil->num_uf($request->input('balance'));
            $check_advance_cash=$request->input('method');
            DB::beginTransaction();

            if ($advance_balance>0) {
                $student = Student::findOrFail($student_id);
                $month_fee=$student->student_tuition_fee;
                if ($student->is_transport) {
                    $month_fee +=$student->student_transport_fee;
                }
                $transaction=FeeTransaction::where('session_id', $this->feeTransactionUtil->getActiveSession())->where('student_id', $student_id)->latest('id')->first();
                $check_divide=$advance_balance/$month_fee;
                if (in_array($check_divide, [1,2,3,4,5,6,7,8,9,10,11,12])) {
                      
                    for ($x = 1; $x <= $check_divide; $x++) {
                        $month=$transaction->month+$x;
                        if($month>=13){
                            DB::rollBack();
                            $output['success'] = false;
                            $output['msg'] = __('english.in_this_session_fee_already_exists_or_paid');
                            return $output;

                        }
                        $lines_formatted=[];

                        $tuition_fee=[
                    'fee_head_id'=>2,
                    'amount'=>$this->studentUtil->num_uf($student->student_tuition_fee)
                ];
                        $lines_formatted[]=new FeeTransactionLine($tuition_fee);
                        if ($student->is_transport) {
                            $transport_fee=[
                        'fee_head_id'=>3,
                        'amount'=>$this->studentUtil->num_uf($student->student_transport_fee)
                    ];
                            $lines_formatted[]=new FeeTransactionLine($transport_fee);
                        }
                        //dd($lines_formatted);
                        $user_id = $request->session()->get('user.id');
                        $system_settings_id = $request->session()->get('user.system_settings_id');
                        $discount=null;
                        $final_total=$this->feeTransactionUtil->getFinalWithoutDiscount($lines_formatted, null);
                
                        if ($final_total !=0) {
                            $fee_transaction=$this->feeTransactionUtil->multiFeeTransaction($student, 'fee', $system_settings_id, $user_id, $lines_formatted, $final_total, $discount, $month);
                        }
                    }
                }else{
                    DB::rollBack();
                    $output['success'] = false;
                    $output['msg'] = __('english.invalid_amount');
                    return $output;
                }
               
            }
            $parent_payment=$this->feeTransactionUtil->payStudent($request);

            DB::commit();
            //$student = $this->studentUtil->getStudentDue($student_id);
           // $response=$this->notificationUtil->autoSendStudentPaymentNotification('owner_payment_received', $student, $parent_payment);

            
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

   
}
