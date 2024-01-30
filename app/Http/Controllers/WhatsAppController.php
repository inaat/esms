<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\WhatsappLog;
use App\Models\WhatsappDevice;

use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Student;
use App\Utils\NotificationUtil;

class WhatsappController extends Controller
{
    public function __construct(NotificationUtil $notificationUtil)
    {
        $this->notificationUtil= $notificationUtil;

    }
    public function index()
    {
       /*  $smslogs = WhatsappLog::where('status','4')->get();
         foreach($smslogs as $sms){
             $t=explode(':',$sms->message);
             $roll=explode('@',$t[1]);
             if(!empty($roll)){
             $student=Student::where('roll_no',$roll[0])->first();
             $student->is_whatsapp='yes';
             $student->save();
             }else{
            dd($student);
             }
            
         }*/
         
        if (request()->ajax()) {
            $smslogs = WhatsappLog::orderBy('id', 'DESC')
            ->leftJoin('users AS u', 'whatsapp_logs.user_id', '=', 'u.id')
            ->select([
                'whatsapp_logs.id',
                'whatsapp_logs.to',
                'whatsapp_logs.message',
                'whatsapp_logs.initiated_time',
                'whatsapp_logs.status',
                'whatsapp_logs.response_gateway',
                DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by")
            ]);            
          if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $smslogs->whereDate('whatsapp_logs.initiated_time', '>=', $start)
                        ->whereDate('whatsapp_logs.initiated_time', '<=', $end);
            }
              if (request()->has('status')) {
                $status = request()->get('status');
                if (!empty($status)) {
                    $smslogs->where('whatsapp_logs.status', $status);
                }
            }
            $datatable = Datatables::of($smslogs)
        ->addColumn(
            'action',
            function ($row) {
                $html= '<div class="dropdown">
                     <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                     <ul class="dropdown-menu" style="">';
               
                $html .= '</ul></div>';

                return $html;
            }
            
        )
        ->addColumn('status', function ($row) {
            if($row->status == 1){
			 return '<span class="badge text-white text-uppercase bg-primary">Pending</span>';
            }
			elseif($row->status == 2)
            {
                return '<span class="badge text-white text-uppercase bg-info">Schedule</span>';
            }
			elseif($row->status == 3)
            {
                return '<span class="badge text-white text-uppercase bg-danger">Fail</span>';
            }
			elseif($row->status == 4)
            {
                return '<span class="badge text-white text-uppercase bg-success">Delivered</span>';
            }
			elseif($row->status == 5)
            {
                return '<span class="badge text-white text-uppercase bg-primary">Processing</span>';
            }
				                    	
        })
        ->editColumn('initiated_time', '{{@format_datetime($initiated_time)}}')

                ->removeColumn('id');



            $rawColumns = ['action','message','status'];

            return $datatable->rawColumns($rawColumns)
              ->make(true);
        }
        $title = "All Whatsapp Message History";
        $device= WhatsappDevice::first();

       // dd($rows);

        return view('whatsapp_messaging.index', compact('device'));
    }

    public function smsStatus()
    {
        
        try {
            if (request()->ajax()) {
                $device= WhatsappDevice::first();
                $device->sms_status=request()->get('sms_status');
                $device->save();
               
     
             }
            $output = ['success' => true,
                            'msg' => __("english.update_success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return $output;
    }
    public function attendanceSmsSend()
    {
      
        $output = ['success' => false
    ];

    try {

      
                
        $device= WhatsappDevice::first();

        $date=Carbon::now()->format('Y-m-d');
        $attendances = Attendance::where('type','absent')->whereDate('clock_in_time',$date)->get();
        foreach($attendances as $attendance){
            $student= Student::where('id', $attendance->student_id)->first();
            $att = Attendance::find($attendance->id);

        
                    
                    if($device->sms_status==='sms_on'){
                     $this->notificationUtil->autoSendStudentNotification('student_attendance_absent_sms', $student, $att);
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

    return redirect('sms-logs')->with('status', $output);

    }

    public function jobEmpty()
    {
      
        $output = ['success' => false
    ];

    try {

      /* $smslogs = WhatsappLog::whereDate('whatsapp_logs.initiated_time', '2023-07-29')->where('status',3)->get();
           $addSecond = 0;
        foreach($smslogs as $log){
            if(strlen(trim($log->to)) > 10){
             // $string has at least one non-space character

                 $addSecond =  $addSecond +30;

            $this->notificationUtil->sendSmsOnWhatsappResend($log->id,$addSecond);
            }
           
        }*/
               

      DB::table('jobs')->delete();   
    // dd($smslogs);
     
        $output = ['success' => true,
                        'msg' => __("english.student_mapping_success")
                    ];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
    }

    return redirect('sms-logs')->with('status', $output);

    }
}
