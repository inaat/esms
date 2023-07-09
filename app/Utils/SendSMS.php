<?php
namespace App\Http\Utility;
use Textmagic\Services\TextmagicRestClient;
use Twilio\Rest\Client;
use App\Models\SMSlog;
use App\Models\CreditLog;
use App\Models\User;
use Illuminate\Support\Str;
use GuzzleHttp\Client AS InfoClient;
use Infobip\Api\SendSmsApi;
use Infobip\Configuration;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Exception;


class SendSMS{

	public static function nexmo($to,$message,$credential,$smsId)
	{
		$log = SMSlog::find($smsId);
		try {
			$basic = new \Vonage\Client\Credentials\Basic($credential->api_key, $credential->api_secret);
			$client = new \Vonage\Client($basic);
			$response = $client->sms()->send(
		    	new \Vonage\SMS\Message\SMS($to, $credential->sender_id, $message)
			);
			$message = $response->current();
			if($message->getStatus() == 0){
				$log->status = SMSlog::SUCCESS;
				$log->save();
			}else{
				$log->status = SMSlog::FAILED;
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
		} catch (\Exception $e) {
			$log->status = SMSlog::FAILED;
			$log->response_gateway = $e->getMessage();
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

	public static function twilio($to,$message,$credential,$smsId)
	{
        $log = SMSlog::find($smsId);
        try{
            $twilioNumber = $credential->from_number;
            $client = new Client($credential->account_sid, $credential->auth_token);
            $create = $client->messages->create('+'.$to, [
                'from' => $twilioNumber, 
                'body' => $message
            ]);
            $log->status = SMSlog::SUCCESS;
            $log->save();
        }catch (\Exception $e) {
        	$log->status = SMSlog::FAILED;
			$log->response_gateway = $e->getMessage();
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

	public static function messageBird($to,$datacoding,$message,$credential, $smsId)
	{
		$log 		 = SMSlog::find($smsId);
		try {
			$MessageBird 		 = new \MessageBird\Client($credential->access_key);
			$Message 			 = new \MessageBird\Objects\Message();
			$Message->originator = $credential->sender_id;
			$Message->recipients = array($to); 
			$Message->datacoding = $datacoding;
			$Message->body 		 = $message;
			$MessageBird->messages->create($Message);
			
			$log->status = SMSlog::SUCCESS;
			$log->save();
		} catch (\Exception $e) {	
			$log->status = SMSlog::FAILED;
			$log->response_gateway = $e->getMessage();
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

	public static function textMagic($to,$message,$credential, $smsId)
	{
		$log = SMSlog::find($smsId);
		$client = new TextmagicRestClient($credential->text_magic_username, $credential->api_key);
		try {
		    $result = $client->messages->create(
		        array(
		            'text' => $message,
		            'phones' => $to,
		        )
		    );
		    $log->status = SMSlog::SUCCESS;
		    $log->save();
		}
		catch (\Exception $e) {
			$log->status = SMSlog::FAILED;
			$log->response_gateway = $e->getMessage();
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

	public static function clickaTell($to,$message,$credentials,$smsId)
	{
		$log = SMSlog::find($smsId);
		try {
			$message = urlencode($message);
			$response = @file_get_contents("https://platform.clickatell.com/messages/http/send?apiKey=$credentials->clickatell_api_key&to=$to&content=$message");

			if ($response==false) {
				$log->status = SMSlog::FAILED;
				$log->response_gateway = "API Error, Check Your Settings";
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
			}else{
				$log->status = SMSlog::SUCCESS;
				$log->save();
			}
			
		} catch (Throwable $e) {
			
		}
	}

	public static function infoBip($to,$message,$credentials,$smsId)
	{
		$BASE_URL = $credentials->infobip_base_url;
		$API_KEY = $credentials->infobip_api_key;

		$SENDER = $credentials->sender_id;
		$RECIPIENT = $to;
		$MESSAGE_TEXT = $message;
		 
		$configuration = (new Configuration())
		    ->setHost($BASE_URL)
		    ->setApiKeyPrefix('Authorization', 'App')
		    ->setApiKey('Authorization', $API_KEY);
		 
		$client = new InfoClient();
		 
		$sendSmsApi = new SendSMSApi($client, $configuration);
		$destination = (new SmsDestination())->setTo($RECIPIENT);
		$message = (new SmsTextualMessage())
		    ->setFrom($SENDER)
		    ->setText($MESSAGE_TEXT)
		    ->setDestinations([$destination]);
		 
		$request = (new SmsAdvancedTextualRequest())->setMessages([$message]);
		$log = SMSlog::find($smsId);
		try {
		    $smsResponse = $sendSmsApi->sendSmsMessage($request);
		    $log->status = SMSlog::SUCCESS;
			$log->save();
		} catch (Throwable $apiException) {
			
		} 
	}

	public static function smsBroadcast($to,$message,$credentials,$smsId)
	{
		$log = SMSlog::find($smsId);
		try { 
			$message = urlencode($message);
			$result = @file_get_contents("https://api.smsbroadcast.com.au/api-adv.php?username=$credentials->sms_broadcast_username&password=$credentials->sms_broadcast_password&to=$to&from=$credential->sender_id,&message=$message&ref=112233&maxsplit=5&delay=15"); 

			if ($result==Str::contains($result, 'ERROR:') || $result==Str::contains($result, 'BAD:')) { 
				$log->status = SMSlog::FAILED;
		        $log->response_gateway = $result;
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
			}else{
				$log->status = SMSlog::SUCCESS;
				$log->save();
			} 			
		} catch (Throwable $e) { 

		}
	}

}