<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\WhatsappDevice;
use App\Models\WhatsappLog;
use Exception;


use App\Services\WhatsappApiService;


class ProcessWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $number;
    protected $logId;
    protected $postData;
    private $whatsappApiService;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $number, $logId, $postData,$whatsappApiService)
    {
        $this->message = $message;
        $this->number = $number;
        $this->logId = $logId;
        $this->postData = $postData;
        $this->whatsappApiService = $whatsappApiService;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $whatsappLog = WhatsappLog::with('whatsappGateway')->find(trim($this->logId));
     
        if(!$whatsappLog){
            return false;
        }
      
     

        //send api
        $response = null;
        try{
            $whatsapp = WhatsappDevice::first();
            //$response=$this->whatsappApiService->sendTestMsg($whatsapp->name,'923428927305','dfghj');
            $response=$this->whatsappApiService->sendTestMsg($whatsapp->name, str_replace('+', '',$this->number),$this->message);
            \Log::emergency($response);
        \Log::emergency("response");
           
            if ($response) {
                $res = $response;

                if($res['error'] ==false){
                    $whatsappLog->status = WhatsappLog::SUCCESS;
                    $whatsappLog->save();
                }else{
                    $whatsappLog->status = WhatsappLog::FAILED;
                    $whatsappLog->response_gateway = $res['message'];
                    $whatsappLog->save();

                }
            }else{
                $whatsappLog->status = WhatsappLog::FAILED;
                $whatsappLog->response_gateway = 'Error::2 Failed to send the message.';
                $whatsappLog->save();
                
            }
        } catch(Exception $e){
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        }
    }
    
}
