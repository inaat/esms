 <div class="card">
     <div class="card-body">
         <h5 class="card-title text-primary">@lang('english.planning_&_progress')</h5>
         <p class="text-info">You may create a plan to complete the subject content for the current session. Later, you can track the subject progress. It is very helpful for teachers, students and parents to follow the plan.
         </p>
         {!! Form::open(['url' => action('Curriculum\ClassSubjectProgressController@store'), 'method' => 'post', 'id' =>'progress_add_form' ]) !!}

         <div class="row align-items-center">
             {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}
             {!! Form::hidden('campus_id', $class_subject->campus_id,[ 'id' => 'campus_id', ]) !!}
             {!! Form::hidden('class_id', $class_subject->class_id,[ 'id' => 'class_id', ]) !!}
             @if(!empty($ClassSubjects))
             {!! Form::hidden('class_section_id', $ClassSubjects->class_section_id,[ 'id' => 'class_section_id', ]) !!}
             @endif
             @if(Auth::User()->user_type=='admin' || Auth::User()->user_type=='other')
             <div class="col-sm-2">
                 {!! Form::select('class_section_id', $section, null, ['class' => 'form-select select2 ', 'required', 'id' => '', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
             </div>
             @endif
             <div class="col-sm-2">
                 {!! Form::select('chapter_id', $chapters, null, ['class' => 'form-select select2 ', 'required', 'id' => 'chapter_progress', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
             </div>
             <div class="col-sm-2">
                 {!! Form::select('lesson_id', [], null, ['class' => 'form-select select2 lessons', 'required', 'id' => 'lessons_ids','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
             </div>

             <div class="col-sm-2 ">
                 <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                     {!! Form::text('start_date',null, ['class' => 'form-control date-picker', 'placeholder' => __('settings.start_date'), 'readonly']) !!}

                 </div>
             </div>
             <div class="col-sm-2">
                 <button type="submit" class="btn  btn-primary">@lang( 'english.save' )</button>
             </div>
         </div>
         {!! Form::close() !!}

         <div class="table-responsive mt-3">
             <hr>
             @php
             $urdu_input=($class_subject->subject_input == 'ur') ? ' urdu' : '';
             @endphp
             <table class="table mb-0 {{ $urdu_input }}" width="100%" id="progress_table">

                 <thead class="table-light" width="100%">
                     <tr>
                         <th>@lang('english.action')</th>
                         <th>@lang('english.lesson_title')</th>
                         <th>@lang('english.chapter_name')</th>
                         <th>@lang('english.status')</th>
                     </tr>
                 </thead>
             </table>
         </div>
     </div>
 </div>
 <div class="modal fade progress_modal select" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
