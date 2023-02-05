<?php

namespace App\Http\Controllers\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Classes;

use App\Models\ClassSection;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Utils\StudentUtil;
use App\Utils\NotificationUtil;
class QrCodeAttendanceController extends Controller
{

    public function create(){

        return view('QrCodeAttendance.create');
    }
    public function employee_create(){

        return view('QrCodeAttendance.employee_create');
    }
}