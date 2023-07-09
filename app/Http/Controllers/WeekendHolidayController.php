<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeekendHoliday;
use App\Models\Classes;
use App\Models\Campus;
use App\Models\Student;
use App\Models\ClassSection;
use App\Models\Attendance;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmAttendance;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon;
use App\Utils\Util;
use App\Utils\NotificationUtil;

class WeekendHolidayController extends Controller
{
    public function __construct(Util $util, NotificationUtil $notificationUtil)
    {
        $this->util= $util;
        $this->notificationUtil= $notificationUtil;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //if PASSED IN PAST
    public function index()
    {
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));

            $weekendHoliday = WeekendHoliday::select(['name','from', 'to','description', 'id',
            DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            return Datatables::of($weekendHoliday)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                        $html.='<li><a class="dropdown-item "href="' . action('WeekendHolidayController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';


                        $html .= '<li><a class="dropdown-item delete_weekend_holiday_button" href="#" data-href="' . action('WeekendHolidayController@destroy', [$row->id]) . '"><i class="bx bx-trash" aria-hidden="true"></i>' . __("english.delete") . '</a></li>';

                        $html .= '</ul></div>';

                        return $html;
                    }
                )

                ->removeColumn('id')
                ->rawColumns(['action','name','from', 'to','description',' rownum'])
                ->make(true);
        }
        // $class_sections =ClassSection::with(['classes'])->get();
        // dd($class_sections);
        return view('weekend_holiday.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_sections =ClassSection::with(['classes','campuses'])->get();

        return view('weekend_holiday.create', compact('class_sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $sms=Sms::get();
        // foreach ($sms as $sm) {
        //     $data=[
        //     'mobile_number' => $sm->mobile,
        //     'sms_body'=>"Hi here is Explainer School software marketing team are you interested in digitalize your school with school management system check out our software demo here http://127.0.0.1:8000     Contact Us:03428927305:  https://www.youtube.com/watch?v=JNaLGOx90d8&ab_channel=ExplaineRKhaN"
        //     ];
        //     $response=$this->notificationUtil->sendSmsOnWhatsapp($data);
        // }
        //         dd('send');
        try {
            $input = $request->only(['name','description','type','class_section','employee_include']);
            $date=explode(' - ', $request->input('list_filter_date_range'));
            $start_date=Carbon::parse($date[0])->format('Y-m-d');
            $end_date=Carbon::parse($date[1])->format('Y-m-d');
            $input['from']=$start_date;
            $input['to']=$end_date;

            if (!empty($request->input('sms'))) {
                $input['sms']=1;
            }

            $days=$this->util->generateDateRange(Carbon::parse($date[0]), Carbon::parse($date[1]));

            DB::beginTransaction();
            if (!empty($request->input('employee_include'))) {
                $input['employee_include']=1;
                $this->employee_attendance($input, $days);
            }
            $weekendHoliday = WeekendHoliday::create($input);
            $this->student_attendance($input, $days);

            DB::commit();

            $output = ['success' => true,
                            'data' => $weekendHoliday,
                            'msg' => __("english.added_success")
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return redirect('weekend-holiday')->with('status', $output);
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
        if (request()->ajax()) {
            $award = Award::find($id);
            return view('weekend_holiday.edit')
                ->with(compact('award'));
        }
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
        if (request()->ajax()) {
            try {
                $input = $request->only(['title','description']);

                $award = Award::findOrFail($id);
                $award->title = $input['title'];
                $award->description= $input['description'];
                $award->save();

                $output = ['success' => true,
                            'msg' => __("english.updated_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $weekendHoliday = WeekendHoliday::findOrFail($id);
                $weekendHoliday->delete();

                $output = ['success' => true,
                            'msg' => __("english.deleted_success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
    public function student_attendance($input, $days)
    {
        foreach ($days as $key => $day) {
            if (Carbon::parse($day)->format('l')=='Sunday') {
                $status='S';
            } else {
                foreach ($input['class_section'] as $key => $section_id) {
                    # code...
                    $students=Student::where('current_class_section_id', $section_id)->where('status', 'active')->get();
                    //dd($students);
                    foreach ($students as $epe) {
                        $student= Student::where('id', $epe->id)->first();
                        $attendance = Attendance::where('student_id', $student->id)->whereDate('clock_in_time', $day)->first();

                        if (empty($attendance)) {
                            $epe_attendance = Attendance::create([
                                    'student_id' => $student->id,
                                    'clock_in_time' =>$day,
                                    'type' => $input['type'],
                                    'session_id' =>$this->notificationUtil->getActiveSession(),
                                ]);
                        }
                    }
                }
            }
        }
        if (!empty($input['sms'])) {
            foreach ($input['class_section'] as $key => $section_id) {
                # code...
                $students=Student::where('current_class_section_id', $section_id)->where('status', 'active')->get();
                //dd($students);
                foreach ($students as $epe) {
                    $student= Student::where('id', $epe->id)->first();

                    $response=$this->notificationUtil->SendNotification(null, $student, null, $input['description']);
                }
            }
        }
    }
    public function employee_attendance($input, $days)
    {
        foreach ($days as $key => $day) {
            if (Carbon::parse($day)->format('l')=='Sunday') {
                $status='S';
            } else {
                # code...
                $employees=HrmEmployee::where('status', 'active')->get();
                //dd($employee);
                foreach ($employees as $std) {
                    $employee= HrmEmployee::where('id', $std->id)->first();
                    $attendance = HrmAttendance::where('employee_id', $employee->id)->whereDate('clock_in_time', $day)->first();

                    if (empty($attendance)) {
                        $std_attendance = HrmAttendance::create([
                                'employee_id' => $employee->id,
                                'clock_in_time' =>$day,
                                'type' => $input['type'],
                                'session_id' =>$this->notificationUtil->getActiveSession(),
                            ]);
                    }
                }
            }
        }
        if (!empty($input['sms'])) {
            $employees=HrmEmployee::where('status', 'active')->get();
            //dd($employee);
            foreach ($employees as $std) {
                $employee= HrmEmployee::where('id', $std->id)->first();
                $response=$this->notificationUtil->SendNotification(null, $employee, null, $input['description']);
            }
        }
    }
}
