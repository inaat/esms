<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AccountTransaction;

use App\Events\ExpenseTransactionPaymentAdded;
class ExpenseAddAccountTransaction
{
   
    
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ExpenseTransactionPaymentAdded $event)
    {
       

        // //Create new account transaction
        if (!empty($event->formInput['account_id'])) {
            $account_transaction_data = [
                // 'amount' => $event->formInput['amount']-$event->formInput['discount_amount'],
                'amount' => $event->formInput['amount'],
                'account_id' => $event->formInput['account_id'],
                'type' => AccountTransaction::getAccountTransactionType($event->formInput['transaction_type']),
                'operation_date' => $event->expenseTransactionPayment->paid_on,
                'created_by' => $event->expenseTransactionPayment->created_by,
                'transaction_id' => $event->expenseTransactionPayment->transaction_id,
                'expense_transaction_payment_id' =>  $event->expenseTransactionPayment->id
            ];

            //If change return then set type as debit
            // if ($event->formInput['transaction_type'] == 'sell' && isset($event->formInput['is_return']) && $event->formInput['is_return'] == 1) {
            //     $account_transaction_data['type'] = 'debit';
            // }
            AccountTransaction::expenseCreateAccountTransaction($account_transaction_data);
        }
    }
}

