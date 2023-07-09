<?php

namespace App\Http\Controllers\StaffLayout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Curriculum\SubjectTeacher;
use App\Models\Curriculum\ClassTimeTable;
use App\Models\Campus;
use App\Models\Classes;
use App\Models\Session;
use App\Models\HumanRM\HrmEmployee;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Validator;
use DB;
use File;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if ($user->user_type == 'staff') {
            $vehicle=Vehicle::where('employee_id', $user->hook_id)->first();
            $students=false;       
       if (!empty($vehicle)) {
         $students=Student::where('vehicle_id', $vehicle->id)->get();
     }
            return view('staff_layouts.staff_dashboard.index')->with(compact('vehicle','students'));
        } else {
            return redirect('/dashboard');
        }
    }
  
}
