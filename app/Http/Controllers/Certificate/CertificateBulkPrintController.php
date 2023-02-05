<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate\CertificateType;
use App\Models\Campus;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Certificate\WithdrawalRegister;
use App\Models\Certificate\CertificateIssue;
use App\Utils\Util;

use Yajra\DataTables\Facades\DataTables;
use DB;
class CertificateBulkPrintController extends Controller
{
    /**
      * Constructor
      *
      * @param Util $Util
      * @return void
      */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil= $commonUtil;
    }
    public function index()
    {
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        if (!auth()->user()->can('print.certificate')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $certificate_type=CertificateType::forDropdown();


        return view('certificate.certificate_bulk_print.create')->with(compact('campuses', 'certificate_type'));
    }
    public function store(Request $request)
    {
        if (!auth()->user()->can('print.certificate')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->input();
        $campus_id=$input['campus_id'];
        $class_id=$input['current_class_id'];
        $status=$input['status'];
        $certificate_type_id=$input['certificate_type_id'];
        //dd($input);
        $certificate_issue=CertificateIssue::leftjoin('students as std', 'certificate_issues.student_id', '=', 'std.id')
                        ->leftJoin('classes as c-class', 'std.current_class_id', '=', 'c-class.id')
                        ->leftJoin('certificate_types as c-type', 'certificate_issues.certificate_type_id', '=', 'c-type.id')
                        ->where('std.current_class_id', $class_id)
                        ->where('std.campus_id', $campus_id)
                        ->where('std.status', $status)
                        ->where('certificate_issues.certificate_type_id', $certificate_type_id)
                        ->select([
                            'certificate_issues.id',
                            'c-class.title as current_class',
                            'c-type.name as certificate_type',
                            DB::raw("CONCAT(COALESCE(std.first_name, ''),' ',COALESCE(std.last_name,'')) as student_name")

                        ])->get();
        //dd($certificate_issue);
        $campuses=Campus::forDropdown();
        $certificate_type=CertificateType::forDropdown();


        return view('certificate.certificate_bulk_print.create')->with(compact('campuses', 'certificate_type','certificate_type_id','certificate_issue'));
    }

    public function BulkPrint(Request $request)
    {
        if (!auth()->user()->can('print.certificate')) {
            abort(403, 'Unauthorized action.');
        }
        // try {
        //     $output = ['success' => 0,
        //     'msg' => trans("messages.something_went_wrong")
        //     ];
            $input = $request->input();

            $certificate_issue=CertificateIssue::with(['certificate_type','student','student.admission_class','student.current_class'])->whereIn('id', $input['student_checked'])->where('certificate_type_id', $input['certificate_type_id'])->get();
           //dd($certificate_issue);
            if (!empty($certificate_issue)) {
                if ($input['certificate_type_id']==2) {
                    $receipt = $this->certificatePrint($certificate_issue, 'certificate\certificate_bulk_print\print.birth');
                } elseif ($input['certificate_type_id']==3) {
                    $receipt = $this->certificatePrint($certificate_issue, 'certificate\certificate_bulk_print\print.character');
                } elseif ($input['certificate_type_id']==4) {
                    $receipt = $this->certificatePrint($certificate_issue, 'certificate\certificate_bulk_print\print.provisional');
                }


                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } else {
                $output = ['success' => 0,
                'msg' => trans("messages.no_data")
            ];
            }
        // } catch (\Exception $e) {
        //     \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        //     $output = ['success' => 0,
        //              'msg' => trans("messages.something_went_wrong")
        //              ];
        // }

        return $output;
    }
    private function certificatePrint($certificate_issue,$view)
    {
        $output = ['is_enabled' => false,
                        'print_type' => 'browser',
                        'html_content' => null,
                        'printer_config' => [],
                        'data' => []
                    ];
                    
        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];
    
        $output['html_content'] = view($view, compact('certificate_issue'))->render();
            
        return $output;
    }
}