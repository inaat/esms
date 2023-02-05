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
use App\Models\HumanRM\HrmEmployee;

use App\Models\HumanRM\HrmDepartment;
use App\Models\HumanRM\HrmDesignation;

use Illuminate\Support\Facades\Validator;
use DB;
use File;

class IdCardPrintController extends Controller
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


    public function createClassWiseIdPrint()
    {
        if (!auth()->user()->can('print.student_card_print')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        return view('school-printing.id-card.create')->with(compact('campuses'));
    }
    public function classWiseIdPrintPost(Request $request)
    {
        if (!auth()->user()->can('print.student_card_print')) {
            abort(403, 'Unauthorized action.');
        }
        $feeCards=[];
        $input = $request->all();
        $validator = Validator::make($input, [
            'class_id' => "required",
            'campus_id' => "required",
        ]);
        $campus_id=$input['campus_id'];
        $class_id=$input['class_id'];
        $design_type=$input['design_type'];
        $class_section_id=$input['class_section_id'];
        $system_settings_id = session()->get('user.system_settings_id');
        $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
        $sections=ClassSection::forDropdown($system_settings_id, false, $input['class_id']);
        $fee_heads=$this->studentUtil->getFeeHeads($campus_id, $class_id);

        $campuses=Campus::forDropdown();
        $students=$this->studentUtil->getStudentList($system_settings_id, $class_id, $class_section_id, 'active');
        return view('school-printing.id-card.class-id-post')->with(compact('design_type', 'students', 'fee_heads', 'campuses', 'classes', 'sections', 'campus_id', 'class_id', 'class_section_id'));
    }
    public function PrintIdCard(Request $request)
    {
        if (!auth()->user()->can('print.student_card_print')) {
            abort(403, 'Unauthorized action.');
        }
        $input=$request->input();
        $design_type=$input['design_type'];

        $students=Student::with('current_class', 'current_class_section')->whereIn('id', $input['student_checked'])->get();
        // dd($students);
        if (File::exists(public_path('uploads/pdf/id-card.pdf'))) {
            File::delete(public_path('uploads/pdf/id-card.pdf'));
        }
        $pdf_name='id-card'.'.pdf';
        $pdf =  config('constants.mpdf');

        if ($design_type=="horizontal") {
            if ($pdf) {
                return view('MPDF.horizontal_student_id_card')->with(compact('students'));
            }
            $snappy  = \WPDF::loadView('school-printing.id-card.class-wise-id-card', compact('students'));
        } else {
            if ($pdf) {
                return view('MPDF.vertical_student_id_card')->with(compact('students'));
            }
            $snappy  = \WPDF::loadView('school-printing.id-card.vertical_id_card', compact('students'));
        }
        $snappy->setPaper('a4')->setOption('margin-top', 5)->setOption('margin-left', 0)->setOption('margin-right', 0)->setOption('margin-bottom', 0);
        $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file

        return $snappy->stream();
    }


    ///Employee Identity Card Area
    public function createEmployeeIdPrint()
    {
        if (!auth()->user()->can('print.employee_card_print')) {
            abort(403, 'Unauthorized action.');
        }

        $campuses=Campus::forDropdown();
        $designations = HrmDesignation::forDropdown();

        return view('school-printing.employee-id-card.create')->with(compact('campuses', 'designations'));
    }
    public function employeeIdPrintPost(Request $request)
    {
        if (!auth()->user()->can('print.employee_card_print')) {
            abort(403, 'Unauthorized action.');
        }
        $feeCards=[];
        $input = $request->all();
        $validator = Validator::make($input, [
            'campus_id' => "required",
        ]);
        $campus_id=$input['campus_id'];
        $designation_id=$input['designation_id'];
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        $designations = HrmDesignation::forDropdown();
        $employees=HrmEmployee::with('campuses', 'designations');
        if (!empty($designation_id)) {
            $employees=$employees->where('designation_id', $designation_id);
        }
        $employees=$employees->get();
        return view('school-printing.employee-id-card.employee-id-post')->with(compact('employees', 'campuses', 'campus_id', 'designations', 'designation_id'));
    }


    public function employeePrintIdCard(Request $request)
    {
        if (!auth()->user()->can('print.employee_card_print')) {
            abort(403, 'Unauthorized action.');
        }
        $input=$request->input();
        $employees=HrmEmployee::with('campuses', 'designations')->whereIn('id', $input['employee_checked'])->get();

        if (File::exists(public_path('uploads/pdf/employee-id-card.pdf'))) {
            File::delete(public_path('uploads/pdf/employee-id-card.pdf'));
        }
        $pdf_name='employee-id-card'.'.pdf';
        $pdf =  config('constants.mpdf');
        if ($pdf) {
            return view('MPDF.horizontal_employee_card')->with(compact('employees'));
        }
        $snappy  = \WPDF::loadView('school-printing.employee-id-card.employee-id-card', compact('employees'));

        $snappy->setPaper('a4')->setOption('margin-top', 5)->setOption('margin-left', 0)->setOption('margin-right', 0)->setOption('margin-bottom', 0);
        $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file

        return $snappy->stream();
    }
}
