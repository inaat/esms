<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
