<?php

namespace App\Http\Controllers\Api;

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

class EmployeeAttendanceController extends Controller
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







     public function store(Request $request)
     {
         try {
             $date = Carbon::now()->toDateString();
             $settings=[];

             $employee= HrmEmployee::where('employeeID', $request->input('employee_id'))->first();
             $attendance = HrmAttendance::where('employee_id', $employee->id)->whereDate('clock_in_time', $date)->first();
             //dd($attendance);
             if (empty($attendance)) {
                 $data = [
                     'employee_id' => $employee->id,
                     'clock_in_time' => \Carbon::now(),
                     'ip_address' => 0,
                     'session_id' =>$this->notificationUtil->getActiveSession()
                 ];
                 $output = $this->clockin($data, $settings, $employee);
             } else {
                 $data = [
                     'employee_id' => $employee->id,
                     'clock_out_time' => \Carbon::now(),
                     'ip_address' => 0,
                     'session_id' =>$this->notificationUtil->getActiveSession()
                 ];
                 $output = $this->clockout($data, $settings, $employee);
             }
         } catch (\Exception $e) {
             \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

             $output = ['success' => false,
                             'msg' => __("english.something_went_wrong")
                         ];
         }

         return response($output);
     }


     public function clockout($data, $essentials_settings, $employee)
     {
         $date = Carbon::now()->toDateString();

         //Get clock in
         $clock_in = HrmAttendance::where('employee_id', $data['employee_id'])
                                 ->whereNull('clock_out_time')
                                 ->whereDate('clock_in_time', $date)
                                 ->first();

         $clock_out_time = is_object($data['clock_out_time']) ? $data['clock_out_time']->toDateTimeString() : $data['clock_out_time'];
         if (!empty($clock_in)) {
             $can_clockout = $this->canClockOut($clock_in, $essentials_settings, $clock_out_time);
             //dd($can_clockout);

             if (!$can_clockout) {
                 $response=$this->notificationUtil->autoSendNotification('shift_is_not_over', $employee, $clock_in);
                 $output = ['success' => false,
                         'msg' => __("english.shift_not_over", ['id' => $employee->employeeID]),
                         'type' => 'clock_out'
                     ];

                 return $output;
             }

             $clock_in->clock_out_time = $data['clock_out_time'];
             $clock_in->save();
             $clock_in = HrmAttendance::where('employee_id', $data['employee_id'])
             ->whereNull('clock_out_time')
             ->first();

             //dd($clock_in);
             $response=$this->notificationUtil->autoSendNotification('attendance_check_out', $employee, $clock_in);

             $output = ['success' => true,
                     'msg' => __("english.clock_out_success", ['id' => $employee->employeeID]),
                     'type' => 'clock_out'
                 ];
         } else {
             $output = ['success' => false,
                     'msg' => __('english.not_clocked_in', ['id' => $employee->employeeID]),
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
        //dd($clock_in->shift_id);
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

     public function clockin($data, $essentials_settings, $employee)
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
                     'msg' => __("english.shift_not_allocated", ['id' => $employee->employeeID]),
                     'type' => 'clock_in',
                     'shift_details' => $available_shifts
                 ];
             return $output;
         }

         $data['shift_id'] = $shift;

         //Check if already clocked in
         $count = HrmAttendance::where('employee_id', $data['employee_id'])
                                 ->whereNull('clock_out_time')
                                 ->whereDate('clock_in_time', $clock_in_time)
                               ->count();
         if ($count == 0) {
             $hrm_attendance=HrmAttendance::create($data);
             $response=$this->notificationUtil->autoSendNotification('attendance_check_in', $employee, $hrm_attendance);

             $shift_info = HrmShift::getGivenShiftInfo($shift);

             $output = ['success' => true,
                     'msg' => __("english.clock_in_success", ['id' => $employee->employeeID]),
                     'type' => 'clock_in',
                     'current_shift' => 'ok'
                 ];
         } else {
             $output = ['success' => false,
                     'msg' => __("english.already_clocked_in", ['id' => $employee->employeeID]),
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
         $available_user_shifts = HrmEmployeeShift::join(
             'hrm_shifts as s',
             's.id',
             '=',
             'hrm_employee_shifts.hrm_shift_id'
         )
                                     ->where('employee_id', $employee_id)
                                     ->whereDate('start_date', '<=', \Carbon::today())
                                     ->whereDate('end_date', '>=', \Carbon::today())
                                     ->select(
                                         'hrm_employee_shifts.start_date',
                                         'hrm_employee_shifts.end_date',
                                         's.name',
                                         's.type',
                                         's.start_time',
                                         's.end_time',
                                         's.holidays'
                                     )
                                     ->get();

         return $available_user_shifts;
     }
}
