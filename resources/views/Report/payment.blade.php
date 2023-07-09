<div class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('english.payment_history')</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="payment_line_table">
                <thead>
                    <tr>
                        <th>@lang('english.date')</th>
                        <th>@lang('english.amount')</th>
                        <th>@lang('english.discount_amount')</th>
                        <th>@lang('english.payment_method')</th>
                        <th>@lang('english.note')</th>
                        <th>@lang('english.account')</th>
                        <th>@lang('english.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fee_payments as $payment)
                    <tr>
                        <td>{{@format_datetime($payment->paid_on)}}</td>
                        <td>{{@num_format($payment->amount)}}</td>
                        <td>{{@num_format($payment->discount_amount)}}</td>
                        <td>{{$payment->method}}</td>
                        <td>{{$payment->note}}
                           @foreach ($payment->sub_payments as $sub)
                           
                           <span class="badge text-white text-uppercase  bg-primary">{{$sub->fee_transaction->type}}</span>
                        
                       @endforeach </td>
                        <td>{{$payment->payment_account->name}}</td>
                        <td>
                          
                            {{-- <button type="button" class="btn btn-info btn-xs edit_payment" data-href="{{action('FeeTransactionPaymentController@edit', [$payment->id]) }}"><i class="glyphicon glyphicon-edit"></i></button>
                            &nbsp; --}}
                            @can('fee.fee_payment_delete')
                            <button type="button" class="btn btn-danger btn-xs pay_delete_payment" data-href="{{ action('FeeTransactionPaymentController@destroy', [$payment->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

