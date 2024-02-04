<?php

namespace App\Http\Controllers;
use App\Models\Frontend\OnlineApplicant;

use Illuminate\Http\Request;
use DB;

use App\Models\ClassSection;
use App\Models\Classes;
use App\Models\Campus;
use App\Mlang;
use App\Models\Category;
use App\Models\District;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use App\Models\Student;
use App\Models\Session;
use App\Models\Guardian;
use App\Models\StudentGuardian;
use App\Models\FeeTransaction;
use App\Models\FeeTransactionPayment;
use App\Models\Discount;
use App\Models\Vehicle;
use App\Models\Attendance;
use App\Models\FeeHead;
use App\Models\StudentDocument;
use Yajra\DataTables\Facades\DataTables;

use File;
use GuzzleHttp\Client;
use App\Models\Exam\ExamAllocation;
use App\Models\Sim;
use App\Models\Sms;
use App\Utils\NotificationUtil;

class OnlineApplicantController extends Controller
{
    
    public function __construct(){
        $this->student_status_colors = [
            'confirm' => 'bg-success',
            'online_admission' => 'bg-info',
            'hold' => 'bg-warning',
            'reject' => 'bg-danger',
        ];
    }/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('student.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
        $student_list=OnlineApplicant::leftJoin('campuses', 'online_applicants.campus_id', '=', 'campuses.id')
       ->leftJoin('classes as adm-class', 'online_applicants.adm_class_id', '=', 'adm-class.id')
        ->select(
            'campuses.campus_name',
            'adm-class.title as adm_class',
            'online_applicants.father_name',
            'online_applicants.father_cnic_no',
            'online_applicants.cnic_no',
            'online_applicants.birth_date',
            'online_applicants.mobile_no',
            'online_applicants.gender',
            'online_applicants.online_applicant_no',
            'online_applicants.status',
            'online_applicants.id as id',
            'online_applicants.student_image',
            'online_applicants.applicant_submit_date',
            'online_applicants.std_permanent_address',
            DB::raw("CONCAT(COALESCE(online_applicants.first_name, ''),' ',COALESCE(online_applicants.last_name,'')) as student_name"),
        );
            // Check for permitted campuses of a user
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $student_list->whereIn('online_applicants.campus_id', $permitted_campuses);
            }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $student_list->where('online_applicants.campus_id', $campus_id);
                }
            }
            if (request()->has('class_id')) {
                $class_id = request()->get('class_id');
                if (!empty($class_id)) {
                    $student_list->where('online_applicants.adm_class_id', $class_id);
                }
            }
         
            if (request()->has('status')) {
                $status = request()->get('status');
                if (!empty($status)) {
                    $student_list->where('online_applicants.status', $status);
                }
            }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $student_list->whereDate('online_applicants.applicant_submit_date', '>=', $start)
                            ->whereDate('online_applicants.applicant_submit_date', '<=', $end);
            }
        $datatable = Datatables::of($student_list)
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
               ->editColumn('student_name', function ($row) {
                   $image = file_exists(public_path('uploads/student_image/'.$row->student_image)) ? $row->student_image : 'default.png';
                   $status='<div>
                <img src="'.url('uploads/student_image/' . $image).'" class="rounded-circle " width="50" height="50" alt="" >
                '.ucwords($row->student_name);
                  
                   $status .='</div>';
                   return $status;
               })
             
               ->editColumn('status', function ($row) {
                $status_color = !empty($this->student_status_colors[$row->status]) ? $this->student_status_colors[$row->status] : 'bg-gray';
                $status ='<a href="#"'.'data-student_id="' . $row->id .
             '" data-status="' . $row->status . '" class="update_status">';
                $status .='<span class="badge badge-mark ' . $status_color .'">' .__('english.'.$row->status).   '</span></a>';
                return $status;
            })
               ->editColumn('adm_class', function ($row) {
                   $adm_class_section_name = $row->adm_class. ' '. $row->adm_section_name;
                   return  $adm_class_section_name;
               })
           
        
            ->filterColumn('student_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(COALESCE(online_applicants.first_name, ''), ' ', COALESCE(online_applicants.last_name, '')) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('father_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('online_applicants.father_name', 'like', "%{$keyword}%");
                });
            })
           // ->editColumn('admission_date', '{{@format_date($admission_date)}}')
        

            ->removeColumn('id', 'student_image');



            $rawColumns = ['action','campus_name','adm_class','birth_date','father_name','status','student_name'];

            return $datatable->rawColumns($rawColumns)
                  ->make(true);
        }
        // dd($query);


        $campuses=Campus::forDropdown();
      
      $classes=Classes::forDropdown(1, false);
      
      //  dd($classes);
        return view('online_applicant.index')->with(compact('campuses', 'classes'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
