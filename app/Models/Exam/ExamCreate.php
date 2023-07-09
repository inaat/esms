<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamCreate extends Model
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
    public function session()
    {
        return $this->hasOne(\App\Models\Session::class, 'id', 'session_id');
    }
    public function term()
    {
        return $this->hasOne(ExamTerm::class, 'id', 'exam_term_id');
    }
    
    public function date_sheet()
    {
        return $this->hasMany(ExamDateSheet::class);
    }
 
    protected $casts = [
        'class_ids' => 'array'        
    ];
    public static function forDropdown($campus_id,$session_id)
    {
        $query =ExamCreate::
                             leftJoin('exam_terms', 'exam_creates.exam_term_id', '=', 'exam_terms.id')
                             ->where('exam_creates.campus_id',$campus_id)
                             ->where('exam_creates.session_id',$session_id)
                             ->select(['exam_creates.id','exam_terms.name']);
                             $query=$query->get();
                             $terms=$query->pluck('name', 'id');

        return  $terms;
    }
}
