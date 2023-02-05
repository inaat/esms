<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\ClassSection;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\SubjectQuestionBank;


use App\Models\Curriculum\ClassSubjectQuestionBank;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\HumanRM\HrmEmployee;

use Yajra\DataTables\Facades\DataTables;
use DB;

class ManageSubjectController extends Controller
{

    //Chapters
    public function index()
    {
    if (!auth()->user()->can('chapter.view')) {
        abort(403, 'Unauthorized action.');
    }
  
    if (request()->ajax()) {
        $chapters = SubjectChapter::select([
        'chapter_name',
        'id'
        ]);
        // $chapters->where('subject_question_banks.type', 'true_and_false');
        if (request()->has('subject_id')) {
            $subject_id = request()->get('subject_id');
            if (!empty($subject_id)) {
                $chapters->where('subject_id', $subject_id);
            }
        }
       

        return Datatables::of($chapters)
            ->addColumn(
                'action',
                function ($row) {
                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
if (auth()->user()->can('chapter.update')) {
    $html.='<li><a class="dropdown-item  edit_chapter_button"data-href="' . action('Curriculum\ManageSubjectController@edit', [$row->id]) . '"><i class="bx bxs-edit "></i> ' . __("english.edit") . '</a></li>';
}
if (auth()->user()->can('chapter.delete')) {
    $html.='<li><a class="dropdown-item  delete_chapter_button"data-href="' . action('Curriculum\ManageSubjectController@destroy', [$row->id]) . '"><i class="bx bxs-trash "></i> ' . __("english.delete") . '</a></li>';
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

public function create($id)
{
    // if (!auth()->user()->can('ClassSubjectLesson.create')) {
    //     abort(403, 'Unauthorized action.');
    // }
    $class_subject = ClassSubject::with(['classes'])->find($id);
     

    return view('Curriculum.manage_subject.chapter_create')->with(compact('class_subject'));
}

public function store(Request $request)
    {
        if (!auth()->user()->can('chapter.create')) {
            abort(403, 'Unauthorized action.');
        }

try {
    $input = $request->only(['subject_id','chapter_name']);
    $chapter= SubjectChapter::create($input);
   
    $output = ['success' => true,
    'data' => $chapter,
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

public function edit($id)
{
    if (!auth()->user()->can('chapter.update')) {
        abort(403, 'Unauthorized action.');
    }
    if (request()->ajax()) {
        $chapter= SubjectChapter::find($id);
        $class_subject = ClassSubject::where('id',$chapter->subject_id)->first();

        return view('Curriculum.manage_Subject.chapter_edit')->with(compact('class_subject', 'chapter'));
    }
}


public function update(Request $request, $id)
{
    if (!auth()->user()->can('chapter.update')) {
        abort(403, 'Unauthorized action.');
    }

    if (request()->ajax()) {
        try {
            $input = $request->only(['chapter_name']);
            $chapter= SubjectChapter::find($id);
            $chapter->fill($input);
            $chapter->save();

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {     if (!auth()->user()->can('chapter.view')) {
        abort(403, 'Unauthorized action.');
    }
        $class_subject = ClassSubject::with(['classes'])->find($id);
        $section=ClassSection::forDropdown(1,false ,$class_subject->class_id);
        $chapters=SubjectChapter::forDropdown($id);
        return view('Curriculum.manage_subject.show')->with(compact('class_subject','chapters','section'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        if (!auth()->user()->can('chapter.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                
                $check_question=SubjectQuestionBank::where('chapter_id',$id)->first();
                if(Empty($check_question)){
                    $chapter = SubjectChapter::findOrFail($id);
                    $chapter->delete();
    
                    $output = ['success' => true,
                            'msg' => __("english.deleted_success")
                            ];
                }else{
                    $output = ['success' => false,
                    'msg' => __("english.chapter_warning")
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
    
}
public function getSubjectChapters(Request $request){

    if (!empty($request->input('subject_id'))) {
        $subject_id = $request->input('subject_id');
        $class_id = $request->input('class_id');
        $campus_id = $request->input('campus_id');
        
        $subject_chapter =SubjectChapter::forDropdown($subject_id);
        $html = '';
        $count=1;
        if (!empty($subject_chapter)) {
           
            foreach ($subject_chapter as $id => $name) {
                $html .= '<div class="col-md-3" style="border-right:2px solid black;text-align:left;
                font-weight:700;FONT-SIZE: 15px;height:70px;margin-bottom:20px;">';
        
             
                $html.='<input type="checkbox" class="chapterName form-check-input mt-2" name="chapter_ids[]" value='.$id.'><span style="color:red p-2 ">'.$count.'</span> &nbsp;&nbsp;' . $name. '</span></div>';

                $count++;
            }
        $html .= '<input type="hidden" name="subject_id" value="'.$subject_id.'" />';
        $html .= '<input type="hidden" name="class_id" value="'.$class_id.'" />';
        $html .= '<input type="hidden" name="campus_id" value="'.$campus_id.'" />';
        $html.='<div class="d-lg-flex align-items-center mt-4 gap-3 ">
        <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit" id="load_chapter_question">'
           .__('english.load_chapter_question').'</button></div></div>';
        }
        
        if(count($subject_chapter) == 0){
            return '<div class="text-center"><h1>No Record Found</h1></div>';
        }


        return $html;
    }
}
public function getSubjectChaptersQuestions(Request $request){
   
        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown(1);
        $input= $request->input();
       
        $subject_id = $request->input('subject_id');
        $class_id = $request->input('class_id');
        $campus_id = $request->input('campus_id');
        $chapter_ids = $request->input('chapter_ids');
        //dd($chapter_ids);
        $class_subjects=ClassSubject::forDropdown($class_id);
        $subject_chapters=SubjectChapter::where('subject_id',$subject_id)->get();
        $total_mcq=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'mcq');
        $total_true_and_false=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'true_and_false');
        $total_short_question=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'short_question');
        $total_long_question=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'long_question');
        $total_fill_in_the_blanks=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'fill_in_the_blanks');
        $total_column_matching=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'column_matching');
        $total_paraphrase=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'paraphrase');
        $total_passage=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'passage');
        $total_stanza=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'stanza');
        $total_translation_to_english=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'translation_to_english');
        $total_translation_to_urdu=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'translation_to_urdu');
        $total_words_and_use=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'words_and_use');
        $total_words_and_use=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'words_and_use');
        $total_words_and_use=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'words_and_use');
        $total_singular_and_plural=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'singular_and_plural');
        $total_masculine_and_feminine=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'masculine_and_feminine');
        $total_contextual=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'contextual');
        $total_grammar=$this->questionTypeCount($input['subject_id'],$input['chapter_ids'],'grammar');

        return view('Curriculum.paper_maker.manual_paper_get_questions')->with(compact('campuses','total_mcq','total_true_and_false','total_short_question',
    'total_long_question','total_grammar','total_contextual','total_singular_and_plural','total_masculine_and_feminine','total_fill_in_the_blanks','total_passage','total_translation_to_english','total_words_and_use','total_translation_to_urdu','total_stanza','total_column_matching','total_paraphrase','campus_id','class_id','subject_id','chapter_ids','classes','class_subjects','subject_chapters'));


}


public function questionTypeCount($subject_id, $chapter_ids, $type){
    $question_type_count=SubjectQuestionBank::where('subject_id',$subject_id)->whereIn('chapter_id', $chapter_ids);
   

    return $question_type_count->where('type',$type)->count();
} 

public function getQuestionsAccordingConfig(Request $request){

   //dd($request);
      
    $input= $request->input();
    $class_subject=ClassSubject::findOrFail($input['subject_id']);
    $campuses=Campus::forDropdown();
    $overallMark=!empty($input['overallMark']) ? $input['overallMark'] : 0;
    $mcq_question_number=!empty($input['mcq_question_number']) ? $input['mcq_question_number'] : 0;
    $mcq_question_marks=!empty($input['mcq_question_marks']) ? $input['mcq_question_marks'] : 0 ;
    $mcq_question_choice=!empty($input['mcq_question_choice']) ? $input['mcq_question_choice'] : 0 ;
    //
    $fill_in_blanks_question_number=!empty($input['fill_in_the_blanks_question_number']) ? $input['fill_in_the_blanks_question_number'] : 0;
    $fill_in_the_blank_marks=!empty($input['fill_in_the_blanks_question_marks']) ? $input['fill_in_the_blanks_question_marks'] : 0;
    $fill_in_the_blanks_question_choice=!empty($input['fill_in_the_blanks_question_choice']) ? $input['fill_in_the_blanks_question_choice'] : 0 ;

    
    //
    $true_and_false_question_number=!empty($input['true_and_false_question_number']) ? $input['true_and_false_question_number'] : 0;
    $true_and_false_question_marks=!empty($input['true_and_false_question_marks']) ? $input['true_and_false_question_marks'] : 0;
    $true_and_false_question_choice=!empty($input['true_and_false_question_choice']) ? $input['true_and_false_question_choice'] : 0 ;

    //
    $paraphrase_question_number=!empty($input['paraphrase_question_number']) ? $input['paraphrase_question_number'] : 0;
    $paraphrase_question_marks=!empty($input['paraphrase_question_marks']) ? $input['paraphrase_question_marks'] : 0;
    $paraphrase_choice=!empty($input['paraphrase_choice']) ? $input['paraphrase_choice'] : 0 ;
    //
    $stanza_question_number=!empty($input['stanza_question_number']) ? $input['stanza_question_number'] : 0;
    $stanza_question_marks=!empty($input['stanza_question_marks']) ? $input['stanza_question_marks'] : 0;
    $stanza_choice=!empty($input['stanza_choice']) ? $input['stanza_choice'] : 0 ;
    //
    $words_and_use_question_number=!empty($input['words_and_use_question_number']) ? $input['words_and_use_question_number'] : 0;
    $words_and_use_question_marks=!empty($input['words_and_use_question_marks']) ? $input['words_and_use_question_marks'] : 0;
    $words_and_use_choice=!empty($input['words_and_use_choice']) ? $input['words_and_use_choice'] : 0 ;
    //
    //
    $translation_to_english_question_number=!empty($input['translation_to_english_question_number']) ? $input['translation_to_english_question_number'] : 0;
    $translation_to_english_question_marks=!empty($input['translation_to_english_question_marks']) ? $input['translation_to_english_question_marks'] : 0;
    $translation_to_english_choice=!empty($input['translation_to_english_choice']) ? $input['translation_to_english_choice'] : 0 ;
    //
    //
    $translation_to_urdu_question_number=!empty($input['translation_to_urdu_question_number']) ? $input['translation_to_urdu_question_number'] : 0;
    $translation_to_urdu_question_marks=!empty($input['translation_to_urdu_question_marks']) ? $input['translation_to_urdu_question_marks'] : 0;
    $translation_to_urdu_choice=!empty($input['translation_to_urdu_choice']) ? $input['translation_to_urdu_choice'] : 0 ;
    //
    $passage_question_number=!empty($input['passage_question_number']) ? $input['passage_question_number'] : 0;
    $passage_question_marks=!empty($input['passage_question_marks']) ? $input['passage_question_marks'] : 0;
    $passage_choice=!empty($input['passage_choice']) ? $input['passage_choice'] : 0 ;
    //
    $short_question_question_number=!empty($input['short_question_question_number']) ? $input['short_question_question_number'] : 0;
    $short_question_question_marks=!empty($input['short_question_question_marks']) ? $input['short_question_question_marks'] : 0;
    $short_question_choice=!empty($input['short_question_choice']) ? $input['short_question_choice'] : 0 ;

   //
     //
     $grammar_question_number=!empty($input['grammar_question_number']) ? $input['grammar_question_number'] : 0;
     $grammar_question_marks=!empty($input['grammar_question_marks']) ? $input['grammar_question_marks'] : 0;
     $grammar_choice=!empty($input['grammar_choice']) ? $input['grammar_choice'] : 0 ;
     //
     //
     $singular_and_plural_question_number=!empty($input['singular_and_plural_question_number']) ? $input['singular_and_plural_question_number'] : 0;
     $singular_and_plural_question_marks=!empty($input['singular_and_plural_question_marks']) ? $input['singular_and_plural_question_marks'] : 0;
     $singular_and_plural_choice=!empty($input['singular_and_plural_choice']) ? $input['singular_and_plural_choice'] : 0 ;
     //
     $masculine_and_feminine_question_number=!empty($input['masculine_and_feminine_question_number']) ? $input['masculine_and_feminine_question_number'] : 0;
     $masculine_and_feminine_question_marks=!empty($input['masculine_and_feminine_question_marks']) ? $input['masculine_and_feminine_question_marks'] : 0;
     $masculine_and_feminine_choice=!empty($input['masculine_and_feminine_choice']) ? $input['masculine_and_feminine_choice'] : 0 ;
     //
     $contextual_question_number=!empty($input['contextual_question_number']) ? $input['contextual_question_number'] : 0;
     $contextual_question_marks=!empty($input['contextual_question_marks']) ? $input['contextual_question_marks'] : 0;
     $contextual_choice=!empty($input['contextual_choice']) ? $input['contextual_choice'] : 0 ;
     //
   //
    $long_question_question_number=!empty($input['long_question_question_number']) ? $input['long_question_question_number'] : 0;
    $long_question_question_marks=!empty($input['long_question_question_marks']) ? $input['long_question_question_marks'] : 0;
    $long_question_choice=!empty($input['long_question_choice']) ? $input['long_question_choice'] : 0 ;
     //
    $column_matching_question_marks=!empty($input['column_matching_question_marks']) ? $input['column_matching_question_marks'] : 0;

    $subject_id = $request->input('subject_id');
    $class_id = $request->input('class_id');
    $campus_id = $request->input('campus_id');
    $chapter_ids = $request->input('chapter_ids');
    $system_settings_id = session()->get('user.system_settings_id');
    $classes=Classes::forDropdown($system_settings_id, false, $input['campus_id']);
    $class_subjects=ClassSubject::forDropdown($class_id);
    $subject_chapters=SubjectChapter::where('subject_id',$subject_id)->get();

    $mcq_questions=null;
    $fill_in_the_blanks_questions=null;
    $true_and_false_questions=null;
    $short_question_questions=null;
    $long_question_questions=null;
    $column_matching_questions=null;
    $paraphrase_questions=null;
    $passage_questions=null;
    $stanza_questions=null;
    $words_and_use_questions=null;
    $translation_to_urdu_questions=null;
    $translation_to_english_questions=null;
    $contextual_questions=null;
    $singular_and_plural_questions=null;
    $masculine_and_feminine_questions=null;
    $grammar_questions=null;
    if(!empty($input['mcq_questions'])){
       $mcq_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'mcq');
     //  dd($mcq_questions);
    }
    if(!empty($input['fill_in_the_blanks_questions'])){
       $fill_in_the_blanks_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'fill_in_the_blanks');
       //dd($fill_in_the_blanks_questions);
    }
    if(!empty($input['true_and_false_questions'])){
       $true_and_false_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'true_and_false');
      // dd($true_and_false_questions);
    }
    if(!empty($input['paraphrase_questions'])){
       $paraphrase_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'paraphrase');
      // dd($paraphrase_questions);
    }
    if(!empty($input['grammar_questions'])){
       $grammar_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'grammar');
      // dd($grammar_questions);
    }
    if(!empty($input['singular_and_plural_questions'])){
       $singular_and_plural_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'singular_and_plural');
      // dd($singular_and_plural_questions);
    }
    if(!empty($input['masculine_and_feminine_questions'])){
       $masculine_and_feminine_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'masculine_and_feminine');
      // dd($masculine_and_feminine_questions);
    }
    if(!empty($input['contextual_questions'])){
       $contextual_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'contextual');
      // dd($contextual_questions);
    }
    if(!empty($input['stanza_questions'])){
       $stanza_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'stanza');
      // dd($passage_questions);
    }
    if(!empty($input['words_and_use_questions'])){
       $words_and_use_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'words_and_use');
      // dd($passage_questions);
    }
    if(!empty($input['translation_to_urdu_questions'])){
       $translation_to_urdu_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'translation_to_urdu');
      // dd($translation_to_urdu_questions);
    }
    if(!empty($input['translation_to_english_questions'])){
       $translation_to_english_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'translation_to_english');
      // dd($translation_to_english_questions);
    }
    if(!empty($input['passage_questions'])){
       $passage_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'passage');
      // dd($passage_questions);
    }
    if(!empty($input['short_question_questions'])){
       $short_question_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'short_question');
       //dd($short_question_questions);
    }
    if(!empty($input['long_question_questions'])){
       $long_question_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'long_question');
      // dd($long_question_questions);
    }
    if(!empty($input['column_matching_questions'])){
       $column_matching_questions=$this->getAllQuestionType($input['subject_id'],$input['chapter_ids'],'column_matching');
      //dd($column_matching_questions);
    }

    return view('Curriculum.paper_maker.manual_paper')->with(compact('campuses','mcq_questions',
    'overallMark',
    'mcq_question_number',
    'mcq_question_marks',
    'mcq_question_choice',
    'fill_in_the_blanks_questions',
    'fill_in_blanks_question_number',
    'fill_in_the_blank_marks',
    'fill_in_the_blanks_question_choice',
    'true_and_false_question_number',
    'true_and_false_questions',
    'true_and_false_question_marks',
    'true_and_false_question_choice',
    ///
    'paraphrase_question_number',

    'paraphrase_questions',
    'paraphrase_question_marks',
    'paraphrase_choice',

    'passage_questions',
    'passage_question_marks',
    'passage_choice',
    'passage_question_number',
    //
    'stanza_questions',
    'stanza_question_marks',
    'stanza_choice',
    'stanza_question_number',
   ///
    'contextual_questions',
    'contextual_question_marks',
    'contextual_choice',
    'contextual_question_number',
   ///
    'grammar_questions',
    'grammar_question_marks',
    'grammar_choice',
    'grammar_question_number',
   ///
    'singular_and_plural_questions',
    'singular_and_plural_question_marks',
    'singular_and_plural_choice',
    'singular_and_plural_question_number',
   ///
    'masculine_and_feminine_questions',
    'masculine_and_feminine_question_marks',
    'masculine_and_feminine_choice',
    'masculine_and_feminine_question_number',
   ///
    'words_and_use_questions',
    'words_and_use_question_marks',
    'words_and_use_choice',
    'words_and_use_question_number',
   ///
   ///
    'translation_to_urdu_questions',
    'translation_to_urdu_question_marks',
    'translation_to_urdu_choice',
    'translation_to_urdu_question_number',
   ///
   ///
    'translation_to_english_questions',
    'translation_to_english_question_marks',
    'translation_to_english_choice',
    'translation_to_english_question_number',
   ///
    'short_question_question_number',
    'short_question_questions',
    'short_question_question_marks',
    'short_question_choice',
    'long_question_question_number',
    'long_question_questions',
    'long_question_question_marks',
    'long_question_choice',
    'column_matching_questions',
    'column_matching_question_marks',
    'campus_id','class_id','subject_id','chapter_ids','class_subject',
    'classes','class_subjects','subject_chapters'
));
}


public function getAllQuestionType($subject_id, $chapter_ids, $type){
    $question_type=SubjectQuestionBank::with(['chapter'])->where('subject_id',$subject_id)->whereIn('chapter_id', $chapter_ids);
     
    return $question_type->where('type',$type)->get();
} 












   
//     public function index(){
//         $Class_SNC_Series=[
//             '1st'=>"Class 1_SNC Series",
//             2=>"Class 2_SNC Series",
//             3=>"Class 3_SNC Series",
//             4=>"Class 4_SNC Series",
//             5=>"Class 5_SNC Series"];
//         $Class_1_SNC_Series=[
//            15=>"English_SNC Series",
//             14=>"اردو_SNC Series",
//             16=>"Mathematics_SNC Series",
//             11=>"اسلامیات (پنجاب)_SNC Series",
//             12=>"General Knowledge_SNC Series"
//         ];

        
// $Class_2_SNC_Series=[
//     20=>"English_SNC Series",
//     18=>"اردو_SNC Series",
//     17=>"Mathematics_SNC Series",
//     21=>"اسلامیات (پنجاب)_SNC Series",
//     22=>"General Knowledge_SNC Series"
// ];

// $Class_3_SNC_Series=[
//     23=>"English_SNC Series",
//     28=>"اردو_SNC Series",
//     24=>"Mathematics_SNC Series",
//     25=>"اسلامیات (پنجاب)_SNC Series",
//     26=>"General Knowledge_SNC Series"
// ];

// $Class_4_SNC_Series=[
//     29=>"English_SNC Series",
//     32=>"Science_SNC Series",
//     30=>"Mathematics_SNC Series",
//     33=>"Social Studies_SNC Series",
//     34=>"اردو_SNC Series",
//     31=>"اسلامیات (پنجاب)_SNC Series"
// ];
// $Class_5_SNC_Series=[
//     36=>"English_SNC Series",
//     39=>"Science_SNC Series",
//     37=>"Mathematics_SNC Series",
//     38=>"Social Studies_SNC Series",
//     35=>"اردو_SNC Series",
//     40=>"اسلامیات (پنجاب)_SNC Series"];
//     $this->test($Class_1_SNC_Series,"Class 1_SNC Series");
//     $this->test($Class_2_SNC_Series,"Class 2_SNC Series");
//     $this->test($Class_3_SNC_Series,"Class 3_SNC Series");
//     $this->test($Class_4_SNC_Series,"Class 4_SNC Series");
//     $this->test($Class_5_SNC_Series,"Class 5_SNC Series");

//         dd('ok1');
        
//     }

//     public function test($sub,$className){
//         foreach($sub as  $key=>$value){
//             //dd($key,$value);
//             $questions=ClassSubjectQuestionBank::where('class_name',$className)
//             ->where('subject_name',$value)->get();
//             foreach($questions as $question){
//                  $question_type=$question->type;
//                      if($question->type=='true_@false'){
//                         $question_type='true_and_false';
//                     }
                    
//                 $check_chapter=SubjectChapter::where('chapter_name',$question->chapter_name)
//                 ->where('subject_id',$key)->first();

//                 if(Empty($check_chapter)){
//                     $create_chapter=SubjectChapter::create([
//                         'subject_id' =>$key,
//                         'chapter_name' => $question->chapter_name,
//                     ]);
            
//                     $create_question=SubjectQuestionBank::create(
//                         [
//                             'subject_id' =>$key,
//                             'chapter_id'  =>$create_chapter->id,
//                             'created_by'=>1,
//                             'question' =>$question->question,
//                             'type'=>$question_type,
//                             'option_a'=>$question->option_a,
//                             'option_b'=>$question->option_b,
//                             'option_c'=>$question->option_c,
//                             'option_d'=>$question->option_d,

//                         ]);
//                 }else{
                   
//                     $create_question=SubjectQuestionBank::create(
//                         [
//                             'subject_id' =>$key,
//                             'chapter_id'  =>$check_chapter->id,
//                             'created_by'=>1,
//                             'question' =>$question->question,
//                             'type'=>$question_type,
//                             'option_a'=>$question->option_a,
//                             'option_b'=>$question->option_b,
//                             'option_c'=>$question->option_c,
//                             'option_d'=>$question->option_d,

//                         ]);
//                 }
                

//             }

//         }
        
//     }
}