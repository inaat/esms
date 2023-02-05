<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Events\HrmTransactionPaymentUpdated;


class HrmTransactionPaymentDeleted
{
    use SerializesModels;

    public $transactionPaymentId;

    public $accountId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($hrmTransactionPayment)
    {
        $this->hrmTransactionPayment = $hrmTransactionPayment;
    }
}
