<?php

namespace App\Models;

use App\Models\HumanRM\HrmDeduction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentStudent extends Model
{
    use HasFactory;
    protected $guarded = ['id'];





    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
   
    public function student_class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function student_class_section()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }
    public function heading_lines()
    {
        return $this->hasMany(AssessmentStudentHeading::class, 'assessment_students_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

   

}
