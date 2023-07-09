<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\AccountTransaction;


class ExpenseDeleteAccountTransaction
{
 
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        

        AccountTransaction::where('account_id', $event->expenseTransactionPayment ->account_id)
                        ->where('expense_transaction_payment_id', $event->expenseTransactionPayment ->id)
                        ->delete();
    }
}
