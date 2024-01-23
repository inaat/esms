<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassTimeTable;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\HumanRM\HrmEmployee;

use Yajra\DataTables\Facades\DataTables;
use DB;

class AssignSubjectTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {if (!auth()->user()->can('assign_subject.view')) {
        abort(403, 'Unauthorized action.');
    }
        $campuses=Campus::forDropdown();

        if (request()->ajax()) {
            $ClassSubjects = SubjectTeacher::leftjoin('campuses as cam', 'subject_teachers.campus_id', '=', 'cam.id')
            ->leftjoin('classes as c', 'subject_teachers.class_id', '=', 'c.id')
            ->leftjoin('class_sections as cs', 'subject_teachers.class_section_id', '=', 'cs.id')
            ->leftjoin('class_subjects as sub', 'subject_teachers.subject_id', '=', 'sub.id')
            ->leftjoin('hrm_employees as th', 'subject_teachers.teacher_id', '=', 'th.id')
            ->select([
                'subject_teachers.id',
                'subject_teachers.class_id',
        'cam.campus_name as campus_name',
        'c.title as class_name',
        'cs.section_name as section_name',

        DB::raw("CONCAT(COALESCE(sub.name, ''),' (',COALESCE(sub.code,''),')') as subject_name"),
        DB::raw("CONCAT(COALESCE(th.first_name, ''),' ',COALESCE(th.last_name,'') ,'(',COALESCE(th.employeeID,''),')' ) as teacher_name")
        ]);
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $ClassSubjects->whereIn('subject_teachers.campus_id', $permitted_campuses);
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
                $ClassSubjects->where('subject_teachers.class_id', $class_id);
            }
        }
        if (request()->has('class_section_id')) {
            $class_section_id = request()->get('class_section_id');
            if (!empty($class_section_id)) {
                $ClassSubjects->where('subject_teachers.class_section_id', $class_section_id);
            }
        }
        $ClassSubjects->orderBy('c.id');
            return Datatables::of($ClassSubjects)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                             $html.='<li><a class="dropdown-item  edit_class_subject_button"data-href="' . action('Curriculum\AssignSubjectTeacherController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                             $html.='<li><a class="dropdown-item btn-danger  delete_assign_subject_button"data-href="' . action('Curriculum\AssignSubjectTeacherController@destroy', [$row->id]) . '"><i class="bx bxs-trash f-16 text-white"></i> ' . __("english.delete") . '</a></li>';
                        $html .= '</ul></div>';
    
                        return $html;
                    }
                )
                ->editColumn('subject_name', function ($row) {
                    return ucwords($row->subject_name);
                })

                ->removeColumn('id')
                ->rawColumns(['action','campus_name','class_name','subject_name'])
                ->make(true);
        }
  
        return view('Curriculum.assign_section_subject_with_teacher.index')->with(compact('campuses'));
    }
    public function create()
    {
        if (!auth()->user()->can('assign_subject.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();

        $teachers=HrmEmployee::teacherDropdown();

        return view('Curriculum.assign_section_subject_with_teacher.create')->with(compact('campuses', 'teachers'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('assign_subject.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['campus_id','class_id','class_section_id','subject_id','teacher_id']);
           
            $check_subject_id= SubjectTeacher::where('class_id', $input['class_id'])
                                               ->where('class_section_id', $input['class_section_id'])
                                               ->where('subject_id', $input['subject_id'])->first();
            if (!empty($check_subject_id)) {
                $output = ['success' => false,
                'msg' => __("english.this_subject_alerdy_exist")
            ];
            } else {
                $class_subject = SubjectTeacher::create($input);
      

                $output = ['success' => true,
                        'data' => $class_subject,
                        'msg' => __("english.added_success")
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
    public function edit($id)
    {
        if (!auth()->user()->can('assign_subject.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $subject_teacher = SubjectTeacher::find($id);
            $classSubject = ClassSubject::forDropdown($subject_teacher->class_id);

            $teachers=HrmEmployee::teacherDropdown();
            //dd($teachers);
            return view('Curriculum.assign_section_subject_with_teacher.edit')
                ->with(compact('subject_teacher', 'teachers', 'classSubject'));
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
        if (!auth()->user()->can('assign_subject.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            try {
                $input = $request->only(['subject_id','teacher_id']);
                $subject_teacher = SubjectTeacher::findOrFail($id);
                $old_teacher_id=$subject_teacher->teacher_id;
                $subject_teacher->fill($input);
                $subject_teacher->save();
                $check_teacher_slot=ClassTimeTable::where('subject_id', $input['subject_id'])
                ->where('class_id', $subject_teacher->class_id)
                ->where('class_section_id', $subject_teacher->class_section_id)
                ->where('campus_id', $subject_teacher->campus_id)->get();

                if (!empty($check_teacher_slot)) {
                    foreach ($check_teacher_slot as $teacher_slot) {
                        $update_teacher_slot=ClassTimeTable::
                        where('subject_id', $input['subject_id'])
                        ->where('teacher_id', $old_teacher_id)->first();

                        $update_teacher_slot->teacher_id=$subject_teacher->teacher_id;
                        $update_teacher_slot->save();
                    }
                }

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
        if (!auth()->user()->can('assign_subject.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
           try {
               
                
                $subject_teacher = SubjectTeacher::findOrFail($id);
                $old_teacher_id=$subject_teacher->teacher_id;
                $subject_id=$subject_teacher->subject_id;
                $subject_teacher->delete();
                $check_teacher_slot=ClassTimeTable::where('subject_id', $subject_id)
                ->where('class_id', $subject_teacher->class_id)
                ->where('class_section_id', $subject_teacher->class_section_id)
                ->where('campus_id', $subject_teacher->campus_id)->get();

                if (!empty($check_teacher_slot)) {
                    foreach ($check_teacher_slot as $teacher_slot) {
                        $update_teacher_slot=ClassTimeTable::
                        where('subject_id', $subject_id)
                        ->where('teacher_id', $old_teacher_id)->first();

                        $update_teacher_slot->delete();
                       
                    }
                }

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
}