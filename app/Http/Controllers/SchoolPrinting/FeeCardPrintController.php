<?php

namespace App\Http\Controllers\SchoolPrinting;

use App\Http\Controllers\Controller;
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
use App\Utils\StudentUtil;
use App\Utils\FeeTransactionUtil;
use Illuminate\Support\Facades\Validator;
use DB;
use File;

class FeeCardPrintController extends Controller
{
    protected $studentUtil;
    protected $feeTransactionUtil;

    /**
     * Constructor
     *
     * @param ModuleUtil $moduleUtil
     * @return void
     */
    public function __construct(StudentUtil $studentUtil, FeeTransactionUtil $feeTransactionUtil)
    {
        $this->studentUtil = $studentUtil;
        $this->feeTransactionUtil = $feeTransactionUtil;
    }

    public function singlePrint($id)
    {
        if (!auth()->user()->can('print.challan_print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];

                $feeCards = [];
                $current_transaction = FeeTransaction::with(['session', 'campus', 'student', 'fee_lines.feeHead', 'student_class', 'student_class_section'])->find($id);
                $current_headings = FeeTransaction::with(['session', 'campus', 'student', 'fee_lines.feeHead', 'student_class', 'student_class_section'])
                    ->where('month', $current_transaction->month)
                    ->where('student_id', $current_transaction->student_id)
                    ->whereDate('transaction_date', $current_transaction->transaction_date)->get();
                //dd($current_transport);
                $date = \Carbon::parse($current_transaction->transaction_date);
                $year = $date->year;
                $current_transaction_paid = $this->__paymentPaid($current_transaction->student_id, $year, $current_transaction->month);
                //dd($current_transaction_paid);
                $query = $this->getSessionWiseTransaction($current_transaction->student_id, $year);

                $old_due = $this->getFeeCardStudentDue($current_transaction->student_id, $year);
                //dd($old_due);

                // dd($per_transaction);
                $fee_transaction_payment = $this->__paymentQuery($current_transaction->student_id, $year);
                $transaction_formatted = $this->__transaction_format($query);
                $payment_formatted = $this->__payment_format($fee_transaction_payment);

                $fee_transaction_payment_discount = $this->__paymentQueryForDiscount($current_transaction->student_id, $year);
                $discount_payment_formatted = $this->__payment_format($fee_transaction_payment_discount);
                //        dd($discount_payment_formatted);



                $balance = $this->__transaction_paid_total_final_format($transaction_formatted, $payment_formatted, $old_due, $discount_payment_formatted);

                $student_dues = $this->studentUtil->getStudentDue($current_transaction->student_id);
                $latest_transaction = FeeTransaction::where('student_id', $current_transaction->student_id)->whereYear('transaction_date', '=', $year)->latest('month')->first();


                $feeCards[] = [
                    'current_transaction' => $current_transaction,
                    'current_transaction_paid' => $current_transaction_paid,
                    'transaction_formatted' => $transaction_formatted,
                    'payment_formatted' => $payment_formatted,
                    'discount_payment_formatted' => $discount_payment_formatted,
                    'balance' => $balance,
                    'student_image' => '/uploads/student_image/' . $current_transaction->student->student_image,
                    'total_due' => $student_dues->total_due,
                    'current_headings' => $current_headings,
                    'fee_month' => $latest_transaction->month


                ];
                $student = $feeCards;
                $receipt = $this->receiptContent($student);


                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }


    public function createClassWisePrint(Request $request)
    {
        if (!auth()->user()->can('print.challan_print')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
                $campus_id = $request->input('campus_id');
                $class_id = $request->input('class_id');
                $section_id = $request->input('section_id');
                $month_year_arr = explode('/', request()->input('month_year'));
                $month = $month_year_arr[0];
                $year = $month_year_arr[1];
                $transaction_date = $year . '-' . $month . '-01';
                if (empty($campus_id) || empty(request()->input('month_year'))) {
                    $output = [
                        'success' => 0,
                        'msg' => trans("Plz select campus and class , section")
                    ];
                    return $output;
                }

                $students = Student::where('campus_id', $campus_id);
                if (!empty($class_id)) {
                    $students->where('current_class_id', $class_id);
                }
                if (!empty($section_id)) {
                    $students->where('current_class_section_id', $section_id);
                }
                $students = $students->get('id');
                if (request()->input('only_transport')=='true') {
                    $students->where('student_transport_fee','>',0);
                }
                $feeCards = [];
                foreach ($students as $student_id) {
                    $current_transaction = $this->__current_transaction($student_id->id, $transaction_date);

                    $current_headings = FeeTransaction::with(['session', 'campus', 'student', 'fee_lines.feeHead', 'student_class', 'student_class_section'])
                        // ->where('month',$month)
                        ->where('student_id', $student_id->id)
                        ->whereDate('transaction_date', $transaction_date)->get();
                    //dd($month);
                    if (!empty($current_transaction)) {
                        $date = \Carbon::parse($current_transaction->transaction_date);
                        $year = $date->year;
                        $current_transaction_paid = $this->__paymentPaid($current_transaction->student_id, $year, $current_transaction->month);
                        $query = $this->getSessionWiseTransaction($current_transaction->student_id, $year);
                        $old_due = $this->getFeeCardStudentDue($current_transaction->student_id, $year);
                        $fee_transaction_payment = $this->__paymentQuery($current_transaction->student_id, $year);
                        $transaction_formatted = $this->__transaction_format($query);
                        $payment_formatted = $this->__payment_format($fee_transaction_payment);
                        $fee_transaction_payment_discount = $this->__paymentQueryForDiscount($current_transaction->student_id, $year);
                        $discount_payment_formatted = $this->__payment_format($fee_transaction_payment_discount);
                        $balance = $this->__transaction_paid_total_final_format($transaction_formatted, $payment_formatted, $old_due, $discount_payment_formatted);
                        $student_dues = $this->studentUtil->getStudentDue($current_transaction->student_id);
                        $latest_transaction = FeeTransaction::where('student_id', $current_transaction->student_id)->whereYear('transaction_date', '=', $year)->latest('month')->first();
                       // dd($current_transaction);
                        if (request()->input('only_unpaid')=='true') {
                            $start_date = \Carbon::parse($date)->format('Y-m-d');
                            $end_date = \Carbon::now()->endOfMonth()->format('Y-m-d');
                           // dd($this->feeTransactionUtil->getTotalFeePaid($start_date, $end_date, $current_transaction->student_id));
                            if ($this->feeTransactionUtil->getTotalFeePaid($start_date, $end_date, $current_transaction->student_id) == 0 && $current_transaction->payment_status == 'due') {
                                $feeCards[] = [
                                    'current_transaction' => $current_transaction,
                                    'current_transaction_paid' => $current_transaction_paid,
                                    'transaction_formatted' => $transaction_formatted,
                                    'payment_formatted' => $payment_formatted,
                                    'discount_payment_formatted' => $discount_payment_formatted,
                                    'balance' => $balance,
                                    'student_image' => '/uploads/student_image/' . $current_transaction->student->student_image,
                                    'total_due' => $student_dues->total_due,
                                    'fee_month' => $latest_transaction->month,
                                    'current_headings' => $current_headings,



                                ];
                            }
                        }else{
                            $feeCards[]=[
                                'current_transaction'=>$current_transaction,
                                'current_transaction_paid'=>$current_transaction_paid,
                                'transaction_formatted'=>$transaction_formatted,
                                'payment_formatted'=>$payment_formatted,
                                'discount_payment_formatted'=>$discount_payment_formatted,
                                'balance'=>$balance,
                                'student_image'=>'/uploads/student_image/'.$current_transaction->student->student_image,
                                'total_due'=>$student_dues->total_due,
                                'fee_month'=>$latest_transaction->month,
                                'current_headings'=>$current_headings,
    
                            ];
                        }
                    }
                }
                $student = $feeCards;
                $receipt = $this->receiptContent($student);


                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => 0,
                    'msg' => trans("messages.something_went_wrong")
                ];
            }

            return $output;
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses = Campus::forDropdown();
        $now_month = \Carbon::now()->month;
        return view('school-printing.feecard.create')->with(compact('campuses', 'now_month'));
    }

    /**
     * Query to get payment details for a customer
     *
     */
    private function __transaction_paid_total_final_format($fee_transaction, $payment_formatted, $old_due, $discount_payment_formatted)
    {
        $old_due = $old_due;
        if (empty($payment_formatted)) {
            $payment_formatted = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
        }
        if (empty($discount_payment_formatted)) {
            $discount_payment_formatted = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
        }
        $total = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
        $bF = [1 => $old_due, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
        $balance = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
        foreach ($fee_transaction as $key => $t) {
            if ($key == 1) {
                $total[$key] = $bF[$key] + $t;
            }
        }
        foreach ($payment_formatted as $key => $p) {
            if ($key == 1) {
                $balance[$key] = $total[$key] - $p - $discount_payment_formatted[$key];
            } else {
                $bF[$key] = $balance[$key - 1];
                $total[$key] = $fee_transaction[$key] + $bF[$key];
                $balance[$key] = $total[$key] - $p - $discount_payment_formatted[$key];
            }
        }

        $details = ['bf' => $bF, 'total' => $total, 'balance' => $balance];
        return $details;
    }
    private function __transaction_format($query)
    {
        $transaction_formatted = [];
        foreach ($query as $q) {
            foreach (__('lang.index_months') as $key => $month) {
                if ($q->month == $key) {
                    $transaction_formatted[$month] = $q->total_invoice;
                } else {
                    if (empty($transaction_formatted[$month])) {
                        $transaction_formatted[$month] = 0;
                    }
                }
            }
        }
        return $transaction_formatted;
    }
    private function __payment_format($query)
    {
        $payment_formatted = [];
        foreach ($query as $p) {
            foreach (__('lang.index_months') as $key => $month) {
                if ($p->month == $key) {
                    $payment_formatted[$month] = $p->total_paid;
                } else {
                    if (empty($payment_formatted[$month])) {
                        $payment_formatted[$month] = 0;
                    }
                }
            }
        }

        return $payment_formatted;
    }
    private function paymentPaid($transaction_id)
    {
        $query = FeeTransactionPayment::where('fee_transaction_payments.fee_transaction_id', $transaction_id)
            ->select(DB::raw("COALESCE(SUM(amount),0) as total_paid"))->first();
        return $query;
    }
    private function __paymentPaid($student_id, $session_id, $month)
    {
        $query = FeeTransactionPayment::where('fee_transaction_payments.payment_for', $student_id)
            //->whereNotNull('transaction_payments.transaction_id');
            ->whereNull('fee_transaction_payments.parent_id')
            ->whereMonth('paid_on', $month)
            ->where('session_id', '=', $session_id)
            // ->where('session_id','=',$this->feeTransactionUtil->getActiveSession())
            ->select(DB::raw("COALESCE(SUM(amount),0) as total_paid"))->first();
        // ->where('fee_transaction_id','=',$transaction_id)->select(DB::raw("COALESCE(SUM(amount),0) as total_paid"))->first();
        // $query->select(DB::raw("SUM(amount) as total_paid"))->get();
        return $query;
        ;
    }
    private function __paymentQuery($student_id, $year)
    {
        $query = FeeTransactionPayment::where('fee_transaction_payments.payment_for', $student_id)
            //->whereNotNull('transaction_payments.transaction_id');
            ->whereNull('fee_transaction_payments.parent_id')
            ->where('method', '!=', 'student_advance_amount')
            ->whereYear('paid_on', '=', $year);
        $query->select([
            DB::raw("SUM(amount) as total_paid"),
            DB::raw('MONTH(paid_on) month')
        ])->groupBy('month');
        return $query->get();
    }
    private function __paymentQueryForDiscount($student_id, $year)
    {
        $query = FeeTransactionPayment::where('fee_transaction_payments.payment_for', $student_id)
            //->whereNotNull('transaction_payments.transaction_id');
            ->whereNull('fee_transaction_payments.parent_id')
            ->where('method', '!=', 'student_advance_amount')
            ->whereYear('paid_on', '=', $year);
        $query->select([
            DB::raw("SUM(discount_amount) as total_paid"),
            DB::raw('MONTH(paid_on) month')
        ])->groupBy('month');

        return $query->get();
    }


    private function getSessionWiseTransaction($student_id, $year)
    {
        $query = FeeTransaction::where('fee_transactions.student_id', $student_id)
            // ->where('session_id', $session_id)
            ->whereYear('transaction_date', $year)
            ->whereIn('type', ['fee', 'admission_fee', 'opening_balance', 'other_fee', 'transport_fee'])
            ->select([
                'month', DB::raw("SUM(IF(status = 'final', before_discount_total, 0)) as total_invoice"),
            ])->groupBy('month')->orderBy('month', 'asc');
        return $query->get();
    }
    private function __base64encode($image, $type = null)
    {
        if (!empty($image)) {
            $path = public_path('uploads/student_image/' . $image);
        } else {
            $path = public_path('uploads/student_image/default.png');
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    private function __current_transaction($student_id, $transaction_date)
    {
        $query = FeeTransaction::with(['session', 'campus', 'student', 'fee_lines.feeHead', 'student_class', 'student_class_section'])
            ->where('student_id', $student_id)
            //->where('type', 'fee')
            ->whereDate('transaction_date', $transaction_date);
        return $query->first();
    }
    private function generateFeeCard($student)
    {
        // dd($base64);
        $student = $student;
        $logo = 'Pk';
        if (File::exists(public_path('uploads/pdf/feecard.pdf'))) {
            File::delete(public_path('uploads/pdf/feecard.pdf'));
        }
        $pdf_name = 'feecard' . '.pdf';
        $snappy = \WPDF::loadView('school-printing/feecard.class-wise-fee-card', compact('student'));
        $headerHtml = view()->make('common._header', compact('logo'))->render();
        $footerHtml = view()->make('common._footer')->render();
        $snappy->setOption('header-html', $headerHtml);
        $snappy->setOption('footer-html', $footerHtml);
        $snappy->setPaper('a5')->setOption('orientation', 'landscape')->setOption('margin-top', 5)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 10);
        $snappy->save('uploads/pdf/' . $pdf_name); //save pdf file

        return $snappy;
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
    private function receiptContent($student)
    {
        $output = [
            'is_enabled' => false,
            'print_type' => 'browser',
            'html_content' => null,
            'printer_config' => [],
            'data' => []
        ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details = [];
        //$output['html_content'] = view('school-printing.feecard.single_fee_card', compact('student'))->render();
        $output['html_content'] = view('school-printing.feecard.fee_card_template1', compact('student'))->render();

        return $output;
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
    private function receiptContentFee($student)
    {
        $output = [
            'is_enabled' => false,
            'print_type' => 'browser',
            'html_content' => null,
            'printer_config' => [],
            'data' => []
        ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details = [];
        $output['html_content'] = view('school-printing.feecard.abasyn', compact('student'))->render();

        return $output;
    }


    public function getFeeCardStudentDue($student_id, $year)
    {
        // $fee_transaction =FeeTransaction::where('student_id', $student_id)
        // ->whereYear('transaction_date', '!=', $year) ->select(
        //     DB::raw('COALESCE(SUM(final_total),0)as total'),
        //     DB::raw('COALESCE(SUM(discount_amount),0)as total_discount_amount')
        // )
        // ->first();
        // $query = FeeTransactionPayment::where('payment_for', $student_id)
        // ->whereYear('paid_on', '!=', $year)  ->whereNull('parent_id') ->select(
        //     DB::raw('COALESCE(SUM(IF( is_return = 0, amount, amount*-1)),0)as total_paid'),
        //     DB::raw('COALESCE(SUM(discount_amount),0)as total_paid_discount_amount')

        // )
        // ->first();
        $fee_transaction = FeeTransaction::where('student_id', $student_id)
            ->whereYear('transaction_date', '<', $year)->select(
                DB::raw('COALESCE(SUM(final_total),0)as total'),
                DB::raw('COALESCE(SUM(discount_amount),0)as total_discount_amount')
            )
            ->first();
        $query = FeeTransactionPayment::where('payment_for', $student_id)
            ->whereYear('paid_on', '<', $year)->whereNull('parent_id')->select(
                DB::raw('COALESCE(SUM(IF( is_return = 0, amount, amount*-1)),0)as total_paid'),
                DB::raw('COALESCE(SUM(discount_amount),0)as total_paid_discount_amount')

            )
            ->first();
        return ($fee_transaction->total + $fee_transaction->total_discount_amount) - ($query->total_paid + $query->total_paid_discount_amount);
    }
}