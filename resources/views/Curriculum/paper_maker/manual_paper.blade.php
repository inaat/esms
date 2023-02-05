 @extends('admin_layouts.app')
 @section('css')
 <style>
     td>table,
     th,
     td {
         border: 1px solid;
     }

 </style>
 @endsection
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.paper_maker')</div>
             <div class="ps-3">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb mb-0 p-0">
                         <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                         </li>
                         <li class="breadcrumb-item active" aria-current="page">@lang('english.paper_maker')</li>
                     </ol>
                 </nav>
             </div>
         </div>
         <!--end breadcrumb-->
         {!! Form::open([
         'url' => action('Curriculum\ManageSubjectController@getSubjectChapters'),
         'method' => 'GET',
         'onsubmit' => 'return false',
         'id' => 'get_chapter_form',
         ]) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                 <hr>
                 <div class="row ">

                     <div class="col-md-4 p-2 ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, $campus_id, [
                         'class' => 'form-select select2 global-campuses',
                         'style' => 'width:100%',
                         'required',
                         'placeholder' => __('english.please_select'),
                         ]) !!}
                     </div>
                     <div class="col-md-4 p-2">
                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                         {!! Form::select('class_id', $classes, $class_id, [
                         'class' => 'form-select select2 global-classes ',
                         'style' => 'width:100%',
                         'required',
                         'placeholder' => __('english.please_select'),
                         ]) !!}
                     </div>

                     <div class="col-md-4 p-2">
                         {!! Form::label('subjects', __('english.subjects') . ':*') !!}
                         {!! Form::select('subject_id', $class_subjects, $subject_id, [
                         'class' => 'form-select select2 global-subjects',
                         'required',
                         'id' => 'subjects',
                         'style' => 'width:100%',
                         'placeholder' => __('english.please_select'),
                         ]) !!}
                     </div>
                 </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                     <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" id="load_chapter" type="submit">
                             <i class="fas fa-filter"></i>@lang('english.load_chapter')</button></div>
                 </div>
             </div>
         </div>


         {{ Form::close() }}
         <div class="card">

             <div class="card-body">
                 <div class="row ">
                     <div class="col-md-12" style="margin-top:30px">
                         <div class="tab-content" id="PaperCardGrid">
                             <fieldset class="fieldSet">
                                 <legend class="lengend">
                                     <h4>
                                         <strong>@lang('english.select_chapters') <b style="color:red">(@lang('english.compulsory'))</b></strong><input type="checkbox" class="form-check-input mt-2 chapterName" name="allchap" id="allchap" style="  margin: revert;"> <strong style="border-bottom:4px  solid red;font-size: medium;">
                                             @lang('english.select_all_chapters')
                                         </strong>
                                     </h4>
                                 </legend>


                             </fieldset>
                             {!! Form::open([
                             'url' => action('Curriculum\ManageSubjectController@getSubjectChaptersQuestions'),
                             'method' => 'POST',
                             'id' => 'post_chapter_question_form',
                             ]) !!}

                             <div class="row" id="ChapTopicGrid">

                                 <input type="hidden" name="subject_id" value="{{$subject_id}}" />
                                 <input type="hidden" name="class_id" value="{{$class_id}}" />
                                 <input type="hidden" name="campus_id" value="{{ $campus_id}}" />
                                 @foreach ($subject_chapters as $chap)

                                 <div class="col-md-3" style="border-right:2px solid black;text-align:left;
font-weight:700;FONT-SIZE: 15px;height:70px;margin-bottom:20px;">

                                     <input type="checkbox" name="chapter_ids[]" @if(in_array($chap->id, $chapter_ids)) checked @endif value="{{ $chap->id }}"
                                     class="chapterName form-check-input mt-2" style="margin-right:5px"><span style="color:red">{{ $loop->iteration }}</span>
                                     &nbsp;&nbsp;{{ $chap->chapter_name }}

                                 </div>
                                 @endforeach
                             </div>

                             {{ Form::close() }}

                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="card">
             <div class="card-body">
                 {!! Form::open([
                 'url' => action('Curriculum\PaperMakerController@generateManualPaper'),
                 'method' => 'GET',
                 'id' => 'post_generate_manual_paper_form',
                 ]) !!}
                 {{-- {!! Form::open(['url' => '#', 'method' => 'post', 'id' => 'preview_setting_form', 'onsubmit' => 'return false']) !!} --}}

                 <div class="row ">
                     <h2 class="card-title text-primary">Select Questions</h2>
                     <input type="hidden" id="mcq_question_number" class="form-control" value="{{ $mcq_question_number }}" />
                     <input type="hidden" id="mcq_each_question_marks" name="mcq_each_question_marks" class="form-control" value="{{ $mcq_question_marks }}" />
                     <input type="hidden" id="fill_in_blanks_question_number" class="form-control" value="{{ $fill_in_blanks_question_number }}" />
                     <input type="hidden" id="fill_in_blanks_each_question_number" name="fill_in_blanks_each_question_number" class="form-control" value="{{ $fill_in_the_blank_marks }}" />


                     <input type="hidden" id="true_and_false_question_number" class="form-control" value="{{ $true_and_false_question_number }}" />
                     <input type="hidden" id="true_and_false_each_question_number" name="true_and_false_each_question_number" class="form-control" value="{{ $true_and_false_question_marks }}" />


                     <input type="hidden" id="short_question_question_number" class="form-control" value="{{ $short_question_question_number }}" />
                     <input type="hidden" name="short_question_question_marks" value="{{ $short_question_question_marks }}" />

                     <input type="hidden" id="long_question_question_number" class="form-control" value="{{ $long_question_question_number }}" />

                     <input type="hidden" name="long_question_question_marks" value="{{ $long_question_question_marks }}" />

                     <input type="hidden" name="column_matching_question_marks" value="{{ $column_matching_question_marks }}" />

                     {!! Form::hidden('subject_id', $subject_id) !!}
                     {!! Form::hidden('campus_id', $campus_id) !!}
                     {!! Form::hidden('class_id', $class_id) !!}
                     @php
                     $urdu_input=($class_subject->subject_input == 'ur') ? 'urdu_input urdu' : '';
                     @endphp
                     <div class="col-md-4 p-2">
                         {!! Form::label('paper_time', __('english.paper_time') . ':*') !!}
                         {!! Form::text('paper_time', $class_subject->subject_input == 'ur' ? '2 - گھنٹے' : '3 Hrs', [
                         'class' => 'form-control'. ' '. $urdu_input ,
                         'style' => 'width:100%',
                         'required',
                         ]) !!}
                     </div>
                     <div class="col-md-4 p-2">
                         {!! Form::label('total_marks', __('english.paper_total_marks') . ':*') !!}
                         {!! Form::text('paper_total_marks',$overallMark, [
                         'class' => 'form-control'. ' '. $urdu_input ,
                         'style' => 'width:100%',
                         'required',
                         ]) !!}
                     </div>

                     @foreach ($chapter_ids as $chapter_id)
                     <input type="hidden" name="chapter_ids[]" value="{{ $chapter_id }}" />
                     @endforeach
                     @if (!empty($mcq_questions))
                     @include('Curriculum.paper_maker.partials.select_mcq')
                     @endif
                     @if (!empty($fill_in_the_blanks_questions))
                     @include('Curriculum.paper_maker.partials.select_fill_in_the_blank')
                     @endif
                     @if (!empty($true_and_false_questions))
                     @include('Curriculum.paper_maker.partials.select_true_and_false')
                     @endif
                     @if (!empty($column_matching_questions))
                     @include('Curriculum.paper_maker.partials.select_column_matching_question')
                     @endif
                     @if (!empty($short_question_questions))
                     @include('Curriculum.paper_maker.partials.select_short_question')
                     @endif
                     @if (!empty($words_and_use_questions))
                     @include('Curriculum.paper_maker.partials.select_words_and_use_question')
                     @endif
                     @if (!empty($select_words_and_use_question_questions))
                     @include('Curriculum.paper_maker.partials.select_select_words_and_use_question')
                     @endif
                     @if (!empty($paraphrase_questions))
                     @include('Curriculum.paper_maker.partials.select_paraphrase_question')
                     @endif
                     @if (!empty($stanza_questions))
                     @include('Curriculum.paper_maker.partials.select_stanza_question')
                     @endif
                     @if (!empty($passage_questions))
                     @include('Curriculum.paper_maker.partials.select_passage_question')
                     @endif

                     @if (!empty($long_question_questions))
                     @include('Curriculum.paper_maker.partials.select_long_question')
                     <input name="long_question_choice" type="hidden" class="form-control" value="{{ $long_question_choice }}" />
                     @endif

                     @if (!empty($translation_to_english_questions))
                     @include('Curriculum.paper_maker.partials.select_translation_to_english_question')
                     @endif
                     @if (!empty($translation_to_urdu_questions))
                     @include('Curriculum.paper_maker.partials.select_translation_to_urdu_question')
                     @endif
                     @if (!empty($grammar_questions))
                     @include('Curriculum.paper_maker.partials.select_grammar_question')
                     @endif
                     @if (!empty($contextual_questions))
                     @include('Curriculum.paper_maker.partials.select_contextual_question')
                     @endif
                     @if (!empty($singular_and_plural_questions))
                     @include('Curriculum.paper_maker.partials.select_singular_and_plural_question')
                     @endif
                     @if (!empty($masculine_and_feminine_questions))
                     @include('Curriculum.paper_maker.partials.select_masculine_and_feminine_question')
                     @endif
                 </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                     <div class="ms-auto">
                     <button type="button" id="generate_paper" class="btn btn-primary pull-right btn-flat btn-block">@lang( 'english.paper_preview' )</button></div>

                 </div>
                 {{ Form::close() }}
             </div>
         </div>

     </div>
 </div>
 @endsection

 @section('javascript')
 <script src="{{ asset('js/paper.js?v=' . $asset_v) }}"></script>

 @endsection

