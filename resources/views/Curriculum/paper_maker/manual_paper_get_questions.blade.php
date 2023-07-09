 @extends('admin_layouts.app')
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
                     <div class="row ">

                         <div class="col-md-12">
                             <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                                 <legend class="lengend">
                                     <h4><strong> @lang('english.question_configuration') <b style="color:red">(@lang('english.compulsory'))</b> </strong>
                                     </h4>
                                 </legend>
                                 <br>
                             </fieldset>
                             {!! Form::open([
                             'url' => action('Curriculum\ManageSubjectController@getQuestionsAccordingConfig'),
                             'method' => 'POST',
                             'id' => 'get_chapter_question_form',
                             ]) !!}
                             {!! Form::hidden('subject_id', $subject_id) !!}
                             {!! Form::hidden('campus_id', $campus_id) !!}
                             {!! Form::hidden('class_id', $class_id) !!}
                             @foreach ($chapter_ids as $chapter_id)
                             <input type="hidden" name="chapter_ids[]" value="{{ $chapter_id }}" />
                             @endforeach
                             <div class="row ">
                                 <table class="table table-responsive table-bordered">
                                     <thead>
                                         <tr>
                                            <th>@lang('english.select') </th>
                                             <th>@lang('english.question_types') </th>
                                             <th>@lang('english.number_of_questions_you_want') </th>
                                             <th>@lang('english.question_in_choice') </th>
                                             <th>@lang('english.question_marks') </th>
                                             <th>@lang('english.total_marks') </th>

                                             <th>@lang('english.available_questions') </th>
                                         </tr>
                                     </thead>
                                     <tbody id="qt">
                                         @if ($total_mcq > 0)
                                         <tr id="1">
                                             <td><input type="checkbox" name="mcq_questions" class="bindQs  form-check-input mt-2" id="1" value="mcq" qtype="MCQs"></td>
                                             <td>@lang('english.mcq')</td>
                                             <td>
                                                 <input type="number" class="form-control input_number questionNumber" name="mcq_question_number" id="questionNumber_1" data-rule-max-value="{{ $total_mcq }}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_mcq)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="mcq_question_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" name="mcq_question_marks" min="0" id="QuestionMarks_1">
                                             </td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="mcq_total_question_marks" readonly="" id="TotalQuesMarks_1"></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_mcq }}" id="questionAvailable_1"></td>
                                         </tr>
                                         @endif
                                         @if ($total_fill_in_the_blanks > 0)
                                         <tr id="2">
                                             <td><input type="checkbox" name="fill_in_the_blanks_questions" class="bindQs  form-check-input mt-2" id="2" value="fill_in_the_blanks" qtype="Fill in The Blanks"></td>
                                             <td>@lang('english.fill_in_the_blanks')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" name="fill_in_the_blanks_question_number" min="0" id="questionNumber_3" data-rule-max-value="{{ $total_fill_in_the_blanks }}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_fill_in_the_blanks)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="fill_in_the_blanks_question_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="fill_in_the_blanks_question_marks" id="QuestionMarks_3"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="fill_in_the_blanks_total_question_marks" readonly="" id="TotalQuesMarks_3"></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_fill_in_the_blanks }}" id="questionAvailable_3"></td>
                                         </tr>
                                         @endif
                                         @if ($total_true_and_false > 0)
                                         <tr id="3">
                                             <td><input type="checkbox" name="true_and_false_questions" class="bindQs  form-check-input mt-2" id="3" value="true_and_false" qtype="True/False"></td>
                                             <td>@lang('english.true_and_false')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="true_and_false_question_number" id="questionNumber_2" data-rule-max-value="{{ $total_true_and_false }}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_true_and_false)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="true_and_false_question_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="true_and_false_question_marks" id="QuestionMarks_2"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="true_and_false_total_question_marks" readonly="" id="TotalQuesMarks_2"></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_true_and_false }}" id="questionAvailable_2"></td>
                                         </tr>
                                         @endif

                                         @if ($total_column_matching > 0)
                                         <tr id="4">
                                             <td><input type="checkbox" name="column_matching_questions" class="bindQs  form-check-input mt-2" id="4" value="column_matching_questions" qtype="Short Question"></td>
                                             <td>@lang('english.column_matching')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="column_matching_question_number" data-rule-max-value="{{ $total_column_matching }}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_column_matching)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="column_matching_question_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="column_matching_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="column_matching_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_column_matching }}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_short_question > 0)
                                         <tr id="5">
                                             <td><input type="checkbox" name="short_question_questions" class="bindQs  form-check-input mt-2" id="6" value="short_question_questions" qtype="Short Question"></td>
                                             <td>@lang('english.short_question')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="short_question_question_number" data-rule-max-value="{{ $total_short_question }}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_short_question)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="short_question_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="short_question_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="short_question_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_short_question }}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_words_and_use > 0)
                                         <tr id="6">
                                             <td><input type="checkbox" name="words_and_use_questions" class="bindQs  form-check-input mt-2" id="6" value="words_and_use_questions" qtype="Short Question"></td>
                                             <td>@lang('english.words_and_use')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="words_and_use_question_number" data-rule-max-value="{{ $total_words_and_use}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_words_and_use)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="words_and_use_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="words_and_use_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="words_and_use_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_words_and_use}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_singular_and_plural > 0)
                                         <tr id="7">
                                             <td><input type="checkbox" name="singular_and_plural_questions" class="bindQs  form-check-input mt-2" id="7" value="singular_and_plural_questions" qtype="Short Question"></td>
                                             <td>@lang('english.singular_and_plural')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="singular_and_plural_question_number" data-rule-max-value="{{ $total_singular_and_plural}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_singular_and_plural)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="singular_and_plural_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="singular_and_plural_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="singular_and_plural_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_singular_and_plural}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_masculine_and_feminine > 0)
                                         <tr id="8">
                                             <td><input type="checkbox" name="masculine_and_feminine_questions" class="bindQs  form-check-input mt-2" id="8" value="masculine_and_feminine_questions" qtype="Short Question"></td>
                                             <td>@lang('english.masculine_and_feminine')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="masculine_and_feminine_question_number" data-rule-max-value="{{ $total_masculine_and_feminine}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_masculine_and_feminine)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="masculine_and_feminine_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="masculine_and_feminine_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="masculine_and_feminine_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_masculine_and_feminine}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_paraphrase > 0)
                                         <tr id="9">
                                             <td><input type="checkbox" name="paraphrase_questions" class="bindQs  form-check-input mt-2" id="9" value="paraphrase_questions" qtype="Short Question"></td>
                                             <td>@lang('english.paraphrase')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="paraphrase_question_number" data-rule-max-value="{{ $total_paraphrase}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_paraphrase)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="paraphrase_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="paraphrase_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="paraphrase_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_paraphrase}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif

                                         @if ($total_stanza > 0)
                                         <tr id="10">
                                             <td><input type="checkbox" name="stanza_questions" class="bindQs  form-check-input mt-2" id="10" value="stanza_questions" qtype="Short Question"></td>
                                             <td>@lang('english.stanza')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="stanza_question_number" data-rule-max-value="{{ $total_stanza}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_stanza)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="stanza_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="stanza_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="stanza_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_stanza}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif


                                         @if ($total_passage > 0)
                                         <tr id="11">
                                             <td><input type="checkbox" name="passage_questions" class="bindQs  form-check-input mt-2" id="11" value="passage_questions" qtype="Short Question"></td>
                                             <td>@lang('english.passage')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="passage_question_number" data-rule-max-value="{{ $total_passage}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_passage)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="passage_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="passage_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="passage_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_passage}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif

                                         @if ($total_long_question > 0)
                                         <tr id="12">
                                             <td><input type="checkbox" name="long_question_questions" class="bindQs  form-check-input mt-2" id="12" value="long_question_questions" qtype="Long Question"></td>
                                             <td>@lang('english.long_question')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" name="long_question_question_number" min="0" id="questionNumber_6" data-rule-max-value="{{ $total_long_question }}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_long_question)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="long_question_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" name="long_question_question_marks" min="0" id="QuestionMarks_6"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" name="long_question_total_question_marks" step="0.01" min="0" readonly="" id="TotalQuesMarks_6"></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_long_question }}" id="questionAvailable_6"></td>
                                         </tr>
                                         @endif

                                         @if ($total_grammar > 0)
                                         <tr id="13">
                                             <td><input type="checkbox" name="grammar_questions" class="bindQs  form-check-input mt-2" id="13" value="grammar_questions" qtype="Short Question"></td>
                                             <td>@lang('english.grammar_question')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="grammar_question_number" data-rule-max-value="{{ $total_grammar}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_grammar)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="grammar_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="grammar_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="grammar_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_grammar}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_contextual > 0)
                                         <tr id="14">
                                             <td><input type="checkbox" name="contextual_questions" class="bindQs  form-check-input mt-2" id="14" value="contextual_questions" qtype="Short Question"></td>
                                             <td>@lang('english.contextual')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="contextual_question_number" data-rule-max-value="{{ $total_contextual}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_contextual)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="contextual_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="contextual_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="contextual_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_contextual}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_translation_to_urdu > 0)
                                         <tr id="15">
                                             <td><input type="checkbox" name="translation_to_urdu_questions" class="bindQs  form-check-input mt-2" id="15" value="translation_to_urdu_questions" qtype="Short Question"></td>
                                             <td>@lang('english.translation_to_urdu')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="translation_to_urdu_question_number" data-rule-max-value="{{ $total_translation_to_urdu}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_translation_to_urdu)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="translation_to_urdu_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="translation_to_urdu_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="translation_to_urdu_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_translation_to_urdu}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         @if ($total_translation_to_english > 0)
                                         <tr id="16">
                                             <td><input type="checkbox" name="translation_to_english_questions" class="bindQs  form-check-input mt-2" id="16" value="translation_to_english_questions" qtype="Short Question"></td>
                                             <td>@lang('english.translation_to_english')</td>
                                             <td><input type="number" class="form-control input_number questionNumber" min="0" name="translation_to_english_question_number" data-rule-max-value="{{ $total_translation_to_english}}" data-msg-max-value="{{ __('english.minimum_value_error_msg', ['value' => @num_format($total_translation_to_english)]) }}">
                                             </td>
                                             <td>
                                                 <input type="number" class="form-control input_number choiceQuestion" name="translation_to_english_choice">
                                             </td>
                                             <td><input type="number" class="form-control input_number QuestionMarks" min="0" name="translation_to_english_question_marks"></td>
                                             <td><input type="text" class="form-control input_number TotalQuesMarks" step="0.01" min="0" name="translation_to_english_total_question_marks" readonly=""></td>

                                             <td><input type="number" class="form-control input_number questionAvailable" readonly="" value="{{ $total_translation_to_english}}" id="questionAvailable_4"></td>
                                         </tr>
                                         @endif
                                         <tr>
                                             <td colspan="5" style="text-align:right">
                                                 <span class="calcMarks btn btn-primary" id="calcMarks">@lang('english.calculate_marks')</span>
                                             </td>
                                             <td>
                                                 <input type="text" name="overallMark" class="overallMark form-control" min="0" readonly="" id="overallMark" style="width:100%">
                                             </td>

                                         </tr>
                                     </tbody>
                                 </table>
                             </div>
                             <div class="row">
                                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                                     <div class="ms-auto"><button class="radius-30 mt-2 mt-lg-0  btn btn-info LoadQuestion" id="LoadQuestions" type="submit">
                                             <i class="fas fa-filter"></i>@lang('english.load_question')</button></div>
                                 </div>

                             </div>
                             {{ Form::close() }}

                         </div>
                     </div>
                 </div>
             </div>

         </div>
     </div>
     @endsection

     @section('javascript')
     <script src="{{ asset('js/paper.js?v=' . $asset_v) }}"></script>

     @endsection

