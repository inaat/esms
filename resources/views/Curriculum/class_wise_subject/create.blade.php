<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\CurriculumController@store'), 'method' => 'post', 'id' =>'class_subject_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.register_new_subject_for_class')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            {!! Form::hidden('class_id', $class_section_detail->class_id) !!}
            {!! Form::hidden('class_section_id', $class_section_detail->id) !!}
            {!! Form::hidden('campus_id', $class_section_detail->campus_id,[ 'id' => 'campus_id', ]) !!}

        <div class="modal-body">
            <div class="row m-0">
                  <div class="col-md-6 p-2">
                {!! Form::label('subjects', __('english.subjects') . ':') !!}
                {!! Form::select('subject_id', $classSubject, null, ['class' => 'form-select select2 global-subjects', 'id' => 'subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-2">
                    {!! Form::label('teacher', __('english.teachers') . ':*') !!}
                    {!! Form::select('teacher_id',$teachers,null, ['class' => 'form-select select2 ','required', 'id' => '', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="clear-fix"></div>
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
