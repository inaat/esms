<?php

namespace App\Http\Middleware;

use Closure;
use Menu;

class AdminSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }
        $user = \Auth::user();

        if ($user->user_type == 'teacher') {
            $this->teacherSidebar();
        } elseif ($user->user_type == 'student') {
            $this->studentSidebar();
        } elseif ($user->user_type == 'guardian') {
            $this->guardianSidebar();
        } elseif ($user->user_type == 'staff') {
            $this->staffSidebar();
        } else {
            $this->adminSidebar();
        }



        return $next($request);
    }

    private function adminSidebar()
    {
        Menu::create('admin-sidebar-menu', function ($menu) {
            // active mm-active $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

            $is_admin = auth()->user()->hasRole('Admin#' . session('system_details.id')) ? true : false;
            //Home

            $menu->header('MAIN MENU');
            $this->global($menu);
            if (auth()->user()->can('backup.view')) {
                $menu->url(
                    action('BackUpController@index'),
                    __('english.backup'),
                    ['icon' => 'fa fas fa-hdd', 'active' => request()->segment(1) == 'backup']
                )->order(1);
            }
            $menu->url(action('HomeController@index'), __('english.dashboard'), ['icon' => 'bx bx-home', 'active' => request()->segment(1) == 'home'])->order(2);

            if (auth()->user()->can('campus.view')) {
                $menu->url(action('CampusController@index'), __('english.campuses'), ['icon' => 'bx bx-buildings', 'active' => request()->segment(1) == 'campuses'])->order(3);
            }
            if (auth()->user()->can('general_settings.view') || auth()->user()->can('session.view') || auth()->user()->can('roles.view') || auth()->user()->can('award.view') || auth()->user()->can('class_level.view') || auth()->user()->can('province.view') || auth()->user()->can('district.view') || auth()->user()->can('city.view') || auth()->user()->can('region.view')) {
                $menu->dropdown(
                    __('english.global_settings'),
                    function ($sub) {
                        if (auth()->user()->can('general_settings.view')) {
                            $sub->url(
                                action('SystemSettingController@index'),
                                __('english.general_setting'),
                                ['icon' => 'bx bx-cog ', 'active' => request()->segment(1) == 'setting']
                            );
                        }
                        if (auth()->user()->can('session.view') || auth()->user()->can('session.create')) {
                            $sub->url(
                                action('SessionController@index'),
                                __('session.sessions'),
                                ['icon' => 'bx bx-calendar ', 'active' => request()->segment(1) == 'session']
                            );
                        }
                        if (auth()->user()->can('roles.view')) {
                            $sub->url(
                                action('RoleController@index'),
                                __('english.roles'),
                                ['icon' => 'fa fas fa-briefcase', 'active' => request()->segment(1) == 'roles']
                            );
                        }

                        // $sub->url(
                        //     action('DesignationController@index'),
                        //     __('designation.designations'),
                        //     ['icon' => 'bx bx-shape-circle ', 'active' => request()->segment(1) == 'designation']
                        // );
                        // $sub->url(
                        //     action('DiscountController@index'),
                        //     __('discount.discounts'),
                        //     ['icon' => 'bx bx-disc ', 'active' => request()->segment(1) == 'discounts']
                        // );
                        if (auth()->user()->can('award.view')) {
                            $sub->url(
                                action('AwardController@index'),
                                __('award.awards'),
                                ['icon' => 'bx bx-award ', 'active' => request()->segment(1) == 'awards']
                            );
                        }
                        if (auth()->user()->can('class_level.view')) {
                            $sub->url(
                                action('ClassLevelController@index'),
                                __('class_level.class_level'),
                                ['icon' => 'bx bx-menu ', 'active' => request()->segment(1) == 'class_levels']
                            );
                        }
                        if (auth()->user()->can('province.view')) {
                            $sub->url(
                                action('ProvinceController@index'),
                                __('english.provinces'),
                                ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'provinces']
                            );
                        }
                        if (auth()->user()->can('district.view')) {
                            $sub->url(
                                action('DistrictController@index'),
                                __('english.districts'),
                                ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'districts']
                            );
                        }
                        if (auth()->user()->can('city.view')) {
                            $sub->url(
                                action('CityController@index'),
                                __('english.cities'),
                                ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'cities']
                            );
                        }
                        if (auth()->user()->can('region.view')) {
                            $sub->url(
                                action('RegionController@index'),
                                __('english.regions'),
                                ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'regions']
                            );
                        }
                    },
                    ['icon' => 'bx bx-globe']
                )->order(4);
            }
            if (auth()->user()->can('student.view') || auth()->user()->can('student.create') || auth()->user()->can('student_category.view')) {
                $menu->dropdown(
                    __('english.student_information'),
                    function ($sub) {
                        if (auth()->user()->can('student.view')) {
                            $sub->url(
                                action('StudentController@index'),
                                __('english.student_details'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'students']
                            );
                        }
                        if (auth()->user()->can('student.create')) {
                            $sub->url(
                                action('StudentController@create'),
                                __('english.add_new_admission'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'students' && request()->segment(2) == 'create']
                            );
                            $sub->url(
                                action('StudentController@bulkEdit'),
                                __('english.bulk_edit'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'students' && request()->segment(2) == 'bulk-edit' || request()->segment(2) == 'get-bulk-edit']
                            );
                        }
                        if (auth()->user()->can('student_category.view')) {
                            $sub->url(
                                action('CategoryController@index'),
                                __('english.student_category'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'categories']
                            );
                        }
                        if (auth()->user()->can('student_category.view')) {
                            $sub->url(
                                action('ImportStudentsController@index'),
                                __('english.import_students'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'import-students']
                            );
                        }
                    },
                    ['icon' => 'bx bx-user-plus']
                )->order(5);
            }
            if (auth()->user()->can('withdrawal.view')) {
                $menu->dropdown(
                    __('english.withdrawal_register'),
                    function ($sub) {
                        if (auth()->user()->can('withdrawal.view')) {
                            $sub->url(
                                action('Certificate\WithdrawalRegisterController@withdrawalStudent'),
                                __('english.withdrawal_students'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'withdrawal-students-list']
                            );

                            $sub->url(
                                action('Certificate\WithdrawalRegisterController@index'),
                                __('english.withdrawal_register'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'withdrawal_register']
                            );
                        }
                    },
                    ['icon' => 'bx bx-book']
                )->order(6);
            }
            //attendance
            if (
                auth()->user()->can('student_attendance.view') || auth()->user()->can('employee_attendance.view')
                || auth()->user()->can('student_attendance.qr_code_attendance') || auth()->user()->can('employee_attendance.qr_code_attendance')
            ) {
                $menu->dropdown(
                    __('english.attendance'),
                    function ($sub) {
                        if (auth()->user()->can('student_attendance.view')) {
                            $sub->url(
                                action('AttendanceController@index'),
                                __('english.student_attendance'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'attendance']
                            );
                        }
                        if (auth()->user()->can('employee_attendance.view')) {
                            $sub->url(
                                action('Hrm\HrmAttendanceController@index'),
                                __('english.employee_attendance'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-attendance']
                            );
                        }
                        if (auth()->user()->can('student_attendance.qr_code_attendance')) {
                            $sub->url(
                                action('Attendance\QrCodeAttendanceController@create'),
                                __('english.student') . ' ' . __('english.qr_code_attendance'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => '']
                            );
                        }
                        if (auth()->user()->can('employee_attendance.qr_code_attendance')) {
                            $sub->url(
                                action('Attendance\QrCodeAttendanceController@employee_create'),
                                __('english.employee') . ' ' . __('english.qr_code_attendance'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => '']
                            );
                        }
                    },
                    ['icon' => 'bx bx-calendar']
                )->order(7);
            }

            //Fee Collector
            if (auth()->user()->can('fee.add_fee_payment') || auth()->user()->can('fee.fee_transaction_view') || auth()->user()->can('fee.fee_transaction_create') || auth()->user()->can('fee_head.view')) {
                $menu->dropdown(
                    __('english.fees_collection'),
                    function ($sub) {
                        if (auth()->user()->can('fee.add_fee_payment')) {
                            $sub->url(
                                action('FeeTransactionPaymentController@feeReceipt'),
                                __('english.collect_fee'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee_receipt']
                            );
                            $sub->url(
                                action('IndividualFeeCollectionController@create'),
                                __('english.individual_fee_collect_with_detail'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-collection']
                            );
                            $sub->url(
                                action('FeeCollectionController@create'),
                                __('english.fee_collect_with_detail'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-collection']
                            );
                        }
                        if (auth()->user()->can('fee.fee_transaction_view')) {
                            $sub->url(
                                action('FeeAllocationController@index'),
                                __('english.fee_transactions'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-allocation']
                            );
                        }
                        if (auth()->user()->can('fee.fee_transaction_create')) {
                            $sub->url(
                                action('FeeAllocationController@create'),
                                __('english.fees_allocation'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-allocation' || request()->segment(1) == 'fees-assign-search' && request()->segment(2) == 'create']
                            );
                            $sub->url(
                                action('OtherFeeAllocationController@create'),
                                __('english.other') . ' ' . __('english.fees_allocation'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'other-fee' || request()->segment(1) == 'fees-assign-search' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('fee_head.view')) {
                            $sub->url(
                                action('FeeHeadController@index'),
                                __('english.fee_heads'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-heads']
                            );
                        }
                        if (auth()->user()->can('fee.increment_decrement')) {
                            $sub->url(
                                action('FeeIncrementController@index'),
                                __('english.fee_increment_decrement'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-increment']
                            );
                        }
                    },
                    ['icon' => 'bx bx-money']
                )->order(8);
            }
            if (auth()->user()->can('print.challan_print') || auth()->user()->can('print.student_card_print') || auth()->user()->can('print.employee_card_print') || auth()->user()->can('print.student_attendance_print') || auth()->user()->can('print.employee_attendance_print') || auth()->user()->can('print.student_particular') || auth()->user()->can('print.certificate')) {
                $menu->dropdown(
                    __('english.bulk_printing'),
                    function ($sub) {
                        if (auth()->user()->can('print.challan_print')) {
                            $sub->url(
                                action('SchoolPrinting\FeeCardPrintController@createClassWisePrint'),
                                __('english.challan_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'class-wise-fee-card-printing']
                            );
                        }
                        if (auth()->user()->can('print.student_card_print')) {
                            $sub->url(
                                action('SchoolPrinting\IdCardPrintController@createClassWiseIdPrint'),
                                __('english.student_card_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/create-class-wise-id-print']
                            );
                        }
                        if (auth()->user()->can('print.employee_card_print')) {
                            $sub->url(
                                action('SchoolPrinting\IdCardPrintController@createEmployeeIdPrint'),
                                __('english.employee_card_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/create-employee-id-print']
                            );
                        }
                        if (auth()->user()->can('print.student_attendance_print')) {
                            $sub->url(
                                action('Report\AttendanceReportController@index'),
                                __('english.students_attendance_report_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/report-attendance']
                            );
                        }
                        if (auth()->user()->can('print.employee_attendance_print')) {
                            $sub->url(
                                action('Report\AttendanceReportController@create'),
                                __('english.employee_attendance_report_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/report-attendance' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('print.student_particular')) {
                            $sub->url(
                                action('SchoolPrinting\StudentPrintController@index'),
                                __('english.student_particular'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/student-particular']
                            );
                        }
                        if (auth()->user()->can('print.certificate')) {
                            $sub->url(
                                action('Certificate\CertificateBulkPrintController@create'),
                                __('english.certificate'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/certificate_bulk_print']
                            );
                        }
                    },
                    ['icon' => 'bx bx-printer']
                )->order(9);
            }
            if (auth()->user()->can('class.view') || auth()->user()->can('section.view') || auth()->user()->can('subject.view') || auth()->user()->can('assign_subject.view')) {
                $menu->dropdown(
                    __('english.academic'),
                    function ($sub) {
                        if (auth()->user()->can('class.view')) {
                            $sub->url(
                                action('ClassController@index'),
                                __('class.classes'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'classes']
                            );
                        }
                        if (auth()->user()->can('section.view')) {
                            $sub->url(
                                action('ClassSectionController@index'),
                                __('class_section.sections'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'sections']
                            );
                        }
                        if (auth()->user()->can('subject.view')) {
                            $sub->url(
                                action('Curriculum\ClassSubjectController@index'),
                                __('english.class_subjects'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'curriculum-class-subject']
                            );
                        }
                        if (auth()->user()->can('assign_subject.view')) {
                            $sub->url(
                                action('Curriculum\AssignSubjectTeacherController@index'),
                                __('english.assign_subject_&_teacher_to_section'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'assign-subject']
                            );
                        }
                    },
                    ['icon' => 'bx bxs-institution']
                )->order(10);
            }
            if (auth()->user()->can('syllabus.view')) {
                $menu->dropdown(
                    __('english.manage_syllabus'),
                    function ($sub) {
                        if (auth()->user()->can('syllabus.view')) {
                            $sub->url(
                                action('Curriculum\SyllabusMangerController@index'),
                                __('english.syllabus_and_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'syllabus']
                            );
                        }
                    },
                    ['icon' => 'fa fa-list-alt ftlayer']
                )->order(11);
            }
            if (auth()->user()->can('study_period.view') || auth()->user()->can('class_routine.view')) {
                $menu->dropdown(
                    __('english.class_routine'),
                    function ($sub) {
                        if (auth()->user()->can('study_period.view')) {
                            $sub->url(
                                action('Curriculum\ClassTimeTablePeriodController@index'),
                                __('english.study_period'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'class-time-table-period']
                            );
                        }
                        if (auth()->user()->can('class_routine.view')) {
                            $sub->url(
                                action('Curriculum\ClassTimeTableController@index'),
                                __('english.classes_time_table'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'class-time-table']
                            );
                        }
                    },
                    ['icon' => 'bx bxs-time']
                )->order(12);
            }
            if (auth()->user()->can('manual_paper.view')) {
                $menu->dropdown(
                    __('english.paper_maker'),
                    function ($sub) {
                        if (auth()->user()->can('manual_paper.view')) {
                            $sub->url(
                                action('Curriculum\PaperMakerController@manualPaperCreate'),
                                __('english.manual_paper'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'manual_paper']
                            );
                        }
                    },
                    ['icon' => 'lni lni-remove-file']
                )->order(13);
            }
            if (auth()->user()->can('grade.view') || auth()->user()->can('exam_term.view') || auth()->user()->can('exam_date_sheet.view') || auth()->user()->can('roll_no_slip.print') || auth()->user()->can('exam_setup.view') || auth()->user()->can('exam_award_list_attendance.print') || auth()->user()->can('exam_mark_entry.create') || auth()->user()->can('mark_entry_print.print') || auth()->user()->can('exam_result.print')) {
                $menu->dropdown(
                    __('english.examination'),
                    function ($sub) {
                        if (auth()->user()->can('grade.view')) {
                            $sub->url(
                                action('Examination\GradeController@index'),
                                __('english.grade'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'grades']
                            );
                        }
                        if (auth()->user()->can('exam_term.view')) {
                            $sub->url(
                                action('Examination\TermController@index'),
                                __('english.exam_term'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'term']
                            );
                        }
                        if (auth()->user()->can('exam_date_sheet.view')) {
                            $sub->url(
                                action('Examination\ExamDateSheetController@index'),
                                __('english.date_sheet'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'date_sheets']
                            );
                            $sub->url(
                                action('Examination\ExamDateSheetController@bulkCreate'),
                                __('english.bulk_date_sheet'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'date_sheets_bulk_create']
                            );
                        }
                        if (auth()->user()->can('roll_no_slip.print')) {
                            $sub->url(
                                action('Examination\ExamDateSheetController@createClassWiseRollSlipPrint'),
                                __('english.roll_no_slip_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'roll_no_slip']
                            );
                        }
                        if (auth()->user()->can('exam_setup.view')) {
                            $sub->url(
                                action('Examination\ExamSetupController@index'),
                                __('english.exam_setup'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'setup']
                            );
                        }
                        if (auth()->user()->can('exam_award_list_attendance.print')) {
                            $sub->url(
                                action('Examination\AwardAttendanceController@index'),
                                __('english.award_attendance_list_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'award-attendance']
                            );
                        }
                        if (auth()->user()->can('exam_mark_entry.create')) {
                            $sub->url(
                                action('Examination\ExamMarkController@create'),
                                __('english.mark_entry'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'mark-entry' || request()->segment(2) == 'mark']
                            );
                        }
                        if (auth()->user()->can('mark_entry_print.print')) {
                            $sub->url(
                                action('Examination\ExamMarkController@index'),
                                __('english.mark_entry_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'exam' || request()->segment(2) == 'mark']
                            );
                        }

                        if (auth()->user()->can('exam_result.print')) {
                            $sub->url(
                                action('Examination\ExamResultController@index'),
                                __('english.result'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'result' || request()->segment(2) == 'result']
                            );


                            $sub->url(
                                action('Examination\ExamResultController@create'),
                                __('english.result_card'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'result' && request()->segment(3) == 'create']
                            );

                            $sub->url(
                                action('Examination\TabulationController@index'),
                                __('english.tabulation_sheet'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'tabulation_sheet']
                            );
                            $sub->url(
                                action('Examination\TabulationController@create'),
                                __('english.tabulation_sheet_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'tabulation_sheet' && request()->segment(2) == 'create']
                            );
                            $sub->url(
                                action('Examination\ExamResultController@topPositionsCreate'),
                                __('english.top_ten_students'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'top-positions-print']
                            );
                            $sub->url(
                                action('Examination\ExamResultController@topPositionListCreate'),
                                __('english.top_position_list'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'position-list']
                            );
                        }
                    },
                    ['icon' => 'bx bxs-graduation']
                )->order(14);
            }
            if (auth()->user()->can('routine_test') || auth()->user()->can('routine_test') || auth()->user()->can('routine_test')) {
                $menu->dropdown(
                    __('english.routine_test'),
                    function ($sub) {
                        if (auth()->user()->can('routine_test')) {
                            $sub->url(
                                action('Examination\RoutineTestController@create'),
                                __('english.mark_entry'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'exam' && request()->segment(2) == 'routine-test' && request()->segment(3) == 'create']
                            );
                        }
                        if (auth()->user()->can('promotion.without_exam')) {
                            $sub->url(
                                action('Examination\RoutineTestController@index'),
                                __('english.routine_test_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'exam' || request()->segment(1) == 'routine-test']
                            );
                        }
                        if (auth()->user()->can('promotion.without_exam')) {
                            $sub->url(
                                action('Examination\RoutineTestController@studentCreate'),
                                __('english.routine_report'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'exam' || request()->segment(1) == 'routine-test-print']
                            );
                        }

                    },

                    ['icon' => 'bx bx-check']
                )->order(15);
            }
            if (auth()->user()->can('promotion.with_exam') || auth()->user()->can('promotion.without_exam') || auth()->user()->can('promotion.pass_out')) {
                $menu->dropdown(
                    __('english.promotion'),
                    function ($sub) {
                        if (auth()->user()->can('promotion.with_exam')) {
                            $sub->url(
                                action('Examination\PromotionController@create'),
                                __('english.promotion'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'promotion' && request()->segment(2) == 'create' || request()->segment(1) == 'promotion-student']
                            );
                        }
                        if (auth()->user()->can('promotion.without_exam')) {
                            $sub->url(
                                action('Examination\PromotionController@index'),
                                __('english.promotion_without_exam'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'promotion' || request()->segment(1) == 'with-out-exam-promotion']
                            );
                        }
                        if (auth()->user()->can('promotion.pass_out')) {
                            $sub->url(
                                action('Examination\PromotionController@passOutCreate'),
                                __('english.pass_out'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'pass-out-create' || request()->segment(1) == 'pass-out-post']
                            );
                        }
                    },
                    ['icon' => 'bx bx-briefcase-alt-2']
                )->order(15);
            }
            if (auth()->user()->can('certificate.issue') || auth()->user()->can('certificate.print')) {
                $menu->dropdown(
                    __('english.certificate'),
                    function ($sub) {
                        // $sub->url(
                        //     action('Certificate\CertificateTypeController@index'),
                        //     __('english.certificate-type'),
                        //     ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'certificate-type']
                        // );
                        if (auth()->user()->can('certificate.issue')) {
                            $sub->url(
                                action('Certificate\CertificateController@create'),
                                __('english.certificate_issue'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'certificate-issue' || request()->segment(1) == 'issuePost']
                            );
                        }
                        if (auth()->user()->can('certificate.print')) {
                            $sub->url(
                                action('Certificate\CertificatePrintController@index'),
                                __('english.certificate_print'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'certificate-print']
                            );
                        }
                    },
                    ['icon' => 'lni lni-certificate']
                )->order(16);
            }
            if (
                auth()->user()->can('employee.view') || auth()->user()->can('employee.create') || auth()->user()->can('shift.view') || auth()->user()->can('department.create') || auth()->user()->can('designation.view') || auth()->user()->can('deduction.view') ||
                auth()->user()->can('education.view') || auth()->user()->can('allowance.view') || auth()->user()->can('payroll.view') || auth()->user()->can('payroll.print')
                || auth()->user()->can('employee.print')
            ) {
                $menu->dropdown(
                    __('english.hrm'),
                    function ($sub) {
                        if (auth()->user()->can('employee.view')) {
                            $sub->url(
                                action('Hrm\HrmEmployeeController@index'),
                                __('english.employees'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-employee']
                            );
                        }
                        if (auth()->user()->can('employee.create')) {
                            $sub->url(
                                action('Hrm\HrmEmployeeController@create'),
                                __('english.add_new_employee'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-employee' && request()->segment(2) == 'create']
                            );
                        }
                        if (auth()->user()->can('shift.view')) {
                            $sub->url(
                                action('Hrm\HrmShiftController@index'),
                                __('english.shift'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-shift']
                            );
                        }
                        if (auth()->user()->can('department.create')) {
                            $sub->url(
                                action('Hrm\HrmDepartmentController@index'),
                                __('english.departments'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-department']
                            );
                        }
                        if (auth()->user()->can('designation.view')) {
                            $sub->url(
                                action('Hrm\HrmDesignationController@index'),
                                __('english.designations'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-designation']
                            );
                        }
                        if (auth()->user()->can('deduction.view')) {
                            $sub->url(
                                action('Hrm\HrmDeductionController@index'),
                                __('english.deductions'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-deduction']
                            );
                        }
                        if (auth()->user()->can('education.view')) {
                            $sub->url(
                                action('Hrm\HrmEducationController@index'),
                                __('english.educations'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-education']
                            );
                        }

                        if (auth()->user()->can('allowance.view')) {
                            $sub->url(
                                action('Hrm\HrmAllowanceController@index'),
                                __('english.allowance'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-allowance']
                            );
                        }
                        if (auth()->user()->can('payroll.view')) {
                            $sub->url(
                                action('Hrm\HrmPayrollController@index'),
                                __('english.pay_roll'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-payroll']
                            );
                        }
                        if (auth()->user()->can('payroll.print')) {
                            $sub->url(
                                action('Hrm\HrmPrintController@create'),
                                __('english.payroll_print'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-print']
                            );
                        }
                        if (auth()->user()->can('employee.print')) {
                            $sub->url(
                                action('Hrm\HrmPrintController@employeeListPrintCreate'),
                                __('english.employee_list_print'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'employee-list-print']
                            );
                        }
                    },
                    ['icon' => 'bx bx-group']
                )->order(17);
            }
            if (auth()->user()->can('expense_category.view') || auth()->user()->can('expense.view') || auth()->user()->can('expense.create')) {
                $menu->dropdown(
                    __('english.expense'),
                    function ($sub) {
                        if (auth()->user()->can('expense_category.view')) {
                            $sub->url(
                                action('ExpenseCategoryController@index'),
                                __('english.expense_categories'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'expense-categories']
                            );
                        }
                        if (auth()->user()->can('expense.view')) {
                            $sub->url(
                                action('ExpenseTransactionController@index'),
                                __('english.expenses_list'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'expenses']
                            );
                        }
                        if (auth()->user()->can('expense.create')) {
                            $sub->url(
                                action('ExpenseTransactionController@create'),
                                __('english.add_expense'),
                                ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == 'create']
                            );
                        }
                    },
                    ['icon' => 'bx bx-minus']
                )->order(18);
            }
            //Accounts dropdown
            if (auth()->user()->can('account.access')) {
                $menu->dropdown(
                    __('english.payment_accounts'),
                    function ($sub) {
                        $sub->url(
                            action('AccountController@index'),
                            __('english.list_accounts'),
                            ['icon' => 'bx bx-list-ul', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'account']
                        );
                        $sub->url(
                            action('AccountReportsController@balanceSheet'),
                            __('english.balance_sheet'),
                            ['icon' => 'fa fas fa-book', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'balance-sheet']
                        );
                        $sub->url(
                            action('AccountReportsController@trialBalance'),
                            __('english.trial_balance'),
                            ['icon' => 'fa fas fa-balance-scale', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'trial-balance']
                        );
                        // $sub->url(
                        //     action('AccountController@cashFlow'),
                        //     __('english.cash_flow'),
                        //     ['icon' => 'fa fas fa-exchange-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'cash-flow']
                        // );
                        // $sub->url(
                        //     action('AccountReportsController@paymentAccountReport'),
                        //     __('english.payment_account_report'),
                        //     ['icon' => 'fa fas fa-file-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'payment-account-report']
                        //);
                    },
                    ['icon' => 'bx bx-money']
                )->order(19);
            }
            if (auth()->user()->can('class_report.view') || auth()->user()->can('strength_report.view') || auth()->user()->can('income_report.view')) {
                $menu->dropdown(
                    __('english.report'),
                    function ($sub) {
                        if (auth()->user()->can('class_report.view')) {
                            $sub->url(
                                action('Report\ClassReportController@index'),
                                __('english.class_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'class-report']
                            );
                        }
                        if (auth()->user()->can('strength_report.view')) {
                            $sub->url(
                                action('Report\StrengthController@index'),
                                __('english.strength_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(2) == 'strength']
                            );
                        }
                        if (auth()->user()->can('strength_report.view')) {
                            $sub->url(
                                action('Report\ReportController@getExpenseReport'),
                                __('english.expense_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(2) == 'expense-report']
                            );
                        }
                        if (auth()->user()->can('strength_report.view')) {
                            $sub->url(
                                action('Report\TopDefaulterController@index'),
                                __('english.top_defaulters'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(2) == 'top-defaulter']
                            );
                        }
                        if (auth()->user()->can('income_report.view')) {
                            $sub->url(
                                action('Report\IncomeReportController@index'),
                                __('english.income_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'income-report']
                            );
                        }
                    },
                    ['icon' => 'fadeIn animated bx bx-bar-chart']
                )->order(20);
            }
            if (auth()->user()->can('vehicle.view')) {
                $menu->dropdown(
                    __('english.transport'),
                    function ($sub) {
                        if (auth()->user()->can('vehicle.view')) {
                            $sub->url(
                                action('VehicleController@index'),
                                __('english.vehicle'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'vehicles']
                            );
                        }
                    },
                    ['icon' => 'bx bx-bus-school']
                )->order(21);
            }
            if (auth()->user()->can('note_books_status.view')) {
                $menu->dropdown(
                    __('english.note_books_status'),
                    function ($sub) {
                        $sub->url(
                            action('NoteBookStatusController@create'),
                            __('english.add'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'note-book' && request()->segment(2) == 'create']
                        );
                        $sub->url(
                            action('NoteBookStatusController@noteBookEmptyPrintCreate'),
                            __('english.empty_form'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'note-print-empty-create']
                        );
                        $sub->url(
                            action('NoteBookStatusController@index'),
                            __('english.report'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'note-book']
                        );
                    },
                    ['icon' => 'fadeIn animated bx bx-book-alt']
                )->order(22);
            }
            if (auth()->user()->can('notification.lesson_send_to_students') || auth()->user()->can('notification.weekend_and_holiday')) {
                $menu->dropdown(
                    __('english.notifications'),
                    function ($sub) {
                        if (auth()->user()->can('notification.lesson_send_to_students')) {
                            $sub->url(
                                action('NotificationController@lessonProgressSendCreate'),
                                __('english.lesson_send_to_students'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'lesson_progress_send']
                            );
                        }
                        if (auth()->user()->can('notification.weekend_and_holiday')) {
                            $sub->url(
                                action('WeekendHolidayController@index'),
                                __('english.weekend_and_holiday'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'weekend-holiday']
                            );
                        
                            $sub->url(
                                action('GeneralSmsController@create'),
                                __('english.general_sms'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'general-sms']
                            );
                          
                        }
                        if (auth()->user()->can('notification.weekend_and_holiday')) {
                            $sub->url(
                                action('NotificationController@FeeRemainderCreate'),
                                __('english.fee_remainder'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee_remainder']
                            );
                        }
                        $sub->url(
                            action('WhatsAppController@index'),
                            __('Whatsapp Sms '),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'sms-logs']
                        );
                    },
                    ['icon' => 'bx bxs-bell']
                )->order(23);
            }
            if (auth()->user()->can('leave_applications_for_employee.view')) {
                $menu->dropdown(
                    __('english.leave_application'),
                    function ($sub) {
                        if (auth()->user()->can('leave_applications_for_employee.view')) {
                            $sub->url(
                                action('LeaveApplicationEmployeeController@index'),
                                __('english.leave_applications_for_employee'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-leave_applications']
                            );
                        }
                        if (auth()->user()->can('leave_applications_for_student.view')) {
                            $sub->url(
                                action('LeaveApplicationStudentController@index'),
                                __('english.leave_applications_for_student'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'leave_application_students']
                            );
                        }
                    },
                    ['icon' => 'bx bx-envelope']
                )->order(24);
            }
            $menu->dropdown(
                __('english.frontend'),
                function ($sub) {
                    if (auth()->user()->can('leave_applications_for_employee.view')) {
                        $sub->url(
                            action('Frontend\FrontGalleryController@index'),
                            __('english.gallery'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'galleries']
                        );
                        $sub->url(
                            action('Frontend\FrontSliderController@index'),
                            __('english.slider'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-sliders']
                        );
                        $sub->url(
                            action('Frontend\FrontAboutController@index'),
                            __('english.about_page'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-abouts']
                        );
                        $sub->url(
                            action('Frontend\FrontEventController@index'),
                            __('english.event'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-events']
                        );
                        $sub->url(
                            action('Frontend\FrontNoticeController@index'),
                            __('english.notice'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-notices']
                        );
                        $sub->url(
                            action('Frontend\FrontNewsController@index'),
                            __('english.news'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-news']
                        );
                        $sub->url(
                            action('Frontend\FrontSettingController@index'),
                            __('english.settings'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-settings']
                        );
                    }
                },
                ['icon' => 'bx bx-globe']
            )->order(24);
        });
    }


    private function teacherSidebar()
    {
        Menu::create('admin-sidebar-menu', function ($menu) {
            $menu->header('MAIN MENU');
            $this->global($menu);
            $menu->url(action('TeacherLayout\TeacherDashboardController@index'), __('english.dashboard'), ['icon' => 'bx bx-home', 'active' => request()->segment(1) == 'dashboard'])->order(1);
            $menu->url(action('Hrm\HrmEmployeeController@employeeProfile', [\Auth::user()->hook_id]), __('english.profile'), ['icon' => 'bx bx-user', 'active' => request()->segment(1) == 'employee-profile'])->order(2);
            $menu->dropdown(
                __('english.examination'),
                function ($sub) {
                    $sub->url(
                        action('TeacherLayout\TeacherDashboardController@teacherSubjectMarkEntry'),
                        __('english.mark_entry'),
                        ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'teacher_mark_entry']
                    );
                    $sub->url(
                        action('TeacherLayout\TeacherDashboardController@teacher_subject_marks_print'),
                        __('english.mark_entry_print'),
                        ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'teacher_subject_marks_print']
                    );
                },
                ['icon' => 'bx bxs-graduation']
            )->order(3);
            if (auth()->user()->can('leave_applications_for_employee.view')) {
                $menu->url(action('LeaveApplicationEmployeeController@index'), __('english.leave_application'), ['icon' => 'bx bx-envelope', 'active' => request()->segment(1) == 'hrm-leave_applications'])->order(4);
            }
        });

    }

    private function studentSidebar()
    {
        Menu::create('admin-sidebar-menu', function ($menu) {
            $menu->header('MAIN MENU');
            $this->global($menu);
            $menu->url(action('StudentLayout\StudentDashboardController@index'), __('english.dashboard'), ['icon' => 'bx bx-home', 'active' => request()->segment(2) == 'dashboard'])->order(1);
            $menu->url(action('StudentController@studentProfile', [\Auth::user()->hook_id]), __('english.profile'), ['icon' => 'bx bx-user', 'active' => request()->segment(1) == 'student-profile'])->order(2);
            if (auth()->user()->can('leave_applications_for_student.view')) {
                $menu->url(action('LeaveApplicationStudentController@index'), __('english.leave_application'), ['icon' => 'bx bx-envelope', 'active' => request()->segment(1) == 'leave_application_students'])->order(3);
            }
        });
    }
    private function guardianSidebar()
    {
        Menu::create('admin-sidebar-menu', function ($menu) {
            $menu->header('MAIN MENU');
            $this->global($menu);
            $menu->url(action('GuardianLayout\GuardianDashboardController@index'), __('english.dashboard'), ['icon' => 'bx bx-home', 'active' => request()->segment(2) == 'dashboard'])->order(1);
            if (auth()->user()->can('leave_applications_for_student.view')) {
                $menu->url(action('LeaveApplicationStudentController@index'), __('english.leave_application'), ['icon' => 'bx bx-envelope', 'active' => request()->segment(1) == 'leave_application_students'])->order(3);
            }
        });
    }
    private function staffSidebar()
    {
        Menu::create('admin-sidebar-menu', function ($menu) {
            $menu->header('MAIN MENU');
            $menu->url(action('StaffLayout\StaffDashboardController@index'), __('english.dashboard'), ['icon' => 'bx bx-home', 'active' => request()->segment(2) == 'dashboard'])->order(1);
            $menu->url(action('Hrm\HrmEmployeeController@employeeProfile', [\Auth::user()->hook_id]), __('english.profile'), ['icon' => 'bx bx-user', 'active' => request()->segment(1) == 'employee-profile'])->order(2);
            if (auth()->user()->can('leave_applications_for_employee.view')) {
                $menu->url(action('LeaveApplicationEmployeeController@index'), __('english.leave_application'), ['icon' => 'bx bx-envelope', 'active' => request()->segment(1) == 'hrm-leave_applications'])->order(3);
            }
        });
    }

    private function global ($menu)
    {
        $menu->dropdown(
            'Digital Library',
            function ($sub) {
                $sub->url(
                    action('Curriculum\MyLibraryController@index'),
                    __('My Library'),
                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) === 'my-library']
                );
                $sub->url(
                    action('IframeSites\WebSiteController@index'),
                    __('E Pustakalaya'),
                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'digital-library']
                );
                $sub->url(
                    'https://learning.cehrd.edu.np/home',
                    __('Learning Cehrd'),
                    ['icon' => 'bx bx-right-arrow-alt', 'target' => '_blank', 'active' => request()->segment(1) == 'digital-library' && request()->segment(2) == 'create']
                );
            },
            ['icon' => 'fadeIn animated bx bx-book-reader']
        )->order(0);
    }

    private function adminSidebar2()
    {
        Menu::create('admin-sidebar-menu', function ($menu) {
            // active mm-active $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

            $is_admin = auth()->user()->hasRole('Admin#' . session('system_details.id')) ? true : false;
            //Home

            $menu->header('MAIN MENU');
            $menu->url(action('HomeController@index'), __('english.dashboard'), ['icon' => 'bx bx-home', 'active' => request()->segment(1) == 'home'])->order(0);


            $menu->dropdown(
                'Administration',
                function ($sub) {
                    if (auth()->user()->can('campus.view')) {
                        $sub->url(action('CampusController@index'), __('english.campuses'), ['icon' => 'bx bx-buildings', 'active' => request()->segment(1) == 'campuses'])->order(3);
                    }
                    if (auth()->user()->can('general_settings.view') || auth()->user()->can('session.view') || auth()->user()->can('roles.view') || auth()->user()->can('award.view') || auth()->user()->can('class_level.view') || auth()->user()->can('province.view') || auth()->user()->can('district.view') || auth()->user()->can('city.view') || auth()->user()->can('region.view')) {
                        $sub->dropdown(
                            __('english.global_settings'),
                            function ($sub) {
                                if (auth()->user()->can('general_settings.view')) {
                                    $sub->url(
                                        action('SystemSettingController@index'),
                                        __('english.general_setting'),
                                        ['icon' => 'bx bx-cog ', 'active' => request()->segment(1) == 'setting']
                                    );
                                }
                                if (auth()->user()->can('session.view') || auth()->user()->can('session.create')) {
                                    $sub->url(
                                        action('SessionController@index'),
                                        __('session.sessions'),
                                        ['icon' => 'bx bx-calendar ', 'active' => request()->segment(1) == 'session']
                                    );
                                }
                                if (auth()->user()->can('roles.view')) {
                                    $sub->url(
                                        action('RoleController@index'),
                                        __('english.roles'),
                                        ['icon' => 'fa fas fa-briefcase', 'active' => request()->segment(1) == 'roles']
                                    );
                                }
        
                                // $sub->url(
                                //     action('DesignationController@index'),
                                //     __('designation.designations'),
                                //     ['icon' => 'bx bx-shape-circle ', 'active' => request()->segment(1) == 'designation']
                                // );
                                // $sub->url(
                                //     action('DiscountController@index'),
                                //     __('discount.discounts'),
                                //     ['icon' => 'bx bx-disc ', 'active' => request()->segment(1) == 'discounts']
                                // );
                                if (auth()->user()->can('award.view')) {
                                    $sub->url(
                                        action('AwardController@index'),
                                        __('award.awards'),
                                        ['icon' => 'bx bx-award ', 'active' => request()->segment(1) == 'awards']
                                    );
                                }
                                if (auth()->user()->can('class_level.view')) {
                                    $sub->url(
                                        action('ClassLevelController@index'),
                                        __('class_level.class_level'),
                                        ['icon' => 'bx bx-menu ', 'active' => request()->segment(1) == 'class_levels']
                                    );
                                }
                                if (auth()->user()->can('province.view')) {
                                    $sub->url(
                                        action('ProvinceController@index'),
                                        __('english.provinces'),
                                        ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'provinces']
                                    );
                                }
                                if (auth()->user()->can('district.view')) {
                                    $sub->url(
                                        action('DistrictController@index'),
                                        __('english.districts'),
                                        ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'districts']
                                    );
                                }
                                if (auth()->user()->can('city.view')) {
                                    $sub->url(
                                        action('CityController@index'),
                                        __('english.cities'),
                                        ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'cities']
                                    );
                                }
                                if (auth()->user()->can('region.view')) {
                                    $sub->url(
                                        action('RegionController@index'),
                                        __('english.regions'),
                                        ['icon' => 'bx bx-cabinet ', 'active' => request()->segment(1) == 'regions']
                                    );
                                }
                            },
                            ['icon' => 'bx bx-globe']
                        )->order(4);
                    }
                    if (
                        auth()->user()->can('student_attendance.view') || auth()->user()->can('employee_attendance.view')
                        || auth()->user()->can('student_attendance.qr_code_attendance') || auth()->user()->can('employee_attendance.qr_code_attendance')
                    ) {
                        $sub->dropdown(
                            __('english.attendance'),
                            function ($sub) {
                                    if (auth()->user()->can('student_attendance.view')) {
                                        $sub->url(
                                            action('AttendanceController@index'),
                                            __('english.student_attendance'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'attendance']
                                        );
                                    }
                                    if (auth()->user()->can('employee_attendance.view')) {
                                        $sub->url(
                                            action('Hrm\HrmAttendanceController@index'),
                                            __('english.employee_attendance'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-attendance']
                                        );
                                    }
                                    if (auth()->user()->can('student_attendance.qr_code_attendance')) {
                                        $sub->url(
                                            action('Attendance\QrCodeAttendanceController@create'),
                                            __('english.student') . ' ' . __('english.qr_code_attendance'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => '']
                                        );
                                    }
                                    if (auth()->user()->can('employee_attendance.qr_code_attendance')) {
                                        $sub->url(
                                            action('Attendance\QrCodeAttendanceController@employee_create'),
                                            __('english.employee') . ' ' . __('english.qr_code_attendance'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => '']
                                        );
                                    }
                                },
                            ['icon' => 'bx bx-calendar']
                        )->order(1);
                    }


                    if (auth()->user()->can('leave_applications_for_employee.view')) {
                        $sub->dropdown(
                            __('english.leave_application'),
                            function ($sub) {
                                    if (auth()->user()->can('leave_applications_for_employee.view')) {
                                        $sub->url(
                                            action('LeaveApplicationEmployeeController@index'),
                                            __('english.leave_applications_for_employee'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-leave_applications']
                                        );
                                    }
                                    if (auth()->user()->can('leave_applications_for_student.view')) {
                                        $sub->url(
                                            action('LeaveApplicationStudentController@index'),
                                            __('english.leave_applications_for_student'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'leave_application_students']
                                        );
                                    }
                                },
                            ['icon' => 'bx bx-envelope']
                        )->order(2);
                    }
                    if (
                        auth()->user()->can('employee.view') || auth()->user()->can('employee.create') || auth()->user()->can('shift.view') || auth()->user()->can('department.create') || auth()->user()->can('designation.view') || auth()->user()->can('deduction.view') ||
                        auth()->user()->can('education.view') || auth()->user()->can('allowance.view') || auth()->user()->can('payroll.view') || auth()->user()->can('payroll.print')
                        || auth()->user()->can('employee.print')
                    ) {
                        $sub->dropdown(
                            __('english.hrm'),
                            function ($sub) {
                                    if (auth()->user()->can('employee.view')) {
                                        $sub->url(
                                            action('Hrm\HrmEmployeeController@index'),
                                            __('english.employees'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-employee']
                                        );
                                    }
                                    if (auth()->user()->can('employee.create')) {
                                        $sub->url(
                                            action('Hrm\HrmEmployeeController@create'),
                                            __('english.add_new_employee'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-employee' && request()->segment(2) == 'create']
                                        );
                                    }
                                    if (auth()->user()->can('shift.view')) {
                                        $sub->url(
                                            action('Hrm\HrmShiftController@index'),
                                            __('english.shift'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-shift']
                                        );
                                    }
                                    if (auth()->user()->can('department.create')) {
                                        $sub->url(
                                            action('Hrm\HrmDepartmentController@index'),
                                            __('english.departments'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-department']
                                        );
                                    }
                                    if (auth()->user()->can('designation.view')) {
                                        $sub->url(
                                            action('Hrm\HrmDesignationController@index'),
                                            __('english.designations'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-designation']
                                        );
                                    }
                                    if (auth()->user()->can('deduction.view')) {
                                        $sub->url(
                                            action('Hrm\HrmDeductionController@index'),
                                            __('english.deductions'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-deduction']
                                        );
                                    }
                                    if (auth()->user()->can('education.view')) {
                                        $sub->url(
                                            action('Hrm\HrmEducationController@index'),
                                            __('english.educations'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-education']
                                        );
                                    }

                                    if (auth()->user()->can('allowance.view')) {
                                        $sub->url(
                                            action('Hrm\HrmAllowanceController@index'),
                                            __('english.allowance'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-allowance']
                                        );
                                    }
                                    if (auth()->user()->can('payroll.view')) {
                                        $sub->url(
                                            action('Hrm\HrmPayrollController@index'),
                                            __('english.pay_roll'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-payroll']
                                        );
                                    }
                                    if (auth()->user()->can('payroll.print')) {
                                        $sub->url(
                                            action('Hrm\HrmPrintController@create'),
                                            __('english.payroll_print'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'hrm-print']
                                        );
                                    }
                                    if (auth()->user()->can('employee.print')) {
                                        $sub->url(
                                            action('Hrm\HrmPrintController@employeeListPrintCreate'),
                                            __('english.employee_list_print'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'employee-list-print']
                                        );
                                    }
                                },
                            ['icon' => 'bx bx-group']
                        )->order(3);
                    }
                    if (auth()->user()->can('student.view') || auth()->user()->can('student.create') || auth()->user()->can('student_category.view')) {
                        $sub->dropdown(
                            __('english.student_information'),
                            function ($sub) {
                                    if (auth()->user()->can('student.view')) {
                                        $sub->url(
                                            action('StudentController@index'),
                                            __('english.student_details'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'students']
                                        );
                                    }
                                    if (auth()->user()->can('student.create')) {
                                        $sub->url(
                                            action('StudentController@create'),
                                            __('english.add_new_admission'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'students' && request()->segment(2) == 'create']
                                        );
                                        $sub->url(
                                            action('StudentController@bulkEdit'),
                                            __('english.bulk_edit'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'students' && request()->segment(2) == 'bulk-edit' || request()->segment(2) == 'get-bulk-edit']
                                        );
                                    }
                                    if (auth()->user()->can('student_category.view')) {
                                        $sub->url(
                                            action('CategoryController@index'),
                                            __('english.student_category'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'categories']
                                        );
                                    }
                                    if (auth()->user()->can('student_category.view')) {
                                        $sub->url(
                                            action('ImportStudentsController@index'),
                                            __('english.import_students'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'import-students']
                                        );
                                    }
                                },
                            ['icon' => 'bx bx-user-plus']
                        )->order(4);
                    }
                    if (auth()->user()->can('withdrawal.view')) {
                        $sub->dropdown(
                            __('english.withdrawal_register'),
                            function ($sub) {
                                    if (auth()->user()->can('withdrawal.view')) {
                                        $sub->url(
                                            action('Certificate\WithdrawalRegisterController@withdrawalStudent'),
                                            __('english.withdrawal_students'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'withdrawal-students-list']
                                        );

                                        $sub->url(
                                            action('Certificate\WithdrawalRegisterController@index'),
                                            __('english.withdrawal_register'),
                                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'withdrawal_register']
                                        );
                                    }
                                },
                            ['icon' => 'bx bx-book']
                        )->order(5);
                    }
                    ///////
                    if (auth()->user()->can('certificate.issue') || auth()->user()->can('certificate.print')) {
                        $sub->dropdown(
                            __('english.certificate'),
                            function ($sub) {
                                    // $sub->url(
                                    //     action('Certificate\CertificateTypeController@index'),
                                    //     __('english.certificate-type'),
                                    //     ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'certificate-type']
                                    // );
                                    if (auth()->user()->can('certificate.issue')) {
                                        $sub->url(
                                            action('Certificate\CertificateController@create'),
                                            __('english.certificate_issue'),
                                            ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'certificate-issue' || request()->segment(1) == 'issuePost']
                                        );
                                    }
                                    if (auth()->user()->can('certificate.print')) {
                                        $sub->url(
                                            action('Certificate\CertificatePrintController@index'),
                                            __('english.certificate_print'),
                                            ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'certificate-print']
                                        );
                                    }
                                },
                            ['icon' => 'lni lni-certificate']
                        )->order(6);
                    }

                },
                ['icon' => 'fadeIn animated bx bx-user-pin']
            );

            $menu->dropdown('Accounts', function ($sub) {
                //Fee Collector
                if (auth()->user()->can('fee.add_fee_payment') || auth()->user()->can('fee.fee_transaction_view') || auth()->user()->can('fee.fee_transaction_create') || auth()->user()->can('fee_head.view')) {
                    $sub->dropdown(
                        __('english.fees_collection'),
                        function ($sub) {
                            if (auth()->user()->can('fee.add_fee_payment')) {
                                $sub->url(
                                    action('FeeTransactionPaymentController@feeReceipt'),
                                    __('english.collect_fee'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee_receipt']
                                );
                                $sub->url(
                                    action('IndividualFeeCollectionController@create'),
                                    __('english.individual_fee_collect_with_detail'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-collection']
                                );
                                $sub->url(
                                    action('FeeCollectionController@create'),
                                    __('english.fee_collect_with_detail'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-collection']
                                );
                            }
                            if (auth()->user()->can('fee.fee_transaction_view')) {
                                $sub->url(
                                    action('FeeAllocationController@index'),
                                    __('english.fee_transactions'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-allocation']
                                );
                            }
                            if (auth()->user()->can('fee.fee_transaction_create')) {
                                $sub->url(
                                    action('FeeAllocationController@create'),
                                    __('english.fees_allocation'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-allocation' || request()->segment(1) == 'fees-assign-search' && request()->segment(2) == 'create']
                                );
                                $sub->url(
                                    action('OtherFeeAllocationController@create'),
                                    __('english.other') . ' ' . __('english.fees_allocation'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'other-fee' || request()->segment(1) == 'fees-assign-search' && request()->segment(2) == 'create']
                                );
                            }
                            if (auth()->user()->can('fee_head.view')) {
                                $sub->url(
                                    action('FeeHeadController@index'),
                                    __('english.fee_heads'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-heads']
                                );
                            }
                            if (auth()->user()->can('fee.increment_decrement')) {
                                $sub->url(
                                    action('FeeIncrementController@index'),
                                    __('english.fee_increment_decrement'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee-increment']
                                );
                            }
                        },
                        ['icon' => 'bx bx-money']
                    )->order(8);
                }

                if (auth()->user()->can('expense_category.view') || auth()->user()->can('expense.view') || auth()->user()->can('expense.create')) {
                    $sub->dropdown(
                        __('english.expense'),
                        function ($sub) {
                            if (auth()->user()->can('expense_category.view')) {
                                $sub->url(
                                    action('ExpenseCategoryController@index'),
                                    __('english.expense_categories'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'expense-categories']
                                );
                            }
                            if (auth()->user()->can('expense.view')) {
                                $sub->url(
                                    action('ExpenseTransactionController@index'),
                                    __('english.expenses_list'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'expenses']
                                );
                            }
                            if (auth()->user()->can('expense.create')) {
                                $sub->url(
                                    action('ExpenseTransactionController@create'),
                                    __('english.add_expense'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'expenses' && request()->segment(2) == 'create']
                                );
                            }
                        },
                        ['icon' => 'bx bx-minus']
                    )->order(18);
                }
                //Accounts dropdown
                if (auth()->user()->can('account.access')) {
                    $sub->dropdown(
                        __('english.payment_accounts'),
                        function ($sub) {
                            $sub->url(
                                action('AccountController@index'),
                                __('english.list_accounts'),
                                ['icon' => 'bx bx-list-ul', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'account']
                            );
                            $sub->url(
                                action('AccountReportsController@balanceSheet'),
                                __('english.balance_sheet'),
                                ['icon' => 'fa fas fa-book', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'balance-sheet']
                            );
                            $sub->url(
                                action('AccountReportsController@trialBalance'),
                                __('english.trial_balance'),
                                ['icon' => 'fa fas fa-balance-scale', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'trial-balance']
                            );
                            // $sub->url(
                            //     action('AccountController@cashFlow'),
                            //     __('english.cash_flow'),
                            //     ['icon' => 'fa fas fa-exchange-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'cash-flow']
                            // );
                            // $sub->url(
                            //     action('AccountReportsController@paymentAccountReport'),
                            //     __('english.payment_account_report'),
                            //     ['icon' => 'fa fas fa-file-alt', 'active' => request()->segment(1) == 'account' && request()->segment(2) == 'payment-account-report']
                            //);
                        },
                        ['icon' => 'bx bx-money']
                    )->order(19);
                }

            }, ['icon' => 'fadeIn animated bx bx-money']);


            $menu->dropdown('Academic', function ($sub) {

                if (auth()->user()->can('class.view') || auth()->user()->can('section.view') || auth()->user()->can('subject.view') || auth()->user()->can('assign_subject.view')) {
                    $sub->dropdown(
                        __('english.academic'),
                        function ($sub) {
                            if (auth()->user()->can('class.view')) {
                                $sub->url(
                                    action('ClassController@index'),
                                    __('class.classes'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'classes']
                                );
                            }
                            if (auth()->user()->can('section.view')) {
                                $sub->url(
                                    action('ClassSectionController@index'),
                                    __('class_section.sections'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'sections']
                                );
                            }
                            if (auth()->user()->can('subject.view')) {
                                $sub->url(
                                    action('Curriculum\ClassSubjectController@index'),
                                    __('english.class_subjects'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'curriculum-class-subject']
                                );
                            }
                            if (auth()->user()->can('assign_subject.view')) {
                                $sub->url(
                                    action('Curriculum\AssignSubjectTeacherController@index'),
                                    __('english.assign_subject_&_teacher_to_section'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'assign-subject']
                                );
                            }
                        },
                        ['icon' => 'bx bxs-institution']
                    )->order(10);
                }
                if (auth()->user()->can('syllabus.view')) {
                    $sub->dropdown(
                        __('english.manage_syllabus'),
                        function ($sub) {
                            if (auth()->user()->can('syllabus.view')) {
                                $sub->url(
                                    action('Curriculum\SyllabusMangerController@index'),
                                    __('english.syllabus_and_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'syllabus']
                                );
                            }
                        },
                        ['icon' => 'fa fa-list-alt ftlayer']
                    )->order(11);
                }
                if (auth()->user()->can('study_period.view') || auth()->user()->can('class_routine.view')) {
                    $sub->dropdown(
                        __('english.class_routine'),
                        function ($sub) {
                            if (auth()->user()->can('study_period.view')) {
                                $sub->url(
                                    action('Curriculum\ClassTimeTablePeriodController@index'),
                                    __('english.study_period'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'class-time-table-period']
                                );
                            }
                            if (auth()->user()->can('class_routine.view')) {
                                $sub->url(
                                    action('Curriculum\ClassTimeTableController@index'),
                                    __('english.classes_time_table'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'class-time-table']
                                );
                            }
                        },
                        ['icon' => 'bx bxs-time']
                    )->order(12);
                }
                if (auth()->user()->can('manual_paper.view')) {
                    $sub->dropdown(
                        __('english.paper_maker'),
                        function ($sub) {
                            if (auth()->user()->can('manual_paper.view')) {
                                $sub->url(
                                    action('Curriculum\PaperMakerController@manualPaperCreate'),
                                    __('english.manual_paper'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'manual_paper']
                                );
                            }
                        },
                        ['icon' => 'lni lni-remove-file']
                    )->order(13);
                }
                if (auth()->user()->can('grade.view') || auth()->user()->can('exam_term.view') || auth()->user()->can('exam_date_sheet.view') || auth()->user()->can('roll_no_slip.print') || auth()->user()->can('exam_setup.view') || auth()->user()->can('exam_award_list_attendance.print') || auth()->user()->can('exam_mark_entry.create') || auth()->user()->can('mark_entry_print.print') || auth()->user()->can('exam_result.print')) {
                    $sub->dropdown(
                        __('english.examination'),
                        function ($sub) {
                            if (auth()->user()->can('grade.view')) {
                                $sub->url(
                                    action('Examination\GradeController@index'),
                                    __('english.grade'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'grades']
                                );
                            }
                            if (auth()->user()->can('exam_term.view')) {
                                $sub->url(
                                    action('Examination\TermController@index'),
                                    __('english.exam_term'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'term']
                                );
                            }
                            if (auth()->user()->can('exam_date_sheet.view')) {
                                $sub->url(
                                    action('Examination\ExamDateSheetController@index'),
                                    __('english.date_sheet'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'date_sheets']
                                );
                                $sub->url(
                                    action('Examination\ExamDateSheetController@bulkCreate'),
                                    __('english.bulk_date_sheet'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'date_sheets_bulk_create']
                                );
                            }
                            if (auth()->user()->can('roll_no_slip.print')) {
                                $sub->url(
                                    action('Examination\ExamDateSheetController@createClassWiseRollSlipPrint'),
                                    __('english.roll_no_slip_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'roll_no_slip']
                                );
                            }
                            if (auth()->user()->can('exam_setup.view')) {
                                $sub->url(
                                    action('Examination\ExamSetupController@index'),
                                    __('english.exam_setup'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'setup']
                                );
                            }
                            if (auth()->user()->can('exam_award_list_attendance.print')) {
                                $sub->url(
                                    action('Examination\AwardAttendanceController@index'),
                                    __('english.award_attendance_list_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'award-attendance']
                                );
                            }
                            if (auth()->user()->can('exam_mark_entry.create')) {
                                $sub->url(
                                    action('Examination\ExamMarkController@create'),
                                    __('english.mark_entry'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'mark-entry' || request()->segment(2) == 'mark']
                                );
                            }
                            if (auth()->user()->can('mark_entry_print.print')) {
                                $sub->url(
                                    action('Examination\ExamMarkController@index'),
                                    __('english.mark_entry_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'exam' || request()->segment(2) == 'mark']
                                );
                            }

                            if (auth()->user()->can('exam_result.print')) {
                                $sub->url(
                                    action('Examination\ExamResultController@index'),
                                    __('english.result'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'result' || request()->segment(2) == 'result']
                                );


                                $sub->url(
                                    action('Examination\ExamResultController@create'),
                                    __('english.result_card'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(2) == 'result' && request()->segment(3) == 'create']
                                );

                                $sub->url(
                                    action('Examination\TabulationController@index'),
                                    __('english.tabulation_sheet'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'tabulation_sheet']
                                );
                                $sub->url(
                                    action('Examination\TabulationController@create'),
                                    __('english.tabulation_sheet_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'tabulation_sheet' && request()->segment(2) == 'create']
                                );
                                $sub->url(
                                    action('Examination\ExamResultController@topPositionsCreate'),
                                    __('english.top_ten_students'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'top-positions-print']
                                );
                                $sub->url(
                                    action('Examination\ExamResultController@topPositionListCreate'),
                                    __('english.top_position_list'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'position-list']
                                );
                            }
                        },
                        ['icon' => 'bx bxs-graduation']
                    )->order(14);
                }
                if (auth()->user()->can('routine_test') || auth()->user()->can('routine_test') || auth()->user()->can('routine_test')) {
                    $sub->dropdown(
                        __('english.routine_test'),
                        function ($sub) {
                            if (auth()->user()->can('routine_test')) {
                                $sub->url(
                                    action('Examination\RoutineTestController@create'),
                                    __('english.mark_entry'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'exam' && request()->segment(2) == 'routine-test' && request()->segment(3) == 'create']
                                );
                            }
                            if (auth()->user()->can('promotion.without_exam')) {
                                $sub->url(
                                    action('Examination\RoutineTestController@index'),
                                    __('english.routine_test_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'exam' || request()->segment(1) == 'routine-test']
                                );
                            }
                            if (auth()->user()->can('promotion.without_exam')) {
                                $sub->url(
                                    action('Examination\RoutineTestController@studentCreate'),
                                    __('english.routine_report'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'exam' || request()->segment(1) == 'routine-test-print']
                                );
                            }

                        },

                        ['icon' => 'bx bx-check']
                    )->order(15);
                }
                if (auth()->user()->can('promotion.with_exam') || auth()->user()->can('promotion.without_exam') || auth()->user()->can('promotion.pass_out')) {
                    $sub->dropdown(
                        __('english.promotion'),
                        function ($sub) {
                            if (auth()->user()->can('promotion.with_exam')) {
                                $sub->url(
                                    action('Examination\PromotionController@create'),
                                    __('english.promotion'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'promotion' && request()->segment(2) == 'create' || request()->segment(1) == 'promotion-student']
                                );
                            }
                            if (auth()->user()->can('promotion.without_exam')) {
                                $sub->url(
                                    action('Examination\PromotionController@index'),
                                    __('english.promotion_without_exam'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'promotion' || request()->segment(1) == 'with-out-exam-promotion']
                                );
                            }
                            if (auth()->user()->can('promotion.pass_out')) {
                                $sub->url(
                                    action('Examination\PromotionController@passOutCreate'),
                                    __('english.pass_out'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'pass-out-create' || request()->segment(1) == 'pass-out-post']
                                );
                            }
                        },
                        ['icon' => 'bx bx-briefcase-alt-2']
                    )->order(15);
                }

                if (auth()->user()->can('vehicle.view')) {
                    $sub->dropdown(
                        __('english.transport'),
                        function ($sub) {
                            if (auth()->user()->can('vehicle.view')) {
                                $sub->url(
                                    action('VehicleController@index'),
                                    __('english.vehicle'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'vehicles']
                                );
                            }
                        },
                        ['icon' => 'bx bx-bus-school']
                    )->order(21);
                }
                if (auth()->user()->can('note_books_status.view')) {
                    $sub->dropdown(
                        __('english.note_books_status'),
                        function ($sub) {
                            $sub->url(
                                action('NoteBookStatusController@create'),
                                __('english.add'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'note-book' && request()->segment(2) == 'create']
                            );
                            $sub->url(
                                action('NoteBookStatusController@noteBookEmptyPrintCreate'),
                                __('english.empty_form'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'note-print-empty-create']
                            );
                            $sub->url(
                                action('NoteBookStatusController@index'),
                                __('english.report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'note-book']
                            );
                        },
                        ['icon' => 'fadeIn animated bx bx-book-alt']
                    )->order(22);
                }
                if (auth()->user()->can('print.challan_print') || auth()->user()->can('print.student_card_print') || auth()->user()->can('print.employee_card_print') || auth()->user()->can('print.student_attendance_print') || auth()->user()->can('print.employee_attendance_print') || auth()->user()->can('print.student_particular') || auth()->user()->can('print.certificate')) {
                    $sub->dropdown(
                        __('english.bulk_printing'),
                        function ($sub) {
                            if (auth()->user()->can('print.challan_print')) {
                                $sub->url(
                                    action('SchoolPrinting\FeeCardPrintController@createClassWisePrint'),
                                    __('english.challan_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == 'class-wise-fee-card-printing']
                                );
                            }
                            if (auth()->user()->can('print.student_card_print')) {
                                $sub->url(
                                    action('SchoolPrinting\IdCardPrintController@createClassWiseIdPrint'),
                                    __('english.student_card_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/create-class-wise-id-print']
                                );
                            }
                            if (auth()->user()->can('print.employee_card_print')) {
                                $sub->url(
                                    action('SchoolPrinting\IdCardPrintController@createEmployeeIdPrint'),
                                    __('english.employee_card_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/create-employee-id-print']
                                );
                            }
                            if (auth()->user()->can('print.student_attendance_print')) {
                                $sub->url(
                                    action('Report\AttendanceReportController@index'),
                                    __('english.students_attendance_report_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/report-attendance']
                                );
                            }
                            if (auth()->user()->can('print.employee_attendance_print')) {
                                $sub->url(
                                    action('Report\AttendanceReportController@create'),
                                    __('english.employee_attendance_report_print'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/report-attendance' && request()->segment(2) == 'create']
                                );
                            }
                            if (auth()->user()->can('print.student_particular')) {
                                $sub->url(
                                    action('SchoolPrinting\StudentPrintController@index'),
                                    __('english.student_particular'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/student-particular']
                                );
                            }
                            if (auth()->user()->can('print.certificate')) {
                                $sub->url(
                                    action('Certificate\CertificateBulkPrintController@create'),
                                    __('english.certificate'),
                                    ['icon' => 'bx bx-right-arrow-alt ', 'active' => request()->segment(1) == '/certificate_bulk_print']
                                );
                            }
                        },
                        ['icon' => 'bx bx-printer']
                    )->order(9);
                }
                if (auth()->user()->can('notification.lesson_send_to_students') || auth()->user()->can('notification.weekend_and_holiday')) {
                    $sub->dropdown(
                        __('english.notifications'),
                        function ($sub) {
                            if (auth()->user()->can('notification.lesson_send_to_students')) {
                                $sub->url(
                                    action('NotificationController@lessonProgressSendCreate'),
                                    __('english.lesson_send_to_students'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'lesson_progress_send']
                                );
                            }
                            if (auth()->user()->can('notification.weekend_and_holiday')) {
                                $sub->url(
                                    action('WeekendHolidayController@index'),
                                    __('english.weekend_and_holiday'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'weekend-holiday']
                                );
                                $sub->url(
                                    action('GeneralSmsController@create'),
                                    __('english.general_sms'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'general-sms']
                                );
                            }
                            if (auth()->user()->can('notification.weekend_and_holiday')) {
                                $sub->url(
                                    action('NotificationController@FeeRemainderCreate'),
                                    __('english.fee_remainder'),
                                    ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'fee_remainder']
                                );
                            }
                        },
                        ['icon' => 'bx bxs-bell']
                    )->order(23);
                }
            }, ['icon' => 'bx bxs-institution']);
            if (auth()->user()->can('class_report.view') || auth()->user()->can('strength_report.view') || auth()->user()->can('income_report.view')) {
                $menu->dropdown(
                    __('english.report'),
                    function ($sub) {
                        if (auth()->user()->can('class_report.view')) {
                            $sub->url(
                                action('Report\ClassReportController@index'),
                                __('english.class_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'class-report']
                            );
                        }
                        if (auth()->user()->can('strength_report.view')) {
                            $sub->url(
                                action('Report\StrengthController@index'),
                                __('english.strength_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(2) == 'strength']
                            );
                        }
                        if (auth()->user()->can('strength_report.view')) {
                            $sub->url(
                                action('Report\ReportController@getExpenseReport'),
                                __('english.expense_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(2) == 'expense-report']
                            );
                        }
                        if (auth()->user()->can('strength_report.view')) {
                            $sub->url(
                                action('Report\TopDefaulterController@index'),
                                __('english.top_defaulters'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(2) == 'top-defaulter']
                            );
                        }
                        if (auth()->user()->can('income_report.view')) {
                            $sub->url(
                                action('Report\IncomeReportController@index'),
                                __('english.income_report'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'income-report']
                            );
                        }
                    },
                    ['icon' => 'fadeIn animated bx bx-bar-chart']
                )->order(20);
            }
            if (auth()->user()->can('vehicle.view')) {
                $menu->dropdown(
                    __('english.transport'),
                    function ($sub) {
                        if (auth()->user()->can('vehicle.view')) {
                            $sub->url(
                                action('VehicleController@index'),
                                __('english.vehicle'),
                                ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'vehicles']
                            );
                        }
                    },
                    ['icon' => 'bx bx-bus-school']
                )->order(21);
            }
            $menu->dropdown(
                __('english.frontend'),
                function ($sub) {
                    if (auth()->user()->can('leave_applications_for_employee.view')) {
                        $sub->url(
                            action('Frontend\FrontGalleryController@index'),
                            __('english.gallery'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'galleries']
                        );
                        $sub->url(
                            action('Frontend\FrontSliderController@index'),
                            __('english.slider'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-sliders']
                        );
                        $sub->url(
                            action('Frontend\FrontAboutController@index'),
                            __('english.about_page'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-abouts']
                        );
                        $sub->url(
                            action('Frontend\FrontEventController@index'),
                            __('english.event'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-events']
                        );
                        $sub->url(
                            action('Frontend\FrontNoticeController@index'),
                            __('english.notice'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-notices']
                        );
                        $sub->url(
                            action('Frontend\FrontNewsController@index'),
                            __('english.news'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-news']
                        );
                        $sub->url(
                            action('Frontend\FrontSettingController@index'),
                            __('english.settings'),
                            ['icon' => 'bx bx-right-arrow-alt', 'active' => request()->segment(1) == 'front-settings']
                        );
                    }
                },
                ['icon' => 'bx bx-globe']
            )->order(24);
        });
    }
}









//  Menu::create('navbar', function($menu) {
//     $menu->url('/', 'Home');
//     $menu->dropdown('Accounhhht', function ($sub) {
//         $sub->url('profile', 'Visit My Profile');
//         $sub->dropdown('Settings', function ($sub) {
//             $sub->url('settings/account', 'Account');
//             $sub->url('settings/password', 'Password');
//             $sub->url('settings/design', 'Design');
//         });
//         $sub->url('logout', 'Logout');
//     });