<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\HumanRM\HrmAttendance;
use App\Models\HumanRM\HrmEmployeeShift;
use App\Models\HumanRM\HrmShift;
use App\Models\HumanRM\HrmEmployee;
use Carbon\Carbon;
use App\Utils\NotificationUtil;
use App\Models\Student;
use App\Models\Attendance;
use GuzzleHttp\Client;

class MappingController extends Controller
{
        /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
   public function __construct(NotificationUtil $notificationUtil)
   {
       $this->notificationUtil= $notificationUtil;
   }
   public function markAbsent(){
    $output = ['success' => false
    ];

    try {

        $students= Student::where('status','active')->get();
       // dd($students);
        foreach($students as $student){
            $date=Carbon::now()->format('Y-m-d');
           
            $attendance = Attendance::where('student_id',$student->id)->whereDate('clock_in_time',$date)->first();
            //dd($attendance);
            if(Empty($attendance)){
                
                    $std_attendance = Attendance::create([
                    'student_id' => $student->id,
                    'clock_in_time' =>Carbon::now(),
                    'type' => 'absent',
                    'session_id' =>$this->notificationUtil->getActiveSession(),
                ]);
                
               

                $response=$this->notificationUtil->autoSendStudentNotification('student_attendance_absent_sms', $student, $std_attendance);
                // if(empty($response)){
                //     $std_attendance->ip_address='in_sms';
                //     $std_attendance->save();
                // }

            }
                

        }
        
     
        $output = ['success' => true,
                        'msg' => __("english.student_mapping_success")
                    ];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
    }

    return redirect('attendance')->with('status', $output);
    }



       /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function studentsMapping(){
        $output = ['success' => false
        ];

       try {
            $student_device_ip=[
                '192.168.1.202',
                '192.168.1.203'
            ];
            
            foreach ($student_device_ip as $ip) {
                $client = new Client();
                $response = $client->get('http://localhost/django-admin/api/sms?ip='.$ip);
                $body = $response->getBody();
                $return_body = json_decode($body, true);
                //dd($return_body);
                if($return_body['success']==false)
                {
                    $output = ['success' => false,
                    'msg' => $return_body['msg']
                ];

                    return redirect('attendance')->with('status', $output);
             
                }
                if(!empty($return_body["data"])){
                
                foreach ($return_body["data"]as $key => $value) {
                    $device_date=\Carbon::parse($value[1])->format('Y');
                    if ($device_date==\Carbon::now()->format('Y')) {
                        //  dd($value);
                        //if( \Carbon::parse($value[1])->toDateString() == '2022-02-09'){
                            
                        $student= Student::where('roll_no', trim($value[0]))->first();
                        if (!empty($student)) {
                            //dd(trim($value[0]));
                        
                            $attendance = Attendance::where('student_id', $student->id)->whereDate('clock_in_time', \Carbon::parse($value[1])->toDateString())->first();
                            if (empty($attendance)) {
                                $std_attendance = Attendance::create([
                                'student_id' => $student->id,
                                'clock_in_time' =>\Carbon::parse($value[1]),
                                'type' => 'present',
                                'session_id' =>$this->notificationUtil->getActiveSession(),
                            ]);
                            
                                $response=$this->notificationUtil->autoSendStudentNotification('student_attendance_check_in', $student, $std_attendance);
                                // if (empty($response)) {
                                //     $attendance->ip_address='in_sms';
                                //     $attendance->save();
                                // }
                            } else {
                                $shift =HrmShift::find(2);
                                $can_clockout= Carbon::parse(\Carbon::parse($value[1])->toDateString().' '.$shift->end_time)->lessThanOrEqualTo(\Carbon::parse($value[1]));
                                if ($can_clockout) {
                                    if (empty($attendance->clock_out_time)) {
                                        $attendance->clock_out_time=\Carbon::parse($value[1]);
                                        $attendance->save();
                                        $response=$this->notificationUtil->autoSendStudentNotification('student_attendance_check_out', $student, $attendance);
                                        // if (empty($response)) {
                                        //     $attendance->ip_address='out_sms';
                                        //     $attendance->save();
                                        // }
                                    }
                                }
                               
                            }
                        }
                    }
                }
                
            }
            }
            $this->clearStudentAttendance();
            $output = ['success' => true,
                            'msg' => __("english.student_mapping_success")
                        ];
                        
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }
        if (request()->ajax()) {
            return $output;
        }
        return redirect('attendance')->with('status', $output);
        }



     
        
 public function employeeMapping(){
   

    try {
        $output = ['success' => false
    ];
         $client = new Client();
        $response = $client->get('http://localhost/django-admin/api/sms?ip=192.168.1.201');
        $body = $response->getBody();
        $return_body = json_decode($body ,true);
     
        foreach ($return_body["data"]as $key => $value) {
         $device_date=\Carbon::parse($value[1])->format('Y'); 
         if($device_date==\Carbon::now()->format('Y')){           
            //if( \Carbon::parse($value[1])->toDateString() == '2022-02-09'){
                $employee=HrmEmployee::where('employeeID',trim($value[0]))->first();
                $attendance = HrmAttendance::where('employee_id',$employee->id)->whereDate('clock_in_time',\Carbon::parse($value[1])->toDateString())->first();
                $settings=[];

                if(Empty($attendance)){
                    $data = [
                        'employee_id' => $employee->id,
                        'clock_in_time' => \Carbon::parse($value[1]),
                        'ip_address' => 0,
                        'session_id' =>$this->notificationUtil->getActiveSession()
                    ];
                    $output = $this->clockin($data, $settings,$employee);
    
                }
                else{
            
                $data = [
                    'employee_id' => $employee->id,
                    'clock_out_time' => \Carbon::parse($value[1]),
                    'ip_address' => 0,
                    'session_id' =>$this->notificationUtil->getActiveSession()
                ];
                $shift =HrmShift::find(2);
                $can_clockout= Carbon::parse(\Carbon::parse($value[1])->toDateString().' '.$shift->end_time)->lessThanOrEqualTo(\Carbon::parse($value[1]));
                if($can_clockout){
                    if(Empty($attendance->clock_out_time)){
                    $attendance->clock_out_time = $data['clock_out_time'];            
                    $attendance->save();
                   
                    $output = $this->clockout($data, $settings,$employee);
                    }
                }
    
                }
             

            }
            
        }
        $this->clearHrmAttendance();
        $output = ['success' => true,
        'msg' => __("english.employee_mapping_success")
    ];
} catch (\Exception $e) {
\Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

$output = ['success' => false,
        'msg' => __("english.something_went_wrong")
    ];
}
if (request()->ajax()) {
    return $output;
}
return redirect('hrm-attendance')->with('status', $output);
    }

  
   


    
    public function clockout($data, $essentials_settings,$employee)
    {
        
        //Get clock in
        $clock_in = HrmAttendance::
                                where('employee_id', $data['employee_id'])
                                ->whereNull('clock_out_time')
                                ->first();
        
        $clock_out_time = is_object($data['clock_out_time']) ? $data['clock_out_time']->toDateTimeString() : $data['clock_out_time'];
        if (!empty($clock_in)) {
            
            $can_clockout = $this->canClockOut($clock_in, $essentials_settings, $clock_out_time);
            //dd($can_clockout);

            if (!$can_clockout) {
                // $response=$this->notificationUtil->autoSendNotification('shift_is_not_over', $employee, $clock_in);
                // $output = ['success' => false,
                //         'msg' => __("essentials::lang.shift_not_over"),
                //         'type' => 'clock_out'
                //     ];
                $output=[];
                return $output;
            }

            $clock_in->clock_out_time = $data['clock_out_time'];
            $clock_in->save();
            $response=$this->notificationUtil->autoSendNotification('attendance_check_out', $employee, $clock_in);
            // if(empty($response)){
            //     $clock_in->ip_address='out_sms';
            //     $clock_in->save();

            //  }
            $output = ['success' => true,
                    'msg' => __("essentials::lang.clock_out_success"),
                    'type' => 'clock_out'
                ];
        } else {
            $output = ['success' => false,
                    'msg' => __("essentials::lang.not_clocked_in"),
                    'type' => 'clock_out'
                ];
        }

        return $output;
    }
       /**
     * Validates user clock out
     */
    public function canClockOut($clock_in, $settings, $clock_out_time = null)
    {
        $shift = HrmShift::find($clock_in->shift_id);
        if (empty($shift->end_time)) {
            return true;
        }

        $grace_before_checkout = !empty($settings['grace_before_checkout']) ? (int) $settings['grace_before_checkout'] : 0;
        $grace_after_checkout = !empty($settings['grace_after_checkout']) ? (int) $settings['grace_after_checkout'] : 0;
        $clock_out_start =  empty($shift->start_time) ? \Carbon::now()->subMinutes($grace_before_checkout) : \Carbon::parse($shift->start_time)->subMinutes($grace_before_checkout);
        $clock_out_end = empty($clock_out_time) ? \Carbon::now()->addMinutes($grace_after_checkout) : \Carbon::parse($clock_out_time)->addMinutes($grace_after_checkout);
        

        if ((\Carbon::parse($shift->end_time)->between($clock_out_start, $clock_out_end)) || $shift->type == 'flexible_shift') {
            return true;
        } else {
            return false;
        }
    }

    public function clockin($data, $essentials_settings,$employee)
    {
        //Check user can clockin
        $clock_in_time = is_object($data['clock_in_time']) ? $data['clock_in_time']->toDateTimeString() : $data['clock_in_time'];

        
        $shift = $this->checkUserShift($data['employee_id'], $essentials_settings, $clock_in_time);
        //dd($shift);

        if (empty($shift)) {
            $available_shifts = $this->getAllAvailableShiftsForGivenUser($data['employee_id']);
            //dd($available_shifts);
            // $available_shifts_html = View::make('essentials::attendance.avail_shifts')
            //                             ->with(compact('available_shifts'))
            //                             ->render();

            $output = ['success' => false,
                    'msg' => __("essentials::lang.shift_not_allocated"),
                    'type' => 'clock_in',
                    'shift_details' => $available_shifts
                ];
            return $output;
        }

         $data['shift_id'] = $shift;

        //Check if already clocked in
        $count = HrmAttendance::
                                where('employee_id', $data['employee_id'])
                                ->whereNull('clock_out_time')
                                ->whereDate('clock_in_time',$clock_in_time)
                              ->count();
        if ($count == 0) {

            $hrm_attendance=HrmAttendance::create($data);
            $response=$this->notificationUtil->autoSendNotification('attendance_check_in', $employee, $hrm_attendance);
            // if(empty($response)){
            //     $hrm_attendance->ip_address='in_sms';
            //     $hrm_attendance->save();

            //  }
            $shift_info = HrmShift::getGivenShiftInfo($shift);
            // $current_shift_html = View::make('essentials::attendance.current_shift')
            //                         ->with(compact('shift_info'))
            //                         ->render();

            $output = ['success' => true,
                    'msg' => __("essentials::lang.clock_in_success"),
                    'type' => 'clock_in',
                    'current_shift' => 'ok'
                ];
        } else {
            $output = ['success' => false,
                    'msg' => __("essentials::lang.already_clocked_in"),
                    'type' => 'clock_in'
                ];
        }

        return $output;
    }

     /**
     * Validates user clock in and returns available shift id
     */
    public function checkUserShift($employee_id, $settings, $clock_in_time = null)
    {
        $shift_id = null;
        $shift_date = !empty($clock_in_time) ? \Carbon::parse($clock_in_time) : \Carbon::now();
        $shift_datetime = $shift_date->format('Y-m-d');
        $day_string = strtolower($shift_date->format('l'));
        $grace_before_checkin = !empty($settings['grace_before_checkin']) ? (int) $settings['grace_before_checkin'] : 0;
        $grace_after_checkin = !empty($settings['grace_after_checkin']) ? (int) $settings['grace_after_checkin'] : 0;
        $clock_in_start =  !empty($clock_in_time) ? \Carbon::parse($clock_in_time)->subMinutes($grace_before_checkin) : \Carbon::now()->subMinutes($grace_before_checkin);
        $clock_in_end = !empty($clock_in_time) ? \Carbon::parse($clock_in_time)->addMinutes($grace_after_checkin) : \Carbon::now()->addMinutes($grace_after_checkin);
        $user_shifts = HrmEmployeeShift::join('hrm_shifts as s', 's.id', '=', 'hrm_employee_shifts.hrm_shift_id')
                    ->where('employee_id', $employee_id)
                    ->where('start_date', '<=', $shift_datetime)
                    ->where(function ($q) use ($shift_datetime) {
                        $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $shift_datetime);
                    })
                    ->select('hrm_employee_shifts.*', 's.holidays', 's.start_time', 's.end_time', 's.type')
                    ->get();
    
        foreach ($user_shifts as $shift) {

            $holidays = json_decode($shift->holidays, true);
            //check if holiday
            if (is_array($holidays) && in_array($day_string, $holidays)) {
                continue;
            }
           // dd($shift);
            //Check allocated shift time
          // if ((!empty($shift->start_time) && \Carbon::parse($shift->start_time)->between($clock_in_start, $clock_in_end)) || $shift->type == 'flexible_shift') {
            // if ((!empty($shift->start_time) && true) || $shift->type == 'flexible_shift') {
                return $shift->hrm_shift_id;
               // dd($shift);

        //}
        }
        
        return $shift_id;
    }

    public function getAllAvailableShiftsForGivenUser($employee_id)
    {   
        $available_user_shifts = HrmEmployeeShift::join('hrm_shifts as s', 's.id', '=', 
                                    'hrm_employee_shifts.hrm_shift_id')
                                    ->where('employee_id', $employee_id)
                                    ->whereDate('start_date', '<=', \Carbon::today())
                                    ->whereDate('end_date', '>=', \Carbon::today())
                                    ->select('hrm_employee_shifts.start_date', 'hrm_employee_shifts.end_date',
                                        's.name', 's.type', 's.start_time', 's.end_time', 's.holidays')
                                    ->get();

        return $available_user_shifts;
    }


    public function clearAttendance(){
        if (request()->ajax()) {
            try {
      
        
        $device_ip=[
                '192.168.1.201',
                '192.168.1.202',
                '192.168.1.203'
            ];
            
        foreach ($device_ip as $ip) {
           
            $options['form_params'] = ['ip'=>$ip];
            $client = new Client();
            $response = $client->post('http://localhost/django-admin/api/clear-att', $options);
            $body = $response->getBody();
            $return_body = json_decode($body, true);
            $output = ['success' => true,
            'msg' => $return_body['msg']];
        }
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
    }

    return $output;
}
    }
  public function clearHrmAttendance(){
      
    $device_ip=[
        '192.168.1.201'
    ];
    
foreach ($device_ip as $ip) {
   
    $options['form_params'] = ['ip'=>$ip];
    $client = new Client();
    $response = $client->post('http://localhost/django-admin/api/clear-att', $options);
    $body = $response->getBody();
    $return_body = json_decode($body, true);
  
}
  }      
  public function clearStudentAttendance(){
      
    $device_ip=[
        '192.168.1.202',
        '192.168.1.203'
    ];
    
foreach ($device_ip as $ip) {
   
    $options['form_params'] = ['ip'=>$ip];
    $client = new Client();
    $response = $client->post('http://localhost/django-admin/api/clear-att', $options);
    $body = $response->getBody();
    $return_body = json_decode($body, true);
  
}
  }      
}
