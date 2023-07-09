<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Utility\SendSMS;
use App\Models\SMSlog;
use App\Models\CreditLog;
use App\Models\User;
use App\Models\GeneralSetting;
use Exception;

class ProcessSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $to;
    protected $from;
    protected $datacoding;
    protected $message;
    protected $credential;
    protected $gatewayCode;
    protected $smsId;


    public function __construct($to, $datacoding, $message, $credential, $gatewayCode, $smsId)
    {
        $this->to = $to;
        $this->datacoding = $datacoding;
        $this->message = $message;
        $this->credential = $credential;
        $this->gatewayCode = $gatewayCode;
        $this->smsId = $smsId;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        if($this->gatewayCode == "101NEX"){
            return SendSMS::nexmo($this->to,$this->message,$this->credential,$this->smsId);
        }
        elseif($this->gatewayCode == "102TWI"){
            SendSMS::twilio($this->to,$this->message,$this->credential,$this->smsId);
        }
        elseif($this->gatewayCode == "103BIRD"){
            SendSMS::messageBird($this->to,$this->datacoding,$this->message,$this->credential,$this->smsId);
        }
        elseif($this->gatewayCode == "104MAG"){
            SendSMS::textMagic($this->to,$this->message,$this->credential,$this->smsId);
        }
        elseif($this->gatewayCode == "105CLICKATELL"){
            SendSMS::clickaTell($this->to,$this->message,$this->credential,$this->smsId);
        }
        elseif($this->gatewayCode == "106INFOBIP"){
            SendSMS::infoBip($this->to,$this->message,$this->credential,$this->smsId);
        }
        elseif($this->gatewayCode == "107SMSBROADCAST"){
            SendSMS::smsBroadcast($this->to,$this->message,$this->credential,$this->smsId);
        }
    }

    public function failed($exception)
    { 
        $log = SMSlog::find($this->smsId); 
        if ($log->status==SMSlog::PENDING) {
            $log->status = SMSlog::FAILED;
            $log->response_gateway = $exception->getMessage();
            $log->save();

            $user = User::find($log->user_id);
            if($user){
                $messages = str_split($log->message,160); 
                $totalcredit = count($messages);

                $user->credit += $totalcredit;
                $user->save();

                $creditInfo = new CreditLog();
                $creditInfo->user_id = $log->user_id;
                $creditInfo->credit_type = "+";
                $creditInfo->credit = $totalcredit;
                $creditInfo->trx_number = trxNumber();
                $creditInfo->post_credit =  $user->credit;
                $creditInfo->details = $totalcredit." Credit Return ".$log->to." is Falied";
                $creditInfo->save();
            }
        } 
    }
}
