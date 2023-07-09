<?php

namespace App\Http\Controllers\Api;

use App\Models\Exam\ExamCreate;
use App\Models\Exam\ExamGrade;
use App\Models\Exam\ExamSubjectResult;
use Exception;
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
use Carbon\Carbon;
use App\Models\Curriculum\ClassSubject;
use App\Models\Curriculum\ClassTimeTable;
use App\Http\Controllers\Api\Resources\SubjectResource;
use App\Http\Controllers\Api\Resources\TimetableResource;
use App\Http\Controllers\Api\Resources\LessonResource;
use App\Models\Curriculum\SubjectChapter;
use App\Models\Curriculum\SubjectQuestionBank;
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
use App\Models\Student;
use App\Utils\NotificationUtil;

class TeacherApiController extends Controller
{
    use IssueTokenTrait;

    private $client;

    public function __construct(NotificationUtil $notificationUtil)
    {
        $this->client = OClient::where('password_client', 1)->first();
        $this->notificationUtil= $notificationUtil;

    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            // if ($request->fcm_id) {
            //     $auth->fcm_id = $request->fcm_id;
            //     $auth->save();
            // }

            $token = $this->issueToken($request, 'password')['access_token'];
            $refresh_token = $this->issueToken($request, 'password')['refresh_token'];
            $expires_in = $this->issueToken($request, 'password')['expires_in'];
             $user = $auth->load(['employee']);
             $image = file_exists(public_path('uploads/employee_image/'.$user->employee->employee_image)) ? url('uploads/employee_image/'.$user->employee->employee_image) : url('uploads/employee_image/default.jpg');

             //dd($user->employee);
             $user= [
                "id"=>$user->id,
                "first_name"=>$user->first_name,
                "last_name"=>$user->last_name,
                "gender"=>$user->employee->gender,
                "email"=>$user->email,
                "fcm_id"=>"faErXC5qTTOstGL6n30ijx:APA91bHF64phU0sYY8vgDrmi2hpCAvLaTVItnm6BvXohihAWgJjWOZlX2UUdDyFxumVkzzgdsHSxP1n1JuHrMkzP2Q9eX_o_Ktz2pI1wHV54eEWP2zpY8NAuRYTX0RfZnwUT5EPRIZeg",
                "email_verified_at"=>null,
                "mobile"=>$user->employee->mobile_no,
                "image"=>$image,
                "dob"=>$user->employee->birth_date,
                "current_address"=>$user->employee->current_address?$user->employee->current_address:'',
                "permanent_address"=>$user->employee->permanent_address?$user->employee->permanent_address:'',
                "status"=>1,
                "reset_request"=>0,
                "teacher"=>[                  
                  "id"=>(int)$user->employee->id,
                  "user_id"=>(int)$user->employee->user_id,
                  "qualification"=>"Developer"
                ]
             ];
            $response = array(
                'error' => false,
                'message' => 'User logged-in!',
                'token' => $token,
                'data' => $user,
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

    public function classes(Request $request)
    {
        try {
            
           $not_section_include=null; 
           $class_teacher=[];  
           $user = $request->user()->employee;
           $class_section=ClassSection::with(['classes'])->where('teacher_id',$user->id)->first();
           $class_master=SubjectTeacher::with(['class_subject','classes','classes.classLevel','class_section'])->where('teacher_id',$user->id)->first();

           if(!empty($class_section)){
            $not_section_include=$class_section->id;
            $class_teacher= [
                "id"=> (int)$class_section->id,
                "class_id"=> (int)$class_section->class_id,
                "section_id"=> (int)$class_section->id,
                "class_teacher_id"=> (int)$class_section->teacher_id,
    
                "class"=> [
                  "id"=> (int)$class_section->classes->id,
                  "name"=> $class_section->classes->title,
                  "medium_id"=> 1,
                  "medium"=> [
                    "name"=> "Gujrati",
                    "id"=> 1
                  ]
                  ],
                "section"=> [
                  "id"=> (int)$class_section->id,
                  "name"=> $class_section->section_name
                ]
                ];
           }else{
            $not_section_include=$class_section->class_section_id;
            $class_teacher= [
                "id"=>(int) $class_section->class_section_id,
                "class_id"=> (int)$class_master->class_id,
                "section_id"=> (int)$class_master->class_section->id,
                "class_teacher_id"=> (int)$class_master->teacher_id,
    
                "class"=> [
                  "id"=> (int)$class_master->classes->id,
                  "name"=> $class_master->classes->title,
                  "medium_id"=> 1,
                  "medium"=> [
                    "name"=> "Gujrati",
                    "id"=> 1
                  ]
                  ],
                "section"=> [
                  "id"=> (int)$class_master->class_section->id,
                  "name"=> $class_master->class_section->section_name
                ]
                ];
           }
           

           $class_section=SubjectTeacher::with(['class_subject','classes','classes.classLevel','class_section'])->where('class_section_id','!=',$not_section_include)->where('teacher_id',$user->id)->get()->unique('class_section_id');
          // /dd($class_master);
           //$class_teacher=[];
           
                  $class_sections= [];
                  //dd($class_section);
                  foreach ($class_section as $key => $value) {
                    $class_sections[]=[ 
                    "id"=> (int)$value->class_section_id,
                    "class_id"=> (int)$value->class_id,
                    "section_id"=> (int)$value->class_section->id,
                    "class_teacher_id"=> (int)$value->teacher_id,
  
                    "class"=> [
                      "id"=> (int)$value->classes->id,
                      "name"=> $value->classes->title,
                      "medium_id"=> 1,
                      "medium"=> [
                        "name"=> "Gujrati",
                        "id"=> 1
                      ]
                      ],
                    "section"=> [
                      "id"=> (int)$value->class_section->id,
                      "name"=> $value->class_section->section_name
                    ]
                    ];
                  }
                  
                  
              
            $response = array(
                'error' => false,
                'message' => 'Teacher Classes Fetched Successfully.',
                'data' => ['class_teacher' => $class_teacher, 'other' => $class_sections],
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

    public function subjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_section_id' => 'nullable|numeric',
            'subject_id' => 'nullable|numeric',
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
           
            $user = $request->user()->employee;
            $assign=SubjectTeacher::with(['class_subject','classes','classes.classLevel','class_section'])->where('teacher_id',$user->id);
            if ($request->class_section_id) {
                $assign = $assign->where('class_section_id', $request->class_section_id);
            }
             if ($request->subject_id) {
                $$assign = $$assign->where('subject_id', $request->subject_id);
            }
            $assign=$assign->get();
            $subjects= [];
            foreach ($assign as $key => $value) {
                $image = file_exists(public_path('uploads/subjects/'.$value->class_subject->subject_icon)) ? url('uploads/subjects/'.$value->class_subject->subject_icon) : url('uploads/subjects/default.svg');

                $subjects[]= [
                    //   "id"=> 25,
                    //   "class_section_id"=> 1,
                    //   "subject_id"=> 1,
                    //   "teacher_id"=> 9,
                      "id"=> (int)$value->class_section_id,
                      "class_section_id"=> (int)$value->class_section->id,
                      "subject_id"=>(int)$value->subject_id,
                      "teacher_id"=>(int) $value->teacher_id,
                      "subject"=> [
                        'id' =>(int)$value->class_subject->id ,
                        'name' => $value->class_subject->name,
                        'code' => $value->class_subject->code,
                        'bg_color' =>  $value->class_subject->bg_color,
                        'image' =>  $image,
                        'medium_id' =>  $value->class_subject->medium_id,
                        'type' =>  $value->class_subject->type,
                      ],
                      "class_section"=> [
                        "id"=> (int)$value->class_section_id,
                        "class_id"=> (int)$value->class_id,
                        "section_id"=> (int)$value->class_section->id,
                        "class_teacher_id"=>(int) $value->teacher_id,
      
                        "class"=> [
                          "id"=> (int)$value->classes->id,
                          "name"=> $value->classes->title,
                          "medium_id"=> 1,
                          "medium"=> [
                            "name"=> "Gujrati",
                            "id"=> 1
                          ]
                          ],
                        "section"=> [
                          "id"=> (int)$value->class_section->id,
                          "name"=> $value->class_section->section_name
                        ]
                      ]
                    
                ];
            }
            //dd($class_section);
           
          
            $response = array(
                'error' => false,
                'message' => 'Teacher Subject Fetched Successfully.',
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


    public function getAssignment(Request $request)
    {
        // if (!Auth::user()->can('assignment-list')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'class_section_id' => 'nullable|numeric',
            'subject_id' => 'nullable|numeric',
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
            $sql = Assignment::assignmentteachers()->with('class_section', 'file', 'subject');
            if ($request->class_section_id) {
                $sql = $sql->where('class_section_id', $request->class_section_id);
            }

            if ($request->subject_id) {
                $sql = $sql->where('subject_id', $request->subject_id);
            }
            $data = $sql->orderBy('id', 'DESC')->paginate();
            $data = $sql->orderBy('id', 'DESC')->paginate();
            $response = array(
                'error' => false,
                'message' => 'Assignment Fetched Successfully.',
                'data' => $data,
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

    public function createAssignment(Request $request)
    {
        if (!Auth::user()->can('assignment-create')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message'),
                'code' => 111
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            "class_section_id" => 'required|numeric',
            "subject_id" => 'required|numeric',
            "name" => 'required',
            "instructions" => 'nullable',
            "due_date" => 'required|date',
            "points" => 'nullable',
            "resubmission" => 'nullable|boolean',
            "extra_days_for_resubmission" => 'nullable|numeric',
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

            $session_year = getSettings('session_year');
            $session_year_id = $session_year['session_year'];

            $assignment = new Assignment();
            $assignment->class_section_id = $request->class_section_id;
            $assignment->subject_id = $request->subject_id;
            $assignment->name = $request->name;
            $assignment->instructions = $request->instructions;
            $assignment->due_date = Carbon::parse($request->due_date)->format('Y-m-d H:i:s');
            $assignment->points = $request->points;
            if ($request->resubmission) {
                $assignment->resubmission = 1;
                $assignment->extra_days_for_resubmission = $request->extra_days_for_resubmission;
            } else {
                $assignment->resubmission = 0;
                $assignment->extra_days_for_resubmission = null;
            }
            $assignment->session_year_id = $session_year_id;

            $subject_name = Subject::select('name')->where('id', $request->subject_id)->pluck('name')->first();
            $title = 'New assignment added in ' . $subject_name;
            $body = $request->name;
            $type = "assignment";
            $user = Students::select('user_id')->where('class_section_id', $request->class_section_id)->get()->pluck('user_id');
            $assignment->save();
            send_notification($user, $title, $body, $type);

            if ($request->hasFile('file')) {
                foreach ($request->file as $file_upload) {
                    $file = new File();
                    $file->file_name = $file_upload->getClientOriginalName();
                    $file->type = 1;
                    $file->file_url = $file_upload->store('assignment', 'public');
                    $file->modal()->associate($assignment);
                    $file->save();
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'code' => 200,
            );
        } catch (\Exception $e ) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function updateAssignment(Request $request)
    {
        if (!Auth::user()->can('assignment-edit')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message'),
                'code' => 111
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            "assignment_id" => 'required|numeric',
            "class_section_id" => 'required|numeric',
            "subject_id" => 'required|numeric',
            "name" => 'required',
            "instructions" => 'nullable',
            "due_date" => 'required|date',
            "points" => 'nullable',
            "resubmission" => 'nullable|boolean',
            "extra_days_for_resubmission" => 'nullable|numeric',
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
            $session_year = getSettings('session_year');
            $session_year_id = $session_year['session_year'];

            $assignment = Assignment::find($request->assignment_id);
            $assignment->class_section_id = $request->class_section_id;
            $assignment->subject_id = $request->subject_id;
            $assignment->name = $request->name;
            $assignment->instructions = $request->instructions;
            $assignment->due_date = Carbon::parse($request->due_date)->format('Y-m-d H:i:s');;
            $assignment->points = $request->points;
            if ($request->resubmission) {
                $assignment->resubmission = 1;
                $assignment->extra_days_for_resubmission = $request->extra_days_for_resubmission;
            } else {
                $assignment->resubmission = 0;
                $assignment->extra_days_for_resubmission = null;
            }

            $assignment->session_year_id = $session_year_id;
            $subject_name = Subject::select('name')->where('id', $request->subject_id)->pluck('name')->first();
            $title = 'Update assignment in ' . $subject_name;
            $body = $request->name;
            $type = "assignment";
            $user = Student::select('user_id')->where('class_section_id', $request->class_section_id)->where('status','active')->get()->pluck('user_id');
            $assignment->save();
            send_notification($user, $title, $body, $type);

            if ($request->hasFile('file')) {
                foreach ($request->file as $file_upload) {
                    $file = new File();
                    $file->file_name = $file_upload->getClientOriginalName();
                    $file->type = 1;
                    $file->file_url = $file_upload->store('assignment', 'public');
                    $file->modal()->associate($assignment);
                    $file->save();
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'code' => 200,
            );
        } catch (\Exception $e ) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function deleteAssignment(Request $request)
    {
        if (!Auth::user()->can('assignment-delete')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message'),
                'code' => 111
            );
            return response()->json($response);
        }
        try {
            $assignment = Assignment::find($request->assignment_id);
            $assignment->delete();
            $response = array(
                'error' => false,
                'message' => trans('data_delete_successfully'),
                'code' => 200
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

    public function getAssignmentSubmission(Request $request)
    {
        if (!Auth::user()->can('assignment-submission')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message'),
                'code' => 111
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            'assignment_id' => 'required|nullable|numeric'
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
            $sql = AssignmentSubmission::assignmentsubmissionteachers()->with('assignment.subject:id,name', 'student:id,user_id', 'student.user:first_name,last_name,id,image', 'file');
            $data = $sql->where('assignment_id', $request->assignment_id)->get();
            $response = array(
                'error' => false,
                'message' => 'Assignment Fetched Successfully.',
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
        return response()->json($response, 200);
    }

    public function updateAssignmentSubmission(Request $request)
    {
        if (!Auth::user()->can('assignment-submission')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message'),
                'code' => 111
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            'assignment_submission_id' => 'required|numeric',
            'status' => 'required|numeric|in:1,2',
            'points' => 'nullable|numeric',
            'feedback' => 'nullable',
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
            $assignment_submission = AssignmentSubmission::findOrFail($request->assignment_submission_id);
            $assignment_submission->feedback = $request->feedback;
            if ($request->status == 1) {
                $assignment_submission->points = $request->points;
            } else {
                $assignment_submission->points = null;
            }

            $assignment_submission->status = $request->status;
            $assignment_submission->save();

            $assignment_data = Assignment::where('id', $assignment_submission->assignment_id)->with('subject')->first();
            $user = Students::select('user_id')->where('id', $assignment_submission->student_id)->get()->pluck('user_id');
            $title = '';
            $body = '';
            if ($request->status == 2) {
                $title = "Assignment rejected";
                $body = $assignment_data->name . " rejected in " . $assignment_data->subject->name . " subject";
            }
            if ($request->status == 1) {
                $title = "Assignment accepted";
                $body = $assignment_data->name . " accepted in " . $assignment_data->subject->name . " subject";
            }
            $type = "assignment";
            send_notification($user, $title, $body, $type);
            $response = array(
                'error' => false,
                'message' => trans('data_update_successfully'),
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

    public function getLesson(Request $request)
    {
        // if (!Auth::user()->can('lesson-list')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message')
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'nullable|numeric',
            'class_section_id' => 'nullable|numeric',
            'subject_id' => 'nullable|numeric',
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
            $sql = SubjectChapter::lessonteachers()->with('file')->withCount('topic');
          
            if ($request->lesson_id) {
                $sql = $sql->where('id', $request->lesson_id);
            }

            if ($request->subject_id) {
                $sql = $sql->where('subject_id', $request->subject_id);
            }
            $data = $sql->orderBy('id', 'DESC')->get();
            $response = array(
                'error' => false,
                'message' => 'Lesson Fetched Successfully.',
                'data' => $data,
                'code' => 200,
            );
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
    }

    public function createLesson(Request $request)
    {
        // if (!Auth::user()->can('lesson-create')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'class_section_id' => 'required|numeric',
                'subject_id' => 'required|numeric',

                'file' => 'nullable|array',
                'file.*.type' => 'nullable|in:1,2,3,4',
                'file.*.name' => 'required_with:file.*.type',
                'file.*.thumbnail' => 'required_if:file.*.type,2,3,4',
                'file.*.file' => 'required_if:file.*.type,1,3',
                'file.*.link' => 'required_if:file.*.type,2,4',
                //            'file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                //            'file.*.name' => 'required_with:file.*.type',
                //            'file.*.thumbnail' => 'required_if:file.*.type,youtube_link,video_upload,other_link',
                //            'file.*.file' => 'required_if:file.*.type,file_upload,video_upload',
                //            'file.*.link' => 'required_if:file.*.type,youtube_link,other_link',
                //Regex for Youtube Link
                // 'file.*.link'=>['required_if:file.*.type,youtube_link','regex:/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/'],
                //Regex for Other Link
                // 'file.*.link'=>'required_if:file.*.type,other_link|url'
            ]
        );

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
        // $validator2 = Validator::make(
        //     $request->all(),
        //     [
        //         'name' => ['required', new uniqueLessonInClass($request->class_section_id)]
        //     ]
        // );
        // if ($validator2->fails()) {
        //     $response = array(
        //         'error' => true,
        //         'message' => $validator2->errors()->first(),
        //         'code' => 113,
        //     );
        //     return response()->json($response);
        // }
        try {
            $lesson = new SubjectChapter();
            $lesson->chapter_name = $request->name;
            $lesson->description = $request->description;
            $lesson->subject_id = $request->subject_id;
            $lesson->save();

            if ($request->file) {
                foreach ($request->file as $key => $file) {
                    if ($file['type']) {
                        $lesson_file = new File();
                        $lesson_file->file_name = $file['name'];
                        $lesson_file->modal()->associate($lesson);

                        if ($file['type'] == "1") {
                            $lesson_file->type = 1;
                            $lesson_file->file_url = $file['file']->store('lessons', 'public');
                        } elseif ($file['type'] == "2") {
                            $lesson_file->type = 2;
                            $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $lesson_file->file_url = $file['link'];
                        } elseif ($file['type'] == "3") {
                            $lesson_file->type = 3;
                            $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $lesson_file->file_url = $file['file']->store('lessons', 'public');
                        } elseif ($file['type'] == "4") {
                            $lesson_file->type = 4;
                            $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $lesson_file->file_url = $file['link'];
                        }
                        $lesson_file->save();
                    }
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'code' => 200,
            );
        } catch (\Exception $e ) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function updateLesson(Request $request)
    {
        // if (!Auth::user()->can('lesson-edit')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'lesson_id' => 'required|numeric',
                'name' => 'required',
                'description' => 'required',
                'class_section_id' => 'required|numeric',
                'subject_id' => 'required|numeric',

                'edit_file' => 'nullable|array',
                'edit_file.*.id' => 'required|numeric',
                'edit_file.*.type' => 'nullable|in:1,2,3,4',
                'edit_file.*.name' => 'required_with:edit_file.*.type',
                'edit_file.*.link' => 'required_if:edit_file.*.type,2,4',

                'file' => 'nullable|array',
                'file.*.type' => 'nullable|in:1,2,3,4',
                'file.*.name' => 'required_with:file.*.type',
                'file.*.thumbnail' => 'required_if:file.*.type,2,3,4',
                'file.*.file' => 'required_if:file.*.type,1,3',
                'file.*.link' => 'required_if:file.*.type,2,4',

                //            'edit_file' => 'nullable|array',
                //            'edit_file.*.id' => 'required|numeric',
                //            'edit_file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                //            'edit_file.*.name' => 'required_with:edit_file.*.type',
                //            'edit_file.*.link' => 'required_if:edit_file.*.type,youtube_link,other_link',
                //
                //            'file' => 'nullable|array',
                //            'file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                //            'file.*.name' => 'required_with:file.*.type',
                //            'file.*.thumbnail' => 'required_if:file.*.type,youtube_link,video_upload,other_link',
                //            'file.*.file' => 'required_if:file.*.type,file_upload,video_upload',
                //            'file.*.link' => 'required_if:file.*.type,youtube_link,other_link',

                //Regex for Youtube Link
                // 'file.*.link'=>['required_if:file.*.type,youtube_link','regex:/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/'],
                //Regex for Other Link
                // 'file.*.link'=>'required_if:file.*.type,other_link|url'
            ]
        );
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }

        // $validator2 = Validator::make(
        //     $request->all(),
        //     [
        //         'name' => ['required', new uniqueLessonInClass($request->class_section_id, $request->lesson_id)]
        //     ]
        // );
        // if ($validator2->fails()) {
        //     $response = array(
        //         'error' => true,
        //         'message' => $validator2->errors()->first(),
        //         'code' => 113,
        //     );
        //     return response()->json($response);
        // }
       try {
            $lesson = SubjectChapter::find($request->lesson_id);
            $lesson->chapter_name = $request->name;
            $lesson->description = $request->description;
            //$lesson->class_section_id = $request->class_section_id;
            $lesson->subject_id = $request->subject_id;
            $lesson->save();

            // Update the Old Files
            if ($request->edit_file) {
                foreach ($request->edit_file as $file) {
                    if ($file['type']) {
                        $lesson_file = File::find($file['id']);
                        if ($lesson_file) {
                            $lesson_file->file_name = $file['name'];

                            if ($file['type'] == "1") {
                                $lesson_file->type = 1;
                                if (!empty($file['file'])) {
                                    if (Storage::disk('public')->exists($lesson_file->getRawOriginal('file_url'))) {
                                        Storage::disk('public')->delete($lesson_file->getRawOriginal('file_url'));
                                    }
                                    $lesson_file->file_url = $file['file']->store('lessons', 'public');
                                }
                            } elseif ($file['type'] == "2") {
                                $lesson_file->type = 2;
                                if (!empty($file['thumbnail'])) {
                                    if (Storage::disk('public')->exists($lesson_file->getRawOriginal('file_url'))) {
                                        Storage::disk('public')->delete($lesson_file->getRawOriginal('file_url'));
                                    }
                                    $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                                }

                                $lesson_file->file_url = $file['link'];
                            } elseif ($file['type'] == "3") {
                                $lesson_file->type = 3;
                                if (!empty($file['file'])) {
                                    if (Storage::disk('public')->exists($lesson_file->getRawOriginal('file_url'))) {
                                        Storage::disk('public')->delete($lesson_file->getRawOriginal('file_url'));
                                    }
                                    $lesson_file->file_url = $file['file']->store('lessons', 'public');
                                }

                                if (!empty($file['thumbnail'])) {
                                    if (Storage::disk('public')->exists($lesson_file->getRawOriginal('file_url'))) {
                                        Storage::disk('public')->delete($lesson_file->getRawOriginal('file_url'));
                                    }
                                    $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                                }
                            } elseif ($file['type'] == "4") {
                                $lesson_file->type = 4;
                                if (!empty($file['thumbnail'])) {
                                    if (Storage::disk('public')->exists($lesson_file->getRawOriginal('file_url'))) {
                                        Storage::disk('public')->delete($lesson_file->getRawOriginal('file_url'));
                                    }
                                    $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                                }
                                $lesson_file->file_url = $file['link'];
                            }

                            $lesson_file->save();
                        }
                    }
                }
            }

            //Add the new Files
            if ($request->file) {
                foreach ($request->file as $file) {
                    if ($file['type']) {
                        $lesson_file = new File();
                        $lesson_file->file_name = $file['name'];
                        $lesson_file->modal()->associate($lesson);

                        if ($file['type'] == "1") {
                            $lesson_file->type = 1;
                            $lesson_file->file_url = $file['file']->store('lessons', 'public');
                        } elseif ($file['type'] == "2") {
                            $lesson_file->type = 2;
                            $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $lesson_file->file_url = $file['link'];
                        } elseif ($file['type'] == "3") {
                            $lesson_file->type = 3;
                            $lesson_file->file_url = $file['file']->store('lessons', 'public');
                            $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                        } elseif ($file['type'] == "4") {
                            $lesson_file->type = 4;
                            $lesson_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            $lesson_file->file_url = $file['link'];
                        }
                        $lesson_file->save();
                    }
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'code' => 200,
            );
        } catch (\Exception $e ) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function deleteLesson(Request $request)
    {
        // if (!Auth::user()->can('lesson-delete')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }

        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|numeric',
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
           
            $check_question = SubjectQuestionBank::where('chapter_id', $request->lesson_id)->first();
            if (empty($check_question)) {
                $lesson = SubjectChapter::lessonteachers()->where('id', $request->lesson_id)->firstOrFail();
                $lesson->delete();
                $response = array(
                    'error' => false,
                    'message' => trans('data_delete_successfully'),
                    'code' => 200,
                );
        } else {
            
            $response = array(
                'error' => false,
                'message' => __("english.chapter_warning"),
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

    public function getTopic(Request $request)
    {
        // if (!Auth::user()->can('topic-list')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|numeric',
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
            // $sql = Lesson::lessontopicteachers()->with('lesson.class_section', 'lesson.subject', 'file');
            // $data = $sql->where('lesson_id', $request->lesson_id)->orderBy('id', 'DESC')->get();
            $data = ClassSubjectLesson::where('chapter_id', $request->lesson_id)->with('file');
            if ($request->topic_id) {
                $data->where('id', $request->topic_id);
            }
            $data = $data->get();
            $response = array(
                'error' => false,
                'message' => 'Topic Fetched Successfully.',
                'data' => $data,
                'code' => 200,
            );
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
    }

    public function createTopic(Request $request)
    {
        // if (!Auth::user()->can('topic-create')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'class_section_id' => 'required|numeric',
                'subject_id' => 'required|numeric',
                'lesson_id' => 'required|numeric',
                'file' => 'nullable|array',
                'file.*.type' => 'nullable|in:1,2,3,4',
                'file.*.name' => 'required_with:file.*.type',
                'file.*.thumbnail' => 'required_if:file.*.type,2,3,4',
                'file.*.file' => 'required_if:file.*.type,1,3',
                'file.*.link' => 'required_if:file.*.type,2,4',
                //            'file' => 'nullable|array',
                //            'file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                //            'file.*.name' => 'required_with:file.*.type',
                //            'file.*.thumbnail' => 'required_if:file.*.type,youtube_link,video_upload,other_link',
                //            'file.*.file' => 'required_if:file.*.type,file_upload,video_upload',
                //            'file.*.link' => 'required_if:file.*.type,youtube_link,other_link',
                //Regex for Youtube Link
                // 'file.*.link'=>['required_if:file.*.type,youtube_link','regex:/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/'],
                //Regex for Other Link
                // 'file.*.link'=>'required_if:file.*.type,other_link|url'
            ]
        );

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102
            );
            return response()->json($response);
        }
        // $validator2 = Validator::make(
        //     $request->all(),
        //     [
        //         'name' => ['required', new uniqueTopicInLesson($request->lesson_id)]
        //     ]
        // );
        // if ($validator2->fails()) {
        //     $response = array(
        //         'error' => true,
        //         'message' => $validator2->errors()->first(),
        //         'code' => 113,
        //     );
        //     return response()->json($response);
        // }

        try {
            $teacher = Auth::user()->employee;

            $topic = new  ClassSubjectLesson();
            $topic->name = $request->name;
            $topic->description = $request->description;
            $topic->chapter_id = $request->lesson_id;
            $topic->subject_id = $request->subject_id;
            $topic->created_by = $teacher->user_id;
            $topic->campus_id =  $teacher->campus_id;
            $topic->save();

            if ($request->file) {
                foreach ($request->file as $data) {
                    if ($data['type']) {
                        $file = new File();
                        $file->file_name = $data['name'];
                        $file->modal()->associate($topic);

                        if ($data['type'] == "1") {
                            $file->type = 1;
                            $file->file_url = $data['file']->store('lessons', 'public');
                        } elseif ($data['type'] == "2") {
                            $file->type = 2;
                            $file->file_thumbnail = $data['thumbnail']->store('lessons', 'public');
                            $file->file_url = $data['link'];
                        } elseif ($data['type'] == "3") {
                            $file->type = 3;
                            $file->file_thumbnail = $data['thumbnail']->store('lessons', 'public');
                            $file->file_url = $data['file']->store('lessons', 'public');
                        } elseif ($data['type'] == "other_link") {
                            $file->type = 4;
                            $file->file_thumbnail = $data['thumbnail']->store('lessons', 'public');
                            $file->file_url = $data['link'];
                        }

                        $file->save();
                    }
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'code' => 200
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
        return response()->json($response);
    }

    public function updateTopic(Request $request)
    {
        // if (!Auth::user()->can('topic-edit')) {
        //     $response = array(
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return redirect(route('home'))->withErrors($response);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'topic_id' => 'required|numeric',
                'name' => 'required',
                'description' => 'required',
                'class_section_id' => 'required|numeric',
                'subject_id' => 'required|numeric',
                'edit_file' => 'nullable|array',
                'edit_file.*.type' => 'nullable|in:1,2,3,4',
                'edit_file.*.name' => 'required_with:edit_file.*.type',
                'edit_file.*.link' => 'required_if:edit_file.*.type,2,',

                'file' => 'nullable|array',
                'file.*.type' => 'nullable|in:1,2,3,4',
                'file.*.name' => 'required_with:file.*.type',
                'file.*.thumbnail' => 'required_if:file.*.type,2,3,4',
                'file.*.file' => 'required_if:file.*.type,1,3',
                'file.*.link' => 'required_if:file.*.type,2,4',


                //            'edit_file' => 'nullable|array',
                //            'edit_file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                //            'edit_file.*.name' => 'required_with:edit_file.*.type',
                //            'edit_file.*.link' => 'required_if:edit_file.*.type,youtube_link,',
                //
                //            'file' => 'nullable|array',
                //            'file.*.type' => 'nullable|in:file_upload,youtube_link,video_upload,other_link',
                //            'file.*.name' => 'required_with:file.*.type',
                //            'file.*.thumbnail' => 'required_if:file.*.type,youtube_link,video_upload,other_link',
                //            'file.*.file' => 'required_if:file.*.type,file_upload,video_upload',
                //            'file.*.link' => 'required_if:file.*.type,youtube_link,other_link',
            ]
        );
        // if ($validator->fails()) {
        //     $response = array(
        //         'error' => true,
        //         'message' => $validator->errors()->first(),
        //         'code' => 102
        //     );
        //     return response()->json($response);
        // }
        // $validator2 = Validator::make(
        //     $request->all(),
        //     [
        //         'name' => ['required', new uniqueTopicInLesson($request->lesson_id, $request->topic_id)],
        //     ]
        // );
        // if ($validator2->fails()) {
        //     $response = array(
        //         'error' => true,
        //         'message' => $validator2->errors()->first(),
        //         'code' => 113,
        //     );
        //     return response()->json($response);
        // }
        try {
            $topic = ClassSubjectLesson::find($request->topic_id);

            $topic->name = $request->name;
            $topic->description = $request->description;
            $topic->save();

            // Update the Old Files
            if ($request->edit_file) {
                foreach ($request->edit_file as $key => $file) {
                    if ($file['type']) {
                        $topic_file = File::find($file['id']);
                        $topic_file->file_name = $file['name'];

                        if ($file['type'] == "1") {
                            // Type File :- File Upload
                            $topic_file->type = 1;
                            if (!empty($file['file'])) {
                                if (Storage::disk('public')->exists($topic_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($topic_file->getRawOriginal('file_url'));
                                }
                                $topic_file->file_url = $file['file']->store('lessons', 'public');
                            }
                        } elseif ($file['type'] == "2") {
                            // Type File :- Youtube Link Upload
                            $topic_file->type = 2;
                            if (!empty($file['thumbnail'])) {
                                if (Storage::disk('public')->exists($topic_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($topic_file->getRawOriginal('file_url'));
                                }
                                $topic_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            }

                            $topic_file->file_url = $file['link'];
                        } elseif ($file['type'] == "3") {
                            // Type File :- Vedio Upload
                            $topic_file->type = 3;
                            if (!empty($file['file'])) {
                                if (Storage::disk('public')->exists($topic_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($topic_file->getRawOriginal('file_url'));
                                }
                                $topic_file->file_url = $file['file']->store('lessons', 'public');
                            }

                            if (!empty($file['thumbnail'])) {
                                if (Storage::disk('public')->exists($topic_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($topic_file->getRawOriginal('file_url'));
                                }
                                $topic_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            }
                        } elseif ($file['type'] == "4") {
                            $topic_file->type = 4;
                            if (!empty($file['thumbnail'])) {
                                if (Storage::disk('public')->exists($topic_file->getRawOriginal('file_url'))) {
                                    Storage::disk('public')->delete($topic_file->getRawOriginal('file_url'));
                                }
                                $topic_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                            }
                            $topic_file->file_url = $file['link'];
                        }

                        $topic_file->save();
                    }
                }
            }

            //Add the new Files
            if ($request->file) {
                foreach ($request->file as $file) {
                    $topic_file = new File();
                    $topic_file->file_name = $file['name'];
                    $topic_file->modal()->associate($topic);

                    if ($file['type'] == "1") {
                        $topic_file->type = 1;
                        $topic_file->file_url = $file['file']->store('lessons', 'public');
                    } elseif ($file['type'] == "2") {
                        $topic_file->type = 2;
                        $topic_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                        $topic_file->file_url = $file['link'];
                    } elseif ($file['type'] == "3") {
                        $topic_file->type = 3;
                        $topic_file->file_url = $file['file']->store('lessons', 'public');
                        $topic_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                    } elseif ($file['type'] == "4") {
                        $topic_file->type = 4;
                        $topic_file->file_thumbnail = $file['thumbnail']->store('lessons', 'public');
                        $topic_file->file_url = $file['link'];
                    }
                    $topic_file->save();
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'code' => 200
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
        return response()->json($response);
    }

    public function deleteTopic(Request $request)
    {
        // if (!Auth::user()->can('topic-delete')) {
        //     $response = array(
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return redirect(route('home'))->withErrors($response);
        // }
        try {
            $topic = ClassSubjectLesson::findOrFail($request->topic_id);
            $topic->delete();
            $response = array(
                'error' => false,
                'message' => trans('data_delete_successfully'),
                'code' => 200
            );
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
        return response()->json($response);
    }

    public function updateFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_id' => 'required|numeric',
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
            $file = File::find($request->file_id);
            $file->file_name = $request->name;


            if ($file->type == "1") {
                // Type File :- File Upload

                if (!empty($request->file)) {
                    if (Storage::disk('public')->exists($file->getRawOriginal('file_url'))) {
                        Storage::disk('public')->delete($file->getRawOriginal('file_url'));
                    }

                    if ($file->modal_type == "App\Models\Lesson") {

                        $file->file_url = $request->file->store('lessons', 'public');
                    } else if ($file->modal_type == "App\Models\LessonTopic") {

                        $file->file_url = $request->file->store('topics', 'public');
                    } else {

                        $file->file_url = $request->file->store('other', 'public');
                    }
                }
            } elseif ($file->type == "2") {
                // Type File :- Youtube Link Upload

                if (!empty($request->thumbnail)) {
                    if (Storage::disk('public')->exists($file->getRawOriginal('file_url'))) {
                        Storage::disk('public')->delete($file->getRawOriginal('file_url'));
                    }

                    if ($file->modal_type == "App\Models\Lesson") {

                        $file->file_thumbnail = $request->thumbnail->store('lessons', 'public');
                    } else if ($file->modal_type == "App\Models\LessonTopic") {

                        $file->file_thumbnail = $request->thumbnail->store('topics', 'public');
                    } else {

                        $file->file_thumbnail = $request->thumbnail->store('other', 'public');
                    }
                }
                $file->file_url = $request->link;
            } elseif ($file->type == "3") {
                // Type File :- Vedio Upload

                if (!empty($request->file)) {
                    if (Storage::disk('public')->exists($file->getRawOriginal('file_url'))) {
                        Storage::disk('public')->delete($file->getRawOriginal('file_url'));
                    }

                    if ($file->modal_type == "App\Models\Lesson") {

                        $file->file_url = $request->file->store('lessons', 'public');
                    } else if ($file->modal_type == "App\Models\LessonTopic") {

                        $file->file_url = $request->file->store('topics', 'public');
                    } else {

                        $file->file_url = $request->file->store('other', 'public');
                    }
                }

                if (!empty($request->thumbnail)) {
                    if (Storage::disk('public')->exists($file->getRawOriginal('file_url'))) {
                        Storage::disk('public')->delete($file->getRawOriginal('file_url'));
                    }
                    if ($file->modal_type == "App\Models\Lesson") {

                        $file->file_thumbnail = $request->thumbnail->store('lessons', 'public');
                    } else if ($file->modal_type == "App\Models\LessonTopic") {

                        $file->file_thumbnail = $request->thumbnail->store('topics', 'public');
                    } else {

                        $file->file_thumbnail = $request->thumbnail->store('other', 'public');
                    }
                }
            }
            $file->save();

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'data' => $file,
                'code' => 200
            );
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
    }

    public function deleteFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_id' => 'required|numeric',
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
            $file = File::findOrFail($request->file_id);
            $file->delete();
            $response = array(
                'error' => false,
                'message' => trans('data_delete_successfully'),
                'code' => 200
            );
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
    }

    public function getAnnouncement(Request $request)
    {
        // if (!Auth::user()->can('announcement-list')) {
        //     $response = array(
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'class_section_id' => 'nullable|numeric',
            'subject_id' => 'nullable|numeric',
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
            $teacher = Auth::user()->employee;
            $subject_teacher_ids = SubjectTeacher::where('teacher_id', $teacher->id);
            if ($request->class_section_id) {
                 $subject_teacher_ids = $subject_teacher_ids->where('class_section_id', $request->class_section_id);
            }
             if ($request->subject_id) {
                 $subject_teacher_ids = $subject_teacher_ids->where('subject_id', $request->subject_id);
             }
            $subject_teacher_ids = $subject_teacher_ids->get()->pluck('id');
            $sql = Announcement::with('table.class_subject', 'file')->where('table_type', 'App\Models\Curriculum\SubjectTeacher')->whereIn('table_id', $subject_teacher_ids);
            
            $data = $sql->orderBy('id', 'DESC')->paginate();
          //  dd($data);
            $response = array(
                'error' => false,
                'message' => 'Announcement Fetched Successfully.',
                'data' => $data,
                'code' => 200,
            );
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
            return response()->json($response, 200);
        }
    }

    public function sendAnnouncement(Request $request)
    {
        // if (!Auth::user()->can('announcement-create')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'class_section_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'title' => 'required',
            'description' => 'nullable',
            'file' => 'nullable'
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
            //$data = getSettings('session_year');
            $session_id = getActiveSession();
            $teacher_id = Auth::user()->employee->id;
            $announcement = new Announcement();
            $announcement->title = $request->title;
            $announcement->description = $request->description;
            $announcement->session_id = $session_id;

            $subject_teacher = SubjectTeacher::where(['teacher_id' => $teacher_id, 'class_section_id' => $request->class_section_id, 'subject_id' => $request->subject_id])->with('class_subject')->firstOrFail();
            if ($subject_teacher) {
                $announcement->table()->associate($subject_teacher);
            }
            $user = Student::select('user_id')->where('status','active')->where('current_class_section_id', $request->class_section_id)->get()->pluck('user_id');


            $title = 'New announcement in ' . $subject_teacher->class_subject->name;
            //$body = $request->title;
            $announcement->save();
            //send_notification($user, $title, $body, 'class_section');
            if ($request->hasFile('file')) {
                foreach ($request->file as $file_upload) {
                    $file = new File();
                    $file->file_name = $file_upload->getClientOriginalName();
                    $file->type = 1;
                    $file->file_url = $file_upload->store('announcement', 'public');
                    $file->modal()->associate($announcement);
                    $file->save();
                }
            }

            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
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

    public function updateAnnouncement(Request $request)
    {
        // if (!Auth::user()->can('announcement-edit')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'announcement_id' => 'required|numeric',
            'class_section_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'title' => 'required'
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
            $teacher_id = Auth::user()->employee->id;
            $announcement = Announcement::findOrFail($request->announcement_id);
            $announcement->title = $request->title;
            $announcement->description = $request->description;

            $subject_teacher = SubjectTeacher::where(['teacher_id' => $teacher_id, 'class_section_id' => $request->class_section_id, 'subject_id' => $request->subject_id])->with('class_subject')->firstOrFail();
            $announcement->table()->associate($subject_teacher);
            $user = Student::select('user_id')->where('status','active')->where('current_class_section_id', $request->class_section_id)->get()->pluck('user_id');

            $title = 'Update announcement in ' . $subject_teacher->class_subject->name;
            $body = $request->title;
            $announcement->save();
           // send_notification($user, $title, $body, 'class_section');
            if ($request->hasFile('file')) {
                foreach ($request->file as $file_upload) {
                    $file = new File();
                    $file->file_name = $file_upload->getClientOriginalName();
                    $file->type = 1;
                    $file->file_url = $file_upload->store('announcement', 'public');
                    $file->modal()->associate($announcement);
                    $file->save();
                }
            }
            $response = [
                'error' => false,
                'message' => trans('data_update_successfully'),
                'code' => 200,
            ];
        } catch (\Exception $e ) {
            $response = [
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            ];
        }
        return response()->json($response);
    }

    public function deleteAnnouncement(Request $request)
    {
        // if (!Auth::user()->can('announcement-delete')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message'),
        //         'code' => 111
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'announcement_id' => 'required|numeric',
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
            $announcement = Announcement::findorFail($request->announcement_id);
            $announcement->delete();
            $response = array(
                'error' => false,
                'message' => trans('data_delete_successfully'),
                'code' => 200
            );
        } catch (\Exception $e ) {
            $response = [
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            ];
        }
        return response()->json($response);
    }

    public function getAttendance(Request $request)
    {


        // if (!Auth::user()->can('attendance-list')) {
        //     $response = array(
        //         'message' => trans('no_permission_message')
        //     );
        //     return response()->json($response);
        // }

        $class_section_id = $request->class_section_id;
        $student_ids=Student::where('current_class_section_id',$class_section_id)->where('status','active')->pluck('id');

        $attendance_type = $request->type;
        $date = date('Y-m-d', strtotime($request->date));

        $validator = Validator::make($request->all(), [
            'class_section_id' => 'required',
            'date' => 'required|date',
            'type' => 'in:0,1',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()
            );
            return response()->json($response);
        }
        try {

            $sql = Attendance::with(['student'])->whereIn('student_id', $student_ids)->whereDate('clock_in_time', $date);
            //dd($student_ids);
            if (isset($attendance_type) && $attendance_type != '') {
                $sql->where('type', $attendance_type);
            }
            $sql = $sql->get();
            $data=[];
            foreach ($sql as $key => $value) {
                $data[]=[
                    'id'=>$value->id,
                    'type'=>$this->attendanceType($value->type),
                    'session_year_id'=>$value->session_id,
                    'student_id'=>$value->student_id,
                    'class_section_id'=>(int)$value->student->current_class_section_id,
                    'remark'=>(string)$value->remark,
                    'date'=>Carbon::parse($value->clock_in_time)->format('Y-m-d'),



                ];
            }
           // dd($data);
            // $holiday = Holiday::where('date', $date)->get();
            // if ($holiday->count()) {
            //     $response = array(
            //         'error' => false,
            //         'data' => $data,
            //         'is_holiday' => true,
            //         'holiday' => $holiday,
            //         'message' => "Data Fetched Successfully",
            //     );
            // } else {
                
                if (count($data)) {
                    $response = array(
                        'error' => false,
                        'data' => $data,
                        'is_holiday' => false,
                        'message' => "Data Fetched Successfully",
                    );
                } else {
                    $response = array(
                        'error' => false,
                        'data' => $data,
                        'is_holiday' => false,
                        'message' => "Attendance not recorded",
                    );
                }
           // }
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'data' => $e
            );
        }
    }


    public function submitAttendance(Request $request)
    {
        // if (!Auth::user()->can('attendance-create') || !Auth::user()->can('attendance-edit')) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('no_permission_message')
        //     );
        //     return response()->json($response);
        // }
        $validator = Validator::make($request->all(), [
            'class_section_id' => 'required',
            // 'student_id' => 'required',
            'attendance.*.student_id' => 'required',
            'attendance.*.type' => 'required|in:0,1',
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()

            );
            return response()->json($response);
        }
       try {
            $session_id = getActiveSession(); 
            $class_section_id = $request->class_section_id;
            $date = date('Y-m-d', strtotime($request->date));
            $getid = Attendance::select('id')->whereDate('clock_in_time', $date)->get()->toArray();
            
            for ($i = 0; $i < count($request->attendance); $i++) {
                $type = '';
                if($request->attendance[$i]['type']==0){
                $type='absent';
                }else{
                   $type='present';
                }
                $std_id = $request->attendance[$i]['student_id'];
               
                if (count($getid) > 0 && isset($getid[$i]['id'])) {
                    $attendance = Attendance::find($getid[$i]['id']);
                    $attendance->type = $type;
                    $attendance->student_id = $std_id;
                    $attendance->session_id = $session_id;
                    $attendance->clock_in_time = $date;
                    $attendance->save();
                } else {
                    $attendance = new Attendance();
                    $attendance->type = $type;
                    $attendance->student_id = $std_id;
                    $attendance->session_id = $session_id;
                    $attendance->clock_in_time = $date;
                    $attendance->save();
                    $student= Student::where('id', $attendance->student_id)->first();

                    if($attendance->type=='present'){
                        $this->notificationUtil->autoSendStudentNotification('student_attendance_check_in', $student, $attendance);
                        }elseif($attendance->type=='absent'){
                         $this->notificationUtil->autoSendStudentNotification('student_attendance_absent_sms', $student, $attendance);

                        }
                }

             
            
                // if ($request->holiday != '' && $request->holiday == 3) {
                //     $attendance->type = $request->holiday;
                // } else {
                //     $attendance->type = $type;
                // }

             

                $response = [
                    'error' => false,
                    'message' => trans('data_store_successfully')
                ];
            }
        } catch (Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'data' => $e

            );
        }
        return response()->json($response);
    }
    public function getStudentList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_section_id' => 'required|numeric',
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
            $user = Auth::user()->employee;
            $class_section_id = $request->class_section_id;
            //$get_class_section_id = ClassSection::select('id')->where('id', $class_section_id)->get()->pluck('id');//->where('class_teacher_id', $user->id)
            $sql = Student::with('student_class','current_class_section','user')->where('status','active')->where('current_class_section_id', $class_section_id);
            $data= [];
            $students = $sql->orderBy('id')->get();
            foreach ($students as $key => $student) {
                # code...
           
                $image = file_exists(public_path('uploads/student_image/'.$student->student_image)) ? url('uploads/student_image/'.$student->student_image) : url('uploads/student_image/default.png');

                $data[]=  [
                    "id"=> (int)$student->id,
                    "user_id" =>$student->user_id?(int)$student->user_id:1,
                    "class_section_id" => (int)$student->current_class_section_id,
                    "category_id" => 4,
                    "admission_no" => $student->admission_no,
                    "roll_number" =>$student->roll_no,
                    "caste" => (string)$student->caste,
                    "religion" => $student->religion,
                    "admission_date" => $student->admission_date,
                    "blood_group" => (string)$student->blood_group,
                    "height" =>  (string)$student->height,
                    "weight" =>  (string)$student->weight,  
                 
                  "is_new_admission"=> 1,
                  "father_id"=> 1,
                  "mother_id"=> 2,
                  "guardian_id"=> 0,
                  "user"=> [
                    "id"=> $student->user_id?(int)$student->user_id:1,
                    "first_name" => $student->first_name,
                     "last_name" => (string)$student->last_name,
                    "image"=> $image,
                    "gender"=> $student->gender,
                    "dob"=> $student->birth_date,
                    "current_address" => (string)$student->std_current_address,
                     "permanent_address" => (string)$student->std_permanent_address,
                  ],
                  "class_section"=> [
                    "id"=> (int)$student->current_class_section->id,
                    "class_id"=> (int)$student->current_class_section->class_id,
                    "section_id"=>(int) $student->current_class_section->id,
                    "class_teacher_id"=> '',//$student->current_class_section->teacher_id,
                    "class"=> [
                      "id"=> (int)$student->student_class->id,
                      "name"=> $student->student_class->title,
                      "medium_id"=> 1,
                      "medium"=> [
                        "name"=> "Gujrati",
                        "id"=> 1
                      ]
                    ],
                    "section"=> [
                      "id"=> (int)$student->current_class_section->id,
                      "name"=> $student->current_class_section->section_name
                    ]
                  ]
                
            ];
           // break;
        }
            $response = array(
                'error' => false,
                'message' => "Student Details Fetched Successfully",
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
    public function getStudentDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|numeric',
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
            
            $student_total_present = Attendance::where('student_id', $request->student_id)->where('type', 'present')->count();
            $student_total_absent = Attendance::where('student_id', $request->student_id)->where('type', 'absent')->count();
           
            $today_date_string = Carbon::now();
            $today_date_string->toDateTimeString();
            $today_date = date('Y-m-d', strtotime($today_date_string));
            $student_today_attendance = Attendance::where('student_id', $request->student_id)->whereDate('clock_in_time', $today_date)->get();
          /// dd($student_today_attendance);
            if ($student_today_attendance->count()) {
                 foreach ($student_today_attendance as $student_attendance) {
                    if ($student_attendance['type'] == 'present') {
                        $today_attendance = 'Present';
                    } elseif($student_attendance['type'] =='absent') {
                        $today_attendance = 'Absent';
                    }
                }
            } else {
                $today_attendance = 'Not Taken';
            }
          // dd($today_attendance);

            $father_data =[ [
                
                  "id"=> 1,
                  "user_id"=> 54,
                  "first_name"=> "Sagar",
                  "last_name"=> "Gor",
                  "gender"=> "Male",
                  "email"=> "sagargor006.test@gmail.com",
                  "mobile"=> "1234567890",
                  "image"=> "https://e-school.wrteam.in/storage/parents/s9MbGYdSmGEnZ0CMixhw1FvJnlZZKJyQhDmJ3wQV.png",
                  "dob"=> "1970-03-03",
                  "occupation"=> "Coder"
                
            ]];
              $mother_data=[ [
                
                  "id"=> 2,
                  "user_id"=> 55,
                  "first_name"=> "Krishna",
                  "last_name"=> "Gor",
                  "gender"=> "Female",
                  "email"=> "wrteam.krishna@gmail.com",
                  "mobile"=> "12345678970",
                  "image"=> "https://e-school.wrteam.in/storage/parents/NXvoGBPntUCqksAKneebFMAxBJnvfRckDyWFp6sm.jpg",
                  "dob"=> "1984-01-09",
                  "occupation"=> "Housewife"
            
              ]];
           
                    $response = array(
                        'error' => false,
                        'message' => "Student Details Fetched Successfully",
                        'father_data' => $father_data,
                        'mother_data' => $mother_data,
                        'total_present' => $student_total_present,
                        'total_absent' => $student_total_absent,
                        'today_attendance' => $today_attendance,
                        'code' => 200,
                    );
        
            
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
    }

    public function getTeacherTimetable(Request $request)
    {
        try {
            $teacher = $request->user()->employee;
          //  $timetable = Timetable::where('subject_teacher_id', $teacher->id)->with('class_section', 'subject')->get();
            //$student = $request->user()->student;           
            $timetable=ClassTimeTable::with(['subjects','teacher','periods','classes','section'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('period_id')->get();
            $data= [];
            //dd($timetable);
            foreach ($timetable as $key => $value) {
                
            $data[]=
                [
                  'id' => $value->id,
                  "subject_teacher_id"=> (int)$value->teacher_id,
                  "class_section_id"=> $value->class_section_id,
                  'start_time'=>$value->periods->start_time,
                  'end_time'=>$value->periods->end_time,
                  "note"=> "",
                  "day"=> 1,
                  "day_name"=> "monday",
                  "class_section"=> [
                    "id"=> $value->section->id,
                    "class_id"=> $value->section->class_id,
                    "section_id"=> $value->section->id,
                    "class_teacher_id"=> (int)$value->teacher_id,
                    "class"=> [
                      "id"=> (int)$value->classes->id,
                      "name"=> $value->classes->title,
                      "medium_id"=> 1,
                      "medium"=> [
                        "name"=> "Gujrati",
                        "id"=> 1
                      ]
                    ],
                    "section"=> [
                      "id"=> $value->section->id,
                      "name"=> $value->section->section_name
                    ]
                  ],
                  "subject"=> [
                    "id"=> $value->id,
                    "class_section_id"=> $value->class_section_id,
                    "subject_id"=> $value->subject_id,
                    "teacher_id"=> $value->teacher_id,
                    "subject"=> $value->subjects
                  ]
                    ];
                }
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

    public function submitExamMarksBySubjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
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
        
       
    //    $sub=ExamSubjectResult::where('exam_create_id',$request->exam_id)
    //             ->where('subject_id',$request->subject_id)
    //             ->where('student_id',30)->first();
               
    //        dd($sub);
            foreach ($request->marks_data as $marks) {
                //\Log::emergency($marks['student_id']);
                $sub=ExamSubjectResult::where('exam_create_id',$request->exam_id)
                ->where('subject_id',$request->subject_id)
                ->where('student_id',$marks['student_id'])->first();
                if (!empty($sub)) {
                if (!empty($marks['obtained_marks'])) {
                    $sub->obtain_theory_mark=$marks['obtained_marks'];
                    $sub->total_obtain_mark=$marks['obtained_marks'];
                    $obtain_percentage=($marks['obtained_marks']*100)/$sub->total_mark;
                    $sub->obtain_percentage=$obtain_percentage;
                    $grades=ExamGrade::get();
                    foreach ($grades as $grade) {
                        if ($obtain_percentage >= $grade->percentag_from && $obtain_percentage  <= $grade->percentage_to) {
                          $sub->grade_id=$grade->id;
                          $sub->remark=$grade->remark;
                        }
                    }
                    $sub->save();


                    ////
                    $exam_allocation=ExamAllocation::findOrFail($sub->exam_allocation_id);
                    $subject=ExamSubjectResult::where('exam_allocation_id', $exam_allocation->id)->get();
                    $total_mark=0;
                    $total_obtain_mark=0;
                    foreach ($subject as $subj) {
                        $total_mark+=$subj->total_mark;
                        $total_obtain_mark+=$subj->total_obtain_mark;
                    }
                  $exam_allocation->total_mark=$total_mark;
                  $exam_allocation->obtain_mark=$total_obtain_mark;
                  $final_percentage=($total_obtain_mark*100)/$total_mark;
                  $exam_allocation->final_percentage=$final_percentage;
                  $grades=ExamGrade::get();
                  foreach ($grades as $grade) {
                      if ($final_percentage >= $grade->percentag_from && $final_percentage <= $grade->percentage_to) {
                        $exam_allocation->grade_id=$grade->id;
                        $exam_allocation->remark=$grade->remark;
                      }
                  }
                  $exam_allocation->save();
                }
            }
        }
            $response = array(
                'error' => false,
                'message' => trans('data_store_successfully'),
                'code' => 200
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


    public function submitExamMarksByStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|numeric',
            'student_id' => 'required|numeric',
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
            $exam_published = Exam::where(['id' => $request->exam_id, 'publish' => 1])->first();
            if (isset($exam_published)) {
                $response = array(
                    'error' => true,
                    'message' => trans('exam_published'),
                    'code' => 400,
                );
                return response()->json($response);
            }

            $teacher_id = Auth::user()->teacher->id;
            $class_id = ClassSection::where('class_teacher_id', $teacher_id)->pluck('class_id')->first();

            //exam status
            $starting_date_db = ExamTimetable::select(DB::raw("min(date)"))->where(['exam_id' => $request->exam_id, 'class_id' => $class_id])->first();
            $starting_date = $starting_date_db['min(date)'];
            $ending_date_db = ExamTimetable::select(DB::raw("max(date)"))->where(['exam_id' => $request->exam_id, 'class_id' => $class_id])->first();
            $ending_date = $ending_date_db['max(date)'];
            $currentTime = Carbon::now();
            $current_date = date($currentTime->toDateString());
            if ($current_date >= $starting_date && $current_date <= $ending_date) {
                $exam_status = "1"; // Upcoming = 0 , On Going = 1 , Completed = 2
            } elseif ($current_date < $starting_date) {
                $exam_status = "0"; // Upcoming = 0 , On Going = 1 , Completed = 2
            } else {
                $exam_status = "2"; // Upcoming = 0 , On Going = 1 , Completed = 2
            }

            if ($exam_status != 2) {
                $response = array(
                    'error' => true,
                    'message' => trans('exam_not_completed_yet'),
                    'code' => 400
                );
                return response()->json($response);
            } else {
                $grades = Grade::orderBy('ending_range', 'desc')->get();

                foreach ($request->marks_data as $marks) {
                    $exam_timetable = ExamTimetable::where(['exam_id' => $request->exam_id, 'subject_id' => $marks['subject_id']])->firstOrFail();
                    $passing_marks = $exam_timetable->passing_marks;
                    if ($marks['obtained_marks'] >= $passing_marks) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }
                    $marks_percentage = ($marks['obtained_marks'] / $exam_timetable->total_marks) * 100;

                    $exam_grade = findExamGrade($marks_percentage);
                    if ($exam_grade == null) {
                        $response = array(
                            'error' => true,
                            'message' => trans('grades_data_does_not_exists'),
                        );
                        return response()->json($response);
                    }

                    $exam_marks = ExamMarks::where(['exam_timetable_id' => $exam_timetable->id, 'student_id' => $request->student_id, 'subject_id' => $marks['subject_id']])->first();
                    if ($exam_marks) {
                        $exam_marks_db = ExamMarks::find($exam_marks->id);
                        $exam_marks_db->obtained_marks = $marks['obtained_marks'];
                        $exam_marks_db->passing_status = $status;
                        $exam_marks_db->grade = $exam_grade;
                        $exam_marks_db->save();

                        $response = array(
                            'error' => false,
                            'message' => trans('data_update_successfully'),
                            'code' => 200,
                        );
                    } else {
                        $exam_result_marks[] = array(
                            'exam_timetable_id' => $exam_timetable->id,
                            'student_id' => $request->student_id,
                            'subject_id' => $marks['subject_id'],
                            'obtained_marks' => $marks['obtained_marks'],
                            'passing_status' => $status,
                            'session_year_id' => $exam_timetable->session_year_id,
                            'grade' => $exam_grade,
                        );
                    }
                }
                if (isset($exam_result_marks)) {
                    ExamMarks::insert($exam_result_marks);
                    $response = array(
                        'error' => false,
                        'message' => trans('data_store_successfully'),
                        'code' => 200,
                    );
                }
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


    public function GetStudentExamResult(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|nullable'
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
        // $teacher_id = Auth::user()->teacher->id;
        // $class_data = ClassSection::where('class_teacher_id', $teacher_id)->with('class.medium', 'section')->get()->first();

        //     $exam_marks_db = ExamClass::with(['exam.timetable' => function ($q) use ($request, $class_data) {
        //         $q->where('class_id', $class_data->class_id)->with(['exam_marks' => function ($q) use ($request) {
        //             $q->where('student_id', $request->student_id);
        //         }])->with('subject:id,name,type,image,code');
        //     }])->with(['exam.results' => function ($q) use ($request) {
        //         $q->where('student_id', $request->student_id)->with(['student' => function ($q) {
        //             $q->select('id', 'user_id', 'roll_number')->with('user:id,first_name,last_name');
        //         }])->with('session_year:id,name');
        //     }])->where('class_id', $class_data->class_id)->get();

        //     if (sizeof($exam_marks_db)) {
        //         foreach ($exam_marks_db as $data_db) {
        //             $starting_date_db = ExamTimetable::select(DB::raw("min(date)"))->where(['exam_id' => $data_db->exam_id, 'class_id' => $class_data->class_id])->first();
        //             $starting_date = $starting_date_db['min(date)'];
        //             $ending_date_db = ExamTimetable::select(DB::raw("max(date)"))->where(['exam_id' => $data_db->exam_id, 'class_id' => $class_data->class_id])->first();
        //             $ending_date = $ending_date_db['max(date)'];
        //             $currentTime = Carbon::now();
        //             $current_date = date($currentTime->toDateString());
        //             if ($current_date >= $starting_date && $current_date <= $ending_date) {
        //                 $exam_status = "1"; // Upcoming = 0 , On Going = 1 , Completed = 2
        //             } elseif ($current_date < $starting_date) {
        //                 $exam_status = "0"; // Upcoming = 0 , On Going = 1 , Completed = 2
        //             } else {
        //                 $exam_status = "2"; // Upcoming = 0 , On Going = 1 , Completed = 2
        //             }


        //             if ($exam_status == 2) {
        //                 $marks_array = array();
        //                 if (sizeof($data_db->exam->timetable)) {
        //                     foreach ($data_db->exam->timetable as $timetable_db) {
        //                         $total_marks = $timetable_db->total_marks;
        //                         $exam_marks = array();
        //                         if (sizeof($timetable_db->exam_marks)) {
        //                             foreach ($timetable_db->exam_marks as $marks_data) {
        //                                 $exam_marks = array(
        //                                     'marks_id' => $marks_data->id,
        //                                     'subject_name' => $marks_data->subject->name,
        //                                     'subject_type' => $marks_data->subject->type,
        //                                     'total_marks' => $total_marks,
        //                                     'obtained_marks' => $marks_data->obtained_marks,
        //                                     'grade' => $marks_data->grade,
        //                                 );
        //                             }
        //                         } else {
        //                             $exam_marks = (object)[];
        //                         }

        //                         $marks_array[] = array(
        //                             'subject_id' => $timetable_db->subject->id,
        //                             'subject_name' => $timetable_db->subject->name,
        //                             'subject_type' => $timetable_db->subject->type,
        //                             'total_marks' => $total_marks,
        //                             'subject_code' => $timetable_db->subject->code,
        //                             'marks' => $exam_marks
        //                         );
        //                     }
        //                 }
        //                 $exam_result = array();
        //                 if (sizeof($data_db->exam->results)) {
        //                     foreach ($data_db->exam->results as $result_data) {
        //                         $exam_result = array(
        //                             'result_id' => $result_data->id,
        //                             'exam_id' => $result_data->exam_id,
        //                             'exam_name' => $data_db->exam->name,
        //                             'class_name' => $class_data->class->name . '-' . $class_data->section->name . ' ' . $class_data->class->medium->name,
        //                             'student_name' => $result_data->student->user->first_name . ' ' . $result_data->student->user->last_name,
        //                             'exam_date' => $starting_date,
        //                             'total_marks' => $result_data->total_marks,
        //                             'obtained_marks' => $result_data->obtained_marks,
        //                             'percentage' => $result_data->percentage,
        //                             'grade' => $result_data->grade,
        //                             'session_year' => $result_data->session_year->name,
        //                         );
        //                     }
        //                 } else {
        //                     $exam_result = (object)[];;
        //                 }
        //                 $data[] = array(
        //                     'exam_id' => $data_db->exam_id,
        //                     'exam_name' => $timetable_db->exam->name,
        //                     'exam_date' => $starting_date,
        //                     'marks_data' => $marks_array,
        //                     'result' => $exam_result
        //                 );
        //             }
        //         }
            //     $response = array(
            //         'error' => false,
            //         'message' => "Exam Marks Fetched Successfully",
            //         'data' => isset($data) ? $data : [],
            //         'code' => 200,
            //     );
            // } else {
                $response = array(
                    'error' => false,
                    'message' => "Exam Marks Fetched Successfully",
                    'data' => [],
                    'code' => 200,
                );
            //}
        }catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103,
            );
        }
        return response()->json($response);
    }

    public function GetStudentExamMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|nullable'
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
            // $teacher_id = Auth::user()->teacher->id;
            // $class_data = ClassSection::where('class_teacher_id', $teacher_id)->with('class.medium', 'section')->get()->first();

            // $exam_marks_db = ExamClass::with(['exam.timetable' => function ($q) use ($request, $class_data) {
            //     $q->where('class_id', $class_data->class_id)->with(['exam_marks' => function ($q) use ($request) {
            //         $q->where('student_id', $request->student_id);
            //     }])->with('subject:id,name,type,image');
            // }])->where('class_id', $class_data->class_id)->get();

            // if (sizeof($exam_marks_db)) {
            //     foreach ($exam_marks_db as $data_db) {
            //         $marks_array = array();
            //         foreach ($data_db->exam->timetable as $marks_db) {
            //             $exam_marks = array();
            //             if (sizeof($marks_db->exam_marks)) {
            //                 foreach ($marks_db->exam_marks as $marks_data) {
            //                     $exam_marks = array(
            //                         'marks_id' => $marks_data->id,
            //                         'subject_name' => $marks_data->subject->name,
            //                         'subject_type' => $marks_data->subject->type,
            //                         'total_marks' => $marks_data->timetable->total_marks,
            //                         'obtained_marks' => $marks_data->obtained_marks,
            //                         'grade' => $marks_data->grade,
            //                     );
            //                 }
            //             } else {
            //                 $exam_marks = [];
            //             }

            //             $marks_array[] = array(
            //                 'subject_id' => $marks_db->subject->id,
            //                 'subject_name' => $marks_db->subject->name,
            //                 'marks' => $exam_marks
            //             );
            //         }
            //         $data[] = array(
            //             'exam_id' => $data_db->exam_id,
            //             'exam_name' => $marks_db->exam->name,
            //             'marks_data' => $marks_array
            //         );
            //     }
            //     $response = array(
            //         'error' => false,
            //         'message' => "Exam Marks Fetched Successfully",
            //         'data' => $data,
            //         'code' => 200,
            //     );
            // } else {
                $response = array(
                    'error' => false,
                    'message' => "Exam Marks Fetched Successfully",
                    'data' => [],
                    'code' => 200,
                );
           // }
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
            'status' => 'in:0,1,2,3',
            'publish' => 'in:0,1',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
       // try {
            // $teacher = Auth::user()->teacher;
            // $teacher_class = ClassSection::with('class')->where('class_teacher_id', $teacher->id)->first();
            // $class_id = $teacher_class->class->id;
            // $sql = ExamClass::with('exam.session_year:id,name')->where('class_id', $class_id);
            // if (isset($request->publish)) {
            //     $publish = $request->publish;
            //     $sql->whereHas('exam', function ($q) use ($publish) {
            //         $q->where('publish', $publish);
            //     });
            // }
            
            $exam_data_db=ExamCreate::with(['session','term'])
             ->get();
    
               foreach ($exam_data_db as $data) {
                   // date status
                   $starting_date = $data->from_date;
                   $ending_date = $data->to_date;
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
                               'id' => $data->id,
                               'name' => $data->term->name,
                               'description' => $data->term->name,
                               'publish' => 1,
                               'session_year' => $data->session->title,
                               'exam_starting_date' => $starting_date,
                               'exam_ending_date' => $ending_date,
                               'exam_status' => $exam_status,
                           );
                       } else if ($request->status == 1) {
                           if ($exam_status == 0) {
                               $exam_data[] = array(
                                'id' => $data->id,
                                'name' => $data->term->name,
                                'description' => $data->term->name,
                                'publish' => 1,
                                'session_year' => $data->session->title,
                                'exam_starting_date' => $starting_date,
                                'exam_ending_date' => $ending_date,
                                'exam_status' => $exam_status,
                               );
                           }
                       } else if ($request->status == 2) {
                           if ($exam_status == 1) {
                               $exam_data[] = array(
                                'id' => $data->id,
                                'name' => $data->term->name,
                                'description' => $data->term->name,
                                'publish' => 1,
                                'session_year' => $data->session->title,
                                'exam_starting_date' => $starting_date,
                                'exam_ending_date' => $ending_date,
                                'exam_status' => $exam_status,
                               );
                           }
                       }else{
                           if ($exam_status == 2) {
                               $exam_data[] = array(
                                'id' => $data->id,
                                'name' => $data->term->name,
                                'description' => $data->term->name,
                                'publish' => 1,
                                'session_year' => $data->session->title,
                                'exam_starting_date' => $starting_date,
                                'exam_ending_date' => $ending_date,
                                'exam_status' => $exam_status,
                               );
                           }
                       }
                   } else {
                       $exam_data[] = array(
                        'id' => $data->id,
                        'name' => $data->term->name,
                        'description' => $data->term->name,
                        'publish' => 1,
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
        // } catch (\Exception $e) {
        //     $response = array(
        //         'error' => true,
        //         'message' => trans('error_occurred'),
        //         'code' => 103,
        //     );
        // }
        return response()->json($response);
    }

    public function getExamDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|nullable',
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
        
            $user = $request->user()->employee;
           // $student_class_id=SubjectTeacher::with(['class_subject','classes','classes.classLevel','class_section'])->where('teacher_id',$user->id)->pluck('class_id');
            $student_class_id=SubjectTeacher::with(['class_subject','classes','classes.classLevel','class_section'])->where('teacher_id',$user->id)->pluck('subject_id');
          //dd($student_class_id);
            $exam_data_db = ExamDateSheet::with(['subject','subject.classes'])->whereIn('subject_id', $student_class_id)
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
                    'total_marks' => (int)$data->subject->total,
                    'passing_marks' => (int)$data->subject->passing_percentage,
                    'date' => $data->date,
                    'starting_time' => $data->start_time,
                    'ending_time' => $data->end_time,
                    'subject' => array(
                        'id' => (int)$data->subject->id,
                        'name' => $data->subject->name .' ('.$data->type.'.)',
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

    public function attendanceType($type){
        if($type=='present'){
            return 1;
        }
        elseif($type=='absent'){
            return 0;
        }
        
    }
}