<?php

namespace App\Models\Curriculum;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Curriculum\SubjectTeacher;
use Illuminate\Support\Facades\Storage;

class SubjectChapter extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['id'];
     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'video_link' => 'array',
        'subject_id' => 'integer',
    ];
    protected $hidden = ["deleted_at", "created_at", "updated_at"];

    protected static function boot() {
        parent::boot();
        static::deleting(function ($chapter) { // before delete() method call this
            if ($chapter->file) {
                foreach ($chapter->file as $file) {
                    if (Storage::disk('public')->exists($file->file_url)) {
                        Storage::disk('public')->delete($file->file_url);
                    }
                }

                $chapter->file()->delete();
            }
            if ($chapter->lesson) {
                $chapter->lesson()->delete();
            }
        });
    }

    public function lesson()
    {
        return $this->hasMany(\App\Models\Curriculum\ClassSubjectLesson::class, 'chapter_id');
    }

    public function topic()
    {
        return $this->hasMany(\App\Models\Curriculum\ClassSubjectLesson::class, 'chapter_id');
    }
    
    public function file() {
        return $this->morphMany(\App\Models\File::class, 'modal');
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



    
    public function scopeLessonTeachers($query) {
        $user = Auth::user();
        //if ($user->hasRole('Teacher#1')) {
            $teacher_id = $user->employee()->select('id')->pluck('id')->first();
            $subject_teacher = SubjectTeacher::select('class_section_id', 'subject_id')->where('teacher_id', $teacher_id)->get();
            // if ($subject_teacher) {
            //     $subject_teacher = $subject_teacher->toArray();
            //     $class_section_id = array_column($subject_teacher, 'class_section_id');
            //     $subject_id = array_column($subject_teacher, 'subject_id');
            //     return $query->whereIn('class_section_id', $class_section_id)->whereIn('subject_id', $subject_id);
            // }
            return $query;

       // }
        return $query;
    }
}
