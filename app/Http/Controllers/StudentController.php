<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\AssessmentStudent;
use App\Models\StudentDocument;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\StudentUtil;
use App\Utils\FeeTransactionUtil;
use Carbon;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassTimeTable;
use DB;
use File;
use GuzzleHttp\Client;
use App\Models\Exam\ExamAllocation;
use App\Models\Sim;
use App\Models\Sms;
use App\Utils\NotificationUtil;

class StudentController extends Controller
{
    protected $studentUtil;
    protected $feeTransactionUtil;
    protected $notificationUtil;

    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(StudentUtil $studentUtil, FeeTransactionUtil $feeTransactionUtil)
    {
        $this->studentUtil = $studentUtil;
        $this->feeTransactionUtil = $feeTransactionUtil;
        $this->student_status_colors = [
            'active' => 'bg-success',
            'inactive' => 'bg-info',
            'struct_up' => 'bg-warning',
            'pass_out' => 'bg-danger',
             'took_slc' => 'bg-secondary',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $students=Student::get();
        // foreach($students as $student){
        //     $std=Student::find($student->id);
        //     $section=ClassSection::where('class_id',$std->current_class_id)->first();
        //     $std->current_class_section_id=$section->id;
        //     $section_adm=ClassSection::where('class_id',$std->adm_class_id)->first();
        //     $std->adm_class_section_id=$section_adm->id;
        //     $std->save();
        // }
       // dd(5);
//          $sms=Sms::get();
//          $now=[];

// foreach ($sms as $sim) {
// //     $smss=Sms::find($sim->id);
// //     if(trim($sim->Balsnce_promotion)=='-'){
// //         $smss->Balsnce_promotion=0;
// //     }
// //     if(trim($sim->Balance_fee)=='-'){
// //         $smsd->Balance_fee=0;
// //     }
// //     $smss->save();
// // }
// // dd($sms);
// $student=Student::where('old_roll_no',$sim->ID_No)->first();
// if (!empty($student)) {
//        //Add opening balance
//        $opening_balance=$this->studentUtil->num_uf($sim->Balsnce_promotion);
//        if (!empty($opening_balance)) {
//         if ($opening_balance>0) {
//            // $this->studentUtil->createOpeningBalanceTransaction(1, $student, $opening_balance, false);
//             $this->studentUtil->createOtherBalanceTransaction(1, $student, $opening_balance, false);
//         }
//     }
//     // $student->fee_before_discount=$this->studentUtil->num_uf($sim->Fee_for_January);
//     // $newprice = $this->studentUtil->num_uf($sim->Fee_for_January)*$sim->Dis_;
//     // $student->student_tuition_fee=$this->studentUtil->num_uf($sim->Fee_for_January)-$newprice;
//     // if ($sim->Dis_>0) {
//     //     $student->discount_per=100-($student->student_tuition_fee/$student->fee_before_discount*100);
//     // }


//     // $student->save();
// }else{
//     $now[]=$sim->ID_No;
// }
       
//             // dd($student);
//             // dd(trim(trim($sim->Fee_for_January),','));
// }

//          dd($now);
    //     $student_list=Student::get();
    //     foreach($student_list as $student){
    //         $std=Student::find($student->id);
    //         $std->mobile_no= preg_replace('/0/', '+92', $std->mobile_no, 1);
    //         $std->save();
    //     }
    //    dd(1); // outpu
  
        if (!auth()->user()->can('student.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');

            $student_list=Student::leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
            ->leftjoin('fee_transactions AS t', 'students.id', '=', 't.student_id')
           ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
           ->leftJoin('classes as adm-class', 'students.adm_class_id', '=', 'adm-class.id')
           ->leftJoin('class_sections', 'students.current_class_section_id', '=', 'class_sections.id')
           ->leftJoin('class_sections as adm_section', 'students.adm_class_section_id', '=', 'adm_section.id')
           ->leftJoin('vehicles', 'students.vehicle_id', '=', 'vehicles.id')
            ->where('students.system_settings_id', $system_settings_id)
            ->select(
                'campuses.campus_name',
                'adm-class.title as adm_class',
                'c-class.title as current_class',
                'class_sections.section_name as section_name',
                'adm_section.section_name as adm_section_name',
                'students.father_name',
                'students.birth_date',
                'students.roll_no',
                'students.mobile_no',
                'students.old_roll_no',
                'students.gender',
                'students.admission_no',
                'students.status',
                'students.id as id',
                'students.student_image',
                'students.admission_date',
                'students.student_transport_fee',
                'students.std_permanent_address',
                'students.student_tuition_fee',
                DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
                DB::raw("CONCAT(COALESCE(vehicles.name, ''),' ',COALESCE(vehicles.vehicle_model,'')) as vehicle_name")
            );
            // $student_list->addSelect([
            //     DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            //     +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            //     +COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            //     +COALESCE(SUM(IF(t.type = 'other_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
            //     +COALESCE(SUM(IF(t.type = 'transport_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'transport_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due")
            // ]);
            $student_list->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'other_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due")
            ]);
            $student_list->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'transport_fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'transport_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due_transport_fee")
            ]);
            $student_list->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) as total_admission_fee")
            ]);
            // Check for permitted campuses of a user
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $student_list->whereIn('students.campus_id', $permitted_campuses);
            }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $student_list->where('students.campus_id', $campus_id);
                }
            }
            if (request()->has('class_id')) {
                $class_id = request()->get('class_id');
                if (!empty($class_id)) {
                    $student_list->where('students.current_class_id', $class_id);
                }
            }
            if (request()->has('class_section_id')) {
                $class_section_id = request()->get('class_section_id');
                if (!empty($class_section_id)) {
                    $student_list->where('students.current_class_section_id', $class_section_id);
                }
            }
            if (request()->has('status')) {
                $status = request()->get('status');
                if (!empty($status)) {
                    $student_list->where('students.status', $status);
                }
            }
            if (request()->has('vehicle_id')) {
                $vehicle_id = request()->get('vehicle_id');
                if (!empty($vehicle_id)) {
                    $student_list->where('students.vehicle_id', $vehicle_id);
                }
            }
            if (request()->has('admission_no')) {
                $admission_no = request()->get('admission_no');
                if (!empty($admission_no)) {
                    $student_list->where('students.admission_no', 'like', "%{$admission_no}%");
                }
            }
            if (request()->has('roll_no')) {
                $roll_no = request()->get('roll_no');
                if (!empty($roll_no)) {
                    $student_list->where('students.roll_no', 'like', "%{$roll_no}%");
                }
            }
            if (request()->has('only_transport')) {
                $only_transport = request()->get('only_transport');
                if (!empty($only_transport)) {
                    $student_list->where('students.student_transport_fee', '>', 0);
                }
            }

            $student_list->groupBy('students.id');
            $student_list->orderBy('students.current_class_id', 'asc');
            $datatable = Datatables::of($student_list)
            ->addColumn(
                'action',
                function ($row) {
                    $html= '<div class="dropdown">
                         <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                         <ul class="dropdown-menu" style="">';
                    if (auth()->user()->can('student.update')) {
                        $html.='<li><a class="dropdown-item "href="' . action('StudentController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
if ($row->total_admission_fee<=0) {
    $html.='<li><a class="dropdown-item admission_add_button " href="#" data-href="' . action('StudentController@addAdmissionFee', [$row->id]) . '"><i class="bx bxs-plus-square "></i> ' . __("english.add_admission_fee") . '</a></li>';
}
                    }
                    if (auth()->user()->can('student.profile')) {
                        $html.='<li><a class="dropdown-item "href="' . action('StudentController@studentProfile', [$row->id]) . '"><i class="bx bx-user-pin "></i> ' . __("english.profile") . '</a></li>';
                    }
                    if (auth()->user()->can('withdrawal.create')) {
                        $html.='<li><a class="dropdown-item "href="' . action('Certificate\WithdrawalRegisterController@edit', [$row->id]) . '"><i class="lni lni-angle-double-up"></i> ' . __("english.withdraw") . '</a></li>';
                    }
                    $html .= '<li><a class="dropdown-item sync-with-device" href="#" data-href="' . action('StudentController@syncWithDevice', [$row->id]) . '"><i class="bx bx-fingerprint" aria-hidden="true"></i>' . __("english.sync_with_device") . '</a></li>';
                    if (auth()->user()->can('print.admission_form')) {
                        $html .= '<li><a class="dropdown-item " href="' . action('StudentController@admissionForm', [$row->id]) . '" class=""><i class="bx bx-printer"" aria-hidden="true"></i>' . __("english.print_admission_form") . '</a></li>';
                    }                    //$html .= '<li><a href="#" class="print-invoice" data-href="' . route('sell.printInvoice', [$row->id]) . '"><i class="fas fa-print" aria-hidden="true"></i> ' . __("english.print_invoice") . '</a></li>';
                    if (auth()->user()->can('fee.add_fee_payment')) {
                        if ($row->total_due!=0) {
                            $html.='<li><a class="dropdown-item pay_fee_due "href="' . action('FeeTransactionPaymentController@getPayStudentDue', [$row->id]) . '"><i class="fas fa-money-bill-alt "></i> ' . __("english.pay_due_amount") . '</a></li>';
                        }
                    }
                    if (auth()->user()->can('student.status')) {
                        $html .= '<li><a class="dropdown-item update_status" href="#" data-student_id="' . $row->id .
                                       '" data-status="' . $row->status . '"><i class="lni lni-invention" aria-hidden="true" ></i>' . __("english.update_status") . '</a></li>';
                    }
                    $html .= '</ul></div>';

                    return $html;
                }
            )
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
               ->editColumn('adm_class', function ($row) {
                   $adm_class_section_name = $row->adm_class. ' '. $row->adm_section_name;
                   return  $adm_class_section_name;
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
            ->editColumn('admission_date', '{{@format_date($admission_date)}}')
            ->editColumn('student_tuition_fee', function ($row) {
                $html = '<span data-orig-value="' . $row->student_tuition_fee . '">' . $this->feeTransactionUtil->num_f($row->student_tuition_fee, true) . '</span>';
                return $html;
            })
            ->editColumn('student_transport_fee', function ($row) {
                $html = '<span data-orig-value="' . $row->student_transport_fee . '">' . $this->feeTransactionUtil->num_f($row->student_transport_fee, true) . '</span>';
                return $html;
            })
            ->editColumn('total_due', function ($row) {
                $html = '<span data-orig-value="' . $row->total_due . '">' . $this->feeTransactionUtil->num_f($row->total_due, true) . '</span>';
                return $html;
            })
            ->editColumn('total_due_transport_fee', function ($row) {
                $html = '<span data-orig-value="' . $row->total_due_transport_fee . '">' . $this->feeTransactionUtil->num_f($row->total_due_transport_fee, true) . '</span>';
                return $html;
            })
            ->editColumn('birth_date', function ($row) {
                $html = '<span data-orig-value="' . $row->birth_date . '">' . $this->feeTransactionUtil->format_date($row->birth_date, false) .'<br> Age:'.$this->feeTransactionUtil->age($row->birth_date). '</span>';
                return $html;
            })

            ->removeColumn('id', 'student_image', 'section_name');



            $rawColumns = ['action','campus_name','adm_class','current_class','birth_date','father_name','status','student_name','student_tuition_fee','student_transport_fee','total_due','admission_date','total_due_transport_fee'];

            return $datatable->rawColumns($rawColumns)
                  ->make(true);
        }
        // dd($query);


        $campuses=Campus::forDropdown();
        $vehicles=Vehicle::forDropdown();
        $campus_id=null;
        $classes=[];
        $sections=[];
        if(request()->has('campus_id')){
            $campus_id= request()->get('campus_id');
            $classes=Classes::forDropdown(1, false, $campus_id);
        }
        $class_id=null;
        if(request()->has('class_id')){
            $class_id = request()->get('class_id');
            
        }
        $class_section_id=null;
        if(request()->has('class_section_id')){
            $class_section_id = request()->get('class_section_id');
            $sections=ClassSection::forDropdown(1, false, $class_id);

        }
      //  dd($classes);
        return view('students.index')->with(compact('campuses', 'vehicles','campus_id','class_id','class_section_id','classes','sections'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('student.create')) {
            abort(403, 'Unauthorized action.');
        }
        // $students= Student::Where('student_transport_fee','>',0)->where('status','active')->get();
        //dd($students);

        // foreach($students as $student){

        //      $std=Student::find($student->id);
        //      $transport=$student->student_transport_fee;
        //      $std->student_transport_fee=$transport+500;
        //      $std->save();
        // }
        $system_settings_id = session()->get('user.system_settings_id');
        $countries = $this->studentUtil->allCountries();

        $campuses=Campus::forDropdown();
        $vehicles=Vehicle::forDropdown();
        $categories=Category::forDropdown();
        $sessions=Session::forDropdown(false, true);
        $ref_admission_no=$this->studentUtil->setAndGetReferenceCount('admission_no', true, false);
        $admission_no=$this->studentUtil->generateReferenceNumber('admission', $ref_admission_no);
        //dd($ref_admission_no);
        // dd($session);

        $districts = District::forDropdown($system_settings_id, false);

        return view('students.create')->with(compact('campuses', 'countries', 'sessions', 'admission_no', 'categories', 'districts', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('student.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();

            $input = $request->except('_token');

            if (!empty($input['guardian_link_id']) && !empty($input['sibling_id'])) {
                $this->studentUtil->studentCreate($request, $input['guardian_link_id']);
                $this->studentUtil->setAndGetReferenceCount('admission_no', false, true);

                $this->studentUtil->setAndGetRollNoCount('roll_no', false, true, $input['adm_session_id']);


                $output = ['success' => true,
                        'msg' => __("english.updated_success")
                        ];
            } else {
                $this->studentUtil->studentCreate($request);
                $this->studentUtil->setAndGetReferenceCount('admission_no', false, true);

                $this->studentUtil->setAndGetRollNoCount('roll_no', false, true, $input['adm_session_id']);
                // $student = $this->studentUtil->getStudentDue($student_id);
                // $this->notificationUtil->autoSendStudentPaymentNotification('owner_payment_received', $student, $parent_payment);


                $output = ['success' => true,
                        'msg' => __("english.updated_success")
                        ];
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                    'msg' => $e->getMessage()
                ];
        }
        return redirect('students')->with('status', $output);
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
        if (!auth()->user()->can('student.update')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $countries = $this->studentUtil->allCountries();

        $campuses=Campus::forDropdown();
        $vehicles=Vehicle::forDropdown();
        $categories=Category::forDropdown();
        $sessions=Session::forDropdown(false, false);
        $domicile = District::forDropdown($system_settings_id, false);
        $student=Student::with(['guardian','guardian.student_guardian'])->find($id);
        //dd($student);
        $siblings=StudentGuardian::with(['student_guardian','students'])->where('student_id', '!=', $student->id)->where('guardian_id', $student->guardian->student_guardian->id)->get();
        // dd($siblings->students->first_name);

        // dd($student->guardian->student_guardian->guardian_name);
        $classes=Classes::forDropdown($system_settings_id, false, $student->campus_id);
        $sections=ClassSection::forDropdown($system_settings_id, false, $student->current_class_id);
        $provinces = Province::forDropdown($system_settings_id, false, $student->country_id);
        $districts = District::forDropdown($system_settings_id, false, $student->province_id);
        $cities = City::forDropdown($system_settings_id, false, $student->district_id);
        $regions = Region::forDropdown($system_settings_id, false, $student->city_id);
        $ob_transaction =  FeeTransaction::where('student_id', $id)
        ->where('type', 'opening_balance')
        ->first();
        $opening_balance = !empty($ob_transaction->final_total) ? $ob_transaction->final_total : 0;
        //Deduct paid amount from opening balance.
        if (!empty($opening_balance)) {
            // $opening_balance_paid = $this->transactionUtil->getTotalAmountPaid($ob_transaction->id);
            // if (!empty($opening_balance_paid)) {
            //     $opening_balance = $opening_balance - $opening_balance_paid;
            // }

            $opening_balance = $this->studentUtil->num_f($opening_balance);
        }
        return view('students.edit')->with(compact(
            'student',
            'campuses',
            'countries',
            'sessions',
            'categories',
            'districts',
            'vehicles',
            'classes',
            'sections',
            'provinces',
            'domicile',
            'cities',
            'regions',
            'opening_balance',
            'siblings'
        ));
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
        if (!auth()->user()->can('student.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();

            $input = $request->except('_token');

            if (!empty($input['remove-sibling'])) {
                $this->studentUtil->removeSibling($input['guardian'], $id);
            }
            if (!empty($input['guardian_link_id']) && !empty($input['sibling_id'])) {
                $output=$this->studentUtil->updateStudent($request, $id, $input['guardian_link_id']);
            } else {
                $output= $this->studentUtil->updateStudent($request, $id);
            }
            // $this->studentUtil->setAndGetReferenceCount('admission_no', false, true);
            // $this->studentUtil->setAndGetRollNoCount('roll_no', false, true, $input['adm_session_id']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                    'msg' => __("english.something_went_wrong")
                ];
        }
        return redirect('students')->with('status', $output);
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



    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function addSibling()
    {
        $system_settings_id = session()->get('user.system_settings_id');

        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id);
        return view('students.sibling')->with(compact('campuses', 'classes'));
    }
    public function getByClassAndSection(Request $request)
    {
        if (!empty($request->input('class_id'))) {
            $class_id = $request->input('class_id');
            $section_id = $request->input('section_id');
            $system_settings_id = session()->get('user.system_settings_id');
            $students = Student::forDropdown($system_settings_id, false, $class_id, $section_id);
            $html = '<option value="">' . __('english.please_select') . '</option>';
            //$html = '';
            if (!empty($students)) {
                foreach ($students as $id => $name) {
                    $html .= '<option value="' . $id .'">' . $name. '</option>';
                }
            }

            return $html;
        }
    }
    public function getStudentRecordByID(Request $request)
    {
        if (!empty($request->input('student_id'))) {
            $student_id = $request->input('student_id');
            $system_settings_id = session()->get('user.system_settings_id');
            $results=Student::with(['guardian','guardian.student_guardian'])->select('id', DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,''),'(',COALESCE(students.roll_no,''),')')  as full_name"))->find($student_id);

            return  json_encode($results);
        }
    }



    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function addAdmissionFee($id)
    {
        $system_settings_id = session()->get('user.system_settings_id');
        $student=Student::with(['guardian','guardian.student_guardian'])->find($id);
        $fee_heads=$this->studentUtil->getAdmissionFeeHeads($student->campus_id, $student->current_class_id);
        $classes=Classes::find($student->adm_class_id);
        //dd($classes->admission_fee);
        return view('students/partials.admission_fee')->with(compact('student', 'fee_heads', 'classes'));
    }
    public function postAdmissionFee(Request $request)
    {
        try {
            DB::beginTransaction();
            $transaction=$this->feeTransactionUtil->createFeeTransaction($request, 'admission_fee');
            $this->feeTransactionUtil->createFeeTransactionLines($request->fee_heads, $transaction);
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

    public function updateStatus(Request $request)
    {
        if (!auth()->user()->can('student.status')) {
            abort(403, 'Unauthorized action.');
        }
     

        if (request()->ajax()) {
            try {
                DB::beginTransaction();

                $student = Student::find($request->student_id);
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



    public function getStudents()
    {
        if (request()->ajax()) {
            $search_term = request()->input('term', '');
            $campus_id = request()->input('campus_id', null);
            $class_id = request()->input('class_id', null);
            $father_name = request()->input('father_name', null);
            $class_section_id = request()->input('class_section_id', null);
            // dd($campus_id, $class_id, $class_section_id);
            $result = $this->filterStudents($search_term, $campus_id, $class_id, $class_section_id, $father_name);
            return json_encode($result);
        }
    }

    /**
    * Filters product as per the given inputs and return the details.
    *
    * @param string $search_type (like or exact)
    *
    * @return object
    */
    public function filterStudents($search_term, $campus_id =null, $class_id=null, $class_section_id=null, $father_name, $search_fields = ['name','roll_no','old_roll_no','father_name','mobile_no'], $search_type = 'like')
    {
        $query =  Student::leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
                ->leftjoin('fee_transactions AS t', 'students.id', '=', 't.student_id')
               ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id');
        // Check for permitted campuses of a user
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('students.campus_id', $permitted_campuses);
        }
        //Include search
        if (!empty($campus_id)) {
            $query->where('students.campus_id', $campus_id);
        }
        if (!empty($class_id)) {
            $query->where('students.current_class_id', $class_id);
        }
        if (!empty($father_name)) {
            $query->orWhere('students.father_name', 'like', '%' . $father_name.'%');
        }
        if (!empty($class_section_id)) {
            $query->where('students.current_class_section_id', $class_section_id);
        }
        if (!empty($search_term)) {
            //Search with like condition
            if ($search_type == 'like') {
                $query->where(function ($query) use ($search_term, $search_fields) {
                    if (in_array('name', $search_fields)) {
                        $query->where('students.first_name', 'like', '%' . $search_term .'%');
                    }

                    if (in_array('roll_no', $search_fields)) {
                        $query->orWhere('students.roll_no', 'like', '%' . $search_term .'%');
                    }
                    if (in_array('old_roll_no', $search_fields)) {
                        $query->orWhere('students.old_roll_no', 'like', '%' . $search_term .'%');
                    }
                    // if (in_array('father_name', $search_fields)) {
                    //     $query->orWhere('students.father_name', 'like', '%' . $search_term .'%');
                    // }
                    if (in_array('mobile_no', $search_fields)) {
                        $query->orWhere('students.mobile_no', 'like', '%' . $search_term .'%');
                    }
                });
            }
        }
        $query->select(
            'campuses.campus_name',
            'campuses.id as campus_id',
            'c-class.title as current_class',
            'students.father_name',
            'students.roll_no',
            'students.old_roll_no',
            'students.status',
            'students.id as id',
            'students.student_image',
            DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name")
        );



        $query->groupBy('students.id');
        return $query->get();
    }




    public function syncWithDevice($id)
    {
        if (!auth()->user()->can('student.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $student_device_ip=[
                    '192.168.1.202',
                    '192.168.1.203'
                ];
                $options=[];
                $student = Student::find($id);
                $name = $student->first_name . ' '.$student->last_name;
                foreach ($student_device_ip as $ip) {
                    $options['form_params'] = ['ip'=>$ip,'name'=>$name,'user_uid'=>$id,'user_id'=>$student->roll_no];
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




    public function admissionForm($id)
    {
        if (!auth()->user()->can('print.admission_form')) {
            abort(403, 'Unauthorized action.');
        }

        // echo $now->weekOfYear;
        $student=Student::with(['campuses','current_class','current_class_section' ,'admission_class','guardian','guardian.student_guardian'
        ,'country','province','district','city','region','adm_session'])->find($id);
        $siblings=StudentGuardian::with(['student_guardian','students','students.current_class','students.current_class_section'])->where('student_id', '!=', $student->id)->where('guardian_id', $student->guardian->student_guardian->id)->get();

        //dd($siblings);
        // dd(json_encode($results));
        $logo = 'Pk';
        // dd($system_settings->pdf);
        // dd(public_path());
        if (File::exists(public_path('uploads/pdf/admission-form.pdf'))) {
            File::delete(public_path('uploads/pdf/admission-form.pdf'));
        }
        $pdf =  config('constants.mpdf');
        if ($pdf) {
            return view('MPDF.admission-form')->with(compact('student', 'siblings'));
        }
        $pdf_name='admission-form.pdf';
        $snappy = \WPDF::loadView('students.admission-form', compact('student', 'siblings'));
        $headerHtml = view()->make('common._header', compact('logo'))->render();
        $footerHtml = view()->make('common._footer')->render();
        $snappy->setOption('header-html', $headerHtml);
        $snappy->setOption('footer-html', $footerHtml);
        $snappy->setPaper('a4')->setOption('margin-top', 35)->setOption('margin-left', 5)->setOption('margin-right', 5);
        $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
        return $snappy->stream();
    }
    public function studentProfile($id)
    {
        if (!auth()->user()->can('student.profile')) {
            abort(403, 'Unauthorized action.');
        }
        $user = \Auth::user();

        if ($user->hook_id == $id || $user->user_type =='guardian' || $user->user_type =='admin' || $user->user_type =='other') {
            $student = Student::with(['studentCategory','guardian','guardian.student_guardian','admission_class','current_class','current_class_section'])->findOrFail($id);
            $siblings=StudentGuardian::with(['students','students.admission_class','students.current_class','students.current_class_section'])->where('student_id', '!=', $id)->where('guardian_id', $student->guardian->student_guardian->id)->get();
            $exam=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
            ->where('session_id', $this->studentUtil->getActiveSession())
            ->where('student_id', $id)->get();
            $timetables=ClassTimeTable::with(['campuses','classes','section','subjects','teacher','periods'])
            ->where('class_id', $student->current_class_id)
            ->where('class_section_id', $student->current_class_section_id)
            ->orderBy('period_id')->get();
            $all_subjects=ClassSubject::allSubjectDropdown();
            if ($user->user_type =='guardian') {
                $child=StudentGuardian::forDropdown($user->hook_id);
                if (!empty($child[$id])) {
                    return view('students.profile')->with(compact('student', 'siblings', 'exam', 'timetables', 'all_subjects'));
                } else {
                    return redirect()->back();
                }
            }

            return view('students.profile')->with(compact('student', 'siblings', 'exam', 'timetables', 'all_subjects'));
        } else {
            return redirect()->back();
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
      $student_id = request()->input('student_id');

      $start_date = request()->start_date;
      $end_date =  request()->end_date;

      $student = Student::find($student_id);


      $ledger_details = $this->studentUtil->getLedgerDetails($student_id, $start_date, $end_date);

      if (request()->input('action') == 'pdf') {
          $for_pdf = true;
          $html = view('students.partials.fee_ledger')
           ->with(compact('ledger_details', 'student', 'for_pdf'))->render();
          $mpdf = $this->getMpdf();
          $mpdf->WriteHTML($html);
          $mpdf->Output();
      }

      return view('students.partials.fee_ledger')
           ->with(compact('ledger_details', 'student'));
  }

  public function getStudentAttendance()
  {
      $student_id = request()->input('student_id');
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
          $employe_attendance =Attendance::where('student_id', $student_id)->whereDate('clock_in_time', $date)->first();
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



  
  public function get_documents()
  {
      if (!auth()->user()->can('student_document.view')) {
          abort(403, 'Unauthorized action.');
      }
      if (request()->ajax()) {
          $student_id = request()->input('student_id');
          $student_document=StudentDocument::where('student_id', $student_id)->select(['type','filename', 'id']);
          return Datatables::of($student_document)
          ->addColumn(
              'action',
              function ($row) {
                  $html= '<div class="dropdown">
             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
             <ul class="dropdown-menu" style="">';
                  $document_name = !empty(explode("_", $row->filename, 2)[1]) ? explode("_", $row->filename, 2)[1] : $row->filename ;
                  if (auth()->user()->can('student_document.download')) {
                      $html .= '<li><a  class="dropdown-item  " href="' . url('uploads/document/' . $row->filename) .'" download="' . $document_name . '"><i class="fas fa-download" ></i> ' . __("english.download_document") . '</a></li>';
                  }
                  if (isFileImage($document_name)) {
                      $html .= '<li><a href="#" data-href="' . url('uploads/document/' . $row->filename) .'" class=" dropdown-item  view_uploaded_document"><i class="fas fa-image"></i> ' . __("english.view_document") . '</a></li>';
                  }
                  if (auth()->user()->can('student_document.delete')) {
                      $html.='<li><a class="dropdown-item btn-danger delete_document_destroy_button "href="#" data-href="' . action('StudentController@document_destroy', [$row->id]) . '"><i class="fas fa-trash"></i> ' . __("english.delete") . '</a></li>';
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
        if (!auth()->user()->can('student_document.create')) {
            abort(403, 'Unauthorized action.');
        }

        $student_id=$id;

        return view('students.document_create')->with(compact('student_id'));
    }
    public function document_post(Request $request)
    {
        if (!auth()->user()->can('student_document.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['student_id','type']);
            $filename=$this->studentUtil->uploadFile($request, 'document', 'document');
            StudentDocument::create([
                'student_id' => $input['student_id'],
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
        if (!auth()->user()->can('student_document.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $document = StudentDocument::findOrFail($id);
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


    public function bulkEdit(){
        $campuses=Campus::forDropdown();

        return view('students.bulk.edit')->with(compact('campuses'));     
    }
    public function getBulkEdit(Request $request){
        $input = $request->input();
        $system_settings_id = session()->get('user.system_settings_id');

        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $class_section_id=null;
        $students=Student::where('campus_id',$campus_id)
                ->where('current_class_id', $class_id)
              //  ->where('current_class_section_id', $class_section_id)
                ->where('status','active')->get();
       // dd($students);

        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        return view('students.bulk.edit_students')->with(compact('students','campuses','classes','sections','campus_id','class_id','class_section_id'));

    }

    public function postBulkEdit(Request $request){
        if (!auth()->user()->can('exam_mark_entry.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
           
            DB::beginTransaction();
        
            $input = $request->except('_token');
       
        foreach($request->input(['marks']) as $mark){
            $student=Student::find($mark['student_id']);
            $student_transport_fee=$this->studentUtil->num_uf($mark['student_transport_fee']);
            $student_tuition_fee=$this->studentUtil->num_uf($mark['student_tuition_fee']);
            if($student_transport_fee>0){  
            $student->student_transport_fee=$student_transport_fee;
            $student->is_transport=1;
            }
            $student->birth_date= $this->studentUtil->uf_date($mark['birth_date']);
            $student->student_tuition_fee=$student_tuition_fee;
            $student->mobile_no=$mark['mobile_no'];
            $student->std_permanent_address=$mark['std_permanent_address'];
            $student->father_name=$mark['father_name'];
            $student->save();
           

          
        }
           
        //dd(550);
        DB::commit();
        $output = ['success' => true,
        'msg' => __("english.added_success")
    ];
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
                'msg' => __("english.something_went_wrong")
            ];
    }

    return redirect('students/bulk-edit')->with('status', $output);

}


public function get_assessments()
  {
      if (!auth()->user()->can('student_assessment.view')) {
          abort(403, 'Unauthorized action.');
      }
      if (request()->ajax()) {
          $student_id = request()->input('student_id');
          $student_document=AssessmentStudent::where('student_id', $student_id)->select(['assessment_date', 'id']);
          return Datatables::of($student_document)
          ->addColumn(
              'action',
              function ($row) {
                  $html= '<div class="dropdown">
             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
             <ul class="dropdown-menu" style="">';
                  
                 
                  if (auth()->user()->can('student_document.delete')) {
                      $html.='<li><a class="dropdown-item btn-danger delete_document_destroy_button "href="#" data-href="' . action('StudentController@document_destroy', [$row->id]) . '"><i class="fas fa-trash"></i> ' . __("english.delete") . '</a></li>';
                  }
                  $html .= '</ul></div>';

                  return $html;
              }
          )

              ->removeColumn('id')
              ->rawColumns(['action','assessment_date'])
              ->make(true);
      }
  }

}