<?php

namespace App\Utils;

// use \Notification;
use App\Models\SystemSetting;
use App\Models\HumanRM\HrmNotificationTemplate;
use App\Models\Sms;
use Config;

class NotificationUtil extends Util
{

    /**
     * Automatically send notification to customer/supplier if enabled in the template setting
     *
     * @param  int  $business_id
     * @param  string  $notification_type
     * @param  obj  $transaction
     * @param  obj  $contact
     *
     * @return void
     */
    public function autoSendNotification($notification_type, $employee, $attendance)
    {
        $notification_template = HrmNotificationTemplate::
                where('template_for', $notification_type)
                ->first();
        $business = SystemSetting::findOrFail(1);

        $data['email_settings'] = $business->email_settings;
        $data['sms_settings'] = $business->sms_settings;
    
        $whatsapp_link = '';
         if (!empty($notification_template)) {
        if (!empty($notification_template->auto_send) || !empty($notification_template->auto_send_sms) || !empty($notification_template->auto_send_wa_notif)) {
            $orig_data = [
                    'email_body' => $notification_template->email_body,
                    'sms_body' => $notification_template->sms_body,
                    'subject' => $notification_template->subject,
                    'whatsapp_text' => $notification_template->whatsapp_text,
                ];
            
        
                  $tag_replaced_data = $this->replaceAttendanceTags($orig_data, $employee , $attendance);
                  //dd($tag_replaced_data);
                  $data['email_body'] = $tag_replaced_data['email_body'];
                  $data['sms_body'] = $tag_replaced_data['sms_body'];
                  $data['whatsapp_text'] = $tag_replaced_data['whatsapp_text'];

                //Auto send sms
                if (!empty($notification_template->auto_send_sms)) {
                    $data['mobile_number'] = $employee->mobile_no;
                    if (!empty($employee->mobile_no)) {

                        try {
                            $sms_send_through_app =  config('constants.sms_send_through_app');
                            if ($sms_send_through_app){
                              
                                 $sms_insert=[
                                    'sms_body'=>$data['sms_body'],
                                    'mobile'=>$data['mobile_number']
                                 ];
                                 Sms::create($sms_insert);
                            }else{
                                $whatsapp_link=  $this->sendSms($data);
                            }
                           // $this->activityLog($transaction, 'sms_notification_sent', null, [], false, $business_id);

                        } catch (\Exception $e) {
                            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                        }
                    }
                }
           }
         }

        return $whatsapp_link;
    }
/**
     * Replaces tags from notification body with original value
     *
     * @param  text  $body
     * @param  int  $transaction_id
     *
     * @return array
     */
    public function replaceAttendanceTags($data, $employee , $attendance)
    {   
        $system_details = SystemSetting::findOrFail(1);
        
        foreach ($data as $key => $value) {
             //Replace employee_name 
             if (strpos($value, '{employee_name}') !== false) {
                $employee_name = empty($employee) ? $employee->first_name . ' '.$employee->last_name : $employee->first_name. ' '.$employee->last_name ;

                $data[$key] = str_replace('{employee_name}', ucwords($employee_name), $data[$key]);
            }
             //Replace employee_Id 
             if (strpos($value, '{employee_id}') !== false) {
                $employee_id = empty($employee) ? $employee->employeeID  : $employee->employeeID ;

                $data[$key] = str_replace('{employee_id}', $employee_id, $data[$key]);
            }
             //Replace clock_in_time
             if (strpos($value, '{clock_in_time}') !== false) {
                $clock_in_time = empty($attendance) ? $attendance->clock_in_time  : $attendance->clock_in_time ;
                
                $data[$key] = str_replace('{clock_in_time}', $this->format_date($clock_in_time,true,$system_details), $data[$key]);
            }
             //Replace clock_out_time
             if (strpos($value, '{clock_out_time}') !== false) {
                $clock_out_time = empty($attendance) ? $attendance->clock_out_time  : $attendance->clock_out_time ;
                
                $data[$key] = str_replace('{clock_out_time}', $this->format_date($clock_out_time,true,$system_details), $data[$key]);
            }
             //Replace org_name
             if (strpos($value, '{org_name}') !== false) {
                $org_name = empty($system_details) ? $system_details->org_name  : $system_details->org_name ;
                
                $data[$key] = str_replace('{org_name}', $org_name, $data[$key]);
            }
             //Replace org_address
             if (strpos($value, '{org_address}') !== false) {
                $org_address = empty($system_details) ? $system_details->org_address  : $system_details->org_address ;
                
                $data[$key] = str_replace('{org_address}', $org_address, $data[$key]);
            }
             //Replace org_contact_number
             if (strpos($value, '{org_contact_number}') !== false) {
                $org_contact_number = empty($system_details) ? $system_details->org_contact_number  : $system_details->org_contact_number ;
                
                $data[$key] = str_replace('{org_contact_number}', $org_contact_number, $data[$key]);
            }
        }

        return $data;
    }
    public function autoSendStudentNotification($notification_type, $student, $attendance)
    {

        $notification_template = HrmNotificationTemplate::
                where('template_for', $notification_type)
                ->first();
        $business = SystemSetting::findOrFail(1);

        $data['email_settings'] = $business->email_settings;
        $data['sms_settings'] = $business->sms_settings;
    
        $whatsapp_link = '';
         if (!empty($notification_template)) {
        if (!empty($notification_template->auto_send) || !empty($notification_template->auto_send_sms) || !empty($notification_template->auto_send_wa_notif)) {
            $orig_data = [
                    'email_body' => $notification_template->email_body,
                    'sms_body' => $notification_template->sms_body,
                    'subject' => $notification_template->subject,
                    'whatsapp_text' => $notification_template->whatsapp_text,
                ];
            
                $tag_replaced_data = $this->replaceStudentAttendanceTags($orig_data, $student , $attendance);
                  $data['email_body'] = $tag_replaced_data['email_body'];
                  $data['sms_body'] = $tag_replaced_data['sms_body'];
                  $data['whatsapp_text'] = $tag_replaced_data['whatsapp_text'];

                //Auto send sms
                if (!empty($notification_template->auto_send_sms)) {
                    $data['mobile_number'] = $student->mobile_no;
                    //$data['mobile_number'] = '03428927305';
                    if (!empty($student->mobile_no)) {

                        try {
                            $sms_send_through_app =  config('constants.sms_send_through_app');
                            if ($sms_send_through_app){

                                 $sms_insert=[
                                    'sms_body'=>$data['sms_body'],
                                    'mobile'=>$data['mobile_number']
                                 ];
                                 Sms::create($sms_insert);
                            }else{
                                $whatsapp_link=  $this->sendSms($data);
                            }                        
                           // $this->activityLog($transaction, 'sms_notification_sent', null, [], false, $business_id);

                        } catch (\Exception $e) {
                            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                        }
                    }
                }
           }
         }

        return $whatsapp_link;
    }
    public function replaceStudentAttendanceTags($data, $student , $attendance,$payment=null)
    {   
        $system_details = SystemSetting::findOrFail(1);
        
        foreach ($data as $key => $value) {
             //Replace student_name 
             if (strpos($value, '{student_name}') !== false) {
                $student_name = empty($student) ? $student->first_name . ' '.$student->last_name : $student->first_name. ' '.$student->last_name ;

                $data[$key] = str_replace('{student_name}', ucwords($student_name), $data[$key]);
            }
            //Replace total_due
             if (strpos($value, '{total_due}') !== false) {
                $total_due = empty($student) ? $student->total_due : $student->total_due ;

                $data[$key] = str_replace('{total_due}', number_format($total_due,0), $data[$key]);
            }
             if (strpos($value, '{current_class}') !== false) {
                 if(!empty($student->current_class->title)){
                $current_class = empty($student) ? $student->current_class->title : $student->current_class->title;
                $data[$key] = str_replace('{current_class}', ucwords($current_class), $data[$key]);

                }
                else{
                    $current_class = empty($student) ? $student->current_class: $student->current_class;
                    $data[$key] = str_replace('{current_class}', ucwords($current_class), $data[$key]);


                }
            }
             //Replace paid_amount 
             if (strpos($value, '{paid_amount}') !== false) {
                $paid_amount = empty($payment) ? $payment->amount : $payment->amount;

                $data[$key] = str_replace('{paid_amount}', $paid_amount, $data[$key]);
            }
            //Replace payment_ref_no 

             if (strpos($value, '{payment_ref_no}') !== false) {
                $payment_ref_no = empty($payment) ? $payment->payment_ref_no : $payment->payment_ref_no;

                $data[$key] = str_replace('{payment_ref_no}', $payment_ref_no, $data[$key]);
            }
            //Replace paid_on 
             if (strpos($value, '{paid_on}') !== false) {
                $paid_on = empty($payment) ? $this->format_date($payment->paid_on,true,$system_details)  : $payment->paid_on;

                $data[$key] = str_replace('{paid_on}', $paid_on, $data[$key]);
            }
             //Replace student_roll_no 
             if (strpos($value, '{student_roll_no}') !== false) {
                $student_roll_no = empty($student) ? $student->roll_no : $student->roll_no;

                $data[$key] = str_replace('{student_roll_no}', $student_roll_no, $data[$key]);
            }
             //Replace father_name 
             if (strpos($value, '{father_name}') !== false) {
                $father_name = empty($student) ? $student->father_name : $student->father_name;

                $data[$key] = str_replace('{father_name}', $father_name, $data[$key]);
            }
            if ($attendance) {
                //Replace clock_in_time
                if (strpos($value, '{clock_in_time}') !== false) {
                    $clock_in_time = empty($attendance) ? $attendance->clock_in_time  : $attendance->clock_in_time ;
                
                    $data[$key] = str_replace('{clock_in_time}', $this->format_date($clock_in_time, true, $system_details), $data[$key]);
                }
                //Replace clock_out_time
                if (strpos($value, '{clock_out_time}') !== false) {
                    $clock_out_time = empty($attendance) ? $attendance->clock_out_time  : $attendance->clock_out_time ;
                
                    $data[$key] = str_replace('{clock_out_time}', $this->format_date($clock_out_time, true, $system_details), $data[$key]);
                }
             } 
             //Replace org_name
             if (strpos($value, '{org_name}') !== false) {
                $org_name = empty($system_details) ? $system_details->org_name  : $system_details->org_name ;
                
                $data[$key] = str_replace('{org_name}', $org_name, $data[$key]);
            }
             //Replace org_address
             if (strpos($value, '{org_address}') !== false) {
                $org_address = empty($system_details) ? $system_details->org_address  : $system_details->org_address ;
                
                $data[$key] = str_replace('{org_address}', $org_address, $data[$key]);
            }
             //Replace org_contact_number
             if (strpos($value, '{org_contact_number}') !== false) {
                $org_contact_number = empty($system_details) ? $system_details->org_contact_number  : $system_details->org_contact_number ;
                
                $data[$key] = str_replace('{org_contact_number}', $org_contact_number, $data[$key]);
            }
        }

        return $data;
    }

    public function autoSendStudentPaymentNotification($notification_type, $student, $parent_payment,$add_second=null)
    {
        $notification_template = HrmNotificationTemplate::
                where('template_for', $notification_type)
                ->first();
        $business = SystemSetting::findOrFail(1);
        $data['add_second'] = $add_second;

        $data['email_settings'] = $business->email_settings;
        $data['sms_settings'] = $business->sms_settings;
    
        $whatsapp_link = '';
         if (!empty($notification_template )) {
        if (!empty($notification_template->auto_send) || !empty($notification_template->auto_send_sms) || !empty($notification_template->auto_send_wa_notif)) {
            $orig_data = [
                    'email_body' => $notification_template->email_body,
                    'sms_body' => $notification_template->sms_body,
                    'subject' => $notification_template->subject,
                    'whatsapp_text' => $notification_template->whatsapp_text,
                ];
            
                $tag_replaced_data = $this->replaceStudentAttendanceTags($orig_data, $student , null,$parent_payment);
                //dd($tag_replaced_data);
                $data['email_body'] = $tag_replaced_data['email_body'];
                  $data['sms_body'] = $tag_replaced_data['sms_body'];
                  $data['whatsapp_text'] = $tag_replaced_data['whatsapp_text'];

                //Auto send sms
                if (!empty($notification_template->auto_send_sms)) {
                    $data['mobile_number'] = $student->mobile_no;
                   // $data['mobile_number'] = '03428927305';
                    if (!empty($student->mobile_no)) {
                        try {
                           
                            $sms_send_through_app =  config('constants.sms_send_through_app');
                            
                            if ($sms_send_through_app){
                                 $sms_insert=[
                                    'sms_body'=>$data['sms_body'],
                                    'mobile'=>$data['mobile_number']
                                 ];
                                 Sms::create($sms_insert);
                                 
                            }else{
                                $this->sendSms($data);
                            }                        
                           // $this->activityLog($transaction, 'sms_notification_sent', null, [], false, $business_id);

                        } catch (\Exception $e) {
                            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                        }
                    }
                }
           }
         }

        return $whatsapp_link;
    }
    public function SendNotification($notification_type,$student,$attendance, $sms_body=null,$add_second=null)
    {
        $notification_template = HrmNotificationTemplate::
                where('template_for', $notification_type)
                ->first();
        $business = SystemSetting::findOrFail(1);
        
        $data['email_settings'] = $business->email_settings;
        $data['sms_settings'] = $business->sms_settings;
        $data['add_second']=$add_second;
        $whatsapp_link = '';
         if (!empty($notification_template )) {
        if (!empty($notification_template->auto_send) || !empty($notification_template->auto_send_sms) || !empty($notification_template->auto_send_wa_notif)) {
            $orig_data = [
                    'email_body' => $notification_template->email_body,
                    'sms_body' => $notification_template->sms_body,
                    'subject' => $notification_template->subject,
                    'whatsapp_text' => $notification_template->whatsapp_text,
                ];
            
                $tag_replaced_data = $this->replaceStudentAttendanceTags($orig_data, $student , null,null);
                //dd($tag_replaced_data);
                $data['email_body'] = $tag_replaced_data['email_body'];
                  $data['sms_body'] = $tag_replaced_data['sms_body'];
                  $data['whatsapp_text'] = $tag_replaced_data['whatsapp_text'];

                //Auto send sms
                if (!empty($notification_template->auto_send_sms)) {
                    $data['mobile_number'] = $student->mobile_no;
                   // $data['mobile_number'] = '03428927305';
                    if (!empty($student->mobile_no)) {
                        try {
                            $sms_send_through_app =  config('constants.sms_send_through_app');
                            if ($sms_send_through_app){
                                 $sms_insert=[
                                    'sms_body'=>$data['sms_body'],
                                    'mobile'=>$data['mobile_number']
                                 ];
                                 Sms::create($sms_insert);
                            }else{
                                $whatsapp_link=  $this->sendSms($data);
                            }                        
                           // $this->activityLog($transaction, 'sms_notification_sent', null, [], false, $business_id);

                        } catch (\Exception $e) {
                            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                        }
                    }
                }
           }
         }
         else{
            
               $data['sms_body'] = str_replace('&nbsp;',' ',strip_tags($sms_body));
              // dd($data);
                $data['mobile_number'] = $student->mobile_no;
               //$data['mobile_number'] = '03428927305';
                if (!empty($data['mobile_number'])) {
                    try {
                        $sms_send_through_app =  config('constants.sms_send_through_app');
                        if ($sms_send_through_app){
                             $sms_insert=[
                                'sms_body'=>$data['sms_body'],
                                'mobile'=>$data['mobile_number']
                             ];
                             Sms::create($sms_insert);
                        }else{
                            $whatsapp_link=  $this->sendSms($data);
                        }                    
                       // $this->activityLog($transaction, 'sms_notification_sent', null, [], false, $business_id);

                    } catch (\Exception $e) {
                        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    }
                }
             
         }

        return $whatsapp_link;
    }


    public function configureEmail($notificationInfo)
    {
        $email_settings = $notificationInfo['email_settings'];


        //Check if prefered email setting is superadmin email settings
            $email_settings['mail_driver'] = config('mail.driver');
            $email_settings['mail_host'] = config('mail.host');
            $email_settings['mail_port'] = config('mail.port');
            $email_settings['mail_username'] = config('mail.username');
            $email_settings['mail_password'] = config('mail.password');
            $email_settings['mail_encryption'] = config('mail.encryption');
            $email_settings['mail_from_address'] = config('mail.from.address');
        

        $mail_driver = !empty($email_settings['mail_driver']) ? $email_settings['mail_driver'] : 'smtp';
        Config::set('mail.driver', $mail_driver);
        Config::set('mail.host', $email_settings['mail_host']);
        Config::set('mail.port', $email_settings['mail_port']);
        Config::set('mail.username', $email_settings['mail_username']);
        Config::set('mail.password', $email_settings['mail_password']);
        Config::set('mail.encryption', $email_settings['mail_encryption']);

        Config::set('mail.from.address', $email_settings['mail_from_address']);
        Config::set('mail.from.name', $email_settings['mail_from_name']);
        dd(55);
    }
   }
