<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Campus;
use DB;
use App\Utils\FeeTransactionUtil;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    /**
    * Constructor
    *
    * @param NotificationUtil $notificationUtil
    * @return void
    */
    public function __construct(FeeTransactionUtil $feeTransactionUtil)
    {
        $this->feeTransactionUtil= $feeTransactionUtil;
        $this->student_status_colors = [
            'active' => 'bg-success',
            'inactive' => 'bg-info',
            'struct_up' => 'bg-warning',
            'pass_out' => 'bg-danger',
             'took_slc' => 'bg-secondary',
        ];
        $this->limit=10;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('strength_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $fee_payments=$this->feeTransactionUtil->__paymentReportQuery('2023-05-02','2023-05-12');
        if (request()->ajax()) {

           }
    $campuses=Campus::forDropdown();

    return view('Report.payment')->with(compact('fee_payments'));

    }

}
