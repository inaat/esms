<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Events\HrmTransactionPaymentUpdated;


class ExpenseTransactionPaymentDeleted
{
    use SerializesModels;

    public $transactionPaymentId;

    public $accountId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($expenseTransactionPayment)
    {
        $this->expenseTransactionPayment = $expenseTransactionPayment;
    }
}
