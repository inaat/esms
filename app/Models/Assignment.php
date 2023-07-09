<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\Curriculum\SubjectTeacher;
class Assignment extends Model
{
    use HasFactory;

    protected $hidden = ["deleted_at", "updated_at"];
    protected $casts = [
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
        'class_section_id' => 'integer',
         'resubmission' => 'integer',
        'extra_days_for_resubmission' => 'integer',
        'session_id' => 'integer',
          'points' => 'integer',
    ];
    protected static function boot() {
        parent::boot();
        static::deleting(function ($assignment) { // before delete() method call this
            //Deletes all the Assignment Submissions first
            $assignment_submission = AssignmentSubmission::where('assignment_id', $assignment->id)->get();
            if ($assignment_submission) {
                foreach ($assignment_submission as $submission) {
                    if (isset($submission->file)) {
                        foreach ($submission->file as $file) {
                            if (Storage::disk('public')->exists($file->file_url)) {
                                Storage::disk('public')->delete($file->file_url);
                            }
                        }
                        $submission->delete();
                    }
                }
            }

            //After that Delete Assignment and its files from the server
            if ($assignment->file) {
                foreach ($assignment->file as $file) {
                    if (Storage::disk('public')->exists($file->file_url)) {
                        Storage::disk('public')->delete($file->file_url);
                    }
                }
            }
            $assignment->file()->delete();
        });
    }

   
    public function subject()
    {
        return $this->belongsTo(\App\Models\Curriculum\ClassSubject::class, 'subject_id');
    }

    public function submission() {
        return $this->hasOne(AssignmentSubmission::class);
    }

  
    public function classes()
    {
        return $this->belongsTo(\App\Models\Classes::class, 'class_id');
    }
    public function class_section()
    {
        return $this->belongsTo(\App\Models\ClassSection::class, 'class_section_id');
    }
    public function file() {
        return $this->morphMany(File::class, 'modal');
    }

    public function scopeAssignmentTeachers($query) {
        $user = Auth::user();
        if ($user->hasRole('Teacher')) {
            $teacher_id = $user->teacher()->select('id')->pluck('hook_id')->first();
            $subject_teacher = SubjectTeacher::select('class_section_id', 'subject_id')->where('teacher_id', $teacher_id)->get();
            if ($subject_teacher) {
                $subject_teacher = $subject_teacher->toArray();
                $class_section_id = array_column($subject_teacher, 'class_section_id');
                $subject_id = array_column($subject_teacher, 'subject_id');
                return $query->whereIn('class_section_id', $class_section_id)->whereIn('subject_id', $subject_id);
            }
            return $query;
        }
        return $query;
    }
}
