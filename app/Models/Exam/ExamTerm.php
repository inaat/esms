<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamTerm extends Model
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
  
    public static function forDropdown($show_none = false)
    {
        $query=ExamTerm::orderBy('id', 'asc')
        ->pluck('name', 'id');
        $terms=$query;
        if ($show_none) {
            $terms->prepend(__('lang.all'), '');
        }

        return  $terms;
    }
}
