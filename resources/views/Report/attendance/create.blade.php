 @extends("admin_layouts.app")
@section('title', __('english.employee_attendance_print'))
 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->
             <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                 <div class="breadcrumb-title pe-3">@lang('english.employee_attendance_print')</div>
                 <div class="ps-3">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb mb-0 p-0">
                             <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                             </li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <!--end breadcrumb-->
             {!! Form::open(['url' => action('Report\AttendanceReportController@employeeStore'), 'method' => 'post', 'class' => 'needs-validation was-validated', 'novalidate' . 'id' => 'search_student_fee', 'files' => true]) !!}

             <div class="card">

                 <div class="card-body">
                     <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                     <hr>
                     <div class="row m-0">
                      <div class="col-md-3 p-2 ">
                         {!! Form::label('campus_id', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                     <div class="col-md-3 p-2">
                         {!! Form::label('status', __('english.designations') . ':') !!}
                        {!! Form::select('designation',$designations, null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'placeholder' => __('english.all')]) !!}

                     </div>

                     <div class="col-md-4 ">
                        {!! Form::label('english.filter_date_range', __('english.filter_date_range') . ':') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('list_filter_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'month_filter_date_range', 'class' => 'form-control']) !!}
   
                        </div>
                     </div>
                     
                     <div class="d-lg-flex align-items-center mt-4 gap-3">
                         <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                 <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                     </div>
                 </div>
             </div>


             {{ Form::close() }}



         </div>
     </div>

 @endsection

 @section('javascript')

     <script type="text/javascript">
         $(document).ready(function() {

        //Default settings for daterangePicker
        var ranges = {};
        ranges[LANG.today] = [moment(), moment()];
        ranges[LANG.yesterday] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
        ranges[LANG.last_7_days] = [moment().subtract(6, 'days'), moment()];
        ranges[LANG.last_30_days] = [moment().subtract(29, 'days'), moment()];
        ranges[LANG.this_month] = [moment().startOf('month'), moment().endOf('month')];
        ranges[LANG.last_month] = [
            moment()
            .subtract(1, 'month')
            .startOf('month')
            , moment()
            .subtract(1, 'month')
            .endOf('month')
        , ];


        var sdateRangeSettings = {
            ranges: ranges
            , startDate: moment().startOf('month') 
            , endDate: moment().endOf('month')
            , locale: {
                cancelLabel: LANG.clear
                , applyLabel: LANG.apply
                , customRangeLabel: LANG.custom_range
                , format: moment_date_format
                , toLabel: '~'
            , }
        , };
        //Date range as a button
        $('#month_filter_date_range').daterangepicker(
            sdateRangeSettings
            , function(start, end) {
                $('#month_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
                    .format(moment_date_format));

            }
        );


         });
     </script>
 @endsection
