    <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\ClassSubjectQuestionBankController@store'), 'method' => 'post', 'id' => 'question_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title text-uppercase" id="exampleModalLabel">@lang('english.add_new_question') For <small>(@lang('english.subject_detail') - {{ $class_subject->name }} of class {{ $class_subject->classes->title }})</small></h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}

        <div class="modal-body">
            <div class="row m-0">
                <div class="col-md-3 p-2  ">
                    {!! Form::label('chapter_id', __('english.chapters') . ':*') !!}
                    {!! Form::select('chapter_id', $chapters,null, ['class' => 'form-select select2 chapter_question', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>

                <div class="col-md-3 p-2">
                    {!! Form::label('english.question_types', __('english.question_types') . ':*') !!}
                    {!! Form::select('type',__('english.question_type'),'mcq', ['class' => 'form-select select2 question_type', 'required', 'style' => 'width:100%']) !!}
                </div>
                <div class="col-md-3 p-2">
                    {!! Form::label('english.marks', __('english.marks') . ':*') !!}
                    {!! Form::text('marks', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'english.marks' ) ]); !!}
                </div>
                @php
                $urdu_input=($class_subject->subject_input == 'ur') ? 'urdu_input  urdu_editor ' : 'english_editor';
                @endphp
                <div class="clearfix"></div>
                <div class="col-sm-12  p-2">
                    <div class="form-group ">
                        {!! Form::label('question', __('english.question') . ':') !!}
                        {!! Form::textarea('question',null, ['class' => 'form-control '. ' '. $urdu_input ,'required', ]); !!}
                        <span class="question_type_error error"></span>

                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6  p-2 hide hide-column">
                    <div class="form-group">
                        {!! Form::label('column_a', __('english.column_a') . ':') !!}
                        {!! Form::textarea('column_a',null, ['class' => ' hide-column form-control'. ' '. $urdu_input ,'id'=>$urdu_input ]); !!}
                        <span class="column_a_type_error error"></span>

                    </div>
                </div>
                <div class="col-sm-6 p-2 hide hide-column">
                    <div class="form-group">
                        {!! Form::label('column_b', __('english.column_b') . ':') !!}
                        {!! Form::textarea('column_b',null, ['class' => 'hide-column form-control'. ' '. $urdu_input ,'id'=>$urdu_input ]); !!}
                        <span class="column_b_type_error error"></span>

                    </div>
                </div>

            </div>
            <div class="row mt-1 mcqs-form">
                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_a') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">A</i>
                        {!! Form::text('option_a', null, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input , 'required','placeholder' => __('english.option_a')]) !!}</div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_b') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">B</i>
                        {!! Form::text('option_b', null, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input , 'required','placeholder' => __('english.option_b')]) !!}</div>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_c') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">C</i>
                        {!! Form::text('option_c', null, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input ,'placeholder' => __('english.option_c')]) !!}</div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('name', __('english.option_d') . ':*') !!}
                    <div class="mcq"> <i class="mcq-badge badge bg-primary">D</i>
                        {!! Form::text('option_d', null, ['class' => 'form-control mcqs-form-input'. ' '. $urdu_input , 'placeholder' => __('english.option_d')]) !!}</div>
                </div>

            </div>
            <div class="row mt-1 form-data ">

                <div class="clearfix"></div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('hint', __('english.hint') . ':') !!}
                        {!! Form::textarea('hint',null, ['class' => 'form-control']); !!}
                    </div>
                </div>
                <div class="col-md-4 p-2  hide-answer">
                    {!! Form::label('english.answer', __('english.answer') . ':*') !!}
                    {!! Form::select('answer',__('english.quest_options'),null, ['class' => 'form-select select2 answer-input','placeholder' => __('english.please_select'), 'required', 'style' => 'width:100%']) !!}
                </div>
            </div>

            
            <div class="modal-footer">

                <button type="submit" class="btn btn-primary">@lang( 'english.save' )</button>
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

