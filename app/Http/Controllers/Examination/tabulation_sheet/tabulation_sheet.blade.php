 @extends("admin_layouts.app")
 @section('title', __('english.roles'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.tabulation_sheet')</div>
             <div class="ps-3">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb mb-0 p-0">
                         <li class="breadcrumb-item"><a href="{{ url('/') }} "><i class="bx bx-home-alt"></i></a>
                         </li>
                     </ol>
                 </nav>
             </div>
         </div>
         <!--end breadcrumb-->
         {!! Form::open(['url' => action('Examination\TabulationController@store'), 'method' => 'post','id' =>'tabulation-sheet-form']) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                 <hr>
                 <div class="row m-0">
                     <div class="col-md-3 p-2 ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, $campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                     </div>
                     <div class="col-md-3 p-2">
                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                         {!! Form::select('class_id',$classes, $class_id, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                     </div>
                     <div class="col-md-3 p-2">
                         {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                         {!! Form::select('class_section_id', $sections, $class_section_id, ['class' => 'form-select select2 global-class_sections','required', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                     </div>
                     <div class="col-md-3 p-1">
                         {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                         {!! Form::select('session_id',$sessions,$session_id, ['class' => 'form-select select2 exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                     </div>
                     <div class="col-md-3 ">
                         {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                         {!! Form::select('exam_create_id',$terms,$exam_create_id, ['class' => 'form-select select2 exam_create_id exam_term_id','required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                 </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                     <div class="ms-auto">

                         <button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                             <i class="fas fa-filter"></i>@lang('english.filter')</button>
                     </div>
                 </div>
             </div>
         </div>
         {{ Form::close() }}

         @if (isset($students))

         @php
         // $f = new \NumberFormatter('eng', \NumberFormatter::SPELLOUT);
         $nf = new NumberFormatter('eng', NumberFormatter::ORDINAL);
         @endphp
         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary"><i class="fas fa-users"></i>@lang('english.tabulation_sheet')</h6>
                 <hr>
                 <div class="table-responsive">
                     <table class="table table-bordered table-hover table-condensed mb-none" id="tableExport">
                         <thead class="text-dark">
                             <tr>

                                 <th>#</th>
                                 <th>@lang('english.roll_no')</th>
                                 <th>@lang('english.student_name')</th>
                                 <th>@lang('english.father_name')</th>
                                 @php
                                 foreach($students[0]->subject_result as $subject){

                                 echo '<th>' . $subject->subject_name->name. " (" . $subject->total_mark . ')</th>';
                                 }
                                 @endphp
                                 <th>@lang('english.total_marks')</th>
                                 <th>@lang('english.per.%')</th>
                                 <th>@lang('english.class') @lang('english.position')</th>
                                 <th>@lang('english.section') @lang('english.position')</th>
                                 <th>@lang('english.grade')</th>
                                 <th>@lang('english.remarks')</th>
                             </tr>
                         </thead>
                         <tbody>
                             @foreach ($students as $student)
                             <tr>
                                 <td>{{ $loop->iteration }}</td>
                                 <td>{{ $student->student->roll_no }}</td>
                                 <td>{{ ucwords($student->student->first_name.'  '.$student->student->last_name) }}</td>
                                 <td>{{ ucwords($student->student->father_name)}}</td>
                                 @foreach ($student->subject_result as $subject)
                                 {{-- {{ $subject->subject_name->name }} --}}
                                 <td>{{ $subject->total_obtain_mark }}/{{ $subject->total_mark }}</td>
                                 @endforeach
                                 <td>{{ number_format($student->obtain_mark,0)}}/{{ number_format($student->total_mark,0) }}</td>
                                 <td>{{ @num_format($student->final_percentage)}}%</td>
                                 <td>
                                     {{ $nf->format($student->class_position) }}
                                 </td>
                                 <td>
                                     {{ $nf->format($student->class_section_position) }}
                                 </td>
                                 <td>
                                     @if(!empty($student->grade))
                                     {{ ucwords($student->grade->name) }}
                                     @endif
                                 </td>
                                 <td>
                                     {{ ucwords($student->remark) }}
                                 </td>
                             </tr>
                             @endforeach
                         </tbody>
                     </table>
                 </div>


             </div>
         </div>

         @endif

     </div>
 </div>

 @endsection

 @section('javascript')

 <script type="text/javascript">
     $(document).ready(function() {
         $('form#tabulation-sheet-form').validate();


     });

 </script>
 @endsection

