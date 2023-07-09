<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\Classes;
use App\Models\Campus;
use App\Models\Session;
use App\Models\Student;
use App\Models\FeeTransaction;
use App\Models\Discount;
use App\Models\FeeHead;
use App\Models\FeeTransactionPayment;
use App\Models\FeeTransactionLine;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\StudentUtil;
use App\Utils\FeeTransactionUtil;
use App\Utils\NotificationUtil;
use App\Events\FeeTransactionPaymentDeleted;

use Illuminate\Support\Facades\Validator;
use DB;
use File;

class FeeAllocationController extends Controller
{
    protected $studentUtil;
    protected $feeTransactionUtil;
    protected $notificationUtil;


    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(StudentUtil $studentUtil, FeeTransactionUtil $feeTransactionUtil, NotificationUtil $notificationUtil)
    {
        $this->studentUtil = $studentUtil;
        $this->feeTransactionUtil = $feeTransactionUtil;
        $this->notificationUtil= $notificationUtil;
        $this->student_status_colors = [
            'active' => 'bg-success',
            'packed' => 'bg-info',
            'shipped' => 'bg-navy',
            'delivered' => 'bg-green',
            'cancelled' => 'bg-red',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
//         $fee_transactions=FeeTransaction::where('session_id',2)->get();
        
// foreach ($fee_transactions as $fee) {
//     $t=FeeTransaction::find($fee->id);
//     $transaction_date = '2023' . '-' . $fee->month . '-01';
//     $t->transaction_date = $transaction_date;
//     $t->save();
// }
// dd($fee_transactions[0]);
        // $date = \Carbon::parse('2023-01-01')->addMonth(3);
        // dd($date->month);
        
        //dd($this->feeTransactionUtil->getListFeeTransaction(1)->get());
//            $students=Student::get();
//            foreach($students as $std){
//             $student = $this->studentUtil->getStudentDue($std->id);
//            // dd($student);
//             if($student->total_due >0){
//                // sleep(120);
        // if ($std->id>693) {
//     $response=$this->notificationUtil->autoSendStudentPaymentNotification('fee_due_sms', $student, null);
//    // dd($response);
        // }

//             }

//            }
//            dd('ok');
        // $student = $this->studentUtil->getStudentDue($student_id);
        // $response=$this->notificationUtil->autoSendStudentPaymentNotification('owner_payment_received', $student, $parent_payment);
        if (!auth()->user()->can('fee.fee_transaction_view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $fee_transactions=$this->feeTransactionUtil->allFeeTransaction();
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $fee_transactions->where('fee_transactions.campus_id', $campus_id);
                }
            }
            if (request()->has('payment_status')) {
                $payment_status = request()->get('payment_status');
                if (!empty($payment_status)) {
                    $fee_transactions->where('fee_transactions.payment_status', $payment_status);
                }
            }
            if (request()->has('transaction_type')) {
                $transaction_type = request()->get('transaction_type');
                if (!empty($transaction_type)) {
                    $fee_transactions->where('fee_transactions.type', $transaction_type);
                }
            }
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $fee_transactions->whereDate('fee_transactions..transaction_date', '>=', $start)
                        ->whereDate('fee_transactions..transaction_date', '<=', $end);
            }
           
            $datatable = Datatables::of($fee_transactions)
        ->addColumn(
            'action',
            function ($row) {
                $html= '<div class="dropdown">
                     <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                     <ul class="dropdown-menu" style="">';
                if (auth()->user()->can('print.challan_print')) {
                    $html.='<li><a class="dropdown-item print-invoice " href="#" data-href="' . action('SchoolPrinting\FeeCardPrintController@singlePrint', [$row->id]) . '"><i class="fas fa-print"></i> ' . __("english.challan_print") . '</a></li>';
                }
                if (auth()->user()->can('fee.add_fee_payment')) {
                    $html .= '<li><a href="' . action('FeeTransactionPaymentController@addPayment', [$row->id]) . '" class="dropdown-item add_payment_modal"><i class="fas fa-money-bill-alt"></i> ' . __("english.add_payment") . '</a></li>';
                } if (auth()->user()->can('fee.fee_payment_view')) {
                    $html .= '<li><a href="' . action('FeeTransactionPaymentController@show', [$row->id]) . '" class="dropdown-item view_payment_modal"><i class="fas fa-eye"></i> ' . __("english.view_payments") . '</a></li>';
                }
                if (auth()->user()->can('fee.fee_transaction_delete')) {
                    if ($row->payment_status == "due") {
                        $html .= '<li>
                <a href="'.action('FeeAllocationController@destroy', [$row->id]).'" class="dropdown-item delete-fee_transaction"><i class="fas fa-trash"></i>'. __("english.delete").'</a>
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
                $payment_status = FeeTransaction::getPaymentStatus($row);
                return (string) view('fee_allocation.partials.payment_status', ['payment_status' => $payment_status, 'id' => $row->id]);
            }
        )
        ->editColumn('student_name', function ($row) {
            return ucwords($row->student_name);
        })
        ->editColumn('month', function ($row) {
            $html=__('english.months.' . $row->month)  .'  - ';
            $html.='<span class="badge text-white text-uppercase  bg-primary">';
              $html.= __('english.transaction_types.' .$row->type) . '</span>';
            return $html;
           
        })
        ->editColumn('father_name', function ($row) {
            return ucwords($row->father_name);
        })
        ->editColumn('roll_no', function ($row) {
            return ucwords($row->roll_no);
        })
        ->editColumn('type', function ($row) {
            return ucwords(str_replace('_', ' ', $row->type));
        })
        ->editColumn('current_class', function ($row) {
            return ucwords($row->current_class);
        })
        //    ->editColumn('status', function ($row) {
        //        $status_color = !empty($this->student_status_colors[$row->status]) ? $this->student_status_colors[$row->status] : 'bg-gray';
        //        $status='<span class="badge badge-mark ' . $status_color .'">' .ucwords($row->status).   '</span>';
        //        return $status;
        //    })
        ->addColumn('total_remaining', function ($row) {
            $total_remaining =  $row->final_total - $row->total_paid;
            $total_remaining_html = '<span class="payment_due" data-orig-value="' . $total_remaining . '">' . $this->feeTransactionUtil->num_f($total_remaining, true) . '</span>';


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
        ->editColumn(
            'before_discount_total',
            '<span class="before_discount_total" data-orig-value="{{$before_discount_total}}">@format_currency($before_discount_total)</span>'
        )
        ->editColumn(
            'discount_amount',
            '<span class="discount_amount" data-orig-value="{{$discount_amount}}">@format_currency($discount_amount)</span>'
        )
        ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
        ->filterColumn('roll_no', function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('students.roll_no', 'like', "%{$keyword}%");
            });
        })
        ->filterColumn('student_name', function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('students.first_name', 'like', "%{$keyword}%");
            });
        })
        ->filterColumn('father_name', function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('students.father_name', 'like', "%{$keyword}%");
            });
        })
        ->removeColumn('id');



            $rawColumns = ['action',
            //'status',
            'campus_name','transaction_class','transaction_date','voucher_no','payment_status','before_discount_total','discount_amount','final_total','total_remaining','total_paid','roll_no','month'];

            return $datatable->rawColumns($rawColumns)
              ->make(true);
        }
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, false);


        return view('fee_allocation.index')->with(compact('campuses', 'sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('fee.fee_transaction_create')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');


        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown();
        $active_session=$this->studentUtil->getActiveSession();
        $now_month = \Carbon::now()->month;

        return view('fee_allocation.create')->with(compact('campuses', 'now_month', 'sessions', 'active_session'));
    }
    public function feesAssignSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
            'month_year' => "required"
        ]);

        $month_year_arr = explode('/', request()->input('month_year'));
        $month = $month_year_arr[0];
        $year = $month_year_arr[1];

        $transaction_date = $year . '-' . $month . '-01';
        //check if fees exists for the month year
        $fees = FeeTransaction::where('type','=','fee')->whereDate('transaction_date', $transaction_date)
        ->get('student_id', 'first_name');
        $already_exists = [];
        if (!empty($fees)) {
            foreach ($fees as $key => $value) {
                array_push($already_exists, $value->student_id);
            }
        }    $month_year=$input['month_year'];
        // $month_id=$input['month_id'];
        // $session_id=$input['session_id'];
        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $class_section_id=$input['class_section_id'];
        $system_settings_id = session()->get('user.system_settings_id');
        $due_date=$this->studentUtil->uf_date($input['due_date']);
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        $fee_heads=$this->studentUtil->getFeeHeads($campus_id, $class_id);
        // $sessions=Session::forDropdown();
        $campuses=Campus::forDropdown();
        $students=$this->studentUtil->getStudentList($system_settings_id, $class_id, $class_section_id, 'active',$already_exists );
        return view('fee_allocation.fees_assign')->with(compact('students', 'fee_heads', 'campuses', 'classes', 'sections', 'campus_id', 'class_id', 'class_section_id', 'month_year', 'due_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('fee.fee_transaction_create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            $input=$request->input();
            $due_date=$input['due_date'];
            $session_id=$this->studentUtil->getActiveSession();
            $fee_heads=$input['fee_heads'];
            $user_id = $request->session()->get('user.id');
            $system_settings_id = $request->session()->get('user.system_settings_id');
            foreach ($input['student_checked'] as $student_id) {
                $student=Student::find($student_id);
                // $discount=Discount::find($student->discount_id);
                $discount=0;
                $lines_formatted = [];
                foreach ($fee_heads as $key => $value) {
                    if (!empty($value['is_enabled'])) {
                        $line=[
                    'fee_head_id'=>$value['fee_head_id'],
                    'amount'=>$this->studentUtil->num_uf($value['amount'])
                ];
                        $lines_formatted[] = new FeeTransactionLine($line);
                    }
                }

                $tuition_fee=[
                        'fee_head_id'=>2,
                        'amount'=>$this->studentUtil->num_uf($student->student_tuition_fee)
                        ];
                $lines_formatted[]=new FeeTransactionLine($tuition_fee);
                // if ($student->is_transport) {
                //     $transport_fee=[
                //             'fee_head_id'=>3,
                //             'amount'=>$this->studentUtil->num_uf($student->student_transport_fee)
                //         ];
                //     $lines_formatted[]=new FeeTransactionLine($transport_fee);
                // }

                $final_total=$this->feeTransactionUtil->getFinalWithoutDiscount($lines_formatted, $discount);

                if ($final_total !=0) {
                    $transaction=$this->feeTransactionUtil->multiFeeTransaction($student, 'fee', $system_settings_id, $user_id, $lines_formatted, $final_total, $discount, $input['month_year'], $due_date, $session_id);
                }
            }

            DB::commit();

            $output = ['success' => true,
                'msg' => __("english.updated_success")
                ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                'msg' => __("english.something_went_wrong")
            ];
        }
        return redirect('fee-allocation/create')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('fee.fee_transaction_delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $output = ['success' => 0,
            'msg' => trans("messages.something_went_wrong")
            ];
            DB::beginTransaction();
            $transaction = FeeTransaction::where('id', $id)
                      //  ->whereIn('type', ['fee'])
                        ->with(['payment_lines'])
                        ->first();

            if (!empty($transaction)) {
                $transaction_payments = $transaction->payment_lines;

                $transaction->delete();
                foreach ($transaction_payments as $payment) {
                    event(new FeeTransactionPaymentDeleted($payment));
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulkAllocationCreate()
    {
        if (!auth()->user()->can('fee.fee_transaction_create')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');


        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown();
        $active_session=$this->studentUtil->getActiveSession();
        $now_month = \Carbon::now()->month;

        return view('fee_allocation.bulk-fee-create')->with(compact('campuses', 'now_month', 'sessions', 'active_session'));
    }

    public function bulkAllocationPost(Request $request)
    {
        dd($request);
    }
}
