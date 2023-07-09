<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeIncrementDecrement;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Student;

use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use DB;

class FeeIncrementController extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('fee.increment_decrement')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');

            $fee_increment_decrement = FeeIncrementDecrement::leftjoin('campuses', 'fee_increment_decrements.campus_id', '=', 'campuses.id')
                        ->leftjoin('classes as c', 'fee_increment_decrements.class_id', '=', 'c.id')
                        ->leftjoin('class_sections as cs', 'fee_increment_decrements.class_section_id', '=', 'cs.id')
                        ->leftJoin('sessions', 'fee_increment_decrements.session_id', '=', 'sessions.id')

                        ->select(
                            'fee_increment_decrements.id',
                            'fee_increment_decrements.transport_fee',
                            'fee_increment_decrements.tuition_fee',
                            'campuses.campus_name as campus_name',
                            'c.title as class_name',
                            'cs.section_name',
                            DB::raw("concat(sessions.title, ' - ' '(', sessions.status, ') ') as session_info")
                        );
            // Check for permitted campuses of a user
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $fee_increment_decrement->whereIn('fee_increment_decrements.campus_id', $permitted_campuses);
            }
            return DataTables::of($fee_increment_decrement)

                           ->editColumn(
                               'tuition_fee',
                               '<span class="tuition_fee" data-orig-value="{{$tuition_fee}}">@format_currency($tuition_fee)</span>'
                           )
                           ->editColumn(
                               'transport_fee',
                               '<span class="transport_fee" data-orig-value="{{$transport_fee}}">@format_currency($transport_fee)</span>'
                           )
                           ->removeColumn('id')
                           ->rawColumns(['campus_name','class_name','tuition_fee','transport_fee'])
                           ->make(true);
        }
        return view('fee-increment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('fee.increment_decrement')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        return view('fee-increment.create')->with(compact('campuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('fee.increment_decrement')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $input = $request->only(['campus_id','class_id','tuition_fee','class_section_id','transport_fee']);
            $input['tuition_fee']=$this->commonUtil->num_uf($input['tuition_fee']);
            $input['transport_fee']=$this->commonUtil->num_uf($input['transport_fee']);
            $input['session_id']=$this->commonUtil->getActiveSession();
            $fee_increment_decrement = FeeIncrementDecrement::create($input);
            if (!empty($input['class_section_id'])) {
                $students = Student::where('campus_id', $input['campus_id'])
                ->where('status', 'active')
                ->where('current_class_id', $input['class_id'])
                ->where('current_class_section_id', $input['class_section_id'])
                ->get();

                foreach ($students as $student) {
                    $std = Student::find($student->id);

                    if ($student->student_tuition_fee>0) {
                        $std->student_tuition_fee= $student->student_tuition_fee+$input['tuition_fee'];
                    }
                    if ($student->student_transport_fee>0) {
                        $std->student_transport_fee= $student->student_transport_fee+$input['transport_fee'];
                    }
                    $std->save();
                }
            } else {
                $students = Student::where('campus_id', $input['campus_id'])
                ->where('status', 'active')
                ->where('current_class_id', $input['class_id'])
                ->get();
                foreach ($students as $student) {
                    $std = Student::find($student->id);

                    if ($student->student_tuition_fee>0) {
                        $std->student_tuition_fee= $student->student_tuition_fee+$input['tuition_fee'];
                    }
                    if ($student->student_transport_fee>0) {
                        $std->student_transport_fee= $student->student_transport_fee+$input['transport_fee'];
                    }
                    $std->save();
                }
            }
            DB::commit();
            $output = ['success' => true,
                            'msg' => __("english.added_success")
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
