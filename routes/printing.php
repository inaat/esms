<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| printing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/pay-slip/{transaction_id}', 'Hrm\HrmPayrollController@paySlip');

Route::get('/single-fee-card/{transaction_id}', 'SchoolPrinting\FeeCardPrintController@singlePrint');
Route::get('/class-wise-fee-card-printing', 'SchoolPrinting\FeeCardPrintController@createClassWisePrint');

Route::get('class-subject/create/{id}', [
    'as' => 'class-subject.create',
    'uses' => 'Curriculum\ClassSubjectLessonController@create'
]);
// Route::get('class-subject-progress/create/{id}', [
//     'as' => 'class-subject-progress.create',
//     'uses' => 'Curriculum\ClassSubjectProgressController@create'
// ]);
Route::get('class-curriculum/{class_id}', [
    'as' => 'class-curriculum.index',
    'uses' => 'Curriculum\CurriculumController@index'
]);
Route::get('class-curriculum/create/{id}', [
    'as' => 'class-curriculum.create',
    'uses' => 'Curriculum\CurriculumController@create'
]);
Route::get('class-subject-question/create/{id}', [
    'as' => 'class-subject-question.create',
    'uses' => 'Curriculum\ClassSubjectQuestionBankController@create'
]);
////////////
Route::get('class-section/{class_id}', [
    'as' => 'class-section.index',
    'uses' => 'Curriculum\ClassSectionController@index'
]);

Route::resource('class-section', 'Curriculum\ClassSectionController',['except' => ['index','create']]);
///////////
Route::resource('class-curriculum', 'Curriculum\CurriculumController',['except' => ['index','create']]);
Route::resource('curriculum-class-subject', 'Curriculum\ClassSubjectController');
Route::resource('assign-subject', 'Curriculum\AssignSubjectTeacherController');
Route::resource('class-subject', 'Curriculum\ClassSubjectLessonController',['except' => 'create']);
Route::resource('class-subject-progress', 'Curriculum\ClassSubjectProgressController',['except' => 'create']);
Route::get('get-chapter-lessons', 'Curriculum\ClassSubjectProgressController@getLessons');
Route::get('get-chapters', 'Curriculum\ClassSubjectProgressController@getSubjectChapters');
Route::resource('class-subject-question', 'Curriculum\ClassSubjectQuestionBankController',['except' => ['create']]);
Route::resource('syllabus', 'Curriculum\SyllabusMangerController');
Route::post('syllabus-print', 'Curriculum\SyllabusMangerController@printSyllabus');

Route::resource('class-time-table-period', 'Curriculum\ClassTimeTablePeriodController');
Route::get('get-periods', 'Curriculum\ClassTimeTablePeriodController@getPeriods');
Route::get('get-subjects', 'Curriculum\ClassSubjectController@getSubjects');
Route::get('get-section-subjects', 'Curriculum\ClassSubjectController@getSectionSubjects');

Route::resource('class-time-table', 'Curriculum\ClassTimeTableController');

///ID Card 
Route::get('/create-class-wise-id-print', 'SchoolPrinting\IdCardPrintController@createClassWiseIdPrint');
Route::post('/class-wise-id-card-print', 'SchoolPrinting\IdCardPrintController@classWiseIdPrintPost');
Route::post('/class-id-card-print', 'SchoolPrinting\IdCardPrintController@PrintIdCard');

///    ///Employee Identity Card Area
 
Route::get('/create-employee-id-print', 'SchoolPrinting\IdCardPrintController@createEmployeeIdPrint');
Route::post('/employee-id-card-print', 'SchoolPrinting\IdCardPrintController@employeeIdPrintPost');
Route::post('/id-card-print', 'SchoolPrinting\IdCardPrintController@employeePrintIdCard');



Route::resource('student-particular', 'SchoolPrinting\StudentPrintController');

Route::resource('my-library', 'Curriculum\MyLibraryController');
Route::get('get-library-class-subjects/{id}', 'Curriculum\MyLibraryController@getSubjectsByClass');
Route::get('get-library-subjects-chapter/{id}', 'Curriculum\MyLibraryController@getSubjectsChapters');
Route::get('get-library-subjects-chapter-lesson/{id}', 'Curriculum\MyLibraryController@getChaptersLessons');

Route::resource('digital-library', 'IframeSites\WebSiteController');


Route::get('manage-subject/create/{id}', [
    'as' => 'manage-subject.create',
    'uses' => 'Curriculum\ManageSubjectController@create'
]);
 Route::resource('manage-subject', 'Curriculum\ManageSubjectController',['except' => ['create']]);
 Route::delete('file/delete/{id}', 'Curriculum\ManageSubjectController@deleteFile')->name('file.delete');

 Route::get('/getSubjectChapters', 'Curriculum\ManageSubjectController@getSubjectChapters');
 Route::post('/get_subject_chapter_questions', 'Curriculum\ManageSubjectController@getSubjectChaptersQuestions');
 Route::post('/get_questions_according_config', 'Curriculum\ManageSubjectController@getQuestionsAccordingConfig');

 Route::get('/manual_paper', 'Curriculum\PaperMakerController@manualPaperCreate');
 Route::get('/single-fee/{transaction_id}', 'Curriculum\PaperMakerController@show');
 Route::get('/paper/preview', 'Curriculum\PaperMakerController@generateManualPaper');

