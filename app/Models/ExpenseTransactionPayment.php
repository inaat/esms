<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\ExpenseTransactionPaymentDeleted;
use App\Events\ExpenseTransactionPaymentUpdated;

class ExpenseTransactionPayment extends Model
{
    use HasFactory;
    

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

        /**
     * Get the phone record associated with the user.
     */
    public function payment_account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'account_id');
    }
  
    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
    public function employee()
    {
        return $this->belongsTo(\App\Models\HumanRM\HrmEmployee::class, 'payment_for');
    }
    /**
     * Get the transaction related to this payment.
     */
    public function expense_transaction()
    {
        return $this->belongsTo(ExpenseTransaction::class, 'expense_transaction_id');
    }
    public static function deletePayment($payment)
    {
        //Update parent payment if exists
        if (!empty($payment->parent_id)) {
            $parent_payment = ExpenseTransactionPayment::find($payment->parent_id);
            $parent_payment->amount -= $payment->amount;

            if ($parent_payment->amount <= 0) {
                $parent_payment->delete();
                event(new ExpenseTransactionPaymentDeleted($parent_payment));
            } else {
                $parent_payment->save();
                //Add event to update parent payment account transaction
                event(new ExpenseTransactionPaymentUpdated($parent_payment, null));
            }
        }

        $payment->delete();

        if(!empty($payment->expense_transaction_id)) {
            $expenseTransactionUtil = new \App\Utils\ExpenseTransactionUtil();
            //update payment status
            $transaction = $payment->load('expense_transaction')->expense_transaction;
            $transaction_before = $transaction->replicate();

            $payment_status = $expenseTransactionUtil->updatePaymentStatus($payment->expense_transaction_id);

            $transaction->payment_status = $payment_status;
            
        }

        //Add event to delete account transaction
        event(new ExpenseTransactionPaymentDeleted($payment));
        
    }
}
