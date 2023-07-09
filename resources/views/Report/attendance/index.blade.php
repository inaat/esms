 @extends("admin_layouts.app")
@section('title', __('english.employee_attendance_print'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
        {!! Form::open(['url' => action('Report\AttendanceReportController@store'), 'method' => 'post', 'id' =>'attendance_report_form']) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                 <hr>
                 <div class="row m-0">
                     <div class="col-md-3  ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                     </div>
                     <div class="col-md-3 ">
                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                         {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                     </div>
                     <div class="col-md-2 ">
                         {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                         {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections','required', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                     </div>
                     <div class="col-md-4 ">
                     {!! Form::label('english.filter_date_range', __('english.filter_date_range') . ':') !!}
                     <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                         {!! Form::text('list_filter_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'month_filter_date_range', 'class' => 'form-control']) !!}

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

{{-- 
 $(document).on('submit', 'form#attendance_report_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __enable_submit_button(form.find('button[type="submit"]'));
            },
          success: function(result) {
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
        });
    }); --}}

     });

 </script>
 @endsection

