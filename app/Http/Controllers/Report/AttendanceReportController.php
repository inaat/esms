<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Campus;
use Carbon\Carbon;
use App\Utils\Util;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmAttendance;
use App\Models\HumanRM\HrmDesignation;
use Yajra\DataTables\Facades\DataTables;
use DB;
use PDF;
use File;

class AttendanceReportController extends Controller
{
    /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
    public function __construct(Util $util)
    {
        $this->util= $util;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('print.student_attendance_print')) {
            abort(403, 'Unauthorized action.');
        }
        // dd(Carbon::now()->format("l") );
        // dd($this->util->generateDateRange(Carbon::parse('2022-02-01'), Carbon::parse('2022-02-28')));

        $campuses=Campus::forDropdown();
      
        return view('Report.attendance.index')->with(compact('campuses'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('print.student_attendance_print')) {
            abort(403, 'Unauthorized action.');
        }
      
        if (File::exists(public_path('uploads/pdf/hrm.pdf'))) {
            File::delete(public_path('uploads/pdf/hrm.pdf'));
        }
        $pdf_name='hrm'.'.pdf';
        $campuses=[];
     
        $input=$request->input();
        $attendance_date=explode(' - ', $input['list_filter_date_range']);
        // dd(Carbon::parse($attendance_date[1])->subMonths(1)->lastOfMonth()->endOfDay());
        // dd(Carbon::parse($attendance_date[1])->subMonths(1)->format('Y-m-d'));
        $start_date=Carbon::parse($attendance_date[0])->format('Y-m-d');
        $end_date=Carbon::parse($attendance_date[1])->format('Y-m-d');
        $student =Student::with(['campuses','current_class','current_class_section','attendances'=>function ($q) use ($start_date, $end_date) {
            $q->whereDate('clock_in_time', '>=', $start_date)->whereDate('clock_in_time', '<=', $end_date)->orderBy('clock_in_time', 'asc');
        }])
        ->where('campus_id', $input['campus_id'])
        ->where('current_class_id', $input['class_id'])
        ->where('current_class_section_id', $input['class_section_id'])
        ->where('status', 'active')->get();
        $days=$this->util->generateDateRange(Carbon::parse($start_date), Carbon::parse($end_date));
       //dd($student[0]->current_class_section->section_name);
        $data=[];
        $total_no_attendance_days=count($days);
        $total_no_attendance=0;
        $sundays_weekend=0;
        foreach ($student as $key => $value) {
            $attendances=[];
            foreach ($days as $day) {
                $status='';
                foreach ($value->attendances as $at) {
                    if ((Carbon::parse($at->clock_in_time)->toDateString()==(Carbon::parse($day)->toDateString()))) {
                        if ($at->type=='late') {
                            $status='LT';
                            $total_no_attendance++;
                        } else {
                            $status=ucwords($at->type[0]);
                            if($at->type=='present' || $at->type=='half_day'){
                                $total_no_attendance++;
                            }
                        
                        }
                        if ($key==0) {
                            if ($at->type=='weekend') {
                                $sundays_weekend++;
                            }
                        }
                    }
                }
                if (Carbon::parse($day)->format('l')=='Sunday') {
                    $status='S';
                    if ($key==0) {
                        $sundays_weekend++;
                    }
                }
                $attendances[]=[
                    'status'=> $status,
                    'clock_in_time'=>$day,
                ];
            }
            $BFAttendance=$this->perviousMonthAttendances($value->id, $start_date, 'present')+$this->perviousMonthAttendances($value->id, $start_date, 'half_day')+$this->perviousMonthAttendances($value->id, $start_date, 'late');
            $Attendance=$this->currentMonthAttendances($value->id, $start_date, $end_date, 'present')+$this->currentMonthAttendances($value->id, $start_date, $end_date, 'half_day')+$this->currentMonthAttendances($value->id, $start_date, $end_date, 'late');

            $info=[
                'name'=>$value->first_name.' '.$value->last_name,
                'roll_no'=>$value->roll_no,
                'attendances'=>$attendances,
                'B/F'=>$this->perviousMonthAttendances($value->id, $start_date, 'absent'),
                'absent'=>$this->currentMonthAttendances($value->id, $start_date, $end_date, 'absent'),
                'BFLeave'=>$this->perviousMonthAttendances($value->id, $start_date, 'leave')  ,
                'leave'=>$this->currentMonthAttendances($value->id, $start_date, $end_date, 'leave'),
                'BFAttendance'=>$BFAttendance,
                'Attendance'=>$Attendance,
                'total_no_attendance'=>$BFAttendance+$Attendance,
                
            ];

            $data[]=$info;
        }
        $average=$total_no_attendance/($total_no_attendance_days-$sundays_weekend);
       // dd($total_no_attendance_days-$sundays_weekend);
       $detail=[
        'days'=>$days, 'data'=>$data,'average'=>$average,'student'=>$student,'start_date'=>$start_date,'end_date'=>$end_date,'total_no_attendance_days'=>$total_no_attendance,'total_no_attendance'=>$total_no_attendance,'sundays_weekend'=>$sundays_weekend
       ];
       $pdf =  config('constants.mpdf');
       if ($pdf) {  
       $this->reportPDF('samplereport.css', $detail, 'MPDF.student-report-attendance','view','a4','landscape');
}else{
    $snappy  = \WPDF::loadView('Report\attendance.report-attendance', compact('days', 'data', 'average', 'student', 'start_date', 'end_date', 'total_no_attendance_days', 'total_no_attendance', 'sundays_weekend'));
    $headerHtml = view()->make('common._header')->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'landscape')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}     
          
    }

    public function create()
    {
        if (!auth()->user()->can('print.employee_attendance_print')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $designations = HrmDesignation::forDropdown();


        $campuses=Campus::forDropdown();
        // dd($this->getTableColumns('hrm_employees'));
        
        return view('Report.attendance.create')->with(compact('campuses', 'designations'));
    }
    public function employeeStore(Request $request)
    {
        if (!auth()->user()->can('print.employee_attendance_print')) {
            abort(403, 'Unauthorized action.');
        }
      // try {
        if (File::exists(public_path('uploads/pdf/hrm.pdf'))) {
            File::delete(public_path('uploads/pdf/hrm.pdf'));
        }
        $pdf_name='hrm'.'.pdf';
        $campuses=[];
     
        $input=$request->input();
      
        $attendance_date=explode(' - ', $input['list_filter_date_range']);
        $start_date=Carbon::parse($attendance_date[0])->format('Y-m-d');
        $end_date=Carbon::parse($attendance_date[1])->format('Y-m-d');
        $HrmEmployees=HrmEmployee::with(['campuses','designations','attendances'=>function ($q) use ($start_date, $end_date) {
            $q->whereDate('clock_in_time', '>=', $start_date)->whereDate('clock_in_time', '<=', $end_date)->orderBy('clock_in_time', 'asc');
        }])
        ->where('campus_id', $input['campus_id'])
        ->where('status', 'active');
        if (!empty($designation)) {
            $HrmEmployees->where('hrm_employees.designation_id', $designation);
        }
        $days=$this->util->generateDateRange(Carbon::parse($start_date), Carbon::parse($end_date));
        $data=[];
        $total_no_attendance_days=count($days);
        $total_no_attendance=0;
        $sundays_weekend=0;
        $HrmEmployees=$HrmEmployees->get();
       // dd($HrmEmployees);
        foreach ($HrmEmployees as $key => $value) {
            $attendances=[];
            foreach ($days as $day) {
                $status='';
                foreach ($value->attendances as $at) {
                    if ((Carbon::parse($at->clock_in_time)->toDateString()==(Carbon::parse($day)->toDateString()))) {
                        if ($at->type=='late') {
                            $status='LT';
                            $total_no_attendance++;
                        } else {
                            $status=ucwords($at->type[0]);
                            if($at->type=='present' || $at->type=='half_day'){
                                $total_no_attendance++;
                            }
                        
                        }
                        if ($key==0) {
                            if ($at->type=='weekend') {
                                $sundays_weekend++;
                            }
                        }
                    }
                }
                if (Carbon::parse($day)->format('l')=='Sunday') {
                    $status='S';
                    if ($key==0) {
                        $sundays_weekend++;
                    }
                }
                $attendances[]=[
                    'status'=> $status,
                    'clock_in_time'=>$day,
                ];
            }
            $BFAttendance=$this->employeePerviousMonthAttendances($value->id, $start_date, 'present')+$this->employeePerviousMonthAttendances($value->id, $start_date, 'half_day')+$this->employeePerviousMonthAttendances($value->id, $start_date, 'late');
            $Attendance=$this->employeeCurrentMonthAttendances($value->id, $start_date, $end_date, 'present')+$this->employeeCurrentMonthAttendances($value->id, $start_date, $end_date, 'half_day')+$this->employeeCurrentMonthAttendances($value->id, $start_date, $end_date, 'late');
            // dd( $start_date, $end_date);
            $info=[
                'name'=>$value->first_name.' '.$value->last_name,
                'roll_no'=>$value->roll_no,
                'attendances'=>$attendances,
                'B/F'=>$this->employeePerviousMonthAttendances($value->id, $start_date, 'absent'),
                'absent'=>$this->employeeCurrentMonthAttendances($value->id, $start_date, $end_date, 'absent'),
                'BFLeave'=>$this->employeePerviousMonthAttendances($value->id, $start_date, 'leave')  ,
                'leave'=>$this->employeeCurrentMonthAttendances($value->id, $start_date, $end_date, 'leave'),
                'BFAttendance'=>$BFAttendance,
                'Attendance'=>$Attendance,
                'total_no_attendance'=>$BFAttendance+$Attendance,
                
            ];

            $data[]=$info;
        }
        $average=$total_no_attendance/($total_no_attendance_days-$sundays_weekend);
       // dd($total_no_attendance_days-$sundays_weekend);
       $detail=[
        'days'=>$days, 'data'=>$data,'average'=>$average,'start_date'=>$start_date,'end_date'=>$end_date,'total_no_attendance_days'=>$total_no_attendance,'total_no_attendance'=>$total_no_attendance,'sundays_weekend'=>$sundays_weekend
       ];
       $pdf =  config('constants.mpdf');
       if ($pdf) {  
       $this->reportPDF('samplereport.css', $detail, 'MPDF.hrm-attendance-report','view','a4','landscape');
}else{
    $snappy  = \WPDF::loadView('Report.attendance.hrm-attendance-report', compact('days', 'data', 'average', 'start_date', 'end_date', 'total_no_attendance_days', 'total_no_attendance', 'sundays_weekend'));
    $headerHtml = view()->make('common._header')->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'landscape')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}     
            $output = ['success' => true,
                             'msg' => __("english.added_success")
                       ];
        // } catch (\Exception $e) {
        //     \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        //     $output = ['success' => false,
        //                     'msg' => __("english.something_went_wrong")
        //                 ];
        // }

        // return response($output);
    }


    


    public function perviousMonthAttendances($student_id, $start_date, $type)
    {
        $pervious_start_date=Carbon::parse($start_date)->subMonths(1)->format('Y-m-d');
        $pervious_end_date=Carbon::parse($start_date)->subMonths(1)->lastOfMonth()->endOfDay()->format('Y-m-d');
        $attendances=Attendance::where('student_id', $student_id)->whereDate('clock_in_time', '>=', $pervious_start_date)
        ->whereDate('clock_in_time', '<=', $pervious_end_date)
        ->where('type', $type)->count();
        return $attendances;
    }

    public function currentMonthAttendances($student_id, $start_date, $end_date, $type)
    {
        $attendances=Attendance::where('student_id', $student_id)->whereDate('clock_in_time', '>=', $start_date)
        ->whereDate('clock_in_time', '<=', $end_date)
        ->where('type', $type)->count();
        return $attendances;
    }

    public function employeePerviousMonthAttendances($student_id, $start_date, $type)
    {
        $pervious_start_date=Carbon::parse($start_date)->subMonths(1)->format('Y-m-d');
        $pervious_end_date=Carbon::parse($start_date)->subMonths(1)->lastOfMonth()->endOfDay()->format('Y-m-d');
        $attendances=HrmAttendance::where('employee_id', $student_id)->whereDate('clock_in_time', '>=', $pervious_start_date)
        ->whereDate('clock_in_time', '<=', $pervious_end_date)
        ->where('type', $type)->count();
        return $attendances;
    }
    public function employeeCurrentMonthAttendances($student_id, $start_date, $end_date, $type)
    {
        $attendances=HrmAttendance::where('employee_id', $student_id)->whereDate('clock_in_time', '>=', $start_date)
        ->whereDate('clock_in_time', '<=', $end_date)
        ->where('type', $type)->count();
        return $attendances;
    }
   
}
