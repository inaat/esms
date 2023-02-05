<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSubjectProgress extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    /**
     * Return list of ClassSubject for a business
     *
     * 
     * @param boolean $show_none = false
     *
     * @return array
     */
    public function subject()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubject::class, 'subject_id');
    }
    public function lesson()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubjectLesson::class, 'lesson_id');
    }
    public function chapter()
    {
        return $this->belongsTo(\App\Models\Curriculum\SubjectChapter::class, 'chapter_id');
    }
    // public static function forDropdown($show_none = false)
    // {
    //     $query=HrmAllowance::orderBy('id', 'asc')
    //     ->pluck('allowance', 'id');

       
        

    //     $allowances=$query;
    //     if ($show_none) {
    //         $allowances->prepend(__('lang.none'), '');
    //     }

    //     return  $allowances;
    // }
}
