<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*use App\Models\ClassSection;
use App\Models\Classes;
use App\Models\Session;
use App\Models\Student;
use App\Models\FeeTransaction;
use App\Models\Discount;
use App\Models\FeeHead;
use App\Models\FeeTransactionPayment;
use App\Models\FeeTransactionLine;
use Yajra\DataTables\Facades\DataTables;*/
use App\Models\Campus;
use Carbon;
use App\Utils\StudentUtil;
use App\Utils\EmployeeUtil;
use App\Utils\FeeTransactionUtil;
use App\Utils\HrmTransactionUtil;
use App\Utils\ExpenseTransactionUtil;
//use App\Utils\NotificationUtil;
//use App\Charts\CommonChart;
use DB;
use App\Utils\Util;

//use Illuminate\Notifications\DatabaseNotification;

class HomeController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    // protected $businessUtil;
    protected $studentUtil;
    protected $feeTransactionUtil;
    protected $expenseTransactionUtil;
    protected $commonUtil;
    protected $employeeUtil;
    protected $hrmTransactionUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Util $commonUtil,
        StudentUtil $studentUtil,
        EmployeeUtil $employeeUtil,
        FeeTransactionUtil $feeTransactionUtil,
        ExpenseTransactionUtil $expenseTransactionUtil,
        HrmTransactionUtil $hrmTransactionUtil
    ) {
        $this->commonUtil = $commonUtil;
        $this->studentUtil = $studentUtil;
        $this->employeeUtil = $employeeUtil;
        $this->feeTransactionUtil = $feeTransactionUtil;
        $this->expenseTransactionUtil = $expenseTransactionUtil;
        $this->hrmTransactionUtil = $hrmTransactionUtil;
    }


    public function index()
    {
        //dd( $common_settings);
        $user = \Auth::user();
        if ($user->user_type == 'teacher') {
            return redirect('/dashboard');
        } elseif ($user->user_type == 'student') {
            return redirect('/student/dashboard');
        } elseif ($user->user_type == 'guardian') {
            return redirect('/guardian/dashboard');
        } elseif ($user->user_type == 'staff') {
            return redirect('/staff/dashboard');
        } else {
            $campuses=Campus::forDropdown();
            $common_settings = session()->get('system_details.common_settings');

            return view('home.home')->with(compact('campuses','common_settings'));
        }
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
       // return view('clear-cache');
        return redirect('/');

    }
        /**
     * Retrieves purchase and sell details for a given time period.
     *
     * @return \Illuminate\Http\Response
     */
   /* public function getTotals()
    {
        if (request()->ajax()) {
            $start = request()->start;
            $end = request()->end;
            //dd($end);
            //$date = \Carbon::parse($end);
            
           // $isToday = $date->isToday();

//$isYesterday = $date->isYesterday();

//$isTodayOrYesterday =  $date->isToday() || $date->isYesterday();
            $campus_id = request()->campus_id;
            
            // $business_id = request()->session()->get('user.business_id');

            $active_students=$this->studentUtil->getActiveStudents($campus_id);
            $students_gender=$this->studentUtil->getStudentGender($campus_id);
            $inactive_students=$this->studentUtil->getInactiveStudents($campus_id);
            $pass_out_students=$this->studentUtil->getPassOutStudents($campus_id);
            $struck_up_students=$this->studentUtil->getStruckUpStudents($campus_id);
            $took_slc_students=$this->studentUtil->getTookSLCStudents($campus_id);
            $total_student_attendance=$this->studentUtil->getStudentTotalAttendances($campus_id, 'present', $start, $end);
            $total_student_late=$this->studentUtil->getStudentTotalAttendances($campus_id, 'late', $start, $end);
            $total_student_absent_attendance=$this->studentUtil->getStudentTotalAttendances($campus_id, 'absent', $start, $end);
            //Employee
            $total_employee_attendance=$this->employeeUtil->getEmployeeTotalAttendances($campus_id, 'present', $start, $end);
            $total_employee_absent_attendance=$this->employeeUtil->getEmployeeTotalAttendances($campus_id, 'absent', $start, $end);
            $active_employees=$this->employeeUtil->getActiveEmployees($campus_id);
            $resign_employees=$this->employeeUtil->getResignEmployees($campus_id);
            $total_paid_amount=$this->feeTransactionUtil->getTotalFeePaid($start, $end, null, $campus_id);
           // $total_expense=$this->expenseTransactionUtil->getTotalExpense($start, $end, $campus_id);
         
            $output['account_balances']=$this->feeTransactionUtil->getAccountBalance($end, $campus_id);
            $output['active_students'] = $active_students;
            $output['total_paid_amount'] = $total_paid_amount;
            $output['inactive_students'] = $inactive_students;
            $output['pass_out_students'] = $pass_out_students;
            $output['struck_up_students'] = $struck_up_students;
            $output['took_slc_students'] = $took_slc_students;
            $output['students_gender'] = $students_gender;
            $output['total_student_attendance'] = $total_student_attendance;
            $output['total_student_absent_attendance'] = $total_student_absent_attendance;
            $output['total_student_late'] = $total_student_late;
             //Khan lala 
             //dd(\Carbon::now()->lastOfMonth()->format('Y-m-d'));
            $output['total_progress_collection_during_month']=$this->feeTransactionUtil->getTotalFeePaid(\Carbon::now()->startOfMonth()->format('Y-m-d'),\Carbon::now()->lastOfMonth()->format('Y-m-d'), null, $campus_id);
             $total=$this->feeTransactionUtil->getFeeTotals(null, $end, $campus_id);
             //dd($total);
             $output['total_dues_this_month']=$total['total_fee_due']+$output['total_progress_collection_during_month'];//+ $output['total_paid_amount'];
             $total_expense=$this->expenseTransactionUtil->getTotalExpense($start, $end, $campus_id) +$this->hrmTransactionUtil->getTotalHrm($start, $end, $campus_id);
             $output['total_expense'] = $total_expense;
             $output['total_hrm_paid_amount'] = $this->expenseTransactionUtil->getTotalExpense(\Carbon::now()->startOfMonth()->format('Y-m-d'),\Carbon::now()->lastOfMonth()->format('Y-m-d'), $campus_id)+$this->hrmTransactionUtil->getTotalHrm(\Carbon::now()->startOfMonth()->format('Y-m-d'),\Carbon::now()->lastOfMonth()->format('Y-m-d'), $campus_id);
             $total_transport=$this->feeTransactionUtil->getTransportTotals(null, $end, $campus_id);
             $total_transport_paid_amount=$this->feeTransactionUtil->getTotalTransportPaid($start, $end, null, $campus_id);

             $output['total_transport_paid_amount']=$total_transport_paid_amount;
             $output['total_transport_progressive_amount']=$this->feeTransactionUtil->getTotalTransportPaid(\Carbon::now()->startOfMonth()->format('Y-m-d'),\Carbon::now()->lastOfMonth()->format('Y-m-d'), null, $campus_id);
             $output['total_transport_dues_this_month']=$total_transport['total_fee_due']+$output['total_transport_progressive_amount'];//+$total_transport_paid_amount;


            //Employee
            $output['total_employee_attendance'] = $total_employee_attendance;
            $output['total_employee_absent_attendance'] = $total_employee_absent_attendance;
            $output['active_employees'] = $active_employees;
            $output['resign_employees'] = $resign_employees;
            $currency = request()->session()->get('currency');
            
            //Chart for sells last 30 days
            $sells_last_30_days = $this->feeTransactionUtil->getFeePAidLast30Days($campus_id);
            $labels = [];
            $all_sell_values = [];
            $dates = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = \Carbon::now()->subDays($i)->format('Y-m-d');
                $dates[] = $date;

                $labels[] = date('j M Y', strtotime($date));

                if (!empty($sells_last_30_days[$date])) {
                    $all_sell_values[] = (float) $sells_last_30_days[$date];
                } else {
                    $all_sell_values[] = 0;
                }
            }
            $output['labels']=$labels;
            $output['all_sell_values']=$all_sell_values;
            //Chart for Expenses last 30 days
            $expenses_last_30_days = $this->expenseTransactionUtil->getExpensesLast30Days($campus_id);
            $labels_expenses = [];
            $all_expenses_values = [];
            $dates = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = \Carbon::now()->subDays($i)->format('Y-m-d');
                $dates[] = $date;

                $labels_expenses[] = date('j M Y', strtotime($date));

                if (!empty($expenses_last_30_days[$date])) {
                    $all_expenses_values[] = (float) $expenses_last_30_days[$date];
                } else {
                    $all_expenses_values[] = 0;
                }
            }
            $output['labels_expenses']=$labels_expenses;
            $output['all_expenses_values']=$all_expenses_values;



                //Chart for Htm last 30 days
                $hrm_last_30_days = $this->hrmTransactionUtil->getEmployeeLast30Days($campus_id);
                $labels_hrm = [];
                $all_hrm_values = [];
                $dates = [];
                for ($i = 29; $i >= 0; $i--) {
                    $date = \Carbon::now()->subDays($i)->format('Y-m-d');
                    $dates[] = $date;
    
                    $labels_hrm[] = date('j M Y', strtotime($date));
    
                    if (!empty($hrm_last_30_days[$date])) {
                        $all_hrm_values[] = (float) $hrm_last_30_days[$date];
                    } else {
                        $all_hrm_values[] = 0;
                    }
                }
                $output['labels_hrm']=$labels_hrm;
                $output['all_hrm_values']=$all_hrm_values;
            return $output;
        }
    }

/*










   ///Aby//////

    /**
     * Retrieves purchase and sell details for a given time period.
     *
     * @return \Illuminate\Http\Response
     */
 public function getTotals()
    {
        if (request()->ajax()) {
            $start = request()->start;
            $end = request()->end;
            $end_date=$end;    
            $_date=explode('-',$end_date) ;
            if($_date[2]>=26){
                $month=$_date['1']+1;
                if($month<=12){
                        $end_date=Carbon::parse($_date[0].'-'.$month.'-01')->format('Y-m-d');           
        
                    }else{
                        $end_date=Carbon::parse($_date[0].'-'.$_date['1'].'-26')->format('Y-m-d');           
        
                    }
            
                }
                $start_date=$start; 
                $_date = explode('-', $start_date);
                if ($_date[2]>= 1 && $_date[2]<26 ) {
                    $month = $_date['1'] - 1;
                    if ((int)$month >= 1 && (int)$month <= 12) {
                        $start_date = Carbon::parse($_date[0] . '-' . $month . '-26')->format('Y-m-d');
    
                    } else {
                        $start_date = Carbon::parse($_date[0]-1 . '-' . $_date['1'] . '-26')->format('Y-m-d');
    
                    }
                }
                 if ((int)$_date[2]===26 || (int)$_date[2]===27 || (int)$_date[2]===28 || (int)$_date[2]===29 || (int)$_date[2]===30 || (int)$_date[2]===31  ) {
                    $month = $_date['1'] ;
                    if ($month >= 1 && $month <= 12) {
                        $start_date = Carbon::parse($_date[0] . '-' . $month . '-26')->format('Y-m-d');
                    }
                }
        // dd($start_date,$end_date);
           
            $campus_id = request()->campus_id;
            
            // $business_id = request()->session()->get('user.business_id');

            $active_students=$this->studentUtil->getActiveStudents($campus_id);
            $students_gender=$this->studentUtil->getStudentGender($campus_id);
            $inactive_students=$this->studentUtil->getInactiveStudents($campus_id);
            $pass_out_students=$this->studentUtil->getPassOutStudents($campus_id);
            $struck_up_students=$this->studentUtil->getStruckUpStudents($campus_id);
            $took_slc_students=$this->studentUtil->getTookSLCStudents($campus_id);
            $total_student_attendance=$this->studentUtil->getStudentTotalAttendances($campus_id, 'present', $start, $end);
            $total_student_late=$this->studentUtil->getStudentTotalAttendances($campus_id, 'late', $start, $end);
            $total_student_absent_attendance=$this->studentUtil->getStudentTotalAttendances($campus_id, 'absent', $start, $end);
            //Employee
            $total_employee_attendance=$this->employeeUtil->getEmployeeTotalAttendances($campus_id, 'present', $start, $end);
            $total_employee_absent_attendance=$this->employeeUtil->getEmployeeTotalAttendances($campus_id, 'absent', $start, $end);
            $active_employees=$this->employeeUtil->getActiveEmployees($campus_id);
            $resign_employees=$this->employeeUtil->getResignEmployees($campus_id);
            $total_paid_amount=$this->feeTransactionUtil->getTotalFeePaid($start, $end, null, $campus_id);
           // $total_expense=$this->expenseTransactionUtil->getTotalExpense($start, $end, $campus_id);
         
            $output['account_balances']=$this->feeTransactionUtil->getAccountBalance($end, $campus_id);
            $output['active_students'] = $active_students;
            $output['total_paid_amount'] = $total_paid_amount;
            $output['inactive_students'] = $inactive_students;
            $output['pass_out_students'] = $pass_out_students;
            $output['struck_up_students'] = $struck_up_students;
            $output['took_slc_students'] = $took_slc_students;
            $output['students_gender'] = $students_gender;
            $output['total_student_attendance'] = $total_student_attendance;
            $output['total_student_absent_attendance'] = $total_student_absent_attendance;
            $output['total_student_late'] = $total_student_late;
             //Khan lala 
             //dd($start_date,$end_date);
            $output['total_progress_collection_during_month']=$this->feeTransactionUtil->getTotalFeePaid(\Carbon::parse($start_date)->format('Y-m-d'),\Carbon::parse($end_date)->format('Y-m-d'), null, $campus_id);
            $total=$this->feeTransactionUtil->getFeeTotals(null, $end_date, $campus_id);
             //$total=$this->feeTransactionUtil->getFeeTotals(null, $end, $campus_id);
             //dd($total);
             $output['total_dues_this_month']=$total['total_fee_due']+$output['total_progress_collection_during_month'];//+ $output['total_paid_amount'];
             $total_expense=$this->expenseTransactionUtil->getTotalExpense($start, $end, $campus_id) +$this->hrmTransactionUtil->getTotalHrm($start, $end, $campus_id);
             $output['total_expense'] = $total_expense;
             $output['total_hrm_paid_amount'] = $this->expenseTransactionUtil->getTotalExpense(\Carbon::parse($start_date)->format('Y-m-d'),\Carbon::parse($end_date)->format('Y-m-d'), $campus_id)+$this->hrmTransactionUtil->getTotalHrm(\Carbon::parse($start_date)->format('Y-m-d'),\Carbon::parse($end_date)->format('Y-m-d'), $campus_id);
             $total_transport=$this->feeTransactionUtil->getTransportTotals(null, $end_date, $campus_id);
             $total_transport_paid_amount=$this->feeTransactionUtil->getTotalTransportPaid($start, $end, null, $campus_id);

             $output['total_transport_paid_amount']=$total_transport_paid_amount;
             $output['total_transport_progressive_amount']=$this->feeTransactionUtil->getTotalTransportPaid(\Carbon::parse($start_date)->format('Y-m-d'),\Carbon::parse($end_date)->format('Y-m-d'), null, $campus_id);
             $output['total_transport_dues_this_month']=$total_transport['total_fee_due']+$output['total_transport_progressive_amount'];//+$total_transport_paid_amount;


            //Employee
            $output['total_employee_attendance'] = $total_employee_attendance;
            $output['total_employee_absent_attendance'] = $total_employee_absent_attendance;
            $output['active_employees'] = $active_employees;
            $output['resign_employees'] = $resign_employees;
            $currency = request()->session()->get('currency');
            
            //Chart for sells last 30 days
            $sells_last_30_days = $this->feeTransactionUtil->getFeePAidLast30Days($campus_id);
            $labels = [];
            $all_sell_values = [];
            $dates = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = \Carbon::now()->subDays($i)->format('Y-m-d');
                $dates[] = $date;

                $labels[] = date('j M Y', strtotime($date));

                if (!empty($sells_last_30_days[$date])) {
                    $all_sell_values[] = (float) $sells_last_30_days[$date];
                } else {
                    $all_sell_values[] = 0;
                }
            }
            $output['labels']=$labels;
            $output['all_sell_values']=$all_sell_values;
            //Chart for Expenses last 30 days
            $expenses_last_30_days = $this->expenseTransactionUtil->getExpensesLast30Days($campus_id);
            $labels_expenses = [];
            $all_expenses_values = [];
            $dates = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = \Carbon::now()->subDays($i)->format('Y-m-d');
                $dates[] = $date;

                $labels_expenses[] = date('j M Y', strtotime($date));

                if (!empty($expenses_last_30_days[$date])) {
                    $all_expenses_values[] = (float) $expenses_last_30_days[$date];
                } else {
                    $all_expenses_values[] = 0;
                }
            }
            $output['labels_expenses']=$labels_expenses;
            $output['all_expenses_values']=$all_expenses_values;



                //Chart for Htm last 30 days
                $hrm_last_30_days = $this->hrmTransactionUtil->getEmployeeLast30Days($campus_id);
                $labels_hrm = [];
                $all_hrm_values = [];
                $dates = [];
                for ($i = 29; $i >= 0; $i--) {
                    $date = \Carbon::now()->subDays($i)->format('Y-m-d');
                    $dates[] = $date;
    
                    $labels_hrm[] = date('j M Y', strtotime($date));
    
                    if (!empty($hrm_last_30_days[$date])) {
                        $all_hrm_values[] = (float) $hrm_last_30_days[$date];
                    } else {
                        $all_hrm_values[] = 0;
                    }
                }
                $output['labels_hrm']=$labels_hrm;
                $output['all_hrm_values']=$all_hrm_values;
            return $output;
        }
    }
}
