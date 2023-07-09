<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Curriculum\ClassSubjectProgress;
use App\Utils\Util;


class LessonResource extends JsonResource
{
    protected $commonUtil;
     /**
    * Constructor
    *
    * @param Util $commonUtil
    * @return void
    */
    // public function __construct(Util $commonUtil)
    // {
    //     $this->commonUtil = $commonUtil;
    // }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'progress' => $this->multiTeacher($request->class_id,$request->section_id,$this->subject_id,$this->chapter_id),
            'video_link' =>$this->video_link
        ];
    }

    public function multiTeacher($class_id,$section_id,$subject_id,$chapter_id){
        $class_subject_progress = ClassSubjectProgress::leftjoin('class_subject_lessons  as csL', 'class_subject_progress.lesson_id', '=', 'csL.id')
        ->leftjoin('subject_chapters as chap', 'class_subject_progress.chapter_id', '=', 'chap.id')
      //  ->where('class_subject_progress.session_id', $this->commonUtil->getActiveSession())
       ->where('class_subject_progress.class_id',$class_id)->where('class_subject_progress.class_section_id',$section_id)
       ->where('class_subject_progress.subject_id',$subject_id)->where('class_subject_progress.chapter_id',$chapter_id);
      
        $class_subject_progress->select(['csL.name','chap.chapter_name as chapter_name','class_subject_progress.complete_date','class_subject_progress.start_date','class_subject_progress.reading_date','class_subject_progress.status', 'class_subject_progress.id']);

        return $class_subject_progress->get();
    }

}