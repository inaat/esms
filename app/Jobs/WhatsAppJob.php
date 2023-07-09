<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Exception;

class WhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tires = 3;


   // public $backoff = 3;


    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    { 
        $this->data = $data;
        
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       //throw new \Exception('Job failed to execute');
        //dd($this->data);
       //sleep(1);
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        
        $response = $client->post('http://127.0.0.1:8000/send',
            ['body' => json_encode(
                [
                    "phone"=> $this->data['mobile_number'],
                    "message"=> $this->data['sms_body'],
                ]
            )]
        );
        // $bodyResponse = $response->getBody();
        // $result = $bodyResponse->getContents();
        // $result = json_decode($result);
        // return $result;
    }
    
}
