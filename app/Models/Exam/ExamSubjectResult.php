<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubjectResult extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    public function campuses()
    {
        return $this->hasOne(\App\Models\Campus::class, 'id', 'campus_id');
    }
    public function current_class()
    {
        return $this->hasOne(\App\Models\Classes::class, 'id', 'class_id');
    }
 
    public function current_class_section()
    {
        return $this->hasOne(\App\Models\ClassSection::class, 'id', 'class_section_id');
    }
    public function session()
    {
        return $this->hasOne(\App\Models\Session::class, 'id', 'session_id');
    }
    public function exam_create()
    {
        return $this->hasOne(ExamCreate::class, 'id', 'exam_create_id');
    }
    public function student()
    {
        return $this->hasOne(\App\Models\Student::class, 'id','student_id');
    }
    public function subject_grade()
    {
        return $this->hasOne(ExamGrade::class, 'id','grade_id');
    }
    public function subject_name()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubject::class, 'subject_id');
    }
    public function teacher()
    {
        return $this->belongsTo(\App\Models\HumanRM\HrmEmployee::class, 'teacher_id');
    }
}
