<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action('AccountController@updateAccountTransaction', ['id' => $account_transaction->id ]), 'method' => 'post', 'id' => 'edit_account_transaction_form' ]) !!}

     <div class="modal-header bg-primary">
           <h4 class="modal-title">@if($account_transaction->sub_type == 'opening_balance')@lang( 'account.edit_opening_balance' ) 
           @elseif($account_transaction->sub_type == 'fund_transfer') @lang( 'account.edit_fund_transfer' ) 
           @elseif($account_transaction->sub_type == 'debit') @lang( 'account.debit' ) 
           @elseif($account_transaction->sub_type == 'deposit') @lang( 'account.edit_deposit' ) @endif</h4>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">

            <div class="form-group">
                <strong>@lang('english.selected_account')</strong>: 
                               {{$account_transaction->account->name}}
            </div>
            @if($account_transaction->sub_type == 'deposit')
            @php
              $label = !empty($account_transaction->type == 'debit') ? __( 'account.deposit_from' ) :  __('english.deposit_to');
            @endphp 
            <div class="form-group">  
                {!! Form::label('account_id', $label .":") !!}
                {!! Form::select('account_id', $accounts, $account_transaction->account_id, ['class' => 'form-control', 'placeholder' => __('english.please_select') ]); !!}
            </div>
            @endif

            <div class="form-group">
                {!! Form::label('amount', __( 'account.amount' ) .":*") !!}
                {!! Form::text('amount',  @num_format($account_transaction->amount), ['class' => 'form-control input_number', 'required','placeholder' => __( 'sale.amount' ) ]); !!}
            </div>

            <div class="form-group">
                   {!! Form::label('operation_date', __( 'messages.date' ) .":*") !!}
                    <div class="input-group date" id="od_datetimepicker" data-target-input="nearest">
                        {!! Form::text('operation_date', @format_datetime($account_transaction->operation_date), ['class' => 'form-control datetimepicker-input', 'data-target'=>'#od_datetimepicker','required','placeholder' => __( 'messages.date' ) ]); !!}

                        <div class="input-group-append" data-target="#od_datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fadeIn animated bx bx-calendar-alt"></i></div>
                        </div>
                    </div>
            </div>

            <div class="form-group">
                {!! Form::label('note', __( 'account.note' )) !!}
                {!! Form::textarea('note', null, ['class' => 'form-control', 'placeholder' => __( 'account.note' ), 'rows' => 4]); !!}
            </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.submit' )</button>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script type="text/javascript">
  $(document).ready( function(){
    $('#od_datetimepicker').datetimepicker({
      format: moment_date_format + ' ' + moment_time_format
    });
  });
</script>