<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusManger extends Model
{
     
    protected $guarded = ['id'];
    /**
     * Return list of ClassSubject for a business
     *
     * 
     * @param boolean $show_none = false
     *
     * @return array
     */
    public function campus()
    {
        return $this->belongsTo(\App\Models\Campus::class, 'campus_id');
    }
    public function classes()
    {
        return $this->belongsTo(\App\Models\Classes::class, 'class_id');
    }
    public function class_subject()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubject::class, 'subject_id');
    }
    public function chapter()
    {
        return $this->belongsTo(\App\Models\Curriculum\SubjectChapter::class, 'chapter_id');
    }
    public function term()
    {
        return $this->belongsTo(\App\Models\Exam\ExamTerm::class, 'exam_term_id');
    }
}