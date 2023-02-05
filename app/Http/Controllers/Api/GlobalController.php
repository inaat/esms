<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\SubjectQuestionBank;
use Illuminate\Http\Request;
use DB;
class GlobalController extends Controller
{

    public function get_campus()
    {

        $campuses = Campus::get();
        //dd($campuses);

        return response()->json($campuses);
    }

    public function get_class($id)
    {

        $classes = Classes::where('campus_id', $id)->get();
        //dd($classes);

        return response()->json($classes);
    }
    public function get_subject($id)
    {

        $subjects = ClassSubject::where('class_id', $id)->get();
        return response()->json($subjects);
    }
    public function get_chapter($id)
    {

        $chapters = SubjectChapter::where('subject_id', $id)->get();
        return response()->json($chapters);
    }

    public function postQuestion(Request $request)
    {
        try {
            $data = explode('()', $request->input('question_data'));
            DB::beginTransaction();

            if ($request->input('type') == 'mcq') {
                foreach ($data as $key => $value) {
                    if (!empty($value)) {
                        $question = SubjectQuestionBank::where('question', $value)->first();
                        if (empty($question)) {
                            $mcq = explode('$mcq', $value);
                            $options = $mcq[1];
                            $new_options = [];
                            $option = explode('mcqo', $options);
                            foreach ($option as $option) {
                                if (!empty($option) && $option != " ") {
                                    $new_options[] = $option;
                                }
                            }
                            if (count($new_options) > 3) {
                                $sim_insert = [
                                    'subject_id' => $request->input('subject_id'),
                                    'chapter_id' => $request->input('chapter_id'),
                                    'type' => $request->input('type'),
                                    'question' => $mcq[0],
                                    'option_a' => $new_options[0],
                                    'option_b' => $new_options[1],
                                    'option_c' => $new_options[2],
                                    'option_d' => $new_options[3],

                                ];
                                $questions = SubjectQuestionBank::create($sim_insert);
                            }
                        }
                    }
                }
                    $output = ['success' => true,
                'msg' => __("english.add_success"),
            ];
                
            } else {
                foreach ($data as $key => $value) {
                    if (!empty($value)) {
                        $question = SubjectQuestionBank::where('question', $value)->first();
                        if (empty($question)) {
                            $sim_insert = [
                                'subject_id' => $request->input('subject_id'),
                                'chapter_id' => $request->input('chapter_id'),
                                'type' => $request->input('type'),
                                'question' => $value,
                            ];
                            $questions = SubjectQuestionBank::create($sim_insert);
                        }
                    }
                }
                $output = ['success' => true,
                'msg' => __("english.add_success"),
            ];
            }
            DB::commit();
 
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = ['success' => false,
                'msg' => __("english.something_went_wrong"),
            ];
        }

        return response($output);
    }

    public function postChapter(Request $request)
    {
        try {
            $data = explode('()', $request->input('question_data'));
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $chapter = SubjectChapter::where('chapter_name', $value)->first();
                    if (empty($chapter)) {
                        $sim_insert = [
                            'subject_id' => $request->input('subject_id'),
                            'chapter_name' => $value,
                        ];
                        $chapters = SubjectChapter::create($sim_insert);
                    }
                }

            }
            $output = ['success' => true,
                'msg' => __("english.add_success"),
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = ['success' => false,
                'msg' => __("english.something_went_wrong"),
            ];
        }

        return response($output);
    }

}
