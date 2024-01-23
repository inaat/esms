<?php

namespace App\Utils;

use App\Models\FeeTransaction;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\StudentGuardian;
use App\Models\Attendance;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Certificate\WithdrawalRegister;
use App\Utils\FeeTransactionUtil;
use App\Models\FeeTransactionPayment;

class StudentUtil extends Util
{
    protected $feeTransactionUtil;

    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(FeeTransactionUtil $feeTransactionUtil)
    {
        $this->feeTransactionUtil = $feeTransactionUtil;

    }
    public function StudentCreate($request, $guardian_link_id = null)
    {
        if ($guardian_link_id == null) {
            $student_input = $request->only([
                'campus_id',
                'adm_session_id',
                'admission_no',
                'admission_date',
                'roll_no',
                'adm_class_id',
                'adm_class_section_id',
                'first_name',
                'last_name',
                'gender',
                'birth_date',
                'category_id',
                'domicile_id',
                'religion',
                'mobile_no',
                'email',
                'cnic_no',
                'blood_group',
                'nationality',
                'mother_tongue',
                'medical_history',
                'father_name',
                'father_phone',
                'father_occupation',
                'father_cnic_no',
                'mother_name',
                'mother_phone',
                'mother_occupation',
                'mother_cnic_no',
                'country_id',
                'province_id',
                'district_id',
                'city_id',
                'region_id',
                'std_current_address',
                'std_permanent_address',
                'student_tuition_fee',
                'is_transport',
                'student_transport_fee',
                'vehicle_id',
                'remark',
                'previous_school_name',
                'last_grade',
                'student_image',
                'opening_balance'
            ]);
             
            if (!empty($student_input['student_image'])) {
                $student_image = $this->uploadFile($request, 'student_image', 'student_image', 'image', $student_input['roll_no']);
                $student_input['student_image'] = $student_image;
            }
            $user_id = $request->session()->get('user.id');
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $student_input['system_settings_id'] = $system_settings_id;
            $student_input['created_by'] = $user_id;
            $student_input['admission_date'] = $this->uf_date($student_input['admission_date']);
            $student_input['birth_date'] = $this->uf_date($student_input['birth_date']);
            $student_input['current_class_id'] = $student_input['adm_class_id'];
            $student_input['current_class_section_id'] = $student_input['adm_class_section_id'];
            $student_input['cur_session_id'] = $student_input['adm_session_id'];

            $student_input['opening_balance'] = $this->num_uf($student_input['opening_balance']);
            $student_input['student_transport_fee'] = $this->num_uf($student_input['student_transport_fee']);
            $student_input['student_tuition_fee'] = $this->num_uf($student_input['student_tuition_fee']);

            $opening_balance = isset($student_input['opening_balance']) ? $student_input['opening_balance'] : 0;
            if (isset($student_input['opening_balance'])) {
                unset($student_input['opening_balance']);
            }
            $student = Student::create($student_input);
            //Add opening balance
            if (!empty($opening_balance)) {
                $this->createOpeningBalanceTransaction($student->system_settings_id, $student, $opening_balance, false);
            }
            $user = $this->studentCreateUpdateLogin($student, 'student', $student->id);
            $student->user_id = $user->id;
            $student->save();
            $this->createWithdrawRegister($student);
            
            $guardian = Guardian::create($request['guardian']);
            $CheckStudentGuardian = StudentGuardian::where('student_id',$student->id)->first();
            if(!empty($CheckStudentGuardian)){
               $CheckStudentGuardian->delete() ;
            }
            $studentGuardian = StudentGuardian::create([
                'student_id' => $student->id,
                'guardian_id' => $guardian->id,
            ]);
           
            $guardian_login = $this->guardianCreateUpdateLogin($guardian, 'guardian', $guardian->id);
        } else {
            $student_input = $request->only([
                'campus_id',
                'adm_session_id',
                'admission_no',
                'admission_date',
                'roll_no',
                'adm_class_id',
                'adm_class_section_id',
                'first_name',
                'last_name',
                'gender',
                'birth_date',
                'category_id',
                'domicile_id',
                'religion',
                'mobile_no',
                'email',
                'cnic_no',
                'blood_group',
                'nationality',
                'mother_tongue',
                'medical_history',
                'is_transport',
                'student_tuition_fee',
                'student_transport_fee',
                'vehicle_id',
                'remark',
                'previous_school_name',
                'last_grade',
                'student_image',
                'opening_balance'
            ]);
            if (!empty($student_input['student_image'])) {
                $student_image = $this->uploadFile($request, 'student_image', 'student_image', 'image', $student_input['roll_no']);
                $student_input['student_image'] = $student_image;
            }
            $user_id = $request->session()->get('user.id');
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $student_input['system_settings_id'] = $system_settings_id;
            $student_input['created_by'] = $user_id;
            $student_input['admission_date'] = $this->uf_date($student_input['admission_date']);
            $student_input['birth_date'] = $this->uf_date($student_input['birth_date']);
            $student_input['current_class_id'] = $student_input['adm_class_id'];
            $student_input['current_class_section_id'] = $student_input['adm_class_section_id'];
            $student_input['cur_session_id'] = $student_input['adm_session_id'];
            $sibling_details = Student::find($request['sibling_id']);
            $student_input['father_name'] = $sibling_details->father_name;
            $student_input['father_phone'] = $sibling_details->father_phone;
            $student_input['father_occupation'] = $sibling_details->father_occupation;
            $student_input['father_cnic_no'] = $sibling_details->father_cnic_no;
            $student_input['mother_name'] = $sibling_details->mother_name;
            $student_input['mother_phone'] = $sibling_details->mother_phone;
            $student_input['mother_occupation'] = $sibling_details->mother_occupation;
            $student_input['mother_cnic_no'] = $sibling_details->mother_cnic_no;
            $student_input['country_id'] = $sibling_details->country_id;
            $student_input['province_id'] = $sibling_details->province_id;
            $student_input['district_id'] = $sibling_details->district_id;
            $student_input['city_id'] = $sibling_details->city_id;
            $student_input['region_id'] = $sibling_details->region_id;
            $student_input['std_current_address'] = $sibling_details->std_current_address;
            $student_input['std_permanent_address'] = $sibling_details->std_permanent_address;

            $student_input['opening_balance'] = $this->num_uf($student_input['opening_balance']);
            $student_input['student_transport_fee'] = $this->num_uf($student_input['student_transport_fee']);
            $student_input['student_tuition_fee'] = $this->num_uf($student_input['student_tuition_fee']);

            $opening_balance = isset($student_input['opening_balance']) ? $student_input['opening_balance'] : 0;
            if (isset($student_input['opening_balance'])) {
                unset($student_input['opening_balance']);
            }
            $student = Student::create($student_input);
            //Add opening balance
            if (!empty($opening_balance)) {
                $this->createOpeningBalanceTransaction($student->system_settings_id, $student, $opening_balance, false);
            }
            $user = $this->studentCreateUpdateLogin($student, 'student', $student->id);
            $student->user_id = $user->id;
            $student->save();
               $CheckStudentGuardian = StudentGuardian::where('student_id',$student->id)->first();
            if(!empty($CheckStudentGuardian)){
               $CheckStudentGuardian->delete() ;
            }
            $studentGuardian = StudentGuardian::create([
                'student_id' => $student->id,
                'guardian_id' => $guardian_link_id,
            ]);
        }
    }


    /**
     * Creates a new opening balance transaction for a contact
     *
     * @param  int $system_settings_id
     * @param  int $student_id
     * @param  int $amount
     *
     * @return void
     */
    public function createOpeningBalanceTransaction($system_settings_id, $student, $amount, $uf_data = true)
    {
        $final_amount = $uf_data ? $this->num_uf($amount) : $amount;
        $now = \Carbon::now();

        $ob_data = [
            'system_settings_id' => $system_settings_id,
            'campus_id' => $student->campus_id,
            'type' => 'opening_balance',
            'status' => 'final',
            'payment_status' => 'due',
            'session_id' => $student->adm_session_id,
            'class_id' => $student->adm_class_id,
            'class_section_id' => $student->adm_class_section_id,
            'month' => $now->month,
            'student_id' => $student->id,
            'transaction_date' => $now,
            'before_discount_total' => $final_amount,
            'final_total' => $final_amount,
            'created_by' => $student->created_by,
        ];
        //Update reference count
        $ob_ref_count = $this->setAndGetReferenceCount('opening_balance', false, true);
        //Generate reference number
        $ob_data['ref_no'] = $this->generateReferenceNumber('opening_balance', $ob_ref_count, $system_settings_id);
        //Create opening balance transaction
        FeeTransaction::create($ob_data);
    }
    public function createOtherBalanceTransaction($system_settings_id, $student, $amount, $uf_data = true)
    {
        $final_amount = $uf_data ? $this->num_uf($amount) : $amount;
        $now = \Carbon::now();

        $ob_data = [
            'system_settings_id' => $system_settings_id,
            'campus_id' => $student->campus_id,
            'type' => 'other_fee',
            'status' => 'final',
            'payment_status' => 'due',
            'session_id' => $student->adm_session_id,
            'class_id' => $student->adm_class_id,
            'class_section_id' => $student->adm_class_section_id,
            'month' => $now->month,
            'student_id' => $student->id,
            'transaction_date' => $now,
            'before_discount_total' => $final_amount,
            'final_total' => $final_amount,
            'created_by' => $student->created_by,
        ];
        //Update reference count
        $ob_ref_count = $this->setAndGetReferenceCount('challan', false, true);
        //Generate reference number
        $ob_data['ref_no'] = $this->generateReferenceNumber('challan', $ob_ref_count, $system_settings_id);
        //Create opening balance transaction
        FeeTransaction::create($ob_data);
    }

    public function updateStudent($request, $student_id, $guardian_link_id = null)
    {
        //'adm_class_id','adm_class_section_id',
        $student_input = $request->only([
            'campus_id',
            'adm_session_id',
            'admission_no',
            'admission_date',
            'roll_no',
            'current_class_id',
            'current_class_section_id',
            'first_name',
            'last_name',
            'gender',
            'birth_date',
            'category_id',
            'domicile_id',
            'religion',
            'mobile_no',
            'email',
            'cnic_no',
            'blood_group',
            'nationality',
            'mother_tongue',
            'medical_history',
            'father_name',
            'father_phone',
            'father_occupation',
            'father_cnic_no',
            'mother_name',
            'mother_phone',
            'mother_occupation',
            'mother_cnic_no',
            'country_id',
            'province_id',
            'district_id',
            'city_id',
            'region_id',
            'std_current_address',
            'std_permanent_address',
            'is_transport',
            'student_tuition_fee',
            'student_transport_fee',
            'vehicle_id',
            'remark',
            'previous_school_name',
            'last_grade',
            'student_image',
            'opening_balance',
            'old_roll_no',
            'adm_class_id',
            'adm_class_section_id'
        ]);
        if (!empty($student_input['student_image'])) {
            $student_image = $this->uploadFile($request, 'student_image', 'student_image', 'image', $student_input['roll_no']);
            $student_input['student_image'] = $student_image;
        }
        $user_id = $request->session()->get('user.id');
        $system_settings_id = $request->session()->get('user.system_settings_id');
        $student_input['system_settings_id'] = $system_settings_id;
        $student_input['created_by'] = $user_id;
        $student_input['admission_date'] = $this->uf_date($student_input['admission_date']);
        $student_input['birth_date'] = $this->uf_date($student_input['birth_date']);
        $student_input['student_transport_fee'] = $this->num_uf($student_input['student_transport_fee']);
        $student_input['student_tuition_fee'] = $this->num_uf($student_input['student_tuition_fee']);
        $student_input['opening_balance'] = $this->num_uf($student_input['opening_balance']);

        //Get opening balance if exists
        $ob_transaction = FeeTransaction::where('student_id', $student_id)
            ->where('type', 'opening_balance')->first();
        $opening_balance = isset($student_input['opening_balance']) ? $student_input['opening_balance'] : 0;
        if (isset($student_input['opening_balance'])) {
            unset($student_input['opening_balance']);
        }

        $student = Student::find($student_id);
        $student->fill($student_input);
        $student->save();
        $user = $this->studentCreateUpdateLogin($student, 'student', $student->id);
        $student->user_id = $user->id;
        $student->save();
        $this->createWithdrawRegister($student);

        if ($guardian_link_id == null) {
            $studentGuardian = StudentGuardian::where('student_id', $student_id)->first();
            $guardian = Guardian::where('id', $studentGuardian->guardian_id)->first();
            $guardian->fill($request['guardian']);
            $guardian->save();
            $guardian_login = $this->guardianCreateUpdateLogin($guardian, 'guardian', $guardian->id);

        } else {
            $this->studentReplace($student_id,$guardian_link_id);
            $stg = StudentGuardian::where('guardian_id', $guardian_link_id)->count();
            if ($stg > 1 || empty($studentGuardian)) {
                $studentGuardianUpdate = StudentGuardian::where('student_id', $student_id)->first();
                if(!empty($studentGuardianUpdate)){
                $studentGuardianUpdate->update([
                    'student_id' => $student->id,
                    'guardian_id' => $guardian_link_id,
                ]);
            }else{
                $studentGuardianCreate = StudentGuardian::Create([
                    'student_id' => $student->id,
                    'guardian_id' => $guardian_link_id,
                ]);
            }
                
            } else {
                $studentGuardian = StudentGuardian::where('student_id', $student_id)->first();
                $guardian = Guardian::where('id', $studentGuardian->guardian_id)->first();
                $guardian->delete();
                $login = User::where('hook_id', $studentGuardian->guardian_id)->where('user_type', 'guardian')->first();
                if (!empty($login)) {
                    $login->delete();
                }

                $studentGuardianCreate = StudentGuardian::Create([
                    'student_id' => $student->id,
                    'guardian_id' => $guardian_link_id,
                ]);
                $std_guardian = Guardian::where('id', $guardian_link_id)->first();

                $guardian_login = $this->guardianCreateUpdateLogin($std_guardian, 'guardian', $std_guardian->id);
            }
        }


        //dd($ob_transaction);
        if (!empty($ob_transaction)) {
            if (!empty($ob_transaction->payment_status == 'due')) {
                $ob_transaction->before_discount_total = $opening_balance;

                $ob_transaction->final_total = $opening_balance - $ob_transaction->discount_amount;
                $ob_transaction->save();

                //Update opening balance payment status
                $this->feeTransactionUtil->updatePaymentStatus($ob_transaction->id, $ob_transaction->final_total);
            }
        } else {
            //Add opening balance
            if (!empty($opening_balance)) {
                $this->createOpeningBalanceTransaction($student->system_settings_id, $student, $opening_balance, false);
            }
        }
        $output = [
            'success' => true,
            'msg' => __("english.updated_success")
        ];
        return $output;
    }

    public function removeSibling($guardian, $student_id)
    {
        $guardian = Guardian::create($guardian);
        $studentGuardian = StudentGuardian::where('student_id', $student_id)->first();
        $studentGuardian->guardian_id = $guardian->id;
        $studentGuardian->save();
    }
    public function getStudentList($system_settings_id, $class_id, $class_section_id, $status = null, $already_exists = null)
    {
        $query = Student::leftJoin('campuses', 'students.campus_id', '=', 'campuses.id')
            ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
            ->where('students.system_settings_id', $system_settings_id)
            ->where('students.current_class_id', $class_id)
            ->select(
                'campuses.campus_name',
                'c-class.title as current_class',
                'students.father_name',
                'students.roll_no',
                'students.admission_no',
                'students.gender',
                'students.id as id',
                'students.student_image',
                'students.admission_date',
                DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name")
            );
        if (!empty($already_exists)) {
            $query->whereNotIn('students.id', $already_exists);
        }
        if (!empty($class_section_id)) {
            $query->where('students.current_class_section_id', $class_section_id);
        }
        if (!empty($status)) {
            $query->where('students.status', $status);
        }

        return $query->get();
    }
    public function getStudentDue($student_id)
    {
        $query = Student::where('students.id', $student_id)
            ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
            ->leftJoin('class_sections as c-section', 'students.current_class_section_id', '=', 'c-section.id')
            ->join('fee_transactions AS t', 'students.id', '=', 't.student_id');

        $query->select(
            DB::raw("SUM(IF(t.type = 'admission_fee' AND t.status = 'final', final_total, 0)) as total_admission_fee"),
            DB::raw("SUM(IF(t.type = 'admission_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as total_admission_fee_paid"),
        );
        $query->addSelect(
            DB::raw("SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)) as total_fee"),
            DB::raw("SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as total_fee_paid"),
        );
        $query->addSelect(
            DB::raw("SUM(IF(t.type = 'other_fee' AND t.status = 'final', final_total, 0)) as total_other_fee"),
            DB::raw("SUM(IF(t.type = 'other_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as total_other_fee_paid"),
        );
        $query->addSelect(
            DB::raw("SUM(IF(t.type = 'transport_fee' AND t.status = 'final', final_total, 0)) as total_transport_fee"),
            DB::raw("SUM(IF(t.type = 'transport_fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as total_transport_fee_paid"),
        );
        //Query for opening balance details
        $query->addSelect(
            DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
            DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(amount) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)) as opening_balance_paid"),
            DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
            'c-class.title as current_class',
            'c-section.section_name as current_class_section',
            'students.roll_no',
            'students.campus_id',
            'students.old_roll_no',
            'students.birth_date',
            'students.father_name',
            'students.mobile_no',
            'students.birth_date',
            'students.std_current_address',
            'students.advance_amount',
            'students.id as student_id',
            'students.student_image',
            'students.first_name',
            'students.last_name',
            'students.student_tuition_fee',
            'students.student_transport_fee',
            'students.is_transport',
        );
        $query->addSelect([
            DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'admission_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'other_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'transport_fee', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'transport_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_due"),
        ]);
        $query->addSelect([
            DB::raw("COALESCE(SUM(IF(t.type = 'fee' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'admission_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'other_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'transport_fee', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM fee_transaction_payments WHERE fee_transaction_payments.fee_transaction_id=t.id), 0)),0) as total_paid"),
        ]);
        $student_details = $query->first();
        return $student_details;
    }
    public function getActiveStudents($campus_id)
    {
        $query = Student::where('students.status', 'active');
        //Filter by the campus
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("COUNT(*) as total_students")
        );
        $student_count = $query->first();
        return $student_count->total_students;
    }
    public function getInactiveStudents($campus_id)
    {
        $query = Student::where('students.status', 'inactive');
        //Filter by the campus
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("COUNT(*) as total_students")
        );
        $student_count = $query->first();
        return $student_count->total_students;
    }
    public function getPassOutStudents($campus_id)
    {
        $query = Student::where('students.status', 'pass_out');
        //Filter by the campus
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("COUNT(*) as total_students")
        );
        $student_count = $query->first();
        return $student_count->total_students;
    }
    public function getStruckUpStudents($campus_id)
    {
        $query = Student::where('students.status', 'struck_up');
        //Filter by the campus
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("COUNT(*) as total_students")
        );
        $student_count = $query->first();
        return $student_count->total_students;
    }
    public function getTookSLCStudents($campus_id)
    {
        $query = Student::where('students.status', 'took_slc');
        //Filter by the campus
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("COUNT(*) as total_students")
        );
        $student_count = $query->first();
        return $student_count->total_students;
    }

    public function getStudentGender($campus_id)
    {
        $query = Student::where('students.status', 'active');
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("100*sum(case when gender = 'male' then 1 else 0 end)/count(*) as male_perc"),
            DB::raw("100*sum(case when gender = 'female' then 1 else 0 end)/count(*) as female_perc"),
            DB::raw("100*sum(case when gender = 'other' then 1 else 0 end)/count(*) as other_perc")
        );

        return $query->first();
    }
    public function createWithdrawRegister($student)
    {
        $check_student = WithdrawalRegister::where('student_id', $student->id)->first();
        if (empty($check_student)) {
            $data = [
                'adm_session_id' => $student->adm_session_id,
                'student_id' => $student->id,
                'campus_id' => $student->campus_id,
                'admission_class_id' => $student->adm_class_id

            ];
            WithdrawalRegister::create($data);
        }

        return true;
    }


    /**
     * Function to get ledger details
     *
     */
    public function getLedgerDetails($student_id, $start, $end)
    {
        //Get sum of totals before start date
        $previous_transaction_sums = $this->__previous_transactionQuery($student_id, $start, $end);
        // dd($previous_transaction_sums);
        // //Get payment totals before start date
        $prev_payments = $this->__previous_paymentQuery($student_id, $start, $end);


        $prev_total_fee_paid = $prev_payments;
        $total_prev_invoice = $previous_transaction_sums;
        $total_prev_paid = $prev_total_fee_paid;
        $beginning_balance = $total_prev_invoice - $total_prev_paid;
        // $contact = Contact::find($student_id);

        // //Get transaction totals between dates
        $transactions = $this->__transactionQuery($student_id, $start, $end)
            ->get();
        $ledger = [];


        foreach ($transactions as $transaction) {
            $ledger[] = [
                'date' => $transaction->transaction_date,
                'ref_no' => $transaction->voucher_no,
                'type' => __('english.months.' . $transaction->month),

                'payment_status' => __('english.' . $transaction->payment_status),
                'total' => '',
                'payment_method' => '',
                'debit' => $transaction->final_total,
                'credit' => '',
                'others' => ''
            ];
        }
        //dd($ledger);
        $fee_sum = $transactions->sum('final_total');
        //Get payment totals between dates
        $payments = $this->__paymentQuery($student_id, $start, $end)
            ->select('fee_transaction_payments.*', 't.type as transaction_type', 't.ref_no')
            ->get();

        $paymentTypes = $this->payment_types();

        foreach ($payments as $payment) {
            $ref_no = $payment->ref_no;
            $note = $payment->note;
            if (!empty($ref_no)) {
                $note .= '<small>' . __('account.payment_for') . ': ' . $ref_no . '</small>';
            }



            $ledger[] = [
                'date' => $payment->paid_on,
                'ref_no' => $payment->payment_ref_no,
                'type' => '',

                'payment_status' => '',
                'total' => '',
                'payment_method' => !empty($paymentTypes[$payment->method]) ? $paymentTypes[$payment->method] : '',
                'payment_method_key' => $payment->method,
                'debit' => '',
                'credit' => $payment->amount,
                'others' => $note
            ];
        }


        $total_fee_paid = $payments->where('is_return', 0)->sum('amount');


        $start_date = $this->format_date($start);
        $end_date = $this->format_date($end);

        $total_fee = $fee_sum;


        $total_paid = $total_fee_paid;
        $curr_due = ($total_fee - $total_paid) + $beginning_balance;

        // //Sort by date
        if (!empty($ledger)) {
            usort($ledger, function ($a, $b) {
                $t1 = strtotime($a['date']);
                $t2 = strtotime($b['date']);
                return $t1 - $t2;
            });
        }
        $total_opening_bal = $beginning_balance;
        //Add Beginning balance & openining balance to ledger
        $ledger = array_merge([
            [
                'date' => $start,
                'ref_no' => '',
                'type' => __('english.opening_balance'),

                'payment_status' => '',
                'total' => '',
                'payment_method' => '',
                'debit' => abs($total_opening_bal),
                'credit' => '',
                'others' => ''
            ]
        ], $ledger);

        $bal = 0;
        foreach ($ledger as $key => $val) {
            $credit = !empty($val['credit']) ? $val['credit'] : 0;
            $debit = !empty($val['debit']) ? $val['debit'] : 0;

            $bal += ($credit - $debit);
            $balance = $this->num_f(abs($bal));

            if ($bal < 0) {
                $balance .= ' ' . __('english.dr');
            } else if ($bal > 0) {
                $balance .= ' ' . __('english.cr');
            }

            $ledger[$key]['balance'] = $balance;
        }



        $output = [
            'ledger' => $ledger,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_fee' => $total_fee,
            'beginning_balance' => $beginning_balance,
            'balance_due' => $curr_due,
            'total_paid' => $total_paid
        ];
        //dd($output);
        return $output;
    }
    /**
     * Query to get transaction totals for a customer
     *
     */
    private function __transactionQuery($student_id, $start, $end = null)
    {
        $query = FeeTransaction::where('fee_transactions.student_id', $student_id);

        if (!empty($start) && !empty($end)) {
            $query->whereDate(
                'fee_transactions.transaction_date',
                '>=',
                $start
            )
                ->whereDate('fee_transactions.transaction_date', '<=', $end)->get();
        }

        if (!empty($start) && empty($end)) {

            $query->whereDate('fee_transactions.transaction_date', '<', $start);

        }

        return $query;
    }
    private function __previous_transactionQuery($student_id, $start, $end = null)
    {
        $less_than = FeeTransaction::where('fee_transactions.student_id', $student_id);
        if (!empty($start)) {

            $less_than->whereDate('fee_transactions.transaction_date', '<', $start);

        }
        $less_than->select(
            DB::raw("SUM(final_total) as total_fee"),
        );
        $greater_than = FeeTransaction::where('fee_transactions.student_id', $student_id);
        if (!empty($end)) {

            $greater_than->whereDate('fee_transactions.transaction_date', '>', $end);

        }
        $greater_than->select(
            DB::raw("SUM(final_total) as total_fee"),
        );


        return $greater_than->first()->total_fee + $less_than->first()->total_fee;
    }

    /**
     * Query to get payment details for a customer
     *
     */
    private function __paymentQuery($student_id, $start, $end = null)
    {
        $query = FeeTransactionPayment::leftJoin(
            'fee_transactions as t',
            'fee_transaction_payments.fee_transaction_id',
            '=',
            't.id'
        )
            ->leftJoin('campuses as campus', 't.campus_id', '=', 'campus.id')
            ->where('fee_transaction_payments.payment_for', $student_id)
            //->whereNotNull('transaction_payments.transaction_id');
            ->whereNull('fee_transaction_payments.parent_id');

        if (!empty($start) && !empty($end)) {
            $query->whereDate('paid_on', '>=', $start)
                ->whereDate('paid_on', '<=', $end);
        }

        if (!empty($start) && empty($end)) {
            $query->whereDate('paid_on', '<', $start);
        }

        return $query;
    }

    private function __previous_paymentQuery($student_id, $start, $end = null)
    {
        $less_than = FeeTransactionPayment::leftJoin(
            'fee_transactions as t',
            'fee_transaction_payments.fee_transaction_id',
            '=',
            't.id'
        )
            ->leftJoin('campuses as campus', 't.campus_id', '=', 'campus.id')
            ->where('fee_transaction_payments.payment_for', $student_id)
            //->whereNotNull('transaction_payments.transaction_id');
            ->whereNull('fee_transaction_payments.parent_id');

        if (!empty($start)) {
            $less_than->whereDate('paid_on', '<', $start);
        }
        $less_than->select('fee_transaction_payments.*', 'campus.campus_name as location_name', 't.type as transaction_type')
            ->get();
        $less_than_amount = $less_than->where('is_return', 0)->sum('amount');

        $greater_than = FeeTransactionPayment::leftJoin(
            'fee_transactions as t',
            'fee_transaction_payments.fee_transaction_id',
            '=',
            't.id'
        )
            ->leftJoin('campuses as campus', 't.campus_id', '=', 'campus.id')
            ->where('fee_transaction_payments.payment_for', $student_id)
            //->whereNotNull('transaction_payments.transaction_id');
            ->whereNull('fee_transaction_payments.parent_id');

        if (!empty($end)) {
            $greater_than->whereDate('paid_on', '>', $end);
        }
        $greater_than->select('fee_transaction_payments.*', 'campus.campus_name as location_name', 't.type as transaction_type')
            ->get();
        $greater_than_amount = $greater_than->where('is_return', 0)->sum('amount');

        return $less_than_amount + $greater_than_amount;
    }




    public function getStudentTotalAttendances($campus_id, $type, $start, $end = null)
    {

        $query = Attendance::leftJoin(
            'students as em',
            'attendances.student_id',
            '=',
            'em.id'
        )->where('attendances.type', $type);

        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('em.campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('em.campus_id', $campus_id);
        }
        if (!empty($start) && !empty($end)) {
            $query->whereDate('attendances.clock_in_time', '>=', $start)
                ->whereDate('attendances.clock_in_time', '<=', $end);
        }

        if (!empty($start) && empty($end)) {
            $query->whereDate('attendances.clock_in_time', '<', $start);
        }
        $query->select(
            DB::raw("COUNT(*) as total_attendance")
        );

        $attendance_count = $query->first();
        return $attendance_count->total_attendance;

    }


 public function studentReplace($student_id,$guardian_link_id){
        $siblings=StudentGuardian::with(['student_guardian','students','students.current_class','students.current_class_section'])->where('guardian_id', $guardian_link_id)->first();
        $student = Student::find($student_id);
        $student->father_name=$siblings->students->father_name;
        $student->father_phone=$siblings->students->father_phone;
        $student->father_occupation=$siblings->students->father_occupation;
        $student->father_cnic_no=$siblings->students->father_cnic_no;
        $student->mother_name=$siblings->students->mother_name;
        $student->mother_phone=$siblings->students->mother_phone;
        $student->mother_occupation=$siblings->students->mother_occupation;
        $student->mother_cnic_no=$siblings->students->mother_cnic_no;
        $student->country_id=$siblings->students->country_id;
        $student->province_id=$siblings->students->province_id;
        $student->district_id=$siblings->students->district_id;
        $student->city_id=$siblings->students->city_id;
        $student->region_id=$siblings->students->region_id;
        $student->std_current_address=$siblings->students->std_current_address;
        $student->std_permanent_address=$siblings->students->std_permanent_address;
        $student->save();
    }





}