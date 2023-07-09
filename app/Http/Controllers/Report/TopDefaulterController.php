<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Campus;
use DB;
use App\Utils\Util;
use Yajra\DataTables\Facades\DataTables;

class TopDefaulterController extends Controller
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
        $this->student_status_colors = [
            'active' => 'bg-success',
            'inactive' => 'bg-info',
            'struct_up' => 'bg-warning',
            'pass_out' => 'bg-danger',
             'took_slc' => 'bg-secondary',
        ];
        $this->limit=10;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('strength_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {

        $student_list=Student::leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
        ->leftjoin('fee_transactions AS t', 'students.id', '=', 't.student_id')
       ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
       ->leftJoin('class_sections', 'students.current_class_section_id', '=', 'class_sections.id')
        ->select(
            'campuses.campus_name',
            'c-class.title as current_class',
            'class_sections.section_name as section_name',
            'students.id',
            'students.father_name',
            'students.status',
            'students.roll_no',
            'students.student_image',
            //'students.mobile_no',
            
            DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
        );
        $student_list->addSelect([
            DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            +COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            +COALESCE(SUM(IF(t.type = 'other_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)  as total_due")
        ]);
        $student_list->addSelect([
            DB::raw("COALESCE(SUM(IF(t.type = 'transport_fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'transport_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due_transport_fee")
        ]);

           // Check for permitted campuses of a user
           $permitted_campuses = auth()->user()->permitted_campuses();
           if ($permitted_campuses != 'all') {
               $student_list->whereIn('students.campus_id', $permitted_campuses);
           }
           if (request()->has('campus_id')) {
            $campus_id = request()->get('campus_id');
            if (!empty($campus_id)) {
                $student_list->where('class_sections.campus_id', $campus_id);
            }
        }
        if (request()->has('class_id')) {
            $class_id = request()->get('class_id');
            if (!empty($class_id)) {
                $student_list->where('class_sections.class_id', $class_id);
            }
        }
        if (request()->has('class_section_id')) {
            $class_section_id = request()->get('class_section_id');
            if (!empty($class_section_id)) {
                $student_list->where('class_sections.id', $class_section_id);
            }
        }

        $student_list->groupBy('students.id');
       $student_list->orderBy('total_due','DESC');
    
       if (request()->has('limit')) {
        $limit = request()->get('limit');
        $this->limit=$limit;

        if (!empty($limit)) {
            $student_list->limit($limit);     
           }
    }

       $datatable = Datatables::of($student_list)
       ->limit(function ($query ) {      
        $query->limit($this->limit);
    })

       ->editColumn('student_name', function ($row) {
        $image = file_exists(public_path('uploads/student_image/'.$row->student_image)) ? $row->student_image : 'default.png';
        $status='<div><a  href="' . action('StudentController@studentProfile', [$row->id]) . '">
     <img src="'.url('uploads/student_image/' . $image).'" class="rounded-circle " width="50" height="50" alt="" >
     '.ucwords($row->student_name);
        if ($row->student_transport_fee>0) {
            $status.='<i class="fadeIn animated bx bx-bus-school"></i>';
        }
        $status .='</a></div>';
        return $status;
    })
    ->editColumn('status', function ($row) {
        $status_color = !empty($this->student_status_colors[$row->status]) ? $this->student_status_colors[$row->status] : 'bg-gray';
        $status ='<a href="#"'.'data-student_id="' . $row->id .
     '" data-status="' . $row->status . '" class="update_status">';
        $status .='<span class="badge badge-mark ' . $status_color .'">' .__('english.'.$row->status).   '</span></a>';
        return $status;
    })
    ->editColumn('current_class', function ($row) {
        $current_class_section_name = $row->current_class. ' '. $row->section_name;
        return  $current_class_section_name;
    })
    ->editColumn('total_due', function ($row) {
        $html = '<span data-orig-value="' . $row->total_due . '">' . $this->util->num_f($row->total_due, true) . '</span>';
        return $html;
    })
    ->editColumn('total_due_transport_fee', function ($row) {
        $html = '<span data-orig-value="' . $row->total_due_transport_fee . '">' . $this->util->num_f($row->total_due_transport_fee, true) . '</span>';
        return $html;
    })
    ->filterColumn('roll_no', function ($query, $keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('students.roll_no', 'like', "%{$keyword}%");
        });
    })
    ->filterColumn('student_name', function ($query, $keyword) {
        $query->whereRaw("CONCAT(COALESCE(students.first_name, ''), ' ', COALESCE(students.last_name, '')) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('father_name', function ($query, $keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('students.father_name', 'like', "%{$keyword}%");
        });
    })
    ->removeColumn('id', 'student_image', 'section_name');


      

    $rawColumns = ['current_class','father_name','status','student_name','total_due','total_due_transport_fee'];

    return $datatable->rawColumns($rawColumns)
          ->make(true);
    }
    $campuses=Campus::forDropdown();

    return view('Report.top-defaulters')->with(compact('campuses'));

    }

}
