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

class CertificateController extends Controller
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

    public function create()
    {
        if (!auth()->user()->can('certificate.issue')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $certificate_type=CertificateType::forDropdown();

        return view('certificate.certificate_issue.create')->with(compact('campuses', 'certificate_type'));
    }

    public function issuePost(Request $request)
    {
        if (!auth()->user()->can('certificate.issue')) {
            abort(403, 'Unauthorized action.');
        }
        $input = $request->input();
        // $students=Student::whereNull('deleted_at');
        $campus_id=$input['campus_id'];
        $class_id=$input['adm_class_id'];
        $status=$input['status'];
        $certificate_type_id=$input['certificate_type_id'];
        $roll_no=$input['roll_no'];

        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $certificate_type=CertificateType::forDropdown();

        if ($input['certificate_type_id'] == 1) {
            $withdrawal_students=WithdrawalRegister::with(['student'=> function ($q) use ($roll_no) {
                // Query the name field in status table
                if (!empty($roll_no)) {
                    $q->where('roll_no', 'like', "%{$roll_no}%");
                }
            }
            ,'leaving_class'])->whereNotNull('withdrawal_registers.date_of_leaving')->get();
            return view('certificate.certificate_issue.create')->with(compact('withdrawal_students', 'campuses', 'roll_no', 'classes', 'certificate_type', 'campus_id', 'class_id', 'certificate_type_id'));
        } else {
            $students =Student::with(['admission_class','current_class']);
            if (!empty($campus_id)) {
                $students->where('campus_id', $campus_id);
            }
            if (!empty($status)) {
                $students->where('status', $status);
            }
            if (!empty($class_id)) {
                $students->where('current_class_id', $class_id);
            }
            if (!empty($roll_no)) {
                $students->where('roll_no', 'like', "%{$roll_no}%");
            }
            $students=$students->get();
            return view('certificate.certificate_issue.create')->with(compact('students', 'campuses', 'classes', 'roll_no', 'certificate_type', 'campus_id', 'class_id', 'certificate_type_id'));
        }
        $output = ['success' => false,
        'msg' => __("english.something_went_wrong")];
        return redirect()->back()->with('status', $output);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('certificate.issue')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->input();

            if ($input['certificate_type_id']==1) {
                $this->slcIssue($input);
            } else {
                $check_certificate_issue=CertificateIssue::where('student_id', $input['student_id'])
            ->where('certificate_type_id', $input['certificate_type_id'])->first();
                if (empty($check_certificate_issue)) {
                    $ref_certificate_no=$this->commonUtil->setAndGetReferenceCount('certificate_no', true, false);
                    $certificate_no=$this->commonUtil->generateReferenceNumber('certificate_no', $ref_certificate_no);

                    $data=[
                        'student_id'=>$input['student_id'],
                        'campus_id'=>$input['campus_id'],
                        'certificate_type_id'=>$input['certificate_type_id'],
                        'issue_date'=>$this->commonUtil->uf_date($input['issue_date']),
                        'certificate_no'=>$certificate_no
                        ];
                    $certificate_issue=CertificateIssue::create($data);
                    $this->commonUtil->setAndGetReferenceCount('certificate_no', false, true);
                }
            }
            $output = ['success' => true,
        'msg' => __("term.added_success")
    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
        'msg' => __("english.something_went_wrong")
    ];
        }
         return redirect('certificate-print')->with('status', $output);
         //return redirect()->back()->with('status', $output);

    }

    public function slcIssue($input)
    {
        $withdrawal_student=WithdrawalRegister::where('student_id', $input['student_id'])->first();
        $withdrawal_student->slc_issue_date=$this->commonUtil->uf_date($input['issue_date']);
        $withdrawal_student->save();
        $check_certificate_issue=CertificateIssue::where('student_id', $input['student_id'])
                                ->where('certificate_type_id', $input['certificate_type_id'])->first();
        if (empty($check_certificate_issue)) {
            $ref_certificate_no=$this->commonUtil->setAndGetReferenceCount('certificate_no', true, false);
            $certificate_no=$this->commonUtil->generateReferenceNumber('certificate_no', $ref_certificate_no);

            $data=[
            'student_id'=>$input['student_id'],
            'campus_id'=>$input['campus_id'],
            'certificate_type_id'=>$input['certificate_type_id'],
            'issue_date'=>$this->commonUtil->uf_date($input['issue_date']),
            'certificate_no'=>$certificate_no
        ];
            $certificate_issue=CertificateIssue::create($data);
            $this->commonUtil->setAndGetReferenceCount('certificate_no', false, true);
        }
    }
}
