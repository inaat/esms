<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Examination\GradeController@store'), 'method' => 'post', 'id' =>'grade_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_grade')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row m-0">
                
             <div class="col-md-6 p-2">
                    {!! Form::label('name', __( 'english.name' ) . ':') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'required','id'=>'subject_name', 'placeholder' => __( 'english.name' ) ]); !!}
                </div>
                <div class="col-md-6 p-2">
                    {!! Form::label('point', __( 'english.point' ) . ':') !!}
                    {!! Form::text('point', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.point' ) ]); !!}
                </div>
                <div class="clear-fix"></div>

                <div class="col-md-6 p-2">
                    {!! Form::label('percentage_from', __( 'english.percentage_from' ) . ':') !!}
                    {!! Form::number('percentage_from', null, ['class' => 'form-control', 'required','id'=>'throry-mark','placeholder' => __( 'english.percentage_from' ) ]); !!}
                </div>
                <div class="col-md-6 p-2">
                    {!! Form::label('percentage_to', __( 'english.percentage_to' ) . ':') !!}
                    {!! Form::number('percentage_to', null, ['class' => 'form-control', 'required','id'=>'percentage_to','placeholder' => __( 'english.percentage_to' ) ]); !!}
                </div>
                
                    <div class="clear-fix"></div>

                <div class="col-md-12 p-2">
                    {!! Form::label('remark', __( 'english.remark' ) . ':') !!}
                    {!! Form::textarea('remark', null, ['class' => 'form-control','rows=4','placeholder' => __( 'english.remark' ) ]); !!}
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
