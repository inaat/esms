 @extends("admin_layouts.app")
 @section('title', __('english.note_book_status_add'))
 @section('wrapper')
 
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         {!! Form::open(['url' => action('NoteBookStatusController@noteBookAssignSearch'), 'method' => 'post', 'id' =>'form']) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.note_book_status_add')</h6>
                 <hr>
                 <div class="row m-0">
                     <div class="col-md-3  ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, $campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                     <div class="col-md-3 ">
                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                         {!! Form::select('class_id',$classes, $class_id, ['class' => 'form-select select2 global-classes','style' => 'width:100%']) !!}
                     </div>

                     <div class="col-md-3 ">
                         {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                         {!! Form::select('class_section_id',$sections, $class_section_id, ['class' => 'form-select select2 global-class_sections', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                     <div class="col-sm-3 ">
                         {!! Form::label('check_date', __('english.check_date') . ':*', ['classs' => 'form-lable']) !!}

                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                             {!! Form::text('check_date', @format_date($date), ['class' => 'form-control date-picker', 'placeholder' => __('english.check_date'), 'readonly']) !!}

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
         @if (isset($note_books))
         {!! Form::open(['url' => action('NoteBookStatusController@noteBookAssignPost'), 'method' => 'post', 'class' => '', '' . 'id' => 'store_student_fee', 'files' => true]) !!}

         <div class="row">

             <div class="col-lg-12">
            <input type="hidden" name="campus_id" value="{{ $campus_id }}">
            <input type="hidden" name="class_id" value="{{ $class_id }}">
            <input type="hidden" name="class_section_id" value="{{ $class_section_id }}">
            <input type="hidden" name="check_date" value="{{ $date }}">
                <div class="col-md-6 p-2">
                    {!! Form::label('teacher', __('english.teachers') . ':*') !!}
                    {!! Form::select('employee_id',$teachers,null, ['class' => 'form-select select2 ','required', 'id' => '', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                 <div class="card">
                     <div class="card-body">
                         <div class="table-responsive">
                             <table  id="note_table" class="table table-striped table-bordered table-sm " cellspacing="0" >
                                 <thead class="table-light" width="100%">
                                     <tr>
                                         <th>#</th>
                                         <th>@lang('english.roll_no')</th>
                                         <th>@lang('english.student_name')</th>
                                         @foreach ($class_subjects as $subject)
                                         <th>{{ $subject->name }}</th>
                                         @endforeach

                                     </tr>
                                 </thead>
                                 <tbody class="">
                                     @foreach ($note_books as $note)
                                     <tr>
                                         <td>{{ $loop->iteration }}</td>
                                         <td>{{ ucwords($note['roll_no']) }}
                                         <td>{{ ucwords($note['student_name']) }}
                                             <input type="hidden" name="data[{{$note['student_id']}}][student_id]" value="{{ $note['student_id']}}">
                                         </td>
                                         @foreach ($note['subjects_list'] as $sub)
                                         <td>
                                             <table class="table mb-0" width="100%" id="students_table">
                                                 <thead class="table-light" width="100%" style="zoom:70%">
                                                     <tr>
                                                         <th>COM</th>
                                                         <th>INC</th>
                                                         <th>MIS</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody class="">
                                                     <tr>
                                                       
                                                         <td>
                                                             <input type="radio" class="form-check-input" @if($sub['status']=='complete') checked @endif name="data[{{$note['student_id']}}][subjects][{{  $sub['subject_id'] }}][status]" value="{{'complete'}}">
                                                         </td>
                                                         <td>
                                                             <input type="radio" class="form-check-input" @if($sub['status']=='incomplete') checked @endif name="data[{{$note['student_id']}}][subjects][{{  $sub['subject_id'] }}][status]" value="{{'incomplete'}}">
                                                         </td>
                                                         <td>
                                                             <input type="radio" class="form-check-input" @if($sub['status']=='Missing') checked @endif name="data[{{$note['student_id']}}][subjects][{{  $sub['subject_id'] }}][status]" value="{{'Missing'}}">
                                                         </td>
                                                     </tr>
                                                 </tbody>

                                             </table>
                                             <input type="hidden" name="data[{{$note['student_id']}}][subjects][{{  $sub['subject_id'] }}][subject_id]" value="{{ $sub['subject_id']}}">
                                         </td>
                                         @endforeach
                                     </tr>
                                     @endforeach
                                 </tbody>
                                 <tr>
                                     <td colspan="7">
                                         <div class="text-center">
                                             <button type="submit"  class="btn btn-primary radius-30 mt-2 mt-lg-0 fix-gr-bg mb-0 submit" >
                                                 <span class="ti-save pr"></span>
                                                 @lang('english.save') 
                                             </button>
                                         </div>
                                     </td>
                                 </tr>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         {{ Form::close() }}
         @endif
     </div>
 </div>

 @endsection
 @section('javascript')
 <script type="text/javascript">
     $(document).ready(function() {
  $('#note_table').DataTable({
    "scrollX": true,
    "scrollY": 300,
    //dom: 'T<"clear"><"button">lfrtip',
    bLengthChange: false,
    "bPaginate": false,
  });
     });

 </script>
 @endsection

