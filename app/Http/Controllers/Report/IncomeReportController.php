<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ClassSection;
use App\Models\FeeTransactionPayment;
use App\Models\Classes;
use App\Models\Campus;
use App\Utils\Util;
use File;
use Carbon;
use App\Models\ExpenseTransactionPayment;
use App\Models\HumanRM\HrmTransactionPayment;

use DB;
use App\Utils\StudentUtil;
use App\Utils\EmployeeUtil;
use App\Utils\FeeTransactionUtil;
use App\Utils\HrmTransactionUtil;
use App\Utils\ExpenseTransactionUtil;

class IncomeReportController extends Controller
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
        if (!auth()->user()->can('income_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses = Campus::forDropdown();

        return view('Report.income.index')->with(compact('campuses'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('income_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        // try {
        if (File::exists(public_path('uploads/pdf/income.pdf'))) {
            File::delete(public_path('uploads/pdf/income.pdf'));
        }
        $pdf_name = 'income' . '.pdf';
        $input = $request->input();

        $_date = explode(' - ', $input['month_list_filter_date_range']);
        $start_date = Carbon::parse($_date[0])->format('Y-m-d');
        $end_date = Carbon::parse($_date[1])->format('Y-m-d');
        $campus_id = $input['campus_id'];
        $student_payments = $this->__payment($campus_id, $start_date, $end_date);
        $hrm_payments = $this->__hrmPayment($campus_id, $start_date, $end_date);
        $expenses_payments = $this->__expensesPayment($campus_id, $start_date, $end_date);

        $pdf = config('constants.mpdf');
        if ($pdf) {
            $data = [
                'campus_id' => $campus_id,
                'student_payments' => $student_payments,
                'hrm_payments' => $hrm_payments,
                'expenses_payments' => $expenses_payments

            ];
            $this->reportPDF('samplereport.css', $data, 'MPDF.income-report', 'view', 'a4');


        } else {
            $snappy = \WPDF::loadView('Report.income.income-report', compact('campus_id', 'student_payments', 'hrm_payments', 'expenses_payments'));
            $headerHtml = view()->make('common._header')->render();
            $footerHtml = view()->make('common._footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            $snappy->setPaper('a4')->setOption('orientation', 'portrait')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
            $snappy->save('uploads/pdf/' . $pdf_name); //save pdf file
            return $snappy->stream();
        }
    }

    private function __payment($campus_id, $start_date, $end_date)
    {
        $query = FeeTransactionPayment::with(['campus', 'student', 'fee_transaction'])->whereNotNull('fee_transaction_id');
        if (!empty($campus_id)) {
            $query->where('campus_id', '=', $campus_id);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereDate('paid_on', '>=', $start_date)
                ->whereDate('paid_on', '<=', $end_date);
        }

        return $query->orderBy('method')->get();

    }
    private function __hrmPayment($campus_id, $start_date, $end_date)
    {
        $query = HrmTransactionPayment::with(['campus', 'employee', 'payment_account'])->where('hrm_transaction_id', '!=', null);
        if (!empty($campus_id)) {
            $query->where('campus_id', '=', $campus_id);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereDate('paid_on', '>=', $start_date)
                ->whereDate('paid_on', '<=', $end_date);
        }
        return $query->orderBy('method')->get();
        ;
    }
    private function __expensesPayment($campus_id, $start_date, $end_date)
    {
        $query = ExpenseTransactionPayment::with(['campus', 'payment_account', 'employee', 'expense_transaction', 'expense_transaction.expenseCategory'])->where('expense_transaction_id', '!=', null);
        if (!empty($campus_id)) {
            $query->where('campus_id', '=', $campus_id);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereDate('paid_on', '>=', $start_date)
                ->whereDate('paid_on', '<=', $end_date);
        }
        return $query->orderBy('method')->get();
        ;
    }


    public function FeePaidToday(Request $request)
    {
        if (!auth()->user()->can('income_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $start = request()->start;
            $end = request()->end;
            $campus_id = request()->campus_id;

            $student_payments = $this->__payment($campus_id, $start, $end);
            //dd($student_payments);
            return view('Report.income.fee_paid_today')->with(compact('student_payments'));
        }
    }
    public function TransportFeePaidToday(Request $request)
    {
        if (!auth()->user()->can('income_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $start = request()->start;
            $end = request()->end;
            $campus_id = request()->campus_id;

            $student_payments = $this->__payment($campus_id, $start, $end);
            //dd($student_payments);
            return view('Report.income.fee_transport_paid_today')->with(compact('student_payments'));
        }
    }
    public function ExpensesPaidToday(Request $request)
    {
        if (!auth()->user()->can('income_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $start = request()->start;
            $end = request()->end;
            $campus_id = request()->campus_id;

            $student_payments = $this->__payment($campus_id, $start, $end);
            //dd($student_payments);
            return view('Report.income.fee_paid_today')->with(compact('student_payments'));
        }
    }



    public function IncomeSummaryCreate()
    {

        // if (request()->ajax()) {
        //     $start = request()->start;
        //     $end = request()->end;
        //     $campus_id = request()->campus_id;

         $campuses = Campus::forDropdown();
        //     $total_paid_amount = $this->feeTransactionUtil->getTotalFeePaid($start, $end, null, $campus_id);
        //     $total_transport_paid_amount = $this->feeTransactionUtil->getTotalTransportPaid($start, $end, null, $campus_id);
        //     $total_expense = $this->expenseTransactionUtil->getTotalExpense($start, $end, $campus_id) + $this->hrmTransactionUtil->getTotalHrm($start, $end, $campus_id);
        //     $total_hrm_paid_amount = $this->expenseTransactionUtil->getTotalExpense(\Carbon::now()->startOfMonth()->format('Y-m-d'), \Carbon::now()->lastOfMonth()->format('Y-m-d'), $campus_id) + $this->hrmTransactionUtil->getTotalHrm(\Carbon::now()->startOfMonth()->format('Y-m-d'), \Carbon::now()->lastOfMonth()->format('Y-m-d'), $campus_id);
        // }
        return view('Report.income.income_summary')->with(compact('campuses'));

    }


    public function getIncomeSummaryReport(Request $request)
    {
        if (!auth()->user()->can('expense_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $filters = $request->only(['category', 'campus_id']);
       $campus_id = request()->campus_id;
        $date_range = $request->input('list_filter_date_range');
        if (!empty($date_range)) {
            $date_range_array = explode(' - ' , $date_range);
            //dd($date_range_array);

            $filters['start_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[0]));
            $filters['end_date'] = $this->expenseTransactionUtil->uf_date(trim($date_range_array[1]));
        } else {
            $filters['start_date'] = \Carbon::now()->startOfMonth()->format('Y-m-d');
            $filters['end_date'] = \Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        
        $expenses = $this->expenseTransactionUtil->getExpenseReport( $filters);
        $hrm=$this->hrmTransactionUtil->getTotalHrm( $filters['start_date'], $filters['end_date'], $campus_id);
        
       // return  $output;
    }
}