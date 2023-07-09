<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class Student extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
        * The attributes that aren't mass assignable.
        *
        * @var array
        */
        protected $guarded = ['id'];

    public function guardian()
    {
        return $this->hasOne(StudentGuardian::class, 'student_id', 'id');
    }
    public function campuses()
    {
        return $this->hasOne(Campus::class, 'id', 'campus_id');
    }
    public function current_class()
    {
        return $this->hasOne(Classes::class, 'id', 'current_class_id');
    }
    public function studentCategory()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function admission_class()
    {
        return $this->hasOne(Classes::class, 'id', 'adm_class_id');
    }
    public function student_class()
    {
        return $this->belongsTo(Classes::class, 'current_class_id');
    }
    public function current_class_section()
    {
        return $this->hasOne(ClassSection::class, 'id', 'current_class_section_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class,'student_id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
 
    public function adm_session()
    {
        return $this->hasOne(Session::class, 'id', 'adm_session_id');
    }
    public function country()
    {
        return $this->hasOne(Currency::class, 'id', 'country_id');
    }
    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }
    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
    public function region()
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }
    public static function forDropdown($system_settings_id,$show_none = false,$class_id =null,$section_id =null)
    {
        // $query=Student::where('system_settings_id',$system_settings_id);
        $query = Student::where('status','active')->select('id', DB::raw("CONCAT(COALESCE(students.first_name, ''),' ',COALESCE(students.last_name,''),'(',COALESCE(students.roll_no,''),')') as student_name"));
       
       
        if(!empty($class_id)){
            $query->where('current_class_id',$class_id);
        }
        if(!empty($section_id)){
            $query->where('current_class_section_id',$section_id);
        }
        

        $students=$query->orderBy('id', 'asc')
        ->pluck('student_name', 'id');
       // dd($students);
        if ($show_none) {
            $students->prepend(__('lang.none'), '');
        }

        return  $students;
    }
   // public function getFatherImageAttribute($value) {
      //  return url($value);
    //}
    // public function getMotherImageAttribute($value) {
    //     return url($value);
    // // }
    // public function getStudentImageAttribute($value) {
    //     return url($value);
    // }

    public function user() {
        return $this->belongsTo(User::class);
    }
  
}
