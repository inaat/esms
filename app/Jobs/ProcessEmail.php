<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Models\EmailLog;
use App\Models\EmailCreditLog;
use App\Models\User;
use App\Models\Admin;
use App\Models\GeneralSetting;
use App\Models\MailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Utility\SendEmail;
use Exception;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $emailLogId;


    public function __construct($emailLogId)
    {
        $this->emailLogId = $emailLogId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailLog = EmailLog::find($this->emailLogId);
        if(!$emailLog){
            return;
        }
        $general = GeneralSetting::first();
        $emailMethod = MailConfiguration::where('status',1)->where('id', $general->email_gateway_id)->first();
        $emailLog->sender_id = $emailMethod->id;
        $emailLog->save();
        
        if($emailLog->user_id){
            $user = User::where('id', $emailLog->user_id)->first();
            if($emailMethod->name != "PHP MAIL"){
                $emailFrom      = $emailMethod->driver_information->from->address;
                $emailFromName  = $emailLog->from_name==''?$emailMethod->driver_information->from->name:$emailLog->from_name;
                $emailReplyTo   = $emailLog->reply_to_email==''?$user->email:$emailLog->reply_to_email;
            }else{
                $emailFrom      = $general->mail_from;
                $emailFromName  = $general->site_name;
                $emailReplyTo   = $general->mail_from;
            }
            
        }else{
            if($emailMethod->name != "PHP MAIL"){
                $admin = Admin::where('id', 1)->first(); 
                $emailFrom      = $emailMethod->driver_information->from->address;
                $emailFromName  = $emailLog->from_name==''?$emailMethod->driver_information->from->name:$emailLog->from_name;
                $emailReplyTo   = $emailLog->reply_to_email==''?$admin->email:$emailLog->reply_to_email;
            }else{
                $emailFrom      = $general->mail_from;
                $emailFromName  = $general->site_name;
                $emailReplyTo   = $general->mail_from;
            }
        }
        
        $emailTo = $emailLog->to; $subject = $emailLog->subject; $messages = $emailLog->message;

        if($emailMethod->name == "PHP MAIL"){
            SendEmail::SendPHPmail($emailFrom, $emailFromName, $emailTo, $subject, $messages, $emailLog);
        }elseif($emailMethod->name == "SMTP"){
            SendEmail::SendSmtpMail($emailFrom, $emailFromName, $emailTo, $emailReplyTo, $subject, $messages, $emailLog);
        }elseif($emailMethod->name == "SendGrid Api"){
            SendEmail::SendGrid($emailFrom, $emailFromName, $emailTo, $subject, $messages, $emailLog, @$emailMethod->driver_information->app_key);
        }
    }

    public function failed($exception)
    {
        $data = EmailLog::find($this->emailLogId);
        if ($data->status==EmailLog::PENDING) {
            $data->status = EmailLog::FAILED;
            $data->save();

            $user = User::find($data->user_id);
            if($user){
                $user->email_credit += 1;
                $user->save();
                $emailCredit = new EmailCreditLog();
                $emailCredit->user_id = $user->id;
                $emailCredit->type = "+";
                $emailCredit->credit = 1;
                $emailCredit->trx_number = trxNumber();
                $emailCredit->post_credit =  $user->email_credit;
                $emailCredit->details = "Credit Added for failed " .$data->to;
                $emailCredit->save();
            }
        }
    }
}
