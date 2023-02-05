
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('Hrm\HrmDeductionController@update', [$deduction->id]), 'method' => 'PUT', 'id' => 'deduction_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_deduction')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
        <p class="text-muted">Update deduction here. Please provide required information to proceed next...</p>
            <div class="form-group">
        {!! Form::label('deduction', __( 'english.deduction_title' ) . ':') !!}
          {!! Form::text('deduction', $deduction->deduction, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.deduction_title' ) ]); !!}
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
