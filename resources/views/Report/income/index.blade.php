 @extends("admin_layouts.app")
@section('title', __('english.roles'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         {!! Form::open(['url' => action('Report\IncomeReportController@store'), 'method' => 'post', 'id' =>'attendance_report_form']) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                 <hr>
                 <div class="row m-0">
                     <div class="col-md-3  ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                     </div>

                     <div class="col-md-3 ">

                         {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                             {!! Form::text('month_list_filter_date_range', null, ['class' => 'form-control', 'id'=>'month_list_filter_date_range', 'placeholder' => __('english.date_range')]) !!}

                         </div>
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
