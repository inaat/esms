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
use App\Utils\Util;
use App\Utils\EmployeeUtil;
use App\Models\Session;
use App\Models\HumanRM\HrmTransaction;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Events\HrmTransactionPaymentDeleted;



use DB;

class HrmPayrollController extends Controller
{
    /**
     * All Utils instance.
     *
     */
   
    protected $employeeUtil;

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
     * @return Response
     */
    /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
    public function index()
    {    //dd($this->getListPayrollTransaction(1)->get());
        if (!auth()->user()->can('payroll.view')) {
            abort(403, 'Unauthorized action.');
        } 

        if (request()->ajax()) {
            $hrm_transactions=$this->getListPayrollTransaction();
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $hrm_transactions->where('hrm_transactions.campus_id', $campus_id);
                }
            }
            if (request()->has('payment_status')) {
                $payment_status = request()->get('payment_status');
                if (!empty($payment_status)) {
                    $hrm_transactions->where('hrm_transactions.payment_status', $payment_status);
                }
            }
            // if (request()->has('session_id')) {
            //     $session_id = request()->get('session_id');
            //     if (!empty($session_id)) {
            //         $hrm_transactions->where('hrm_transactions.session_id', $session_id);
            //     }
            // }
            // if (request()->has('month')) {
            //     $month = request()->get('month');
            //     if (!empty($month)) {
            //         $hrm_transactions->where('hrm_transactions.month', $month);
            //     }
            // }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $hrm_transactions->whereDate('hrm_transactions.transaction_date', '>=', $start)
                        ->whereDate('hrm_transactions.transaction_date', '<=', $end);
            }
            $datatable = Datatables::of($hrm_transactions)
        ->addColumn(
            'action',
            function ($row) {
                $html= '<div class="dropdown">
                     <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                     <ul class="dropdown-menu" style="">';
                if (auth()->user()->can('payroll.update')) {
                if ($row->payment_status == "due") {
                    $html.='<li><a class="dropdown-item "href="' . action('Hrm\HrmPayrollController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                }
            }
if (auth()->user()->can('hrm_payment.create')) {
    if ($row->payment_status != "paid") {
        $html .= '<li><a href="' . action('Hrm\HrmTransactionPaymentController@addPayment', [$row->id]) . '" class="dropdown-item add_payment_modal"><i class="fas fa-money-bill-alt"></i> ' . __("english.add_payment") . '</a></li>';
    }
}
if (auth()->user()->can('payroll.print')) {
    $html.='<li><a class="dropdown-item print-invoice " href="#" data-href="' . action('Hrm\HrmPayrollController@paySlip', [$row->id]) . '"><i class="fas fa-print"></i> ' . __("english.pay_slip_print") . '</a></li>';
}
if (auth()->user()->can('hrm_payment.view')) {
    $html .= '<li><a href="' . action('Hrm\HrmTransactionPaymentController@show', [$row->id]) . '" class="dropdown-item view_payment_modal"><i class="fas fa-money-bill-alt"></i> ' . __("english.view_payments") . '</a></li>';
}  if (auth()->user()->can('payroll.delete')) {
                if ($row->payment_status == "due") {
                    $html .= '<li>
                        <a href="'.action('Hrm\HrmPayrollController@destroy', [$row->id]).'" class="delete-hrm_transaction"><i class="fas fa-trash"></i>'.__("messages.delete").'</a>
                        </li>';
                }
                }
                $html .= '</ul></div>';

                return $html;
            }
        )
        ->editColumn(
            'payment_status',
            function ($row) {
                $payment_status = HrmTransaction::getPaymentStatus($row);
                return (string) view('hrm.payroll.partials.payment_status', ['payment_status' => $payment_status, 'id' => $row->id]);
            }
        )
        ->editColumn('employee_name', function ($row) {
            return ucwords($row->employee_name);
        })
        ->editColumn('father_name', function ($row) {
            return ucwords($row->father_name);
        })
        ->editColumn('employeeID', function ($row) {
            return ucwords($row->employeeID);
        })
        ->editColumn('month', function ($row) {
            return __('english.months.' . $row->month);
        })
         ->editColumn('status', function ($row) {
             $status_color = !empty($this->employee_status_colors[$row->status]) ? $this->employee_status_colors[$row->status] : 'bg-gray';
             $status='<span class="badge badge-mark ' . $status_color .'">' .ucwords($row->status).   '</span>';
             return $status;
         })
        ->addColumn('total_remaining', function ($row) {
            $total_remaining =  $row->final_total - $row->total_paid;
            $total_remaining_html = '<span class="payment_due" data-orig-value="' . $total_remaining . '">' . $this->employeeUtil->num_f($total_remaining, true) . '</span>';

            
            return $total_remaining_html;
        })
        ->editColumn(
            'total_paid',
            '<span class="total-paid" data-orig-value="{{$total_paid}}">@format_currency($total_paid)</span>'
        )
        ->editColumn(
            'final_total',
            '<span class="final-total" data-orig-value="{{$final_total}}">@format_currency($final_total)</span>'
        )
        ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
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
        ->removeColumn('id');
     
      

            $rawColumns = ['action','status','campus_name','month','transaction_date','ref_no','payment_status','final_total','total_remaining','total_paid','employeeID'];
        
            return $datatable->rawColumns($rawColumns)
              ->make(true);
        }
        $campuses=Campus::forDropdown();
        
        $sessions=Session::forDropdown(false, true);

        return view('hrm.payroll.index')->with(compact('campuses','sessions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('payroll.create')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');
       

        $campuses=Campus::forDropdown();
        $now_month = \Carbon::now()->month;
        
        return view('hrm.payroll.create')->with(compact('campuses', 'now_month'));
    }
    public function payrollAssignSearch(Request $request)
    {
        if (!auth()->user()->can('payroll.create')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'campus_id' => "required",
            'month_year' => "required"
        ]);
        $month_year=$input['month_year'];
        $status=$input['status'];
        $campus_id=$input['campus_id'];
        $month_year_arr = explode('/', request()->input('month_year'));
        $month = $month_year_arr[0];
        $year = $month_year_arr[1];
     
        $transaction_date = $year . '-' . $month . '-01';
        $start_date = $transaction_date;
        $end_date = \Carbon::parse($start_date)->lastOfMonth();
        $month_name = $end_date->format('F');
        $campuses=Campus::forDropdown();

        //check if payrolls exists for the month year
        $payrolls = HrmTransaction::whereDate('transaction_date', $transaction_date)
                    ->get('employee_id', 'first_name');
        $already_exists = [];
        if (!empty($payrolls)) {
            foreach ($payrolls as $key => $value) {
                array_push($already_exists, $value->employee_id);
            }
        }
        $employees=$this->employeeUtil->getEmployeeList($campus_id, $already_exists);
        $allowances = HrmAllowance::get();
        $deductions = HrmDeduction::get();
        return view('hrm.payroll.payroll_assign')->with(compact('employees', 'status', 'campuses', 'campus_id', 'month_year', 'month_name', 'transaction_date', 'year', 'deductions', 'allowances'));
    }
    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        // if (!(auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'essentials_module'))) {
        //     abort(403, 'Unauthorized action.');
        // }
        // dd(array_sum(array_column($request->input('allowances'), 'amount')));
      // dd($request);
      if (!auth()->user()->can('payroll.create')) {
        abort(403, 'Unauthorized action.');
    }
        try {
            $employee_ids = request()->input('employee_checked');
            $month_year_arr = explode('/', request()->input('month_year'));
            $month = $month_year_arr[0];
            $year = $month_year_arr[1];
    
            $transaction_date = $year . '-' . $month . '-01';
           
            //check if payrolls exists for the month year
            $payrolls = HrmTransaction::WhereIn('employee_id', $employee_ids)
                        ->whereDate('transaction_date', $transaction_date)
                        ->get();
    
            $add_payroll_for = array_diff($employee_ids, $payrolls->pluck('employee_id')->toArray());
            if (!empty($add_payroll_for)) {
                DB::beginTransaction();
                $input=$request->input();
                foreach ($add_payroll_for as $employee_id) {
                    $employee = HrmEmployee::find($employee_id);
                    $transaction=$this->createPayrollTransaction($employee, $request);
                }
         
                DB::commit();
                $output = ['success' => true,
                'msg' => __("english.added_success")
            ];
            } else {
                return redirect('hrm-payroll/create')
                ->with(
                    'status',
                    [
                        'success' => true,
                        'msg' => __("payroll_already_added_for_given_user")
                    ]
                );
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect('hrm-payroll')->with('status', $output);
    }



    public function createPayrollTransaction($employee, $request)
    {
        
        if (!auth()->user()->can('payroll.create')) {
            abort(403, 'Unauthorized action.');
        }$user_id = $request->session()->get('user.id');
        $system_settings_id = $request->session()->get('user.system_settings_id');
        $basic_salary =  $this->employeeUtil->num_uf($employee->basic_salary);
        $month_year_arr = explode('/', $request->input('month_year'));
        $month = $month_year_arr[0];
        //Update reference count
        $ob_ref_count = $this->employeeUtil->setAndGetReferenceCount('payroll', false, true);
        $total_allowance=$this->sum_deduction_allowance($request->input('allowances'));
        $total_deduction=$this->sum_deduction_allowance($request->input('deductions'));
        if(($basic_salary+$total_allowance)-$total_deduction >0){
        $transaction = HrmTransaction::create([
                    'campus_id' => $employee->campus_id,
                    'type' => 'pay_roll',
                    'status' => $request->input('status'),
                    'payroll_group_name' => $request->input('payroll_group_name'),
                    'allowances_amount'=>$this->employeeUtil->num_uf($request->input('allowance_amount')),
                    'deductions_amount'=>$this->employeeUtil->num_uf($request->input('deduction_amount')),
                    'payment_status' => 'due',
                    'ref_no'=> $this->employeeUtil->generateReferenceNumber('payroll', $ob_ref_count, $system_settings_id),
                    'session_id'=>$this->employeeUtil->getActiveSession(),
                    'month' => $month,
                    'employee_id' => $employee->id,
                    'transaction_date' =>$request->input('transaction_date'),
                    'final_total' => ($basic_salary+$total_allowance)-$total_deduction,
                    'basic_salary'=>$basic_salary,
                    'created_by' => $user_id,
                ]);
        
        $lines_formatted = $this->hrmAllowanceTransactionLine($request->input('allowances'), $transaction->id);
        $deduction_lines_formatted =  $this->hrmDeductionTransactionLine($request->input('deductions'), $transaction->id);
        
        if (!empty($lines_formatted)) {
            $transaction->allowance()->saveMany($lines_formatted);
        }
        if (!empty($lines_formatted)) {
            $transaction->deduction()->saveMany($deduction_lines_formatted);
        }
        return $transaction;
    }
    }


    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('payroll.update')) {
            abort(403, 'Unauthorized action.');
        }
        $transaction = HrmTransaction::where('id', $id)->where('payment_status', '=', 'due')
        ->with(['employee', 'campus','allowance','allowance.hrm_allowance','deduction','deduction.hrm_deduction'])
        ->first();
        
        $allowance_id_remover=[];
        if (!empty($transaction->allowance)) {
            foreach ($transaction->allowance as $key => $value) {
                array_push($allowance_id_remover, $value->allowance_id);
            }
        }
        $deduction_id_remover=[];
        if (!empty($transaction->deduction)) {
            foreach ($transaction->deduction as $key => $value) {
                array_push($deduction_id_remover, $value->deduction_id);
            }
        }
        $allowances = HrmAllowance::WhereNotIn('id', $allowance_id_remover)->get();
        $deductions = HrmDeduction::WhereNotIn('id', $deduction_id_remover)->get();
         
       
        return view('hrm.payroll.edit')->with(compact('transaction', 'deductions', 'allowances'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('payroll.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input=$request->input();
            DB::beginTransaction();

            $payroll = HrmTransaction::with(['allowance','deduction'])->where('payment_status', '!=', 'paid')->where('type', 'pay_roll')
            ->findOrFail($id);
            $payroll->final_total = $this->employeeUtil->num_uf($input['gross_final_total']);
            $payroll->deductions_amount = $this->employeeUtil->num_uf($input['deduction_amount']);
            $payroll->allowances_amount = $this->employeeUtil->num_uf($input['allowance_amount']);
            $payroll->save();
            $lines_formatted = $this->hrmAllowanceTransactionLine($input['allowances'], $id);
            
            $deduction_lines_formatted =  $this->hrmDeductionTransactionLine($input['deductions'], $id);
            if (!empty($lines_formatted)) {
                $payroll->allowance()->saveMany($lines_formatted);
            }
            if (!empty($deduction_lines_formatted)) {
                $payroll->deduction()->saveMany($deduction_lines_formatted);
            }
            DB::commit();
            $output = ['success' => true,
        'msg' => __("english.updated_success")
    ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
    
            $output = ['success' => false,
        'msg' => __("messages.something_went_wrong")
    ];
        }
        return redirect('hrm-payroll')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('payroll.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                //Begin transaction
                DB::beginTransaction();

                $output = $this->deleteSale($id);
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output['success'] = false;
                $output['msg'] = trans("messages.something_went_wrong");
            }

            return $output;
        }  
    }


    public function hrmAllowanceTransactionLine($allowances, $transaction_id)
    {
        $lines_formatted = [];
        if(!empty($allowances)){
        foreach ($allowances as $key => $value) {
            if (!empty($value['allowance_line_id'])) {
                if (!empty($value['is_enabled'])) {
                    $allowance_line=HrmAllowanceTransactionLine::where('hrm_transaction_id', $transaction_id)->where('id', $value['allowance_line_id'])->first();
                    $allowance_line->amount=$this->employeeUtil->num_uf($value['amount']);
                    $allowance_line->divider=$value['divider'];
                    $allowance_line->save();
                } else {
                    $delete_allowance_line=HrmAllowanceTransactionLine::where('hrm_transaction_id', $transaction_id)->where('id', $value['allowance_line_id'])->first();
                    $delete_allowance_line->delete();
                }
            } else {
                if (!empty($value['is_enabled'])) {
                    $line=[
            'is_enabled'=>$value['is_enabled'],
            'allowance_id'=>$value['allowance_id'],
            'divider'=>$value['divider'],
            'hrm_transaction_id'=>$transaction_id,
            'amount'=>$this->employeeUtil->num_uf($value['amount'])
        ];
                    $lines_formatted[] = new HrmAllowanceTransactionLine($line);
                }
            }
        }
    }

        return $lines_formatted;
    }
    public function hrmDeductionTransactionLine($deductions, $transaction_id)
    {
        $lines_formatted = [];
        $edit_lines_formatted=[];
        if(!empty($deductions)){
        foreach ($deductions as $key => $value) {
            if (!empty($value['deduction_line_id'])) {
                if (!empty($value['is_enabled'])) {
                    $deduction_line=HrmDeductionTransactionLine::where('hrm_transaction_id', $transaction_id)->where('id', $value['deduction_line_id'])->first();
                    $deduction_line->amount=$this->employeeUtil->num_uf($value['amount']);
                    $deduction_line->divider=$value['divider'];
                    $deduction_line->save();
                } else {
                    $delete_deduction_line=HrmDeductionTransactionLine::where('hrm_transaction_id', $transaction_id)->where('id', $value['deduction_line_id'])->first();
                    $delete_deduction_line->delete();
                }
            } else {
                if (!empty($value['is_enabled'])) {
                    $line=[
                'is_enabled'=>$value['is_enabled'],
                'divider'=>$value['divider'],
                'deduction_id'=>$value['deduction_id'],
                'hrm_transaction_id'=>$transaction_id,
                'amount'=>$this->employeeUtil->num_uf($value['amount'])
            ];
                    $lines_formatted[] = new HrmDeductionTransactionLine($line);
                }
            }
        }
    }

        return $lines_formatted;
    }



    /**
    * common function to get
    * list sell
    * @param int $system_settings_id
    *
    * @return object
    */
    public function getListPayrollTransaction()
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
                ->where('hrm_transactions.type', 'pay_roll')
                ->select(
                    'hrm_transactions.id',
                    'hrm_transactions.transaction_date',
                    'hrm_transactions.month',
                    'hrm_transactions.ref_no',
                    'hrm_transactions.payment_status',
                    'hrm_transactions.final_total',
                    'hrm_employees.father_name',
                    'hrm_employees.employeeID as employeeID',
                    'hrm_employees.status',
                    DB::raw("concat(sessions.title, ' - ' '(', sessions.status, ') ') as session_info"),
                    DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name"),
                    DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                    DB::raw('(SELECT SUM(IF(TP.is_return = 1,-1*TP.amount,TP.amount)) FROM hrm_transaction_payments AS TP WHERE
                        TP.hrm_transaction_id=hrm_transactions.id) as total_paid'),
                    'campus.campus_name as campus_name',
                );
                $permitted_campuses = auth()->user()->permitted_campuses();
                if ($permitted_campuses != 'all') {
                    $transactions->whereIn('hrm_transactions.campus_id', $permitted_campuses);
                }
        return $transactions->orderBy('hrm_transactions.transaction_date', 'desc');
    }
    public function getEmployeeDue($employee_id){
        $query = HrmEmployee::where('hrm_employees.id', $employee_id)
                            ->join('hrm_transactions AS t', 'hrm_employees.id', '=', 't.employee_id');
      
            $query->addSelect(
                DB::raw("SUM(IF(t.type = 'pay_roll' AND t.status = 'final', final_total, 0)) as total_fee"),
                DB::raw("SUM(IF(t.type = 'pay_roll' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)) as total_hrm_paid"),
            );
            
            

            //Query for opening balance details
            $query->addSelect(
                DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(amount) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)) as opening_balance_paid"),
                DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name"),
                

            );
            $query->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', final_total, 0)),0)-COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', final_total, 0)),0) -COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
                as total_due")
            ]);
            $query->addSelect([
                DB::raw("COALESCE(SUM(IF(t.type = 'pay_roll' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
                +COALESCE(SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM hrm_transaction_payments WHERE hrm_transaction_payments.hrm_transaction_id=t.id), 0)),0)
                 as total_paid")
            ]);
            $employee_details = $query->first();
            return $employee_details;
            

}

    public function check_deduction_allowance($input)
    {
        $allowances=$input['allowances'];
        $allowances_check=false;
        $deductions=$input['deductions'];
        $deductions_check=false;
        foreach ($allowances as $key => $value) {
            if (!empty($value['is_enabled'])) {
                $allowances_check =true;
            }
        }
        foreach ($deductions as $key => $value) {
            if (!empty($value['is_enabled'])) {
                $deductions_check =true;
            }
        }

        if ($allowances_check == false && $deductions_check == false) {
            return false;
        }
        return true;
    }
    public function sum_deduction_allowance($input)
    {
        $total=0;
        if(!empty($input)){
        foreach ($input as $key => $value) {
            if (!empty($value['is_enabled'])) {
                $amount=$this->employeeUtil->num_uf($value['amount']);
                $total +=$amount;
            }
        }
    }
        return $total;

    }

    
    /**
    * Function to delete sale
    *
    * @param int $business_id
    * @param int $transaction_id
    *
    * @return array
    */
    public function deleteSale( $transaction_id)
    {
      
        try {
            $output = ['success' => 0,
            'msg' => trans("messages.something_went_wrong")
            ];
            DB::beginTransaction();
        $transaction = HrmTransaction::where('id', $transaction_id)
                    ->whereIn('type', ['pay_roll'])
                    ->with(['allowance','deduction', 'payment_lines'])
                    ->first();

        if (!empty($transaction)) {
         
              
            $transaction_payments = $transaction->payment_lines;

        $transaction->delete();
        foreach ($transaction_payments as $payment) {
                    event(new HrmTransactionPaymentDeleted($payment));
                }
            
        }

        $output = [
                    'success' => true,
                    'msg' => __('english.sale_delete_success')
                ];
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => 0,
                        'msg' => trans("messages.something_went_wrong")
                        ];
            }

            return $output;
        
    }

    public function paySlip($id)
    {
        if (!auth()->user()->can('payroll.print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = ['success' => 0,
                'msg' => trans("messages.something_went_wrong")
                ];
                $transaction = HrmTransaction::where('id', $id)
        ->with(['employee','payment_lines','employee.designations', 'campus','allowance','allowance.hrm_allowance','deduction','deduction.hrm_deduction'])
        ->select(
            'hrm_transactions.*',
            DB::raw('(SELECT SUM(IF(TP.is_return = 1,-1*TP.amount,TP.amount)) FROM hrm_transaction_payments AS TP WHERE
                TP.hrm_transaction_id=hrm_transactions.id) as total_paid'),
        )->first();
                $student=[];
                $receipt = $this->receiptContent($transaction);

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => 0,
                        'msg' => trans("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
       
    }


    
    
    /**
     * Returns the content for the receipt
     *
     * @param  int  $business_id
     * @param  int  $location_id
     * @param  int  $transaction_id
     * @param string $printer_type = null
     *
     * @return array
     */
    private function receiptContent($transaction)
    {
        $output = ['is_enabled' => false,
                    'print_type' => 'browser',
                    'html_content' => null,
                    'printer_config' => [],
                    'data' => []
                ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];
        $employee_details=$this->getEmployeeDue($transaction->employee_id);

        $output['html_content'] = view('hrm.payroll.pay_slip', compact('transaction','employee_details'))->render();
        
        return $output;
    }
}
