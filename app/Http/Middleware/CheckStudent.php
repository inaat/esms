<?php

namespace App\Http\Middleware;

use App\Models\ClassSection;
use App\Models\Students;
use App\Models\Teacher;
use Closure;
use Illuminate\Http\Request;

class CheckStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = $request->user()->id;
        $teacher_id = Teacher::where('user_id',$user_id)->pluck('id')->first();
        $class_section_id = ClassSection::where('class_teacher_id',$teacher_id)->pluck('id')->first();
        $student_class_section_id = Students::where('id',$request->student_id)->pluck('class_section_id')->first();
        if($class_section_id != $student_class_section_id){
            return response()->json(array(
                'error' => true,
                'message' => "Invalid Student ID Passed.",
                'code'=> 105,
            ));
        }
        return $next($request);
    }
}
