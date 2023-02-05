<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectTeacher extends Model
{
    use HasFactory;
    use SoftDeletes;
    
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
    public function class_section()
    {
        return $this->belongsTo(\App\Models\ClassSection::class, 'class_section_id');
    }
    public function teacher()
    {
        return $this->belongsTo(\App\Models\HumanRM\HrmEmployee::class, 'teacher_id');
    }
    public function class_subject()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubject::class, 'subject_id');
    }


    public static function forDropdown($class_id,$class_section_id,$show_none = false)
    {
        $query=SubjectTeacher::leftjoin('campuses as cam', 'subject_teachers.campus_id', '=', 'cam.id')
        ->leftjoin('class_subjects as sub', 'subject_teachers.subject_id', '=', 'sub.id')
         ->where('subject_teachers.class_section_id', $class_section_id)
         ->where('subject_teachers.class_id', $class_id)
         ->select(['sub.id','sub.name']);
        $query=$query->get();
        $subjects=$query->pluck('name', 'id');;
        if ($show_none) {
            $subjects->prepend(__('lang.none'), '');
        }

        return  $subjects;
    }
    public static function subjects_list($campus_id,$class_id,$class_section_id)
    {
        $query=SubjectTeacher::leftjoin('campuses as cam', 'subject_teachers.campus_id', '=', 'cam.id')
        ->leftjoin('class_subjects as sub', 'subject_teachers.subject_id', '=', 'sub.id')
         ->where('subject_teachers.class_section_id', $class_section_id)
         ->select(['sub.id','sub.name','sub.total']);
       
        return  $query->get();
    }
    

}
