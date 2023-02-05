 @extends("admin_layouts.app")
 @section('title', __('english.roles'))
 @section('title', __('english.roles'))

 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">

         <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.edit_role')</div>
             <ol class="breadcrumb mb-0 p-0">
                 <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                 </li>
                 <li class="breadcrumb-item active" aria-current="page">@lang('english.edit_role')</li>
             </ol>
             </nav>
         </div>
         <!--end breadcrumb-->

         <div class="card">
             <div class="card-body">
                 {!! Form::open(['url' => action('RoleController@update', [$role->id]), 'method' => 'PUT', 'id' => 'role_form' ]) !!}

                 <div class="row">
                     <div class="col-md-4">
                         <div class="form-group">
                             {!! Form::label('name', __( 'english.role_name' ) . ':*') !!}
                             {!! Form::text('name', str_replace( '#' . auth()->user()->system_settings_id, '', $role->name) , ['class' => 'form-control', 'required', 'placeholder' => __( 'english.role_name' ) ]); !!}
                         </div>
                     </div>
                     <div class="col-md-4 mt-4 ">
                         {!! Form::checkbox('access_all_campuses', 'access_all_campuses', in_array('access_all_campuses', $role_permissions),
                         ['class' => 'form-check-input big-checkbox']); !!}
                         <label for="{{ __( 'english.access_all_campuses' ) }}" class="control-label m-2  ">{{ __( 'english.access_all_campuses' ) }}</label>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-3">
                         <label>@lang( 'english.permissions' ):</label>
                     </div>
                 </div>

                 <div class="table-responsive">
                     <table class="table table-stripped">
                         <thead>
                             <tr>
                                 <th>@lang('english.module')</th>
                                 <th>@lang('english.feature')</th>
                                 <th>@lang('english.view')</th>
                                 <th>@lang('english.add')</th>
                                 <th>@lang('english.edit')</th>
                                 <th>@lang('english.delete')</th>
                                 <th>@lang('english.other')</th>
                             </tr>
                         </thead>
                         <tbody>

                             <tr>
                                 <th>@lang('english.roles')</th>
                                 <td>
                                     @lang('english.roles')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'roles.view', in_array('roles.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roles.create',in_array('roles.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roles.update',in_array('roles.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roles.delete',in_array('roles.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.session')</th>
                                 <td>
                                     @lang('english.session')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'session.view', in_array('session.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'session.create',in_array('session.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'session.update',in_array('session.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'session.delete',in_array('session.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.backup')</th>
                                 <td>
                                     @lang('english.backup')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'backup.view', in_array('backup.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'backup.create',in_array('backup.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'backup.download',in_array('backup.download', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.download')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'backup.delete',in_array('backup.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.campus')</th>
                                 <td>
                                     @lang('english.campus')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'campus.view', in_array('campus.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'campus.create',in_array('campus.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'campus.update',in_array('campus.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'campus.delete',in_array('campus.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'english.multiple_campus_access',in_array('english.multiple_campus_access', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.multiple_campus_access')
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.class_level')</th>
                                 <td>
                                     @lang('english.class_level')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'class_level.view', in_array('class_level.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_level.create',in_array('class_level.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_level.update',in_array('class_level.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_level.delete',in_array('class_level.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>

                             <tr>
                                 <th>@lang('english.province')</th>
                                 <td>
                                     @lang('english.province')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'province.view', in_array('province.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'province.create',in_array('province.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'province.update',in_array('province.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'province.delete',in_array('province.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.district')</th>
                                 <td>
                                     @lang('english.district')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'district.view', in_array('district.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'district.create',in_array('district.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'district.update',in_array('district.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'district.delete',in_array('district.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.city')</th>
                                 <td>
                                     @lang('english.city')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'city.view', in_array('city.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'city.create',in_array('city.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'city.update',in_array('city.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'city.delete',in_array('city.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.region')</th>
                                 <td>
                                     @lang('english.region')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'region.view', in_array('region.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'region.create',in_array('english.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'region.update',in_array('english.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'region.delete',in_array('english.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.general_settings')</th>
                                 <td>
                                     @lang('english.general_settings')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'general_settings.view', in_array('general_settings.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'general_settings.update',in_array('general_settings.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                 </td>
                                 <td>

                                 </td>
                             </tr>

                             <tr>
                                 <th>@lang('english.award')</th>
                                 <td>
                                     @lang('english.award')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'award.view', in_array('award.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'award.create',in_array('award.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'award.update',in_array('award.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'award.delete',in_array('award.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.student')</th>
                                 <td>
                                     @lang('english.student')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'student.view', in_array('student.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.create',in_array('student.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.update',in_array('student.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.delete',in_array('student.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.status',in_array('student.status', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_status')<br>
                                     {!! Form::checkbox('permissions[]', 'student.profile',in_array('student.profile', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_profile')
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.print')</th>
                                 <td>
                                     @lang('english.admission_form')<br>
                                     @lang('english.challan_print')<br>
                                     @lang('english.bulk_printing')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.admission_form', in_array('print.admission_form', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.admission_form')
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.challan_print', in_array('print.challan_print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.challan_print') <br>
                                     {!! Form::checkbox('permissions[]', 'print.student_attendance_print', in_array('print.student_attendance_print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_attendance_print')<br>
                                     {!! Form::checkbox('permissions[]', 'print.employee_attendance_print', in_array('print.employee_attendance_print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_attendance_print')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.student_card_print', in_array('print.student_card_print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_card_print')<br>
                                     {!! Form::checkbox('permissions[]', 'print.employee_card_print', in_array('print.employee_card_print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_card_print')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.student_particular', in_array('print.student_particular', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_particular')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.certificate', in_array('print.certificate', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.certificate')
                                 </td>
                             </tr>

                             <tr>
                                 <th>@lang('english.student_category')</th>
                                 <td>
                                     @lang('english.student_category')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'student_category.view', in_array('student_category.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_category.create',in_array('student_category.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_category.update',in_array('student_category.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_category.delete',in_array('student_category.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.withdrawal')</th>
                                 <td>
                                     @lang('english.withdrawal')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'withdrawal.view', in_array('withdrawal.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'withdrawal.create',in_array('withdrawal.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'withdrawal.update',in_array('withdrawal.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'withdrawal.print_withdrawal',in_array('withdrawal.print_withdrawal', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.print_withdrawal')
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.student_attendance')</th>
                                 <td>
                                     @lang('english.student_attendance')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.view', in_array('student_attendance.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.create', in_array('student_attendance.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.mark_absent_today', in_array('student_attendance.mark_absent_today', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mark_absent_today')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.qr_code_attendance', in_array('student_attendance.qr_code_attendance', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee') @lang('english.qr_code_attendance')<br>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.mapping', in_array('student_attendance.mapping', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mapping')
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.employee_attendance')</th>
                                 <td>
                                     @lang('english.employee_attendance')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.view', in_array('employee_attendance.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.create', in_array('employee_attendance.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.mark_absent_today', in_array('employee_attendance.mark_absent_today', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mark_absent_today')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.qr_code_attendance', in_array('employee_attendance.qr_code_attendance', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee') @lang('english.qr_code_attendance')<br>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.mapping', in_array('employee_attendance.mapping', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mapping')
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.fee')</th>
                                 <td>
                                     @lang('english.fee')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.add_fee_payment', in_array('fee.add_fee_payment', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.add_fee_payment')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_transaction_view', in_array('fee.fee_transaction_view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_transaction_view')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_transaction_create', in_array('fee.fee_transaction_create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_transaction_create')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_transaction_delete', in_array('fee.fee_transaction_delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_transaction_delete')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_payment_view', in_array('fee.fee_payment_view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_payment_view')<br>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_payment_delete', in_array('fee.fee_payment_delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_payment_delete')
                                 </td>
                             </tr>

                             <tr>
                                 <th>@lang('english.fee_head')<br>
                                     @lang('english.fee_increment_decrement')</th>
                                 <td>
                                     @lang('english.fee_head')<br>
                                     @lang('english.fee_increment_decrement')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'fee_head.view', in_array('fee_head.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee_head.create',in_array('fee_head.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee_head.update',in_array('fee_head.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee_head.delete',in_array('fee_head.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.increment_decrement',in_array('fee.increment_decrement', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_increment_decrement')
                                 </td>
                             </tr>

                             <tr>
                                 <th>@lang('english.class')</th>
                                 <td>
                                     @lang('english.class')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'class.view', in_array('class.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class.create',in_array('class.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class.update',in_array('class.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class.delete',in_array('class.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>

                             <tr>
                                 <th>@lang('english.section')</th>
                                 <td>
                                     @lang('english.section')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'section.view', in_array('section.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'section.create',in_array('section.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'section.update',in_array('section.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'section.delete',in_array('section.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.subject')</th>
                                 <td>
                                     @lang('english.subject')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'subject.view', in_array('subject.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'subject.create',in_array('subject.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'subject.update',in_array('subject.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'subject.delete',in_array('subject.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.assign_subject')</th>
                                 <td>
                                     @lang('english.assign_subject')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'assign_subject.view', in_array('assign_subject.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'assign_subject.create',in_array('assign_subject.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'assign_subject.update',in_array('assign_subject.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'assign_subject.delete',in_array('assign_subject.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>


                             <tr>
                                 <th>@lang('english.syllabus')</th>
                                 <td>
                                     @lang('english.syllabus')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'syllabus.view', in_array('syllabus.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'syllabus.create',in_array('syllabus.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'syllabus.update',in_array('syllabus.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'syllabus.delete',in_array('syllabus.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.study_period')</th>
                                 <td>
                                     @lang('english.study_period')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'study_period.view', in_array('study_period.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'study_period.create',in_array('study_period.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'study_period.update',in_array('study_period.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'study_period.delete',in_array('study_period.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.class_routine')</th>
                                 <td>
                                     @lang('english.class_routine')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'class_routine.view', in_array('class_routine.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_routine.create',in_array('class_routine.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_routine.update',in_array('class_routine.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_routine.delete',in_array('class_routine.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.manual_paper')</th>
                                 <td>
                                     @lang('english.manual_paper')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'manual_paper.view', in_array('manual_paper.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'manual_paper.create',in_array('manual_paper.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'manual_paper.update',in_array('manual_paper.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'manual_paper.delete',in_array('manual_paper.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.chapter')</th>
                                 <td>
                                     @lang('english.chapter')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'chapter.view', in_array('chapter.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'chapter.create',in_array('chapter.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'chapter.update',in_array('chapter.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'chapter.delete',in_array('chapter.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.lesson')</th>
                                 <td>
                                     @lang('english.lesson')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'lesson.view', in_array('lesson.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson.create',in_array('lesson.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson.update',in_array('lesson.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson.delete',in_array('lesson.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.lesson_progress')</th>
                                 <td>
                                     @lang('english.lesson_progress')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'lesson_progress.view', in_array('lesson_progress.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson_progress.create',in_array('lesson_progress.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson_progress.update',in_array('lesson_progress.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson_progress.delete',in_array('lesson_progress.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.question_bank')</th>
                                 <td>
                                     @lang('english.question_bank')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'question_bank.view', in_array('question_bank.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'question_bank.create',in_array('question_bank.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'question_bank.update',in_array('question_bank.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'question_bank.delete',in_array('question_bank.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.exam')</th>
                                 <td>
                                     @lang('english.grade')<br>
                                     @lang('english.exam_term')<br>
                                     @lang('english.date_sheet')<br>
                                     @lang('english.roll_no_slip_print')<br>
                                     @lang('english.exam_setup')<br>
                                     @lang('english.award_attendance_list_print')<br>
                                     @lang('english.mark_entry_print')<br>
                                     @lang('english.result')<br>
                                     @lang('english.result_card')<br>
                                     @lang('english.tabulation_sheet')<br>
                                     @lang('english.tabulation_sheet_print')<br>
                                     @lang('english.top_ten_position_list')<br>
                                     @lang('english.position_wise_list_print')<br>
                                     @lang('english.promotion')<br>

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'grade.view', in_array('grade.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.grade') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_setup.view', in_array('exam_setup.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}@lang('english.exam_setup') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_term.view', in_array('exam_term.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}@lang('english.exam_term') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_date_sheet.view', in_array('exam_date_sheet.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.date_sheet') <br>
                                     {!! Form::checkbox('permissions[]', 'result_card_setting.view', in_array('result_card_setting.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.result_card_setting')


                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'exam_mark_entry.create', in_array('exam_mark_entry.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.exam_mark_entry')


                                 </td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'promotion.with_exam', in_array('promotion.with_exam', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.promotion_with_exam')<br>
                                     {!! Form::checkbox('permissions[]', 'promotion.without_exam', in_array('promotion.without_exam', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.promotion_without_exam')<br>
                                     {!! Form::checkbox('permissions[]', 'promotion.pass_out', in_array('promotion.pass_out', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.pass_out')<br>
                                 </td>

                                 <td>
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roll_no_slip.print', in_array('roll_no_slip.print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.roll_no_slip_print')<br>
                                     {!! Form::checkbox('permissions[]', 'mark_entry_print.print', in_array('mark_entry_print.print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mark_entry_print') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_award_list_attendance.print', in_array('exam_award_list_attendance.print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.exam_award_list_attendance_print') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_result.print', in_array('exam_result.print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                     @lang('english.result')<br>
                                     @lang('english.result_card')<br>
                                     @lang('english.tabulation_sheet')<br>
                                     @lang('english.tabulation_sheet_print')<br>
                                     @lang('english.top_ten_position_list')<br>
                                     @lang('english.position_wise_list_print')<br>
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.certificate')</th>
                                 <td>
                                     @lang('english.certificate')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'certificate.issue', in_array('certificate.issue', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.certificate_issue')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'certificate.print',in_array('certificate.print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.certificate_print')
                                 </td>
                                 <td>
                                     {{-- {!! Form::checkbox('permissions[]', 'certificate.update',in_array('certificate.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} --}}
                                 </td>
                                 <td>
                                     {{-- {!! Form::checkbox('permissions[]', 'certificate.delete',in_array('certificate.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} --}}
                                 </td>
                                 <td>

                                 </td>
                             </tr>

                             </tr>

                             <tr>
                                 <th>@lang('english.human_resources')</th>
                                 <td>
                                     @lang('english.employee')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'employee.view', in_array('employee.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.create',in_array('employee.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.update',in_array('employee.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.delete',in_array('employee.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.status',in_array('employee.status', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_status')
                                     {!! Form::checkbox('permissions[]', 'employee.print',in_array('employee.print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_list_print')
                                     {!! Form::checkbox('permissions[]', 'employee.profile',in_array('employee.profile', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_profile')
                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.shift')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'shift.view', in_array('shift.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'shift.create',in_array('shift.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'shift.update',in_array('shift.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'shift.delete',in_array('shift.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.department')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'department.view', in_array('department.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'department.create',in_array('department.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'department.update',in_array('department.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'department.delete',in_array('department.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.designation')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'designation.view', in_array('designation.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'designation.create',in_array('designation.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'designation.update',in_array('designation.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'designation.delete',in_array('designation.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.education')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'education.view', in_array('education.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'education.create',in_array('education.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'education.update',in_array('education.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'education.delete',in_array('education.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.allowance')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'allowance.view', in_array('allowance.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'allowance.create',in_array('allowance.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'allowance.update',in_array('allowance.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'allowance.delete',in_array('allowance.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.deduction')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'deduction.view', in_array('deduction.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'deduction.create',in_array('deduction.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'deduction.update',in_array('deduction.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'deduction.delete',in_array('deduction.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.pay_roll')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'payroll.view', in_array('payroll.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.create',in_array('payroll.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.update',in_array('payroll.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.delete',in_array('payroll.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.print',in_array('payroll.print', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.payroll_print')
                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.hrm_payment')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'hrm_payment.view', in_array('hrm_payment.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'hrm_payment.create',in_array('hrm_payment.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'hrm_payment.update',in_array('hrm_payment.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'hrm_payment.delete',in_array('hrm_payment.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.expense')</th>
                                 <td>
                                     @lang('english.expense')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'expense.view', in_array('expense.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.create',in_array('expense.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.update',in_array('expense.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.delete',in_array('expense.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.payment',in_array('expense.payment', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.expense_payment')
                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.expense_categories')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'expense_category.view', in_array('expense_category.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense_category.create',in_array('expense_category.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense_category.update',in_array('expense_category.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense_category.delete',in_array('expense_category.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th> @lang('english.report')</th>
                                 <td>
                                     @lang('english.report')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'income_report.view', in_array('income_report.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.income_report')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_report.view',in_array('class_report.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.class_report')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'strength_report.view',in_array('strength_report.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.strength_report')
                                 </td>
                                 <td>

                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.miscellaneous')</th>
                                 <td>
                                     @lang('english.vehicle')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'vehicle.view', in_array('vehicle.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'vehicle.create',in_array('vehicle.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'vehicle.update',in_array('vehicle.update', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'vehicle.delete',in_array('vehicle.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.note_books_status')<br>@lang('english.payment_account')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'note_books_status.view', in_array('note_books_status.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.note_books_status')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'account.access', in_array('account.access', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.payment_account')
                                 </td>

                                 <td>

                                 </td>
                                 <td>

                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.dashboard')
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'dashboard.view', in_array('dashboard.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.dashboard')
                                 </td>
                                 <td>

                                 </td>

                                 <td>

                                 </td>
                                 <td>

                                 </td>
                                 <td>

                                 </td>
                             <tr>
                                 <th>@lang('english.notifications')</th>
                                 <td>
                                     @lang('english.notifications')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'notification.weekend_and_holiday', in_array('notification.weekend_and_holiday', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.weekend_and_holiday')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'notification.lesson_send_to_students',in_array('notification.lesson_send_to_students', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.lesson_send_to_students')
                                 </td>
                                 <td>
                                 </td>
                                 <td>

                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             </tr>
                             <tr>
                                 <th>@lang('english.leave_applications_for_employee')</th>
                                 <td>
                                     @lang('english.leave_applications_for_employee')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_employee.view', in_array('leave_applications_for_employee.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_employee.create',in_array('leave_applications_for_employee.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_employee.update_status',in_array('leave_applications_for_employee.update_status', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.update_status')
                                 </td>
                                 <td>

                                 </td>
                                 <td>

                                 </td>
                             </tr>

                             <tr>
                                 <th>@lang('english.leave_applications_for_student')</th>
                                 <td>
                                     @lang('english.leave_applications_for_student')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_student.view', in_array('leave_applications_for_student.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_student.create',in_array('leave_applications_for_student.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_student.update_status',in_array('leave_applications_for_student.update_status', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.update_status')
                                 </td>
                                 <td>

                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.employee_document')</th>
                                 <td>
                                     @lang('english.employee_document')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'employee_document.view', in_array('employee_document.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_document.create',in_array('employee_document.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_document.delete',in_array('employee_document.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.delete')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_document.download',in_array('employee_document.download', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.download')
                                 </td>
                                
                                 <td>

                                 </td>
                             </tr>
                                <tr>
                                 <th>@lang('english.student_document')</th>
                                 <td>
                                     @lang('english.student_document')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'student_document.view', in_array('student_document.view', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_document.create',in_array('student_document.create', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_document.delete',in_array('student_document.delete', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.delete')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_document.download',in_array('student_document.download', $role_permissions),
                                     [ 'class' => 'form-check-input']); !!} @lang('english.download')
                                 </td>
                                
                                 <td>

                                 </td>
                             </tr>
                         </tbody>

                     </table>
                 </div>
                 <div class="row">
                     <div class="col-md-12">
                         <button type="submit" class="btn btn-primary pull-right">@lang( 'english.update' )</button>
                     </div>
                 </div>

                 {!! Form::close() !!}
             </div>
         </div>

     </div>
 </div>

 @endsection

