<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\HumanRM\HrmEmployee;
use App\Models\ExpenseTransaction;
use App\Models\ExpenseTransactionPayment;
use App\Events\ExpenseTransactionPaymentUpdated;
use App\Events\ExpenseTransactionPaymentAdded;

use App\Utils\ExpenseTransactionUtil;
use DB;
class ExpenseTransactionPaymentController extends Controller
{
    protected $expenseTransactionUtil;

     /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(ExpenseTransactionUtil $expenseTransactionUtil)
    {
        $this->expenseTransactionUtil = $expenseTransactionUtil;
        
    } 
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        

        if (request()->ajax()) {
            $transaction = ExpenseTransaction::where('id', $id)
                                        ->with(['employee'])
                                        ->first();
           // dd($transaction);
            $payments_query = ExpenseTransactionPayment::where('expense_transaction_id', $id);

            
            $accounts_enabled = true;
            $payments_query->with(['payment_account']);
            $payments = $payments_query->get();
            $payment_types = $this->expenseTransactionUtil->payment_types();
            return view('expense_transaction_payment.show_payments')
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
        

        if (request()->ajax()) {
            $system_settings_id = request()->session()->get('user.system_settings_id');
            $transaction = ExpenseTransaction::
                                        with(['employee', 'campus'])
                                        ->findOrFail($transaction_id);
            if ($transaction->payment_status != 'paid') {
                $payment_types = $this->expenseTransactionUtil->payment_types();

                $paid_amount = $this->expenseTransactionUtil->getTotalPaid($transaction_id);
                $amount = $transaction->final_total - $paid_amount;
                if ($amount < 0) {
                    $amount = 0;
                }

                $amount_formated = $this->expenseTransactionUtil->num_f($amount);

                $payment_line = new ExpenseTransactionPayment();
                $payment_line->amount = $amount;
                $payment_line->method = 'cash';
                $payment_line->paid_on = \Carbon::now()->toDateTimeString();

                //Accounts
                $accounts =$this->expenseTransactionUtil->accountsDropdown($system_settings_id,$transaction->campus_id,false,false,true,true);

                $view = view('expense_transaction_payment.payment_row')
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
       try {
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $transaction_id = $request->input('transaction_id');
            $transaction = ExpenseTransaction::with(['employee'])->findOrFail($transaction_id);

            $transaction_before = $transaction->replicate();

         
            if ($transaction->payment_status != 'paid') {
                $inputs = $request->only(['amount','discount_amount', 'method', 'note', 'card_number', 'card_holder_name',
                'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
                'cheque_number', 'bank_account_number']);
                $inputs['paid_on'] = $this->expenseTransactionUtil->uf_date($request->input('paid_on'), true);
                $inputs['expense_transaction_id'] = $transaction->id;
                $inputs['campus_id'] = $transaction->campus_id;
                $inputs['payment_for'] = $transaction->expense_for;
                $inputs['amount'] = $this->expenseTransactionUtil->num_uf($inputs['amount']);
                $inputs['created_by'] = auth()->user()->id;
                $inputs['account_id'] = $request->input('account_id');
                $inputs['session_id']=$this->expenseTransactionUtil->getActiveSession();


                $prefix_type = 'expense_payment';
                DB::beginTransaction();

                $ref_count = $this->expenseTransactionUtil->setAndGetReferenceCount($prefix_type, false, true);
                        //Generate reference number
                $inputs['payment_ref_no'] = $this->expenseTransactionUtil->generateReferenceNumber($prefix_type, $ref_count, $system_settings_id);

                $inputs['document'] = $this->expenseTransactionUtil->uploadFile($request, 'document', 'documents');

                //Pay from advance balance
                $payment_amount = $inputs['amount'];
                if (!empty($inputs['amount'])) {
                    $tp = ExpenseTransactionPayment::create($inputs);

                    $inputs['transaction_type'] = $transaction->type;
                    event(new ExpenseTransactionPaymentAdded($tp, $inputs));
                }

                //update payment status
                $payment_status = $this->expenseTransactionUtil->updatePaymentStatus($transaction_id, $transaction->final_total);
                
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
    public function edit($id)
    {
       

        if (request()->ajax()) {
            $system_settings_id = request()->session()->get('user.system_settings_id');

            $payment_line = ExpenseTransactionPayment::where('method', '!=', 'advance')->findOrFail($id);

            $transaction = ExpenseTransaction::where('id', $payment_line->expense_transaction_id)
                                        ->with(['employee', 'campus'])
                                        ->first();
            $payment_types = $this->expenseTransactionUtil->payment_types();

            //Accounts
            $accounts =$this->expenseTransactionUtil->accountsDropdown($system_settings_id,$transaction->campus_id,false,false,true,true);

            return view('expense_transaction_payment.edit_payment_row')
                        ->with(compact('transaction', 'payment_types', 'payment_line', 'accounts'));
        }
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
        

        try {
            $inputs = $request->only(['amount','discount_amount', 'method', 'note', 'card_number', 'card_holder_name',
            'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
            'cheque_number', 'bank_account_number']);
            $inputs['paid_on'] = $this->expenseTransactionUtil->uf_date($request->input('paid_on'), true);
            $inputs['amount'] = $this->expenseTransactionUtil->num_uf($inputs['amount']);

         

            if (!empty($request->input('account_id'))) {
                $inputs['account_id'] = $request->input('account_id');
            }

            $payment = ExpenseTransactionPayment::where('method', '!=', 'advance')->findOrFail($id);
            //Update parent payment if exists
            if (!empty($payment->parent_id)) {
                $parent_payment = ExpenseTransactionPayment::find($payment->parent_id);
                $parent_payment->amount = $parent_payment->amount - ($payment->amount - $inputs['amount']);                    
                $parent_payment->save();
            }

            $system_settings_id = $request->session()->get('user.system_settings_id');

            $transaction = ExpenseTransaction::find($payment->expense_transaction_id);
            $transaction_before = $transaction->replicate();

            $document_name = $this->expenseTransactionUtil->uploadFile($request, 'document', 'documents');
            if (!empty($document_name)) {
                $inputs['document'] = $document_name;
            }
                               
            DB::beginTransaction();

            $payment->update($inputs);

            //update payment status
            $payment_status = $this->expenseTransactionUtil->updatePaymentStatus($payment->expense_transaction_id);
            $transaction->payment_status = $payment_status;

            //event
            event(new ExpenseTransactionPaymentUpdated($payment, $transaction->type));
            DB::commit();


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
       
        if (request()->ajax()) {
            try {

                $payment = ExpenseTransactionPayment::findOrFail($id);
               // dd($payment);
                DB::beginTransaction();

                if (!empty($payment->transaction_id)) {
                    ExpenseTransactionPayment::deletePayment($payment);
                } else { //advance payment
                    $adjusted_payments = ExpenseTransactionPayment::where('parent_id', 
                                                $payment->id)
                                                ->get();

                    $total_adjusted_amount = $adjusted_payments->sum('amount');
                   
                    //Delete all child payments
                    foreach ($adjusted_payments as $adjusted_payment) {
                        //Make parent payment null as it will get deleted
                        $adjusted_payment->parent_id = null;
                        ExpenseTransactionPayment::deletePayment($adjusted_payment);
                    }

                    //Delete advance payment
                    ExpenseTransactionPayment::deletePayment($payment);
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

}
