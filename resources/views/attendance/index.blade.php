 @extends("admin_layouts.app")
@section('title', __('english.student_attendance'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         <div class="card">
             <div class="card-body">
                 <div class="accordion" id="employee-fillter">
                     <div class="accordion-item">
                         <h2 class="accordion-header" id="employee-fillter">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                 <h5 class="card-title text-primary">@lang('english.flitters')</h5>
                             </button>
                         </h2>
                         <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="employee-fillter" data-bs-parent="#employee-fillter" style="">
                             <div class="accordion-body">
                                 <div class="row">
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('campus_id', __('english.campuses') . ':') !!}
                                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id'=>'list_filter_campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                     <div class="col-md-4 p-1">
                                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                        {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'list_filter_class_id']) !!}
                                    </div>
                                    <div class="col-md-4 p-1">
                                        {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                                        {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'id' => 'list_filter_class_section_id', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                    </div> 
                                     <div class="col-md-3 p-1">
                                        {!! Form::label('status', __('english.status') . ':*') !!}
                                        {!! Form::select('status', __('english.attendance_status'), $status, ['class' => 'form-control','id'=>'list_filter_status','placeholder' => __('english.please_select'), 'required']); !!}
                                    </div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('roll_no', __('english.roll_no')) !!}
                                         {!! Form::text('roll_no', null, ['class' => 'form-control', 'placeholder' => __('english.roll_no'), 'id' => 'list_filter_roll_no']) !!}
                                     </div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('student_name', __('english.student_name') . ':') !!}
                                         {!! Form::text('student_name', null, ['class' => 'form-control', 'placeholder' => __('english.student_name'), 'id' => 'list_filter_student_name']) !!}
                                     </div>
                                     <div class="clearfix"></div>

                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.filter_date_range', __('english.filter_date_range') . ':') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                             {!! Form::text('list_filter_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'today_list_filter_date_range', 'class' => 'form-control']) !!}

                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </div>





         <div class="card">
             <div class="card-body">
                 <h5 class="card-title text-primary">@lang('english.attendance_list')</h5>

                 <div class="d-lg-flex align-items-center mb-4">
                  @can('student_attendance.create')
                     <div class=""><a class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ action('AttendanceController@create') }}">
                             <i class="bx bxs-plus-square"></i>@lang('english.add_new_attendance')</a>
                     </div>
                     @endcan
                     @can('student_attendance.mapping')
                     <div class=""><a class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ action('MappingController@studentsMapping') }}">
                             <i class="bx bxs-plus-square"></i>@lang('english.mapping_attendance')</a>
                     </div>
                     @endcan
                     @can('student_attendance.mark_absent_today')
                     <div class=""><a class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ action('MappingController@markAbsent') }}">
                             <i class="bx bxs-plus-square"></i>@lang('english.mark_absent_today')</a>
                     </div>
                     @endcan
                 </div>


                 <hr>

                 <div class="table-responsive">
                     <table class="table mb-0" width="100%" id="attendance_table">
                         <thead class="table-light" width="100%">
                             <tr>
                                 {{-- <th>@lang('english.action')</th> --}}
                                 <th>@lang('english.campus_name')</th>
                                 <th>@lang('english.student_name')</th>
                                 <th>@lang('english.father_name')</th>
                                 <th>@lang('english.roll_no')</th>
                                 <th>@lang('english.status')</th>
                                 <th>@lang('english.check_in_time')</th>
                                 <th>@lang('english.check_out_time')</th>
                                 <th>@lang('english.work_duration')</th>



                             </tr>
                         </thead>

                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>

 @endsection

 @section('javascript')

 <script type="text/javascript">
     $(document).ready(function() {
         
    
         //attendance_table
         var attendance_table = $("#attendance_table").DataTable({
             processing: true
             , serverSide: true
             , "ajax": {
                 "url": "/attendance"
                 , "data": function(d) {
                     if ($('#today_list_filter_date_range').val()) {
                         var start = $('#today_list_filter_date_range').data('daterangepicker')
                             .startDate.format('YYYY-MM-DD');
                         var end = $('#today_list_filter_date_range').data('daterangepicker')
                             .endDate.format('YYYY-MM-DD');
                         d.start_date = start;
                         d.end_date = end;
                     }

                     if ($('#list_filter_campus_id').length) {
                         d.campus_id = $('#list_filter_campus_id').val();
                     }
                     if ($('#list_filter_class_id').length) {
                         d.class_id = $('#list_filter_class_id').val();
                     }
                     if ($('#list_filter_class_section_id').length) {
                         d.class_section_id = $('#list_filter_class_section_id').val();
                     }
                     if ($('#list_filter_status').length) {
                         d.status = $('#list_filter_status').val();
                     }
                     if ($('#list_filter_roll_no').length) {
                         d.roll_no = $('#list_filter_roll_no').val();
                     }
                    //  if ($('#list_filter_employeeID').length) {
                    //      d.employeeID = $('#list_filter_employeeID').val();
                    //  }
                     if ($('#list_filter_student_name').length) {
                        d.student_name = $('#list_filter_student_name').val();
                     }
                     d = __datatable_ajax_callback(d);
                 }
             }

             , columns: [
                 {
                     data: "campus_name"
                     , name: "campus_name"
                     , orderable: false
                     , "searchable": false
                 }
                 , {
                     data: "student_name"
                     , name: "student_name"
                 }
                 ,{
                     data:"father_name"
                     ,name:"father_name"
                 }
                 , {
                     data: "roll_no"
                     , name: "roll_no"

                 }
                 , {
                     data: "status"
                     , name: "status"
                     , orderable: false
                     , "searchable": false
                    
                 }
                 , {
                     data: "clock_in"
                     , name: "clock_in"
                     , orderable: false
                     , "searchable": false
                    
                 }
                 , {
                     data: "clock_out"
                     , name: "clock_out"
                     , orderable: false
                     , "searchable": false
                  
                 }
                 , {
                     data: "work_duration"
                     , name: "work_duration"
                     , orderable: false
                     , "searchable": false
                    
                 }
               
             , ]
         , });

         $(document).on('change'
             , '#list_filter_campus_id,#list_filter_status,#today_list_filter_date_range,#list_filter_class_id,#list_filter_class_section_id'
             , function() {
                 attendance_table.ajax.reload();
             });
         $(document).on('keyup', '#list_filter_roll_no,#list_filter_student_name'
             , function() {
                 attendance_table.ajax.reload();
             });







     });

 </script>
 @endsection
