<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\AccountType;
//use App\TransactionPayment;
use App\Utils\Util;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Media;
use Carbon\Carbon;
use PDF;
use File;

class AccountController extends Controller
{
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');
        if (request()->ajax()) {
            $accounts = Account::leftjoin('account_transactions as AT', function ($join) {
                $join->on('AT.account_id', '=', 'accounts.id');
                $join->whereNull('AT.deleted_at');
            })
            ->leftjoin(
                'account_types as ats',
                'accounts.account_type_id',
                '=',
                'ats.id'
            )
            ->leftjoin(
                'account_types as pat',
                'ats.parent_account_type_id',
                '=',
                'pat.id'
            )
            ->leftJoin('users AS u', 'accounts.created_by', '=', 'u.id')
                                ->where('accounts.system_settings_id', $system_settings_id)
                                ->select(['accounts.name', 'accounts.account_number', 'accounts.note', 'accounts.id', 'accounts.account_type_id',
                                    'ats.name as account_type_name',
                                    'pat.name as parent_account_type_name',
                                    'is_closed', DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance"),
                                    DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by")
                                    ])
                                ->groupBy('accounts.id');

            $is_closed = request()->input('account_status') == 'closed' ? 1 : 0;
            $accounts->where('is_closed', $is_closed);
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
             $accounts->whereIn('accounts.campus_id', $permitted_campuses);
            }
            return DataTables::of($accounts)
                            ->addColumn(
                                'action',
                                '<div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> @lang("lang.actions")</button>
                                <ul class="dropdown-menu" style="">
                                    <li><a class="dropdown-item  btn-modal" data-href="{{action(\'AccountController@edit\',[$id])}}" data-container=".account_model"><i class="bx bxs-edit f-16 mr-15 "></i> @lang("english.edit")</a>
                                    </li>
                                    @if($is_closed == 0)
                                    <li><a href="{{action(\'AccountController@getAccountLedger\',[$id])}}" class="dropdown-item"><i class="bx bx-book"></i> @lang("english.account_ledger")</a>
                                    <li><a href="{{action(\'AccountController@show\',[$id])}}" class="dropdown-item"><i class="bx bx-book"></i> @lang("english.account_book")</a>
                                    </li>
                                    <li><a class="dropdown-item  btn-modal" data-href="{{action(\'AccountController@getFundTransfer\',[$id])}}" data-container=".view_modal"><i class="bx bx-transfer f-16 mr-15 "></i> @lang("english.fund_transfer")</a>
                                    </li>
                                    <li><a class="dropdown-item  btn-modal" data-href="{{action(\'AccountController@getDeposit\',[$id])}}" data-container=".view_modal"><i class="bx bx-money f-16 mr-15 "></i> @lang("english.deposit")</a>
                                    </li>
                                    <li><a class="dropdown-item  btn-modal" data-href="{{action(\'AccountController@getDebit\',[$id])}}" data-container=".view_modal"><i class="bx bx-money f-16 mr-15 "></i> @lang("english.debit")</a>
                                    </li>
                                    <li> <button data-url="{{action(\'AccountController@close\',[$id])}}" class="dropdown-item  btn btn-sm btn-danger close_account"><i class="fa fa-power-off"></i> @lang("lang.close")</button>
                                    </li>
                                    @elseif($is_closed == 1)
                                          <li>  <button data-url="{{action(\'AccountController@activate\',[$id])}}" class=" dropdown-item btn btn-sm btn-success activate_account"><i class="fa fa-power-off"></i> @lang("lang.activate")</button>
                                     </li>
                                          @endif
                                </ul>
                            </div>')
                            ->editColumn('name', function ($row) {
                                if ($row->is_closed == 1) {
                                    return $row->name . ' <small class="label pull-right bg-red no-print">' . __("english.closed") . '</small><span class="print_section">(' . __("english.closed") . ')</span>';
                                } else {
                                    return $row->name;
                                }
                            })
                            ->editColumn('balance', function ($row) {
                                return '<span class="display_currency" data-currency_symbol="true">' . $row->balance . '</span>';
                            })
                            ->editColumn('account_type', function ($row) {
                                $account_type = '';
                                if (!empty($row->account_type->parent_account)) {
                                    $account_type .= $row->account_type->parent_account->name . ' - ';
                                }
                                if (!empty($row->account_type)) {
                                    $account_type .= $row->account_type->name;
                                }
                                return $account_type;
                            })
                            ->editColumn('parent_account_type_name', function ($row) {
                                $parent_account_type_name = empty($row->parent_account_type_name) ? $row->account_type_name : $row->parent_account_type_name;
                                return $parent_account_type_name;
                            })
                            ->editColumn('account_type_name', function ($row) {
                                $account_type_name = empty($row->parent_account_type_name) ? '' : $row->account_type_name;
                                return $account_type_name;
                            })
                            ->removeColumn('id')
                            ->removeColumn('is_closed')
                            ->rawColumns(['action', 'balance', 'name'])
                            ->make(true);
        }


        $account_types = AccountType::where('system_settings_id', $system_settings_id)
                                     ->whereNull('parent_account_type_id')
                                     ->with(['sub_types'])
                                     ->get();

        return view('account.index')
                ->with(compact('account_types'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');
        $account_types = AccountType::where('system_settings_id', $system_settings_id)
                                     ->whereNull('parent_account_type_id')
                                     ->with(['sub_types'])
                                     ->get();

        return view('account.create')
                ->with(compact('account_types'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'account_number', 'note', 'account_type_id']);
                $system_settings_id = $request->session()->get('user.system_settings_id');
                $user_id = $request->session()->get('user.id');
                $input['system_settings_id'] = $system_settings_id;
                $input['created_by'] = $user_id;
                $permitted_campuses = auth()->user()->permitted_campuses();
                if ($permitted_campuses != 'all') {
                    $input['campus_id'] = auth()->user()->campus_id;

                }
                $account = Account::create($input);

                //Opening Balance
                $opening_bal = $request->input('opening_balance');

                if (!empty($opening_bal)) {
                    $ob_transaction_data = [
                        'amount' =>$this->commonUtil->num_uf($opening_bal),
                        'account_id' => $account->id,
                        'type' => 'credit',
                        'sub_type' => 'opening_balance',
                        'operation_date' => \Carbon::now(),
                        'created_by' => $user_id
                    ];

                    AccountTransaction::createAccountTransaction($ob_transaction_data);
                }
                
                $output = ['success' => true,
                            'msg' => __("english.account_created_success")
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                            ];
            }

            return $output;
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = request()->session()->get('user.system_settings_id');

        if (request()->ajax()) {
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            $accounts = AccountTransaction::join(
                'accounts as A',
                'account_transactions.account_id',
                '=',
                'A.id'
            )
            ->leftJoin('fee_transaction_payments AS tp', 'account_transactions.transaction_payment_id', '=', 'tp.id')
            ->leftJoin('hrm_transaction_payments AS hrm_tp', 'account_transactions.hrm_transaction_payment_id', '=', 'hrm_tp.id')
            ->leftJoin('users AS u', 'account_transactions.created_by', '=', 'u.id')
            ->leftJoin('students AS c', 'tp.payment_for', '=', 'c.id')
            ->leftJoin('hrm_employees AS em', 'hrm_tp.payment_for', '=', 'em.id')
                            ->where('A.system_settings_id', $system_settings_id)
                            ->where('A.id', $id)
                            ->with(['transfer_transaction', 'media', 'transfer_transaction.media','expenseTransactionPayment','expenseTransactionPayment.expense_transaction','expenseTransactionPayment.expense_transaction.expenseCategory'])
                            ->select(['account_transactions.type', 'account_transactions.amount', 'operation_date',
                                'sub_type', 'transfer_transaction_id',
                                DB::raw('(SELECT SUM(IF(AT.type="credit", AT.amount, -1 * AT.amount)) from account_transactions as AT WHERE  AT.account_id  =account_transactions.account_id AND AT.deleted_at IS NULL AND AT.id <= account_transactions.id) as balance'),
                                'account_transactions.transaction_id',
                                'account_transactions.id',
                                'account_transactions.expense_transaction_payment_id',
                                'account_transactions.hrm_transaction_payment_id',
                                'account_transactions.transaction_payment_id',
                                'account_transactions.note',
                                'tp.payment_ref_no',
                                'hrm_tp.payment_ref_no as payment_ref_no_pay_roll',
                                DB::raw("CONCAT(COALESCE(c.first_name, ''),' ',COALESCE(c.last_name,''),' (',COALESCE(c.roll_no,''),')') as payment_for"),
                                DB::raw("CONCAT(COALESCE(em.first_name, ''),' ',COALESCE(em.last_name,''),' (',COALESCE(em.employeeID,''),')') as payment_for_pay_roll"),
                                DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by")
                                ])
                             ->groupBy('account_transactions.id')
                             ->orderBy('account_transactions.operation_date', 'asc');
                        
            if (!empty(request()->input('type'))) {
                $accounts->where('account_transactions.type', request()->input('type'));
            }

            
            if (!empty($start_date) && !empty($end_date)) {
                $accounts->whereDate('operation_date', '>=', $start_date)
                        ->whereDate('operation_date', '<=', $end_date);
            }
            return DataTables::of($accounts)
                            ->addColumn('debit', function ($row) {
                                if ($row->type == 'debit') {
                                    return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                                }
                                return '';
                            })
                            ->addColumn('credit', function ($row) {
                                if ($row->type == 'credit') {
                                    return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                                }
                                return '';
                            })
                            ->editColumn('balance', function ($row) {
                                return '<span class="display_currency" data-currency_symbol="true">' . $row->balance . '</span>';
                            })
                            ->editColumn('operation_date', function ($row) {
                                return $this->commonUtil->format_date($row->operation_date, true);
                            })
                            ->editColumn('sub_type', function ($row) {
                                return $this->__getPaymentDetails($row);
                            })
                            ->editColumn('action', function ($row) {
                                $action = '';
                                if ($row->sub_type == 'deposit' || $row->sub_type == 'debit') {
                                    $action = '<button type="button" class="btn btn-danger btn-sm delete_account_transaction" data-href="' . action('AccountController@destroyAccountTransaction', [$row->id]) . '"><i class="fadeIn animated bx bx-trash"></i>' . __('english.delete') . '</button>';

                                }
                                if($row->sub_type == 'fund_transfer'  && $row->type == 'debit'  ){
                                    $action = '<button type="button" class="btn btn-danger btn-sm delete_account_transaction" data-href="' . action('AccountController@destroyAccountTransaction', [$row->id]) . '"><i class="fadeIn animated bx bx-trash"></i>' . __('english.delete') . '</button>';

                                }
                                if (auth()->user()->can('edit_account_transaction')) {
                                    if ($row->sub_type == 'deposit' || $row->sub_type == 'debit' || $row->sub_type == 'opening_balance') {
                                    }
                                  
                                }
                                if (!empty($row->media->first()) || (!empty($row->transfer_transaction && !empty($row->transfer_transaction->media->first()) ))) {
                                    $display_url = !empty($row->media->first()) ? $row->media->first()->display_url : $row->transfer_transaction->media->first()->display_url;

                                    $display_name = !empty($row->media->first()) ? $row->media->first()->display_name : $row->transfer_transaction->media->first()->display_name;

                                    $action .= '&nbsp; <a class="btn btn-success btn-sm" href="' . $display_url . '" download="' . $display_name . '"><i class="bx bx-download"></i> ' . __('english.download_document') . '</a>';
                                }
                                return $action;
                            })
                            ->removeColumn('id')
                            ->removeColumn('is_closed')
                            ->rawColumns(['credit', 'debit', 'balance', 'sub_type', 'action'])
                            ->make(true);
        }
        $account = Account::where('system_settings_id', $system_settings_id)
                        ->with(['account_type', 'account_type.parent_account'])
                        ->findOrFail($id);

        return view('account.show')
                ->with(compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $system_settings_id = request()->session()->get('user.system_settings_id');
            $account = Account::where('system_settings_id', $system_settings_id)
                                ->find($id);

            $account_types = AccountType::where('system_settings_id', $system_settings_id)
                                     ->whereNull('parent_account_type_id')
                                     ->with(['sub_types'])
                                     ->get();
           
            return view('account.edit')
                ->with(compact('account', 'account_types'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'account_number', 'note', 'account_type_id']);

                $system_settings_id = request()->session()->get('user.system_settings_id');
                $account = Account::where('system_settings_id', $system_settings_id)
                                                    ->findOrFail($id);
                $account->name = $input['name'];
                $account->account_number = $input['account_number'];
                $account->note = $input['note'];
                $account->account_type_id = $input['account_type_id'];
                $account->save();

                $output = ['success' => true,
                                'msg' => __("english.account_updated_success")
                                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }
            
            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroyAccountTransaction($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $system_settings_id = request()->session()->get('user.system_settings_id');

                $account_transaction = AccountTransaction::findOrFail($id);
                
                if (in_array($account_transaction->sub_type, ['fund_transfer', 'deposit','debit'])) {
                    //Delete transfer transaction for fund transfer
                    if (!empty($account_transaction->transfer_transaction_id)) {
                        $transfer_transaction = AccountTransaction::findOrFail($account_transaction->transfer_transaction_id);
                        $transfer_transaction->delete();
                    }
                    $account_transaction->delete();
                }

                $output = ['success' => true,
                            'msg' => __("english.deleted_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    /**
     * Closes the specified account.
     * @return Response
     */
    public function close($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $system_settings_id = session()->get('user.system_settings_id');
            
                $account = Account::where('system_settings_id', $system_settings_id)
                                                    ->findOrFail($id);
                $account->is_closed = 1;
                $account->save();

                $output = ['success' => true,
                                    'msg' => __("english.account_closed_success")
                                    ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }
            
            return $output;
        }
    }

    /**
     * Shows form to transfer fund.
     * @param  int $id
     * @return Response
     */
    public function getFundTransfer($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');
            
            $from_account = Account::where('system_settings_id', $system_settings_id)
                            ->NotClosed()
                            ->find($id);

            $to_accounts = Account::where('system_settings_id', $system_settings_id)
                            ->where('id', '!=', $id)
                            ->NotClosed()
                            ->pluck('name', 'id');

            return view('account.transfer')
                ->with(compact('from_account', 'to_accounts'));
        }
    }

    /**
     * Transfers fund from one account to another.
     * @return Response
     */
    public function postFundTransfer(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            $system_settings_id = session()->get('user.system_settings_id');

            $amount = $this->commonUtil->num_uf($request->input('amount'));
            $from = $request->input('from_account');
            $to = $request->input('to_account');
            $note = $request->input('note');
            if (!empty($amount)) {
                $debit_data = [
                    'amount' => $amount,
                    'account_id' => $from,
                    'type' => 'debit',
                    'sub_type' => 'fund_transfer',
                    'created_by' => session()->get('user.id'),
                    'note' => $note,
                    'transfer_account_id' => $to,
                    'operation_date' => $this->commonUtil->uf_date($request->input('operation_date'), true),
                ];

                DB::beginTransaction();
                $debit = AccountTransaction::createAccountTransaction($debit_data);

                $credit_data = [
                        'amount' => $amount,
                        'account_id' => $to,
                        'type' => 'credit',
                        'sub_type' => 'fund_transfer',
                        'created_by' => session()->get('user.id'),
                        'note' => $note,
                        'transfer_account_id' => $from,
                        'transfer_transaction_id' => $debit->id,
                        'operation_date' => $this->commonUtil->uf_date($request->input('operation_date'), true),
                    ];

                $credit = AccountTransaction::createAccountTransaction($credit_data);

                $debit->transfer_transaction_id = $credit->id;
                $debit->save();

                Media::uploadMedia($system_settings_id, $debit, $request, 'document');

                DB::commit();
            }
            
            $output = ['success' => true,
                                'msg' => __("english.fund_transfered_success")
                                ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        
            $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
        }

        return redirect()->action('AccountController@index')->with('status', $output);
    }

    /**
     * Shows deposit form.
     * @param  int $id
     * @return Response
     */
    public function getDeposit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');
            
            $account = Account::where('system_settings_id', $system_settings_id)
                            ->NotClosed()
                            ->find($id);

            $from_accounts = Account::where('system_settings_id', $system_settings_id)
                            ->where('id', '!=', $id)
                            // ->where('account_type', 'capital')
                            ->NotClosed()
                            ->pluck('name', 'id');

            return view('account.deposit')
                ->with(compact('account', 'account', 'from_accounts'));
        }
    }

    /**
     * Deposits amount.
     * @param  Request $request
     * @return json
     */
    public function postDeposit(Request $request)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $system_settings_id = session()->get('user.system_settings_id');

            $amount = $this->commonUtil->num_uf($request->input('amount'));
            $account_id = $request->input('account_id');
            $note = $request->input('note');

            $account = Account::where('system_settings_id', $system_settings_id)
                            ->findOrFail($account_id);

            if (!empty($amount)) {
                $credit_data = [
                    'amount' => $amount,
                    'account_id' => $account_id,
                    'type' => 'credit',
                    'sub_type' => 'deposit',
                    'operation_date' => $this->commonUtil->uf_date($request->input('operation_date'), true),
                    'created_by' => session()->get('user.id'),
                    'note' => $note
                ];
                $credit = AccountTransaction::createAccountTransaction($credit_data);

                $from_account = $request->input('from_account');
                if (!empty($from_account)) {
                    $debit_data = $credit_data;
                    $debit_data['type'] = 'debit';
                    $debit_data['account_id'] = $from_account;
                    $debit_data['transfer_transaction_id'] = $credit->id;

                    $debit = AccountTransaction::createAccountTransaction($debit_data);

                    $credit->transfer_transaction_id = $debit->id;

                    $credit->save();
                }
            }
            
            $output = ['success' => true,
                                'msg' => __("english.deposited_successfully")
                                ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        
            $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
        }

        return $output;
    }

    /**
     * Calculates account current balance.
     * @param  int $id
     * @return json
     */
    public function getAccountBalance($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = session()->get('user.system_settings_id');
        $account = Account::leftjoin(
            'account_transactions as AT',
            'AT.account_id',
            '=',
            'accounts.id'
        )
            ->whereNull('AT.deleted_at')
            ->where('accounts.system_settings_id', $system_settings_id)
            ->where('accounts.id', $id)
            ->select('accounts.*', DB::raw("SUM( IF(AT.type='credit', amount, -1 * amount) ) as balance"))
            ->first();

        return $account;
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function cashFlow()
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = request()->session()->get('user.system_settings_id');

        if (request()->ajax()) {
            $accounts = AccountTransaction::join(
                'accounts as A',
                'account_transactions.account_id',
                '=',
                'A.id'
                )
                ->leftjoin(
                    'transaction_payments as TP',
                    'account_transactions.transaction_payment_id',
                    '=',
                    'TP.id'
                )
                ->where('A.system_settings_id', $system_settings_id)
                ->with(['transaction', 'transaction.contact', 'transfer_transaction'])
                ->select(['type', 'account_transactions.amount', 'operation_date',
                    'sub_type', 'transfer_transaction_id',
                    DB::raw("(SELECT SUM(IF(AT.type='credit', AT.amount, -1 * AT.amount)) from account_transactions as AT JOIN accounts as ac ON ac.id=AT.account_id WHERE ac.system_settings_id= $system_settings_id AND AT.operation_date <= account_transactions.operation_date AND AT.deleted_at IS NULL) as balance"),
                    'account_transactions.transaction_id',
                    'account_transactions.id',
                    'A.name as account_name',
                    'TP.payment_ref_no as payment_ref_no'
                    ])
                 ->groupBy('account_transactions.id')
                 ->orderBy('account_transactions.operation_date', 'desc');
            if (!empty(request()->input('type'))) {
                $accounts->where('type', request()->input('type'));
            }

            if (!empty(request()->input('account_id'))) {
                $accounts->where('A.id', request()->input('account_id'));
            }

            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            
            if (!empty($start_date) && !empty($end_date)) {
                $accounts->whereBetween(DB::raw('date(operation_date)'), [$start_date, $end_date]);
            }

            return DataTables::of($accounts)
                ->addColumn('debit', function ($row) {
                    if ($row->type == 'debit') {
                        return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                    }
                    return '';
                })
                ->addColumn('credit', function ($row) {
                    if ($row->type == 'credit') {
                        return '<span class="display_currency" data-currency_symbol="true">' . $row->amount . '</span>';
                    }
                    return '';
                })
                ->editColumn('balance', function ($row) {
                    return '<span class="display_currency" data-currency_symbol="true">' . $row->balance . '</span>';
                })
                ->editColumn('operation_date', function ($row) {
                    return $this->commonUtil->format_date($row->operation_date, true);
                })
                ->editColumn('sub_type', function ($row) {
                    return $this->__getPaymentDetails($row);
                })
                ->removeColumn('id')
                ->rawColumns(['credit', 'debit', 'balance', 'sub_type'])
                ->make(true);
        }
        $accounts = Account::forDropdown($system_settings_id, false);
                            
        return view('account.cash_flow')
                 ->with(compact('accounts'));
    }
    public function __getPaymentDetails($row)
    {
        $details = '';
      
        if (!empty($row->sub_type)) {
            $details = __('english.' . $row->sub_type);
            if (in_array($row->sub_type, ['fund_transfer']) && !empty($row->transfer_transaction)) {
               // dd($row->transfer_transaction->account->name);

            //    dd($row->type);
                if ($row->type == 'credit') {
                    if(!empty($row->transfer_transaction->account->name)){
                    $details .= ' ( ' . __('english.from') .': ' . $row->transfer_transaction->account->name . ')';
                    }
                } 
                else {
                    $details .= ' ( ' . __('english.to') .': ' . $row->transfer_transaction->account->name . ')';
                }
            }
         }
         if (!empty($row->expense_transaction_payment_id)) {
            //dd($row->expenseTransactionPayment->expense_transaction->expenseCategory->name);
            $details='<b>'. __('english.expense_category') .':'.$row->expenseTransactionPayment->expense_transaction->expenseCategory->name.'</b><br>';
            $details .= '<b>'. __('english.expense') .':</b> '.$row->expenseTransactionPayment->expense_transaction->additional_notes.'<b><br>' . __('english.pay_reference_no') . ':</b> ' . $row->expenseTransactionPayment->expense_transaction->ref_no . '<br><b>';
           // dd($row->expenseTransactionPayment->expense_transaction->ref_no);   
        }
        if (!empty($row->hrm_transaction_payment_id)) {
            if (!empty($details)) {
                $details .= '<br/>';
            }
           
            $details .= '<b>' . __('english.payment_for').'  ' . __('english.employee') . ':</b> ' . $row->payment_for_pay_roll;
        }
        
    if (!empty($row->transaction_payment_id )) {
        if (!empty($details)) {
            $details .= '<br/>';
        }

        $details .= '<b>' . __('english.payment_for') .' '. __('english.student') .':</b> ' . $row->payment_for;
    }
    

        if (!empty($row->payment_ref_no)) {
            if (!empty($details)) {
                $details .= '<br/>';
            }

            $details .= '<b>' . __('english.pay_reference_no') . ':</b> ' . $row->payment_ref_no;
        }
        if (!empty($row->payment_ref_no_pay_roll)) {
            if (!empty($details)) {
                $details .= '<br/>';
            }

            $details .= '<b>' . __('english.pay_reference_no') . ':</b> ' . $row->payment_ref_no_pay_roll;
        }
        

        if ($row->is_advance == 1) {
            $details .= '<br>(' . __('english.advance_payment') . ')';
        }

        return $details;
    }
    /**
     * activate the specified account.
     * @return Response
     */
    public function activate($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $system_settings_id = session()->get('user.system_settings_id');
            
                $account = Account::where('system_settings_id', $system_settings_id)
                                ->findOrFail($id);

                $account->is_closed = 0;
                $account->save();

                $output = ['success' => true,
                        'msg' => __("english.success")
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }
            
            return $output;
        }
    }


      /**
     * Shows deposit form.
     * @param  int $id
     * @return Response
     */
    public function getDebit($id)
    {
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            
            $system_settings_id = session()->get('user.system_settings_id');
            
            $account = Account::where('system_settings_id', $system_settings_id)
                            ->NotClosed()
                            ->find($id);

            $from_accounts = Account::where('system_settings_id', $system_settings_id)
                            ->where('id', '!=', $id)
                            // ->where('account_type', 'capital')
                            ->NotClosed()
                            ->pluck('name', 'id');

            return view('account.debit')
                ->with(compact('account', 'account', 'from_accounts'));
        }
    }


    
/**
     * Deposits amount.
     * @param  Request $request
     * @return json
     */
    public function postDebit(Request $request)
    {   
         
        if (!auth()->user()->can('account.access')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $system_settings_id = session()->get('user.system_settings_id');

            $amount = $this->commonUtil->num_uf($request->input('amount'));
            $account_id = $request->input('account_id');
            $note = $request->input('note');

            $account = Account::where('system_settings_id', $system_settings_id)
                            ->findOrFail($account_id);

            if (!empty($amount)) {
                $credit_data = [
                    'amount' => $amount,
                    'account_id' => $account_id,
                    'type' => 'debit',
                    'sub_type' => 'debit',
                    'operation_date' => $this->commonUtil->uf_date($request->input('operation_date'), true),
                    'created_by' => session()->get('user.id'),
                    'note' => $note
                ];
                $credit = AccountTransaction::createAccountTransaction($credit_data);

                $from_account = $request->input('from_account');
                if (!empty($from_account)) {
                    $debit_data = $credit_data;
                    $debit_data['type'] = 'credit';
                    $debit_data['account_id'] = $from_account;
                    $debit_data['transfer_transaction_id'] = $credit->id;

                    $debit = AccountTransaction::createAccountTransaction($debit_data);

                    $credit->transfer_transaction_id = $debit->id;

                    $credit->save();
                }
            }
            
            $output = ['success' => true,
                                'msg' => __("english.deposited_successfully")
                                ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        
            $output = ['success' => false,
                        'msg' => __("english.something_went_wrong")
                    ];
        }

        return $output;
    }


      /**
     * Edit the specified resource from storage.
     * @return Response
     */
    public function editAccountTransaction($id)
    {
        if (!auth()->user()->can('edit_account_transaction')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $account_transaction = AccountTransaction::with(['account', 'transfer_transaction'])->findOrFail($id);

        $accounts = Account::NotClosed()
                        ->pluck('name', 'id');

        return view('account.edit_account_transaction')
            ->with(compact('accounts', 'account_transaction'));

    }
    
    public function updateAccountTransaction(Request $request, $id)
    {
        if (!auth()->user()->can('edit_account_transaction')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $account_transaction = AccountTransaction::with(['transfer_transaction'])->findOrFail($id);

            $amount = $this->commonUtil->num_uf($request->input('amount'));
            $note = $request->input('note');

            $account_transaction->amount = $this->commonUtil->num_uf($request->input('amount'));
            $account_transaction->operation_date = $this->commonUtil->uf_date($request->input('operation_date'), true);
            $account_transaction->note = $request->input('note');
            $account_transaction->save();
            if (!empty($account_transaction->transfer_transaction)) {
                $transfer_transaction = $account_transaction->transfer_transaction;

                $transfer_transaction->amount = $amount;
                $transfer_transaction->operation_date = $account_transaction->operation_date;
                $transfer_transaction->note = $account_transaction->note;

                if ($account_transaction->sub_type == 'deposit') {
                    $transfer_transaction->account_id = $request->input('from_account');
                }
                if ($account_transaction->sub_type == 'fund_transfer') {
                    $transfer_transaction->account_id = $request->input('to_account');
                    $account_transaction->account_id = $request->input('account_id');

                }

                $transfer_transaction->save();
            }

            DB::commit();
            
            $output = ['success' => true,
                'msg' => __("english.success")
            ];
        } catch (\Exception $e) {

            DB::rollBack();

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        
            $output = ['success' => false,
                        'msg' => __("messages.something_went_wrong")
                    ];
        }

        return $output;
    }
    public function getAccountLedger($id)
    {
        if (!auth()->user()->can('edit_account_transaction')) {
            abort(403, 'Unauthorized action.');
        }
            
        $balance=$this->getAccountBalance($id)->balance;
        //dd($before_bal_query);
        $account = Account::
        with(['account_type', 'account_type.parent_account'])
        ->findOrFail($id);

        return view('account.account_ledger')
            ->with(compact('account','balance'));

    }
    public function postAccountLedger(Request $request)
    {
       
        if (!auth()->user()->can('edit_account_transaction')) {
            abort(403, 'Unauthorized action.');
        }
        $input=$request->input();
        $_date=explode(' - ', $input['transaction_date_range']);
        $start_date=Carbon::parse($_date[0])->format('Y-m-d');
        $end_date=Carbon::parse($_date[1])->format('Y-m-d');
        $before_bal_query = AccountTransaction::
                where('account_transactions.account_id', $input['account_id'])
                ->whereDate('account_transactions.operation_date', '<', $start_date)
                ->select([
                    DB::raw("COALESCE(SUM(IF(account_transactions.type = 'credit', account_transactions.amount, 0)),0) as bf_credit"),
                    DB::raw("COALESCE(SUM(IF(account_transactions.type = 'debit', account_transactions.amount, 0)),0) as bf_debit"),
                ])
                ->whereNull('account_transactions.deleted_at')->first();
        $after_bal_query = AccountTransaction::
                where('account_transactions.account_id', $input['account_id'])
                ->whereDate('account_transactions.operation_date', '>', $end_date)
                ->select([
                    DB::raw("COALESCE(SUM(IF(account_transactions.type = 'credit', account_transactions.amount, 0)),0) as af_credit"),
                    DB::raw("COALESCE(SUM(IF(account_transactions.type = 'debit', account_transactions.amount, 0)),0) as af_debit"),
                ])
                ->whereNull('account_transactions.deleted_at')->first();
         $beginning_balance=($before_bal_query->bf_credit-$before_bal_query->bf_debit);//+($after_bal_query->af_credit-$after_bal_query->af_debit);
        $account_transaction = AccountTransaction::join(
            'accounts as A',
            'account_transactions.account_id',
            '=',
            'A.id'
        )
        ->leftJoin('fee_transaction_payments AS tp', 'account_transactions.transaction_payment_id', '=', 'tp.id')
        ->leftJoin('hrm_transaction_payments AS hrm_tp', 'account_transactions.hrm_transaction_payment_id', '=', 'hrm_tp.id')
        ->leftJoin('users AS u', 'account_transactions.created_by', '=', 'u.id')
        ->leftJoin('students AS c', 'tp.payment_for', '=', 'c.id')
        ->leftJoin('hrm_employees AS em', 'hrm_tp.payment_for', '=', 'em.id')
                        ->where('A.id', $input['account_id'])
                        
                        // ->with(['transaction', 'transaction.contact', 'transfer_transaction', 'media', 'transfer_transaction.media'])
                        ->with(['transfer_transaction', 'media', 'transfer_transaction.media','expenseTransactionPayment','expenseTransactionPayment.expense_transaction','expenseTransactionPayment.expense_transaction.expenseCategory'])
                        ->select(['account_transactions.type', 'account_transactions.amount', 'operation_date',
                            'sub_type', 'transfer_transaction_id',
                            'account_transactions.transaction_id',
                            'account_transactions.id',
                            'account_transactions.note',
                            'account_transactions.expense_transaction_payment_id',
                            'account_transactions.hrm_transaction_payment_id',
                            'account_transactions.transaction_payment_id',
                            // 'tp.is_advance',
                            'tp.payment_ref_no',
                            'hrm_tp.payment_ref_no as payment_ref_no_pay_roll',
                            DB::raw("CONCAT(COALESCE(em.first_name, ''),' ',COALESCE(em.last_name,''),' (',COALESCE(em.employeeID,''),')') as payment_for_pay_roll"),
                            DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                            DB::raw("CONCAT(COALESCE(c.first_name, ''),' ',COALESCE(c.last_name,''),' (',COALESCE(c.roll_no,''),')') as payment_for")
                            ])
                         ->groupBy('account_transactions.id')
                        // ->orderBy('account_transactions.id', 'asc')
                         ->orderBy('account_transactions.operation_date', 'asc');
                        
        if (!empty($input['type'])) {
            $account_transaction->where('account_transactions.type', request()->input('type'));
        }

        
        if (!empty($start_date) && !empty($end_date)) {
            $account_transaction->whereDate('operation_date', '>=', $start_date)
                    ->whereDate('operation_date', '<=', $end_date);
        }
        $ledger_transaction=[];
        foreach ($account_transaction->get() as $at) {
            if($at['type'] =='debit'){
                $beginning_balance=$beginning_balance-$at['amount'];
            }
            if($at['type'] =='credit'){
                $beginning_balance=$beginning_balance+$at['amount'];
            }
            $data=[
                'date'=>$at->operation_date,
                'description'=>$this->__getPaymentDetails($at),
                'note'=>$at->note,
                'added_by'=>$at->added_by,
                'debit'=>in_array($at->type, ['debit'])  ? $at->amount : 0,
                'credit'=>in_array($at->type, ['credit'])  ? $at->amount : 0,
                'balance'=>$beginning_balance,
                'action'=>''
    
            ];
            $ledger_transaction[]=$data;
        }
          //Sort by date
          if (!empty($ledger_transaction)) {
            usort($ledger_transaction, function ($a, $b) {
                $t1 = strtotime($a['date']);
                $t2 = strtotime($b['date']);
                return $t1 - $t2;
            });
        }
        $ledger_transaction = array_merge([[
            'date'=>$start_date,
            'description'=>'beginning balance',
            'note'=>'',
            'added_by'=>'',
            'debit'=>0,
            'credit'=>0,
            'balance'=>($before_bal_query->bf_credit-$before_bal_query->bf_debit),///+($after_bal_query->af_credit-$after_bal_query->af_debit),
            'action'=>''

        ]],$ledger_transaction);
        $balance=$this->getAccountBalance($input['account_id'])->balance;
        if (File::exists(public_path('uploads/pdf/account.pdf'))) {
            File::delete(public_path('uploads/pdf/account.pdf'));
        }
        $pdf_name='account'.'.pdf';
        $account = Account::
        with(['account_type', 'account_type.parent_account'])
        ->findOrFail($input['account_id']);
        $pdf =  config('constants.mpdf');
        if ($pdf) {
            $data=['start_date'=>$start_date, 'end_date'=>$end_date, 'ledger_transaction'=>$ledger_transaction, 'account'=>$account, 'balance'=>$balance];  
        $this->reportPDF('normal.css', $data, 'MPDF.account_ledger_pdf','view','a4');
 }else{
    $snappy  = \WPDF::loadView('account.account_ledger_pdf', compact('start_date', 'end_date', 'ledger_transaction', 'account', 'balance'));
    $headerHtml = view()->make('common._header')->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'landscape')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
    //dd($ledger_transaction);


    return view('account.account_ledger')
        ->with(compact('account', 'balance', 'ledger_transaction'));
}
    }
}


