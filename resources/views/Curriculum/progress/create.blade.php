<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\ClassSubjectLessonController@store'), 'method' => 'post', 'id' =>'lesson_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title text-uppercase" id="exampleModalLabel">@lang('english.add_new_lesson')For<small>(@lang('english.subject_detail') - {{ $class_subject->name }} of class {{ $class_subject->classes->title }})</small></h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}
            {!! Form::hidden('campus_id', $class_subject->campus_id,[ 'id' => 'campus_id', ]) !!}

        <div class="modal-body">
            <div class="row m-0">
                <div class="col-md-4 p-2 ">
                    {!! Form::label('chapter_id', __('english.chapters') . ':*') !!}
                    {!! Form::select('chapter_id', $chapters, null, ['class' => 'form-select select2  chapter-lessson', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2 ">
                    {!! Form::label('lessons_id', __('english.lessons') . ':*') !!}
                    {!! Form::select('lessons_id', [], null, ['class' => 'form-select select2', 'required', 'id' => 'lessons_ids','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
               
                <div class="clearfix"></div>
                 <div class="col-md-12 p-2">
                    {!! Form::label('description', __( 'english.description' ) . ':') !!}
                    {!! Form::textarea('description',null ,['class' => 'form-control', 'rows=4','placeholder' => __( 'english.description' ) ]); !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">

            <button type="submit" class="btn btn-primary">@lang( 'english.save' )</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
        </div>
    </div>

    {!! Form::close() !!}

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
