<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('FeeIncrementController@store'), 'method' => 'post', 'id' => 'add_new_fee_increment_decrement']) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_fee_increment_decrement')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">

                <div class="col-md-4 p-2">
                    {!! Form::label('campus_id', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),]) !!}
                </div>
                  <div class="col-md-4 p-2">
                         {!! Form::label('english.sections', __('english.sections') . ':') !!}
                         {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                     </div>
                <div class="clearfix"></div>
                <div class="col-md-6 p-2">
                    {!! Form::label('tuition_fee', __( 'english.fee_increment_decrement_tuition_fee' ) . ':*') !!}
                    {!! Form::text('tuition_fee', 0, ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'english.fee_increment_tuition_fee' ) ]); !!}

                </div>
                <div class="col-md-6 p-2">
                    {!! Form::label('transport_fee', __( 'english.fee_increment_decrement_transport_fee' ) . ':*') !!}
                    {!! Form::text('transport_fee', 0, ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'english.fee_increment_transport_fee' ) ]); !!}

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
