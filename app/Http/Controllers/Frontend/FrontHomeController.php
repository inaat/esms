<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNews;
use App\Models\Frontend\FrontAboutUs;
use App\Models\Frontend\FrontGalleryContent;
use App\Models\Frontend\FrontEvent;
use App\Models\Frontend\OnlineApplicant;
use Illuminate\Http\Request;
use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamAllocation;
use App\Models\ClassSection;
use App\Models\Campus;
use App\Models\ClassLevel;
use App\Models\Users;
use App\Models\Classes;
use App\Models\Session;
use App\Models\District;
use App\Utils\Util;
use App\Models\HumanRM\HrmEmployee;
use App\Models\HumanRM\HrmEducation;
use DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ContactFormMail;
use App\Notifications\SendMessageToEndUser;
use App\Mail\TestEmail;
use Illuminate\Http\JsonResponse;

class FrontHomeController extends Controller
{
    protected $studentUtil;
    

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->student_status_colors = [
            'active' => 'bg-success',
            'inactive' => 'bg-info',
            'struct_up' => 'bg-warning',
            'pass_out' => 'bg-danger',
             'took_slc' => 'bg-secondary',
        ];
    }
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


public function onlineApply(){

    $countries = $this->commonUtil->allCountries();
    $campuses=Campus::orderBy('campus_name', 'asc')
     ->pluck('campus_name', 'id');
    $sessions=Session::forDropdown(false, true);
    $districts = District::forDropdown(1, false);
    
  
    return view('frontend.online-apply')->with(compact('countries','campuses','sessions','districts'));
}
public function saveApply(Request $request){
        

    try {
        $validatedData = $request->validate([
            'campus_id' => 'required',
            'adm_session_id' => 'required',
            'adm_class_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'nullable',
            'gender' => 'required',
            'birth_date' => 'required',
            'domicile_id' => 'required',
            'religion' => 'required',
            'mobile_no' => 'required',
            'email' => 'required|email|unique:online_applicants,email',
            'cnic_no' => 'required',
            'nationality' => 'required',
            'father_name' => 'required',
            'father_phone' => 'required',
            'father_occupation' => 'required',
            'father_cnic_no' => 'required',
            'guardian_is' => 'required',
            'guardian_name' => 'required_if:guardian_is,Other',
            'guardian_relation' => 'required_if:guardian_is,Other',
            'guardian_occupation' => 'required_if:guardian_is,Other',
            'guardian_email' => 'required_if:guardian_is,Other|email',
            'guardian_phone' => 'required_if:guardian_is,Other',
            'guardian_address' => 'required_if:guardian_is,Other',
            'country_id' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'std_current_address' => 'required',
            'std_permanent_address' => 'required',
            'is_kmu_cat' => 'required',
            'previous_college_name' => 'required',
            'board_name' => 'required',
            'fsc_roll_no' => 'required',
            'fsc_marks' => 'required',
            'fsc_mark_sheet' => 'required|file|max:'.config('constants.document_size_limit'),
            'cnic_front_side' => 'required|file|max:'.config('constants.document_size_limit'),
            'cnic_back_side' => 'required|file|max:'.config('constants.document_size_limit')
        ]);
        $online_applicants_data = $request->only([
            'campus_id',
            'adm_session_id',
            'adm_class_id',
            'first_name',
            'last_name',
            'gender',
            'birth_date',
            'domicile_id',
            'religion',
            'mobile_no',
            'email',
            'cnic_no',
            'blood_group',
            'nationality',
            'mother_tongue',
            'medical_history',
            'father_name',
            'father_phone',
            'father_occupation',
            'father_cnic_no',
            'guardian_is',
            'guardian_name',
            'guardian_relation',
            'guardian_occupation',
            'guardian_email',
            'guardian_phone',
            'guardian_address',
            'country_id',
            'province_id',
            'district_id',
            'city_id',
            'std_current_address',
            'std_permanent_address',
            'is_kmu_cat',
            'previous_college_name',
            'board_name',
            'fsc_roll_no',
            'fsc_marks',
        ]);
        $student_image=$this->commonUtil->uploadFile($request, 'student_image', 'document');
        $fsc_mark_sheet=$this->commonUtil->uploadFile($request, 'fsc_mark_sheet', 'document');
        $cnic_front_side=$this->commonUtil->uploadFile($request, 'cnic_front_side', 'document');
        $cnic_back_side=$this->commonUtil->uploadFile($request, 'cnic_back_side', 'document');
        $online_applicants_data['student_image']=$student_image;
        $online_applicants_data['fsc_mark_sheet']=$fsc_mark_sheet;
        $online_applicants_data['cnic_front_side']=$cnic_front_side;
        $online_applicants_data['cnic_back_side']=$cnic_back_side;
        $online_applicants_data['applicant_submit_date']=\Carbon::now();
        $prefix_type = 'online_applicant_no';

        $ref_count = $this->commonUtil->setAndGetReferenceCount($prefix_type, false, true);
                //Generate reference number
        $online_applicants_data['online_applicant_no'] = $this->commonUtil->generateReferenceNumber($prefix_type, $ref_count, 1);

         
         OnlineApplicant::create($online_applicants_data);
         return view('frontend.submit-success');
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    }
 
}

public function emptyAdmissionForm()
{
    if (!auth()->user()->can('print.admission_form')) {
        abort(403, 'Unauthorized action.');
    }

   
    return view('MPDF.empty-admission-form');
    
   
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


public function submitForm(Request $request): JsonResponse
{
    try {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'mobile' => 'required',
            'message' => 'required',
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'mobile' => $request->input('mobile'),
            'message' => $request->input('message'),
        ];

        Mail::to('info@sirms.edu.pk')->send(new ContactFormMail($data));
        Mail::to($request->input('email'))->send(new SendMessageToEndUser($request->input('name')));

        return response()->json(['message' => 'Your message has been sent. Thank you!']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error sending the message. Please try again later.'], 500);
    }
}

}
