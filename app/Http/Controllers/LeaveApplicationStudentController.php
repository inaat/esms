<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplicationStudent;
use App\Models\Campus;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentGuardian;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Utils\Util;
use Carbon;
class LeaveApplicationStudentController extends Controller
{
    protected $commonUtil;

    /**
    * Constructor
    *
    */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->student_status_colors = [
            'approve' => 'bg-success',
            'pending' => 'bg-info',
            'reject' => 'bg-danger',
        ];
    }/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('leave_applications_for_student.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $leaveApplications=LeaveApplicationStudent::leftJoin('students', 'leave_application_students.student_id', '=', 'students.id')
            ->leftJoin('users as u', 'leave_application_students.approve_by', '=', 'u.id')
            ->leftJoin('classes as cl', 'leave_application_students.class_id', '=', 'cl.id')
            ->join(
                'campuses AS campus',
                'leave_application_students.campus_id',
                '=',
                'campus.id'
            )
              ->select(
                  'leave_application_students.apply_date',
                  'leave_application_students.id',
                  'cl.title as class_name',
                  'leave_application_students.from_date',
                  'leave_application_students.to_date',
                  'leave_application_students.document',
                  'leave_application_students.status',
                  'leave_application_students.reason',
                  DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
                  DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as approve_by"),
                  'campus.campus_name as campus_name',
              )->orderBy('leave_application_students.apply_date', 'desc');
                 // Check for permitted campuses of a user
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $leaveApplications->whereIn('leave_application_students.campus_id', $permitted_campuses);
            }
            $user = \Auth::user();
           if ($user->user_type == 'student') {
            $leaveApplications->where('leave_application_students.student_id', $user->hook_id);
            }
            if ($user->user_type == 'guardian') {
                $leaveApplications->whereIn('leave_application_students.student_id', $this->get_students_id());

                
            }     
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $leaveApplications->where('leave_application_students.campus_id', $campus_id);
                }
            }
            if (request()->has('status')) {
                $status = request()->get('status');
                if (!empty($status)) {
                    $leaveApplications->where('leave_application_students.status', $status);
                }
            }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $leaveApplications->whereDate('leave_application_students.apply_date', '>=', $start)
                        ->whereDate('leave_application_students.apply_date', '<=', $end);
            }
            $datatable = Datatables::of($leaveApplications)
            ->addColumn(
                'action',
                function ($row) {
                    $html= '<div class="dropdown">
                 <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                 <ul class="dropdown-menu" style="">';
                    if (auth()->user()->can("leave_applications_for_student.view") && !empty($row->document)) {
                        $document_name = !empty(explode("_", $row->document, 2)[1]) ? explode("_", $row->document, 2)[1] : $row->document ;
                        $html .= '<li><a  class="dropdown-item  " href="' . url('uploads/documents/' . $row->document) .'" download="' . $document_name . '"><i class="fas fa-download" aria-hidden="true"></i>' . __("english.download_document") . '</a></li>';
                        if (isFileImage($document_name)) {
                            $html .= '<li><a href="#" data-href="' . url('uploads/documents/' . $row->document) .'" class=" dropdown-item  view_uploaded_document"><i class="fas fa-image" aria-hidden="true"></i>' . __("english.view_document") . '</a></li>';
                        }
                    }
                    $html .= '</ul></div>';

                    return $html;
                }
            )
            ->editColumn('student_name', function ($row) {
                return ucwords($row->student_name);
            })
            ->editColumn('apply_date', '{{@format_date($apply_date)}}')
            ->editColumn('from_date', '{{@format_date($from_date)}}')
            ->editColumn('to_date', '{{@format_date($to_date)}}')
            ->editColumn('status', function ($row) {
                $user = \Auth::user();
                $status_color = !empty($this->student_status_colors[$row->status]) ? $this->student_status_colors[$row->status] : 'bg-gray';
if ($user->user_type == 'student' || $user->user_type == 'guardian') {
    $status ='<a href="#"'.'data-leave_id="' . $row->id .
        '" data-status="' . $row->status . '" class="">';
}else{
    $status ='<a href="#"'.'data-leave_id="' . $row->id .
        '" data-status="' . $row->status . '" class="update_leave_status">';
}
                $status .='<span class="badge badge-mark ' . $status_color .'">' .__('english.'.$row->status).   '</span></a>';
                return $status;
                
            })
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('students.first_name', 'like', "%{$keyword}%");
                });
            }) ->removeColumn('id');
            $rawColumns = ['action','campus_name','status'];

            return $datatable->rawColumns($rawColumns)
              ->make(true);
        }
        $campuses=Campus::forDropdown();


        return view('leave_applications_student.index')->with(compact('campuses'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('leave_applications_for_student.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $students=null;
        $user = \Auth::user();
     if ($user->user_type == 'guardian') {
        

        $students=StudentGuardian::forDropdown($user->hook_id);
     }
        return view('leave_applications_student.create')->with(compact('campuses','students'));
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('leave_applications_for_student.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['campus_id','student_id','reason','class_id','class_section_id']);
            $date=explode(' - ', $request->input('date_range'));
            $start_date=Carbon::parse($date[0])->format('Y-m-d');
            $end_date=Carbon::parse($date[1])->format('Y-m-d');
            if(\Auth::User()->user_type=='student'||\Auth::User()->user_type == 'guardian'){
               $student=Student::find($input['student_id']);
               $input['class_id']=$student->current_class_id;
               $input['class_section_id']=$student->current_class_section_id;
            }

            $input['from_date']=$start_date;
            $input['to_date']=$end_date;
            $input['status']='pending';
            $input['session_id']=$this->commonUtil->getActiveSession();
            $input['apply_date']=Carbon::now()->format('Y-m-d');
            $input['document'] = $this->commonUtil->uploadFile($request, 'document', 'documents');
            $leaveApplications=LeaveApplicationStudent::create($input);
            $output = ['success' => true,
            'msg' => __("english.added_success")
               ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
        }

        return redirect('leave_application_students')->with('status', $output);
    }

   
    public function updateStatus(Request $request)
    {
        if (!auth()->user()->can('leave_applications_for_student.update_status')) {
            abort(403, 'Unauthorized action.');
        }


        if (request()->ajax()) {
            try {
                DB::beginTransaction();

                $leaveApplications=LeaveApplicationStudent::find($request->leave_id);
                $leaveApplications->status = $request->status;
                if ($request->status =='reject'|| $request->status =='approve') {
                    $leaveApplications->approve_by=auth()->user()->id;
                }
                $leaveApplications->save();
                if ($request->status =='approve'){
                   $days=$this->commonUtil->generateDateRange(Carbon::parse($leaveApplications->from_date), Carbon::parse($leaveApplications->to_date));
                   $this->student_attendance($leaveApplications->student_id, $days);
                }else{
                    $days=$this->commonUtil->generateDateRange(Carbon::parse($leaveApplications->from_date), Carbon::parse($leaveApplications->to_date));
                    $this->student_delete_attendance($leaveApplications->student_id, $days); 
                }

                DB::commit();

                $output = ['success' => true,
                            'msg' => __("english.updated_success")
                             ];
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }




    public function student_attendance($student_id, $days)
    {
        foreach ($days as $key => $day) {
            if (Carbon::parse($day)->format('l')=='Sunday') {
                $status='S';
            } else {
               
                    $attendance = Attendance::where('student_id', $student_id)->whereDate('clock_in_time', $day)->first();

                    if (empty($attendance)) {
                        $std_attendance = Attendance::create([
                                'student_id' => $student_id,
                                'clock_in_time' =>$day,
                                'type' => 'leave',
                                'session_id' =>$this->commonUtil->getActiveSession(),
                            ]);
                    }
                
            }
        }
       
    }
    public function student_delete_attendance($student_id, $days)
    {
        foreach ($days as $key => $day) {
            if (Carbon::parse($day)->format('l')=='Sunday') {
                $status='S';
            } else {
               
                    $attendance = Attendance::where('student_id', $student_id)->whereDate('clock_in_time', $day)->first();

                    if (!empty($attendance)) {
                        $attendance->delete();
                    }
                
            }
        }
      
        
    }
    public function get_students_id(){
        $user = \Auth::user();

        $students=StudentGuardian::forDropdown($user->hook_id);
        $student_ids=[];
        foreach ($students as  $id =>$student){
            $student_ids[]=$id;
           
        }
        return $student_ids;
    }
}
