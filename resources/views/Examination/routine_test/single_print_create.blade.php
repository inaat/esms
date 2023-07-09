@extends("admin_layouts.app")
@section('title', __('english.routine_test_print'))
 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->
             <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                 <div class="breadcrumb-title pe-3">@lang('english.routine_test_print')</div>
                 <div class="ps-3">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb mb-0 p-0">
                             <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                             </li>
                             <li class="breadcrumb-item active" aria-current="page">@lang('english.routine_test_print')</li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <!--end breadcrumb-->
            {{-- {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-assign-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }} --}}
            {!! Form::open(['url' => action('Examination\RoutineTestController@routinePrint'), 'method' => 'post', 'id' =>'print_report_form']) !!}

             <div class="card">

                 <div class="card-body">
                     <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                       <hr>
                     <div class="row ">
                   
                            <div class="col-md-4 p-1">
                                {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                                {!! Form::select('campus_id',$campuses,null, ['class' => 'form-select select2 campuses ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'campus_id']) !!}
                            </div>
            
                            <div class="col-md-4 p-1">
                                {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                {!! Form::select('class_id',[],null, ['class' => 'form-select select2 classes','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'class_ids']) !!}
                            </div>
            
                            <div class="col-md-4 p-1">
                                {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                                {!! Form::select('class_section_id',[],null, ['class' => 'form-select select2 class_sections  ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! Form::label('subjects', __('english.subjects') . ':') !!}
                                {!! Form::select('subject_id',[], null, ['class' => 'form-select select2 global-section-subjects', 'id' => 'global-section-subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="col-md-4 p-1">
                                {!! Form::label('english.students', __('english.students') . ':*') !!}
                                {!! Form::select('sibiling_student_id',[],null, ['class' => 'form-select select2 sibiling_student_id','id'=>'sibiling_student_id', 'style' => 'width:100%',  'placeholder' => __('english.please_select')]) !!}
                                             <div class="sibling_msg">
                                </div>
                            </div>
                          <div class="col-md-4">
                            {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                            <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                {!! Form::text('list_filter_date_range', null, ['class' => 'form-control', 'id'=>'list_filter_date_range','readonly', 'placeholder' => __('english.date_range')]) !!}

                            </div>
                        </div>
                   </div>
                      <div class="d-lg-flex align-items-center mt-4 gap-3">
                         <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0"
                                 type="submit">
                                 <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                     </div>
                 </div>
             </div>


            {{ Form::close() }}



         </div>
     </div>
   
 @endsection

 @section('javascript')
 <script src="{{ asset('/js/student.js?v=' . $asset_v) }}"></script>

     <script type="text/javascript">
         $(document).ready(function() {



         });
     </script>
 @endsection
