<div class="card">
    <div class="card-body">
        <h5 class="card-title text-primary">@lang('english.general_settings')
            <small class="text-info font-13">@lang('english.setting_help_text')</small>
        </h5>
        <hr>

        <div class="row">
            <div class="col-md-4 p-3">
                {!! Form::label('org_name', __('english.organization_name') . ':*', ['classs' => 'form-lable']) !!}
                {!! Form::text('org_name', $general_settings->org_name, ['class' => 'form-control','required','placeholder' => __('english.organization_name')]) !!}
                @if ($errors->has('org_name'))
                <span class="text-danger">{{ $errors->first('org_name') }}</span>
                @endif


            </div>
          
            <div class="col-md-4 p-2">
                {!! Form::label('org_address', __('english.organization_address') . ':*', ['classs' => 'form-lable']) !!}
                {!! Form::text('org_address', $general_settings->org_address, ['class' => 'form-control', 'required', 'placeholder' => __('english.organization_address')]) !!}

            </div>
            <div class="col-md-4 p-2">
                {!! Form::label('org_contact_number', __('english.organization_contact_number') . ':*', ['classs' => 'form-lable']) !!}
                {!! Form::text('org_contact_number', $general_settings->org_contact_number, ['class' => 'form-control', 'pattern' => '\d{11}', 'required', 'maxlength' => '11', 'placeholder' => __('english.organization_contact_number')]) !!}

            </div>

            <div class="clearfix"></div>
            <div class="col-sm-4 p-3">
                {!! Form::label('start_date', __('english.start_date') . ':*', ['classs' => 'form-lable']) !!}

                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                    {!! Form::text('start_date', @format_date($general_settings->start_date), ['class' => 'form-control start-date-picker', 'placeholder' => __('english.start_date'), 'readonly']) !!}

                </div>
            </div>
            <div class="col-sm-4 p-3">
                {!! Form::label('school_name', __('english.currency') . ':*', ['classs' => 'form-lable']) !!}

                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>

                    {!! Form::select('currency_id', $currencies, $general_settings->currency_id, ['class' => 'form-control select2', 'placeholder' => __('english.currency'), 'required']) !!}

                </div>
            </div>
            <div class="col-md-4  p-3 ">
                <div class="form-group">
                    {!! Form::label('currency_symbol_placement', __('english.currency_symbol_placement') . ':') !!}
                    {!! Form::select('currency_symbol_placement', ['before' => __('english.before_amount'), 'after' => __('english.after_amount')], $general_settings->currency_symbol_placement, ['class' => 'form-control select2', 'required']) !!}
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="col-sm-4 p-3">
                {!! Form::label('session_start_month', __('english.session_start_month') . ':') !!} @show_tooltip(__('tooltip.session_start_month'))

                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                    {!! Form::select('session_start_month', $month_list, $general_settings->session_start_month, ['class' => 'form-control select2', 'required']) !!}

                </div>
            </div>
            <div class="col-sm-4 p-3">
                {!! Form::label('transaction_edit_days', __('english.transaction_edit_days') . ':*') !!} @show_tooltip(__('tooltip.transaction_edit_days'))

                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-edit"></i></span>

                    {!! Form::number('transaction_edit_days', $general_settings->transaction_edit_days, ['class' => 'form-control input_number', 'min' => '0', 'placeholder' => __('english.transaction_edit_days'), 'required']) !!}

                </div>
            </div>

            <div class="col-sm-4 p-3">
                {!! Form::label('date_format', __('english.date_format') . ':*') !!}

                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                    {!! Form::select('date_format', $date_formats, $general_settings->date_format, ['class' => 'form-control select2', 'required']) !!}

                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-sm-4  p-3">
                {!! Form::label('time_zone', __('english.time_zone') . ':*', ['classs' => 'form-lable']) !!}

                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-clock"></i></span>

                    {!! Form::select('time_zone', $timezone_list, $general_settings->time_zone, ['class' => 'form-control select2', 'required']) !!}

                </div>
            </div>
            <div class="col-sm-4 p-3">
                {!! Form::label('time_format', __('english.time_format') . ':*') !!}

                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-clock"></i></span>

                    {!! Form::select('time_format', [12 => __('english.12_hour'), 24 => __('english.24_hour')], $general_settings->time_format, ['class' => 'form-control select2', 'required']) !!}

                </div>
            </div>
            <div class="col-sm-4 p-3">
                {!! Form::label('org_short_name', __('english.organization_short_name') . ':*', ['classs' => 'form-lable']) !!}
                {!! Form::text('org_short_name', $general_settings->org_short_name, ['class' => 'form-control', 'required', 'placeholder' => __('english.organization_short_name')]) !!}

            </div>
            <div class="col-sm-4 p-3">
                {!! Form::label('tag_line', __('english.tag_line') . ':*', ['classs' => 'form-lable']) !!}
                {!! Form::text('tag_line', $general_settings->tag_line, ['class' => 'form-control', 'required', 'placeholder' => __('english.tag_line')]) !!}

            </div>
        </div>
        <div class="row p-3">
            <div class="col-sm-4">
                <h5 class="card-title ">@lang('english.organization_logo')</h5>
                <img src="{{'uploads/business_logos/'.$general_settings->org_logo }}" class="org_logo card-img-top" width="170" height="170" alt="...">
                {!! Form::label('org_logo', __('english.organization_logo') . ':') !!}
                {!! Form::file('org_logo', ['accept' => 'image/*','class' => 'form-control upload_org_logo']); !!}
                <p class="card-text fs-6 help-block"></p>
            </div>
            <div class="col-sm-4">
                <h5 class="card-title ">@lang('english.organization_favicon')</h5>

                <img src="{{'uploads/business_logos/'.$general_settings->org_favicon }}" class="org_favicon card-img-top" width="170" height="170" alt="...">
                {!! Form::label('org_favicon', __('english.organization_favicon') . ':') !!}
                {!! Form::file('org_favicon', ['accept' => 'image/*','class' => 'form-control upload_org_favicon']); !!}
                <p class="card-text fs-6 help-block"></p>
            </div>

        </div>
        <div class="row p-3">
            <div class="col-sm-8">
                <h5 class="card-title ">@lang('english.page_header_logo')</h5>
                <img src="{{'uploads/business_logos/'.$general_settings->page_header_logo }}" class="page_header_logo card-img-top" width="170" height="170" alt="...">
                {!! Form::label('page_header_logo', __('english.page_header_logo') . ':') !!}
                {!! Form::file('page_header_logo', ['accept' => 'image/*','class' => 'form-control upload_page_header_logo']); !!}
                <p class="card-text fs-6 help-block"></p>
            </div>
            <div class="col-sm-4">
                <h5 class="card-title ">@lang('english.id_card_logo')</h5>
                <img src="{{'uploads/business_logos/'.$general_settings->id_card_logo }}" class="id_card_logo card-img-top" width="170" height="170" alt="...">
                {!! Form::label('id_card_logo', __('english.id_card_logo') . ':') !!}
                {!! Form::file('id_card_logo', ['accept' => 'image/*','class' => 'form-control upload_id_card_logo']); !!}
                <p class="card-text fs-6 help-block"></p>
            </div>


        </div>


    </div>
</div>

