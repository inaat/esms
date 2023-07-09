 @extends("admin_layouts.sidebar-header-less")
 @section('wrapper')

 <div class="row" style="padding-left:10px; padding-right:10px ;">
     <div class="col-12">
         <div class="card">
             <div class="card-body">
                 <div class="row align-items-center">

                     <div class="row g-2 ">

                         <div class="col-md-3  ">
                             {!! Form::label('english.student', __('english.campuses') . '') !!}
                             {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                         </div>
                         <div class="col-md-3 ">
                             {!! Form::label('english.classes', __('english.classes') . '') !!}
                             {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                         </div>
                         <div class="col-md-3 ">
                             {!! Form::label('english.sections', __('english.sections') . '') !!}
                             {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                         </div>
                         <div class="col-md-3 ">
                             {!! Form::label('status', __('english.student_status') . ':*') !!}
                             {!! Form::select('status', __('english.std_status'), 'active', ['class' => 'form-control select2 ','id'=>'students_list_filter_status','placeholder' => __('english.please_select'), 'required']); !!}
                         </div>

                     </div>
                 </div>


             </div>
         </div>
     </div>
 </div>
 {!! Form::open(['url' => action('IndividualFeeCollectionController@store'), 'method' => 'post', 'id' => 'student_transaction_due_form', 'files' => true]) !!}

 <div class="row" style="padding-left:10px; padding-right:10px ;">
     <div class="col-12">


         <div class="row ">
             <div class="col-md-2 ">
                 {!! Form::label('roll_no', __('english.roll_no')) !!}
                 {!! Form::text('roll_no', null, ['class' => 'form-control', 'placeholder' => __('english.roll_no'), 'id' => 'students_list_filter_roll_no']) !!}
             </div>
             <div class="col-md-2 ">
                 {!! Form::label('english.student_name', __('english.student_name') . '') !!}
                 {!! Form::text('student_name', null, ['class' => 'form-control','id'=>'students_list_filter_student_name','placeholder' => __('english.student_name')]) !!}
             </div>
             <div class="col-md-2 ">
                 {!! Form::label('english.father_name', __('english.father_name') . '') !!}
                 {!! Form::text('father_name', null, ['class' => 'form-control','id'=>'students_list_filter_father_name','placeholder' => __('english.father_name')]) !!}
             </div>
             <div class="col-md-2 ">
                 {!! Form::label('english.class', __('english.class') . '') !!}
                 {!! Form::text('current_class', null, ['class' => 'form-control','id'=>'current-class','placeholder' => __('english.class')]) !!}
             </div>
             <div class="col-md-2 ">
                 {!! Form::label('english.fee_month', __('english.fee_month') . ':*') !!}
                 {!! Form::select('month_id',__('english.months'),$now_month, ['class' => 'form-select select2','id'=>'month_id', 'required', 'style' => 'width:100%']) !!}
             </div>
             <div class="col-md-2 ">
                 {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                 {!! Form::select('session_id',$sessions,$active_session, ['class' => 'form-select select2 exam-session','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
             </div>

         </div>
         <div class="row pt-2">
             <div class="col-sm-8">
                 <div class="card">
                     <div class="card-body">
                         <div class="table-responsive">
                             <table class="table table-bordered table-striped ajax_get" width="100%" id="students_table" style="zoom:70%">
                                 <thead class="table-light" width="100%">
                                     <tr>
                                         {{-- <th>#</th> --}}
                                         <th>@lang('english.roll_no')</th>
                                         <th>@lang('english.student_name')</th>
                                         <th>@lang('english.father_name')</th>
                                         <th>@lang('english.current_class')</th>
                                         <th>@lang('english.section')</th>
                                         <th>@lang('english.tuition_fee')</th>
                                         <th>@lang('english.transport_fee')</th>
                                     </tr>
                                 </thead>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-sm-4">
                 <div class="card">
                     <div class="card-body">
                         <div class="row">

                             <div class="col-6">

                                 <div class="col-md-12 hide ">
                                     {!! Form::label('english.class', __('english.voucher_no') . '') !!}
                                     {!! Form::text('voucher_no', null, ['class' => 'form-control','id'=>'voucher_no','placeholder' => __('english.voucher_no')]) !!}
                                 </div>

                                 <div class="col-md-12 ">
                                     {!! Form::label('english.class', __('english.tuition_fee') . '') !!}
                                     {!! Form::text('student_tuition_fee', null, ['class' => 'form-control input_number' , 'required' ,'id'=>'tuition-fee','placeholder' => __('english.class')]) !!}
                                 </div>
                                 <div class="col-md-12 ">
                                     {!! Form::label('english.transport_fee', __('english.transport_fee') . '') !!}
                                     {!! Form::text('student_transport_fee', null, ['class' => 'form-control input_number' , 'required' ,'id'=>'transport-fee','placeholder' => __('english.class')]) !!}
                                 </div>
                             </div>

                             <div class="col-6">

                                 <div class="col-md-12 ">
                                     <img src="{{ url('/uploads/student_image/default.png') }}" class="student_image card-img-top" width="100%" height="107px" alt="...">

                                 </div>

                             </div>
                         </div>

                     </div>
                 </div>
             </div>


         </div>


         <div class="fee-heads"></div>

     </div>
 </div>

 {{ Form::close() }}


 @endsection

 @section('javascript')
 <script src="{{ asset('/js/indvidualfee.js?v=' . $asset_v) }}"></script>


 @endsection

