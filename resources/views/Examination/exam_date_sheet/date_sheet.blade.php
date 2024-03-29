 @extends("admin_layouts.app")
@section('title', __('english.date_sheet'))
 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->
             <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                 <div class="breadcrumb-title pe-3">@lang('english.roll_no_slip_printing')</div>
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
            {{-- {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-assign-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }} --}}
            {!! Form::open(['url' => action('Examination\ExamDateSheetController@postClassWiseRollSlipPrint'), 'method' => 'post', 'class'=>'needs-validation was-validated','novalidate'.'id' =>'search_student_fee' ,'files' => true]) !!}

             <div class="card">

                 <div class="card-body">
                     <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                       <hr>
                     <div class="row m-0">
                         <div class="col-md-3 p-2 ">
                             {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                             {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                         </div>
                         <div class="col-md-3 p-2">
                             {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                             {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                         </div>
                         <div class="col-md-3 p-2">
                             {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                             {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                         </div>
                           <div class="col-md-3 p-1">
                                {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                {!! Form::select('session_id',$sessions,null, ['class' => 'form-select select2 exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                        </div>
                        <div class="col-md-3 ">
                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                        {!! Form::select('exam_create_id',[],null, ['class' => 'form-select select2 exam_create_id exam_term_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                     </div>
                     <div class="d-lg-flex align-items-center mt-4 gap-3">
                         <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 exam-print-invoice"
                                 type="button"  href="#" data-href="{{ action('Examination\ExamDateSheetController@postClassWiseRollSlipPrint') }}">
                                 <i class="lni lni-printer"></i>@lang('english.print')</a>
                                 <a class="btn btn-primary radius-30 mt-2 mt-lg-0 exam-print-invoice"
                                 type="button"  href="#" data-href="{{ action('Examination\ExamDateSheetController@lablePrint') }}">
                                 <i class="lni lni-printer"></i>@lang('english.label_print')</a>
                                
                                 <a class="btn btn-primary radius-30 mt-2 mt-lg-0 exam-print-invoice"
                                 type="button"  href="#" data-href="{{ action('Examination\ExamDateSheetController@singleDateSheet') }}">
                                 <i class="lni lni-printer"></i>@lang('english.single_print')</a>
                                
                                
                                
                                
                                </div>
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



         });
     </script>
 @endsection
