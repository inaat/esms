<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('FeeHeadController@update',[$fee_head->id]), 'method' => 'PUT', 'id' =>'fee_head_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_fee_head')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      <div class="modal-body">
            <div class="row">

                <div class="col-md-6 p-1">
                    {!! Form::label('campus_id', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses, $fee_head->campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-1">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id', $classes, $fee_head->class_id, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),]) !!}
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 p-1">
                    {!! Form::label('description', __('english.fee_head_name') . ':*') !!}
                    {!! Form::text('description',$fee_head->description, ['class' => 'form-control','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.fee_head_name')]) !!}
                </div>

                <div class="col-md-6 p-1">
                    {!! Form::label('amount', __( 'english.fee_amount' ) . ':*') !!}
                    {!! Form::text('amount',@num_format($fee_head->amount), ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'english.fee_amount' ) ]); !!}

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
