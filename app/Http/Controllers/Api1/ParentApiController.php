<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Grade;
use App\Http\Controllers\Api\Auth\IssueTokenTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Laravel\Passport\Client as OClient;
use App\Http\Controllers\Api\Resources\ChapterResource;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\ClassTimeTable;
use App\Http\Controllers\Api\Resources\SubjectResource;
use App\Http\Controllers\Api\Resources\TimetableResource;
use App\Http\Controllers\Api\Resources\LessonResource;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\ClassSubjectLesson;
use App\Models\ClassSection;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\File;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Exam\ExamAllocation;
use App\Models\Exam\ExamDateSheet;
use App\Models\Curriculum\SubjectTeacher;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\TimetableCollection;
use App\Models\StudentGuardian;
use App\Models\Student;

class ParentApiController extends Controller
{
    use IssueTokenTrait;

    private $client;

    public function __construct()
    {
        $this->client = OClient::where('password_client', 1)->first();

    }
    public function login(Request $request)
    {
            // $auth = Auth::user();
            // if ($request->fcm_id) {
            //     $auth->fcm_id = $request->fcm_id;
            //     $auth->save();
            // }

            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
    
            if ($validator->fails()) {
                $response = array(
                    'error' => true,
                    'message' => $validator->errors()->first(),
                    'code' => 102,
                );
                return response()->json($response);
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                //        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                //Here Email Field is referenced as a GR Number for Student
                $auth = Auth::user();
                if (!$auth->hasRole('Guardian#1')) {
                    $response = array(
                        'error' => true,
                        'message' => 'Invalid Login Credentials',
                        'code' => 101
                    );
                    return response()->json($response, 200);
                }
    
                $token = $this->issueToken($request, 'password')['access_token'];
                $refresh_token = $this->issueToken($request, 'password')['refresh_token'];
                $expires_in = $this->issueToken($request, 'password')['expires_in'];
     

           $user = \Auth::user();
           $children =StudentGuardian::with(['student_guardian','students','students.current_class', 'students.current_class.classLevel', 'students.current_class_section', 'students.studentCategory'])->where('guardian_id', $user->hook_id)->get();
            // $children = Students::where('father_id', $user->parent->id)->orWhere('mother_id', $user->parent->id)->orWhere('guardian_id', $user->parent->id)->with('class_section')->get();
            // $user = flattenMyModel($user);
            $children_data=[];
            foreach ($children as $child) {
                $class_section_name = $child->students->current_class->title . " " . $child->students->current_class_section->section_name;
            //Set Medium name
            $medium_name = $child->students->current_class->classLevel->title;
                $children_data[]=[
                    "id" => $child->students->id,
                    "first_name" => $child->students->first_name,
                    "last_name" => $child->last_name,
                    "gender" => $child->students->gender,
                    "email" => $child->students->email,
                    "mobile" => $child->students->mobile_no,
                    "image" => "https://e-school.wrteam.in/storage/students/zoCmIaKbQloD2tCAV35PeDhpTPJDUSYz2G2ueUoX.png",
                    "dob" => $child->students->birth_date,
                    "current_address" => $child->students->std_current_address,
                    "permanent_address" => $child->students->std_permanent_address,
                    "status" => 1,
                    "class_section_name" => $class_section_name,
                    "medium_name" => $medium_name,
                    "category_name" => "General",
                    "name" => "Student",
                    "user_id" => (int)$child->students->user_id,
                    "class_section_id" => $child->students->current_class_section_id,
                    "category_id" => 4,
                    "admission_no" => $child->students->admission_no,
                    "roll_number" => $child->students->roll_no,
                    "caste" => "caste",
                    "religion" => $child->students->religion,
                    "admission_date" => $child->students->admission_date,
                    "blood_group" => $child->students->blood_group,
                    "height" => "6.5",
                    "weight" => "80",
                ];
               
                
            }
            $user =
            array (
                "id" => $user->id,
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
              'gender' => 'Male',
              'email' => 'parent@gmail.com',
              'fcm_id' => 'dHRHN3jVRAuHWOu6C7D122:APA91bEgXX-UQhLGAcX6ZvzLjwvvXOHkHghEZ6HkHQ-CSAeOdkeubs9HA9Y5eHbc9K7ulzFlfwhl_5LOD68dTWIe01vJtHFcFExttbjZnKmVkvdTYqJDaCo7j1MvJXgit6jmDFQvP5Yy',
              'email_verified_at' => NULL,
              'mobile' => '1234567890',
              'image' => 'https://e-school.wrteam.in/storage/parents/jFrVXjtWsr11lPGwpuZhyohMc3qgsGLMztPgYHJR.png',
              'dob' => '1970-01-01',
              'current_address' => NULL,
              'permanent_address' => NULL,
              'status' => 1,
              'reset_request' => 0,
              'user_id' => 66,
              'occupation' => 'Job',
            );
            $data = array_merge($user, ['children' => $children_data]);
           /* dd($data);
              $children= 
              array (
                0 => 
                array (
                  'id' => 2,
                  'user_id' => 65,
                  'class_section_id' => 5,
                  'category_id' => 3,
                  'admission_no' => '2022-232',
                  'roll_number' => 123,
                  'caste' => 'Hindu',
                  'religion' => 'Hindu',
                  'admission_date' => '2022-06-13',
                  'blood_group' => 'A+',
                  'height' => '6.43',
                  'weight' => '57',
                  'is_new_admission' => 1,
                  'father_id' => 3,
                  'mother_id' => 2,
                  'guardian_id' => 0,
                  'first_name' => 'Punit',
                  'last_name' => 'Jangam',
                  'image' => 'https://e-school.wrteam.in/storage/students/tDJ9rwnQapGVVH8E636FZaIv1Wheu1Zut1OnJGR0.png',
                  'class_section_name' => '10 A',
                  'medium_name' => 'Gujrati',
                  'category_name' => 'OBC',
                ),
                1 => 
                array (
                  'id' => 3,
                  'user_id' => 68,
                  'class_section_id' => 5,
                  'category_id' => 4,
                  'admission_no' => '2022-233',
                  'roll_number' => 1,
                  'caste' => 'Hindu',
                  'religion' => 'Hindu',
                  'admission_date' => '2022-06-14',
                  'blood_group' => 'A+',
                  'height' => '5.5',
                  'weight' => '59',
                  'is_new_admission' => 1,
                  'father_id' => 3,
                  'mother_id' => 4,
                  'guardian_id' => 0,
                  'first_name' => 'Sagar',
                  'last_name' => 'Gor',
                  'image' => 'https://e-school.wrteam.in/storage/students/C9fg5gzsUsCAuoGcLz228ygpfQp4YJzqn1V4aa73.jpg',
                  'class_section_name' => '10 A',
                  'medium_name' => 'Gujrati',
                  'category_name' => 'General',
                ),
                2 => 
                array (
                  'id' => 4,
                  'user_id' => 69,
                  'class_section_id' => 2,
                  'category_id' => 4,
                  'admission_no' => '2022-234',
                  'roll_number' => 1,
                  'caste' => 'muslim',
                  'religion' => 'muslim',
                  'admission_date' => '2022-12-07',
                  'blood_group' => 'A+',
                  'height' => '5.7',
                  'weight' => '75',
                  'is_new_admission' => 1,
                  'father_id' => 3,
                  'mother_id' => 4,
                  'guardian_id' => 0,
                  'first_name' => 'shakir',
                  'last_name' => 'memon',
                  'image' => 'https://e-school.wrteam.in/storage/students/1670387623-dummy_student.png',
                  'class_section_name' => '9 B',
                  'medium_name' => 'Gujrati',
                  'category_name' => 'General',
                )
            );
         $data = array_merge($user, ['children' => $children]);
*/
         $response = array(
                'error' => false,
                'message' => 'User logged-in!',
                'token' => $token,
                'data' => $data,
                'code' => 100,
            );
            return response()->json($response, 200);
        } else {
            $response = array(
                'error' => true,
                'message' => 'Invalid Login Credentials',
                'code' => 101
            );
            return response()->json($response, 200);
        }
    }

    // public function getChildren(Request $request) {
    //     try {
    //         $user = $request->user();
    //         $children = $user->parent->children->load(['user:id,first_name,last_name']);
    //         $response = array(
    //             'error' => false,
    //             'message' => 'Children Fetched Successfully.',
    //             'data' => $children,
    //             'code' => 200,
    //         );
    //         return response()->json($response, 200);
    //     } catch (\Exception $e) {
    //         $response = array(
    //             'error' => true,
    //             'message' => trans('error_occurred'),
    //             'code' => 103,
    //         );
    //         return response()->json($response, 200);
    //     }
    // }

    public function subjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            $student = Student::find($request->child_id);
            $subjects = ClassSubject::where('class_id',$student->current_class->id)->get();
          //dd($subjects);
            $core_subjects=[];
            foreach ($subjects as $key => $subject) {
              
              $core_subjects[]=[
                'id' => 9,
                'class_id' => 1,
                'type' => 'Compulsory',
                'subject_id' => $subject->class_id,
                'elective_subject_group_id' => NULL,
                'subject' => 
               [
                  'id' =>$subject->id ,
                  'name' => $subject->name,
                  'code' => $subject->code,
                  'bg_color' =>  $subject->bg_color,
                  'image' =>  $subject->image,
                  'medium_id' =>  $subject->medium_id,
                  'type' =>  $subject->type,
               ]
              ];
            }
            $subjects=
                array (
                  'core_subject' => $core_subjects);
           

            $response = array(
                'error' => false,
                'message' => 'Student Subject Fetched Successfully.',
                'data' => $subjects,
                'code' => 200,
            );
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
    }

    public function classSubjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            $user = $request->user();
            $children = $user->parent->children()->where('id', $request->child_id)->first();
            $subjects = $children->classSubjects();
            $response = array(
                'error' => false,
                'message' => 'Class Subject Fetched Successfully.',
                'data' => $subjects,
                'code' => 103
            );
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103
            );
            return response()->json($response, 200);
        }
    }

    public function getTimetable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            $student = Student::find($request->child_id);         
           $timetable=ClassTimeTable::with(['subjects','teacher','periods'])
           ->where('class_id', $student->current_class_id)
           ->where('class_section_id', $student->current_class_section_id)
           ->orderBy('period_id')->get();
           $data=TimetableResource::collection($timetable);
            $response = array(
                'error' => false,
                'message' => "Timetable Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    /**
     * @param
     * subject_id : 2
     * lesson_id : 1 //OPTIONAL
     */
    public function getLessons(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
            'chapter_id' => 'nullable|numeric',
            'subject_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }

        try {
            $data = SubjectChapter::where('subject_id', $request->subject_id)->with('topic', 'file');
            
            if ($request->chapter_id) {
                $data->where('id', $request->chapter_id);
            }
            $data = $data->get();

            $response = array(
                'error' => false,
                'message' => "Lessons Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    /**
     * @param
     * lesson_id : 1
     * topic_id : 1    //OPTIONAL
     */
    public function getLessonTopics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
            'chapter_id' => 'required|numeric',
            'topic_id' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }

        try {
            $data = ClassSubjectLesson::where('chapter_id', $request->chapter_id)->with('file');
        if ($request->topic_id) {
            $data->where('id', $request->topic_id);
        }
        $data = $data->get();
            $response = array(
                'error' => false,
                'message' => "Topics Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    /**
     * @param
     * assignment_id : 1    //OPTIONAL
     * subject_id : 1       //OPTIONAL
     */
    public function getAssignments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
            'assignment_id' => 'nullable|numeric',
            'subject_id' => 'nullable|numeric',
            'is_submitted' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }

        try {
            $student = Student::find($request->child_id);
         
          $data = Assignment::where('class_section_id', $student->current_class_section_id)->with('file', 'subject', 'submission.file');
       
          if ($request->assignment_id) {
              $data->where('id', $request->assignment_id);
          }
          if ($request->subject_id) {
              $data->where('subject_id', $request->subject_id);
          }
        
          if ($request->is_submitted) {
              if ($request->is_submitted == 1) {
                  $data->has('submission')->get();
              } else if ($request->is_submitted == 0) {
                  $data->has('submission', '<', 1)->get();
              }
          }
          $data = $data->orderBy('id', 'desc')->paginate();

            $response = array(
                'error' => false,
                'message' => "Assignments Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    /**
     * @param
     * month : 4 //OPTIONAL
     * year : 2022 //OPTIONAL
     */
    public function getAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
            'month' => 'nullable|numeric',
            'year' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            // $user = $request->user();
            // $children = $user->parent->children()->where('id', $request->child_id)->first();
            // $session_year = getSettings('session_year');
            // $session_year_id = $session_year['session_year'];

            // $attendance = Attendance::where('student_id', $children->id)->where('session_year_id', $session_year_id);
            // $holidays = new Holiday;
            // $session_year_data = SessionYear::find($session_year_id);
            // if (isset($request->month)) {
            //     $attendance = $attendance->whereMonth('date', $request->month);
            //     $holidays = $holidays->whereMonth('date', $request->month);
            // }

            // if (isset($request->year)) {
            //     $attendance = $attendance->whereYear('date', $request->year);
            //     $holidays = $holidays->whereYear('date', $request->year);
            // }
            // $attendance = $attendance->get();
            // $holidays = $holidays->get();
            $attendance=[
                [
                  "id"=> 12,
                  "class_section_id"=> 1,
                  "student_id"=> 1,
                  "session_id"=> 1,
                  "type"=> 0,
                  "date"=> "2023-05-06"
                ]
                ];
                    $session_year_data = [
                        "id" => 1,
                        "name" => "2022-23",
                        "default" => 1,
                        "start_date" => "2022-06-01",
                        "end_date" => "2024-04-30",
                        "created_at" => "2022-06-13T11:05:05.000000Z",
                        "updated_at" => "2022-12-06T08:31:48.000000Z",
                        "deleted_at" => null,
                    ];
                
           
              $response = array(
                  'error' => false,
                  'message' => "Attendance Details Fetched Successfully",
                  'data' => ['attendance' => $attendance,'session_year' => $session_year_data],
                  'code' => 200,
              );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function getAnnouncements(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|in:subject,noticeboard,class',
           'child_id' => 'required_if:type,subject,class|numeric',
            'subject_id' => 'required_if:type,subject|numeric'
        ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        //try {
            $children = null;
            if ($request->type !== "noticeboard") {
                $children = Student::find($request->child_id);
               
            
            }
            $session_id =getActiveSession();
            $table = null;
            if (isset($request->type) && $request->type == "subject") {
                $table = SubjectTeacher::where('class_section_id', $children->current_class_section_id)->where('subject_id', $request->subject_id)->get()->pluck('id');
                if (empty($table)) {
                    $response = array(
                        'error' => true,
                        'message' => "Invalid Subject ID",
                        'code' => 106,
                    );
                    return response()->json($response);
                }
            }
 
            $data = Announcement::with('file')->where('session_id', $session_id);
 
            if (isset($request->type) && $request->type == "noticeboard") {
                $data = $data->where('table_type', "");
            }
 
         //    if (isset($request->type) && $request->type == "class") {
         //        $data = $data->where('table_type', "App\Models\Classes")->where('table_id', $class_id);
         //    }
 
            if (isset($request->type) && $request->type == "subject") {
                $data = $data->where('table_type', "App\Models\Curriculum\SubjectTeacher")->whereIn('table_id', $table);
            }
 
            $data = $data->orderBy('id', 'desc')->paginate();
            $response = array(
                'error' => false,
                'message' => "Announcement Details Fetched Successfully",
                'data' => $data,
                'code' => 200,
            );
        // } catch (\Exception $e) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('error_occurred'),
        //         'code' => 103,
        //     );
        // }
        return response()->json($response);
    }

    public function getTeachers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            // $user = $request->user();
            $children = Student::find($request->child_id);
            $subjects=  SubjectTeacher::with(['teacher','class_subject'])->where('class_section_id', $children->current_class_section_id)->get();
            $subject_teachers=[];
            foreach($subjects as $subject){
                $image = file_exists(public_path('uploads/employee_image/'.$subject->teacher->employee_image)) ? url('uploads/employee_image/'.$subject->teacher->employee_image) : url('uploads/employee_image/default.jpg');
                $subject_teachers[]=[
                    'id' => 1,
                    'class_section_id' => $subject->class_section_id,
                    'subject_id' => $subject->subject_id,
                    'teacher_id' => $subject->teacher_id,
                    'subject' => [
                        'id' => $subject->class_subject->id,
                        'name' => $subject->class_subject->name,
                    ],
                    'teacher' => [
                        'id' =>  $subject->teacher->id,
                        'user_id' => 57,
                        'qualification' => 'B.sc (Maths)',
                        'user' => [
                            'id' => 57,
                            'first_name' => $subject->teacher->first_name,
                            'last_name' => $subject->teacher->last_name,
                            'gender' => $subject->teacher->gender,
                            'email' => $subject->teacher->email,
                            'fcm_id' => NULL,
                            'email_verified_at' => NULL,
                            'mobile' => $subject->teacher->mobile_no,
                            'image' => $image,
                            'dob' => $subject->teacher->birth_date,
                            'current_address' => $subject->teacher->current_address,
                            'permanent_address' => $subject->teacher-> permanent_address,
                        
                        ]
                        
                    ]
                        ];

            }
                 
                
                
              
            $response = array(
                'error' => false,
                'message' => "Teacher Details Fetched Successfully",
                'data' => $subject_teachers,
                'code' => 200,
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }
    public function getExamList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|nullable',
            'status' => 'nullable:digits:0,1,2,3'
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            $exam_data_db=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
            ->where('student_id', $request->child_id)  ->get();
    
               foreach ($exam_data_db as $data) {
                   // date status
                   $starting_date = $data->exam_create->from_date;
                   $ending_date = $data->exam_create->to_date;
                   $currentTime = Carbon::now();
                   $current_date = date($currentTime->toDateString());
                   if ($current_date >= $starting_date && $current_date <= $ending_date) {
                       $exam_status = "1"; // Upcoming = 0 , On Going = 1 , Completed = 2
                   } elseif ($current_date < $starting_date) {
                       $exam_status = "0"; // Upcoming = 0 , On Going = 1 , Completed = 2
                   } else {
                       $exam_status = "2"; // Upcoming = 0 , On Going = 1 , Completed = 2
                   }
                  // dd($exam_status);
                   if (isset($request->status)) {
                       if ($request->status == 0) {
                           $exam_data[] = array(
                               'id' => $data->exam_create->id,
                               'name' => $data->exam_create->term->name,
                               'description' => $data->exam_create->term->name,
                               'publish' => 0,
                               'session_year' => $data->session->title,
                               'exam_starting_date' => $starting_date,
                               'exam_ending_date' => $ending_date,
                               'exam_status' => $exam_status,
                           );
                       } else if ($request->status == 1) {
                           if ($exam_status == 0) {
                               $exam_data[] = array(
                                'id' => $data->exam_create->id,
                                'name' => $data->exam_create->term->name,
                                'description' => $data->exam_create->term->name,
                                'publish' => 0,
                                'session_year' => $data->session->title,
                                'exam_starting_date' => $starting_date,
                                'exam_ending_date' => $ending_date,
                                'exam_status' => $exam_status,
                               );
                           }
                       } else if ($request->status == 2) {
                           if ($exam_status == 1) {
                               $exam_data[] = array(
                                'id' => $data->exam_create->id,
                                'name' => $data->exam_create->term->name,
                                'description' => $data->exam_create->term->name,
                                'publish' => 0,
                                'session_year' => $data->session->title,
                                'exam_starting_date' => $starting_date,
                                'exam_ending_date' => $ending_date,
                                'exam_status' => $exam_status,
                               );
                           }
                       }else{
                           if ($exam_status == 2) {
                               $exam_data[] = array(
                                'id' => $data->exam_create->id,
                                'name' => $data->exam_create->term->name,
                                'description' => $data->exam_create->term->name,
                                'publish' => 0,
                                'session_year' => $data->session->title,
                                'exam_starting_date' => $starting_date,
                                'exam_ending_date' => $ending_date,
                                'exam_status' => $exam_status,
                               );
                           }
                       }
                   } else {
                       $exam_data[] = array(
                        'id' => $data->exam_create->id,
                        'name' => $data->exam_create->term->name,
                        'description' => $data->exam_create->term->name,
                        'publish' => 0,
                        'session_year' => $data->session->title,
                        'exam_starting_date' => $starting_date,
                        'exam_ending_date' => $ending_date,
                        'exam_status' => $exam_status,
                       );
                   }
               }
    
    
               $response = array(
                   'error' => false,
                  'data' => isset($exam_data) ? $exam_data : [],
                   'code' => 200,
               );
    
         
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function getExamDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|nullable',
            'child_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
           
            $student = Student::find($request->child_id);

           $student_class_section_id = $student->current_class_id;
           $exam_data_db = ExamDateSheet::with(['subject','subject.classes'])->where('class_id', $student_class_section_id)
           ->where('exam_create_id',  $request->exam_id)->orderBy('date')->orderby('date')->get();
       // dd($exam_data_db);
        if(!$exam_data_db){
               $response = array(
                   'error' => false,
                   'data' => [],
                   'code' => 200,
               );
               return response()->json($response);
           }


           foreach ($exam_data_db as $data) {
               $exam_data[] = array(
                   'id' => $data->id,
                   'total_marks' => $data->subject->total,
                   'passing_marks' => $data->subject->passing_percentage,
                   'date' => $data->date,
                   'starting_time' => $data->start_time,
                   'ending_time' => $data->end_time,
                   'subject' => array(
                       'id' => $data->subject->id,
                       'name' => $data->subject->name .' ('.$data->type.')',
                       'bg_color' => $data->subject->bg_color,
                       'image' => $data->subject->image,
                       'type' => $data->subject->type,
                   )
               );
           }
           $response = array(
               'error' => false,
              'data' => isset($exam_data) ? $exam_data : [],
               'code' => 200,
           );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function getExamMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|nullable',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        try {
            $exam_result_db=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
             ->where('student_id', $request->child_id) ->get();



           if (sizeof($exam_result_db)) {
               foreach ($exam_result_db as $exam_result_data) {
                  $starting_date = $exam_result_data->exam_create->from_date;
                   $exam_result = array(
                       'result_id' => $exam_result_data->id,
                       'exam_id' => $exam_result_data->exam_create->id,
                       'exam_name' => $exam_result_data->exam_create->term->name,
                       'class_name' => $exam_result_data->current_class->title . " " . $exam_result_data->current_class_section->section_name,
                       'student_name' => $exam_result_data->student->first_name . ' ' . $exam_result_data->student->last_name,
                       'exam_date' => $starting_date,
                       'total_marks' => $exam_result_data->total_mark,
                       'obtained_marks' => $exam_result_data->obtain_mark,
                       'percentage' => $exam_result_data->final_percentage,
                       'grade' =>  $exam_result_data->grade ? ucwords($exam_result_data->grade->name):'',
                       'session_year' => $exam_result_data->session->title,
                   );
                   $exam_marks = array();
                   foreach ($exam_result_data->subject_result as $marks) {
                       $exam_marks[] = array(
                           'marks_id' => $marks->id,
                           'subject_name' => $marks->subject_name->name,
                           'subject_type' => $marks->subject_name->type,
                           'total_marks' => $marks->total_mark,
                           'obtained_marks' => $marks->total_obtain_mark,
                           'teacher_review' => $marks->subject_grade ? ucwords($marks->subject_grade->remark):'',
                           'grade' => $marks->subject_grade ? ucwords($marks->subject_grade->name):'',
                       );
                   }
          
               }
               $data[] = array(
                'result' => $exam_result,
                'exam_marks' => $exam_marks,
            );
               $response = array(
                   'error' => false,
                   'message' => "Exam Result Fetched Successfully",
                   'data' => $data,
                   'code' => 200,
               );
           }else{
               $response = array(
                   'error' => false,
                   'message' => "Exam Result Fetched Successfully",
                   'data' => [],
                   'code' => 200,
               );
           }
 
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }
}
