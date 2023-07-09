 <div class="card">
     <div class="card-body">
         <h5 class="card-title text-primary">@lang('english.subject_lessons')</h5>
         <p class="text-info">Lesson helps organizing the subject content. This may be a chapter of the book. You should create all the lessons for this subject. So that you could manage other activities of this book. This also helps manageing the progress tracking. This is one time process for the course of subject life.
         </p>

         <div class="d-lg-flex align-items-center mb-4 gap-3">
             
           {!! Form::label('chapter_id', __('english.chapters') . ':*') !!}
           {!! Form::select('chapter_id', $chapters, null, ['class' => 'form-select select2 ', 'required', 'id' => 'chapter_id', 'style' => 'width:30%', 'placeholder' => __('english.all')]) !!}
            
            {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}
           
             <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('Curriculum\ClassSubjectLessonController@create',[$class_subject->id]) }}" data-container=".lesson_modal">
                     <i class="bx bxs-plus-square"></i>@lang('english.add_new_lesson')</button></div>
         </div>
         <hr>
   @php
                $urdu_input=($class_subject->subject_input == 'ur') ? ' urdu' : '';
                @endphp
         <div class="table-responsive">
             <table class="table mb-0 {{ $urdu_input }}" width="100%" id="lessons_table">

                 <thead class="table-light" width="100%">
                     <tr>
                         <th>@lang('english.action')</th>
                         <th>@lang('english.lesson_title')</th>
                         <th>@lang('english.chapter_name')</th>
                         <th>@lang('english.description')</th>
                         <th>@lang('english.files')</th>
                     </tr>
                 </thead>
             </table>
         </div>
     </div>
 </div>

 <div class="modal fade lesson_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
