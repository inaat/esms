<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\HumanRM\HrmEmployee;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use DB;
use File;
class ClassSubjectController extends Controller
{
    protected $commonUtil;

    /**
    * Constructor
    *
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
        if (!auth()->user()->can('subject.view')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        if (request()->ajax()) {
            $ClassSubjects = ClassSubject::leftjoin('campuses as cam', 'class_subjects.campus_id', '=', 'cam.id')
            ->leftjoin('classes as c', 'class_subjects.class_id', '=', 'c.id')
            ->whereNull('class_subjects.deleted_at')->select(['class_subjects.id',
        'class_subjects.subject_input',
        'class_subjects.description',
        'class_subjects.subject_icon',
        'class_subjects.subject_book',
        'class_subjects.passing_percentage',
        'cam.campus_name as campus_name',
        'c.title as class_name',
        DB::raw("CONCAT(COALESCE(class_subjects.name, ''),' (',COALESCE(class_subjects.code,''),')') as subject_name")

        ])->orderBy('class_subjects.class_id');
      
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $ClassSubjects->whereIn('class_subjects.campus_id', $permitted_campuses);
        }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $ClassSubjects->where('cam.id', $campus_id);
                }
            }
            if (request()->has('class_id')) {
                $class_id = request()->get('class_id');
                if (!empty($class_id)) {
                    $ClassSubjects->where('class_subjects.class_id', $class_id);
                }
            }
            if (request()->has('subject_id')) {
                $subject_id = request()->get('subject_id');
                if (!empty($subject_id)) {
                    $ClassSubjects->where('class_subjects.id', $subject_id);
                }
            }
            return Datatables::of($ClassSubjects)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                        if (auth()->user()->can('subject.update')) {
                            $html.='<li><a class="dropdown-item  edit_class_subject_button"data-href="' . action('Curriculum\ClassSubjectController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                        }
                        $html.='<li><a class="dropdown-item "href="' . action('Curriculum\ManageSubjectController@show', [$row->id]) . '"><i class="lni lni-eye "></i> ' . __("english.manage_subject") . '</a></li>';
                        if (auth()->user()->can('subject.delete')) {
                            $html.='<li><a class="dropdown-item  delete_class_subject_button"data-href="' . action('Curriculum\ClassSubjectController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';
                        }
                        $document_name = !empty(explode("_", $row->subject_book, 2)[1]) ? explode("_", $row->subject_book, 2)[1] : $row->subject_book ;
                        if (auth()->user()->can('student_document.download')) {
                            $html .= '<li><a  class="dropdown-item  " href="' . url('uploads/subjects/' . $row->subject_book) .'" download="' . $document_name . '"><i class="fas fa-download" ></i> ' . __("english.download_document") . '</a></li>';
                        }
                        $html .= '</ul></div>';

                        return $html;
                    }
                )
                ->editColumn('subject_name', function ($row) {
                    $image = 'default.png';

                    if(!empty($row->subject_icon)){
                        $image = $row->subject_icon;
                    }
                    
                    $status='<div><a  href="' . action('Curriculum\ManageSubjectController@show', [$row->id]) . '">
                 <img src="'.url('uploads/subjects/' . $image).'" class="rounded-circle " width="50" height="50" alt="" >
                 '.ucwords($row->subject_name);
                  
                    $status .='</a></div>';
                    return $status;
                })
                ->filterColumn('subject_name', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('class_subjects.name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('campus_name', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('cam.campus_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('class_name', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('c.title', 'like', "%{$keyword}%");
                    });
                })
                ->editColumn('passing_percentage', '{{$passing_percentage}}%')
                ->removeColumn('id','subject_icon','subject_book')
                ->rawColumns(['action','campus_name','class_name','subject_name'])
                ->make(true);
        }

        return view('Curriculum.class_subject.index')->with(compact('campuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('subject.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        return view('Curriculum.class_subject.create')->with(compact('campuses'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('subject.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['campus_id','class_id','name','code','theory_mark','parc_mark','subject_input','description','passing_percentage']);
            $input['total']=$input['theory_mark']+$input['parc_mark'];
            $user_id = $request->session()->get('user.id');
            $input['created_by'] = $user_id;
            if (!empty($request->hasFile('subject_icon'))) {
               $subject_icon = $this->commonUtil->uploadFile($request, 'subject_icon', 'subjects', 'image');
               //dd($subject_icon);
                $input['subject_icon'] = $subject_icon;
            }
            if (!empty($request->hasFile('subject_book'))) {
                $subject_book = $this->commonUtil->uploadFile($request, 'subject_book', 'subjects', 'document');
                $input['subject_book'] = $subject_book;
            }
            //dd($input);
            $class_subject = ClassSubject::create($input);
            $output = ['success' => true,
                        'data' => $class_subject,
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
        if (!auth()->user()->can('subject.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $campuses=Campus::forDropdown();

            $class_subject = ClassSubject::find($id);
            $classes=Classes::forDropdown(1);
            // $teachers=HrmEmployee::forDropdown();
            // dd($teachers);
            return view('Curriculum.class_subject.edit')
                ->with(compact('class_subject', 'campuses', 'classes'));
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
        if (!auth()->user()->can('subject.update')) {
            abort(403, 'Unauthorized action.');
        }
        //UPDATE `exam_subject_results` SET `theory_mark`=100,total_mark=100 WHERE subject_id=13
        if (request()->ajax()) {
            try {
                $input = $request->only(['campus_id','class_id','name','code','theory_mark','parc_mark','subject_input','description','passing_percentage']);
                $input['total']=$input['theory_mark']+$input['parc_mark'];
                $class_subject = ClassSubject::findOrFail($id);
                if (!empty($request->hasFile('subject_icon'))) {
                    if (File::exists(public_path('uploads/subjects/'.$class_subject->subject_icon))) {
                        File::delete(public_path('uploads/subjects/'.$class_subject->subject_icon));
                    }
                    $subject_icon = $this->commonUtil->uploadFile($request, 'subject_icon', 'subjects', 'image');
                    //dd($subject_icon);
                     $input['subject_icon'] = $subject_icon;
                 }
                 if (!empty($request->hasFile('subject_book'))) {
                    if (File::exists(public_path('uploads/subjects/'.$class_subject->subject_book))) {
                        File::delete(public_path('uploads/subjects/'.$class_subject->subject_book));
                    }
                     $subject_book = $this->commonUtil->uploadFile($request, 'subject_book', 'subjects', 'document');
                     $input['subject_book'] = $subject_book;
                 }
                $class_subject->fill($input);
                $class_subject->save();

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
        if (!auth()->user()->can('subject.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $class_subject = ClassSubject::findOrFail($id);
                $class_subject->delete();

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
    /**
     * Gets the ClassSubject for
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $class_idcampus_id
     * @return \Illuminate\Http\Response
     */
    public function getSubjects(Request $request)
    {
        if (!empty($request->input('class_id'))) {
            $class_id = $request->input('class_id');

            $classSubject = ClassSubject::forDropdown($class_id);
            $html = '<option value="">' . __('english.please_select') . '</option>';
            //$html = '';
            if (!empty($classSubject)) {
                foreach ($classSubject as $id => $name) {
                    $html .= '<option value="' . $id .'">' . $name. '</option>';
                }
            }

            return $html;
        }
    }
    /**
     * Gets the ClassSubject for
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $class_idcampus_id
     * @return \Illuminate\Http\Response
     */
    public function getSectionSubjects(Request $request)
    {
        if (!empty($request->input('class_id'))) {
            $class_id = $request->input('class_id');
            $class_section_id = $request->input('class_section_id');
            $classSubject =SubjectTeacher::forDropdown($class_id, $class_section_id);
            $html = '<option value="">' . __('english.please_select') . '</option>';
            //$html = '';
            if (!empty($classSubject)) {
                foreach ($classSubject as $id => $name) {
                    $html .= '<option value="' . $id .'">' . $name. '</option>';
                }
            }

            return $html;
        }
    }
}
