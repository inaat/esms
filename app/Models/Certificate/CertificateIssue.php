<?php

namespace App\Models\Certificate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CertificateIssue extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];



    public function campuses()
    {
        return $this->hasOne(\App\Models\Campus::class, 'id', 'campus_id');
    }
 
    public function certificate_type()
    {
        return $this->hasOne(CertificateType::class, 'id', 'campus_id');
    }
 
   
    
    
    public function student()
    {
        return $this->hasOne(\App\Models\Student::class, 'id','student_id');
    }
    
    
}
