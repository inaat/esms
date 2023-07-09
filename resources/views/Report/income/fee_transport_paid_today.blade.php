<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel">@lang('english.today_paid_transport_fee_details')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="table-responsive" >
                    @php
                    $total_paid=0;
                    $total_discount_amount=0;
                    @endphp
                    <table class="table mb-0" width="100%">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>#</th>
                                <th>@lang('english.date')</th>
                                <th>@lang('english.transaction_month')</th>
                                <th>@lang('english.roll_no')</th>
                                <th>@lang('english.student_name')</th>
                                <th>@lang('english.discount_amount')</th>
                                <th>@lang('english.paid')</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($student_payments as $pay)
                            @if( $pay->fee_transaction->type=='transport_fee')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ @format_date($pay->paid_on)}}</td>
                                <td>{{__('english.months.' . $pay->fee_transaction->month) .' ' }}
                                    <span class="badge text-white text-uppercase  bg-primary">
                                        {{__('english.transaction_types.' .$pay->fee_transaction->type)}} </span>
                                    
                                </td>
                                <td>{{ $pay->student ? $pay->student->roll_no : ' '}}</td>
                                <td>{{ $pay->student ? $pay->student->first_name.' '.$pay->student->last_name : ''}}</td>
                                <td>{{ @num_format($pay->discount_amount)}}</td>
                                <td>{{ @num_format($pay->amount)}}</td>
                            </tr>
                            @php
                            $total_paid += $pay->amount;
                            $total_discount_amount += $pay->discount_amount;
        
                            @endphp
                            @endif
                            @endforeach
        
                             <tr>
                                <td colspan='5'>Total</td>
                                <td>{{@num_format($total_discount_amount)}}</td>
                                <td>{{@num_format($total_paid)}}</td>
                            </tr>
        
                        </tbody>
                    </table>
                </div>
            </div>


        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
        </div>
    </div>


</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
