<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use Illuminate\Http\Request;
use App\Utils\StudentUtil;
use App\Utils\FeeTransactionUtil;
use App\Models\Classes;
use App\Models\Student;
use App\Models\ClassSection;
use App\Models\Campus;
use App\Models\Guardian;
use App\Models\StudentGuardian;
use App\Models\HumanRM\HrmEmployee;

use File;
use App\Models\HumanRM\HrmDesignation;

class ImportStudentsController extends Controller
{
    protected $studentUtil;
    protected $feeTransactionUtil;

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
            'struct_up' => 'bg-navy',
            'pass_out' => 'bg-danger',
            //'cancelled' => 'bg-red',
        ];
    }

    /**
     * Display import product screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

        $zip_loaded = extension_loaded('zip') ? true : false;

        //Check if zip extension it loaded or not.
        if ($zip_loaded === false) {
            $output = ['success' => 0,
                            'msg' => 'Please install/enable PHP Zip archive for import'
                        ];

            return view('import_students.index')
                ->with('notification', $output);
        } else {
            return view('import_students.index');
        }
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
        //Set maximum php execution time
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);
        if ($request->hasFile('products_csv')) {
            $file = $request->file('products_csv');

            $parsed_array = Excel::toArray([], $file);
            //dd($parsed_array);
            //Remove header row
            $imported_data = array_splice($parsed_array[0], 1);
            $user_id = $request->session()->get('user.id');
            $formatted_data = [];

            $is_valid = true;
            $error_msg = '';

            $total_rows = count($imported_data);
            //dd($total_rows);
            foreach ($imported_data as $key => $value) {
                //Check if any column is missing
                if (count($value) < 23) {
                    $is_valid =  false;
                    $error_msg = "Some of the columns are missing. Please, use latest CSV file template.";
                    break;
                }
                $row_no = $key + 1;
                $student_array = [];
                $student_array['system_settings_id'] = 1;
                $student_array['created_by'] = $user_id;
                $student_array['campus_id'] = 1;
              

                //dd($value);
                $old_roll_no = trim($value[0]);

                if (!empty($old_roll_no)) {
                    // $month_year_arr = explode('/', $value[0]);
                    // $year = $month_year_arr[1];
                    // if ($year=='17') {
                    //     $student_array['adm_session_id'] = 1;
                    //     $student_array['cur_session_id'] = 1;
                    // }elseif($year=='18'){
                    //     $student_array['adm_session_id'] = 2;
                    //     $student_array['cur_session_id'] = 2;
                    // }
                    // elseif($year=='19'){
                    //     $student_array['adm_session_id'] = 3;
                    //     $student_array['cur_session_id'] = 3;
                    // }elseif($year=='20'){
                    //     $student_array['adm_session_id'] = 4;
                    //     $student_array['cur_session_id'] = 4;
                    // }
                    // elseif($year=='21'){
                    //     $student_array['adm_session_id'] = 5;
                    //     $student_array['cur_session_id'] = 5;
                    // }
                    // elseif($year=='22'){
                    //     $student_array['adm_session_id'] = 6;
                    //     $student_array['cur_session_id'] = 6;
                    // }
                    // elseif($year=='23'){
                        $student_array['adm_session_id'] = 7;
                        $student_array['cur_session_id'] = 7;
                   // }
                    $student_array['old_roll_no'] = $old_roll_no;
                }
                $name = trim($value[1]);
                if (!empty($name)) {
                    $student_array['first_name'] = $name;
                }
                //admission_date
                if (!empty($value[2])) {
                    if (!empty($value[2])) {
                        $date_excel =\Carbon::parse($value[2])->format('Y-m-d');
                        $date=$date_excel;
                        $student_array['admission_date'] = $date;
                    }
                } else {
                    $student_array['admission_date'] = \Carbon::now()->format('Y-m-d');;
                }
                //Add Classes
                //Check if Classes exists else create new
                $adm_class_id = trim($value[3]);
                if (!empty($adm_class_id)) {
                    $adm_class = Classes::firstOrCreate(
                        ['campus_id' => 1, 'title' => $adm_class_id],
                        ['created_by' => $user_id,'system_settings_id'=>1]
                    );
                    $student_array['adm_class_id'] = $adm_class->id;
                }
                //Check if Classes exists else create new
                $current_class_id = trim($value[4]);
                if (!empty($current_class_id)) {
                    
                    $cur_class = Classes::firstOrCreate(
                        ['campus_id' => 1, 'title' => $current_class_id],
                        ['created_by' => $user_id,'system_settings_id'=>1]
                    );
                    
                    $student_array['current_class_id'] = $cur_class->id;
                }
                $adm_class_section_id = trim($value[5]);
                //dd($adm_class_section_id);
                if (!empty($adm_class_section_id)) {
                    // $adm_class_section = ClassSection::firstOrCreate(
                    //     ['campus_id' => 1, 'section_name' => $adm_class_section_id],
                    //     ['created_by' => $user_id,'system_settings_id'=>1,'class_id'=>$student_array['adm_class_id']]
                    // );
                    $adm_class_section = ClassSection::firstOrCreate(
                        ['campus_id' => 1, 'section_name' => $adm_class_section_id,'class_id'=>$student_array['adm_class_id']],
                        ['created_by' => $user_id,'system_settings_id'=>1]
                    );
                    $student_array['adm_class_section_id'] = $adm_class_section->id;
                    $student_array['current_class_section_id'] = $adm_class_section->id;
                }
                if (!empty($value[6])) {
                    
                    $student_array['father_name'] = $value[6];
                }
                if (!empty($value[7])) {
                    if (!empty($value[2])) {
                        $date_excel =\Carbon::parse($value[7])->format('Y-m-d');
                        $date=$date_excel;
                        $student_array['birth_date'] = $date;
                    }
                } else {
                    $student_array['birth_date'] = \Carbon::now()->format('Y-m-d');
                }
             //   dd(\Carbon::now()->format('Y-m-d'));
                $BirthPlace= trim($value[8]);
                if (!empty($BirthPlace)) {
                    $student_array['BirthPlace'] = $BirthPlace;
                }
                $mobile_no= trim($value[9]);
                if (!empty($mobile_no)) {
                    $student_array['mobile_no'] = '+92'.$mobile_no;
                }
                $std_current_address= trim($value[10]);
                if (!empty($std_current_address)) {
                    $student_array['std_current_address'] = ucwords($std_current_address);
                    $student_array['std_permanent_address'] = $std_current_address;
                }

                $religion= trim($value[11]);
                if (!empty($religion)) {
                    $student_array['religion'] = $religion;
                }
                $gender= trim($value[12]);
                if (!empty($gender)) {
                    $student_array['gender'] = strtolower($gender);
                }
                $previous_school_name= trim($value[13]);
                if (!empty($previous_school_name)) {
                    $student_array['previous_school_name'] = $previous_school_name;
                }
                $student_tuition_fee= trim($value[14]);
                if (!empty($student_tuition_fee)) {
                    $student_array['student_tuition_fee'] = $student_tuition_fee;
                }
                $student_transport_fee= trim($value[15]);
                if (!empty($student_transport_fee)) {
                    $student_array['student_transport_fee'] = $student_transport_fee;
                    $student_array['is_transport'] = 1;
                }
                $mother_tongue= trim($value[16]);
                if (!empty($mother_tongue)) {
                    $student_array['mother_tongue'] = $mother_tongue;
                }
                $cnic_no= trim($value[17]);
                if (!empty($cnic_no)) {
                    $student_array['cnic_no'] = $cnic_no;
                }
                $guardian_name= trim($value[18]);
                if (!empty($guardian_name)) {
                    $student_array['guardian_name'] = $guardian_name;
                } else {
                   // dd($student_array);
                    $student_array['guardian_name'] = $student_array['father_name'];
                }
                $guardian_relation= trim($value[19]);
                if (!empty($guardian_relation)) {
                    $student_array['guardian_relation'] = $guardian_relation;
                } else {
                    $student_array['guardian_relation'] = 'Father';
                }
                $guardian_occupation= trim($value[20]);
                if (!empty($guardian_occupation)) {
                    $student_array['guardian_occupation'] = $guardian_occupation;
                    $student_array['father_occupation'] = $guardian_occupation;

                } else {
                    $student_array['guardian_occupation'] = '';
                }
                $guardian_cnic= trim($value[21]);
                if (!empty($guardian_cnic)) {
                    $student_array['guardian_cnic'] = $guardian_cnic;
                    $student_array['father_cnic_no'] = $guardian_cnic;
                } else {
                    $student_array['guardian_cnic'] = '';
                }
                $guardian_phone= trim($value[22]);
                if (!empty($guardian_phone)) {
                    $student_array['guardian_phone'] = $guardian_phone;
                    $student_array['father_phone'] = $guardian_phone;
                } else {
                    $student_array['guardian_phone'] = '';
                }
              
                $opening_balance= trim($value[23]);
                if (!empty($opening_balance)) {
                    $student_array['opening_balance'] = $opening_balance;
                } else {
                    $student_array['opening_balance'] = 0;
                }
                // $status= trim($value[23]);
                // if ($status==0) {
                    //         $student_array['status'] = 'pass_out';
                    //     } else {
                    //         $student_array['status'] = 'active';
                    //     }

                //Assign to formatted array
                //dd($student_array);
                $formatted_data[] = $student_array;
            }
        }
      // dd($formatted_data);
        if (!$is_valid) {
            throw new \Exception($error_msg);
        }
        DB::beginTransaction();

        if (!empty($formatted_data)) {
            foreach ($formatted_data as $index => $student_data) {
                $opening_balance= $student_data['opening_balance'];
                $ref_admission_no=$this->studentUtil->setAndGetReferenceCount('admission_no', true, false);
                $admission_no=$this->studentUtil->generateReferenceNumber('admission', $ref_admission_no);
                $student_data['admission_no'] = $admission_no;
                $student_data['roll_no'] = $this->studentUtil->getRollNo($student_data['cur_session_id']);
                $guardian_data=[
                'guardian_name'=>$student_data['guardian_name'],
                'guardian_relation'=>$student_data['guardian_relation'],
                'guardian_occupation'=>$student_data['guardian_occupation'],
                'guardian_cnic'=>$student_data['guardian_cnic'],
                'guardian_phone'=>$student_data['guardian_phone'],
                'guardian_email'=>$student_data['roll_no'].'gu@gmail.com',
                ];
                if (isset($student_data['opening_balance'])) {
                    unset($student_data['opening_balance']);
                }
                if (isset($student_data['guardian_name'])) {
                    unset($student_data['guardian_name']);
                }
                if (isset($student_data['guardian_relation'])) {
                    unset($student_data['guardian_relation']);
                }
                if (isset($student_data['guardian_occupation'])) {
                    unset($student_data['guardian_occupation']);
                }
                if (isset($student_data['guardian_cnic'])) {
                    unset($student_data['guardian_cnic']);
                }
                if (isset($student_data['guardian_phone'])) {
                    unset($student_data['guardian_phone']);
                }
              
             //   dd($student_data);
                $student_data['email'] = $student_data['roll_no'].'@gmail.com';
                //dd($guardian_data);
                // //Create new product
                $student = Student::create($student_data);
                $guardian = Guardian::create($guardian_data);
                $studentGuardian = StudentGuardian::create([
                'student_id' => $student->id,
                'guardian_id' => $guardian->id,
                ]);
                //Add opening balance
                if (!empty($opening_balance)) {
                    if ($opening_balance>0) {
                        $this->studentUtil->createOpeningBalanceTransaction($student->system_settings_id, $student, $opening_balance, false);
                    }
                }
                $this->studentUtil->setAndGetReferenceCount('admission_no', false, true);
                $this->studentUtil->setAndGetRollNoCount('roll_no', false, true, $student->adm_session_id);
            }
        }
        $output = ['success' => 1,
                        'msg' => __('english.file_imported_successfully')
        ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' => $e->getMessage()
                        ];
            return redirect('import-students')->with('notification', $output);
        }

        return redirect('import-students')->with('status', $output);
    }


    public function StudentImage(Request $request)
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        //Set maximum php execution time
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);
        if ($request->hasFile('products_csv')) {
            $file = $request->file('products_csv');

            $parsed_array = Excel::toArray([], $file);
            //Remove header row
            $imported_data = array_splice($parsed_array[0], 1);
            foreach ($imported_data as $key => $value) {
                // dd('uploads/pdf/image/'.trim($value[0]).'.png');
                if (File::exists(public_path('uploads/pdf/image/'.trim($value[0]).'.png'))) {
                    $student_data=Student::where('old_roll_no', $value[0])->first();
                    if (!empty($student_data)) {
                        if ($student_data->old_roll_no==trim($value[0])) {
                            $student_data->student_image=$student_data->old_roll_no.'.png';
                            $student_data->save();
                        } else {
                            File::delete(public_path('uploads/pdf/image/'.trim($value[0]).'.png'));
                        }
                    } else {
                        File::delete(public_path('uploads/pdf/image/'.trim($value[0]).'.png'));
                    }
                }

                // dd($imported_data);
            }
            dd(5);
            // $user_id = $request->session()->get('user.id');
            // $formatted_data = [];

            // $is_valid = true;
            // $error_msg = '';

            // $total_rows = count($imported_data);
            // return view('import_students.img')->with(compact('imported_data'));
        }
    }

    public function employeeImport(Request $request)
    {
        // $emp = HrmEmployee::get();
        // foreach ($emp as $ep){
        //     $employee = HrmEmployee::findOrFail($ep->id);
        //     $employeeID = explode("-",$employee->employeeID);
        //     $employee->employeeID=$employeeID[0].'-'.$employeeID[2];
        //     $employee->save();
        // }
        // dd($emp);
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            //Set maximum php execution time
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', -1);
            if ($request->hasFile('products_csv')) {
                $file = $request->file('products_csv');

                $parsed_array = Excel::toArray([], $file);
                //Remove header row
                $imported_data = array_splice($parsed_array[0], 1);
                $user_id = $request->session()->get('user.id');
                $formatted_data = [];

                $is_valid = true;
                $error_msg = '';

                $total_rows = count($imported_data);
                DB::beginTransaction();
                ;
                foreach ($imported_data as $key => $value) {
                    //Check if any column is missing
                    if (count($value) < 12) {
                        $is_valid =  false;
                        $error_msg = "Some of the columns are missing. Please, use latest CSV file template.";
                        break;
                    }
                    $row_no = $key + 1;
                    $student_array = [];
                    $student_array['created_by'] = $user_id;
                    $student_array['campus_id'] = 1;

                    //joining_date
                    if (!empty($value[0])) {
                        $date_excel = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[0])->format('Y-m-d');
                        $date=$date_excel;
                        $student_array['joining_date'] = $date;
                        $student_array['birth_date'] = $date;
                    }
                    // //birth_date
                    // if (!empty($value[1])) {
                    //     if($value[1] != '0' || $value[1] != 0){
                    //         $birth_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[1])->format('Y/m/d');
                    //         $student_array['birth_date'] = $birth_date;
                    //     }

                    // }
                    //old_EmpID
                    $old_EmpID = trim($value[2]);
                    if (!empty($old_EmpID)) {
                        $student_array['old_EmpID'] = $old_EmpID;
                    }
                    $name = trim($value[3]);
                    if (!empty($name)) {
                        $student_array['first_name'] = $name;
                    }
                    $father_name = trim($value[4]);
                    if (!empty($father_name)) {
                        $student_array['father_name'] = $father_name;
                    }
                    $gender = trim($value[5]);
                    if (!empty($gender)) {
                        $student_array['gender'] = 'male';
                    }
                    $M_Status = trim($value[6]);
                    if (!empty($M_Status)) {
                        $student_array['M_Status'] = $M_Status;
                    }
                    $mobile_no= trim($value[7]);
                    if (!empty($mobile_no)) {
                        $student_array['mobile_no'] = $mobile_no;
                    }
                    $current_address= trim($value[8]);
                    if (!empty($current_address)) {
                        $student_array['current_address'] = ucwords($current_address);
                        $student_array['permanent_address'] = $current_address;
                    }
                    $cnic_no= trim($value[9]);
                    if (!empty($cnic_no)) {
                        $student_array['cnic_no'] = ucwords($cnic_no);
                    }
                    $basic_salary= trim($value[10]);
                    if (!empty($basic_salary)) {
                        $student_array['basic_salary'] = ucwords($basic_salary);
                    }
                    //Add hrm_designations
                    //Check if hrm_designations exists else create new
                    $hrm_designations = trim($value[11]);
                    if (!empty($hrm_designations)) {
                        $designation = HrmDesignation::firstOrCreate(
                            ['designation' => $hrm_designations],
                            ['created_by' => $user_id]
                        );
                        $student_array['designation_id'] = $designation->id;
                    }



                    //Assign to formatted array
                    $formatted_data[] = $student_array;
                }
            }
            if (!$is_valid) {
                throw new \Exception($error_msg);
            }

            if (!empty($formatted_data)) {
                foreach ($formatted_data as $index => $student_data) {
                    $ref_employeeID=$this->studentUtil->setAndGetReferenceCount('employee_no', true, false);
                    $employeeID=$this->studentUtil->generateReferenceNumber('employee', $ref_employeeID);
                    $student_data['employeeID'] = $employeeID;
                    $student_data['email'] = $student_data['employeeID'].'@gmail.com';
                    $student_array['gender'] = 'male';
                    // //Create new Employee
                    $student = HrmEmployee::create($student_data);
                    $this->studentUtil->setAndGetReferenceCount('employee_no', false, true);
                }
            }
            $output = ['success' => 1,
                            'msg' => __('english.file_imported_successfully')
        ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' => $e->getMessage()
                        ];
            return redirect('import-students')->with('notification', $output);
        }

        return redirect('import-students')->with('status', $output);
    }
}
