
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('Examination\TermController@update', [$exam_term->id]), 'method' => 'PUT', 'id' => 'exam_term_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_exam_term')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
    
            <div class="form-group">
           {!! Form::label('exam_term', __( 'english.exam_term_name' ) . ':*') !!}
                {!! Form::text('name', $exam_term->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.exam_term_name' ) ]); !!}
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
