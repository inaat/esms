<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ClassTimeTable extends Model
{
    use HasFactory;
    //use SoftDeletes;
    protected $casts = [
        'multi_subject_ids' => 'array',
        'multi_teacher' => 'array',
        
    ];
    protected $guarded = ['id'];
    protected $table = 'class_timetables';
    public function campuses()
    {
        return $this->hasOne(\App\Models\Campus::class, 'id', 'campus_id');
    }
    public function classes()
    {
        return $this->belongsTo(\App\Models\Classes::class, 'class_id');
    }
    public function section()
    {
        return $this->belongsTo(\App\Models\ClassSection::class, 'class_section_id');
    }
  
    public function subjects()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubject::class, 'subject_id');
    }
    public function periods()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassTimeTablePeriod::class, 'period_id');
    }
    public function teacher()
    {
        return $this->belongsTo(\App\Models\HumanRM\HrmEmployee::class, 'teacher_id');
    }
}
