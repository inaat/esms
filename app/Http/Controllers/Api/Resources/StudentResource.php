<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Student;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $student=$this->getStudentDetails($this->hook_id);
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'user_type' => $this->user_type,
            'current_class'=>$student->current_class->title,
            'class_id'=>$student->current_class->id,
            'current_section'=>$student->current_class_section->section_name,
            'section_id'=>$student->current_class_section->id,
            'category'=>$student->studentCategory?$student->studentCategory->cat_name:'',
            'roll_no'=>$student->roll_no,
            'student_image' => url('uploads/student_image/'.$student->student_image)

                        //'role' => $this->role
        ];
    }

    public function getStudentDetails($id){
        $student = Student::with(['studentCategory','guardian','guardian.student_guardian','admission_class','current_class','current_class_section'])->findOrFail($id);
         return $student;

    }
}