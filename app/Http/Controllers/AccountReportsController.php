<?php

namespace App\Http\Controllers;


use App\Models\Account;
use App\Models\AccountTransaction;
use App\Utils\FeeTransactionUtil;
use App\Utils\HrmTransactionUtil;
use App\Utils\ExpenseTransactionUtil;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Campus;

class AccountReportsController extends Controller
{
     /**
     * All Utils instance.
     *
     */
    protected $fee_transactionUtil;
    protected $hrmTransactionUtil;
    protected $expenseTransactionUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FeeTransactionUtil $fee_transactionUtil,HrmTransactionUtil $hrmTransactionUtil,ExpenseTransactionUtil $expenseTransactionUtil)
    {
        $this->fee_transactionUtil = $fee_transactionUtil;
        $this->hrmTransactionUtil = $hrmTransactionUtil;
        $this->expenseTransactionUtil = $expenseTransactionUtil;
    }
     public function balanceSheet()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $end_date = !empty(request()->input('end_date')) ? $this->fee_transactionUtil->uf_date(request()->input('end_date')) : \Carbon::now()->format('Y-m-d');
             $campus_id = !empty(request()->input('campus_id')) ? request()->input('campus_id') : null;

            $fee_details = $this->fee_transactionUtil->getFeeTotals(
                null,
                $end_date,
                $campus_id
            );
            $hrm_details = $this->hrmTransactionUtil->getPayRollTotals(
                null,
                $end_date,
                $campus_id
            );
            $expense_details = $this->expenseTransactionUtil->getExpenseTotals(
                null,
                $end_date,
                $campus_id
            );

            $account_details = $this->getAccountBalance($end_date, $campus_id);


            $output = [
                'expense_due' => $expense_details['total_expense_due'],
                'fee_due' => $fee_details['total_fee_due'],
                'payroll_due' => $hrm_details['total_hrm_due'],
                'account_balances' => $account_details,
                'capital_account_details' => null
            ];

            return $output;
        }

        $campuses=Campus::forDropdown();

        return view('account_reports.balance_sheet')->with(compact('campuses'));
    }
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function trialBalance()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
       // dd($this->fee_transactionUtil->getFeeTotals());
       // dd($this->hrmTransactionUtil->getPayRollTotals());
       // dd($this->expenseTransactionUtil->getExpenseTotals());

        if (request()->ajax()) {
            $end_date = !empty(request()->input('end_date')) ? $this->fee_transactionUtil->uf_date(request()->input('end_date')) : \Carbon::now()->format('Y-m-d');
             $campus_id = !empty(request()->input('campus_id')) ? request()->input('campus_id') : null;

            $fee_details = $this->fee_transactionUtil->getFeeTotals(
                null,
                $end_date,
                $campus_id
            );
            $hrm_details = $this->hrmTransactionUtil->getPayRollTotals(
                null,
                $end_date,
                $campus_id
            );
            $expense_details = $this->expenseTransactionUtil->getExpenseTotals(
                null,
                $end_date,
                $campus_id
            );

            $account_details = $this->getAccountBalance($end_date, $campus_id);


            $output = [
                'expense_due' => $expense_details['total_expense_due'],
                'fee_due' => $fee_details['total_fee_due'],
                'payroll_due' => $hrm_details['total_hrm_due'],
                'account_balances' => $account_details,
                'capital_account_details' => null
            ];

            return $output;
        }

        $campuses=Campus::forDropdown();

        return view('account_reports.trial_balance')->with(compact('campuses'));
    }
    private function getAccountBalance($end_date, $campus_id = null)
    {
        $query = Account::leftjoin(
            'account_transactions as AT',
            'AT.account_id',
            '=',
            'accounts.id'
        )
                                // ->NotClosed()
                                ->whereNull('AT.deleted_at')
                                ->whereDate('AT.operation_date', '<=', $end_date);

       
//Filter by the campus
$permitted_campuses = auth()->user()->permitted_campuses();
if ($permitted_campuses != 'all') {
 $query->whereIn('accounts.campus_id', $permitted_campuses);
}
if (!empty($campus_id)) {
    $query->where('accounts.campus_id', $campus_id);
  }
        $account_details = $query->select(['name',
                                        DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")])
                                ->groupBy('accounts.id')
                                ->get()
                                ->pluck('balance', 'name');

        return $account_details;
    }

}