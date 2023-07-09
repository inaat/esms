<div class="card">
    <div class="card-body">
        <h5 class="card-title text-primary">@lang('english.prefixes_settings')
            <small class="text-info font-13">@lang('english.setting_help_text')</small>
        </h5>
        <hr>
        <div class="row">
            <div class="col-md-4 p-3">
            @php
                    $student = '';
                    if(!empty($general_settings->ref_no_prefixes['student'])){
                        $student = $general_settings->ref_no_prefixes['student'];
                    }
                @endphp
                {!! Form::label('ref_no_prefixes[student]', __('english.student') . ':') !!}
                {!! Form::text('ref_no_prefixes[student]', $student, ['class' => 'form-control']); !!}
              

            </div>
            <div class="col-md-4 p-3">
            @php
                    $employee = '';
                    if(!empty($general_settings->ref_no_prefixes['employee'])){
                        $employee = $general_settings->ref_no_prefixes['employee'];
                    }
                @endphp
                {!! Form::label('ref_no_prefixes[employee]', __('english.employee') . ':') !!}
                {!! Form::text('ref_no_prefixes[employee]', $employee, ['class' => 'form-control']); !!}
              

            </div>
            <div class="col-md-4 p-3">
            @php
                    $fee_payment = '';
                    if(!empty($general_settings->ref_no_prefixes['fee_payment'])){
                        $fee_payment = $general_settings->ref_no_prefixes['fee_payment'];
                    }
                @endphp
                {!! Form::label('ref_no_prefixes[fee_payment]', __('english.fee_payment') . ':') !!}
                {!! Form::text('ref_no_prefixes[fee_payment]', $fee_payment, ['class' => 'form-control']); !!}
              

            </div>
            <div class="col-md-4 p-3">
            @php
                    $expenses_payment = '';
                    if(!empty($general_settings->ref_no_prefixes['expenses_payment'])){
                        $expenses_payment = $general_settings->ref_no_prefixes['expenses_payment'];
                    }
                @endphp
                {!! Form::label('ref_no_prefixes[expenses_payment]', __('english.expenses_payment') . ':') !!}
                {!! Form::text('ref_no_prefixes[expenses_payment]', $fee_payment, ['class' => 'form-control']); !!}
              

            </div>
            <div class="col-md-4 p-3">
            @php
                    $admission = '';
                    if(!empty($general_settings->ref_no_prefixes['admission'])){
                        $admission = $general_settings->ref_no_prefixes['admission'];
                    }
                @endphp
                {!! Form::label('ref_no_prefixes[admission]', __('english.admission') . ':') !!}
                {!! Form::text('ref_no_prefixes[admission]', $admission, ['class' => 'form-control']); !!}
              

            </div>
        </div>
    </div>
</div>
