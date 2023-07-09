<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssignmentSubmission extends Model
{
    use HasFactory;
    protected $casts = [
        'assignment_id' => 'integer',
        'student_id' => 'integer',
        'session_id' => 'integer',
        'points' => 'integer',
        'status' => 'integer',

       
    ];
    public function file() {
        return $this->morphMany(File::class, 'modal');
    }

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function scopeAssignmentSubmissionTeachers($query) {
        $user = Auth::user();
        if ($user->hasRole('Teacher')) {
            $teacher_id = $user->teacher()->select('id')->pluck('hook_id')->first();
            $subject_teacher = SubjectTeacher::select('class_section_id', 'subject_id')->where('teacher_id', $teacher_id)->get();
            if ($subject_teacher) {
                $subject_teacher = $subject_teacher->toArray();
                $class_section_id = array_column($subject_teacher, 'class_section_id');
                $subject_id = array_column($subject_teacher, 'subject_id');
                $assignment_id = Assignment::select('id')->whereIn('class_section_id', $class_section_id)->whereIn('subject_id', $subject_id)->get()->pluck('id');
                return $query->whereIn('assignment_id', $assignment_id);
            }
            return $query;
        }
        return $query;
    }
}
