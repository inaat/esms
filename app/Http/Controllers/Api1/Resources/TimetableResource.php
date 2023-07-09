<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Curriculum\ClassSubject;
use App\Models\HumanRM\HrmEmployee;

class TimetableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
   
        return [
            'id' => $this->id,
            'start_time'=>$this->periods->start_time,
            'end_time'=>$this->periods->end_time,
            'other' => $this->other,
            "day"=> 1,
            "day_name"=> "monday",
            "subject"=> [ 
                'id' => 1,
                'name' => $this->subject_id? $this->subjects->name:$this->multiSubject($this->multi_subject_ids),
                'code' => $this->subject_id? $this->subjects->code:'N/A',
                'bg_color' => '#65a3fe',
                'image' => 'https://e-school.wrteam.in/storage/subjects/9KzzSrwwpyCeGCcQnej2VeXcew7T719PrFXQZ7SP.svg',
                'medium_id' => 1,
                'type' => 'Practical',
                  
                ],
                "teacher_first_name"=> $this->teacher_id ?$this->teacher->first_name:' ',
                "teacher_last_name"=> $this->teacher_id ?$this->teacher->last_name:$this->multiTeacher($this->multi_teacher),
              


            
        ];
    }
    public function multiSubject($subjects){
        $all_subjects=ClassSubject::allSubjectDropdown();
        $subject_name='';
        foreach ($subjects as $multi_subject ){
             $subject_name .=$all_subjects[$multi_subject].' + ';
        }  
        return $subject_name;
    }
    public function multiTeacher($teachers){
        $teacher_name='';
        foreach ($teachers as $multi_teacher ){
            $teacher=HrmEmployee::find($multi_teacher);

             $teacher_name .= $teacher->first_name.' '.$teacher->last_name .' + ';
        }  
        return $teacher_name;
    }
    
}