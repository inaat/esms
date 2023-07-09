<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Award;
use Yajra\DataTables\Facades\DataTables;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //if PASSED IN PAST
    public function index()
    {
        if (!auth()->user()->can('award.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $award = Award::select(['title', 'description', 'id']);
            return Datatables::of($award)
                ->addColumn(
                    'action',
                    '<div class="d-flex order-actions">
                     @can("award.update")
                    <button data-href="{{action(\'AwardController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_award_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                        &nbsp;
                    @endcan
                        @can("award.delete")
                        <button data-href="{{action(\'AwardController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_award_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                     @endcan
                    </div>'
                )

                ->removeColumn('id')
                ->rawColumns(['action','description','title'])
                ->make(true);
        }

        return view('admin.global_configuration.award.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('award.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.global_configuration.award.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        if (!auth()->user()->can('award.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['title','description']);
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $user_id = $request->session()->get('user.id');
            $input['system_settings_id'] = $system_settings_id;
            $input['created_by'] = $user_id;
            $award = Award::create($input);
            $output = ['success' => true,
                            'data' => $award,
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
        if (!auth()->user()->can('award.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $award = Award::find($id);
            return view('admin.global_configuration.award.edit')
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
        if (!auth()->user()->can('award.update')) {
            abort(403, 'Unauthorized action.');
        }
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
        if (!auth()->user()->can('award.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $award = Award::findOrFail($id);
                $award->delete();

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
