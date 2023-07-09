<?php

use Illuminate\Support\Facades\Route;

Route::resource('dashboard', 'TeacherLayout\TeacherDashboardController');
Route::resource('routine_mark_entry', 'TeacherLayout\TeacherDashboardController@teacherRoutineTestSubjectMarkEntry');
Route::get('/teacher_mark_entry', 'TeacherLayout\TeacherDashboardController@teacherSubjectMarkEntry');
Route::get('/get_teacher_campus_classes', 'TeacherLayout\TeacherDashboardController@get_teacher_classes');
Route::get('/get_teacher_class_section', 'TeacherLayout\TeacherDashboardController@get_teacher_class_section');
Route::get('/get-teacher-subjects', 'TeacherLayout\TeacherDashboardController@get_teacher_subjects');
Route::get('/teacher_subject_marks_print', 'TeacherLayout\TeacherDashboardController@teacher_subject_marks_print');
Route::get('/get_teacher_attendance', 'TeacherLayout\TeacherDashboardController@getTeacherAttendance');
Route::get('/lesson_progress_send', 'NotificationController@lessonProgressSendCreate');
Route::post('/lesson_progress_post', 'NotificationController@lessonProgressSendPost');
Route::get('/get_student_attendance', 'StudentController@getStudentAttendance');
Route::get('/get_employee_attendance', 'Hrm\HrmEmployeeController@getEmployeeAttendance');


Route::resource('student/dashboard', 'StudentLayout\StudentDashboardController');
Route::resource('guardian/dashboard', 'GuardianLayout\GuardianDashboardController');
Route::resource('staff/dashboard', 'StaffLayout\StaffDashboardController');

Route::get('/fee_remainder', 'NotificationController@FeeRemainderCreate');
Route::post('/fee_remainder_send', 'NotificationController@FeeRemainderPost');

Route::get('/media', 'NotificationController@mediaCreate');
Route::post('/media_send', 'NotificationController@mediaPost');


