<?php

namespace App\Utils;

use Illuminate\Support\Facades\Hash;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmEmployeeDocument;
use App\Models\User;
use App\Models\HumanRM\HrmTransaction;
use App\Models\HumanRM\HrmTransactionPayment;
use App\Models\HumanRM\HrmAttendance;
use DB;
use File;
use Spatie\Permission\Models\Role;

class EmployeeUtil extends Util
{
    public function employeeCreate($request)
    {
        $employee_input = $request->only(['campus_id','employeeID','first_name' ,'last_name','father_name','gender', 'birth_date','joining_date',
        'religion' ,'mobile_no','email','cnic_no','blood_group','nationality' ,'mother_tongue' ,'country_id','province_id' ,
        'district_id' ,'city_id' ,'region_id','current_address','permanent_address','department_id' , 'designation_id' ,
        'education_ids' ,'basic_salary','default_allowance','default_deduction' ,'pay_period','employee_image','password','bank_details']);
        $employee_input['birth_date']=$this->uf_date($employee_input['birth_date']);
        $employee_input['joining_date']=$this->uf_date($employee_input['joining_date']);
        $input['education_ids'] = !empty($request->input('education_ids')) ? $request->input('education_ids') : null;

        if (!empty($employee_input['employee_image'])) {
            $employee_image = $this->uploadFile($request, 'employee_image', 'employee_image', 'image', $employee_input['employeeID']);
            $employee_input['employee_image'] = $employee_image;
        }
        $employee_input['password'] =!empty($employee_input['password']) ? Hash::make($employee_input['password']) : Hash::make($employee_input['employeeID']);


        $employee_input['bank_details'] = !empty($employee_input['bank_details']) ? json_encode($employee_input['bank_details']) : null;
        $employee_input['email'] = !empty($employee_input['email']) ? $employee_input['email'] : $employee_input['employeeID'].'@gmail.com';

        $employee_input['password'] = Hash::make($employee_input['password']);

        $employee_input['basic_salary'] = $this->num_uf($employee_input['basic_salary']);
        $employee_input['default_allowance'] = $this->num_uf($employee_input['default_allowance']);
        $employee_input['default_deduction'] = $this->num_uf($employee_input['default_deduction']);
        $employee=HrmEmployee::create($employee_input);
        $user=$this->createEmployeeUpdateLogin($employee, $employee->id, $request->input('role'));
        $employee->user_id=$user->id;
        $employee->save();
        $employee_document=$request->file('document');
        // -------------- UPLOAD THE DOCUMENTS  -----------------
        $documents = ['resume', 'offerLetter', 'joiningLetter', 'contract', 'IDProof'];


        foreach ($documents as $document) {
            if ($request->hasFile($document)) {
                $filename=$this->uploadFile($request, $document, 'document');
                HrmEmployeeDocument::create([
                    'employee_id' => $employee->id,
                    'filename' => $filename,
                    'type' => $document
                ]);
            }
        }
        return $employee;
    }


    public function employeeUpdate($request, $id)
    {
        $employee_input = $request->only(['campus_id','employeeID','first_name' ,'last_name','father_name','gender', 'birth_date','joining_date',
        'religion' ,'mobile_no','email','cnic_no','blood_group','nationality' ,'mother_tongue' ,'country_id','province_id' ,
        'district_id' ,'city_id' ,'region_id','current_address','permanent_address','department_id' , 'designation_id' ,
        'education_ids' ,'basic_salary' ,'default_allowance','default_deduction','pay_period','employee_image','password','bank_details']);
        $employee_input['birth_date']=$this->uf_date($employee_input['birth_date']);
        $employee_input['joining_date']=$this->uf_date($employee_input['joining_date']);
        $input['education_ids'] = !empty($request->input('education_ids')) ? $request->input('education_ids') : null;
        $employee=HrmEmployee::with(['user'])->find($id);
        if (!empty($employee_input['employee_image'])) {
            if (File::exists(public_path('uploads/employee_image/'.$employee->employee_image))) {
                File::delete(public_path('uploads/employee_image/'.$employee->employee_image));
            }
            $employee_image = $this->uploadFile($request, 'employee_image', 'employee_image', 'image', $employee_input['employeeID']);
            $employee_input['employee_image'] = $employee_image;
        }
        if (empty($employee_input['password'])) {
            unset($employee_input['password']);
        } else {
            $employee_input['password'] =!empty($employee) ? Hash::make($employee_input['password']) : Hash::make($employee_input['employeeID']);
        }


        $employee_input['bank_details'] = !empty($employee_input['bank_details']) ? json_encode($employee_input['bank_details']) : null;
        $employee_input['email'] = !empty($employee_input['email']) ? $employee_input['email'] : $employee_input['employeeID'].'@gmail.com';
        $employee_input['basic_salary'] = $this->num_uf($employee_input['basic_salary']);
        $employee_input['default_allowance'] = $this->num_uf($employee_input['default_allowance']);
        $employee_input['default_deduction'] = $this->num_uf($employee_input['default_deduction']);
        $employee->fill($employee_input);
        $employee->save();
        $employee_user=HrmEmployee::with(['user'])->find($id);
        $user=$this->createEmployeeUpdateLogin($employee_user, $employee->id, $request->input('role'));
        $employee_user->user_id=$user->id;
        $employee_user->save();
        // -------------- UPLOAD THE DOCUMENTS  -----------------
        $documents = ['resume', 'offerLetter', 'joiningLetter', 'contract', 'IDProof'];


        foreach ($documents as $document) {
            if ($request->hasFile($document)) {
                $filename=$this->uploadFile($request, $document, 'document');
                $employee_document=HrmEmployeeDocument::where('employee_id', $employee->id)->where('type', $document)->first();
                if (!empty($employee_document)) {
                    if (File::exists(public_path('uploads/document/'.$employee_document->filename))) {
                        File::delete(public_path('uploads/document/'.$employee_document->filename));
                    }
                    $employee_document->filename = $filename;
                    $employee_document->save();
                } else {
                    HrmEmployeeDocument::create([
                    'employee_id' => $employee->id,
                    'filename' => $filename,
                    'type' => $document
                ]);
                }
            }
        }
        return $employee;
    }


    public function getEmployeeList($campus_id, $already_exists)
    {
        $query=HrmEmployee::leftJoin('campuses', 'hrm_employees.campus_id', '=', 'campuses.id')
        ->where('status', '=', 'active')
        ->whereNotIn('hrm_employees.id', $already_exists)
        ->select(
            'campuses.campus_name',
            'hrm_employees.father_name',
            'hrm_employees.employeeID',
            'hrm_employees.status',
            'hrm_employees.id as id',
            'hrm_employees.basic_salary',
            'hrm_employees.employee_image',
            'hrm_employees.joining_date',
            DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name")
        );

        if (!empty($campus_id)) {
            $query->where('hrm_employees.campus_id', $campus_id);
        }

        return $query->get();
    }
    public function getActiveEmployees($campus_id)
    {
        $query = HrmEmployee::where('status', '=', 'active');
        //Filter by the campus
          $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("COUNT(*) as total_employees")
        );

        $employee_count = $query->first();
        return $employee_count->total_employees;
    }
    public function getResignEmployees($campus_id)
    {
        $query = HrmEmployee::where('status', '=', 'resign');
        //Filter by the campus
          $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("COUNT(*) as total_employees")
        );

        $employee_count = $query->first();
        return $employee_count->total_employees;
    }

     /**
     * Function to get ledger details
     *
     */
    public function getLedgerDetails($employee_id, $start, $end)
    {
        //Get sum of totals before start date
        $previous_transaction_sums = $this->__transactionQuery($employee_id, $start)
                ->select(
                    DB::raw("SUM(final_total) as total_payroll"),
                )->first();
        // //Get payment totals before start date
        $prev_payments = $this->__paymentQuery($employee_id, $start)
                            ->select('hrm_transaction_payments.*', 'campus.campus_name as location_name', 't.type as transaction_type')
                                    ->get();

        $prev_total_payroll_paid = $prev_payments->where('is_return', 0)->sum('amount');
        $total_prev_invoice = $previous_transaction_sums->total_payroll;
        $total_prev_paid = $prev_total_payroll_paid  ;
        $beginning_balance =  $total_prev_invoice - $total_prev_paid;
        // $contact = Contact::find($employee_id);

        // //Get transaction totals between dates
        $transactions = $this->__transactionQuery($employee_id, $start, $end)
                            ->get();
        $ledger = [];

        
        foreach ($transactions as $transaction) {
            $ledger[] = [
                'date' => $transaction->transaction_date,
                'ref_no' =>  $transaction->ref_no,
                'type' => $transaction->payroll_group_name,
    
                'payment_status' =>  __('english.' . $transaction->payment_status),
                'total' => '',
                'payment_method' => '',
                'debit' => '',
                'credit' => $transaction->final_total ,
                'others' => ''
            ];
        }
        //dd($ledger);
        $payroll_sum = $transactions->sum('final_total');
        //Get payment totals between dates
        $payments = $this->__paymentQuery($employee_id, $start, $end)
                        ->select('hrm_transaction_payments.*','t.type as transaction_type', 't.ref_no')
                        ->get();
        
        $paymentTypes = $this->payment_types();
        
         foreach ($payments as $payment) {
            $ref_no =  $payment->ref_no;
            $note = $payment->note;
            if (!empty($ref_no)) {
                $note .='<small>' . __('account.payment_for') . ': ' . $ref_no . '</small>';
            }

         

            $ledger[] = [
                'date' => $payment->paid_on,
                'ref_no' => $payment->payment_ref_no,
                'type' => '',
    
                'payment_status' => '',
                'total' => '',
                'payment_method' => !empty($paymentTypes[$payment->method]) ? $paymentTypes[$payment->method] : '',
                'payment_method_key' => $payment->method,
                'debit' => $payment->amount ,
                'credit' => '',
                'others' =>  $note 
            ];
        }
        
       
        $total_payroll_paid = $payments->where('is_return', 0)->sum('amount');


        $start_date = $this->format_date($start);
        $end_date = $this->format_date($end);

        $total_payroll = $payroll_sum;


        $total_paid = $total_payroll_paid;
         $curr_due = $total_payroll - $total_paid + $beginning_balance;

        // //Sort by date
        if (!empty($ledger)) {
            usort($ledger, function ($a, $b) {
                $t1 = strtotime($a['date']);
                $t2 = strtotime($b['date']);
                return $t1 - $t2;
            });
        }
        $total_opening_bal = $beginning_balance ;
        //Add Beginning balance & openining balance to ledger
        $ledger = array_merge([[
            'date' => $start,
            'ref_no' => '',
            'type' => __('english.opening_balance'),

            'payment_status' => '',
            'total' => '',
            'payment_method' => '',
            'debit' =>  '',
            'credit' => abs($total_opening_bal),
            'others' => ''
        ]], $ledger) ;

        $bal = 0;
        foreach($ledger as $key => $val) {
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
            'total_payroll' => $total_payroll,
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
    private function __transactionQuery($employee_id, $start, $end = null)
    {
        $query = HrmTransaction::where('hrm_transactions.employee_id', $employee_id);
                    
        if (!empty($start)  && !empty($end)) {
            $query->whereDate(
                'hrm_transactions.transaction_date',
                '>=',
                $start
            )
                ->whereDate('hrm_transactions.transaction_date', '<=', $end)->get();
        }

        if (!empty($start)  && empty($end)) {
           
            $query->whereDate('hrm_transactions.transaction_date', '<', $start);
            
        }
           
        return $query;
    }
    
    /**
     * Query to get payment details for a customer
     *
     */
    private function __paymentQuery($employee_id, $start, $end = null)
    {
        $query = HrmTransactionPayment::leftJoin(
            'hrm_transactions as t',
            'hrm_transaction_payments.hrm_transaction_id',
            '=',
            't.id'
        )
            ->leftJoin('campuses as campus', 't.campus_id', '=', 'campus.id')
            ->where('hrm_transaction_payments.payment_for', $employee_id)
            //->whereNotNull('transaction_payments.transaction_id');
            ->whereNull('hrm_transaction_payments.parent_id');

        if (!empty($start)  && !empty($end)) {
            $query->whereDate('paid_on', '>=', $start)
                        ->whereDate('paid_on', '<=', $end);
        }

        if (!empty($start)  && empty($end)) {
            $query->whereDate('paid_on', '<', $start);
        }

        return $query;
    }


    public  function  getEmployeeTotalAttendances($campus_id,$type,$start, $end = null){

    $query = HrmAttendance::leftJoin(
        'hrm_employees as em',
        'hrm_attendances.employee_id',
        '=',
        'em.id'
    )->where('hrm_attendances.type',$type);
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
      $query->whereIn('em.campus_id', $permitted_campuses);
    }
    if (!empty($campus_id)) {
        $query->where('em.campus_id', $campus_id);
    }
    if (!empty($start)  && !empty($end)) {
        $query->whereDate('hrm_attendances.clock_in_time', '>=', $start)
                    ->whereDate('hrm_attendances.clock_in_time', '<=', $end);
    }

    if (!empty($start)  && empty($end)) {
        $query->whereDate('hrm_attendances.clock_in_time', '<', $start);
    }
    $query->select(
        DB::raw("COUNT(*) as total_attendance")
    );

    $attendance_count = $query->first();
    return $attendance_count->total_attendance;

    }
    
   
}
