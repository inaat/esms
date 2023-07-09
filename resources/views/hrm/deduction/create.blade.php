<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Hrm\HrmDeductionController@store'), 'method' => 'post', 'id' =>'deduction_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.register_new_deduction')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
        <p class="text-muted">Register as many deductions as you need. Please provide required information to proceed next...</p>
            <div class="form-group">
                {!! Form::label('deduction', __( 'english.deduction_name' ) . ':*') !!}
                {!! Form::text('deduction', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.deduction_name' ) ]); !!}
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
