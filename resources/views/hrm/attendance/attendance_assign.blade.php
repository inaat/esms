 @extends("admin_layouts.app")
 @section('title', __('english.employee_attendance'))
 @section('wrapper')
 <style>
     .table-responsive {
         overflow: visible !important;
         zoom: 80%;
     }

 </style>
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.attendance')</div>
             <div class="ps-3">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb mb-0 p-0">
                         <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                         </li>
                         <li class="breadcrumb-item active" aria-current="page">@lang('english.attendance')</li>
                     </ol>
                 </nav>
             </div>
         </div>
         <!--end breadcrumb-->
         {!! Form::open(['url' => action('Hrm\HrmAttendanceController@attendanceAssignSearch'), 'method' => 'post', 'class' => '', 'novalidate' . 'id' => 'search_studen', 'files' => true]) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                 <hr>
                 <div class="row m-0">
                     <div class="col-md-3 p-2 ">
                         {!! Form::label('english.employee', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, $campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'employees_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                     </div>

                     <div class="col-md-3 p-2">
                         {!! Form::label('date', __('english.date') . ':*', ['classs' => 'form-lable']) !!}

                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                             {!! Form::text('date', @format_date($date), ['class' => 'form-control datepicker', 'placeholder' => __('settings.start_date'), 'readonly']) !!}

                         </div>
                     </div>
                 </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                     <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                             <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                 </div>
             </div>
         </div>


         {{ Form::close() }}
         @if (isset($employees))
         {!! Form::open(['url' => action('Hrm\HrmAttendanceController@store'), 'method' => 'post', 'class' => '', '' . 'id' => 'store_employee_fee', 'files' => true]) !!}
         <div class="row">

             <div class="col-lg-12">

                 <div class="card">
                     <div class="card-body">
                         <div class="table-responsive">
                             <table class="table mb-0" width="100%" id="employees_table">
                                 <thead class="table-light" width="100%">
                                     <tr>
                                         <th>@lang('english.employee_name')</th>
                                         <th>@lang('english.father_name')</th>
                                         <th>@lang('english.employee_id')</th>
                                         <th>@lang('english.check_in')</th>
                                         <th>@lang('english.check_out')</th>

                                         <th>@lang('english.attendance')</th>
                                     </tr>
                                 </thead>
                                 <tbody class="">
                                     @foreach ($employees as $employee)
                                     <tr>
                                         <td>{{ ucwords($employee->first_name.' '. $employee->last_name) }}
                                             <input type="hidden" name="attendance[{{ $employee->id }}][employee_id]" value="{{ $employee->id }}">
                                             {!! Form::hidden('date', @format_date($date), ['class' => 'form-control date-picker', 'placeholder' => __('settings.start_date'), 'readonly']) !!}

                                         </td>
                                         <td>{{ ucwords($employee->father_name) }}</td>
                                         <td>{{ $employee->employeeID }}</td>

                                         <td>
                                             @php
                                             $clock_in=true;
                                             @endphp
                                             @forelse ($attendance_list as $attendance )

                                             @if($attendance->employee_id==$employee->id)
                                             <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                                 <input class="form-control datetimepicker-input date-timepicker" data-target-input="nearest" data-toggle="datetimepicker" required="" name="attendance[{{ $employee->id }}][check_in]" type="text" value="{{ @format_datetime($attendance->clock_in_time) }}">
                                             </div>
                                             @php
                                             $clock_in=false;
                                             @endphp
                                             @endif
                                             @endforeach
                                             @if($clock_in)
                                             <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                                 <input class="form-control datetimepicker-input date-timepicker" data-target-input="nearest" data-toggle="datetimepicker" required="" name="attendance[{{ $employee->id }}][check_in]" type="text" value="{{ @format_datetime($date) }}">
                                             </div>
                                             @endif
                                         </td>
                                         <td>
                                             @php
                                             $clock_out=true;
                                             @endphp
                                             @forelse ($attendance_list as $attendance )

                                             @if($attendance->employee_id==$employee->id)
                                             <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                                 <input class="form-control datetimepicker-input date-timepicker" data-target-input="nearest" data-toggle="datetimepicker" required="" name="attendance[{{ $employee->id }}][check_out]" type="text" value="{{ @format_datetime($attendance->clock_out_time) }}">
                                             </div>
                                             @php
                                             $clock_out=false;
                                             @endphp
                                             @endif
                                             @endforeach
                                             @if($clock_out)
                                             <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                                 <input class="form-control datetimepicker-input date-timepicker" data-target-input="nearest" data-toggle="datetimepicker" required="" name="attendance[{{ $employee->id }}][check_out]" type="text" value="{{@format_datetime($date) }}">
                                             </div>
                                             @endif
                                         </td>

                                         <td>

                                             @php
                                             $check=true;
                                             $count=$loop->iteration;
                                             @endphp
                                             @forelse ($attendance_list as $attendance )

                                             @if($attendance->employee_id==$employee->id)
                                             <input type="radio" @if($attendance->employee_id==$employee->id && $attendance->type=='present') checked @endif class="form-check-input" name="attendance[{{ $employee->id }}][status]" value="{{'present'}}">
                                             <label class="form-check-label" for=""> @lang('english.present')</label>
                                             <input type="radio" @if($attendance->employee_id==$employee->id && $attendance->type=='late') checked @endif class="form-check-input" name="attendance[{{ $employee->id }}][status]" value="{{'late'}}">
                                             <label class="form-check-label" for=""> @lang('english.late')</label>
                                             <input type="radio" @if($attendance->employee_id==$employee->id && $attendance->type=='absent') checked @endif class="form-check-input" name="attendance[{{ $employee->id }}][status]" value="{{'absent'}}">
                                             <label class="form-check-label" for=""> @lang('english.absent')</label>
                                             <input type="radio" @if($attendance->employee_id==$employee->id && $attendance->type=='half_day') checked @endif class="form-check-input" name="attendance[{{ $employee->id }}][status]" value="{{'half_day'}}">
                                             <label class="form-check-label" for=""> @lang('english.half_day')</label>
                                             <input type="radio" @if($attendance->employee_id==$employee->id && $attendance->type=='leave') checked @endif class="form-check-input" name="attendance[{{ $employee->id }}][status]" value="{{'leave'}}">
                                             <label class="form-check-label" for="">@lang('english.leave')</label>
                                             @php
                                             $check=false;
                                             @endphp

                                             @endif

                                             @endforeach
                                             @if($check)

                                             <input type="radio" class="form-check-input" name="attendance[{{  $employee->id }}][status]"  value="{{'present'}}">
                                             <label class="form-check-label" for=""> @lang('english.present')</label>
                                             <input type="radio" class="form-check-input" name="attendance[{{  $employee->id }}][status]" value="{{'late'}}">
                                             <label class="form-check-label" for=""> @lang('english.late')</label>
                                             <input type="radio" class="form-check-input" name="attendance[{{  $employee->id }}][status]" value="{{'absent'}}">
                                             <label class="form-check-label" for=""> @lang('english.absent')</label>
                                             <input type="radio" class="form-check-input" name="attendance[{{  $employee->id }}][status]" value="{{'half_day'}}">
                                             <label class="form-check-label" for=""> @lang('english.half_day')</label>
                                             <input type="radio" class="form-check-input" name="attendance[{{  $employee->id }}][status]" value="{{'leave'}}">
                                             <label class="form-check-label" for="">@lang('english.leave')</label>
                                             @endif
                                         </td>
                                     </tr>
                                     @endforeach
                                 </tbody>
                                 @if ($employees->count() > 0)
                                 <tr>
                                     <td colspan="7">
                                         <div class="text-center">
                                             <button type="submit" id="btn-assign-fees-group" class="btn btn-primary radius-30 mt-2 mt-lg-0 fix-gr-bg mb-0 submit" id="btn-assign-fees-group" data-loading-text="<i class='fas fa-spinner'></i> Processing Data">
                                                 <span class="ti-save pr"></span>
                                                 @lang('english.save')
                                             </button>
                                         </div>
                                     </td>
                                 </tr>
                                 @endif
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         {{ Form::close() }}
         @endif
     </div>
 </div>
 @endsection

 @section('javascript')

 <script type="text/javascript">
     $(document).ready(function() {
         $('.date-picker').datetimepicker({
             format: moment_date_format
             , ignoreReadonly: true
         , });
         $('#datetimepicker1').datetimepicker();



         $('#employees_table').find('.date-timepicker').each(function() {
             $(this).datetimepicker({
                 format: moment_date_format + ' ' + moment_time_format,


             });
         });
         if ($("#table_id_table").length) {
             $("#table_id_table").DataTable({
                 dom: 'T<"clear"><"button">lfrtip'
                 , bFilter: false
                 , bLengthChange: false
             , });
         }




         // Fees Assign
         $("#checkAll").on("click", function() {
             $(".common-checkbox").prop("checked", this.checked);
         });

         $(".common-checkbox").on("click", function() {
             if (!$(this).is(":checked")) {
                 $("#checkAll").prop("checked", false);
             }
             var numberOfChecked = $(".common-checkbox:checked").length;
             var totalCheckboxes = $(".common-checkbox").length;
             var totalCheckboxes = totalCheckboxes - 1;

             if (numberOfChecked == totalCheckboxes) {
                 $("#checkAll").prop("checked", true);
             }
         });



     });

 </script>
 @endsection

