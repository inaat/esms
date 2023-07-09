<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentStudentHeading extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function assessments()
    {
        return $this->belongsTo(AssessmentStudent::class,);
    }
}
