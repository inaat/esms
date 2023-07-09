<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Models\ExpenseCategory;
use App\Models\Campus;
use App\Utils\ExpenseTransactionUtil;
use App\Utils\HrmTransactionUtil;

class ReportController extends Controller
{
    /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
    public function __construct(ExpenseTransactionUtil $expenseTransactionUtil,  HrmTransactionUtil $hrmTransactionUtil)
    {
        $this->expenseTransactionUtil= $expenseTransactionUtil;
        $this->hrmTransactionUtil = $hrmTransactionUtil;

    }

/**
     * Shows expense report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getExpenseReport(Request $request)
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
        $values = [];
        $labels = [];
        foreach ($expenses as $expense) {
            $values[] = [
                'name'=>!empty($expense->category) ? $expense->category : __('english.others'),
                'y'=>(float) $expense->total_expense
            ] ;
            $labels[] = !empty($expense->category) ? $expense->category : __('english.others');
        }
         $values[] = [
                'name'=>'HRM',
                'y'=>(float) $hrm
            ] ;
            $labels[] = __('english.hrm');
        // $chart = new CommonChart;
        // $chart->labels($labels)
        //     ->title(__('report.expense_report'))
        //     ->dataset(__('report.total_expense'), 'column', $values);

        $categories = ExpenseCategory::pluck('name', 'id');
        
        $campuses = Campus::forDropdown();
        $labels= json_encode($labels) ;
        $values= json_encode($values) ;
        return view('Report.expense.expense_report')
                    ->with(compact('labels','values','categories', 'campuses', 'expenses'));
    }

}