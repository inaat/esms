<?php

namespace App\Models\Certificate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRegister extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    protected $table_name='withdrawal_registers';



    public function campuses()
    {
        return $this->hasOne(\App\Models\Campus::class, 'id', 'campus_id');
    }
 
    public function leaving_class()
    {
        return $this->hasOne(\App\Models\Classes::class, 'id', 'leaving_class_id');
    }
    
    
    public function student()
    {
        return $this->hasOne(\App\Models\Student::class, 'id','student_id');
    }
    
    
}
