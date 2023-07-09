<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Resources\ChapterResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Storage;
use App\Models\Announcement;
use App\Models\Attendance;

class StudentApiController extends Controller
{
    


     /**
     * @OA\Get(
     *   path="/api/class-subjects",
     * 	 security={{"bearerAuth":{}}},

     *   tags={"Student"},
     *   @OA\Response(response="200",
     *     description="class-subjects",
     *   )
     * )
     */

    public function classSubjects(Request $request)
    { 
        try {
            $user = $request->user();
            $subjects = ClassSubject::where('class_id',$user->student->current_class->id)->get();
            return response(SubjectResource::collection($subjects));
            // $response = array(
            //     'error' => false,
            //     'message' => 'Class Subject Fetched Successfully.',
            //     //                'data' => new ClassSubjectCollection($subjects),
            //     'data' => $subjects,
            //     'code' => 200
            // );
            // return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'code' => 103
            );
            return response()->json($response, 200);
        }
    }


    /**
     * @OA\Get(
     *   path="/api/timetable",
     *   tags={"Student"},
     * security={{"bearerAuth":{}}},
     *   @OA\Response(response="200",
     *     description="Get TimeTable",
     *   )
     * )
     */
    public function getTimetable(Request $request)
    {
       try {
            $student = $request->user()->student;
           // $timetable = Timetable::where('class_section_id', $student->class_section_id)->with('subject_teacher')->orderBy('day', 'asc')->orderBy('start_time', 'asc')->get();
           
           $timetable=ClassTimeTable::with(['subjects','teacher','periods'])
           ->where('class_id', $student->current_class_id)
           ->where('class_section_id', $student->current_class_section_id)
           ->orderBy('period_id')->get();
           return response(TimetableResource::collection($timetable));
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
        * path="/api/subjects-chapters",
        * operationId="subjects-chapters",
        * tags={"Student"},
        * security={{"bearerAuth":{}}},
        * summary="User Get Chapters",
        * description="Get Chapters Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="form-data",
        *            @OA\Schema(
        *               required={"subject_id"},
        *               @OA\Property(property="subject_id", type="integer"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Get Chapters Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Get Chapters Successfully",
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
    public function getSubjectChapters(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'subject_id' => 'required|numeric'
            ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
      // dd($request->subject_id);
        try {
            $data =SubjectChapter::where('subject_id',$request->subject_id)->get();
            return response(ChapterResource::collection($data));

         
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
        * path="/api/subjects-chapter-lessons",
        * operationId="subjects-chapter-lessons",
        * tags={"Student"},
        * security={{"bearerAuth":{}}},
        * summary="User Get Chapters",
        * description="Get Chapters Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="form-data",
        *            @OA\Schema(
        *               required={"chapter_id","class_id","section_id"},
        *               @OA\Property(property="chapter_id", type="integer"),
        *               @OA\Property(property="class_id", type="integer"),
        *               @OA\Property(property="section_id", type="integer"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Get Chapters Lesson Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Get Chapters Lesson Successfully",
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
    public function getChapterLesson(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'chapter_id' => 'required|numeric',
        'class_id' => 'required|numeric',
        'section_id' => 'required|numeric'

            ]);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first(),
                'code' => 102,
            );
            return response()->json($response);
        }
      // dd($request->subject_id);
       try {
            $data =ClassSubjectLesson::where('chapter_id',$request->chapter_id)->get();
            return response(LessonResource::collection($data));

         
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
        * @OA\Get(
        * path="/api/assignments",
        * operationId="get assignments",
        * tags={"Student"},
        * security={{"bearerAuth":{}}},
        * summary=" assignment_id : 1    //OPTIONAL
     * subject_id : 1       //OPTIONAL",
        * description="Get assignments Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="form-data",
        *            @OA\Schema(
        *               @OA\Property(property="assignment_id", type="integer"),
        *               @OA\Property(property="subject_id", type="integer"),
        *               @OA\Property(property="is_submitted", type="integer"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Assignments Fetched Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Assignments Fetched Successfully",
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
            $student = $request->user()->student;            
            $data = Assignment::where('class_id',$student->current_class_id)->where('class_section_id', $student->current_class_section_id)->with('file', 'subject', 'submission.file');
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

            if (isset($request->type) && $request->type == "class") {
                $data = $data->where('table_type', "App\Models\Classes")->where('table_id', $class_id);
            }

            if (isset($request->type) && $request->type == "subject") {
                $data = $data->where('table_type', "App\Models\SubjectTeacher")->whereIn('table_id', $table);
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
 /**
     * @param
     * month : 4 //OPTIONAL
     * year : 2022 //OPTIONAL
     */

      	/**
        * @OA\Get(
        * path="/api/attendance",
        * operationId="get attendance",
        * tags={"Student"},
        * security={{"bearerAuth":{}}},
        * description="month : 4 //OPTIONAL year : 2023 //OPTIONAL ",
        *      @OA\Response(
        *          response=201,
        *          description="attendance Details Fetched Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="attendance Details Fetched Successfully",
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

            $attendance = Attendance::where('student_id', $student->id);
            if (isset($request->month)) {
                $attendance = $attendance->whereMonth('clock_in_time', $request->month);
            }

            if (isset($request->year)) {
                $attendance = $attendance->whereYear('clock_in_time', $request->year);
            }
            $attendance = $attendance->get();
            $response = array(
                'error' => false,
                'message' => "Attendance Details Fetched Successfully",
                'data' => ['attendance' => $attendance],
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
   



   
   
}
