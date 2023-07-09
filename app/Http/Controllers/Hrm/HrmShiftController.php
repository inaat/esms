<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanRM\HrmShift;
use App\Models\HumanRM\HrmEmployee;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use DB;
use App\Models\HumanRM\HrmEmployeeShift;

class HrmShiftController extends Controller
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
     * @return Response
     */
    public function index()
    {
        if (!auth()->user()->can('shift.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $shifts = HrmShift::
                        select([
                            'id',
                            'name',
                            'type',
                            'start_time',
                            'end_time',
                            'holidays'
                        ]);

            return Datatables::of($shifts)
                ->editColumn('start_time', function ($row) {
                    $start_time_formated = $this->commonUtil->format_time($row->start_time);
                    return $start_time_formated ;
                })
                ->editColumn('end_time', function ($row) {
                    $end_time_formated = $this->commonUtil->format_time($row->end_time);
                    return $end_time_formated ;
                })
                ->editColumn('type', function ($row) {
                    return __('english.' . $row->type);
                })
                ->editColumn('holidays', function ($row) {
                    if (!empty($row->holidays)) {
                        $holidays = array_map(function ($item) {
                            return __('english.' . $item);
                        }, $row->holidays);
                        return implode(', ', $holidays);
                    }
                })
                ->addColumn('action', function ($row) {
if (auth()->user()->can('shift.update')) {
    $html = '<a href="#" data-href="' . action('Hrm\HrmShiftController@edit', [$row->id]) . '" data-container="#edit_shift_modal" class="btn-modal btn btn-xs btn-primary"><i class="fas fa-edit" aria-hidden="true"></i> ' . __("messages.edit") . '</a> &nbsp;<a href="#" data-href="' . action('Hrm\HrmShiftController@getAssignUsers', [$row->id]) . '" data-container="#employee_shift_modal" class="btn-modal btn btn-xs btn-success"><i class="fas fa-users" aria-hidden="true"></i> ' . __("english.assign_users") . '</a>';
    return $html;
}else{
    return '';
}
                })
                ->removeColumn('id')
                ->rawColumns(['action', 'type'])
                ->make(true);
        }
        return view('hrm.shift.index');

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!auth()->user()->can('shift.create')) {
            abort(403, 'Unauthorized action.');
        }
        $days = $this->commonUtil->getDays();

        return view('hrm.shift.create')->with(compact('days'));;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('shift.create')) {
            abort(403, 'Unauthorized action.');
        }
     
        try {
            $input = $request->only(['name', 'type', 'holidays']);

            if ($input['type'] != 'flexible_shift') {
                $input['start_time'] = $this->commonUtil->uf_time($request->input('start_time'));
                $input['end_time'] = $this->commonUtil->uf_time($request->input('end_time'));
            } else {
                $input['start_time'] = null;
                $input['end_time'] = null;
            }
            $user_id = $request->session()->get('user.id');
            $input['created_by'] = $user_id;
            HrmShift::create($input);

            $output = ['success' => true,
                            'msg' => __("english.added_success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")

                        ];
        }

        return $output;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('essentials::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('shift.update')) {
            abort(403, 'Unauthorized action.');
        }
        $shift = HrmShift::findOrFail($id);

        $days = $this->commonUtil->getDays();

        return view('hrm.shift.create')->with(compact('shift', 'days'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('shift.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
           
 
            $input = $request->only(['name', 'type', 'holidays']);

            if ($input['type'] != 'flexible_shift') {
                $input['start_time'] = $this->commonUtil->uf_time($request->input('start_time'));
                $input['end_time'] = $this->commonUtil->uf_time($request->input('end_time'));
            } else {
                $input['start_time'] = null;
                $input['end_time'] = null;
            }

            if (!empty($input['holidays'])) {
                $input['holidays'] = json_encode($input['holidays']);
            } else {
                $input['holidays'] = null;
            }

            $shift = HrmShift::where('id', $id)
                        ->update($input);

            $output = ['success' => true,
                                'msg' => __("english.updated_success")
                            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
            $output = ['success' => false,
                                'msg' => __("messages.something_went_wrong")

                            ];
        }

        return $output;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAssignUsers($shift_id)
    {
        // $business_id = request()->session()->get('user.business_id');
        // $is_admin = $this->commonUtil->is_admin(auth()->user(), $business_id);

        // if (!(auth()->user()->can('superadmin') || $this->commonUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) && !$is_admin) {
        //     abort(403, 'Unauthorized action.');
        // }
        if (!auth()->user()->can('shift.update')) {
            abort(403, 'Unauthorized action.');
        }
        $shift = HrmShift::with(['employee_shifts'])
                    ->findOrFail($shift_id);

        $employees = HrmEmployee::forDropdown();

        $employee_shifts = [];

        if (!empty($shift->employee_shifts)) {
            foreach ($shift->employee_shifts as $employee_shift) {
                $employee_shifts[$employee_shift->employee_id] = [
                    'start_date' => !empty($employee_shift->start_date) ? $this->commonUtil->format_date($employee_shift->start_date) : null,
                    'end_date' => !empty($employee_shift->end_date) ? $this->commonUtil->format_date($employee_shift->end_date) : null
                ];
            }
        }

        return view('hrm.shift.add_shift_employees')
                ->with(compact('shift', 'employees', 'employee_shifts'));
    }

    public function postAssignUsers(Request $request)
    {
        //dd($request->input());
        // if (!(auth()->user()->can('superadmin') || $this->commonUtil->hasThePermissionInSubscription($business_id, 'essentials_module')) && !$is_admin) {
        //     abort(403, 'Unauthorized action.');
        // }
       //dd($request->input());
       if (!auth()->user()->can('shift.update')) {
        abort(403, 'Unauthorized action.');
    }
        try {
            $shift_id = $request->input('shift_id');
            $shift = HrmShift::find($shift_id);

            $user_shifts = $request->input('employee_shift');
            $user_shift_data = [];
            $user_ids = [];
            foreach ($user_shifts as $key => $value) {
                if (!empty($value['is_added'])) {
                    $user_ids[] = $key;
                    HrmEmployeeShift::updateOrCreate(
                        [
                            'hrm_shift_id' => $shift_id,
                            'employee_id' => $key
                        ],
                        [
                            'start_date' => !empty($value['start_date']) ? $this->commonUtil->uf_date($value['start_date']) : null,
                            'end_date' => !empty($value['end_date']) ? $this->commonUtil->uf_date($value['end_date']) : null,
                        ]
                    );
                }
            }

            HrmEmployeeShift::where('hrm_shift_id', $shift_id)
                            ->whereNotIn('employee_id', $user_ids)
                            ->delete();
            
            $output = ['success' => true,
                            'msg' => __("english.added_success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")

                        ];
        }

        return $output;
    }
      
    
}
