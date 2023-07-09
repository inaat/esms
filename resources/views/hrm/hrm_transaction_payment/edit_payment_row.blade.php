<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('Hrm\HrmTransactionPaymentController@update', [$payment_line->id]), 'method' => 'put', 'id' => 'transaction_payment_add_form', 'files' => true ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_payment')
                </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
               <div class="row ">
                <div class="col-md-6">
                    <div class="card card-body bg-light">
                        <p>
                             <strong>@lang('english.employee_name'):
                            </strong>({{ ucwords($transaction->employee->first_name . ' ' . $transaction->employee->last_name) }})<br>
                            <strong>@lang('english.father_name'):
                            </strong>{{ ucwords($transaction->employee->father_name) }}<br>
                            <strong>@lang('english.employeeID'): </strong>{{ ucwords($transaction->employee->employeeID) }}
                        
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-body bg-light">
                       <p>
                            <strong>@lang('english.ref_no'):
                            </strong>({{ ucwords($transaction->ref_no) }})<br>
                            <strong>@lang('english.transaction_date'):
                            </strong>{{ @format_date($transaction->transaction_date) }}<br>
                            <strong>@lang('english.payment_status'): </strong>{{ ucwords($transaction->payment_status) }}<br>
                             <strong>@lang('english.total_amount'): </strong><span class="display_currency" data-currency_symbol="true">{{ $transaction->final_total }}</span><br>

                        </p>
                    </div>
                </div>
                </div>

           <div class="row payment_row">


                <div class="col-md-4 p-1">
                    {!! Form::label('english.amount', __('english.amount') . ':*') !!}
                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                class="fas fa-money-bill-alt"></i></span>
                        {{-- {!! Form::text('amount', @num_format($payment_line->amount), ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.amount')]) !!} --}}
                        {!! Form::text("amount", @num_format($payment_line->amount), ['class' => 'form-control amount input_number', 'required', 'placeholder' => 'Amount', 'data-rule-max-value' =>$payment_line->amount, 'data-msg-max-value' => __('english.max_amount_to_be_paid_is', ['amount' =>@num_format($payment_line->amount)])]); !!}

                    </div>
                </div>
                @if(empty($payment_line->parent_id))
                 <div class="col-md-4 p-1">
                    {!! Form::label('english.amount',  __('english.discount').' '. __('english.amount') ) !!}
                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                class="fas fa-money-bill-alt"></i></span>
                        {!! Form::text("discount_amount", @num_format($payment_line->discount_amount), ['class' => 'form-control input_number', 'required', 'placeholder' => 'Amount', 'id'=>'discount_amount']); !!}
                    </div>
                </div>
                @endif
                <div class="col-md-4 p-1"  id="datetimepicker"
                        data-target-input="nearest" data-target="#datetimepicker" data-toggle="datetimepicker">
                    {!! Form::label('paid_on', __('english.paid_on') . ':*') !!}
                    <div class="input-group flex-nowrap input-group-append  input-group date"> <span
                            class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('paid_on', @format_datetime($payment_line->paid_on), ['class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker', 'required']) !!}
                    </div>
                </div>
                @if(empty($payment_line->parent_id))

                <div class="clearfix"></div>
                @endif

                <div class="col-md-4 p-1">
                    {!! Form::label('method', __('english.payment_method') . ':*') !!}
                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                class="fas fa-money-bill-alt"></i></span>
                        {!! Form::select('method', $payment_types, $payment_line->method, ['class' => 'form-select select2 payment_types_dropdown', 'required', 'style' => 'width:100%;']) !!}
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-md-6 p-1">
                    {!! Form::label('document', __('english.attach_document') . ':') !!}
                    {!! Form::file('document', ['class' => 'form-control ', 'id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}

                    @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                    @includeIf('components.document_help_text')

                </div>
                @if (!empty($accounts))
                    <div class="col-md-6 p-1">
                        {!! Form::label('account_id', __('english.payment_account') . ':') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                    class="fas fa-money-bill-alt"></i></span>
                            {!! Form::select('account_id', $accounts, !empty($payment_line->account_id) ? $payment_line->account_id : '', ['class' => 'form-select select2 ', 'id' => 'account_id', 'required', 'style' => 'width:100%;']) !!}
                        
                        </div>
                    </div>
                @endif
                <div class="clearfix"></div>

                @include('hrm.hrm_transaction_payment.payment_type_details')
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('note', __('english.payment_note') . ':') !!}
                        {!! Form::textarea('note', $payment_line->note, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                </div>
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
