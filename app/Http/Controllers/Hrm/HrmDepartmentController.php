<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanRM\HrmDepartment;
use Yajra\DataTables\Facades\DataTables;

class HrmDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('department.view')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            $hrmDepartments = HrmDepartment::select(['department', 'id']);
            return Datatables::of($hrmDepartments)
                ->addColumn(
                    'action',
                    '
                    <div class="d-flex order-actions">
                    @can("department.update")
                    <button data-href="{{action(\'Hrm\HrmDepartmentController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_department_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                        &nbsp;
                        @endcan
                        @can("department.delete")

                        <button data-href="{{action(\'Hrm\HrmDepartmentController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_department_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                        @endcan
                        </div>
                    '
                )
                
                ->removeColumn('id')
                ->rawColumns(['action','department'])
                ->make(true);
        }
  
        return view('hrm.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('department.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('hrm.department.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('department.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['department']);

            $user_id = $request->session()->get('user.id');
            $input['created_by'] = $user_id;
            $department = HrmDepartment::create($input);
      

            $output = ['success' => true,
                        'data' => $department,
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
        if (!auth()->user()->can('department.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            $department = HrmDepartment::find($id);
            return view('hrm.department.edit')
                ->with(compact('department'));
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
        if (!auth()->user()->can('department.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            try {
                $input = $request->only(['department']);
  
                $department = HrmDepartment::findOrFail($id);
                $department->department = $input['department'];
                $department->save();
  
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
      
      if (!auth()->user()->can('department.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $department = HrmDepartment::findOrFail($id);
                $department->delete();

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
}
