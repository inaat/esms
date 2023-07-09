<?php

namespace App\Utils;

use App\Models\Campus;
use App\Models\ExpenseCategory;
use App\Models\ExpenseTransactionPayment;
use App\Models\ExpenseTransaction;
use App\Models\HumanRM\HrmEmployee;
use App\Utils\Util;
use App\Events\ExpenseTransactionPaymentAdded;
use DB;
class ExpenseTransactionUtil extends Util
{
    public function createExpense($request, $user_id, $format_data = true)
    {
        $transaction_data = $request->only([ 'ref_no', 'transaction_date', 
            'campus_id', 'final_total', 'expense_for', 'additional_notes','vehicle_id', 
            'expense_category_id']);
           
        $transaction_data['session_id']=$this->getActiveSession();
        $transaction_data['created_by'] = $user_id;
        $transaction_data['type'] =  'expense';
        $transaction_data['status'] = 'final';
        $transaction_data['payment_status'] = 'due';
        $transaction_data['final_total'] = $format_data ? $this->num_uf(
                $transaction_data['final_total']
            ) : $transaction_data['final_total'];
        if ($request->has('transaction_date')) {
            $transaction_data['transaction_date'] = $format_data ? $this->uf_date($transaction_data['transaction_date'], true) : $transaction_data['transaction_date'];
        } else {
            $transaction_data['transaction_date'] = \Carbon::now();
        }

        

      

        //Update reference count
        $ref_count = $this->setAndGetReferenceCount('expense',false, true);
        //Generate reference number
        if (empty($transaction_data['ref_no'])) {
            $transaction_data['ref_no'] = $this->generateReferenceNumber('expense', $ref_count,1);
        }

        //upload document
        $document_name = $this->uploadFile($request, 'document', 'documents');
        if (!empty($document_name)) {
            $transaction_data['document'] = $document_name;
        }
        $transaction = ExpenseTransaction::create($transaction_data);
        
        //add expense payment
        if(!empty($request->input('amount'))) {
         $this->payExpense($request, $transaction);
        }
        //update payment status
        $this->updatePaymentStatus($transaction->id, $transaction->final_total);

        return $transaction;
    }
    public function updatePaymentStatus($transaction_id, $final_amount = null)
    {
        $status = $this->calculatePaymentStatus($transaction_id, $final_amount);
        ExpenseTransaction::where('id', $transaction_id)
            ->update(['payment_status' => $status]);

        return $status;
    }

    public function calculatePaymentStatus($transaction_id, $final_amount = null)
    {
        $total_paid = $this->getTotalPaid($transaction_id);
        if (is_null($final_amount)) {
            $final_amount = ExpenseTransaction::find($transaction_id)->final_total;
        }

        $status = 'due';
        if ($final_amount <= $total_paid) {
            $status = 'paid';
        } elseif ($total_paid > 0 && $final_amount > $total_paid) {
            $status = 'partial';
        }

        return $status;
    }
    public function getTotalPaid($transaction_id)
    {
        $total_paid = ExpenseTransactionPayment::where('expense_transaction_id', $transaction_id)
                ->select(DB::raw('SUM(IF( is_return = 0, amount, amount*-1))as total_paid'))
                ->first()
                ->total_paid;

        return $total_paid;
    }
    public function updateExpense($request, $id,$format_data = true)
    {
        $transaction_data = [];
        $transaction = ExpenseTransaction::findOrFail($id);

        if ($request->has('ref_no')) {
            $transaction_data['ref_no'] = $request->input('ref_no');
        }
        if ($request->has('expense_for')) {
            $transaction_data['expense_for'] = $request->input('expense_for');
        }
        if ($request->has('vehicle_id')) {
            $transaction_data['vehicle_id'] = $request->input('vehicle_id');
        }
        if ($request->has('transaction_date')) {
            $transaction_data['transaction_date'] = $format_data ? $this->uf_date($request->input('transaction_date'), true) : $request->input('transaction_date');
        }
        if ($request->has('campus_id')) {
            $transaction_data['campus_id'] = $request->input('campus_id');
        }
        if ($request->has('additional_notes')) {
            $transaction_data['additional_notes'] = $request->input('additional_notes');
        }

    

        if ($request->has('expense_category_id')) {
            $transaction_data['expense_category_id'] = $request->input('expense_category_id');
        }
        $final_total = $request->has('final_total') ? $request->input('final_total') : $transaction->final_total;
        if ($request->has('final_total')) {
            $transaction_data['final_total'] = $format_data ? $this->num_uf(
                $final_total
            ) : $final_total;
            $final_total = $transaction_data['final_total'];
        }

       

        //upload document
        $document_name = $this->uploadFile($request, 'document', 'documents');
        if (!empty($document_name)) {
            $transaction_data['document'] = $document_name;
        }

        $transaction->update($transaction_data);
        $transaction->save();

        //update payment status
        $this->updatePaymentStatus($transaction->id, $transaction->final_total);

        return $transaction;
    }
    public function payExpense($request,  $transaction,$format_data = true)
    {
        $system_settings_id = auth()->user()->system_settings_id;
        $inputs = $request->only(['amount', 'discount_amount','method', 'note', 'card_number', 'card_holder_name',
            'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
            'cheque_number', 'bank_account_number']);

        $payment_types = $this->payment_types();
        $inputs['session_id']=$this->getActiveSession();

        if (!array_key_exists($inputs['method'], $payment_types)) {
            throw new \Exception("Payment method not found");
        }
        $inputs['paid_on'] = $request->input('paid_on', \Carbon::now()->toDateTimeString());
        if ($format_data) {
            $inputs['paid_on'] = $this->uf_date($inputs['paid_on'], true);
            $inputs['amount'] = $this->num_uf($inputs['amount']);
        }
        
        $inputs['created_by'] = auth()->user()->id;
        $due_payment_type = 'expense';
        $prefix_type = 'expense_payment';
        $ref_count = $this->setAndGetReferenceCount($prefix_type, false, true);
        //Generate reference number
        $payment_ref_no = $this->generateReferenceNumber($prefix_type, $ref_count, $system_settings_id);

        $inputs['payment_ref_no'] = $payment_ref_no;

        if (!empty($request->input('account_id'))) {
            $inputs['account_id'] = $request->input('account_id');
        }

        //Upload documents if added
        $inputs['document'] = $this->uploadFile($request, 'document', 'documents');
        
        $inputs['expense_transaction_id']=$transaction->id;
        $inputs['payment_for'] = $transaction->expense_for;
        $inputs['campus_id']=$transaction->campus_id;
        
        $parent_payment = ExpenseTransactionPayment::create($inputs);
        $inputs['transaction_type'] = $due_payment_type;
        event(new ExpenseTransactionPaymentAdded($parent_payment, $inputs));
        return $parent_payment;
    } 


    public function deleteExpense( $transaction_id)
    {
      
        try {
            $output = ['success' => 0,
            'msg' => trans("messages.something_went_wrong")
            ];
            DB::beginTransaction();
        $transaction = ExpenseTransaction::where('id', $transaction_id)
                    ->with(['payment_lines'])
                    ->first();

        if (!empty($transaction)) {
         
              
            $transaction_payments = $transaction->payment_lines;

        $transaction->delete();
        foreach ($transaction_payments as $payment) {
                    event(new ExpenseTransactionPaymentDeleted($payment));
                }
            
        }

        $output = [
                    'success' => true,
                    'msg' => __('english.sale_delete_success')
                ];
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => 0,
                        'msg' => trans("messages.something_went_wrong")
                        ];
            }

            return $output;
        
    }


    public function getTotalExpense($start_date = null, $end_date = null,$campus_id=null)
    {
        $query = ExpenseTransaction::select(DB::raw("COALESCE(SUM(IF(expense_transactions.type='expense', final_total, 0)),0) as total_expense"));
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
        }
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id',$campus_id);
        }
         
        return $query->first()->total_expense;
    }
    
    public function getExpensesLast30Days($campus_id)
    {
        $query = ExpenseTransactionPayment::whereBetween(DB::raw('date(paid_on)'), [\Carbon::now()->subDays(30), \Carbon::now()]);

        // Check for permitted campuses of a user
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
         $query->where('campus_id',$campus_id);
     }

        $query->select(
            DB::raw("DATE_FORMAT(paid_on, '%Y-%m-%d') as date"),
            DB::raw('COALESCE(SUM(IF( is_return = 0, amount, amount*-1)),0)as total_expense')
        )
        ->groupBy(DB::raw('Date(paid_on)'));

        // if ($group_by_location) {
        //     $query->addSelect('fee_transactions.campus_id');
        //     $query->groupBy('fee_transactions.campus_id');
        // }
        $sells = $query->get();

        $sells = $sells->pluck('total_expense', 'date');

        return $sells;
    }
    public function getExpenseTotals($start_date = null, $end_date = null, $campus_id = null, $created_by = null)
    {
    $query = expenseTransaction::select(
      DB::raw('SUM(final_total) as total_expense'),
      DB::raw('SUM(final_total - (SELECT COALESCE(SUM(tp.amount), 0) FROM expense_transaction_payments as tp WHERE tp.expense_transaction_id = expense_transactions.id) )  as total_expense_due'),
    
    );
    
    
    if (!empty($start_date) && !empty($end_date)) {
      $query->whereDate('expense_transactions.transaction_date', '>=', $start_date)
          ->whereDate('expense_transactions.transaction_date', '<=', $end_date);
    }
    
    if (empty($start_date) && !empty($end_date)) {
      $query->whereDate('expense_transactions.transaction_date', '<=', $end_date);
    }
    
    //Filter by the campus
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
     $query->whereIn('expense_transactions.campus_id', $permitted_campuses);
    }
    if (!empty($campus_id)) {
      $query->where('expense_transactions.campus_id', $campus_id);
    }
    
    if (!empty($created_by)) {
      $query->where('expense_transactions.created_by', $created_by);
    }
    
    $expense_details = $query->first();
    
    
    $output['total_expense_due'] = $expense_details->total_expense_due;
    $output['total_expense'] = $expense_details->total_expense;
    
    return $output;
    }
     /**
     * Retrives expense report
     *
     * @param int $business_id
     * @param array $filters
     * @param string $type = by_category (by_category or total)
     *
     * @return Obj
     */
    public function getExpenseReport(
        $filters = [],
        $type = 'by_category'
    ) {
        $query = ExpenseTransaction::leftjoin('expense_categories AS ec', 'expense_transactions.expense_category_id', '=', 'ec.id')
                            ->whereIn('type', ['expense', 'expense_refund']);
        // ->where('payment_status', 'paid');

        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('expense_transactions.campus_id', $permitted_campuses);
        }

        if (!empty($filters['campus_id'])) {
            $query->where('expense_transactions.campus_id', $filters['campus_id']);
        }

        if (!empty($filters['expense_for'])) {
            $query->where('expense_transactions.expense_for', $filters['expense_for']);
        }

        if (!empty($filters['category'])) {
            $query->where('ec.id', $filters['category']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween(DB::raw('date(transaction_date)'), [$filters['start_date'],
                $filters['end_date']]);
        }

        //Check tht type of report and return data accordingly
        if ($type == 'by_category') {
            $expenses = $query->select(
                DB::raw("SUM( IF(expense_transactions.type='expense_refund', -1 * final_total, final_total) ) as total_expense"),
                'ec.name as category'
            )
                        ->groupBy('expense_category_id')
                        ->get();
        } elseif ($type == 'total') {
            $expenses = $query->select(
                DB::raw("SUM( IF(expense_transactions.type='expense_refund', -1 * final_total, final_total) ) as total_expense")
            )
                        ->first();
        }
        
        return $expenses;
    }

}
