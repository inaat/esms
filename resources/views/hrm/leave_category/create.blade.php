<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Hrm\HrmLeaveCategoryController@store'), 'method' => 'post', 'id' => 'leave_category_add_form']) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.register_new_leave_category')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row ">
                <div class="col-md-12 p-1 {{ $errors->has('hrm_leave_category') ? ' has-error' : '' }} has-feedback">
                    {!! Form::label('leave_category', __('english.leave_category_name') . ':*') !!}
                    {!! Form::text('leave_category', null, ['class' => 'form-control', 'required', 'placeholder' => __('english.leave_category_name')]) !!}

                    <span class="text-danger error-text leave_category_err"></span>

                </div>
                <div class="col-md-12 p-1">
                    {!! Form::label('max_leave_count', __('english.max_leave_count') . ':') !!}
                    {!! Form::number('max_leave_count', null, ['class' => 'form-control', 'placeholder' => __('english.max_leave_count')]) !!}
                </div>
                <div class="col-md-12 p-1">
                    <strong>@lang('english.leave_count_interval')</strong><br>
                    <label class="radio-inline">
                        {!! Form::radio('leave_count_interval', 'month', false,['class'=>'form-check-input']) !!} @lang('english.current_month')
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('leave_count_interval', 'year', false,['class'=>'form-check-input']) !!} @lang('english.current_fy')
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('leave_count_interval', null, false,['class'=>'form-check-input']) !!} @lang('english.none')
                    </label>
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
