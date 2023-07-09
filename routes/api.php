<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Student\StudentApiController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ParentApiController;
use App\Http\Controllers\Api\TeacherApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * GENERAL APIs
 **/
Route::get('holidays', [ApiController::class, 'getHolidays']);
Route::get('schools', [ApiController::class, 'getSchool']);
Route::get('sliders', [ApiController::class, 'getSliders']);
Route::get('current-session-year', [ApiController::class, 'getSessionYear']);
Route::get('settings', [ApiController::class, 'getSettings']);
Route::post('forgot-password', [ApiController::class, 'forgotPassword']);
///Test
Route::get('get-student-result', [TeacherApiController::class, 'GetStudentExamResult']); // Student Exam Result
Route::get('general-announcements', [TeacherApiController::class, 'generalAnnouncements']);
Route::post('submit-assessment', [TeacherApiController::class, 'postAssessment']);

////Test

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [ApiController::class, 'logout']);
    Route::post('change-password', [ApiController::class, 'changePassword']);
    

});
/**
 * TEACHER APIs
 **/
Route::group(['prefix' => 'teacher'], function () {
    //Non Authenticated APIs
    Route::post('login', [TeacherApiController::class, 'login']);
    //Authenticated APIs
    Route::group(['middleware' => ['auth:api',]], function () {
        Route::get('classes', [TeacherApiController::class, 'classes']);

        Route::get('subjects', [TeacherApiController::class, 'subjects']);

        //Assignment
        Route::get('get-assignment', [TeacherApiController::class, 'getAssignment']);
        Route::post('create-assignment', [TeacherApiController::class, 'createAssignment']);
        Route::post('update-assignment', [TeacherApiController::class, 'updateAssignment']);
        Route::post('delete-assignment', [TeacherApiController::class, 'deleteAssignment']);

        //Assignment Submission
        Route::get('get-assignment-submission', [TeacherApiController::class, 'getAssignmentSubmission']);
        Route::post('update-assignment-submission', [TeacherApiController::class, 'updateAssignmentSubmission']);

        //File
        Route::post('delete-file', [TeacherApiController::class, 'deleteFile']);
        Route::post('update-file', [TeacherApiController::class, 'updateFile']);

        //Lesson
        Route::get('get-lesson', [TeacherApiController::class, 'getLesson']);
        Route::post('create-lesson', [TeacherApiController::class, 'createLesson']);
        Route::post('update-lesson', [TeacherApiController::class, 'updateLesson']);
        Route::post('delete-lesson', [TeacherApiController::class, 'deleteLesson']);

        //Topic
        Route::get('get-topic', [TeacherApiController::class, 'getTopic']);
        Route::post('create-topic', [TeacherApiController::class, 'createTopic']);
        Route::post('update-topic', [TeacherApiController::class, 'updateTopic']);
        Route::post('delete-topic', [TeacherApiController::class, 'deleteTopic']);

        //Announcement
        Route::get('get-announcement', [TeacherApiController::class, 'getAnnouncement']);
        Route::post('send-announcement', [TeacherApiController::class, 'sendAnnouncement']);
        Route::post('update-announcement', [TeacherApiController::class, 'updateAnnouncement']);
        Route::post('delete-announcement', [TeacherApiController::class, 'deleteAnnouncement']);
        Route::get('general-announcements', [TeacherApiController::class, 'generalAnnouncements']);

        Route::get('get-attendance', [TeacherApiController::class, 'getAttendance']);
        Route::post('submit-attendance', [TeacherApiController::class, 'submitAttendance']);


        //Exam
        Route::get('get-exam-list', [TeacherApiController::class, 'getExamList']); // Exam list Route
        Route::get('get-exam-details', [TeacherApiController::class, 'getExamDetails']); // Exam Details Route
        Route::post('submit-exam-marks/subject', [TeacherApiController::class, 'submitExamMarksBySubjects']); // Submit Exam Marks By Subjects Route
        Route::post('submit-exam-marks/student', [TeacherApiController::class, 'submitExamMarksByStudent']); // Submit Exam Marks By Students Route

        //Route::group(['middleware' => ['auth:sanctum', 'checkStudent']], function () {
            Route::get('get-student-result', [TeacherApiController::class, 'GetStudentExamResult']); // Student Exam Result
            Route::get('get-student-marks', [TeacherApiController::class, 'GetStudentExamMarks']); // Student Exam Marks
        //});

        //Student List
        Route::post('submit-routine-marks/subject', [TeacherApiController::class, 'submitRoutineMarksBySubjects']); // Submit Exam Marks By Subjects Route

        Route::get('student-list-by-routine-test', [TeacherApiController::class, 'getStudentListByRoutine']);
        Route::get('student-list-by-exam-allocation', [TeacherApiController::class, 'getStudentListByExamAllocation']);
        Route::get('student-list', [TeacherApiController::class, 'getStudentList']);
        Route::get('student-details', [TeacherApiController::class, 'getStudentDetails']);

        //Schedule List
        Route::get('teacher_timetable', [TeacherApiController::class, 'getTeacherTimetable']);
        Route::get('get-assessment', [TeacherApiController::class, 'getAssessment']);
        Route::post('submit-assessment', [TeacherApiController::class, 'postAssessment']);

    });
});
Route::group(['prefix' => 'student'], function () {

    //Non Authenticated APIs
    Route::post('login', 'Api\Student\StudentApiController@login');

    Route::middleware(['auth:api'])->group(function () {

    Route::get('subjects', 'Api\Student\StudentApiController@subjects');
    Route::get('parent-details', [StudentApiController::class, 'getParentDetails']);
    Route::get('timetable', [StudentApiController::class, 'getTimetable']);
    Route::get('lessons', [StudentApiController::class, 'getLessons']);
    Route::get('lesson-topics', [StudentApiController::class, 'getLessonTopics']);
    Route::get('assignments', [StudentApiController::class, 'getAssignments']);
    Route::post('submit-assignment', [StudentApiController::class, 'submitAssignment']);
    Route::post('delete-assignment-submission', [StudentApiController::class, 'deleteAssignmentSubmission']);
    Route::get('attendance', [StudentApiController::class, 'getAttendance']);
    Route::get('announcements', [StudentApiController::class, 'getAnnouncements']);
    Route::get('get-exam-list', [StudentApiController::class, 'getExamList']); // Exam list Route
    Route::get('get-exam-details', [StudentApiController::class, 'getExamDetails']); // Exam Details Route
    Route::get('exam-marks', [StudentApiController::class, 'getExamMarks']); // Exam Details Route


});
});
/**
 * PARENT APIs
 **/
Route::group(['prefix' => 'parent'], function () {
    //Non Authenticated APIs
    Route::post('login', [ParentApiController::class, 'login']);
    //Authenticated APIs
    
    Route::group(['middleware' => ['auth:api',]], function () {
       Route::get('announcements', [ParentApiController::class, 'getAnnouncements']);
        Route::group(['middleware' => ['auth:api', 'checkChild']], function () {

            Route::get('subjects', [ParentApiController::class, 'subjects']);
            Route::get('class-subjects', [ParentApiController::class, 'classSubjects']);
            Route::get('timetable', [ParentApiController::class, 'getTimetable']);
            Route::get('lessons', [ParentApiController::class, 'getLessons']);
            Route::get('lesson-topics', [ParentApiController::class, 'getLessonTopics']);
            Route::get('assignments', [ParentApiController::class, 'getAssignments']);
            Route::get('attendance', [ParentApiController::class, 'getAttendance']);
            Route::get('teachers', [ParentApiController::class, 'getTeachers']);
            Route::get('get-exam-list', [ParentApiController::class, 'getExamList']); // Exam list Route
            Route::get('get-exam-details', [ParentApiController::class, 'getExamDetails']); // Exam Details Route
            Route::get('exam-marks', [ParentApiController::class, 'getExamMarks']); //Exam Marks
        });
    });
});
Route::get('/map-attendance',[App\Http\Controllers\Api\AttendanceController::class, 'index']);
Route::get('/map-attendance-students',[App\Http\Controllers\Api\AttendanceController::class, 'studentsMapping']);
Route::post('/device-attendance',[App\Http\Controllers\Api\EmployeeAttendanceController::class, 'store']);
Route::post('/device-attendance-student',[App\Http\Controllers\Api\AttendanceController::class, 'store']);
Route::get('/get-sms', 'Api\SmsController@index');
Route::apiResource('/sms', 'Api\SmsController');

Route::get('/get-campus', 'Api\GlobalController@get_campus');
Route::get('/get-class/{id}', 'Api\GlobalController@get_class');
Route::get('/get-subject/{id}', 'Api\GlobalController@get_subject');
Route::get('/get-chapter/{id}', 'Api\GlobalController@get_chapter');
Route::post('/question-bank', 'Api\GlobalController@postQuestion');
Route::post('/chapter', 'Api\GlobalController@postChapter');

Route::group(['middleware' => ['cors', 'json.response']], function () {

// Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('login', 'Api\Auth\LoginController@login');
Route::post('refresh-token', 'Api\Auth\LoginController@refresh');
// Route::post('social_auth', 'Api\Auth\SocialAuthController@socialAuth');

//Route::middleware(['auth:api','SetSessionData','timezone'])->group(function () {
Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', 'Api\Auth\LoginController@logout');

    Route::get('profile', 'Api\UserController@profile')->name('profile.api');
    Route::get('profile', 'Api\UserController@profile')->name('profile.api');
    Route::get('class-subjects', 'Api\StudentApiController@classSubjects')->name('class-subjects.api');
    Route::get('timetable', 'Api\StudentApiController@getTimetable')->name('timetable.api');
    Route::get('subjects-chapters', 'Api\StudentApiController@getSubjectChapters')->name('chapter.api');
    Route::get('subjects-chapter-lessons', 'Api\StudentApiController@getChapterLesson')->name('chapter_lesson.api');
    Route::get('assignments', 'Api\StudentApiController@getAssignments');
    Route::post('submit-assignment', 'Api\StudentApiController@submitAssignment');
    Route::post('delete-assignment-submission', 'Api\StudentApiController@deleteAssignmentSubmission');
   Route::get('attendance', 'Api\StudentApiController@getAttendance');
    Route::get('announcements', 'Api\StudentApiController@getAnnouncements');




});
});



