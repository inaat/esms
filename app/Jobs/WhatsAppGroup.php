<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;

class WhatsAppGroup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $group_name;
    protected $sms_body;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group_name,$sms_body)
    { 
        $this->sms_body = $sms_body;
        $this->group_name = $group_name;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        
        $response = $client->post(
            'http://127.0.0.1:8000/group-send',
            ['body' => json_encode(
            [
                "group_name"=> $this->group_name,
                "message"=> $this->sms_body,
            ]
        )]
        );
    }
}
