<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmDeduction;
use App\Models\HumanRM\HrmAllowance;
use App\Models\HumanRM\HrmAllowanceTransactionLine;
use App\Models\HumanRM\HrmDeductionTransactionLine;
use App\Models\HumanRM\HrmDesignation;
use App\Utils\Util;
use App\Models\HumanRM\HrmAttendance;
use App\Utils\EmployeeUtil;
use App\Models\HumanRM\HrmTransaction;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use File;

class HrmPrintController extends Controller
{
    
 /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('payroll.print')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $designations = HrmDesignation::forDropdown();


        $campuses=Campus::forDropdown();
        // dd($this->getTableColumns('hrm_employees'));
        
        return view('hrm.print.create')->with(compact('campuses', 'designations'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('payroll.print')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'campus_id' => "required",
            'month_year' => "required"
        ]);
        $month_year=$input['month_year'];
        $campus_id=$input['campus_id'];
        $month_year_arr = explode('/', request()->input('month_year'));
        $month = $month_year_arr[0];
        $year = $month_year_arr[1];
        $transaction_date = $year . '-' . $month . '-01';
        $start_date = $transaction_date;
        $end_date = \Carbon::parse($start_date)->lastOfMonth();
        $month_last_date = $end_date->format('d');
        $transaction_end_date = $year . '-' . $month .'-'. $month_last_date ;
        $transactions=$this->getListPayrollTransactionForPrint($month, $year,$input['designation'],$campus_id);
        if(empty($transactions)){
            $output = ['success' => false,
            'msg' => __('english.data_not_found')
                ];

           return redirect()->back()->with(['status' => $output]);   
        }
        $logo = 'Pk';
        if (File::exists(public_path('uploads/pdf/hrm.pdf'))) {
            File::delete(public_path('uploads/pdf/hrm.pdf'));
        }
        $pdf_name='hrm'.'.pdf';
        $month_name = $end_date->format('F');
        $pdf =  config('constants.mpdf');
        if ($pdf) {  
        $data=[
            'transactions'=>$transactions,
            'month_name'=>$month_name,
            'year'=>$year
        ];
        $this->reportPDF('samplereport.css', $data, 'MPDF.pay_roll_list_print','view','a4','landscape');
       }else{
    $snappy  = \WPDF::loadview('hrm.print.employee_print', compact('transactions', 'month_name', 'year'));
    $headerHtml = view()->make('common._header', compact('logo'))->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'landscape')->setOption('margin-top', 30)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 15);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}

        // dd($this->getTableColumns('hrm_employees'));
    }


    public function getTableColumns($table)
    {
        return DB::getSchemaBuilder()->getColumnListing($table);

        // OR

        return Schema::getColumnListing($table);
    }
    public function getListPayrollTransactionForPrint($month, $year ,$designation,$campus_id)
    {
        $transactions = HrmTransaction::leftJoin('hrm_employees', 'hrm_transactions.employee_id', '=', 'hrm_employees.id')
           
                 ->leftJoin('users as u', 'hrm_transactions.created_by', '=', 'u.id')
                ->leftJoin('sessions', 'hrm_transactions.session_id', '=', 'sessions.id')
                ->join(
                    'campuses AS campus',
                    'hrm_transactions.campus_id',
                    '=',
                    'campus.id'
                )
                ->where('hrm_transactions.status', 'final')
                // ->where('hrm_transactions.month', $month)
                ->whereYear('hrm_transactions.transaction_date', '=', $year)
                 ->whereMonth('hrm_transactions.transaction_date', '=', $month)
                ->where('hrm_transactions.type', 'pay_roll')
                ->select(
                    'hrm_transactions.id',
                    'hrm_transactions.transaction_date',
                    'hrm_transactions.ref_no',
                    'hrm_transactions.basic_salary',
                    'hrm_transactions.payment_status',
                    'hrm_transactions.final_total',
                    'hrm_transactions.allowances_amount',
                    'hrm_transactions.deductions_amount',
                    'hrm_employees.father_name',
                    'hrm_employees.employeeID as employeeID',
                    'hrm_employees.id as employee_id',
                    'hrm_employees.status',
                    DB::raw("concat(sessions.title, ' - ' '(', sessions.status, ') ') as session_info"),
                    DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name"),
                    DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                    DB::raw('(SELECT SUM(IF(TP.is_return = 1,-1*TP.amount,TP.amount)) FROM hrm_transaction_payments AS TP WHERE
                        TP.hrm_transaction_id=hrm_transactions.id) as total_paid'),
                    'campus.campus_name as campus_name',
                );
               // dd($designation);
        if (!empty($designation)) {
            $transactions->where('hrm_employees.designation_id', $designation);
        }
        if (!empty($campus_id)) {
            $transactions->where('hrm_employees.campus_id', $campus_id);
        }
        $transactions = $transactions->get();
        //dd($transactions);
        $data=[];
        if (!empty($transactions)) {
            foreach ($transactions as $t) {
                $employee=$this->getEmployeeDue($t->employee_id);
                $att=$this->getEmployeeAttendance($t->employee_id,$month, $year);
                $data[]=[
                            'employee_name' => $t->employee_name,
                            'designation'=>$employee->designation,
                            'employeeID' => $t->employeeID,
                            'father_name' => $t->father_name,
                            'attendance' => $att,
                            'arrears' =>$employee->arrears ,
                            'basic_salary' => $t->basic_salary,
                            'allowances_amount' => $t->allowances_amount,
                            'deductions_amount' => $t->deductions_amount,
                            'ref_no' => $t->ref_no,
                            'transaction_date' => $t->transaction_date,
                            'session_info' => $t->session_info,
                            'campus_name' => $t->campus_name,
                            'total_paid' => $t->total_paid,
                            'final_total' => $t->final_total,
                            'payment_status' => $t->payment_status,
                            'added_by' => $t->added_by,
                            'employee_id' => $t->employee_id,
                            'id' => $t->id,

                        ];
            }
        }
        return $data;
    }

    public function getEmployeeDue($employee_id)
    {
        $HrmEmployees = HrmEmployee::leftJoin('campuses', 'hrm_employees.campus_id', '=', 'campuses.id')
           ->where('hrm_employees.id', '=', $employee_id)
            ->leftjoin('hrm_transactions AS t', 'hrm_employees.id', '=', 't.employee_id')
            ->leftjoin('hrm_designations AS d', 'hrm_employees.designation_id', '=', 'd.id')
            ->select(
                'd.designation',
                DB::raw("COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
               as arrears")
            )->first();
        return $HrmEmployees;
    }
   public function getEmployeeAttendance($employee_id,$month, $year)
   {

       $result = array();
       $new_date = "01-" . $month . "-" . $year;
       $totalDays = \Carbon::createFromDate($year, $month)->daysInMonth;//cal_days_in_month(CAL_GREGORIAN, $month, $year);
       $first_day_this_month = date('01-m-Y');
       $fst_day_str = strtotime(date('d-m-Y', strtotime($new_date)));
       $array = array();
       for ($day = 1; $day <= $totalDays; $day++) {
           $date = date('Y-m-d', $fst_day_str);
           $employe_attendance =HrmAttendance::where('employee_id',$employee_id)->whereDate('clock_in_time',$date)->where('type','present')->first();
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
               } else if ($type == 'absent') {
                   $s['classname'] = "badge bg-danger";
               } else if ($type == 'late') {
                   $s['classname'] = "badge bg-warning";
               } else if ($type == 'half_day') {
                   $s['classname'] = "badge bg-dark";
               } else if ($type == 'holiday') {
                   $s['classname'] = "badge holiday";
               } else if ($type == 'weekend') {
                   $s['classname'] = "badge weekend";
               
               } else if ($type == 'leave') {
                   $s['classname'] = "badge bg-info";
               }
               $array[] = $s;
           }
           $fst_day_str = ($fst_day_str + 86400);
       }
      return count($array);
   }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function employeeListPrintCreate()
    {
        if (!auth()->user()->can('employee.print')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $designations = HrmDesignation::forDropdown();


        $campuses=Campus::forDropdown();
        // dd($this->getTableColumns('hrm_employees'));
        
        return view('hrm.print.list_create')->with(compact('campuses', 'designations'));
    }
    public function PostEmployeePrint(Request $request)
    {
        if (!auth()->user()->can('employee.print')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'campus_id' => "required",
        ]);
        $campus_id=$input['campus_id'];
        $designation=$input['designation'];
        $status=$input['status'];
        $HrmEmployees = HrmEmployee::leftJoin('campuses', 'hrm_employees.campus_id', '=', 'campuses.id')
            ->leftjoin('hrm_transactions AS t', 'hrm_employees.id', '=', 't.employee_id')
            ->leftjoin('hrm_designations AS d', 'hrm_employees.designation_id', '=', 'd.id')
            ->select(
                'd.designation',
                'campuses.campus_name',
                'hrm_employees.father_name',
                'hrm_employees.employeeID',
                'hrm_employees.gender',
                'hrm_employees.email',
                'hrm_employees.basic_salary',
                'hrm_employees.mobile_no',
                'hrm_employees.cnic_no',
                'hrm_employees.birth_date',
                'hrm_employees.permanent_address',
                DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name")
            );
            if (!empty($designation)) {
                $HrmEmployees->where('hrm_employees.designation_id', $designation);
            }
            if (!empty($campus_id)) {
                $HrmEmployees->where('hrm_employees.campus_id', $campus_id);
            }
            if (!empty($status)) {
                $HrmEmployees->where('hrm_employees.status', $status);
            }
            $HrmEmployees->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
               as arrears")
            ]);
            $HrmEmployees->groupBy('hrm_employees.id');
            $HrmEmployees=$HrmEmployees->get();
        $logo = 'Pk';
        if (File::exists(public_path('uploads/pdf/hrm_employee.pdf'))) {
            File::delete(public_path('uploads/pdf/hrm_employee.pdf'));
        }
        $pdf_name='hrm_employee'.'.pdf';
 // dd($student_list);
 $pdf =  config('constants.mpdf');
 if ($pdf) {  
 $this->reportPDF('samplereport.css', $HrmEmployees, 'MPDF.employee_list_print','view','a4');
}else{
    $snappy  = \WPDF::loadview('hrm.print.employee_list_print', compact('HrmEmployees'));
    $headerHtml = view()->make('common._header', compact('logo'))->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'landscape')->setOption('margin-top', 30)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 15);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}

        // dd($this->getTableColumns('hrm_employees'));
    }
}
