<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassLevel;
use Yajra\DataTables\Facades\DataTables;

class ClassLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //if PASSED IN PAST
    public function index()
    {
        if (!auth()->user()->can('class_level.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $classLevel = ClassLevel::select(['title', 'description', 'id']);
            return Datatables::of($classLevel)
                ->addColumn(
                    'action',
                    '<div class="d-flex order-actions">
                    @can("class_level.update")
                    <button data-href="{{action(\'ClassLevelController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_class_level_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                        &nbsp;
                    @endcan
                    @can("class_level.delete")
                        <button data-href="{{action(\'ClassLevelController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_class_level_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                        @endcan

                    </div>'
                )

                ->removeColumn('id')
                ->rawColumns(['action','description','title'])
                ->make(true);
        }

        return view('admin.global_configuration.class_levels.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('class_level.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.global_configuration.class_levels.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('class_level.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['title','description']);
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $user_id = $request->session()->get('user.id');
            $input['system_settings_id'] = $system_settings_id;
            $input['created_by'] = $user_id;
            $class_level = ClassLevel::create($input);
            $output = ['success' => true,
                            'data' => $class_level,
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
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('class_level.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $class_level = ClassLevel::find($id);
            return view('admin.global_configuration.class_levels.edit')
                ->with(compact('class_level'));
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
        if (!auth()->user()->can('class_level.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $input = $request->only(['title','description']);

                $class_level = ClassLevel::findOrFail($id);
                $class_level->title = $input['title'];
                $class_level->description= $input['description'];
                $class_level->save();

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
        if (!auth()->user()->can('class_level.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $class_level = ClassLevel::findOrFail($id);
                $class_level->delete();

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
