<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">


        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.view_payments')
                ({{ ucwords($transaction->ref_no) }})</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row ">
               
                <div class="col-md-6">
                    <div class="card card-body bg-light">
                        <p>
                            <strong>@lang('english.ref_no'):
                            </strong>({{ ucwords($transaction->ref_no) }})<br>
                            <strong>@lang('english.transaction_date'):
                            </strong>{{ @format_date($transaction->transaction_date) }}<br>
                            <strong>@lang('english.payment_status'):
                            </strong>{{ ucwords($transaction->payment_status) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>@lang('english.date')</th>
                                <th>@lang('english.ref_no')</th>
                                <th>@lang('english.amount')</th>
                                <th>@lang('english.payment_method')</th>
                                <th>@lang('english.payment_note')</th>
                                @if ($accounts_enabled)
                                    <th>@lang('english.payment_account')</th>
                                @endif
                                <th class="no-print">@lang('english.actions')</th>
                            </tr>
                            @forelse ($payments as $payment)
                                <tr>
                                    <td>{{ @format_datetime($payment->paid_on) }}</td>
                                    <td>{{ $payment->payment_ref_no }}</td>
                                    <td><span class="display_currency"
                                            data-currency_symbol="true">{{ $payment->amount }}</span></td>
                                    <td>{{ $payment_types[$payment->method] ?? '' }}</td>
                                    <td>{{ $payment->note }}</td>
                                    @if ($accounts_enabled)
                                        <td>{{ $payment->payment_account->name ?? '' }}</td>
                                    @endif
                                    <td class="no-print" style="display: flex;">
                                        @if ($payment->method != 'advance_pay')

                                            <button type="button" class="btn btn-info btn-xs edit_payment"
                                                data-href="{{ action('ExpenseTransactionPaymentController@edit', [$payment->id]) }}"><i
                                                    class="glyphicon glyphicon-edit"></i></button>
                                            &nbsp;
                                        @endif
                                        <button type="button" class="btn btn-danger btn-xs delete_payment"
                                            data-href="{{ action('ExpenseTransactionPaymentController@destroy', [$payment->id]) }}"><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                        &nbsp;
                                        @if (!empty($payment->document_path))
                                            &nbsp;
                                            <a href="{{ $payment->document_path }}" class="btn btn-success btn-xs"
                                                download="{{ $payment->document_name }}"><i class="fa fa-download"
                                                    data-toggle="tooltip"
                                                    title="{{ __('english.download_document') }}"></i></a>
                                            @if (isFileImage($payment->document_name))
                                                &nbsp;
                                                <button data-href="{{ $payment->document_path }}"
                                                    class="btn btn-info btn-xs view_uploaded_document"
                                                    data-toggle="tooltip" title="{{ __('english.view_document') }}"><i
                                                        class="fa fa-picture-o"></i></button>
                                            @endif

                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="6">@lang('english.no_records_found')</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close'
                    )</button>
            </div>
        </div>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
