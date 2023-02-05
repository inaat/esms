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


Route::resource('leave_application_students', 'LeaveApplicationStudentController');
Route::post('leave_applications/update-status', 'LeaveApplicationStudentController@updateStatus');

Route::resource('hrm-leave_applications', 'LeaveApplicationEmployeeController');
Route::post('hrm-leave_applications/update-status', 'LeaveApplicationEmployeeController@updateStatus');

Route::resource('hrm-department', 'Hrm\HrmDepartmentController');
Route::resource('hrm-allowance', 'Hrm\HrmAllowanceController');
Route::resource('hrm-deduction', 'Hrm\HrmDeductionController');
Route::resource('hrm-print', 'Hrm\HrmPrintController');
Route::get('employee-list-print', 'Hrm\HrmPrintController@employeeListPrintCreate');
Route::post('employee-list-print-post', 'Hrm\HrmPrintController@PostEmployeePrint');
Route::resource('hrm-designation', 'Hrm\HrmDesignationController');
Route::resource('hrm-education', 'Hrm\HrmEducationController');
Route::resource('hrm-leave_category', 'Hrm\HrmLeaveCategoryController');
Route::resource('hrm-shift', 'Hrm\HrmShiftController');
Route::resource('hrm-employee', 'Hrm\HrmEmployeeController');
Route::get('employee-profile/{id}', 'Hrm\HrmEmployeeController@employeeProfile');
Route::resource('hrm-payroll', 'Hrm\HrmPayrollController');
Route::post('payroll-assign-search', 'Hrm\HrmPayrollController@payrollAssignSearch')->name('payroll-assign-search');
Route::get('/hrm-sync-with-device/{id}', 'Hrm\HrmEmployeeController@syncWithDevice');
Route::get('/employee/ledger', 'Hrm\HrmEmployeeController@getLedger');
Route::get('/employee/documents', 'Hrm\HrmEmployeeController@get_documents');
Route::get('/employee/document/create/{id}', 'Hrm\HrmEmployeeController@document_create');
Route::post('/employee/document/post}', 'Hrm\HrmEmployeeController@document_post');
Route::delete('/employee/document/{id}', 'Hrm\HrmEmployeeController@document_destroy');


Route::get('/payments/pay-employee-due/{employee_id}', 'Hrm\HrmTransactionPaymentController@getPayEmployeeDue');
Route::post('/payments/pay-employee-due', 'Hrm\HrmTransactionPaymentController@postPayEmployeeDue');
Route::get('/payments/hrm-add_payment/{transaction_id}', 'Hrm\HrmTransactionPaymentController@addPayment');
///option 
Route::get('/payments/add_employee_advance_amount_payment/{employee_id}', 'Hrm\HrmTransactionPaymentController@addEmployeeAdvanceAmountPayment');
Route::post('/payments/hrm-post_advance_amount_payment', 'Hrm\HrmTransactionPaymentController@postAdvanceAmount');
///

Route::resource('hrm_payment', 'Hrm\HrmTransactionPaymentController');
Route::resource('hrm-attendance', 'Hrm\HrmAttendanceController');
Route::post('hrm-attendance-assign-search', 'Hrm\HrmAttendanceController@attendanceAssignSearch')->name('hrm-attendance-assign-search');



Route::post('employee/update-status', 'Hrm\HrmEmployeeController@updateStatus');
Route::post('employee/employee-resign', 'Hrm\HrmEmployeeController@employeeResign');
Route::post('/employee/register/check-email', 'Hrm\HrmEmployeeController@postCheckEmail')->name('employee.postCheckEmail');

Route::get('/shift/assign-users/{shift_id}', 'Hrm\HrmShiftController@getAssignUsers');
Route::post('/shift/assign-users', 'Hrm\HrmShiftController@postAssignUsers');

