<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class StudentGuardian extends Model
{
    protected $fillable = ['student_id', 'guardian_id'];


    public function student_guardian()
    {
        return $this->hasOne(Guardian::class, 'id', 'guardian_id');
    }
    public function students()
    {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }
    public static function forDropdown($guardian_id)
    {

        $query=StudentGuardian::leftJoin('students', 'student_guardians.student_id', '=', 'students.id')
        ->where('student_guardians.guardian_id' ,$guardian_id )
         ->select('student_guardians.student_id as id', DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,'')) as full_name"));

        $all_vehicles=$query->get();
        $vehicles = $all_vehicles->pluck('full_name', 'id');
     

        return $vehicles;
    }
}
