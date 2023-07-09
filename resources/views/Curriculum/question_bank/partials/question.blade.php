
 
 <div class="card">
     <div class="card-body">
         <h5 class="card-title text-primary">@lang('english.question_bank')</h5>
         <div class="d-lg-flex align-items-center mb-4 gap-3">

             {!! Form::label('chapter_id', __('english.chapters') . ':*') !!}
             {!! Form::select('chapter_id', $chapters, null, ['class' => 'form-select select2 ', 'required', 'id' => 'qt_chapter_id', 'style' => 'width:30%', 'placeholder' => __('english.all')]) !!}
                   
             {!! Form::label('english.question_types', __('english.question_types') . ':*') !!}
             {!! Form::select('type',__('english.question_type'),null, ['class' => 'form-select select2 ', 'required' ,'id'=>'question_type_filter', 'style' => 'width:30%','placeholder' => __('english.all')]) !!}
             
             
             {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}
                    @php
                $urdu_input=($class_subject->subject_input == 'ur') ? ' urdu' : '';
                @endphp
                @can('question_bank.create')
             <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-question-modal" data-href="{{ action('Curriculum\ClassSubjectQuestionBankController@create',[$class_subject->id]) }}" data-container=".question_bank_modal">
                 <i class="bx bxs-plus-square"></i>@lang('english.add_new_question')</button></div>
                 @endcan
     </div>
     <hr>
     <div class="table-responsive mt-3">
         <table class="table mb-0 {{ $urdu_input }}" width="100%" id="question_bank_table">

             <thead class="table-light " width="100%">
                 <tr>
                     <th>@lang('english.action')</th>
                     <th>@lang('english.chapter_name')</th>
                     <th>@lang('english.question')</th>
                     <th>@lang('english.marks')</th>
                     <th>@lang('english.type')</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>
 </div>

 <div class="modal fade question_bank_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

