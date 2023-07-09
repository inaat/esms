<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

use App\Models\ExpenseTransactionPayment;

class ExpenseTransactionPaymentUpdated
{
    use SerializesModels;

    public $expenseTransactionPayment;

    public $transactionType;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ExpenseTransactionPayment $expenseTransactionPayment, $transactionType)
    {
        $this->expenseTransactionPayment = $expenseTransactionPayment;
        $this->transactionType = $transactionType;
    }
}
