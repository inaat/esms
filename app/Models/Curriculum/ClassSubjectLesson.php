<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ClassSubjectLesson extends Model
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
        'chapter_id' => 'integer',
        'subject_id' => 'integer',
    ];
    protected $hidden = ["deleted_at", "created_at", "updated_at"];

    protected static function boot() {
        parent::boot();
        static::deleting(function ($lesson) { // before delete() method call this
            if ($lesson->file) {
                foreach ($lesson->file as $file) {
                    if (Storage::disk('public')->exists($file->file_url)) {
                        Storage::disk('public')->delete($file->file_url);
                    }
                }

                $lesson->file()->delete();
            }
            if ($lesson->lessonProgress) {
                $lesson->lessonProgress()->delete();
            }
        });
    }
    public function lessonProgress()
    {
        return $this->hasMany(\App\Models\Curriculum\ClassSubjectProgress::class, 'lesson_id');
    }
    
    public function file() {
        return $this->morphMany(\App\Models\File::class, 'modal');
    }

   /**
       * Return list of   $ClassSubjectLesson for a organization
       *
       * @param int $  $ClassSubjectLesson
       * @param boolean $show_none = false
       *
       * @return array
       */

      public static function forDropdown($subject_id,$chapter_id,$show_none = false)
      {
          $query=ClassSubjectLesson::where('subject_id',$subject_id);
  
          if(!empty($chapter_id)){
              $query->where('chapter_id',$chapter_id);
          }
          
  
          $lessons=$query->orderBy('id', 'asc')
          ->pluck('name', 'id');
          if ($show_none) {
              $lessons->prepend(__('lang.none'), '');
          }
  
          return  $lessons;
      }
  }

