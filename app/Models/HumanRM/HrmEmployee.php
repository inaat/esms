<?php

namespace App\Models\HumanRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use DB;
class HrmEmployee extends Model
{
    use HasFactory;
    
    protected $casts = [
        'education_ids' => 'array'
    ];
    protected $guarded = ['id'];


    public function campuses()
    {
        return $this->hasOne(\App\Models\Campus::class, 'id', 'campus_id');
    }
    public function designations()
    {
        return $this->hasOne(HrmDesignation::class, 'id', 'designation_id');
    }
    public function department()
    {
        return $this->hasOne(HrmDepartment::class, 'id', 'department_id');
    }
    public function education()
    {
        return $this->hasOne(HrmEducation::class, 'id', 'education_id');
    }
    public function attendances()
    {
        return $this->hasMany(HrmAttendance::class,'employee_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
        /**
     * Return list of employees dropdown for a campus
     *
     * @param $prepend_none = true (boolean)
     *
     * @return array use
     */
    public static function forDropdown($prepend_none = false,$prepend_all = false, $campus = false)
    {
     
        $all_employees = HrmEmployee::select('id', DB::raw("CONCAT(COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"));
        
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $all_employees->whereIn('campus_id', $permitted_campuses);
        }
        $employees = $all_employees->get()->pluck('full_name', 'id');
        

        //Prepend none
        if ($prepend_none) {
            $employees = $employees->prepend(__('english.none'), '');
        }

        //Prepend all
        if ($prepend_all) {
            $employees = $employees->prepend(__('english.all'), '');
        }
        
        return $employees;
    }
    public static function teacherDropdown($prepend_none = false,$prepend_all = false, $campus = false,$status=true)
    {
     
        $all_employees = HrmEmployee::leftJoin('hrm_designations', 'hrm_employees.designation_id', '=', 'hrm_designations.id')
        ->leftJoin('users', 'hrm_employees.id', '=', 'users.hook_id')
      // ->where('hrm_designations.designation','=',['teacher','director','Pet','Principal','Vice Principal'])
       // ->where('users.user_type','teacher')
        ->select('hrm_employees.id as id', DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,''),'(',COALESCE(hrm_designations.designation,''),')') as full_name"));
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $all_employees->whereIn('hrm_employees.campus_id', $permitted_campuses);
        }
        if($status){
            $all_employees->where('hrm_employees.status','active');
        }
        $all_employees=$all_employees->get();
        $employees = $all_employees->pluck('full_name', 'id');
        //Prepend none
        if ($prepend_none) {
            $employees = $employees->prepend(__('english.none'), '');
        }

        //Prepend all
        if ($prepend_all) {
            $employees = $employees->prepend(__('english.all'), '');
        }
        
        return $employees;
    }
    public static function driverDropdown($prepend_none = false,$prepend_all = false, $campus = false,$status=true)
    {
     
        $all_employees = HrmEmployee::leftJoin('hrm_designations', 'hrm_employees.designation_id', '=', 'hrm_designations.id')
      ->where('hrm_designations.designation','=',['Driver'])
        ->select('hrm_employees.id as id', DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,''),'(',COALESCE(hrm_designations.designation,''),')') as full_name"));
        $permitted_campuses = auth()->user()->permitted_campuses();

        if ($permitted_campuses != 'all') {
            $all_employees->whereIn('hrm_employees.campus_id', $permitted_campuses);
          }
        if($status){
            $all_employees->where('hrm_employees.status','active');
        }
        $all_employees=$all_employees->get();
        $employees = $all_employees->pluck('full_name', 'id');
        //Prepend none
        if ($prepend_none) {
            $employees = $employees->prepend(__('english.none'), '');
        }

        //Prepend all
        if ($prepend_all) {
            $employees = $employees->prepend(__('english.all'), '');
        }
        
        return $employees;
    }
}
