<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Classes;

use App\Models\ClassSection;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Utils\StudentUtil;
use App\Utils\NotificationUtil;

class AttendanceController extends Controller
{
    /**
    * Constructor
    *
    * @param StudentUtil $studentUtil
    * @return void
    */
    public function __construct(StudentUtil $studentUtil, NotificationUtil $notificationUtil)
    {
        $this->studentUtil= $studentUtil;
        $this->notificationUtil= $notificationUtil;
        $this->attendance_status_colors = [
            'present' => 'bg-success',
            'late' => 'bg-warning',
            'leave'=>'bg-info',
            'half_day' => 'bg-dark',
            'absent' => 'bg-danger',
            //'cancelled' => 'bg-red',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('student_attendance.view')) {
            abort(403, 'Unauthorized action.');
        }
        $status='present';
        if(request()->has('type')){
            $status = request()->get('type');
        }
//         $students =Student::where('status','active')->get();
//         foreach($students as $student){
//             $attendance = Attendance::where('student_id', $student->id)->whereDate('clock_in_time', '2023-05-16')->get();
//            if(count($attendance)>0){
// //dd($attendance);
//           if(count($attendance)>1){
//             //dd($attendance[0]->id);
//             Attendance::find($attendance[0]->id)->delete();
//           }
//         }
//         }
//         dd($students);
        if (request()->ajax()) {
            $attendance = Attendance::leftJoin('students', 'students.id', '=', 'attendances.student_id')
            ->leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
           // ->where('attendances.type','present')
            ->select(
                'campuses.campus_name',
                'students.roll_no',
                'students.father_name',
                'attendances.id as id',
                'attendances.clock_in_time',
                'attendances.clock_out_time',
                'attendances.clock_in_note',
                'attendances.clock_out_note',
                'attendances.type as status',
                DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name")
            );
            $permitted_campuses = auth()->user()->permitted_campuses();
       if ($permitted_campuses != 'all') {
        $attendance->whereIn('students.campus_id', $permitted_campuses);
       }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $attendance->where('students.campus_id', $campus_id);
                }
            }
            if (request()->has('class_id')) {
                $class_id = request()->get('class_id');
                if (!empty($class_id)) {
                    $attendance->where('students.current_class_id', $class_id);
                }
            }
            if (request()->has('class_section_id')) {
                $class_section_id = request()->get('class_section_id');
                if (!empty($class_section_id)) {
                    $attendance->where('students.current_class_section_id', $class_section_id);
                }
            }
            if (request()->has('status')) {
                $status = request()->get('status');
                if (!empty($status)) {
                    $attendance->where('attendances.type', $status);
                }
            }
            if (request()->has('roll_no')) {
                $roll_no = request()->get('roll_no');
                if (!empty($roll_no)) {
                    $attendance->where('students.roll_no', 'like', "%{$roll_no}%");
                }
            }
            if (request()->has('student_name')) {
                $student_name = request()->get('student_name');
                if (!empty($student_name)) {
                    $attendance->whereRaw("CONCAT(COALESCE(students.first_name, ''), ' ', COALESCE(students.last_name, '')) like ?", ["%{$student_name}%"]);
                }
            }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $attendance->whereDate('clock_in_time', '>=', $start)
                            ->whereDate('clock_in_time', '<=', $end);
            }
            $datatable = Datatables::of($attendance)
            ->addColumn(
                'action',
                function ($row) {
                    $html= '<div class="dropdown">
                         <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                         <ul class="dropdown-menu" style="">';
                    $html.='<li><a class="dropdown-item "href="' . action('Hrm\HrmEmployeeController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';

                    $html .= '</ul></div>';

                    return $html;
                }
            )
            ->editColumn('status', function ($row) {
                $status_color = !empty($this->attendance_status_colors[$row->status]) ? $this->attendance_status_colors[$row->status] : 'bg-gray';
                $status ='<a href="#"'.'data-student_id="' . $row->id .
             '" data-status="' . $row->status . '" class="update_attendance_status">';
                $status .='<span class="badge badge-mark ' . $status_color .'">' .__('english.'.$row->status).   '</span></a>';
                return $status;
            })
            ->editColumn('work_duration', function ($row) {
                $clock_in = \Carbon::parse($row->clock_in_time);
                if (!empty($row->clock_out_time)) {
                    $clock_out = \Carbon::parse($row->clock_out_time);
                } else {
                    $clock_out = \Carbon::now();
                }

                $html = $clock_in->diffForHumans($clock_out, true, true, 2);

                return $html;
            })
            ->editColumn('clock_in', function ($row) {
                $html = $this->studentUtil->format_date($row->clock_in_time, true);


                if (!empty($row->clock_in_note)) {
                    $html .= '<br>' . $row->clock_in_note .'<br>';
                }

                return $html;
            })
            ->editColumn('clock_out', function ($row) {
                $html = $this->studentUtil->format_date($row->clock_out_time, true);
                if (!empty($row->clock_out_note)) {
                    $html .= '<br>' . $row->clock_out_note .'<br>';
                }

                return $html;
            })
            ->filterColumn('roll_no', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('students.roll_no', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('father_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('students.father_name', 'like', "%{$keyword}%");
                });
            })

            ->filterColumn('student_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(COALESCE(students.first_name, ''), ' ', COALESCE(students.last_name, '')) like ?", ["%{$keyword}%"]);
            });
            $rawColumns = ['action','campus_name','clock_in','work_duration', 'clock_out','status'];

            return $datatable->rawColumns($rawColumns)
                  ->make(true);
        }
        $campuses=Campus::forDropdown();

        return view('attendance.index')->with(compact('campuses','status'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   if (!auth()->user()->can('student_attendance.create')) {
        abort(403, 'Unauthorized action.');
    }
        $campuses=Campus::forDropdown();
        return view('attendance.create')->with(compact('campuses'));
    }
    public function attendanceAssignSearch(Request $request)
    {
        if (!auth()->user()->can('student_attendance.create')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->all();

        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $date=$this->studentUtil->uf_date($input['date']);
        $class_section_id=$input['class_section_id'];
        $system_settings_id = session()->get('user.system_settings_id');
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);

        $campuses=Campus::forDropdown();
        $students=$this->studentUtil->getStudentList($system_settings_id, $class_id, null, 'active');
        $attendance_list=Attendance::leftJoin('students', 'students.id', '=', 'attendances.student_id')
                ->where('students.current_class_id', $class_id)
                //->where('students.current_class_section_id', $class_section_id)
                ->where('students.status', 'active')
                ->where('students.campus_id', $campus_id)
                ->whereDate('clock_in_time', '=', $date)
                ->select(
                    'students.id as student_id',
                    'attendances.id as id',
                    'attendances.type',
                )->get();
        return view('attendance.attendance_assign')->with(compact('students', 'campuses', 'classes', 'sections', 'campus_id', 'class_id', 'class_section_id', 'date', 'attendance_list'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('student_attendance.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();

            $input=$request->input();
            $date=$this->studentUtil->uf_date($input['date']);
            // dd($input);
            foreach ($input['attendance'] as $attend) {
              //  dd($input['attendance']);
                $student= Student::where('id', $attend['student_id'])->first();
                $attendance = Attendance::where('student_id', $student->id)->whereDate('clock_in_time', $date)->first();
                if (empty($attendance)) {
                    if (!empty($attend['status'])) {
                        $std_attendance = Attendance::create([
                        'student_id' => $student->id,
                        'clock_in_time' =>Carbon::now(),
                        'type' => $attend['status'],
                        'session_id' =>$this->notificationUtil->getActiveSession(),
                                        ]);
                        if($attend['status']=='present'){
                        $this->notificationUtil->autoSendStudentNotification('student_attendance_check_in', $student, $std_attendance);
                        }elseif($attend['status']=='absent'){
                         $this->notificationUtil->autoSendStudentNotification('student_attendance_absent_sms', $student, $std_attendance);

                        }

                    }
                } else {
                    if (!empty($attend['status'])) {
                        $attendance->type=$attend['status'];
                        $attendance->save();
                    }
                }
            }

            $output = ['success' => true,
        'msg' => __("english.updated_success")
        ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
    'msg' => __("english.something_went_wrong")
];
        }
        return redirect('attendance')->with('status', $output);


        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}


