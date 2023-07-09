<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmEmployeeDocument;
use App\Models\Campus;
use App\Models\HumanRM\HrmDepartment;
use App\Models\HumanRM\HrmAttendance;
use App\Models\HumanRM\HrmDesignation;
use App\Models\HumanRM\HrmEducation;
use App\Models\District;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\EmployeeUtil;
use GuzzleHttp\Client;
use File;
use DB;

class HrmEmployeeController extends Controller
{
    /**
    * Constructor
    *
    * @param EmployeeUtil $employeeUtil
    * @return void
    */
    public function __construct(EmployeeUtil $employeeUtil)
    {
        $this->employeeUtil = $employeeUtil;
        $this->employee_status_colors = [
         'active' => 'bg-success',
         'inactive' => 'bg-info',
         'resign' => 'bg-danger',
    ];
    }
     /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function index()
     {
    //     $HrmEmployees = HrmEmployee::get();
    //    //dd($HrmEmployees);
    //     foreach ($HrmEmployees as $key => $value) {
    //          $employee=HrmEmployee::find($value->id);
    //          $employee->employee_image=$employee->employeeID.'.jpg';
    //         $employee->save();

    //     }
    //     dd(2);
         if (!auth()->user()->can('employee.view')) {
             abort(403, 'Unauthorized action.');
         }

         //dd($HrmEmployees->get());
         if (request()->ajax()) {
             $HrmEmployees = HrmEmployee::leftJoin('campuses', 'hrm_employees.campus_id', '=', 'campuses.id')
             ->leftjoin('hrm_transactions AS t', 'hrm_employees.id', '=', 't.employee_id')

             ->select(
                 'campuses.campus_name',
                 'hrm_employees.father_name',
                 'hrm_employees.employeeID',
                 'hrm_employees.status',
                 'hrm_employees.id as id',
                 'hrm_employees.employee_image',
                 'hrm_employees.joining_date',
                 DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name")
             );
             $permitted_campuses = auth()->user()->permitted_campuses();
             if ($permitted_campuses != 'all') {
                 $HrmEmployees->whereIn('hrm_employees.campus_id', $permitted_campuses);
             }
             if (request()->has('campus_id')) {
                 $campus_id = request()->get('campus_id');
                 if (!empty($campus_id)) {
                     $HrmEmployees->where('hrm_employees.campus_id', $campus_id);
                 }
             }
             if (request()->has('status')) {
                 $status = request()->get('status');
                 if (!empty($status)) {
                     $HrmEmployees->where('hrm_employees.status', $status);
                 }
             }
             if (request()->has('employeeID')) {
                 $employeeID = request()->get('employeeID');
                 if (!empty($employeeID)) {
                     $HrmEmployees->where('hrm_employees.employeeID', 'like', "%{$employeeID}%");
                 }
             }
             if (!empty(request()->start_date) && !empty(request()->end_date)) {
                 $start = request()->start_date;
                 $end =  request()->end_date;
                 $HrmEmployees->whereDate('hrm_employees.joining_date', '>=', $start)
                             ->whereDate('hrm_employees.joining_date', '<=', $end);
             }
             $HrmEmployees->addSelect([
                 DB::raw("COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
               as total_due")
             ]);
             $HrmEmployees->groupBy('hrm_employees.id');
             $datatable = Datatables::of($HrmEmployees)
             ->addColumn(
                 'action',
                 function ($row) {
                     $html= '<div class="dropdown">
                         <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                         <ul class="dropdown-menu" style="">';
                     if (auth()->user()->can('employee.update')) {
                         $html.='<li><a class="dropdown-item "href="' . action('Hrm\HrmEmployeeController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                     }                     if ($row->total_due!=0) {
                         if (auth()->user()->can('hrm.payment')) {
                             $html.='<li><a class="dropdown-item pay_payroll_due "href="' . action('Hrm\HrmTransactionPaymentController@getPayEmployeeDue', [$row->id]) . '"><i class="fas fa-money-bill-alt "></i> ' . __("english.pay_due_amount") . '</a></li>';
                         }
                     }
                     $html.='<li><a class="dropdown-item  "href="' . action('Hrm\HrmEmployeeController@employeeProfile', [$row->id]) . '"><i class="fas fa-user"></i> ' . __("english.profile") . '</a></li>';

                     if (auth()->user()->can('device.sync')) {
                         $html .= '<li><a href="#" data-employee_id="' . $row->id .
                                        '" data-status="' . $row->status . '" class="update_status dropdown-item"><i class="fas fa-edit" aria-hidden="true" ></i>' . __("english.update_status") . '</a></li>';
                         $html .= '<li><a href="#" data-href="' . action('Hrm\HrmEmployeeController@syncWithDevice', [$row->id]) . '" class="sync-with-device"><i class="fas fa-money-bill-alt" aria-hidden="true"></i>' . __("english.sync_with_device") . '</a></li>';
                     }

                     if (auth()->user()->can('employee.status')) {
                         if ($row->status != 'resign') {
                             $html .= '<li><a  href="#" data-employee_id="' . $row->id .
                                        '" data-employee-name="' . $row->employee_name . '" class="employee_resign dropdown-item"><i class="fas fa-edit" aria-hidden="true" ></i>' . __('english.resign') . '</a></li>';
                         }
                     }
                     $html .= '</ul></div>';

                     return $html;
                 }
             )

                ->editColumn('employee_name', function ($row) {
                    $image = file_exists(public_path('uploads/employee_image/'.$row->employee_image)) ? $row->employee_image : 'default.jpg';
                    $status='<div><a  href="' . action('Hrm\HrmEmployeeController@employeeProfile', [$row->id]) . '">
                 <img src="'.url('uploads/employee_image/' . $image).'" class="rounded-circle " width="50" height="50" alt="" >
                 '.ucwords($row->employee_name);
                    $status .='</a></div>';
                    return $status;
                })
                ->editColumn('status', function ($row) {
                    $status_color = !empty($this->employee_status_colors[$row->status]) ? $this->employee_status_colors[$row->status] : 'bg-gray';
                    $status ='<a href="#"'.'data-employee_id="' . $row->id .
                    '" data-status="' . $row->status . '" class="update_status">';
                    $status .='<span class="badge badge-mark ' . $status_color .'">' .__('english.'.$row->status).   '</span></a>';
                    return $status;
                })
             ->filterColumn('employeeID', function ($query, $keyword) {
                 $query->where(function ($q) use ($keyword) {
                     $q->where('hrm_employees.employeeID', 'like', "%{$keyword}%");
                 });
             })
             ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(COALESCE(hrm_employees.first_name, ''), ' ', COALESCE(hrm_employees.last_name, '')) like ?", ["%{$keyword}%"]);
            })
             ->filterColumn('father_name', function ($query, $keyword) {
                 $query->where(function ($q) use ($keyword) {
                     $q->where('hrm_employees.father_name', 'like', "%{$keyword}%");
                 });
             })
             ->editColumn('joining_date', '{{@format_date($joining_date)}}');




             $rawColumns = ['action','campus_name','father_name','status','employee_name'];

             return $datatable->rawColumns($rawColumns)
                   ->make(true);
         }
         $campuses=Campus::forDropdown();

         return view('hrm.employee.index')->with(compact('campuses'));
     }

     /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
     public function create()
     {
         if (!auth()->user()->can('employee.create')) {
             abort(403, 'Unauthorized action.');
         }
         $system_settings_id = session()->get('user.system_settings_id');
         $countries = $this->employeeUtil->allCountries();

         $campuses=Campus::forDropdown();
         $ref_admission_no=$this->employeeUtil->setAndGetReferenceCount('employee_no', true, false);
         $admission_no=$this->employeeUtil->generateReferenceNumber('employee', $ref_admission_no);

         $departments = HrmDepartment::forDropdown();
         $designations = HrmDesignation::forDropdown();
         $educations = HrmEducation::forDropdown();
         $roles = $this->employeeUtil->getRolesArray($system_settings_id);


         return view('hrm.employee.create')->with(compact('roles', 'campuses', 'countries', 'admission_no', 'departments', 'designations', 'educations'));
     }
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
         if (!auth()->user()->can('employee.create')) {
             abort(403, 'Unauthorized action.');
         }

         try {
             DB::beginTransaction();

             $input = $request->except('_token');

             $this->employeeUtil->employeeCreate($request);

             $this->employeeUtil->setAndGetReferenceCount('employee_no', false, true);

             $this->employeeUtil->generateReferenceNumber('employee', false, true);
             $output = ['success' => true,
                         'msg' => __("english.added_success")
                     ];


             DB::commit();
         } catch (\Exception $e) {
             DB::rollBack();
             \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

             $output = ['success' => false,
                     'msg' => __("english.something_went_wrong")
                 ];
         }
         return redirect('hrm-employee')->with('status', $output);
     }
         /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
            /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
       public function edit($id)
       {
           if (!auth()->user()->can('employee.update')) {
               abort(403, 'Unauthorized action.');
           }
           $system_settings_id = session()->get('user.system_settings_id');
           $countries = $this->employeeUtil->allCountries();
           $employee=HrmEmployee::with(['user'])->find($id);
           // dd($employee->user->roles);
           $provinces = Province::forDropdown($system_settings_id, false, $employee->country_id);
           $districts = District::forDropdown($system_settings_id, false, $employee->province_id);
           $cities = City::forDropdown($system_settings_id, false, $employee->district_id);
           $regions = Region::forDropdown($system_settings_id, false, $employee->city_id);
           $campuses=Campus::forDropdown();
           $departments = HrmDepartment::forDropdown();
           $designations = HrmDesignation::forDropdown();
           $educations = HrmEducation::forDropdown();
           $bank_details=json_decode($employee->bank_details);

           $roles = $this->employeeUtil->getRolesArray($system_settings_id);

           return view('hrm.employee.edit')->with(compact('roles', 'campuses', 'employee', 'bank_details', 'countries', 'departments', 'designations', 'educations', 'districts', 'provinces', 'cities', 'regions'));
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
         if (!auth()->user()->can('employee.update')) {
             abort(403, 'Unauthorized action.');
         }
         $output = ['success' => false,
         'msg' => __("english.something_went_wrong")
     ];
         try {
             DB::beginTransaction();

             $input = $request->except('_token');

             $this->employeeUtil->employeeUpdate($request, $id);

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
         return redirect('hrm-employee')->with('status', $output);
     }
      /**
      * Handles the validation email
      *
      * @return \Illuminate\Http\Response
      */
     public function postCheckEmail(Request $request)
     {
         $email = $request->input('email');
         $query = HrmEmployee::where('email', $email);
         $exists = $query->exists();
         if (!$exists) {
             echo "true";
             exit;
         } else {
             echo "false";
             exit;
         }
     }
     public function updateStatus(Request $request)
     {
         if (!auth()->user()->can('employee.status')) {
             abort(403, 'Unauthorized action.');
         }

         if (request()->ajax()) {
             try {
                 DB::beginTransaction();

                 $student = HrmEmployee::find($request->employee_id);
                 $student->status = $request->status;
                 $student->save();

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

     public function employeeResign(Request $request)
     {
         if (!auth()->user()->can('employee.status')) {
             abort(403, 'Unauthorized action.');
         }

         if (request()->ajax()) {
             try {
                 DB::beginTransaction();

                 $employee = HrmEmployee::find($request->employee_id);
                 $employee->status = 'resign';
                 $employee->resign_remark = $request->resign_remark;
                 $employee->save();
                 if ($request->hasFile('resign')) {
                     $filename=$this->employeeUtil->uploadFile($request, 'resign', 'document');
                     HrmEmployeeDocument::create([
                         'employee_id' => $employee->id,
                         'filename' => $filename,
                         'type' => 'resign'
                     ]);
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
  public function syncWithDevice($id)
  {
      if (!auth()->user()->can('device.sync')) {
          abort(403, 'Unauthorized action.');
      }

      if (request()->ajax()) {
          try {
              $student_device_ip=[
                  '192.168.1.201'
              ];
              $options=[];
              $student = HrmEmployee::find($id);
              $name = $student->first_name . ' '.$student->last_name;
              foreach ($student_device_ip as $ip) {
                  $options['form_params'] = ['ip'=>$ip,'name'=>$name,'user_uid'=>$id,'user_id'=>$student->employeeID ];
                  $client = new Client();
                  $response = $client->post('http://localhost/django-admin/api/sync-with-device', $options);
                  $body = $response->getBody();
                  $return_body = json_decode($body, true);
                  // dd($return_body);

                  $output = $return_body;
              }
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
  public function employeeProfile($id)
  {
      if (!auth()->user()->can('employee.profile')) {
          abort(403, 'Unauthorized action.');
      }
      $user = \Auth::user();
      if ($user->hook_id == $id || $user->user_type =='admin' || $user->user_type =='other') {
          $employee = HrmEmployee::with(['campuses','designations','department','education'])->find($id);
          $employee_document=HrmEmployeeDocument::where('employee_id', $id)->get();
          $bank_details=json_decode($employee->bank_details);

          return view('hrm.employee.profile')->with(compact('employee', 'bank_details', 'employee_document'));
      } else {
          return redirect()->back();
      }
  }

  public function get_documents()
  {
      if (!auth()->user()->can('employee_document.view')) {
          abort(403, 'Unauthorized action.');
      }
      if (request()->ajax()) {
          $employee_id = request()->input('employee_id');
          $employee_document=HrmEmployeeDocument::where('employee_id', $employee_id)->select(['type','filename', 'id']);
          return Datatables::of($employee_document)
          ->addColumn(
              'action',
              function ($row) {
                  $html= '<div class="dropdown">
             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
             <ul class="dropdown-menu" style="">';
                  $document_name = !empty(explode("_", $row->filename, 2)[1]) ? explode("_", $row->filename, 2)[1] : $row->filename ;
                  if (auth()->user()->can('employee_document.download')) {
                      $html .= '<li><a  class="dropdown-item  " href="' . url('uploads/document/' . $row->filename) .'" download="' . $document_name . '"><i class="fas fa-download" aria-hidden="true"></i> ' . __("english.download_document") . '</a></li>';
                  }
                  if (isFileImage($document_name)) {
                      $html .= '<li><a href="#" data-href="' . url('uploads/document/' . $row->filename) .'" class=" dropdown-item  view_uploaded_document"><i class="fas fa-image" aria-hidden="true"></i> ' . __("english.view_document") . '</a></li>';
                  }
                  if (auth()->user()->can('employee_document.delete')) {
                      $html.='<li><a class="dropdown-item btn-danger delete_document_destroy_button "href="#" data-href="' . action('Hrm\HrmEmployeeController@document_destroy', [$row->id]) . '"><i class="fas fa-trash"></i> ' . __("english.delete") . '</a></li>';
                  }
                  $html .= '</ul></div>';

                  return $html;
              }
          )

              ->removeColumn('id', 'filename')
              ->rawColumns(['action','type'])
              ->make(true);
      }
  }
      /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function document_create($id)
    {
        if (!auth()->user()->can('employee_document.create')) {
            abort(403, 'Unauthorized action.');
        }

        $employee_id=$id;

        return view('hrm.employee.document_create')->with(compact('employee_id'));
    }
    public function document_post(Request $request)
    {
        if (!auth()->user()->can('employee_document.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['employee_id','type']);
            $filename=$this->employeeUtil->uploadFile($request, 'document', 'document');
            HrmEmployeeDocument::create([
                'employee_id' => $input['employee_id'],
                'filename' => $filename,
                'type' => $input['type']
            ]);



            $output = ['success' => true,
                        'msg' => __("english.added_success")
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
        }

        return $output;
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function document_destroy($id)
    {
        if (!auth()->user()->can('employee_document.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $document = HrmEmployeeDocument::findOrFail($id);
                if (File::exists(public_path('uploads/document/'.$document->filename))) {
                    File::delete(public_path('uploads/document/'.$document->filename));
                }
                $document->delete();

                $output = ['success' => true,
                        'msg' => __("english.deleted_success")
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
            }

            return $output;
        }
    }
      /**
   * Shows ledger for employees
   *
   * @param  \Illuminate\Http\Request
   * @return \Illuminate\Http\Response
   */
    public function getLedger()
    {
        $employee_id = request()->input('employee_id');

        $start_date = request()->start_date;
        $end_date =  request()->end_date;

        $employee = HrmEmployee::find($employee_id);


        $ledger_details = $this->employeeUtil->getLedgerDetails($employee_id, $start_date, $end_date);

        if (request()->input('action') == 'pdf') {
            $for_pdf = true;
            $html = view('hrm.employee.partials.pay_ledger')
             ->with(compact('ledger_details', 'employee', 'for_pdf'))->render();
            $mpdf = $this->getMpdf();
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }

        return view('hrm.employee.partials.pay_ledger')
             ->with(compact('ledger_details', 'employee'));
    }



  public function getEmployeeAttendance()
  {
      $employee_id = request()->input('employee_id');
      $year = request()->input('year');
      $month = request()->input('month');

      $result = array();
      $new_date = "01-" . $month . "-" . $year;
      $totalDays = \Carbon::createFromDate($year, $month)->daysInMonth;//cal_days_in_month(CAL_GREGORIAN, $month, $year);
      $first_day_this_month = date('01-m-Y');
      $fst_day_str = strtotime(date('d-m-Y', strtotime($new_date)));
      $array = array();
      for ($day = 1; $day <= $totalDays; $day++) {
          $date = date('Y-m-d', $fst_day_str);
          $employe_attendance =HrmAttendance::where('employee_id', $employee_id)->whereDate('clock_in_time', $date)->first();
          if (!empty($employe_attendance)) {
              $s = array();
              //'present','late','absent','half_day','holiday','weekend','leave'
              $s['date'] = $date;
              $s['badge'] = false;
              $s['footer'] = "Extra information";
              $type = $employe_attendance->type;
              $s['title'] = $type;
              if ($type == 'present') {
                  $s['classname'] = "badge bg-success";
              } elseif ($type == 'absent') {
                  $s['classname'] = "badge bg-danger";
              } elseif ($type == 'late') {
                  $s['classname'] = "badge bg-warning";
              } elseif ($type == 'half_day') {
                  $s['classname'] = "badge bg-dark";
              } elseif ($type == 'holiday') {
                  $s['classname'] = "badge holiday";
              } elseif ($type == 'weekend') {
                  $s['classname'] = "badge weekend";
              } elseif ($type == 'leave') {
                  $s['classname'] = "badge bg-info";
              }
              $array[] = $s;
          }
          $fst_day_str = ($fst_day_str + 86400);
      }
      if (!empty($array)) {
          echo json_encode($array);
      } else {
          echo false;
      }
  }
}
