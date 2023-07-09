<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ExpenseTransactionPayment;


class ExpenseTransactionPaymentAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $expenseTransactionPayment;
    public $formInput;

    /**
     * Create a new event instance.
     *
     * @param  Order  $order
     * @param  array $formInput = []
     * @return void
     */
    public function __construct(ExpenseTransactionPayment $expenseTransactionPayment, $formInput = [])
    {
        $this->expenseTransactionPayment = $expenseTransactionPayment;
        $this->formInput = $formInput;
    }
}
