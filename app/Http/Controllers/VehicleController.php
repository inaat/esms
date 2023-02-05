<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\HumanRM\HrmEmployee;

use DB;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('vehicle.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $vehicles=Vehicle::leftJoin('hrm_employees', 'vehicles.employee_id', '=', 'hrm_employees.id')
            ->leftJoin('campuses', 'vehicles.campus_id', '=', 'campuses.id')
            ->select(
                'vehicles.id',
                'vehicles.name',
                'vehicles.driver_license',
                'vehicles.year_made',
                'vehicles.vehicle_number',
                'vehicles.vehicle_model',
                DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,'')) as employee_name"),
            );
            // dd($vehicles->get());
            //Filter by the campus
    $permitted_campuses = auth()->user()->permitted_campuses();
    if ($permitted_campuses != 'all') {
     $vehicles->whereIn('vehicles.campus_id', $permitted_campuses);
    }
            $datatable = Datatables::of($vehicles)
            ->addColumn(
                'action',
                '<div class="d-flex order-actions">
        @can("vehicle.update")
        <button data-href="{{action(\'VehicleController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_vehicle_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
            &nbsp;
@endcan
        </div>'
            )

            ->editColumn('employee_name', function ($row) {
                return ucwords($row->employee_name);
            })




            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('hrm_employees.first_name', 'like', "%{$keyword}%");
                });
            })

            ->removeColumn('id');



            $rawColumns = ['action'];

            return $datatable->rawColumns($rawColumns)
              ->make(true);
        }


        return view('vehicles.index');
    }

    /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
    public function create()
    {
        if (!auth()->user()->can('vehicle.create')) {
            abort(403, 'Unauthorized action.');
        }
        $employees=HrmEmployee::driverDropdown();
        return view('vehicles.create')->with(compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('vehicle.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['name','vehicle_number','vehicle_model','employee_id','year_made']);
            $vehicle = Vehicle::create($input);
            $output = ['success' => true,
                            'data' => $vehicle,
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
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('vehicle.update')) {
            abort(403, 'Unauthorized action.');
        }
        $vehicle= Vehicle::find($id);
        $employees=HrmEmployee::driverDropdown();

        return view('vehicles.edit')->with(compact('employees', 'vehicle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('vehicle.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['name','vehicle_number','vehicle_model','employee_id','year_made']);
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->fill($input);
            $vehicle->save();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
