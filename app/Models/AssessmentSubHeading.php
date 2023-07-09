<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentSubHeading extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function heading()
    {
        return $this->belongsTo(\App\Models\AssessmentHeading::class, 'heading_id');
    }

}
