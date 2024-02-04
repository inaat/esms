<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('\App\Http\Controllers\ClassController@store'), 'method' => 'post', 'id' => 'class_add_form']) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_class')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 p-3">
                    {!! Form::label('title', __('english.class_title') . ':*', ['classs' => 'form-lable']) !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'required', 'placeholder' => __('english.class_title')]) !!}

                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select  select2 ', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="clearfix"></div>

                <div class="col-md-6 p-3">
                    {!! Form::label('class_level.class_level', __('english.class_levels') . ':*') !!}
                    {!! Form::select('class_level_id', $classLevel, null, ['class' => 'form-select  select2 ', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('tuition_fee', __('english.tuition_fee') . ':*') !!}
                    {!! Form::text('tuition_fee', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.tuition_fee')]) !!}

                </div>
                <div class="clearfix"></div>

                <div class="col-md-6 p-3">
                    {!! Form::label('admission_fee', __('english.admission_fee') . ':*') !!}
                    {!! Form::text('admission_fee', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.admission_fee')]) !!}

                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('transport_fee', __('english.transport_fee') . ':*') !!}
                    {!! Form::text('transport_fee', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.transport_fee')]) !!}

                </div>
                <div class="clearfix"></div>

                <div class="col-md-6 p-3">
                    {!! Form::label('security_fee', __('english.security_fee') . ':*') !!}
                    {!! Form::text('security_fee', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.security_fee')]) !!}

                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('prospectus_fee', __('english.prospectus_fee') . ':*') !!}
                    {!! Form::text('prospectus_fee', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.prospectus_fee')]) !!}

                </div>

            </div>


            <div class="modal-footer">




                <button type="submit" class="btn btn-primary">@lang( 'english.save' )</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close'
                    )</button>
            </div>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
