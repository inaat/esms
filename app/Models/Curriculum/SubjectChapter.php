<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectChapter extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['id'];
    public function lesson()
    {
        return $this->hasMany(\App\Models\Curriculum\ClassSubjectLesson::class, 'chapter_id');
    }
    
    
    public static function forDropdown($subject_id, $show_none = false)
    {
        $query=SubjectChapter::orderBy('id', 'asc')->where('subject_id', $subject_id)
        ->pluck('chapter_name', 'id');
        $chapters=$query;
        if ($show_none) {
            $chapters->prepend(__('lang.none'), '');
        }

        return  $chapters;
    }
}
