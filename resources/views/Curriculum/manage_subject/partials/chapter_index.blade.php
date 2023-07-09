 <div class="card">
     <div class="card-body">
         <h5 class="card-title text-primary">@lang('english.chapters')</h5>
         <div class="d-lg-flex align-items-center mb-4 gap-3">             
             {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}
             @can('chapter.view')
             <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('Curriculum\ManageSubjectController@create',[$class_subject->id]) }}" data-container=".chapter_modal">
                 <i class="bx bxs-plus-square"></i>@lang('english.add_new_chapter')</button></div>
                 @endcan
     </div>
     <hr>
        @php
                $urdu_input=($class_subject->subject_input == 'ur') ? ' urdu' : '';
                @endphp
     <div class="table-responsive mt-3">
         <table class="table mb-0 {{ $urdu_input }}" width="100%" id="chapter_table">

             <thead class="table-light" width="100%">
                 <tr>
                     <th>@lang('english.action')</th>
                     <th>@lang('english.chapter_name')</th>
                     <th>@lang('english.files')</th>
                 </tr>
             </thead>
         </table>
     </div>
 </div>
 </div>

 <div class="modal fade chapter_modal select" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

