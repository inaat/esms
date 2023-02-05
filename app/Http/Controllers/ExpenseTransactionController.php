<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\ExpenseCategory;
use App\Models\ExpenseTransactionPayment;
use App\Models\ExpenseTransaction;
use App\Models\HumanRM\HrmEmployee;
use App\Utils\ExpenseTransactionUtil;
use App\Events\ExpenseTransactionPaymentAdded;
use App\Events\ExpenseTransactionPaymentDeleted;
use DB;
use App\Models\Vehicle;

use Yajra\DataTables\Facades\DataTables;

class ExpenseTransactionController extends Controller
{
    protected $expenseTransactionUtil;

    /**
    * Constructor
    *
    */
    public function __construct(ExpenseTransactionUtil $expenseTransactionUtil)
    {
        $this->expenseTransactionUtil = $expenseTransactionUtil;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('expense.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $transactions = ExpenseTransaction::leftJoin('hrm_employees', 'expense_transactions.expense_for', '=', 'hrm_employees.id')

            ->leftJoin('users as u', 'expense_transactions.created_by', '=', 'u.id')
            ->leftJoin('vehicles as v', 'expense_transactions.vehicle_id', '=', 'v.id')
       ->leftJoin('expense_categories', 'expense_transactions.expense_category_id', '=', 'expense_categories.id')
       ->join(
           'campuses AS campus',
           'expense_transactions.campus_id',
           '=',
           'campus.id'
       )
       ->where('expense_transactions.status', 'final')
       ->select(
           'expense_transactions.id',
           'expense_transactions.transaction_date',
           'expense_transactions.additional_notes',
           'expense_transactions.ref_no',
           'expense_categories.name as category_name',
           'expense_transactions.payment_status',
           'expense_transactions.final_total',
           'hrm_employees.status',
           // DB::raw("concat(sessions.title, ' - ' '(', sessions.status, ') ') as session_info"),
           DB::raw("CONCAT(COALESCE(v.name,''),'  ',COALESCE(v.vehicle_number,'')) as vehicle_name"),
           DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name"),
           DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
           DB::raw('(SELECT SUM(IF(TP.is_return = 1,-1*TP.amount,TP.amount)) FROM expense_transaction_payments AS TP WHERE
               TP.expense_transaction_id=expense_transactions.id) as total_paid'),
           'campus.campus_name as campus_name',
       )->orderBy('expense_transactions.transaction_date', 'desc');

       $permitted_campuses = auth()->user()->permitted_campuses();
       if ($permitted_campuses != 'all') {
           $transactions->whereIn('expense_transactions.campus_id', $permitted_campuses);
       }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $transactions->where('expense_transactions.campus_id', $campus_id);
                }
            }
            if (request()->has('expense_category_id')) {
                $expense_category_id = request()->get('expense_category_id');
                if (!empty($expense_category_id)) {
                    $transactions->where('expense_transactions.expense_category_id', $expense_category_id);
                }
            }
            if (request()->has('vehicle_id')) {
                $vehicle_id = request()->get('vehicle_id');
                if (!empty($vehicle_id)) {
                    $transactions->where('expense_transactions.vehicle_id', $vehicle_id);
                }
            }
            if (request()->has('payment_status')) {
                $payment_status = request()->get('payment_status');
                if (!empty($payment_status)) {
                    $transactions->where('expense_transactions.payment_status', $payment_status);
                }
            }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $transactions->whereDate('transaction_date', '>=', $start)
                        ->whereDate('transaction_date', '<=', $end);
            }


            $datatable = Datatables::of($transactions)
            ->addColumn(
                'action',
                function ($row) {
                    $html= '<div class="dropdown">
                     <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                     <ul class="dropdown-menu" style="">';
                    if (auth()->user()->can('expense.update')) {
                        if ($row->payment_status == "due") {
                            $html.='<li><a class="dropdown-item "href="' . action('ExpenseTransactionController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                        }
                    }
                    if (auth()->user()->can('expense.expense_payment')) {
                        if ($row->payment_status != "paid" && (auth()->user()->can("sell.create") || auth()->user()->can("direct_sell.access")) && auth()->user()->can("sell.payments")) {
                            $html .= '<li><a href="' . action('ExpenseTransactionPaymentController@addPayment', [$row->id]) . '" class="dropdown-item add_payment_modal"><i class="fas fa-money-bill-alt"></i> ' . __("english.add_payment") . '</a></li>';
                        }
                        $html .= '<li><a href="' . action('ExpenseTransactionPaymentController@show', [$row->id]) . '" class="dropdown-item view_payment_modal"><i class="fas fa-money-bill-alt"></i> ' . __("english.view_payments") . '</a></li>';
                        if (auth()->user()->can('expense.delete')) {
                        }
                        if ($row->payment_status == "due") {
                            $html .= '<li>
                        <a href="'.action('ExpenseTransactionController@destroy', [$row->id]).'" class="delete-hrm_transaction"><i class="fas fa-trash"></i>'.__("messages.delete").'</a>
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
                    $payment_status = ExpenseTransaction::getPaymentStatus($row);
                    return (string) view('expense.partials.payment_status', ['payment_status' => $payment_status, 'id' => $row->id]);
                }
            )
            ->editColumn('employee_name', function ($row) {
                return ucwords($row->employee_name);
            })



            ->addColumn('total_remaining', function ($row) {
                $total_remaining =  $row->final_total - $row->total_paid;
                $total_remaining_html = '<span class="payment_due" data-orig-value="' . $total_remaining . '">' . $this->expenseTransactionUtil->num_f($total_remaining, true) . '</span>';


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

            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('hrm_employees.first_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('category_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('expense_categories.name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('vehicle_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(COALESCE(v.name,''),'  ',COALESCE(v.vehicle_number,'')) like ?", ["%{$keyword}%"]);
            })
            ->removeColumn('id');



            $rawColumns = ['action','campus_name','ref_no','payment_status','final_total','total_remaining','total_paid'];

            return $datatable->rawColumns($rawColumns)
              ->make(true);
        }
        $campuses=Campus::forDropdown();
        $vehicles=Vehicle::forDropdown();
        $expense_categories=ExpenseCategory::forDropdown();


        return view('expense.index')->with(compact('campuses', 'vehicles', 'expense_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('expense.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $expense_categories=ExpenseCategory::forDropdown();
        $employees=HrmEmployee::forDropdown();
        $payment_types = $this->expenseTransactionUtil->payment_types();
        $accounts = $this->expenseTransactionUtil->accountsDropdown(1, 1, false, false, true, true);
        $payment_line = new ExpenseTransactionPayment();

        $payment_line->method = 'cash';
        $payment_line->paid_on = \Carbon::now()->toDateTimeString();
        $vehicles=Vehicle::forDropdown();

        return view('expense.create')->with(compact('campuses', 'vehicles', 'expense_categories', 'employees', 'payment_types', 'accounts', 'payment_line'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('expense.create')) {
            abort(403, 'Unauthorized action.');
        }
        $output = ['success' => 0,
        'msg' => __('english.something_went_wrong')
    ];
        try { //dd($request);
            //Validate document size
            $request->validate([
                'document' => 'file|max:'. (config('constants.document_size_limit') / 1000)
            ]);
            $user_id = $request->session()->get('user.id');

            DB::beginTransaction();
            $this->expenseTransactionUtil->createExpense($request, $user_id, $format_data = true);
            DB::commit();

            $output = ['success' => 1,
                            'msg' => __('english.expense_add_success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' => __('english.something_went_wrong')
                        ];
        }



        return redirect('expenses')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('expense.update')) {
            abort(403, 'Unauthorized action.');
        }

        $expense = ExpenseTransaction::where('id', $id)
                                ->first();
        $campuses=Campus::forDropdown();
        $expense_categories=ExpenseCategory::forDropdown();
        $employees=HrmEmployee::forDropdown();
        $vehicles=Vehicle::forDropdown();

        return view('expense.edit')
            ->with(compact('expense', 'expense_categories', 'vehicles', 'campuses', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('expense.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            //Validate document size
            $request->validate([
                'document' => 'file|max:'. (config('constants.document_size_limit') / 1000)
            ]);
            $expense = $this->expenseTransactionUtil->updateExpense($request, $id);


            $output = ['success' => 1,
                            'msg' => __('english.expense_update_success')
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' => __('english.something_went_wrong')
                        ];
        }

        return redirect('expenses')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('expense.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                //Begin transaction
                DB::beginTransaction();

                $output = $this->expenseTransactionUtil->deleteExpense($id);

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
}
