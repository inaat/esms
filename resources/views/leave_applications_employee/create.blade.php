<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('LeaveApplicationEmployeeController@store'), 'method' => 'post', 'id' =>'add_new_leave_application_form','files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_leave_application')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
            @if(Auth::User()->user_type=='staff' ||Auth::User()->user_type=='teacher')
            				{!! Form::hidden('campus_id',Auth::User()->campus_id); !!}
            				{!! Form::hidden('employee_id', Auth::User()->hook_id); !!}
            @else
                <div class="col-md-6 ">
                    {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 ">
                    {!! Form::label('employee_id', __('english.employee_id').':*') !!} @show_tooltip(__('tooltip.employee_id'))
                    {!! Form::select('employee_id', $employees, null, ['class' => 'form-control select2','required', 'placeholder' => __('english.please_select')]); !!}
                </div>
                @endif
                <div class="col-md-6 ">
                    {!! Form::label('english.from_date', __('english.from_date') .' '.__('english.to_date') . ':') !!}
                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'date_range', 'class' => 'form-control']) !!}

                    </div>
                </div>
                <div class="col-md-6 p-1">
                    {!! Form::label('document', __('english.attach_document') . ':') !!}
                    {!! Form::file('document', ['class' => 'form-control ', 'id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}

                    @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                    @includeIf('components.document_help_text')

                </div>
                <div class="clearfix"></div>

                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('reason', __('english.reason') . ':') !!}
                        {!! Form::textarea('reason',null, ['class' => 'form-control','required', 'rows' => 3]) !!}
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

