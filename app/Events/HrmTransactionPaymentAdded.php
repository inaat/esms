<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\HumanRM\HrmTransactionPayment;


class HrmTransactionPaymentAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // /**
    //  * Create a new event instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // /**
    //  * Get the channels the event should broadcast on.
    //  *
    //  * @return \Illuminate\Broadcasting\Channel|array
    //  */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }

    public $hrmTransactionPayment;
    public $formInput;

    /**
     * Create a new event instance.
     *
     * @param  Order  $order
     * @param  array $formInput = []
     * @return void
     */
    public function __construct(HrmTransactionPayment $hrmTransactionPayment, $formInput = [])
    {
        $this->hrmTransactionPayment = $hrmTransactionPayment;
        $this->formInput = $formInput;
    }
}