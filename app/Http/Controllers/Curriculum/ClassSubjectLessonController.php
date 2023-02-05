<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\ClassSubjectLesson;
use App\Models\Campus;
use App\Models\Classes;

use Yajra\DataTables\Facades\DataTables;
use DB;

class ClassSubjectLessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        if (!auth()->user()->can('lesson.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $subject_lessons = ClassSubjectLesson::leftjoin('subject_chapters as chap', 'class_subject_lessons.chapter_id', '=', 'chap.id')
            ->select(['class_subject_lessons.name','chap.chapter_name as chapter_name','class_subject_lessons.description', 'class_subject_lessons.id']);
            if (request()->has('chapter_id')) {
                $chapter_id = request()->get('chapter_id');
                if (!empty($chapter_id)) {
                    $subject_lessons->where('chapter_id', $chapter_id);
                }
            }
            if (request()->has('subject_id')) {
                $subject_id = request()->get('subject_id');
                if (!empty($subject_id)) {
                    $subject_lessons->where('class_subject_lessons.subject_id', $subject_id);
                }
            }
            //dd(40);
            return Datatables::of($subject_lessons)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
if (auth()->user()->can('lesson.update')) {
    $html.='<li><a class="dropdown-item  edit_lesson_button"data-href="' . action('Curriculum\ClassSubjectLessonController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
}
if (auth()->user()->can('lesson.delete')) {
    $html.='<li><a class="dropdown-item  delete_lesson_button"data-href="' . action('Curriculum\ClassSubjectLessonController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';
}    
                       $html .= '</ul></div>';
    
                        return $html;
                    }
                )
                
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if (!auth()->user()->can('lesson.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $class_subject = ClassSubject::with(['classes'])->find($id);
        $chapters=SubjectChapter::forDropdown($id);


        return view('Curriculum.lesson.create')->with(compact('campuses','class_subject','chapters'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('lesson.create')) {
            abort(403, 'Unauthorized action.');
        }           

        try {
            $input = $request->only(['campus_id','subject_id','name','chapter_id','description']);
            $user_id = $request->session()->get('user.id');
            $input['created_by'] = $user_id;
            $subject_lesson = ClassSubjectLesson::create($input);
      
           
            $output = ['success' => true,
            'data' => $subject_lesson,
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
        if (!auth()->user()->can('lesson.view')) {
            abort(403, 'Unauthorized action.');
        }
        $class_subject = ClassSubject::with(['classes'])->find($id);
        $chapters=SubjectChapter::forDropdown($id);
 
        return view('Curriculum.lesson.index')->with(compact('class_subject','chapters'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('lesson.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $subject_lesson = ClassSubjectLesson::find($id);
            $class_subject = ClassSubject::where('id',$subject_lesson->subject_id)->first();
            $chapters=SubjectChapter::forDropdown($subject_lesson->subject_id);
           
    
            return view('Curriculum.lesson.edit')->with(compact('subject_lesson', 'class_subject', 'chapters'));
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
        if (!auth()->user()->can('lesson.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            try {
                $input = $request->only(['name','chapter_id','description']);
                $class_lesson = ClassSubjectLesson::findOrFail($id);
                $class_lesson->fill($input);
                $class_lesson->save();
  
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
      
        if (!auth()->user()->can('lesson.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $subject_lesson = ClassSubjectLesson::findOrFail($id);
                $subject_lesson->delete();

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
