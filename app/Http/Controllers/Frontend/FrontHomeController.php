<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use App\Models\Frontend\FrontAboutUs;
use App\Models\Frontend\FrontGalleryContent;
use App\Models\Frontend\FrontEvent;
use Illuminate\Http\Request;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\ClassSection;
use App\Models\Campus;
use App\Models\ClassLevel;
use App\Models\Users;
use App\Models\Classes;
use App\Models\Session;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmEducation;
use DB;

class FrontHomeController extends Controller
{
    public function index()
    {
        $news=FrontNews::orderBy('id', 'DESC')->get();
        $slider=FrontSlider::orderBy('id', 'DESC')->get();
        $about_us=FrontAboutUs::get();
        $galleries = FrontGalleryContent::orderBy('id', 'DESC')->where('status','publish')->select(['title', 'id','slug','thumb_image'])->get();
        $events = FrontEvent::orderBy('id', 'DESC')->where('status','publish')->select(['title', 'id','slug','images'])->get();

        //dd($galleries);
        $topers = null;
        //dd($topers[2]['data']->final_percentage);
        return view('frontend.index')->with(compact('slider', 'news', 'about_us', 'topers','galleries','events'));
    }
    public function about_index()
    {
        $data = FrontAboutUs::first();
        $nav = frontMenu();
        return view('frontend.about_us.show')->with(compact('data', 'nav'));
    }
    public function about_show($slug, $id)
    {
        $data = FrontAboutUs::where('id', $id)
        // ->orWhere('slug', $slug)
         ->firstOrFail();
        $nav = frontMenu();
        return view('frontend.about_us.show')->with(compact('data', 'nav'));
    }
    public function event_index()
    {
        $data = FrontEvent::first();
        $nav = frontEventMenu();
        return view('frontend.event.show')->with(compact('data', 'nav'));
    }
    public function event_show($slug, $id)
    {
        $data = FrontEvent::where('id', $id)
        // ->orWhere('slug', $slug)
         ->firstOrFail();
        $nav = frontEventMenu();
        return view('frontend.event.show')->with(compact('data', 'nav'));
    }
    public function news_show($slug, $id)
    {
        $data = FrontNews::where('id', $id)
     // ->orWhere('slug', $slug)
        ->firstOrFail();
        $nav=FrontNews::select('title', 'id', 'slug')->get();
        //dd();
        return view('frontend.news.show')->with(compact('data', 'nav'));
    }

    public function gallery()
    {
        $albums=FrontGalleryContent::select('title', 'id', 'slug', 'thumb_image', 'description')->orderBy('id', 'desc')->get();
        return view('frontend.gallery.index')->with(compact('albums'));
    }
    public function gallery_show($slug, $id)
    {
        $query=FrontGalleryContent::where('id', $id)->firstOrFail();
        $data=json_decode($query->elements);
        return view('frontend.gallery.show')->with(compact('query', 'data'));
    }
    public function examResult()
    {        return view('frontend.exam_result');
    }

    public function faculty()
    {
        $all_employees = HrmEmployee::leftJoin('hrm_designations', 'hrm_employees.designation_id', '=', 'hrm_designations.id')
    ->whereIn('hrm_designations.designation', [
        'Teacher', 'Director', 'Principal', 'Lecturer',
        'Senior Lecturer', 'Visiting Lecturer', 'Visiting Faculty', 'Vice Principal'
    ])
    ->select(
        'hrm_designations.designation',
        'hrm_employees.education_ids',
        'hrm_employees.employee_image',
        DB::raw("CONCAT(COALESCE(hrm_employees.first_name, ''), ' ', COALESCE(hrm_employees.last_name, '')) as full_name")
    )
    ->get();
      $faculty=[];
        foreach ($all_employees as $key => $value) {

            if (!empty($value->education_ids)) {
                $educations = HrmEducation::whereIn('id', $value->education_ids)->pluck('education');
        
                if ($educations->count() > 1) {
                    $educations = $educations->implode(', ');
                } else {
                    $educations = $educations->first();
                }

                $faculty[]=[
                     'full_name'=>$value->full_name,
                     'designation'=>$value->designation,
                     'educations'=>$educations,
                     'image' => file_exists(public_path('uploads/employee_image/'.$value->employee_image)) ? url('uploads/employee_image/'.$value->employee_image) : url('uploads/employee_image/default.jpg')

                ];
             
            }else{
                $faculty[]=[
                    'full_name'=>$value->full_name,
                    'designation'=>$value->designation,
                    'educations'=>'',
                    'image' => file_exists(public_path('uploads/employee_image/'.$value->employee_image)) ? url('uploads/employee_image/'.$value->employee_image) : url('uploads/employee_image/default.jpg')

               ];
            }
            # code...
        }

        return view('frontend.faculty')->with(compact('faculty'));
    }
    public function toppers()
    {
        $students=[];
        $session_id=1;
        $exam_create_id=4;
        $campus_id=1;
        $limit=1;
        $class_levels=ClassLevel::get();
        foreach ($class_levels as $CL) {
            $class_level_id=$CL->id;
            $class_level_name=ClassLevel::findOrFail($class_level_id);
            $class_ids=Classes::where('class_level_id', $class_level_id)->pluck('id')->toArray();
            $string_class_ids=$this->associativeArrayToSimple($class_ids);
            $top_students = DB::table('exam_allocations')
->select('id', 'total_mark', 'obtain_mark', 'final_percentage')
->selectRaw("FIND_IN_SET( final_percentage, ( SELECT GROUP_CONCAT( DISTINCT `final_percentage` ORDER BY `final_percentage` DESC ) FROM exam_allocations Where 
campus_id=".$campus_id." And  class_id IN".$string_class_ids." And  session_id=".$session_id." And  exam_create_id=".$exam_create_id."  )) as rank ")
->where('campus_id', $campus_id)
->whereIn('class_id', $class_ids)
->where('session_id', $session_id)
->where('exam_create_id', $exam_create_id)
->orderBy('final_percentage', 'desc')
->get();

            foreach ($top_students as $value) {
                if ($value->rank<=$limit && $value->rank>0) {
                    $data=[ 'rank'=>$value->rank,
      'data'=>ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
      ->findOrFail($value->id)
  ];
                        $students[]=$data;
                    
                }
            }
        }
        return $students;
    }
public function associativeArrayToSimple($data)
{
    $simple_array ='('; //simple array
    foreach ($data as $key=>$d) {
        if (array_key_last($data)==$key) {
            $simple_array.=$d.')';
        } else {
            $simple_array.=$d.',';
        }
    }

    return $simple_array;
}
}
