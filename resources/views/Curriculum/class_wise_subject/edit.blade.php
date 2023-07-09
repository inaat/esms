<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\CurriculumController@update', [$subject_teacher->id]), 'method' => 'PUT', 'id' => 'class_subject_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_class_subject')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row m-0">
              <div class="col-md-6 p-2">
                {!! Form::label('subjects', __('english.subjects') . ':') !!}
                {!! Form::select('subject_id', $classSubject, $subject_teacher->subject_id , ['class' => 'form-select select2 global-subjects', 'id' => 'subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-2">
                    {!! Form::label('teacher', __('english.teachers') . ':*') !!}
                    {!! Form::select('teacher_id',$teachers,$subject_teacher->teacher_id, ['class' => 'form-select select2 ','required', 'id' => '', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
            </div>
        </div>
        <div class="modal-footer">

            <button type="submit" class="btn btn-primary">@lang( 'english.update' )</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
        </div>
    </div>

    {!! Form::close() !!}

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

