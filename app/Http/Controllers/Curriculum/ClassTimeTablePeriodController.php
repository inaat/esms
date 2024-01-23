<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use App\Models\Curriculum\ClassTimeTable;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassTimeTablePeriod;
use App\Models\Campus;
use App\Utils\Util;

use Yajra\DataTables\Facades\DataTables;
use DB;

class ClassTimeTablePeriodController extends Controller
{
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
       
        if (!auth()->user()->can('study_period.view')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            $classTimeTablePeriods = ClassTimeTablePeriod::leftjoin('campuses as cam', 'class_timetable_periods.campus_id', '=', 'cam.id')
            ->select([
            'class_timetable_periods.id',
            'class_timetable_periods.name',
            'class_timetable_periods.start_time',
            'class_timetable_periods.end_time',
            'class_timetable_periods.total_time',
            'class_timetable_periods.type',
            'cam.campus_name as campus_name'

        ]);
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $classTimeTablePeriods->whereIn('class_timetable_periods.campus_id', $permitted_campuses);
        }
            return Datatables::of($classTimeTablePeriods)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
if (auth()->user()->can('study_period.update')) {
    $html.='<li><a class="dropdown-item  edit_period_button"data-href="' . action('Curriculum\ClassTimeTablePeriodController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
}
if (auth()->user()->can('study_period.delete')) {
    $html.='<li><a class="dropdown-item  delete_period_button"data-href="' . action('Curriculum\ClassTimeTablePeriodController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';
}
                       $html .= '</ul></div>';
    
                        return $html;
                    }
                )
                ->editColumn('start_time', function ($row) {
                    $start_time_formatted = $this->commonUtil->format_time($row->start_time);
                    return $start_time_formatted ;
                })
                ->editColumn('end_time', function ($row) {
                    $end_time_formatted = $this->commonUtil->format_time($row->end_time);
                    return $end_time_formatted ;
                })
                ->editColumn('type', function ($row)  {
                    $type=__('english.period_type');
                    return $type[$row->type];
                })
                ->removeColumn('id')
                ->rawColumns(['action','campus_name'])
                ->make(true);
        }
  
        return view('Curriculum.class_time_table_period.index');
    }
         /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('study_period.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        return view('Curriculum.class_time_table_period.create')->with(compact('campuses'));
    }
    //   /**
    //  * Store a newly created resource in storage.
    //  * @param Request $request
    //  * @return Response
    //  */
    public function store(Request $request)
    {
        if (!auth()->user()->can('study_period.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['name', 'type','campus_id']);

            if ($input['type'] != 'flexible_shift') {
                $input['start_time'] = $this->commonUtil->uf_time($request->input('start_time'));
                $input['end_time'] = $this->commonUtil->uf_time($request->input('end_time'));
                $start = strtotime($input['start_time']);
                $end = strtotime($input['end_time']);
                $mins = ($end - $start) / 60;
                $input['total_time'] = $mins;

            } else {
                $input['start_time'] = null;
                $input['end_time'] = null;
            }
           
            ClassTimeTablePeriod::create($input);

            $output = ['success' => true,
                            'msg' => __("english.added_success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")

                        ];
        }

        return $output;
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('study_period.update')) {
            abort(403, 'Unauthorized action.');
        }
        $classTimeTablePeriod = ClassTimeTablePeriod::findOrFail($id);

        $campuses=Campus::forDropdown();

        return view('Curriculum.class_time_table_period.edit')->with(compact('campuses', 'classTimeTablePeriod'));
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
        if (!auth()->user()->can('study_period.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'type','campus_id']);
                if ($input['type'] != 'flexible_shift') {
                    $input['start_time'] = $this->commonUtil->uf_time($request->input('start_time'));
                    $input['end_time'] = $this->commonUtil->uf_time($request->input('end_time'));
                    $start = strtotime($input['start_time']);
                    $end = strtotime($input['end_time']);
                    $mins = ($end - $start) / 60;
                    $input['total_time'] = $mins;
    
                } else {
                    $input['start_time'] = null;
                    $input['end_time'] = null;
                }
                $period =ClassTimeTablePeriod::findOrFail($id);
                $period->fill($input);
                $period->save();
  
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
      
        if (!auth()->user()->can('study_period.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                
                $period =ClassTimeTablePeriod::findOrFail($id);
                $check=ClassTimeTable::where('period_id',$id)->first();
                if (!empty($check)) {
                    $output = ['success' => false,
                    'msg' => __("You Can't Delete because the are use Class Time Table")
                ];
                } else {
                
                $period->delete();

                $output = ['success' => true,
                'msg' => __("english.deleted_success")
                ];
                }
               

               
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
     * Gets the ClassTimeTablePeriod for 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $campus_id
     * @return \Illuminate\Http\Response
     */
    public function getPeriods(Request $request)
    {
        if (!empty($request->input('campus_id'))) {
            $campus_id = $request->input('campus_id');
            
            $classTimeTablePeriod = ClassTimeTablePeriod::forDropdown($campus_id);
            $html = '<option value="">' . __('english.please_select') . '</option>';
            //$html = '';
            if (!empty($classTimeTablePeriod)) {
                foreach ($classTimeTablePeriod as $id => $name) {
                    $html .= '<option value="' . $id .'">' . $name. '</option>';
                }
            }

            return $html;
        }
    }
}