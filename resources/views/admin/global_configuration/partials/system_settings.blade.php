<div class="card">
    <div class="card-body">
        <h5 class="card-title text-primary">@lang('english.system_settings')
            <small class="text-info font-13">@lang('english.setting_help_text')</small>
        </h5>
        <hr>
        <div class="row">
            <div class="col-sm-4">
                {!! Form::label('theme_color', __('english.theme_color')) !!}
                {!! Form::select('theme_color', $theme_colors, $general_settings->theme_color, [
                    'class' => 'form-control select2',
                    'placeholder' => __('english.please_select'),
                    'style' => 'width: 100%;',
                ]) !!}

            </div>
            <div class="col-sm-4">
                @php
                    $page_entries = [25 => 25, 50 => 50, 100 => 100, 200 => 200, 500 => 500, 1000 => 1000, -1 => __('english.all')];
                @endphp
                {!! Form::label('default_datatable_page_entries', __('english.default_datatable_page_entries')) !!}
                {!! Form::select(
                    'common_settings[default_datatable_page_entries]',
                    $page_entries,
                    !empty($common_settings['default_datatable_page_entries'])
                        ? $common_settings['default_datatable_page_entries']
                        : 25,
                    ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id' => 'default_datatable_page_entries'],
                ) !!}

            </div>
            <div class="col-sm-4">

                <div class="form-check mt-3">
                    {!! Form::checkbox('enable_tooltip', 1, $general_settings->enable_tooltip, [
                        'class' => 'form-check-input big-checkbox',
                    ]) !!}
                    <label class="form-check-label p-2">{{ __('english.show_help_text') }}</label>
                </div>

            </div>
            <div class="row">

                <div class="col-sm-4">
                    <div class="form-check mt-3">
                        {!! Form::checkbox(
                            'common_settings[active_students]',
                            true,
                            !empty($common_settings['active_students']) ? true : false,
                            ['class' => 'form-check-input big-checkbox'],
                        ) !!}
                        <label class="form-check-label p-2">{{ __('english.active_students') }}</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check mt-3">
                        {!! Form::checkbox(
                            'common_settings[inactive_students]',
                            true,
                            !empty($common_settings['inactive_students']) ? true : false,
                            ['class' => 'form-check-input big-checkbox'],
                        ) !!}
                        <label class="form-check-label p-2">{{ __('english.inactive_students') }}</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check mt-3">
                        {!! Form::checkbox(
                            'common_settings[pass_out_students]',
                            true,
                            !empty($common_settings['pass_out_students']) ? true : false,
                            ['class' => 'form-check-input big-checkbox'],
                        ) !!}
                        <label class="form-check-label p-2">{{ __('english.pass_out_students') }}</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check mt-3">
                        {!! Form::checkbox(
                            'common_settings[struck_up_students]',
                            true,
                            !empty($common_settings['struck_up_students']) ? true : false,
                            ['class' => 'form-check-input big-checkbox'],
                        ) !!}
                        <label class="form-check-label p-2">{{ __('english.struck_up_students') }}</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check mt-3">
                        {!! Form::checkbox(
                            'common_settings[took_slc_students]',
                            true,
                            !empty($common_settings['took_slc_students']) ? true : false,
                            ['class' => 'form-check-input big-checkbox'],
                        ) !!}
                        <label class="form-check-label p-2">{{ __('english.took_slc_students') }}</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check mt-3">
                        {!! Form::checkbox(
                            'common_settings[active_employees]',
                            true,
                            !empty($common_settings['active_employees']) ? true : false,
                            ['class' => 'form-check-input big-checkbox'],
                        ) !!}
                        <label class="form-check-label p-2">{{ __('english.active_employees') }}</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check mt-3">
                        {!! Form::checkbox(
                            'common_settings[resign_employees]',
                            true,
                            !empty($common_settings['resign_employees']) ? true : false,
                            ['class' => 'form-check-input big-checkbox'],
                        ) !!}
                        <label class="form-check-label p-2">{{ __('english.resign_employees') }}</label>
                    </div>
                </div>
            </div>
        </div>

    </div>
