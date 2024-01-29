<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassLevel;
use App\Models\Classes;
use App\Models\Campus;
use App\Models\ClassSection;

use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use DB;

class ClassController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('class.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');

            $classes = Classes::where('classes.system_settings_id', $system_settings_id)
                        ->leftjoin('campuses as c', 'classes.campus_id', '=', 'c.id')
                        ->leftjoin('class_levels as l', 'classes.class_level_id', '=', 'l.id')
                        ->select(['classes.id', 'classes.title','l.title as class_level',
                           'c.campus_name as campus_name','classes.tuition_fee', 'classes.admission_fee','classes.admission_fee','classes.transport_fee','classes.security_fee','classes.prospectus_fee']);
                           $permitted_campuses = auth()->user()->permitted_campuses();
                           if ($permitted_campuses != 'all') {
                             $classes->whereIn('classes.campus_id', $permitted_campuses);
                           }
            return DataTables::of($classes)
                           ->addColumn(
                        'action',
                    function ($row) {
                        $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
if (auth()->user()->can('class.update')) {
    $html.='<li><a class="dropdown-item  edit_class_button" data-container=".discounts_model" data-href="' . action('ClassController@edit', [$row->id]) . '"><i class="bx bxs-edit bxs-edit f-16 mr-15 "></i> ' . __("english.edit") . '</a></li>';
}
                        if (auth()->user()->can('section.view')) {
    $html.='<li><a class="dropdown-item "href="' . action('Curriculum\ClassSectionController@index', [$row->id]) . '"><i class="lni lni-eye "></i> ' . __("english.manage_section") . '</a></li>';
}
                       $html .= '</ul></div>';
    
                        return $html;
                    }

                           )
                           ->filterColumn('campus_name', function ($query, $keyword) {
                            $query->where( function($q) use($keyword) {
                                $q->where('c.campus_name', 'like', "%{$keyword}%");
                            });
                        })
                           ->filterColumn('class_level', function ($query, $keyword) {
                            $query->where( function($q) use($keyword) {
                                $q->where('l.title', 'like', "%{$keyword}%");
                            });
                        })
                           ->filterColumn('title', function ($query, $keyword) {
                            $query->where( function($q) use($keyword) {
                                $q->where('classes.title', 'like', "%{$keyword}%");
                            });
                        })
                    
                        ->editColumn('title', function ($row)  {
                            return '<div><a  href="' . action('Curriculum\ClassSectionController@index', [$row->id]) . '">
                            '.ucwords($row->title).'
                            </a></div>';
                            
                        })
                          ->editColumn('tuition_fee', '{{@num_format($tuition_fee)}}')
                          ->editColumn('admission_fee', '{{@num_format($admission_fee)}}')
                          ->editColumn('transport_fee', '{{@num_format($transport_fee)}}')
                          ->editColumn('security_fee', '{{@num_format($security_fee)}}')
                          ->editColumn('prospectus_fee', '{{@num_format($prospectus_fee)}}')
                           ->removeColumn('id')
                           ->rawColumns(['action', 'title'])
                           ->make(true);
        }
        return view('admin.classes.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('class.create')) {
            abort(403, 'Unauthorized action.');
        }

        $campuses=Campus::forDropdown();
        $classLevel=ClassLevel::forDropdown();
        return view('admin.classes.create')->with(compact('campuses', 'classLevel'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('class.create')) {
            abort(403, 'Unauthorized action.');
        }
      

        try {
            
            $input = $request->only(['title','campus_id','class_level_id','tuition_fee', 'admission_fee','transport_fee','security_fee','prospectus_fee']);
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $user_id = $request->session()->get('user.id');
            $input['system_settings_id'] = $system_settings_id;
            $input['created_by'] = $user_id;
            $input['tuition_fee'] =  $this->commonUtil->num_uf($input['tuition_fee']);
            $input['admission_fee'] =  $this->commonUtil->num_uf($input['admission_fee']);
            $input['transport_fee'] =  $this->commonUtil->num_uf($input['transport_fee']);
            $input['security_fee'] =  $this->commonUtil->num_uf($input['security_fee']);
            $input['prospectus_fee'] =  $this->commonUtil->num_uf($input['prospectus_fee']);
            $classes = Classes::create($input);
            $output = ['success' => true,
                            'data' => $classes,
                            'msg' => __("class.added_success")
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
        $classes=Classes::find($id);
        return view('admin.classes.profile')->with(compact('classes'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('class.update')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $classes = Classes::where('system_settings_id', $system_settings_id)->find($id);
        $campuses=Campus::forDropdown();
        $classLevel=ClassLevel::forDropdown();
        return view('admin.classes.edit')->with(compact('classes', 'campuses', 'classLevel'));
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
        if (!auth()->user()->can('class.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['title','campus_id','class_level_id','tuition_fee', 'admission_fee','transport_fee','security_fee','prospectus_fee']);
            $system_settings_id = session()->get('user.system_settings_id');
            $input['tuition_fee'] =  $this->commonUtil->num_uf($input['tuition_fee']);
            $input['admission_fee'] =  $this->commonUtil->num_uf($input['admission_fee']);
            $input['transport_fee'] =  $this->commonUtil->num_uf($input['transport_fee']);
            $input['security_fee'] =  $this->commonUtil->num_uf($input['security_fee']);
            $input['prospectus_fee'] =  $this->commonUtil->num_uf($input['prospectus_fee']);
    
            $classes = Classes::where('system_settings_id', $system_settings_id)->find($id);
            $classes->fill($input);
            $classes->save();
            $output = ['success' => true,
            'msg' => __("english.updated_success")
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
      /**
     * Gets the Classes for the given unit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $unit_id
     * @return \Illuminate\Http\Response
     */
    public function getCampusClass(Request $request)
    {
        
        if (!empty($request->input('campus_id'))) {
            $campus_id = $request->input('campus_id');
            
            $system_settings_id = 1;
            $classes = Classes::forDropdown($system_settings_id,false,$campus_id);
            $html = '<option value="">' . __('english.please_select') . '</option>';
            //$html = '';
            if (!empty($classes)) {
                foreach ($classes as $id => $title) {
                    $html .= '<option value="' . $id .'">' . $title. '</option>';
                }
            }

            return $html;
        }
    }
      
      /**
     * Gets the Classes for the given unit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $unit_id
     * @return \Illuminate\Http\Response
     */
    public function getClassFee(Request $request)
    {
        
        if (!empty($request->input('class_id'))) {
            $class_id = $request->input('class_id');
            
            $system_settings_id = session()->get('user.system_settings_id');
            $classes = Classes::where('system_settings_id', $system_settings_id)->find($class_id);
            return json_encode($classes );
        }
    }
      /**
     * Gets the Classes for the given unit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $unit_id
     * @return \Illuminate\Http\Response
     */
    public function getClassSection(Request $request)
    {
        
        if (!empty($request->input('class_id'))) {
            $class_id = $request->input('class_id');
            
            $system_settings_id = session()->get('user.system_settings_id');
            $sections=ClassSection::forDropdown($system_settings_id,false,$class_id);
            $html = '<option value="">' . __('english.please_select') . '</option>';
            //$html = '';
            if (!empty($sections)) {
                foreach ($sections as $id => $section_name) {
                    $html .= '<option value="' . $id .'">' . $section_name. '</option>';
                }
            }

            return $html;
        }
    }


}
