<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\HumanRM\HrmShift;
use App\Models\HumanRM\HrmEmployee;
use Carbon\Carbon;
use App\Utils\NotificationUtil;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceController extends Controller
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
        $shift =HrmShift::find(2);
        $student= Student::where('roll_no', $request->input('student_id'))->first();
        $attendance = Attendance::where('student_id', $student->id)->whereDate('clock_in_time', $date)->first();
        if (empty($attendance)) {
            $std_attendance = Attendance::create([
                'student_id' => $student->id,
                'clock_in_time' =>Carbon::now(),
                'type' => 'present',
                'session_id' =>$this->notificationUtil->getActiveSession(),
            ]);

            $response=$this->notificationUtil->autoSendStudentNotification('student_attendance_check_in', $student, $std_attendance);
            $output = ['success' => true,
            'msg' => 'Thank You '.$student->roll_no
        ];
        } else {
            $shift =HrmShift::find(2);
            $can_clockout= Carbon::parse($shift->end_time)->lessThanOrEqualTo(Carbon::now());
            if ($can_clockout) {
                $attendance->clock_out_time=Carbon::now();
                $attendance->save();
                $response=$this->notificationUtil->autoSendStudentNotification('student_attendance_check_out', $student, $attendance);
                $output = ['success' => true,
                'msg' => __("english.clock_out_success", ['id' => $student->roll_no]),
                'type' => 'clock_out'
            ];
            }else{
                $output = ['success' => false,
                'msg' => __("english.school_time_not_over", ['id' => $student->roll_no]),
                'type' => 'clock_out'
            ];
            }
        }

        
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
    }

    return response($output);
}

}
