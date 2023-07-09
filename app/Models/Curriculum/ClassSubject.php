<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ClassSubject extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['id'];
    protected $hidden = ["deleted_at", "created_at", "updated_at"];

    /**
     * Return list of ClassSubject for a business
     *
     * 
     * @param boolean $show_none = false
     *
     * @return array
     */
    public function campuses()
    {
        return $this->hasOne(\App\Models\Campus::class, 'id', 'campus_id');
    }
    public function classes()
    {
        return $this->belongsTo(\App\Models\Classes::class, 'class_id');
    }
    public function employees()
    {
        return $this->belongsTo(\App\Models\HumanRM\HrmEmployee::class, 'teacher_id');
    }
    
    public static function forDropdown($class_id,$show_none = false)
    {
        $query=ClassSubject::orderBy('id', 'asc')->where('class_id',$class_id)
        ->pluck('name', 'id');
        $subjects=$query;
        if ($show_none) {
            $subjects->prepend(__('lang.none'), '');
        }

        return  $subjects;
    }
    public static function allSubjectDropdown()
    {
        $query=ClassSubject::orderBy('id', 'asc')
        ->pluck('name', 'id');
        $subjects=$query;
        

        return  $subjects;
    }

     //Getter Attributes
     public function getImageAttribute($value) {
        return url(Storage::url($value));
    }
}
