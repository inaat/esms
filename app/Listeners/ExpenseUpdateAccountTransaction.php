<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\AccountTransaction;


class ExpenseUpdateAccountTransaction
{
   
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {

        AccountTransaction::expenseUpdateAccountTransaction($event->expenseTransactionPayment, $event->transactionType);
    }
}
