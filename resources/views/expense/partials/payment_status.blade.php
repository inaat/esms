<a href="{{ action('ExpenseTransactionPaymentController@show', [$id])}}" class="view_payment_modal payment-status-label" data-orig-value="{{$payment_status}}" data-status-name="{{__('english.' . $payment_status)}}"><span class="badge text-white text-uppercase  @payment_status($payment_status)">{{__('english.' . $payment_status)}}
                        </span></a>