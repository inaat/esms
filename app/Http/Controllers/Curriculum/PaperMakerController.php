<?php

namespace App\Http\Controllers\Curriculum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\SubjectQuestionBank;


use App\Models\Curriculum\ClassSubjectQuestionBank;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Session;
use App\Models\HumanRM\HrmEmployee;

use Yajra\DataTables\Facades\DataTables;
use DB;

class PaperMakerController extends Controller
{
    public function manualPaperCreate()
    {
        $campuses=Campus::forDropdown();
        $sessions=Session::forDropdown(false, true);
        
        return view('Curriculum.paper_maker.manual_paper_create')->with(compact('campuses', 'sessions'));
    }

   
 /**
     * Returns the html for labels preview
     *
     * @return \Illuminate\Http\Response
     */
    public function generateManualPaper(Request $request)
    {
        try {
          
            $mcq_questions=null;
                    $fill_in_the_blank_questions=null;
                    $true_and_false_questions=null;
                    $paraphrase_questions=null;
                    $passage_questions=null;
                    $stanza_questions=null;
                    $short_questions=null;
                    $long_questions=null;
                    $column_matching_questions=null;
                    $translation_to_urdu_questions=null;
                    $translation_to_english_questions=null;
                    $words_and_use_questions=null;
                    $contextual_questions=null;
                    $singular_and_plural_questions=null;
                    $masculine_and_feminine_questions=null;
                    $grammar_questions=null;
                    $input=$request->input();
                   //dd($request->input());
                   $class_subject=ClassSubject::with('classes')->findOrFail($input['subject_id']);
                    if (!empty($input['mcq_questions'])) {
                        $mcq_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['mcq_questions'])->get();
                    }
                    if (!empty($input['fill_in_the_blank_questions'])) {
                        $fill_in_the_blank_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['fill_in_the_blank_questions'])->get();
                    }
                    if (!empty($input['true_and_false_questions'])) {
                        $true_and_false_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['true_and_false_questions'])->get();
                    }
                    if (!empty($input['column_matching_questions'])) {
                        $column_matching_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['column_matching_questions'])->get();
                    }
                    if (!empty($input['paraphrase_questions'])) {
                        $paraphrase_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['paraphrase_questions'])->get();
                    }
                    if (!empty($input['masculine_and_feminine_questions'])) {
                        $masculine_and_feminine_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['masculine_and_feminine_questions'])->get();
                    }
                    if (!empty($input['singular_and_plural_questions'])) {
                        $singular_and_plural_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['singular_and_plural_questions'])->get();
                    }
                    if (!empty($input['contextual_questions'])) {
                        $contextual_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['contextual_questions'])->get();
                    }
                    if (!empty($input['grammar_questions'])) {
                        $grammar_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['grammar_questions'])->get();
                    }
                    if (!empty($input['stanza_questions'])) {
                        $stanza_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['stanza_questions'])->get();
                    }
                    if (!empty($input['translation_to_english_questions'])) {
                        $translation_to_english_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['translation_to_english_questions'])->get();
                    }
                    if (!empty($input['translation_to_urdu_questions'])) {
                        $translation_to_urdu_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['translation_to_urdu_questions'])->get();
                    }
                    if (!empty($input['words_and_use_questions'])) {
                        $words_and_use_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['words_and_use_questions'])->get();
                    }
                    if (!empty($input['short_question_questions'])) {
                        $short_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['short_question_questions'])->get();
                    }
                    if (!empty($input['long_question_questions'])) {
                        $long_questions=SubjectQuestionBank::where('subject_id', $input['subject_id'])->whereIn('id', $input['long_question_questions'])->get();
                    }
            
if ($class_subject->subject_input =='eng') {
    $output  = view('Curriculum.paper_maker.generate_manual_paper', compact(
        'mcq_questions',
        'fill_in_the_blank_questions',
        'true_and_false_questions',
        'words_and_use_questions',
        'contextual_questions',
        'grammar_questions',
        'singular_and_plural_questions',
        'masculine_and_feminine_questions',
        'short_questions',
        'paraphrase_questions',
        'passage_questions',
        'stanza_questions',
        'translation_to_urdu_questions',
        'translation_to_english_questions',
        'long_questions',
        'column_matching_questions',
        'class_subject',
        'input'
    ))->render();
}else{
    $output  = view('Curriculum.paper_maker.urdu_manual_paper', compact(
        'mcq_questions',
        'fill_in_the_blank_questions',
        'true_and_false_questions',
        'words_and_use_questions',
        'contextual_questions',
        'grammar_questions',
        'singular_and_plural_questions',
        'masculine_and_feminine_questions',
        'short_questions',
        'paraphrase_questions',
        'passage_questions',
        'stanza_questions',
        'translation_to_urdu_questions',
        'translation_to_english_questions',
        'long_questions',
        'column_matching_questions',
        'class_subject',
        'input'
    ))->render();
}
               
               print_r($output);

             print_r('<script>window.print()</script>');


        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = __('english.barcode_label_error');
        }

        //return $output;
    }
    public function show($id)
    {
        if (request()->ajax()) {
            try {
                $output = ['success' => 0,
                'msg' => trans("messages.something_went_wrong")
                ];
               
                $receipt = $this->receiptContent($id);
              

                if (!empty($receipt)) {
                    $output = ['success' => 1, 'receipt' => $receipt];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                
                $output = ['success' => 0,
                        'msg' => trans("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    /**
     * Returns the content for the receipt
     *
     * @param  int  $business_id
     * @param  int  $location_id
     * @param  int  $transaction_id
     * @param string $printer_type = null
     *
     * @return array
     */
    private function receiptContent($class_subject,$mcq_questions,$fill_in_the_blank_questions,$true_and_false_questions,
    $short_questions,$long_questions,$column_matching_questions,$input)
    {
        $output = ['is_enabled' => false,
                    'print_type' => 'browser',
                    'html_content' => null,
                    'printer_config' => [],
                    'data' => []
                ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details=[];
        if($class_subject->subject_input=='ur'){
 
        $output['html_content'] = view('Curriculum.paper_maker.urdu_manual_papaer', compact('mcq_questions','fill_in_the_blank_questions','true_and_false_questions',
        'short_questions','long_questions','column_matching_questions','class_subject','input'))->render();
        }else{
            $output['html_content'] = view('Curriculum.paper_maker.generate_manual_paper', compact('mcq_questions','fill_in_the_blank_questions','true_and_false_questions',
            'short_questions','long_questions','column_matching_questions','class_subject','input'))->render(); 
        }
        
        return $output;
    }


          
    

}