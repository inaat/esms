<?php

namespace App\Http\Controllers\Certificate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\Student;
use App\Models\Session;
use App\Models\ClassLevel;
use App\Models\Classes;
use App\Models\Certificate\WithdrawalRegister;
use App\Models\Certificate\CertificateIssue;
use App\Models\Certificate\CertificateType;

use App\Utils\Util;

use Yajra\DataTables\Facades\DataTables;
use DB;

class CertificatePrintController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('certificate.print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $certificate_type=CertificateType::forDropdown();

        return view('certificate.print.index')->with(compact('campuses','certificate_type'));
    }
     
    public function store(Request $request){
        if (!auth()->user()->can('certificate.print')) {
            abort(403, 'Unauthorized action.');
        }
        try {
             
            $output = ['success' => 0,
            'msg' => trans("messages.something_went_wrong")
            ];
            // dd($request);
            $input = $request->input();
            $student=Student::where('roll_no', $input['roll_no'])->first();
            $certificate_issue=CertificateIssue::with(['certificate_type','student','student.admission_class','student.current_class'])->where('student_id', $student->id)->where('certificate_type_id', $input['certificate_type_id'])->first();
            if (!empty($student)) {
                if ($input['certificate_type_id']==1) {
                    $withdrawal_register = WithdrawalRegister::with(['student','student.admission_class','student.current_class'])->where('student_id',  $student->id)->first();
                    if (!empty($withdrawal_register)) {
                        $receipt = $this->slcPrint($withdrawal_register, $certificate_issue);
                    }
                }else if($input['certificate_type_id']==2){
                    $receipt = $this->certificatePrint($certificate_issue,'certificate.print.birth');

                
                }else if($input['certificate_type_id']==3){
                    $receipt = $this->certificatePrint($certificate_issue,'certificate.print.character');
                }
                else if($input['certificate_type_id']==4){
                    $receipt = $this->certificatePrint($certificate_issue,'certificate.print.provisional');
                }

                
            if (!empty($receipt)) {
                $output = ['success' => 1, 'receipt' => $receipt];
            }
            }else{
                $output = ['success' => 0,
                'msg' => trans("messages.no_data")
            ];
            }
             
             

        }
          catch (\Exception $e) {
             \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
             
             $output = ['success' => 0,
                     'msg' => trans("messages.something_went_wrong")
                     ];
         }

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
    private function slcPrint($withdrawal_register, $certificate_issue)
    {
        $output = ['is_enabled' => false,
                        'print_type' => 'browser',
                        'html_content' => null,
                        'printer_config' => [],
                        'data' => []
                    ];
                    $new_birth_date = explode('-', $withdrawal_register->student->birth_date);
                    $year = $new_birth_date[0];
                    $month = $new_birth_date[1];
                    $day  = $new_birth_date[2];
                    $birth_day=$this->commonUtil->numToWord($day,'en','ORDINAL');
                    $birth_year=$this->commonUtil->numToWord($year,'en','SPELLOUT');
                    $monthNum = $month;
                    $dateObj = \Carbon::createFromFormat('!m', $monthNum);//Convert the number into month name
                    $monthName = ucwords($dateObj->format('F'));
                    $birth_date=ucwords($birth_day).' '.$monthName.' '.ucwords($birth_year);
        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];
    
        $output['html_content'] = view('certificate.print.slc', compact('withdrawal_register', 'certificate_issue','birth_date'))->render();
            
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
                    $new_birth_date = explode('-', $certificate_issue->student->birth_date);
                    $year = $new_birth_date[0];
                    $month = $new_birth_date[1];
                    $day  = $new_birth_date[2];
                    $birth_day=$this->commonUtil->numToWord($day,'en','ORDINAL');
                    $birth_year=$this->commonUtil->numToWord($year,'en','SPELLOUT');
                    $monthNum = $month;
                    $dateObj = \Carbon::createFromFormat('!m', $monthNum);//Convert the number into month name
                    $monthName = ucwords($dateObj->format('F'));
                    $birth_date=ucwords($birth_day).' '.$monthName.' '.ucwords($birth_year);
        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];
    
        $output['html_content'] = view($view, compact('certificate_issue','birth_date'))->render();
            
        return $output;
    }
}
