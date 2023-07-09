 @extends("admin_layouts.app")
@section('title', __('english.student_particular'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
        {!! Form::open(['url' => action('SchoolPrinting\StudentPrintController@store'), 'method' => 'post', 'id' =>'attendance_report_form']) !!}

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
                         {!! Form::select('class_ids[]', [], null, ['class' => 'form-select select2 global-classes', 'multiple','style' => 'width:100%']) !!}
                     </div>
                     <div class="col-md-3 ">
                         {!! Form::label('status', __('english.student_status') . ':*') !!}
                         {!! Form::select('status', __('english.std_status'), 'active', ['class' => 'form-control','id'=>'students_list_filter_status','placeholder' => __('english.please_select'), 'required']); !!}
                     </div>
                     <div class="col-md-3 ">

                         {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                             {!! Form::text('month_list_filter_date_range', null, ['class' => 'form-control', 'id'=>'month_list_filter_date_range', 'placeholder' => __('english.date_range')]) !!}

                         </div>


                     </div>
                    <div class="col-md-4 p-2 ">
                                              {!! Form::label('due_limit', __( 'english.due_limit' ) . ':*') !!}
                        {!! Form::text('due_limit', null, ['class' => 'form-control input_number', 'placeholder' => __('english.due_limit')]) !!}

                     </div>
                    <div class="col-md-4 mt-4 ">
                             {!! Form::checkbox('only_transport', 0, null ,[ 'class' => 'form-check-input big-checkbox' , 'id' => 'only_transport'] ); !!}
                        {{ Form::label('only_transport', __('english.only_transport') , ['class' => 'control-label mt-2 ']) }}

                     </div>
        
                          <div class="col-md-4 mt-4 ">
                           {!! Form::checkbox('only_paid', 0, null ,[ 'class' => 'form-check-input big-checkbox' , 'id' => 'only_paid'] ); !!}
                        {{ Form::label('only_paid', __('english.only_paid') , ['class' => 'control-label mt-2 ']) }}

                           {!! Form::checkbox('only_unpaid', 0, null ,[ 'class' => 'form-check-input big-checkbox' , 'id' => 'only_unpaid'] ); !!}
                        {{ Form::label('only_unpaid', __('english.only_unpaid') , ['class' => 'control-label mt-2 ']) }}

                    </div>
                    <div class="col-md-4 mt-4 ">
                        {!! Form::checkbox('net_dues', 0, null ,[ 'class' => 'form-check-input big-checkbox' , 'id' => 'net_dues'] ); !!}
                   {{ Form::label('net_dues', __('english.net_dues') , ['class' => 'control-label mt-2 ']) }}

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

 

