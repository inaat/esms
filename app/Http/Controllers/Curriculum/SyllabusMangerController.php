<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\SyllabusManger;
use App\Models\Exam\ExamTerm;


use App\Models\Curriculum\ClassSubjectQuestionBank;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Session;
use App\Models\HumanRM\HrmEmployee;

use Yajra\DataTables\Facades\DataTables;
use DB;
use File;

class SyllabusMangerController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('syllabus.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $syllabus = SyllabusManger::leftjoin('class_subjects', 'syllabus_mangers.subject_id', '=', 'class_subjects.id')
            ->leftjoin('campuses', 'syllabus_mangers.campus_id', '=', 'campuses.id')
            ->leftjoin('classes', 'syllabus_mangers.class_id', '=', 'classes.id')
            ->leftjoin('exam_terms', 'syllabus_mangers.exam_term_id', '=', 'exam_terms.id')
            ->leftjoin('subject_chapters as chap', 'syllabus_mangers.chapter_id', '=', 'chap.id')
            ->select(['syllabus_mangers.id','campuses.campus_name','classes.title as class_name','class_subjects.name as subject_name','exam_terms.name as exam_term','chap.chapter_name as chapter_name','syllabus_mangers.description',]);
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
              $syllabus->whereIn('syllabus_mangers.campus_id', $permitted_campuses);
            }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $syllabus->where('syllabus_mangers.campus_id', $campus_id);
                }
            }
            if (request()->has('class_id')) {
                $class_id = request()->get('class_id');
                if (!empty($class_id)) {
                    $syllabus->where('syllabus_mangers.class_id', $class_id);
                }
            }
            if (request()->has('exam_term_id')) {
                $exam_term_id = request()->get('exam_term_id');
                if (!empty($exam_term_id)) {
                    $syllabus->where('syllabus_mangers.exam_term_id', $exam_term_id);
                }
            }
            if (request()->has('chapter_id')) {
                $chapter_id = request()->get('chapter_id');
                if (!empty($chapter_id)) {
                    $syllabus->where('syllabus_mangers.chapter_id', $chapter_id);
                }
            }
            if (request()->has('subject_id')) {
                $subject_id = request()->get('subject_id');
                if (!empty($subject_id)) {
                    $syllabus->where('syllabus_mangers.subject_id', $subject_id);
                }
            }
            return Datatables::of($syllabus)
            ->addColumn(
                'action',
                function ($row) {
                    $html= '<div class="dropdown">
                         <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                         <ul class="dropdown-menu" style="">';
                         if (auth()->user()->can('syllabus.update')) {
                    $html.='<li><a class="dropdown-item  edit_syllabus_button"data-href="' . action('Curriculum\SyllabusMangerController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
                         }
                         if (auth()->user()->can('syllabus.delete')) {

                         $html.='<li><a class="dropdown-item  delete_syllabus_button"data-href="' . action('Curriculum\SyllabusMangerController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';
}
                    $html .= '</ul></div>';

                    return $html;
                }
            )
            ->removeColumn('id')
            ->rawColumns(['action'])
            ->make(true);
        }
        $campuses=Campus::forDropdown();
        $term=ExamTerm::forDropdown();
        return view('Curriculum.syllabus.index')->with(compact('campuses', 'term'));
    }
/**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
    public function create()
    {
        if (!auth()->user()->can('syllabus.create')) {
            abort(403, 'Unauthorized action.');
        }
        $campuses=Campus::forDropdown();
        $term=ExamTerm::forDropdown();
        return view('Curriculum.syllabus.create')->with(compact('campuses', 'term'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('syllabus.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['campus_id','class_id','subject_id','chapter_id','exam_term_id','description']);
            $check_syllabus = SyllabusManger::where('chapter_id', $input['chapter_id'])->where('subject_id', $input['subject_id'])->first();
            if (empty($check_syllabus)) {
                $syllabus = SyllabusManger::create($input);
                $output = ['success' => true,
                            'data' => $syllabus,
                            'msg' => __("english.added_success")
                        ];
            }else{
                $output = ['success' => false,
                        'msg' => __("english.this_is_already_exist")
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('syllabus.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $campuses=Campus::forDropdown();

            $syllabus = SyllabusManger::find($id);
            $classes=Classes::forDropdown($syllabus->campus_id);
            $term=ExamTerm::forDropdown();
            $class_subject = ClassSubject::forDropdown($syllabus->class_id);

            $chapters = SubjectChapter::forDropdown($syllabus->subject_id);

            return view('Curriculum.syllabus.edit')
                ->with(compact('syllabus', 'class_subject', 'campuses', 'classes', 'term', 'chapters'));
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
        if (!auth()->user()->can('syllabus.update')) {
            abort(403, 'Unauthorized action.');
        }
        //UPDATE `exam_subject_results` SET `theory_mark`=100,total_mark=100 WHERE subject_id=13
        if (request()->ajax()) {
            try {
                $input = $request->only(['campus_id','class_id','subject_id','chapter_id','exam_term_id','description']);
                $syllabus = SyllabusManger::find($id);
                $syllabus->fill($input);
                $syllabus->save();

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
        if (!auth()->user()->can('syllabus.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $syllabus = SyllabusManger::find($id);
                $syllabus->delete();

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
    public function printSyllabus(Request $request)
    {
        if (!auth()->user()->can('syllabus.view')) {
            abort(403, 'Unauthorized action.');
        }
        //dd(SyllabusManger::with(['campus','classes','class_subject','chapter','chapter.lesson','term'])->get());
        if (File::exists(public_path('uploads/pdf/syllabus.pdf'))) {
            File::delete(public_path('uploads/pdf/syllabus.pdf'));
        }
        $pdf_name='syllabus.pdf';
        $input =$request->input();
        $campus_id=$input['campus_id'];
        $term=ExamTerm::find($input['exam_term_id']);

        if (!empty($request->input('class_ids'))) {
            $class_list=Classes::whereIn('id', $input['class_ids'])->where('campus_id', $campus_id)->orderBy('id', 'asc')->get();
        } else {
            $class_list=Classes::orderBy('id', 'asc')->where('campus_id', $campus_id)->get();
        }
        $class_syllabus_list=[];
        //dd($class_list);
        foreach ($class_list as $class_id) {
            // dd($class_id);
            $class_subject = ClassSubject::where('class_id', $class_id->id)->get();
            $class_wise=[];
            if (!$class_subject->isEmpty()) {
                foreach ($class_subject as $subject) {
                    $syllabus = SyllabusManger::with(['campus','classes','class_subject','chapter','chapter.lesson','term'])
                    ->where('subject_id', $subject->id)->where('exam_term_id', $input['exam_term_id'])->orderBy('chapter_id', 'asc')->get();
                    $subject_data =[
                        'subject' =>$subject,
                        'syllabus' =>$syllabus
                    ];
                    $class_wise[]=$subject_data;
                }
            }

            $class_syllabus_list[]= [
                'data'=>$class_wise,
                'classes' =>$class_id];
        }
        $pdf =  config('constants.mpdf');
        if ($pdf) {
            $data=[
                'class_syllabus_list'=>$class_syllabus_list,
                'term'=>$term
            ];
            $this->reportPDF('samplereport.css', $data, 'MPDF.syllabus', 'view', 'a4');
        } else {
    $snappy  = \WPDF::loadView('Curriculum.syllabus.print', compact('class_syllabus_list', 'term'));
    $headerHtml = view()->make('common._header')->render();
    $footerHtml = view()->make('common._footer')->render();
    $snappy->setOption('header-html', $headerHtml);
    $snappy->setOption('footer-html', $footerHtml);
    $snappy->setPaper('a4')->setOption('orientation', 'portrait')->setOption('footer-right', 'Page [page] of [toPage]')->setOption('margin-top', 20)->setOption('margin-left', 5)->setOption('margin-right', 5)->setOption('margin-bottom', 5);
    $snappy->save('uploads/pdf/'.$pdf_name);//save pdf file
    return $snappy->stream();
}
    }
}
