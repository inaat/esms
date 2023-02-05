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

class IncomeReportController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('income_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

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
        $pdf_name='income'.'.pdf';
        $input = $request->input();

        $_date=explode(' - ', $input['month_list_filter_date_range']);
        $start_date=Carbon::parse($_date[0])->format('Y-m-d');
        $end_date=Carbon::parse($_date[1])->format('Y-m-d');
        $campus_id=$input['campus_id'];
        $student_payments=$this->__payment($campus_id, $start_date, $end_date);
        $hrm_payments=$this->__hrmPayment($campus_id, $start_date, $end_date);
        $expenses_payments=$this->__expensesPayment($campus_id, $start_date, $end_date);

        $pdf =  config('constants.mpdf');
        if ($pdf) {
            $data=[
                'campus_id'=>$campus_id,
                 'student_payments'=>$student_payments,
                  'hrm_payments'=>$hrm_payments, 
                  'expenses_payments'=>$expenses_payments

            ];
            $this->reportPDF('samplereport.css', $data, 'MPDF.income-report','view','a4');

           
        } else {
            $snappy  = \WPDF::loadView('Report.income.income-report', compact('campus_id', 'student_payments', 'hrm_payments', 'expenses_payments'));
            $headerHtml = view()->make('common._header')->render();
            $footerHtml = view()->make('common._footer')->render();
            $snappy->setOption('header-html', $headerHtml);
            $snappy->setOption('footer-html', $footerHtml);
            $snappy->setPaper('a4')->setOption('orientation', 'portrait')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
            $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
            return $snappy->stream();
        }
    }

    private function __payment($campus_id, $start_date, $end_date)
    {
        $query = FeeTransactionPayment::with(['campus','student'])->whereNull('fee_transaction_id');
        if (!empty($campus_id)) {
            $query->where('campus_id', '=', $campus_id);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereDate('paid_on', '>=', $start_date)
                    ->whereDate('paid_on', '<=', $end_date);
        }

        return $query->get();
        ;
    }
    private function __hrmPayment($campus_id, $start_date, $end_date)
    {
        $query = HrmTransactionPayment::with(['campus','employee'])->where('hrm_transaction_id', '!=', null);
        if (!empty($campus_id)) {
            $query->where('campus_id', '=', $campus_id);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereDate('paid_on', '>=', $start_date)
                    ->whereDate('paid_on', '<=', $end_date);
        }
        return $query->get();
        ;
    }
    private function __expensesPayment($campus_id, $start_date, $end_date)
    {
        $query = ExpenseTransactionPayment::with(['campus','employee','expense_transaction','expense_transaction.expenseCategory'])->where('expense_transaction_id', '!=', null);
        if (!empty($campus_id)) {
            $query->where('campus_id', '=', $campus_id);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereDate('paid_on', '>=', $start_date)
                    ->whereDate('paid_on', '<=', $end_date);
        }
        return $query->get();
        ;
    }
}
