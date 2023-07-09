 @extends("admin_layouts.app")
 @section('title', __('english.roles'))
 @section('title', __('english.roles'))

 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">

         <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.add_new_role')</div>
             <ol class="breadcrumb mb-0 p-0">
                 <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                 </li>
                 <li class="breadcrumb-item active" aria-current="page">@lang('english.add_new_role')</li>
             </ol>
             </nav>
         </div>
         <!--end breadcrumb-->

         <div class="card">
             <div class="card-body">
                 {!! Form::open(['url' => action('RoleController@store'), 'method' => 'post', 'id' => 'role_add_form' ]) !!}

                 <div class="row">

                     <div class="col-md-4">
                         <div class="form-group">
                             {!! Form::label('name', __( 'english.role_name' ) . ':*') !!}
                             {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.role_name' ) ]); !!}
                         </div>
                     </div>
                     <div class="col-md-4 mt-4 ">
                         {!! Form::checkbox('access_all_campuses', 'access_all_campuses', false,
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
                                     {!! Form::checkbox('permissions[]', 'roles.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roles.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roles.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roles.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'session.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'session.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'session.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'session.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'backup.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'backup.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'backup.download', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.download')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'backup.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'campus.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'campus.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'campus.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'campus.delete', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>

                                 <td>
                                     {!! Form::checkbox('permissions[]', 'english.multiple_campus_access', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.multiple_campus_access')
                                 </td>


                             <tr>
                                 <th>@lang('english.class_level')</th>
                                 <td>
                                     @lang('english.class_level')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_level.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_level.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_level.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_level.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'province.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'province.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'province.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'province.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'district.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'district.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'district.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'district.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'city.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'city.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'city.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'city.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'region.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'region.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'region.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'region.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'general_settings.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'general_settings.update', false,
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
                                     {!! Form::checkbox('permissions[]', 'award.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'award.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'award.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'award.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'student.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.delete', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student.status', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_status')
                                     {!! Form::checkbox('permissions[]', 'student.profile', false,
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
                                     {!! Form::checkbox('permissions[]', 'print.admission_form', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.admission_form')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.challan_print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.challan_print') <br>
                                     {!! Form::checkbox('permissions[]', 'print.student_attendance_print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_attendance_print')<br>
                                     {!! Form::checkbox('permissions[]', 'print.employee_attendance_print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_attendance_print')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.student_card_print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_card_print')<br>
                                     {!! Form::checkbox('permissions[]', 'print.employee_card_print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_card_print')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.student_particular', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student_particular')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'print.certificate', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.certificate')
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.student_category')</th>
                                 <td>
                                     @lang('english.student_category')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_category.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_category.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_category.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_category.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'withdrawal.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'withdrawal.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'withdrawal.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'withdrawal.print_withdrawal', false,
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
                                     {!! Form::checkbox('permissions[]', 'student_attendance.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.mark_absent_today', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mark_absent_today')
                                 </td>
                                 <td>
                                     <br>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.qr_code_attendance', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.student') @lang('english.qr_code_attendance')<br>
                                     {!! Form::checkbox('permissions[]', 'student_attendance.mapping', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mapping')
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.employee_attendance')</th>
                                 <td>
                                     @lang('english.employee_attendance')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.mark_absent_today', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mark_absent_today')
                                 </td>
                                 <td>
                                     <br>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.qr_code_attendance', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee') @lang('english.qr_code_attendance')<br>
                                     {!! Form::checkbox('permissions[]', 'employee_attendance.mapping', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mapping')
                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.fee')</th>
                                 <td>
                                     @lang('english.fee')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.add_fee_payment', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.add_fee_payment')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_transaction_view', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_transaction_view')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_transaction_create', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_transaction_create')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_transaction_delete', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_transaction_delete')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.fee_payment_view', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_payment_view')
                                     {!! Form::checkbox('permissions[]', 'fee.fee_payment_delete', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_payment_delete')
                                 </td>
                             </tr>

                             <tr>
                                 <th>
                                     @lang('english.fee_head')<br>
                                     @lang('english.fee_increment_decrement')

                                 </th>
                                 <td>
                                     @lang('english.fee_head')<br>
                                     @lang('english.fee_increment_decrement')

                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee_head.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee_head.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee_head.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee_head.delete', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'fee.increment_decrement', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.fee_increment_decrement')
                                 </td>


                             </tr>
                             <tr>
                                 <th>@lang('english.class')</th>
                                 <td>
                                     @lang('english.class')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'section.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'section.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'section.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'section.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'subject.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'subject.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'subject.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'subject.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'syllabus.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'syllabus.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'syllabus.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'syllabus.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'assign_subject.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'assign_subject.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'assign_subject.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'assign_subject.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'study_period.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'study_period.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'study_period.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'study_period.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'class_routine.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_routine.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_routine.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_routine.delete', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                 </td>
                             <tr>
                                 <th>@lang('english.manual_paper')</th>
                                 <td>
                                     @lang('english.manual_paper')</td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'manual_paper.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'manual_paper.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'manual_paper.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'manual_paper.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'chapter.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'chapter.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'chapter.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'chapter.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'lesson.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'lesson_progress.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson_progress.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson_progress.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'lesson_progress.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'question_bank.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'question_bank.create', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'question_bank.update', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'question_bank.delete', false,
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
                                     {!! Form::checkbox('permissions[]', 'grade.view', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.grade') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_setup.view', false,
                                     [ 'class' => 'form-check-input']); !!}@lang('english.exam_setup') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_term.view', false,
                                     [ 'class' => 'form-check-input']); !!}@lang('english.exam') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_date_sheet.view', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.date_sheet') <br>
                                     {!! Form::checkbox('permissions[]', 'result_card_setting.view', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.result_card_setting')


                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'exam_mark_entry.create', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.exam_mark_entry')


                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'promotion.without_exam', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.promotion_without_exam')<br>
                                     {!! Form::checkbox('permissions[]', 'promotion.with_exam', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.promotion_with_exam')<br>
                                     {!! Form::checkbox('permissions[]', 'promotion.pass_out', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.pass_out')<br>
                                 </td>

                                 <td>
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'roll_no_slip.print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.roll_no_slip_print')<br>
                                     {!! Form::checkbox('permissions[]', 'mark_entry_print.print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.mark_entry_print') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_award_list_attendance.print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.exam_award_list_attendance_print') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_result.print', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.exam_result_print') <br>
                                     {!! Form::checkbox('permissions[]', 'exam_result.print', false,
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

                                     {!! Form::checkbox('permissions[]', 'certificate.issue', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.certificate_issue')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'certificate.print',false,
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

                             <tr>
                                 <th>@lang('english.human_resources')</th>
                                 <td>
                                     @lang('english.employee')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'employee.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.delete',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee.status',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_status')
                                     {!! Form::checkbox('permissions[]', 'employee.print',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_list_print')
                                     {!! Form::checkbox('permissions[]', 'employee.profile',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.employee_profile')
                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.shift')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'shift.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'shift.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'shift.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'shift.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'department.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'department.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'department.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'department.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'designation.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'designation.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'designation.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'designation.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'education.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'education.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'education.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'education.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'allowance.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'allowance.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'allowance.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'allowance.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'deduction.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'deduction.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'deduction.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'deduction.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'payroll.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.delete',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'payroll.print',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.payroll_print')
                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.hrm_payment')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'hrm_payment.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'hrm_payment.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'hrm_payment.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'hrm_payment.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'expense.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.delete',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense.payment',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.expense_payment')
                                 </td>
                             </tr>
                             <tr>
                                 <th></th>
                                 <td>
                                     @lang('english.expense_categories')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'expense_category.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense_category.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense_category.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'expense_category.delete',false,
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

                                     {!! Form::checkbox('permissions[]', 'income_report.view', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.income_report')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'class_report.view',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.class_report')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'strength_report.view',false,
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

                                     {!! Form::checkbox('permissions[]', 'vehicle.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'vehicle.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'vehicle.update',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'vehicle.delete',false,
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
                                     {!! Form::checkbox('permissions[]', 'note_books_status.view', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.note_books_status')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'account.access', false,
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
                                     {!! Form::checkbox('permissions[]', 'dashboard.view', false,
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
                             </tr>

                             <tr>
                                 <th>@lang('english.notifications')</th>
                                 <td>
                                     @lang('english.notifications')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'notification.weekend_and_holiday', false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.weekend_and_holiday')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'notification.lesson_send_to_students',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.lesson_send_to_students')
                                 </td>
                                 <td>
                                 </td>
                                 <td>

                                 </td>
                                 <td>

                                 </td>
                             </tr>
                             <tr>
                                 <th>@lang('english.leave_applications_for_employee')</th>
                                 <td>
                                     @lang('english.leave_applications_for_employee')</td>
                                 <td>

                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_employee.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_employee.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_employee.update_status',false,
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

                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_student.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_student.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'leave_applications_for_student.update_status',false,
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

                                     {!! Form::checkbox('permissions[]', 'employee_document.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_document.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_document.delete',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.delete')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'employee_document.download',false,
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

                                     {!! Form::checkbox('permissions[]', 'student_document.view', false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_document.create',false,
                                     [ 'class' => 'form-check-input']); !!}
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_document.delete',false,
                                     [ 'class' => 'form-check-input']); !!} @lang('english.delete')
                                 </td>
                                 <td>
                                     {!! Form::checkbox('permissions[]', 'student_document.download',false,
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
                         <button type="submit" class="btn btn-primary pull-right">@lang( 'messages.save' )</button>
                     </div>
                 </div>

                 {!! Form::close() !!}
             </div>
         </div>

     </div>
 </div>

 @endsection

