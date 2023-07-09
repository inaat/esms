 @extends("admin_layouts.app")
@section('title', __('english.student_particular'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
        {!! Form::open(['url' => action('StudentController@getBulkEdit'), 'method' => 'post', 'id' =>'attendance_report_form']) !!}

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
                    {{-- <div class="col-md-3 p-2">
                        {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                        {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                    </div> --}}

        
                
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

 

