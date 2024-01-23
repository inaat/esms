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
use App\Utils\Util;
use App\Models\Sim;
use App\Models\Sms;
use Yajra\DataTables\Facades\DataTables;
use DB;

class WithdrawalRegisterController extends Controller
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
        //$sims=Sim::where('STATUS','SLC')->orderBy('SLC_NO')->get();
        //dd($sims);
 
        //  $ff=[];
        //  foreach ($sims as $key=>$sim) {
        //     //dd($sim);
        //       $with_date=null;
        //       if (!empty($sim->Date_of_withdrawl)) {
        //         if (!empty($sim->Date_of_withdrawl)) {
        //             $date_excel =\Carbon::parse($sim->Date_of_withdrawl)->format('Y-m-d');
        //             $date=$date_excel;
        //             $with_date = $date;
        //         }
        //     } else {
        //         $with_date = \Carbon::now()->format('Y-m-d');;
        //     }
        //     //dd($with_date);
        //     $student=Student::where('old_roll_no',$sim->Reg_no)->first();
        //     if (!empty($student)) {
        //         // $this->createWithdrawRegister($student);
        //         $withdrawal_register = WithdrawalRegister::where('student_id', $student->id)->first();
        //         // dd($withdrawal_register);
        //         $ref=$key+1;
        //         $slc_no='00'. $ref;
        //         $data=[
        //         'withdraw_reason'=>'Father Wish',
        //         'date_of_leaving'=>$with_date,
        //         'slc_no'=>$slc_no,
        //         'any_remarks'=>'',
        //         'co_curricular_activities'=>'Good',
        //         'leaving_session_id'=> $student->cur_session_id,
        //         'leaving_class_id'=>$student->current_class_id
        //         ];
        //         $withdrawal_register->update($data);
        //         $student->status='took_slc';
        //         $student->save();
        //         $this->commonUtil->setAndGetReferenceCount('slc_no', false, true);
        //     }
        //  }
        // dd('ok');
        // $students =Student::get();
        // foreach ($students as $student) {
        //     $data=[
        //         'adm_session_id'=>$student->adm_session_id,
        //         'student_id'=>$student->id,
        //         'campus_id'=>$student->campus_id,
        //         'admission_class_id'=>$student->adm_class_id
                
        //     ];
        //     WithdrawalRegister::create($data);
        // }
        // dd($students);

        if (!auth()->user()->can('withdrawal.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));

            $withdrawal_register= WithdrawalRegister::leftJoin('students', 'withdrawal_registers.student_id', '=', 'students.id')
        ->leftJoin('campuses', 'withdrawal_registers.campus_id', '=', 'campuses.id')
        ->leftJoin('classes as c-class', 'withdrawal_registers.leaving_class_id', '=', 'c-class.id')
        ->leftJoin('classes as promoted-class', 'students.current_class_id', '=', 'promoted-class.id')
        ->leftJoin('classes as adm-class', 'withdrawal_registers.admission_class_id', '=', 'adm-class.id')
        ->select([
            'students.admission_date',
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
            'students.birth_date',
            'students.father_name',
            'students.father_occupation',
            'students.std_permanent_address',
            'withdrawal_registers.date_of_leaving',
            'withdrawal_registers.id',
            'withdrawal_registers.local_reg_no',
            'withdrawal_registers.status',
            'promoted-class.title as promoted_class',
          //  'students.cast',
            'adm-class.title as admission_class',
            'c-class.title as leaving_class',
        ])->orderBy('students.admission_date');
        
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
         $withdrawal_register->whereIn('students.campus_id', $permitted_campuses);
        }
            // dd($withdrawal_register->first());
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $withdrawal_register->where('students.campus_id', $campus_id);
                }
            }
            if (request()->has('class_level_id')) {
                $class_level_id = request()->get('class_level_id');
                if (!empty($class_level_id)) {
                    // dd($class_level_id);
                    $class_levels=Classes::where('class_level_id', $class_level_id)->select(['id'])->get();
                    $withdrawal_register->whereIn('withdrawal_registers.admission_class_id', $class_levels);
                }
            }
            $datatable = Datatables::of($withdrawal_register)
        ->addColumn('remarks', function ($row) {
            if ($row->date_of_leaving != null) {
                return 'Took SLC';
            }
            if ($row->promoted_class!=$row->admission_class) {
                return 'Promoted To '.$row->promoted_class;
            }
            return '';
        })
        ->addColumn(
            'action',
            function ($row) {
                $html= '<div class="dropdown">
                 <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                 <ul class="dropdown-menu" style="">';
             
                if (auth()->user()->can('expense.expense_payment')) {
                    $html .= '<li><a href="#" data-container=".withdrawal_modal" data-href="' . action('Certificate\WithdrawalRegisterController@withdrawalRegisterEdit', [$row->id]) . '" class="dropdown-item btn-modal"><i class="fas fa-edit"></i> ' . __("english.update") . '</a></li>';
                    
                }
                $html .= '</ul></div>';

                return $html;
            }
        )
        ->addColumn('birth_date', function ($row) {
            $new_birth_date = explode('-', $row->birth_date);
            $year = $new_birth_date[0];
            $month = $new_birth_date[1];
            $day  = $new_birth_date[2];
            $birth_day=$this->commonUtil->numToWord($day,'en','ORDINAL');
            $birth_year=$this->commonUtil->numToWord($year,'en','SPELLOUT');
            $monthNum = $month;
            $dateObj = \Carbon::createFromFormat('!m', $monthNum);//Convert the number into month name
            $monthName = ucwords($dateObj->format('F'));
            $final_date=ucwords($birth_day).' '.$monthName.' '.ucwords($birth_year);
            $birth_date=$this->commonUtil->format_date($row->birth_date);
            return "<p align='center'>$final_date <br> $birth_date </p>";
        })
        ->addColumn('rownum', function ($row) {
            
            return $row->local_reg_no.'/'.$row->status;
        })
        ->editColumn('admission_date', '{{@format_date($admission_date)}}')
        ->editColumn('date_of_leaving', '@if(!empty($date_of_leaving)){{@format_date($date_of_leaving)}}@endif')

        ->filterColumn('student_name', function ($query, $keyword) {
            $query->whereRaw("CONCAT(COALESCE(students.first_name, ''), ' ', COALESCE(students.last_name, '')) like ?", ["%{$keyword}%"]);
        })
        ->filterColumn('father_name', function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('students.father_name', 'like', "%{$keyword}%");
            });
        })
        ->removeColumn(['promoted_class','id'])
        ->rawColumns(['rownum','remarks','birth_date','action']);

        
            return $datatable->make(true);
        }
        $campuses=Campus::forDropdown();
        $class_level=ClassLevel::forDropdown();
        // dd($sessions);
  
        return view('certificate.withdrawal.index')->with(compact('campuses', 'class_level'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('withdrawal.update')) {
            abort(403, 'Unauthorized action.');
        }
        $student=Student::find($id);
        $this->createWithdrawRegister($student);
        $withdrawal_register = WithdrawalRegister::where('student_id', $id)->first();
        if ($withdrawal_register->slc_no==null) {
            $ref_slc_no=$this->commonUtil->setAndGetReferenceCount('slc_no', true, false);
            $slc_no=$this->commonUtil->generateReferenceNumber('slc_no', $ref_slc_no);
        } else {
            $slc_no=$withdrawal_register->slc_no;
        }
        

        return view('certificate.withdrawal.edit')->with(compact('withdrawal_register', 'slc_no'));
    }


    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('withdrawal.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            DB::beginTransaction();
            
            $input = $request->only(['withdraw_reason','date_of_leaving','slc_no','any_remarks','co_curricular_activities','local_withdrawal_register_detail']);
            $withdrawal_register = WithdrawalRegister::findOrFail($id);
            $student = Student::findOrFail($withdrawal_register->student_id);
            if ($withdrawal_register->slc_no==null) {
                $this->commonUtil->setAndGetReferenceCount('slc_no', false, true);
            }
            $data=[
            'withdraw_reason'=>$input['withdraw_reason'],
            'date_of_leaving'=>$this->commonUtil->uf_date($input['date_of_leaving']),
            'slc_no'=>$input['slc_no'],
            'any_remarks'=>$input['any_remarks'],
            'co_curricular_activities'=>$input['co_curricular_activities'],
            'local_withdrawal_register_detail'=>$input['local_withdrawal_register_detail'],
            'leaving_session_id'=> $this->commonUtil->getActiveSession(),
            'leaving_class_id'=>$student->current_class_id
             ];
            $withdrawal_register->update($data);
            $student->status='took_slc';
            $student->save();
            $output = ['success' => true,
        'msg' => __("english.added_success")
    ];


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
    'msg' => __("english.something_went_wrong")
];
        }
        return redirect('students')->with('status', $output);
    }

    public function show($id)
    {
    }
    public function withdrawalPrint(Request $request)
    {
        if (!auth()->user()->can('withdrawal.print_withdrawal')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
            
                $input = $request->input();
                 
                DB::statement(DB::raw('set @rownum=0'));

                $withdrawal_register= WithdrawalRegister::leftJoin('students', 'withdrawal_registers.student_id', '=', 'students.id')
        ->leftJoin('campuses', 'withdrawal_registers.campus_id', '=', 'campuses.id')
        ->leftJoin('classes as c-class', 'withdrawal_registers.leaving_class_id', '=', 'c-class.id')
        ->leftJoin('classes as promoted-class', 'students.current_class_id', '=', 'promoted-class.id')
        ->leftJoin('classes as adm-class', 'withdrawal_registers.admission_class_id', '=', 'adm-class.id')
        ->where('withdrawal_registers.campus_id',$input['campus_id'])
        ->select([
            'students.admission_date',
            'students.admission_no',
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
            'students.birth_date',
            'students.mobile_no',
            'students.father_name',
            'students.father_occupation',
            'students.father_cnic_no',
            'students.cnic_no',
            'students.religion',
            'students.std_permanent_address',
            'withdrawal_registers.date_of_leaving',
            'promoted-class.title as promoted_class',
          //  'students.cast',
            'adm-class.title as admission_class',
            'c-class.title as leaving_class',
        ])->orderBy('students.admission_date');
        $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
     $withdrawal_register->whereIn('students.campus_id', $permitted_campuses);
    }
                $class_level=[0=>'All'];
                $class_level_id=$input['class_level_id'];
                if (!empty($class_level_id)) {
                    $class_level=ClassLevel::findOrFail($class_level_id);

                    // dd($class_level_id);
                    $class_levels=Classes::where('class_level_id', $class_level_id)->where('campus_id',$input['campus_id'])->select(['id'])->get();
                    $withdrawal_register->whereIn('withdrawal_registers.admission_class_id', $class_levels);
                }
                $withdrawal_register=$withdrawal_register->get();
                $receipt = $this->receiptContent($withdrawal_register, $class_level);

                if ($withdrawal_register->count()==0) {
                    $output = ['success' => 0,
                        'msg' => trans("english.no_data_available_in_table")
                        ];
                        return $output;
                }
                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => 0,
                        'msg' => trans("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
    
    public function withdrawalStudent()
    {
        if (!auth()->user()->can('withdrawal.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));

            $withdrawal_register= WithdrawalRegister::leftJoin('students', 'withdrawal_registers.student_id', '=', 'students.id')
    ->leftJoin('campuses', 'withdrawal_registers.campus_id', '=', 'campuses.id')
    ->leftJoin('classes as c-class', 'withdrawal_registers.leaving_class_id', '=', 'c-class.id')
    ->leftJoin('classes as promoted-class', 'students.current_class_id', '=', 'promoted-class.id')
    ->leftJoin('classes as adm-class', 'withdrawal_registers.admission_class_id', '=', 'adm-class.id')
    ->whereNotNull('withdrawal_registers.date_of_leaving')
        ->select([
        DB::raw('@rownum  := @rownum  + 1 AS rownum'),
        DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as student_name"),
        'students.birth_date',
        'students.father_name',
        'students.roll_no',
        'withdrawal_registers.slc_no',
        'withdrawal_registers.date_of_leaving',
        'promoted-class.title as promoted_class',
      //  'students.cast',
        'adm-class.title as admission_class',
        'c-class.title as leaving_class',
    ]);
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
     $withdrawal_register->whereIn('students.campus_id', $permitted_campuses);
    }
           // dd($withdrawal_register->first());
           if (request()->has('campus_id')) {
            $campus_id = request()->get('campus_id');
            if (!empty($campus_id)) {
                $withdrawal_register->where('students.campus_id', $campus_id);
            }
        }
        if (request()->has('class_id')) {
            $class_id = request()->get('class_id');
            if (!empty($class_id)) {
                $withdrawal_register->where('withdrawal_registers.admission_class_id', $class_id);
            }
        }
 
        if (request()->has('admission_no')) {
            $admission_no = request()->get('admission_no');
            if (!empty($admission_no)) {
                $withdrawal_register->where('students.admission_no', 'like', "%{$admission_no}%");
            }
        }
        if (request()->has('roll_no')) {
            $roll_no = request()->get('roll_no');
            if (!empty($roll_no)) {
                $withdrawal_register->where('students.roll_no', 'like', "%{$roll_no}%");
            }
        }
     
            if (request()->has('class_level_id')) {
                $class_level_id = request()->get('class_level_id');
                if (!empty($class_level_id)) {
                    // dd($class_level_id);
                    $class_levels=Classes::where('class_level_id', $class_level_id)->select(['id'])->get();
                    $withdrawal_register->whereIn('withdrawal_registers.admission_class_id', $class_levels);
                }
            }
            $datatable = Datatables::of($withdrawal_register)
    ->addColumn('remarks', function ($row) {
        if ($row->date_of_leaving != null) {
            return 'Took SLC';
        }
        if ($row->promoted_class!=$row->admission_class) {
            return 'Promoted To '.$row->promoted_class;
        }
        return '';
    })
    ->addColumn(
        'action',
        function ($row) {
            $html= '<div class="dropdown">
                 <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                 <ul class="dropdown-menu" style="">';
         
            $html .= '</ul></div>';

            return $html;
        }
    )
    ->addColumn('birth_date', function ($row) {
        $new_birth_date = explode('-', $row->birth_date);
        $year = $new_birth_date[0];
        $month = $new_birth_date[1];
        $day  = $new_birth_date[2];
        $birth_day=$this->commonUtil->numToWord($day,'en','ORDINAL');
        $birth_year=$this->commonUtil->numToWord($year,'en','SPELLOUT');
        $monthNum = $month;
        $dateObj = \Carbon::createFromFormat('!m', $monthNum);//Convert the number into month name
        $monthName = ucwords($dateObj->format('F'));
        $final_date=ucwords($birth_day).' '.$monthName.' '.ucwords($birth_year);
        $birth_date=$this->commonUtil->format_date($row->birth_date);
        return "<p align='center'>$final_date <br> $birth_date </p>";
    })
    ->editColumn('date_of_leaving', '@if(!empty($date_of_leaving)){{@format_date($date_of_leaving)}}@endif')

    ->filterColumn('student_name', function ($query, $keyword) {
        $query->whereRaw("CONCAT(COALESCE(students.first_name, ''), ' ', COALESCE(students.last_name, '')) like ?", ["%{$keyword}%"]);
    })
    ->filterColumn('father_name', function ($query, $keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('students.father_name', 'like', "%{$keyword}%");
        });
    })
    ->removeColumn(['promoted_class'])
    ->rawColumns(['rownum','remarks','birth_date','action']);

    
            return $datatable->make(true);
        }
        $sessions=Session::forDropdown();
        $class_level=ClassLevel::forDropdown();
        // dd($sessions);
        $campuses=Campus::forDropdown();
        return view('certificate.withdrawal.withdrawal_students')->with(compact('campuses', 'sessions', 'class_level'));
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
    private function receiptContent($withdrawal_register, $class_level)
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
    
        $output['html_content'] = view('certificate.withdrawal.print', compact('withdrawal_register', 'class_level'))->render();
            
        return $output;
    }

    public function withdrawalRegisterEdit($id){

        $withdrawal_register=WithdrawalRegister::where('id', $id)->first();


        return view('certificate.withdrawal.update_modal')->with(compact('withdrawal_register'));

    }
    public function withdrawalRegisterUpdate(Request $request, $id){
        // if (!auth()->user()->can('fee_head.update')) {
        //     abort(403, 'Unauthorized action.');
        // }
        try {
            $input = $request->only(['local_reg_no','status']);
            $withdrawal_register = WithdrawalRegister::find($id);
            $withdrawal_register->fill($input);
            $withdrawal_register->save();
            $output = ['success' => true,
            'msg' => __("english.updated_success")
        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
        }

        return  $output;

    }
    public function createWithdrawRegister($student)
    {
            $check_student=WithdrawalRegister::where('student_id', $student->id)->first();
            if (empty($check_student)){
               $data=[
                'adm_session_id'=>$student->adm_session_id,
                'student_id'=>$student->id,
                'campus_id'=>$student->campus_id,
                'admission_class_id'=>$student->adm_class_id
                
            ];
            WithdrawalRegister::create($data);
           }

        return true;
    }
}
