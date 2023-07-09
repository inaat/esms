

    <div class="modal-dialog modal-xl">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\ClassSubjectQuestionBankController@update', [$class_subject_question_bank->id]), 'method' => 'PUT', 'id' => 'question_edit_form' ]) !!}
        {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title text-uppercase" id="exampleModalLabel">@lang('english.add_new_question') For <small>(@lang('english.subject_detail') - {{ $class_subject->name }} of class {{ $class_subject->classes->title }})</small></h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}

        <div class="modal-body">
            <div class="row m-0">
                <div class="col-md-3 p-2  ">
                    {!! Form::label('chapter_id', __('english.chapters') . ':*') !!}
                    {!! Form::select('chapter_id', $chapters,$class_subject_question_bank->chapter_id, ['class' => 'form-select select2 chapter_question', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>

                <div class="col-md-3 p-2">
                    {!! Form::label('english.question_types', __('english.question_types') . ':*') !!}
                    {!! Form::select('type',__('english.question_type'),$class_subject_question_bank->type, ['class' => 'form-select select2 question_type', 'id'=>'question_type_trigger','required', 'style' => 'width:100%']) !!}
                </div>
                <div class="col-md-3 p-2">
                    {!! Form::label('english.marks', __('english.marks') . ':*') !!}
                    {!! Form::text('marks', $class_subject_question_bank->marks, ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'english.marks' ) ]); !!}
                </div>
                @php
                $urdu_input=($class_subject->subject_input == 'ur') ? 'urdu_input urdu urdu_editor ' : 'english_editor';
                @endphp
                <div class="clearfix"></div>
                <div class="col-sm-12  p-2">
                    <div class="form-group ">
                        {!! Form::label('question', __('english.question') . ':') !!}
                        {!! Form::textarea('question',$class_subject_question_bank->question, ['class' => 'form-control '. ' '. $urdu_input ,'required', ]); !!}
                        <span class="question_type_error error"></span>

                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6  p-2 hide hide-column">
                    <div class="form-group">
                        {!! Form::label('column_a', __('english.column_a') . ':') !!}
                        {!! Form::textarea('column_a',$class_subject_question_bank->column_a, ['class' => ' hide-column form-control'. ' '. $urdu_input ,'id'=>$urdu_input ]); !!}
                        <span class="column_a_type_error error"></span>

                    </div>
                </div>
                <div class="col-sm-6 p-2 hide hide-column">
                    <div class="form-group">
                        {!! Form::label('column_b', __('english.column_b') . ':') !!}
                        {!! Form::textarea('column_b',$class_subject_question_bank->column_b, ['class' => 'hide-column form-control'. ' '. $urdu_input ,'id'=>$urdu_input ]); !!}
                        <span class="column_b_type_error error"></span>

                    </div>
                </div>

            </div>
            <div class="row mt-1 mcqs-form">
                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_a') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">A</i>
                        {!! Form::text('option_a',$class_subject_question_bank->option_a, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input , 'required','placeholder' => __('english.option_a')]) !!}</div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_b') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">B</i>
                        {!! Form::text('option_b', $class_subject_question_bank->option_b, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input , 'required','placeholder' => __('english.option_b')]) !!}</div>
                </div>               
                 <div class="clearfix"></div>
                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_c') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">C</i>
                        {!! Form::text('option_c', $class_subject_question_bank->option_c, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input ,'placeholder' => __('english.option_c')]) !!}</div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_d') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">D</i>
                        {!! Form::text('option_d', $class_subject_question_bank->option_d, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input , 'placeholder' => __('english.option_d')]) !!}</div>
                </div>

            </div>
            <div class="row mt-1 form-data ">

                <div class="clearfix"></div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('hint', __('english.hint') . ':') !!}
                        {!! Form::textarea('hint',$class_subject_question_bank->hint, ['class' => 'form-control']); !!}
                    </div>
                </div>
                
                <div class="col-md-4 p-2  hide-answer">
                 @if($class_subject_question_bank->type == 'mcq')
                    {!! Form::label('english.answer', __('english.answer') . ':*') !!}
                    {!! Form::select('answer',__('english.quest_options'),$class_subject_question_bank->answer, ['class' => 'form-select select2 answer-input','placeholder' => __('english.please_select'), 'required', 'style' => 'width:100%']) !!}
                   @else
                                       {!! Form::label('english.answer', __('english.answer') . ':*') !!}
                    {!! Form::select('answer',__('english.true_false'),$class_subject_question_bank->answer, ['class' => 'form-select select2 answer-input','placeholder' => __('english.please_select'), 'required', 'style' => 'width:100%']) !!}

                    @endif
                </div>
            </div>

            
            <div class="modal-footer">

                <button type="submit" class="btn btn-primary">@lang( 'english.update' )</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("form#question_add_form").validate({
            rules: {
                question: {
                    required: true
                , }
            , }
        , });


    });

</script>


