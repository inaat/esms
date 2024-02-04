<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;

use App\Models\FeeTransaction;
use App\Models\FeeTransactionLine;
use App\Models\Student;
use App\Models\FeeTransactionPayment;

use App\Events\FeeTransactionPaymentAdded;

class FeeTransactionUtil extends Util
{
    public function createFeeTransaction($request, $type)
    {
        $user_id = $request->session()->get('user.id');
        $system_settings_id = $request->session()->get('user.system_settings_id');
        $final_total =  $this->num_uf($request->final_total);
        $now = \Carbon::now();
        //Update reference count

        $ob_ref_count = $this->setAndGetReferenceCount('challan', false, true);
        $transaction = FeeTransaction::create([
                    'system_settings_id' => $system_settings_id,
                    'campus_id' => $request->campus_id,
                    'type' => $type,
                    'status' => 'final',
                    'payment_status' => 'due',
                    'voucher_no'=> $this->generateReferenceNumber('challan', $ob_ref_count, $system_settings_id),
                    'session_id'=>$this-> getActiveSession(),
                    'class_id'=>$request->class_id,
                    'class_section_id'=>$request->class_section_id,
                    'month' => $now->month,
                    'student_id' => $request->student_id,
                    'transaction_date' =>$now,
                    'before_discount_total' => $final_total,
                    'final_total' => $final_total,
                    'created_by' => $user_id,
                ]);

        return $transaction;
    }
    public function multiFeeTransaction($student, $type, $system_settings_id, $user_id, $lines_formatted, $final_total, $discount, $month_year, $due_date=null, $session_id=null)
    {
        $final_total =  $this->num_uf($final_total);
        //Update reference count
        if ($session_id==null) {
            $session_id=$this-> getActiveSession();
        }
        $ob_ref_count = $this->setAndGetReferenceCount('challan', false, true);

        $month_year_arr = explode('/', $month_year);
        $month = $month_year_arr[0];
        $year = $month_year_arr[1];

        $transaction_date = $year . '-' . $month . '-01';


        $transaction = FeeTransaction::create([
                    'system_settings_id' => $system_settings_id,
                    'campus_id' => $student->campus_id,
                    'type' => $type,
                    'due_date' => $due_date,
                    'status' => 'final',
                    'payment_status' => 'due',
                    'voucher_no'=> $this->generateReferenceNumber('challan', $ob_ref_count, $system_settings_id),
                    'session_id'=>$session_id,
                    'class_id'=>$student->current_class_id,
                    'class_section_id'=>$student->current_class_section_id,
                    'month' =>  $month,
                    'discount_type' => !empty($discount->discount_type) ? $discount->discount_type : null,
                    'discount_amount' => !empty($discount->discount_amount) ? $discount->discount_amount : 0,
                    'student_id' => $student->id,
                    'transaction_date' =>$transaction_date,
                    'before_discount_total' => $final_total,
                    'final_total' => $final_total,
                    'created_by' => $user_id,
                ]);
        if (!empty($lines_formatted)) {
            $transaction->fee_lines()->saveMany($lines_formatted);
        }
        return $transaction;
    }

    public function createFeeTransactionLines($fee_heads, $transaction)
    {
        $lines_formatted = [];

        foreach ($fee_heads as $key => $value) {
            # code...

            if (!empty($value['is_enabled'])) {
                $line=[
                       'fee_head_id'=>$value['fee_head_id'],
                       'amount'=>$this->num_uf($value['amount'])
                   ];

                $lines_formatted[]=new FeeTransactionLine($line);
            }
        }

        if (!empty($lines_formatted)) {
            $transaction->fee_lines()->saveMany($lines_formatted);
        }
    }
    public function getFinalWithoutDiscount($fee_heads, $discount)
    {
        $final_total =0;

        foreach ($fee_heads as $key => $value) {
            $final_total +=$value['amount'];
        }
        if (!empty($discount)) {
            // if ($discount->discount_type == 'fixed') {
            //$final_total -= $discount->discount_amount;
            $final_total -= $discount;
            // } else {
            //$final_total = $final_total-(($discount->discount_amount/100)*$final_total);
            // }
        }
        return $final_total;
    }

    public function payStudent($request, $format_data = true)
    {
        $student_id = $request->input('student_id');
        $system_settings_id = auth()->user()->system_settings_id;
        $inputs = $request->only(['amount', 'method','discount_amount', 'note', 'card_number', 'card_holder_name',
            'card_transaction_number', 'card_type', 'card_month', 'card_year', 'card_security',
            'cheque_number', 'bank_account_number']);
        $discount=$request->input('discount_amount');

        $payment_types = $this->payment_types();
        $inputs['session_id']=$this->getActiveSession();

        if (!array_key_exists($inputs['method'], $payment_types)) {
            throw new \Exception("Payment method not found");
        }
        $inputs['paid_on'] = $request->input('paid_on', \Carbon::now()->toDateTimeString());
        if ($format_data) {
            $inputs['paid_on'] = $this->uf_date($inputs['paid_on'], true);
            $inputs['amount'] = $this->num_uf($inputs['amount']);
        }

        $inputs['created_by'] = auth()->user()->id;
        $inputs['payment_for'] = $student_id;
        $inputs['system_settings_id'] = $system_settings_id;
        $student = Student::where('system_settings_id', $system_settings_id)
                        ->findOrFail($student_id);
        $inputs['campus_id'] = $student->campus_id;
        $due_payment_type = 'fee';
        $prefix_type = 'fee_payment';
        $ref_count = $this->setAndGetReferenceCount($prefix_type, false, true);
        //Generate reference number
        $payment_ref_no = $this->generateReferenceNumber($prefix_type, $ref_count, $system_settings_id);

        $inputs['payment_ref_no'] = $payment_ref_no;

        if (!empty($request->input('account_id'))) {
            $inputs['account_id'] = $request->input('account_id');
        }

        //Upload documents if added
        $inputs['document'] = $this->uploadFile($request, 'document', 'documents');
        $types = ['opening_balance','transport_fee','admission_fee', 'fee','other_fee'];
        $due_transactions = FeeTransaction::where('student_id', $student->id)
                                ->whereIn('type', $types)
                                ->where('payment_status', '!=', 'paid')
                                ->orderBy('transaction_date', 'asc')
                                ->get();
        if ($due_transactions->count()) {
            if ($inputs['discount_amount']>0) {
                $discount_amount=$this->num_uf($inputs['discount_amount']);
                foreach ($due_transactions as $transaction) {
                    if ($discount_amount == 0) {
                        break;
                    } else {
                        $up=FeeTransaction::find($transaction->id);

                        if ($up->final_total>=$discount_amount) {
                            $up->final_total=$transaction->final_total-$discount_amount;
                            $up->discount_type='fixed';
                            $up->discount_amount=$discount_amount;
                            $up->save();
                            $discount_amount=$discount_amount-$up->discount_amount;
                        } else {
                        //     $before_discount_total=$discount;
                        //     $discount_amount=$discount_amount-$up->final_total;
                        //     $up->final_total=($before_discount_total-$discount_amount )-$up->final_total;
                        //     $up->discount_type='fixed';
                        //     $up->discount_amount=$before_discount_total-$discount_amount;
                        //     $up->save();
                            $discount_amount-=$up->final_total;
                            $up->discount_amount=$up->final_total;
                            $up->final_total=0;
                            $up->save();
                        }
                    }
                }
            }



            $parent_payment = FeeTransactionPayment::create($inputs);
            $inputs['transaction_type'] = $due_payment_type;
            event(new FeeTransactionPaymentAdded($parent_payment, $inputs));

            //Distribute above payment among unpaid transactions
            $excess_amount = $this->payAtOnce($parent_payment, $due_payment_type);
            //Update excess amount
            // if (!empty($excess_amount)) {
        //     $this->updatestudentBalance($student, $excess_amount);
            // }

            return $parent_payment;
        }
    }
    /**
     * Pay student due at once
     *
     * @param obj $parent_payment, string $type
     *
     * @return void
     */
    public function payAtOnce($parent_payment, $type)
    {
        //Get all unpaid transaction for the student
        $types = ['opening_balance','transport_fee','admission_fee','other_fee', $type];
        $due_transactions = FeeTransaction::where('student_id', $parent_payment->payment_for)
                                ->whereIn('type', $types)
                                ->where('payment_status', '!=', 'paid')
                                ->orderBy('transaction_date', 'asc')
                                ->get();

        $total_amount = $parent_payment->amount;

        $tranaction_payments = [];

        if ($due_transactions->count()) {
            foreach ($due_transactions as $transaction) {
                $transaction_before = $transaction->replicate();
                //If sell check status is final
                if ($transaction->type == 'fee' && $transaction->status != 'final') {
                    continue;
                }
                if ($total_amount > 0) {
                    $total_paid = $this->getTotalPaid($transaction->id);
                    $due = $transaction->final_total - $total_paid;

                    $now = \Carbon::now()->toDateTimeString();

                    $array = [
                            'fee_transaction_id' => $transaction->id,
                            'campus_id' => $transaction->campus_id,
                            'session_id'=>$this->getActiveSession(),
                            'system_settings_id' => $parent_payment->system_settings_id,
                            'method' => $parent_payment->method,
                            'transaction_no' => $parent_payment->method,
                            'card_transaction_number' => $parent_payment->card_transaction_number,
                            'card_number' => $parent_payment->card_number,
                            'card_type' => $parent_payment->card_type,
                            'card_holder_name' => $parent_payment->card_holder_name,
                            'card_month' => $parent_payment->card_month,
                            'card_year' => $parent_payment->card_year,
                            'card_security' => $parent_payment->card_security,
                            'cheque_number' => $parent_payment->cheque_number,
                            'bank_account_number' => $parent_payment->bank_account_number,
                            'paid_on' => $parent_payment->paid_on,
                            'created_by' => $parent_payment->created_by,
                            'payment_for' => $parent_payment->payment_for,
                            'parent_id' => $parent_payment->id,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];


                    $prefix_type = 'fee_payment';

                    $ref_count = $this->setAndGetReferenceCount($prefix_type, false, true);
                    //Generate reference number
                    $payment_ref_no = $this->generateReferenceNumber($prefix_type, $ref_count, $parent_payment->system_settings_id);
                    $array['payment_ref_no'] = $payment_ref_no;

                    if ($due <= $total_amount) {
                        $array['amount'] = $due;
                        $tranaction_payments[] = $array;

                        //Update transaction status to paid
                        $transaction->payment_status = 'paid';
                        $transaction->save();

                        $total_amount = $total_amount - $due;

                    //$this->activityLog($transaction, 'payment_edited', $transaction_before);
                    } else {
                        $array['amount'] = $total_amount;
                        $tranaction_payments[] = $array;

                        //Update transaction status to partial
                        $transaction->payment_status = 'partial';
                        $transaction->save();
                        $total_amount = 0;
                        //$this->activityLog($transaction, 'payment_edited', $transaction_before);

                        break;
                    }
                }
            }

            //Insert new transaction payments
            if (!empty($tranaction_payments)) {
                FeeTransactionPayment::insert($tranaction_payments);
            }
        }
        return $total_amount;
    }
    /**
     * Get total paid amount for a transaction
     *
     * @param int $transaction_id
     *
     * @return int
     */
    public function getTotalPaid($transaction_id)
    {
        $total_paid = FeeTransactionPayment::where('fee_transaction_id', $transaction_id)
                ->select(DB::raw('SUM(IF( is_return = 0, amount, amount*-1))as total_paid'))
                ->first()
                ->total_paid;

        return $total_paid;
    }

    /**
    * common function to get
    * list sell
    * @param int $session_id
    *
    * @return object
    */
    public function getListFeeTransaction($session_id)
    {
        $fee_transactions = FeeTransaction::leftJoin('students', 'fee_transactions.student_id', '=', 'students.id')

                ->leftJoin('users as u', 'fee_transactions.created_by', '=', 'u.id')
                ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
                ->leftJoin('sessions', 'fee_transactions.session_id', '=', 'sessions.id')
                ->join(
                    'campuses AS campus',
                    'fee_transactions.campus_id',
                    '=',
                    'campus.id'
                )
                ->where('fee_transactions.session_id', $session_id)
                ->where('fee_transactions.status', 'final')
                ->select(
                    'fee_transactions.id',
                    'fee_transactions.transaction_date',
                    'fee_transactions.voucher_no',
                    'fee_transactions.type',
                    'fee_transactions.payment_status',
                    'fee_transactions.final_total',
                    'c-class.title as current_class',
                    'students.father_name',
                    'students.roll_no as roll_no',
                    'students.status',
                    DB::raw("concat(sessions.title, ' - ' '(', sessions.status, ') ') as session_info"),
                    DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
                    // DB::raw('DATE_FORMAT(fee_transactions.transaction_date, "%Y/%m/%d") as transaction_date'),
                    DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                    DB::raw('(SELECT SUM(IF(TP.is_return = 1,-1*TP.amount,TP.amount)) FROM fee_transaction_payments AS TP WHERE
                        TP.fee_transaction_id=fee_transactions.id) as total_paid'),
                    'campus.campus_name as campus_name',
                )->orderBy('fee_transactions.transaction_date', 'desc');

        return $fee_transactions;
    }
    /**
     * Update the payment status for purchase or sell transactions. Returns
     * the status
     *
     * @param int $transaction_id
     *
     * @return string
     */
    public function updatePaymentStatus($transaction_id, $final_amount = null)
    {
        $status = $this->calculatePaymentStatus($transaction_id, $final_amount);
        FeeTransaction::where('id', $transaction_id)
            ->update(['payment_status' => $status]);

        return $status;
    }
    /**
     * Calculates the payment status and returns back.
     *
     * @param int $transaction_id
     * @param float $final_amount = null
     *
     * @return string
     */
    public function calculatePaymentStatus($transaction_id, $final_amount = null)
    {
        $total_paid = $this->getTotalPaid($transaction_id);
        if (is_null($final_amount)) {
            $final_amount = FeeTransaction::find($transaction_id)->final_total;
        }

        $status = 'due';
        if ($final_amount <= $total_paid) {
            $status = 'paid';
        } elseif ($total_paid > 0 && $final_amount > $total_paid) {
            $status = 'partial';
        }

        return $status;
    }


    public function getFeeTransaction($student_id, $session_id, $month)
    {
        $fee_transaction =FeeTransaction::with(['fee_lines','fee_lines.feeHead'])->where('student_id', $student_id)
               ->where('session_id', $session_id)->where('month', $month)->latest('id')->first();
        if (empty($fee_transaction)) {
            
            $fee_transaction =FeeTransaction::with(['fee_lines','fee_lines.feeHead'])->where('student_id', $student_id)
            ->latest('transaction_date', 'Y-m-d')->first();
        }
        return $fee_transaction;
    }


    /**
     * Query to get payment details for a customer
     *
     */
    public function __paymentQuery($student_id, $session_id, $start=null, $end = null)
    {
        $query = FeeTransactionPayment::with(['payment_account','sub_payments','sub_payments.fee_transaction'])->where('payment_for', $student_id)

            ->whereNull('parent_id');
        if (!empty($session_id)) {
            $query->where('session_id', $session_id);
        }

        if (!empty($start)  && !empty($end)) {
            $query->whereDate('paid_on', '>=', $start)
                        ->whereDate('paid_on', '<=', $end);
        }

        if (!empty($start)  && empty($end)) {
            $query->whereDate('paid_on', '<=', $start);
        }

        return $query->get();
    }
    /**
     * Query to get payment details for a customer
     *
     */
    public function __paymentReportQuery($start=null, $end = null)
    {
        $query = FeeTransactionPayment::with(['payment_account','sub_payments','sub_payments.fee_transaction'])

            ->whereNull('parent_id');
      
        if (!empty($start)  && !empty($end)) {
            $query->whereDate('paid_on', '>=', $start)
                        ->whereDate('paid_on', '<=', $end);
        }

        if (!empty($start)  && empty($end)) {
            $query->whereDate('paid_on', '<=', $start);
        }

        return $query->get();
    }
    public function allFeeTransaction()
    {
        $fee_transactions = FeeTransaction::leftJoin('students', 'fee_transactions.student_id', '=', 'students.id')

                ->leftJoin('users as u', 'fee_transactions.created_by', '=', 'u.id')
                ->leftJoin('classes as c-class', 'students.current_class_id', '=', 'c-class.id')
                ->leftJoin('classes as t-class', 'fee_transactions.class_id', '=', 't-class.id')
                ->leftJoin('sessions', 'fee_transactions.session_id', '=', 'sessions.id')
                ->join(
                    'campuses AS campus',
                    'fee_transactions.campus_id',
                    '=',
                    'campus.id'
                )
                //->where('fee_transactions.final_total','<', 0)
                ->where('fee_transactions.status', 'final')
                ->select(
                    'fee_transactions.id',
                    'fee_transactions.transaction_date',
                    'fee_transactions.month',
                    'fee_transactions.type',
                    'fee_transactions.voucher_no',
                    'fee_transactions.payment_status',
                    'fee_transactions.before_discount_total',
                   'fee_transactions.discount_amount',
                    'fee_transactions.final_total',
                    'c-class.title as current_class',
                    't-class.title as transaction_class',
                    'students.father_name',
                    'students.roll_no as roll_no',
                    'students.status',
                    DB::raw("concat(sessions.title, ' - ' '(', sessions.status, ') ') as session_info"),
                    DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
                    // DB::raw('DATE_FORMAT(fee_transactions.transaction_date, "%Y/%m/%d") as transaction_date'),
                    DB::raw("CONCAT(COALESCE(u.surname, ''),' ',COALESCE(u.first_name, ''),' ',COALESCE(u.last_name,'')) as added_by"),
                    DB::raw('(SELECT SUM(IF(TP.is_return = 1,-1*TP.amount,TP.amount)) FROM fee_transaction_payments AS TP WHERE
                        TP.fee_transaction_id=fee_transactions.id) as total_paid'),
                    'campus.campus_name as campus_name',
                )->orderBy('fee_transactions.transaction_date', 'desc');
        // Check for permitted campuses of a user
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $fee_transactions->whereIn('fee_transactions.campus_id', $permitted_campuses);
        }
        return $fee_transactions;
    }

public function getTotalFeePaid($start_date = null, $end_date = null, $student_id=null, $campus_id=null)
{
    $query = FeeTransactionPayment::leftJoin('fee_transactions', 'fee_transaction_payments.fee_transaction_id', '=', 'fee_transactions.id')
   ->whereIn('fee_transactions.type',['fee','other_fee','opening_balance','admission_fee'])
    ->select(DB::raw('COALESCE(SUM(IF( is_return = 0, fee_transaction_payments.amount, fee_transaction_payments.amount*-1)),0) as total_paid', 'paid_on'));
    if (!empty($start_date) && !empty($end_date)) {
        $query->whereDate('fee_transaction_payments.paid_on', '>=', $start_date)
                ->whereDate('fee_transaction_payments.paid_on', '<=', $end_date);
    }
    if ($start_date==null){   
       
        $query->whereDate('fee_transaction_payments.paid_on', '<=', $end_date);
        
    }
    if (!empty($student_id)) {
        $query->where('fee_transaction_payments.payment_for', $student_id);
    }
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
        $query->whereIn('fee_transaction_payments.campus_id', $permitted_campuses);
    }
    if (!empty($campus_id)) {
        $query->where('fee_transaction_payments.campus_id', $campus_id);
    }

    return $query->first()->total_paid;
}
    public function getFeePAidLast30Days($campus_id)
    {
        $query = FeeTransactionPayment::whereNull('fee_transaction_payments.parent_id')
                            ->whereBetween(DB::raw('date(paid_on)'), [\Carbon::now()->subDays(30), \Carbon::now()]);

        // Check for permitted campuses of a user
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('campus_id', $campus_id);
        }
        $query->select(
            DB::raw("DATE_FORMAT(paid_on, '%Y-%m-%d') as date"),
            DB::raw('COALESCE(SUM(IF( is_return = 0, amount, amount*-1)),0)as total_paid')
        )
        ->groupBy(DB::raw('Date(paid_on)'));


        $sells = $query->get();


        $sells = $sells->pluck('total_paid', 'date');


        return $sells;
    }

    public function getFeeTotals($start_date = null, $end_date = null, $campus_id = null, $created_by = null)
    {
        $query = FeeTransaction::whereIn('type',['fee','other_fee','opening_balance','admission_fee'])->select(
            DB::raw('SUM(final_total) as total_fee'),
            DB::raw('SUM(final_total - (SELECT COALESCE(SUM(tp.amount), 0) FROM fee_transaction_payments as tp WHERE tp.fee_transaction_id = fee_transactions.id) )  as total_fee_due'),
            DB::raw('SUM(before_discount_total) as total_fee_before_discount'),
            DB::raw('SUM(discount_amount) as total_fee_discount_amount'),
        );


        if (!empty($start_date) && !empty($end_date)) {
            $query->whereDate('fee_transactions.transaction_date', '>=', $start_date)
                ->whereDate('fee_transactions.transaction_date', '<=', $end_date);
        }

        if (empty($start_date) && !empty($end_date)) {
            $query->whereDate('fee_transactions.transaction_date', '<=', $end_date);
        }

        //Filter by the campus
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('fee_transactions.campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('fee_transactions.campus_id', $campus_id);
        }

        if (!empty($created_by)) {
            $query->where('fee_transactions.created_by', $created_by);
        }

        $fee_details = $query->first();


        $output['total_fee_due'] = $fee_details->total_fee_due;
        $output['total_fee'] = $fee_details->total_fee;
        $output['total_fee_before_discount'] = $fee_details->total_fee_before_discount;
        $output['total_fee_discount_amount'] = $fee_details->total_fee_discount_amount;
        $output['gap_to_check'] =$fee_details->total_fee-($fee_details->total_fee_before_discount- $fee_details->total_fee_discount_amount);

        return $output;
    }

       /**
     * Retrieves and sum total amount paid for a transaction
     * @param int $transaction_id
     *
     */
    public function getTotalAmountPaid($fee_transaction_id)
    {
        $paid = FeeTransactionPayment::where(
            'fee_transaction_id',
            $fee_transaction_id
        )->sum('amount');
        return $paid;
    }




public function payStudentIndividual($request,$inputs,$types, $format_data = true )
{
    $student_id = $request->input('student_id');
    $system_settings_id = auth()->user()->system_settings_id;
    $discount=$inputs['discount_amount'];

    $payment_types = $this->payment_types();
    $inputs['session_id']=$this->getActiveSession();

    if (!array_key_exists($inputs['method'], $payment_types)) {
        throw new \Exception("Payment method not found");
    }
    $inputs['paid_on'] = $inputs['paid_on']?$inputs['paid_on']: \Carbon::now()->toDateTimeString();
    if ($format_data) {
        $inputs['paid_on'] = $this->uf_date($inputs['paid_on'], true);
        $inputs['amount'] = $this->num_uf($inputs['amount']);
    }

    $inputs['created_by'] = auth()->user()->id;
    $inputs['payment_for'] = $student_id;
    $inputs['system_settings_id'] = $system_settings_id;
    $student = Student::where('system_settings_id', $system_settings_id)
                    ->findOrFail($student_id);
    $inputs['campus_id'] = $student->campus_id;
    $due_payment_type ='fee';
    $prefix_type = 'fee_payment';
    $ref_count = $this->setAndGetReferenceCount($prefix_type, false, true);
    //Generate reference number
    $payment_ref_no = $this->generateReferenceNumber($prefix_type, $ref_count, $system_settings_id);

    $inputs['payment_ref_no'] = $payment_ref_no;

    if (!empty($inputs['account_id'])) {
        $inputs['account_id'] = $inputs['account_id'];
    }

    //Upload documents if added
    $due_transactions = FeeTransaction::where('student_id', $student->id)
                            ->whereIn('type', $types)
                            ->where('payment_status', '!=', 'paid')
                            ->orderBy('transaction_date', 'asc')
                            ->get();
    if ($due_transactions->count()) {
        if ($inputs['discount_amount']>0) {
            $discount_amount=$this->num_uf($inputs['discount_amount']);
            foreach ($due_transactions as $transaction) {
                if ($discount_amount == 0) {
                    break;
                } else {
                    $up=FeeTransaction::find($transaction->id);

                    if ($up->final_total>=$discount_amount) {
                        $up->final_total=$transaction->final_total-$discount_amount;
                        $up->discount_type='fixed';
                        $up->discount_amount=$discount_amount;
                        $up->save();
                        $discount_amount=$discount_amount-$up->discount_amount;
                    } else {
                    //     $before_discount_total=$discount;
                    //     $discount_amount=$discount_amount-$up->final_total;
                    //     $up->final_total=($before_discount_total-$discount_amount )-$up->final_total;
                    //     $up->discount_type='fixed';
                    //     $up->discount_amount=$before_discount_total-$discount_amount;
                    //     $up->save();
                        $discount_amount-=$up->final_total;
                        $up->discount_amount=$up->final_total;
                        $up->final_total=0;
                        $up->save();
                    }
                }
            }
        }



        $parent_payment = FeeTransactionPayment::create($inputs);
        $inputs['transaction_type'] = $due_payment_type;
        event(new FeeTransactionPaymentAdded($parent_payment, $inputs));

        //Distribute above payment among unpaid transactions
        $excess_amount = $this->payAtOnceIndividual($parent_payment, $types);
        //Update excess amount
        // if (!empty($excess_amount)) {
    //     $this->updatestudentBalance($student, $excess_amount);
        // }

        return $parent_payment;
    }
}
/**
 * Pay student due at once
 *
 * @param obj $parent_payment, string $type
 *
 * @return void
 */
public function payAtOnceIndividual($parent_payment, $type)
{
    //Get all unpaid transaction for the student
    $types =  $type;
    $due_transactions = FeeTransaction::where('student_id', $parent_payment->payment_for)
                            ->whereIn('type', $types)
                            ->where('payment_status', '!=', 'paid')
                            ->orderBy('transaction_date', 'asc')
                            ->get();

    $total_amount = $parent_payment->amount;

    $tranaction_payments = [];

    if ($due_transactions->count()) {
        foreach ($due_transactions as $transaction) {
            $transaction_before = $transaction->replicate();
            //If sell check status is final
            if ($transaction->type == 'fee' && $transaction->status != 'final') {
                continue;
            }
            if ($total_amount > 0) {
                $total_paid = $this->getTotalPaid($transaction->id);
                $due = $transaction->final_total - $total_paid;

                $now = \Carbon::now()->toDateTimeString();

                $array = [
                        'fee_transaction_id' => $transaction->id,
                        'campus_id' => $transaction->campus_id,
                        'session_id'=>$this->getActiveSession(),
                        'system_settings_id' => $parent_payment->system_settings_id,
                        'method' => $parent_payment->method,
                        'transaction_no' => $parent_payment->method,
                        'card_transaction_number' => $parent_payment->card_transaction_number,
                        'card_number' => $parent_payment->card_number,
                        'card_type' => $parent_payment->card_type,
                        'card_holder_name' => $parent_payment->card_holder_name,
                        'card_month' => $parent_payment->card_month,
                        'card_year' => $parent_payment->card_year,
                        'card_security' => $parent_payment->card_security,
                        'cheque_number' => $parent_payment->cheque_number,
                        'bank_account_number' => $parent_payment->bank_account_number,
                        'paid_on' => $parent_payment->paid_on,
                        'created_by' => $parent_payment->created_by,
                        'payment_for' => $parent_payment->payment_for,
                        'parent_id' => $parent_payment->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];


                $prefix_type = 'fee_payment';

                $ref_count = $this->setAndGetReferenceCount($prefix_type, false, true);
                //Generate reference number
                $payment_ref_no = $this->generateReferenceNumber($prefix_type, $ref_count, $parent_payment->system_settings_id);
                $array['payment_ref_no'] = $payment_ref_no;

                if ($due <= $total_amount) {
                    $array['amount'] = $due;
                    $tranaction_payments[] = $array;

                    //Update transaction status to paid
                    $transaction->payment_status = 'paid';
                    $transaction->save();

                    $total_amount = $total_amount - $due;

                //$this->activityLog($transaction, 'payment_edited', $transaction_before);
                } else {
                    $array['amount'] = $total_amount;
                    $tranaction_payments[] = $array;

                    //Update transaction status to partial
                    $transaction->payment_status = 'partial';
                    $transaction->save();
                    $total_amount = 0;
                    //$this->activityLog($transaction, 'payment_edited', $transaction_before);

                    break;
                }
            }
        }

        //Insert new transaction payments
        if (!empty($tranaction_payments)) {
            FeeTransactionPayment::insert($tranaction_payments);
        }
    }
    return $total_amount;
}

public function getTransportTotals($start_date = null, $end_date = null, $campus_id = null, $created_by = null)
{
    $query = FeeTransaction::whereIn('type',['transport_fee'])->select(
        DB::raw('SUM(final_total) as total_fee'),
        DB::raw('SUM(final_total - (SELECT COALESCE(SUM(tp.amount), 0) FROM fee_transaction_payments as tp WHERE tp.fee_transaction_id = fee_transactions.id) )  as total_fee_due'),
        DB::raw('SUM(before_discount_total) as total_fee_before_discount'),
        DB::raw('SUM(discount_amount) as total_fee_discount_amount'),
    );


    if (!empty($start_date) && !empty($end_date)) {
        $query->whereDate('fee_transactions.transaction_date', '>=', $start_date)
            ->whereDate('fee_transactions.transaction_date', '<=', $end_date);
    }

    if (empty($start_date) && !empty($end_date)) {
        $query->whereDate('fee_transactions.transaction_date', '<=', $end_date);
    }

    //Filter by the campus
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
        $query->whereIn('fee_transactions.campus_id', $permitted_campuses);
    }
    if (!empty($campus_id)) {
        $query->where('fee_transactions.campus_id', $campus_id);
    }

    if (!empty($created_by)) {
        $query->where('fee_transactions.created_by', $created_by);
    }

    $fee_details = $query->first();


    $output['total_fee_due'] = $fee_details->total_fee_due;
    $output['total_fee'] = $fee_details->total_fee;
    $output['total_fee_before_discount'] = $fee_details->total_fee_before_discount;
    $output['total_fee_discount_amount'] = $fee_details->total_fee_discount_amount;
    $output['gap_to_check'] =$fee_details->total_fee-($fee_details->total_fee_before_discount- $fee_details->total_fee_discount_amount);

    return $output;
}

public function getTotalTransportPaid($start_date = null, $end_date = null, $student_id=null, $campus_id=null)
{
    $query = FeeTransactionPayment::leftJoin('fee_transactions', 'fee_transaction_payments.fee_transaction_id', '=', 'fee_transactions.id')
    ->whereIn('fee_transactions.type',['transport_fee'])
    ->select(DB::raw('COALESCE(SUM(IF( is_return = 0, amount, amount*-1)),0)as total_paid', 'paid_on'));
    if (!empty($start_date) && !empty($end_date)) {
        $query->whereDate('fee_transaction_payments.paid_on', '>=', $start_date)
                ->whereDate('fee_transaction_payments.paid_on', '<=', $end_date);
    }
    if (!empty($student_id)) {
        $query->where('fee_transaction_payments.payment_for', $student_id);
    }
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
        $query->whereIn('fee_transaction_payments.campus_id', $permitted_campuses);
    }
    if (!empty($campus_id)) {
        $query->where('fee_transaction_payments.campus_id', $campus_id);
    }

    return $query->first()->total_paid;
}
public function getSumFee($end_date = null,$campus_id=null){
    $query = FeeTransaction::whereIn('type',['fee','other_fee','opening_balance','admission_fee'])->select(
        DB::raw('SUM(final_total) as total_fee'),
        DB::raw('SUM(final_total )  as total_fee'));
        if (!empty($campus_id)) {
            $query->where('fee_transactions.campus_id', $campus_id);
        }

        if (!empty($end_date)) {
            $query->whereDate('fee_transactions.transaction_date', '<=', $end_date);
        }
        return $query->get();
}
}