<?php

namespace App\Http\Controllers\Curriculum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\Classes;
use App\Models\Campus;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use App\Models\HumanRM\HrmEmployee;

use DB;
class ClassSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($class_id)
    {
       
        if (!auth()->user()->can('section.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');

            $sections = ClassSection::where('class_sections.class_id', $class_id)
                        ->leftjoin('campuses as c', 'class_sections.campus_id', '=', 'c.id')
                        ->leftjoin('classes as l', 'class_sections.class_id', '=', 'l.id')
                        ->select(['class_sections.id', 'class_sections.section_name','l.title as class_name',
                           'c.campus_name as campus_name']);
                           $permitted_campuses = auth()->user()->permitted_campuses();
                           if ($permitted_campuses != 'all') {
                             $sections->whereIn('class_sections.campus_id', $permitted_campuses);
                           }
            return DataTables::of($sections)
                           ->addColumn(
                               'action',
                               '<div class="dropdown">
                               <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> @lang("lang.actions")</button>
                               <ul class="dropdown-menu">
                                   <li><a class="dropdown-item edit_class_section_button" data-href="{{action(\'ClassSectionController@edit\',[$id])}}" data-container=".discounts_model"><i class="bx bxs-edit f-16 mr-15 "></i> @lang("english.edit")</a>
                                   </li>
                               </ul>
                           </div>'
                           )
                           ->editColumn('section_name', function ($row)  {
                            return '<div><a  href="' . action('Curriculum\CurriculumController@index', [$row->id]) . '">
                            '.ucwords($row->section_name).'
                            </a></div>';
                            
                        })
                       
                           ->removeColumn('id')
                           ->rawColumns(['action', 'classes.title','campus_name','class_name','section_name'])
                           ->make(true);
        }
        
        return view('Curriculum.class_wise_section.index')->with(compact('class_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('section.create')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id);
        $teachers=HrmEmployee::teacherDropdown();
        return view('admin.class_section.create')->with(compact('campuses','classes','teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (!auth()->user()->can('section.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['section_name','campus_id','class_id','teacher_id']);
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $user_id = $request->session()->get('user.id');
            $input['system_settings_id'] = $system_settings_id;
            $input['created_by'] = $user_id;
            $class_section=ClassSection::create($input);
            $output = ['success' => true,
                            'data' => $class_section,
                            'msg' => __("class_section.added_success")
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
        if (!auth()->user()->can('section.update')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $sections = ClassSection::where('system_settings_id', $system_settings_id)->find($id);
        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id);
        $teachers=HrmEmployee::teacherDropdown();
        return view('admin.class_section.edit')->with(compact('classes','campuses','sections','teachers'));

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
        if (!auth()->user()->can('section.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['section_name','campus_id','class_id','teacher_id']);
            $system_settings_id = session()->get('user.system_settings_id');
            $sections = ClassSection::where('system_settings_id', $system_settings_id)->find($id);
            $sections->fill($input);
            $sections->save();
            $output = ['success' => true,
            'msg' => __("class_section.updated_success")
        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
    
            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
        }
    
        return  $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
