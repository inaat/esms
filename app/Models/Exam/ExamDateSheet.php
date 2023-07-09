<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamDateSheet extends Model
{
    use HasFactory;
    protected $casts = [
        'exam_create_id' => 'integer',
    ];
    protected $guarded = ['id'];

    public function exam_create()
    {
        return $this->belongsTo(ExamCreate::class);
    }
    public function subject()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubject::class, 'subject_id');
    }
    
}
