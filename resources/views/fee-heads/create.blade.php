<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('FeeHeadController@store'), 'method' => 'post', 'id' => 'fee_head_add_form']) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_fee_head')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                 <div class="col-md-12 ">
                        {!! Form::checkbox('check_all', 1, null ,[ 'class' => 'form-check-input big-checkbox'] ); !!}
                        {{ Form::label('check_all', __('english.for_all_classes') , ['class' => 'control-label mt-2 ']) }}
                    </div>
                <div class="col-md-6 p-1">
                    {!! Form::label('campus_id', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-1">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),]) !!}
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 p-3">
                    {!! Form::label('description', __('english.fee_head_name') . ':*') !!}
                    {!! Form::text('description',null, ['class' => 'form-control','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.fee_head_name')]) !!}
                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('amount', __( 'english.fee_amount' ) . ':*') !!}
                    {!! Form::text('amount', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'english.fee_amount' ) ]); !!}

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
