<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\ClassSubjectLesson;
use App\Models\Curriculum\SubjectQuestionBank;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Campus;
use App\Models\Classes;

use Yajra\DataTables\Facades\DataTables;
use DB;

class ClassSubjectQuestionBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function index()
    {
        if (!auth()->user()->can('question_bank.view')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            $questionBank = SubjectQuestionBank::
        leftjoin('subject_chapters as chap', 'subject_question_banks.chapter_id', '=', 'chap.id')->select(['subject_question_banks.id',
        'subject_question_banks.question',
        'subject_question_banks.type',
        'subject_question_banks.marks',
        'chap.chapter_name as chapter_name',
        ]); 
        // $questionBank->where('subject_question_banks.type', 'true_and_false');
         if (request()->has('subject_id')) {
            $subject_id = request()->get('subject_id');
            if (!empty($subject_id)) {
                $questionBank->where('subject_question_banks.subject_id', $subject_id);
            }
        }
        
         if (request()->has('chapter_id')) {
            $chapter_id = request()->get('chapter_id');
            if (!empty($chapter_id)) {
                $questionBank->where('subject_question_banks.chapter_id', $chapter_id);
            }
        }
         if (request()->has('type')) {
            $type = request()->get('type');
            if (!empty($type)) {
                $questionBank->where('subject_question_banks.type', $type);
            }
        }

            return Datatables::of($questionBank)
                ->addColumn(
                    'action',
                    function ($row) {
                        $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
if (auth()->user()->can('question_bank.update')) {
    $html.='<li><a class="dropdown-item  edit_question_button"data-href="' . action('Curriculum\ClassSubjectQuestionBankController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
}
if (auth()->user()->can('question.delete')) {
    $html.='<li><a class="dropdown-item  delete_question_button"data-href="' . action('Curriculum\ClassSubjectQuestionBankController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';
}
                       $html .= '</ul></div>';
    
                        return $html;
                    }
                )
                ->editColumn('type', function ($row)  {
                    $type=__('english.question_type');
                    return $type[$row->type];
                })
                ->removeColumn('id')
                ->rawColumns(['action','question','type'])
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
        if (!auth()->user()->can('question_bank.create')) {
            abort(403, 'Unauthorized action.');
        }
        $class_subject = ClassSubject::with(['classes'])->find($id);
        $chapters=SubjectChapter::forDropdown($id);


        return view('Curriculum.question_bank.create')->with(compact('class_subject', 'chapters'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('question_bank.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['subject_id','chapter_id', 'type','marks', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'hint' , 'answer','column_a','column_b']);
           
            if($input['type']=='mcq' || $input['type']=='true_and_false'){
                if(!empty($input['answer'])){
                    $user_id = $request->session()->get('user.id');
                    $input['created_by'] = $user_id;
                    $SubjectQuestionBank = SubjectQuestionBank::create($input);
                    $output = ['success' => true,
                                'data' => $SubjectQuestionBank,
                                'msg' => __("english.added_success")
                            ];
                }else{
                    $output = ['success' => false,
                                'msg' => __("english.answer_required")
                            ];
                }
           }
            else{
                $user_id = $request->session()->get('user.id');
                $input['created_by'] = $user_id;
                $SubjectQuestionBank = SubjectQuestionBank::create($input);
                $output = ['success' => true,
                            'data' => $SubjectQuestionBank,
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
        if (!auth()->user()->can('question_bank.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {

            $class_subject_question_bank = SubjectQuestionBank::find($id);
            $class_subject = ClassSubject::with(['classes'])->find($class_subject_question_bank->subject_id);
            $lesson = ClassSubjectLesson::forDropdown($class_subject_question_bank->subject_id, $class_subject_question_bank->chapter_number);
            $chapters=SubjectChapter::forDropdown($class_subject_question_bank->subject_id);
            
            return view('Curriculum.question_bank.edit')
                ->with(compact('class_subject_question_bank','chapters','class_subject','lesson'));
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
        if (!auth()->user()->can('question_bank.update')) {
            abort(403, 'Unauthorized action.');
        }
  
        if (request()->ajax()) {
            try {
                $input = $request->only(['subject_id', 'chapter_id','type','marks', 'question', 'option_a', 'option_b', 'option_c', 'option_d', 'hint' , 'answer','column_a','column_b']);
               // dd($input);
                $SubjectQuestionBank = SubjectQuestionBank::findOrFail($id);
                $SubjectQuestionBank->fill($input);
                $SubjectQuestionBank->save();
  
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
      
      if (!auth()->user()->can('question_bank.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $SubjectQuestionBank = SubjectQuestionBank::findOrFail($id);
                $SubjectQuestionBank->delete();

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
