<?php

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanRM\HrmDesignation;
use Yajra\DataTables\Facades\DataTables;

class HrmDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('designation.view')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            $hrmDesignations = HrmDesignation::select(['designation', 'id']);
            return Datatables::of($hrmDesignations)
                ->addColumn(
                    'action',
                    '
                    <div class="d-flex order-actions">
                    <button data-href="{{action(\'Hrm\HrmDesignationController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_designation_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                        &nbsp;
                        <button data-href="{{action(\'Hrm\HrmDesignationController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_designation_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                    </div>
                    '
                )
                
                ->removeColumn('id')
                ->rawColumns(['action','designation'])
                ->make(true);
        }
  
        return view('hrm.designation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('designation.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('hrm.designation.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('designation.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['designation']);

            $user_id = $request->session()->get('user.id');
            $input['created_by'] = $user_id;
            $designation = HrmDesignation::create($input);
      

            $output = ['success' => true,
                        'data' => $designation,
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
        if (!auth()->user()->can('designation.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            $designation = HrmDesignation::find($id);
            return view('hrm.designation.edit')
                ->with(compact('designation'));
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
        if (!auth()->user()->can('designation.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            try {
                $input = $request->only(['designation']);
  
                $designation = HrmDesignation::findOrFail($id);
                $designation->designation = $input['designation'];
                $designation->save();
  
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
      
      if (!auth()->user()->can('designation.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $department = HrmDesignation::findOrFail($id);
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
