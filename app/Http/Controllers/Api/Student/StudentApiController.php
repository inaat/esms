<?php

namespace App\Http\Controllers\Api\Student;

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

class StudentApiController extends Controller
{
    use IssueTokenTrait;

    private $client;

    public function __construct()
    {
        $this->client = OClient::where('password_client', 1)->first();

    }
    public function login(Request $request)
    {
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
            if (!$auth->hasRole('Student#1')) {
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
            $user = $auth->load(['student.current_class', 'student.current_class.classLevel', 'student.current_class_section', 'student.studentCategory']);

            if ($request->fcm_id) {
             $auth->fcm_id = $request->fcm_id;
            $auth->save();
            }
            //Set Class Section name
            $class_section_name = $user->student->current_class->title . " " . $user->student->current_class_section->section_name;
            //Set Medium name
            $medium_name = $user->student->current_class->classLevel->title;
            unset($user->student->current_class);
            unset($user->student->current_class_section);
            unset($user->student);
            $image = file_exists(public_path('uploads/student_image/'.$user->student->student_image)) ? url('uploads/student_image/'.$user->student->student_image) : url('uploads/student_image/default.png');
            $user = [
              "id" => $user->student->id,
              "first_name" => $user->first_name,
              "last_name" => $user->last_name,
              "gender" => $user->student->gender,
              "email" => $user->email,
              "mobile" => $user->student->mobile_no,
              "image" => $image,
              "dob" => $user->student->birth_date,
              "current_address" => $user->student->std_current_address,
              "permanent_address" => $user->student->std_permanent_address,
              "status" => 1,
              "class_section_name" => $class_section_name,
              "medium_name" => $medium_name,
              "category_name" => "General",
              "name" => "Student",
              "user_id" => $user->id,
              "class_section_id" => $user->student->current_class_section_id,
              "category_id" => 4,
              "admission_no" => $user->student->admission_no,
              "roll_number" => $user->student->roll_no,
              "caste" => $user->student->caste,
              "religion" => $user->student->religion,
              "admission_date" => $user->student->admission_date,
              "blood_group" => $user->student->blood_group,
              "height" =>  $user->student->height,
              "weight" =>  $user->student->weight,
          ];
            //Set Category name
            //$user->category_name = $user->student->category->name;
            //unset($user->student->category);
            $response = array(
                'error' => false,
                'message' => 'User logged-in!',
                'token' => $token,
                'refresh_token' => $refresh_token,
                'expires_in' => $expires_in,
                'data' => $user,
                // 'data' => flattenMyModel($user),
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

    public function subjects(Request $request)
    {
        try {
            $user = $request->user();
            $subjects = ClassSubject::where('class_id',$user->student->current_class->id)->get();
          //dd($subjects);
            $core_subjects=[];
            foreach ($subjects as $key => $subject) {
                $image = file_exists(public_path('uploads/subjects/'.$subject->subject_icon)) ? url('uploads/subjects/'.$subject->subject_icon) : url('uploads/subjects/default.svg');

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
                  'image' =>  $image,
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
                //'data' => ['core_subject' => []],
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

   

    
    public function getParentDetails(Request $request)
    {
        try {
            $student = $request->user()->student;
            //dd($student);
           /* $data = array(
                'father' => (!empty($student->father)) ? $student->father : (object)[],
                'mother' => (!empty($student->mother)) ? $student->mother : (object)[],
                'guardian' => (!empty($student->guardian)) ? $student->guardian : (object)[]
            );*/
            $image = file_exists(public_path('uploads/employee_image/default.jpg')) ? url('uploads/employee_image/default.jpg') : url('uploads/employee_image/default.jpg');

            $data=
                [                  'father' => 
                  array (
                    'id' => 1,
                    'user_id' => 54,
                    'first_name' => $student->father_name,
                    'last_name' => '',
                    'gender' => 'Male',
                    'email' => 'sagargor006.test@gmail.com',
                    'mobile' => $student->father_phone,
                    'image' => $image,
                    'dob' => '1970-03-03',
                    'occupation' =>$student->father_occupation ,
                    'cnic_no' =>$student->father_cnic_no ,
                  ),
                  'mother' => 
                  array (
                    'id' => 1,
                    'user_id' => 54,
                    'first_name' => $student->mother_name,
                    'last_name' => '',
                    'gender' => 'female',
                    'email' => 'sagargor006.test@gmail.com',
                    'mobile' => $student->mother_phone,
                    'image' => $image,
                    'dob' => '1970-03-03',
                    'occupation' =>$student->mother_occupation ,
                    'cnic_no' =>$student->mother_cnic_no ,
                  ),
                  'guardian' => []
                ];
          
                  $data1 = array(
                    'father' => (!empty($data['father'])) ? $data['father'] : (object)[],
                    'mother' => (!empty($data['mother'])) ? $data['mother'] : (object)[],
                    'guardian' => (!empty($data['guardian'])) ? $data['guardian'] : (object)[]
                );
             //dd($data1['father']);
            $response = array(
                'error' => false,
                'message' => "Parent Details Fetched Successfully",
                'data' => $data1,
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
    
    public function getTimetable(Request $request)
    {
        try {
           
            $student = $request->user()->student;           
           $timetable=ClassTimeTable::with(['subjects','teacher','periods'])
           ->where('class_id', $student->current_class_id)
           ->where('class_section_id', $student->current_class_section_id)
           ->orderBy('period_id')->get();
           $data=TimetableResource::collection($timetable);
            /*$data= [
                  [
                  "start_time"=> "17:00:00",
                  "end_time"=> "18:00:00",
                  "day"=> 1,
                  "day_name"=> "monday",
                  "subject"=> [
                    
                    "id"=> 1,

                    "name"=> "Maths",
                    "code"=> "MA",
                    "bg_color"=> "#65a3fe",
                    "image"=> "https://e-school.wrteam.in/storage/subjects/9KzzSrwwpyCeGCcQnej2VeXcew7T719PrFXQZ7SP.svg",
                    "medium_id"=> 1,
                    "type"=> "Practical"
                    
                  ],
                  "teacher_first_name"=> "john",
                  "teacher_last_name"=> "Doe"
                ]
            ];*/
            $response = array(
                'error' => false,
                'message' => "Timetable Fetched Successfully",
                //data' => new TimetableCollection($timetable),
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
     * chapter_id : 1 //OPTIONAL
     */
    public function getLessons(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
    * chapter_id : 1
    * topic_id : 1    //OPTIONAL
    */
   public function getLessonTopics(Request $request)
   {
       $validator = Validator::make($request->all(), [
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

   public function getAssignments(Request $request)
   {
       $validator = Validator::make($request->all(), [
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
          // $data = Assignment::where('class_id',$student->current_class_id)->where('class_section_id', $student->current_class_section_id)->with('file', 'subject', 'submission.file');
          $student = $request->user()->student;
          //$get_class_id = ClassSection::select('class_id')->where('id', $student->class_section_id)->get()->pluck('class_id');
         
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
               // 'message' => trans('error_occurred'),
               'message' => trans($e->getMessage()),
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

/**
       * @OA\Post(
       * path="/api/submit-assignment",
       * operationId="submit-assignment",
       * tags={"Student"},
       * security={{"bearerAuth":{}}},
       * summary="submit-assignment",
       * description="submit-assignment",
       *     @OA\RequestBody(
       *         @OA\JsonContent(),
       *         @OA\MediaType(
       *            mediaType="multipart/form-data",
       *            @OA\Schema(
       *               type="object",
       *               required={"assignment_id", "files[]"},
       *               @OA\Property(property="assignment_id", type="integer"),
       *               @OA\Property(property="subject_id", type="integer"),
       *                @OA\Property(
*                      property="files[]",
*                      description="files",
*                      type="file",
*                      
*                   ),
       *            ),
       *        ),
       *    ),
       *      @OA\Response(
       *          response=201,
       *          description="Assignments Submitted Successfully",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(
       *          response=200,
       *          description="Assignments Submitted Successfully",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(
       *          response=422,
       *          description="Unprocessable Entity",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(response=400, description="Bad request"),
       *      @OA\Response(response=404, description="Resource Not Found"),
       * )
       */
   public function submitAssignment(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'assignment_id' => 'required|numeric',
           'subject_id' => 'nullable|numeric',
           'files' => 'required|array',
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
           $student = $request->user()->student;
           $session_id = getActiveSession();

           $assignment = Assignment::where('id', $request->assignment_id)->where('class_id',$student->current_class_id)->where('class_section_id', $student->current_class_section_id)->firstOrFail();
           $assignment_submission = AssignmentSubmission::where('assignment_id', $request->assignment_id)->where('student_id', $student->id)->first();
           if (empty($assignment_submission)) {
               $assignment_submission = new AssignmentSubmission();
               $assignment_submission->assignment_id = $request->assignment_id;
               $assignment_submission->student_id = $student->id;
               $assignment_submission->session_id = $session_id;
           } else if ($assignment_submission->status == 2 && $assignment->resubmission) {
               // if assignment submission is rejected and
               // Assignment has resubmission allowed then change the status to resubmitted
               $assignment_submission->status = 3;
               if ($assignment_submission->file) {
                   foreach ($assignment_submission->file as $file) {
                       if (Storage::disk('public')->exists($file->file_url)) {
                           Storage::disk('public')->delete($file->file_url);
                       }
                   }
               }
               $assignment_submission->file()->delete();
           } else {
               $response = array(
                   'error' => true,
                   'message' => "You already have submitted your assignment.",
                   'code' => 104
               );
               return response()->json($response);
           }

           $assignment_submission->save();
           foreach ($request->file('files') as $key => $image) {
               $file = new File();
               $file->file_name = $image->getClientOriginalName();
               $file->modal()->associate($assignment_submission);
               $file->type = 1;
               $file->file_url = $image->store('assignment', 'public');
               $file->save();
           }
           $submitted_assignment = AssignmentSubmission::where('id', $assignment_submission->id)->with('file')->get();
           $response = array(
               'error' => false,
               'message' => "Assignments Submitted Successfully",
               'data' => $submitted_assignment,
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

    /**
       * @OA\Post(
       * path="/api/delete-assignment-submission",
       * operationId="delete-assignment-submission",
       * tags={"Student"},
       * security={{"bearerAuth":{}}},
       * summary="delete-assignment-submission",
       * description="delete-assignment-submission",
       *     @OA\RequestBody(
       *         @OA\JsonContent(),
       *         @OA\MediaType(
       *            mediaType="multipart/form-data",
       *            @OA\Schema(
       *               type="object",
       *               required={"assignment_submission_id"},
       *               @OA\Property(property="assignment_submission_id", type="integer"),
       *            ),
       *        ),
       *    ),
       *      @OA\Response(
       *          response=201,
       *          description="Assignments Deleted Successfully",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(
       *          response=200,
       *          description="Assignments Deleted Successfully",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(
       *          response=422,
       *          description="Unprocessable Entity",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(response=400, description="Bad request"),
       *      @OA\Response(response=404, description="Resource Not Found"),
       * )
       */
   public function deleteAssignmentSubmission(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'assignment_submission_id' => 'required|numeric',
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
           $student = $request->user()->student;
           $assignment_submission = AssignmentSubmission::where('id', $request->assignment_submission_id)->where('student_id', $student->id)->with('file')->first();

           if (!empty($assignment_submission) && $assignment_submission->status == 0) {
               foreach ($assignment_submission->file as $file) {
                   if (Storage::disk('public')->exists($file->file_url)) {
                       Storage::disk('public')->delete($file->file_url);
                   }
               }
               $assignment_submission->file()->delete();
               $assignment_submission->delete();
               $response = array(
                   'error' => false,
                   'message' => "Assignments Deleted Successfully",
                   'code' => 200,
               );
           } else {
               $response = array(
                   'error' => true,
                   'message' => "You can not delete assignment",
                   'code' => 110,
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

           /**
       * @OA\Get(
       * path="/api/announcements",
       * operationId="get announcements",
       * tags={"Student"},
       * security={{"bearerAuth":{}}},
       * description=" 'type' => 'nullable|in:subject,noticeboard,class ,subject_id' => 'required_if:type,subject|numeric'",
       *      @OA\Response(
       *          response=201,
       *          description="Announcement Details Fetched Successfully",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(
       *          response=200,
       *          description="Announcement Details Fetched Successfully",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(
       *          response=422,
       *          description="Unprocessable Entity",
       *          @OA\JsonContent()
       *       ),
       *      @OA\Response(response=400, description="Bad request"),
       *      @OA\Response(response=404, description="Resource Not Found"),
       * )
       */
   public function getAnnouncements(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'type' => 'nullable|in:subject,noticeboard,class',
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
       try {
           $student = $request->user()->student;
           $class_id = $student->current_class_section_id;
           $session_id =getActiveSession();
           $table = null;
           if (isset($request->type) && $request->type == "subject") {
               $table = SubjectTeacher::where('class_section_id', $student->current_class_section_id)->where('subject_id', $request->subject_id)->get()->pluck('id');
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
       try {
        $exam_data_db=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
        ->where('student_id', Auth::user()->student->id)  ->get();

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
       
         
           $student_class_section_id = Auth::user()->student->current_class_id;
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
                   'total_marks' => (int)$data->subject->total,
                   'passing_marks' => (int)$data->subject->passing_percentage,
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
       try {
           $exam_result_db=ExamAllocation::with(['student','campuses','session','current_class','current_class_section','exam_create','exam_create.term','grade','subject_result','subject_result.subject_grade','subject_result.subject_name'])
             ->where('student_id', Auth::user()->student->id) ->get();



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
      /**
     * @param
     * month : 4 //OPTIONAL
     * year : 2022 //OPTIONAL
     */
    public function getAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            $student = $request->user()->student;
            $session_id =getActiveSession();

            $attendance = Attendance::where('student_id', $student->id)->where('session_id', $session_id);
            // $holidays = new Holiday;
            // $session_year_data = SessionYear::find($session_id);
             if (isset($request->month)) {
                 $attendance = $attendance->whereMonth('clock_in_time', $request->month);
                // $holidays = $holidays->whereMonth('date', $request->month);
             }

            if (isset($request->year)) {
                 $attendance = $attendance->whereYear('clock_in_time', $request->year);
            //     $holidays = $holidays->whereYear('date', $request->year);
             }
             $attendance = $attendance->get();

                 $data=[];
            foreach ($attendance as $key => $value) {
                $data[]=[
                    'id'=>$value->id,
                    'type'=>$this->attendanceType($value->type),
                    'session_year_id'=>(int)$value->session_id,
                    'student_id'=>(int)$value->student_id,
                    'class_section_id'=>(int)$value->student->current_class_section_id,
                    'remark'=>(string)$value->remark,
                    'date'=>Carbon::parse($value->clock_in_time)->format('Y-m-d'),



                ];
            }
           // 

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
                'data' => ['attendance' => $data,'session_year' => $session_year_data],
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