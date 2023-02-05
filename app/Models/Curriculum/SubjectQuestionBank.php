<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectQuestionBank extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['id']; 
    
    public function chapter()
    {
        return $this->belongsTo(\App\Models\Curriculum\SubjectChapter::class, 'chapter_id');
    }
}
