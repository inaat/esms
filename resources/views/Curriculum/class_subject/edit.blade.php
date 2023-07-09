<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\ClassSubjectController@update', [$class_subject->id]), 'method' => 'PUT', 'id' => 'class_subject_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_class_subject')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row m-0">
                <div class="col-md-3 p-2 ">
                    {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses,$class_subject->campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-3 p-2">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id', $classes,$class_subject->class_id, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
               <div class="col-md-3 p-2">
                    {!! Form::label('name', __( 'english.class_subject_name' ) . ':') !!}
                    {!! Form::text('name', $class_subject->name, ['class' => 'form-control', 'required','id'=>'subject_name','placeholder' => __( 'english.class_subject_name' ) ]); !!}
                </div>

                <div class="col-md-3 p-2">
                    {!! Form::label('code', __( 'english.class_subject_code' ) . ':') !!}
                    {!! Form::text('code', $class_subject->code, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.class_subject_code' ) ]); !!}
                </div>
                                   <div class="clear-fix"></div>

                <div class="col-md-3 p-2">
                    {!! Form::label('theory_mark', __( 'english.theory_mark' ) . ':') !!}
                    {!! Form::number('theory_mark', $class_subject->theory_mark, ['class' => 'form-control', 'required','id'=>'throry-mark','placeholder' => __( 'english.passing_percentage' ) ]); !!}
                </div>
                <div class="col-md-3 p-2">
                    {!! Form::label('parc_mark', __( 'english.practical_mark' ) . ':') !!}
                    {!! Form::number('parc_mark', $class_subject->parc_mark, ['class' => 'form-control', 'required','id'=>'parc_mark','placeholder' => __( 'english.passing_percentage' ) ]); !!}
                </div>
                <div class="col-md-3 p-2">
                    {!! Form::label('passing_percentage', __( 'english.passing_percentage' ) . ':') !!}
                    {!! Form::number('passing_percentage',$class_subject->passing_percentage, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.passing_percentage' ) ]); !!}
                </div>
                <div class="col-md-3 p-2">
                    {!! Form::label('subject_input', __( 'english.subject_input' ) . ':') !!}
                    {!! Form::select('subject_input',['eng'=>'English','ur'=>'Urdu'],$class_subject->subject_input, ['class' => 'form-select select2 ', 'id'=>'subject_input', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
               
                    <div class="clear-fix"></div>
                    <div class="col-md-6 p-3">
                    {!! Form::label('subject_icon', __('english.subject_icon') . ':') !!}
                    {!! Form::file('subject_icon', ['accept' => 'image/jpg, image/jpeg, image/png','class' => 'form-control ']); !!} 
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('subject_book', __('english.subject_book') . ':') !!}
                    {!! Form::file('subject_book', ['accept' => 'pdf','class' => 'form-control']); !!} 
                </div>
                <div class="col-md-12 p-2">
                    {!! Form::label('description', __( 'english.description' ) . ':') !!}
                    {!! Form::textarea('description',$class_subject->description, ['class' => 'form-control', 'rows=4','placeholder' => __( 'english.description' ) ]); !!}
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
