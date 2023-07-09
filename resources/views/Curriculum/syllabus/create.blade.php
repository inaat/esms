<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\SyllabusMangerController@store'), 'method' => 'post', 'id' =>'syllabus_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_syllabus')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row m-0">
                <div class="col-md-4 p-2 ">
                    {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                    {!! Form::label('subjects', __('english.subjects') . ':') !!}
                    {!! Form::select('subject_id', [], null, ['class' => 'form-select select2 global-subjects', 'id'=>'subject_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="clear-fix"></div>

                <div class="col-md-4 p-2 ">
                    {!! Form::label('chapter_id', __('english.chapters') . ':*') !!}
                    {!! Form::select('chapter_id',[], null, ['class' => 'form-select select2 global-chapter', 'required', 'id' => 'chapter_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                 <div class="col-md-4 p-2 ">
                    {!! Form::label('exam_term', __('english.exam_term') . ':*') !!}
                    {!! Form::select('exam_term_id',$term, null, ['class' => 'form-select select2', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div> 
                <div class="clear-fix"></div>

                <div class="col-md-12 p-2">
                    {!! Form::label('description', __( 'english.description' ) . ':') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control','rows=4','placeholder' => __( 'english.description' ) ]); !!}
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

