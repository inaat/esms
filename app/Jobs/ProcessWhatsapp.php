<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\WhatsappLog;
use Exception;
use Illuminate\Support\Facades\Http;
use App\Models\HumanRM\HrmEmployee;

class ProcessWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $number;
    protected $logId;
    protected $postData;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $number, $logId, $postData)
    {
        $this->message = $message;
        $this->number = $number;
        $this->logId = $logId;
        $this->postData = $postData;
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
        if($this->message != null){
            $body = ['text'=>$this->message];
        }
        if(array_key_exists('type', $this->postData)){
            if($this->postData['type'] == "Image" ){
                $body = [
                    'image'=>[
                        'url'=>url($this->postData['url_file'])
                    ],
                    'caption'=>$this->message,
                ];

            }
            else if($this->postData['type'] == "Audio" ){
                $body = [
                    'audio'=>[
                        'url'=>url($this->postData['url_file'])
                    ],
                    'caption'=>$this->message,
                ];
            }
            else if($this->postData['type'] == "Video" ){
                $body = [
                    'video'=>[
                        'url'=>url($this->postData['url_file'])
                    ],
                    'caption'=>$this->message,
                ];
            }
            else if($this->postData['type'] == "pdf" ){
                $body = [
                    'document'=>[
                        'url'=>url($this->postData['url_file'])
                    ],
                    'mimetype' => 'application/pdf',
                    'fileName' => $this->postData['name'],
                    'caption'  => $this->message,
                ];
            }
        }

        //send api
        $response = null;
        try{
            
            $apiURL = 'http://whatsapp.sfsc.edu.pk/message/send?id='.$whatsappLog->whatsappGateway->name;
            $postInput = [
                'receiver' => trim('923428927305'),
                'message' => $body
            ];
            $headers = [
                'Content-Type' => 'application/json',
            ];
            $response = Http::withoutVerifying()->withHeaders($headers)->post($apiURL, $postInput);
           //dd(json_decode($response->getBody(), true));
            if ($response) {
                $res = json_decode($response->getBody(), true);
                if($res['success']){
                    $whatsappLog->status = WhatsappLog::SUCCESS;
                    $whatsappLog->save();
                }else{
                    $whatsappLog->status = WhatsappLog::FAILED;
                    $whatsappLog->response_gateway = $res['message'];
                    $whatsappLog->save();
                    $user = User::find($whatsappLog->user_id);
                    if($user){
                        // $messages = str_split($whatsappLog->message,260);
                        // $totalcredit = count($messages);
                        // $user->whatsapp_credit += $totalcredit;
                        // $user->save();
                        // $creditInfo = new WhatsappCreditLog();
                        // $creditInfo->user_id = $whatsappLog->user_id;
                        // $creditInfo->type = "+";
                        // $creditInfo->credit = $totalcredit;
                        // $creditInfo->trx_number = trxNumber();
                        // $creditInfo->post_credit =  $user->whatsapp_credit;
                        // $creditInfo->details = $totalcredit." Credit Return ".$whatsappLog->to." is Falied";
                        // $creditInfo->save();
                    }
                }
            }else{
                $whatsappLog->status = WhatsappLog::FAILED;
                $whatsappLog->response_gateway = 'Error::2 Failed to send the message.';
                $whatsappLog->save();
                $user = User::find($whatsappLog->user_id);
                if($user){
                    // $messages = str_split($whatsappLog->message,260);
                    // $totalcredit = count($messages);
                    // $user->whatsapp_credit += $totalcredit;
                    // $user->save();
                    // $creditInfo = new WhatsappCreditLog();
                    // $creditInfo->user_id = $whatsappLog->user_id;
                    // $creditInfo->type = "+";
                    // $creditInfo->credit = $totalcredit;
                    // $creditInfo->trx_number = trxNumber();
                    // $creditInfo->post_credit =  $user->whatsapp_credit;
                    // $creditInfo->details = $totalcredit." Credit Return ".$whatsappLog->to." is Falied";
                    // $creditInfo->save();
                }
            }
        } catch(Exception $exception){

        }
    }
    
}
