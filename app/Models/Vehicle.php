<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class Vehicle extends Model
{
   
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

 

  
    public static function forDropdown($show_none = false)
    {

        $query=Vehicle::leftJoin('hrm_employees', 'vehicles.employee_id', '=', 'hrm_employees.id')
        ->leftJoin('hrm_designations', 'hrm_employees.designation_id', '=', 'hrm_designations.id')
        // ->select('vehicles.id as id', DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,''),'(',COALESCE(vehicles.name,''),')') as full_name"));
        ->select('vehicles.id as id', DB::raw("CONCAT(COALESCE(vehicles.name,''),'  (',COALESCE(vehicles.vehicle_number,''),') (',COALESCE(hrm_employees.first_name, ''),' ',COALESCE(hrm_employees.last_name,''),')') as full_name"));
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('vehicles.campus_id', $permitted_campuses);
        }
        $all_vehicles=$query->get();
        $vehicles = $all_vehicles->pluck('full_name', 'id');
        if ($show_none) {
            $vehicles->prepend(__('lang.none'), '');
        }

        return $vehicles;
    }

}
