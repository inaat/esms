 @extends("admin_layouts.app")
 @section('title', __('english.roles'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         {!! Form::open(['url' => action('NoteBookStatusController@noteBookEmptyPrintStore'), 'method' => 'post', 'id' =>'form']) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.note_book_status_empty_form')</h6>
                 <hr>
                 <div class="row m-0">
                     <div class="col-md-3  ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                     <div class="col-md-3 ">
                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                         {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes','style' => 'width:100%']) !!}
                     </div>

                     <div class="col-md-3 p-1">
                         {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                         {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                 </div>

                   <div class="d-lg-flex align-items-center mt-4 gap-3">
                 <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                         <i class="fas fa-print"></i>@lang('english.print')</button></div>

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

