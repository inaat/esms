<div class="row payment_details_div  @if ($payment_line->method !== 'card') {{ 'd-none' }} @endif" data-type="card" >
    <div class="col-md-4 p-1">
        
            {!! Form::label('card_number', __('english.card_no')) !!}
            {!! Form::text('card_number', $payment_line->card_number, ['class' => 'form-control', 'placeholder' => __('english.card_no')]) !!}
        
    </div>
    
    <div class="col-md-4 p-1">
        
            {!! Form::label('card_holder_name', __('english.card_holder_name')) !!}
            {!! Form::text('card_holder_name', $payment_line->card_holder_name, ['class' => 'form-control', 'placeholder' => __('english.card_holder_name')]) !!}
        
    </div>
    <div class="col-md-4 p-1">
        
            {!! Form::label('card_transaction_number', __('english.card_transaction_no')) !!}
            {!! Form::text('card_transaction_number', $payment_line->card_transaction_number, ['class' => 'form-control', 'placeholder' => __('english.card_transaction_no')]) !!}
        
    </div>
    <div class="clearfix"></div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_type', __('english.card_type')) !!}
            {!! Form::select('card_type', ['credit' => 'Credit Card', 'debit' => 'Debit Card', 'visa' => 'Visa', 'master' => 'MasterCard'], $payment_line->card_type, ['class' => 'form-control select2']) !!}
        
    </div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_month', __('english.month')) !!}
            {!! Form::text('card_month', $payment_line->card_month, ['class' => 'form-control', 'placeholder' => __('english.month')]) !!}
        
    </div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_year', __('english.year')) !!}
            {!! Form::text('card_year', $payment_line->card_year, ['class' => 'form-control', 'placeholder' => __('english.year')]) !!}
        
    </div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_security', __('english.security_code')) !!}
            {!! Form::text('card_security', $payment_line->card_security, ['class' => 'form-control', 'placeholder' => __('english.security_code')]) !!}
        
    </div>
    <div class="clearfix"></div>
</div>
<div class="payment_details_div @if ($payment_line->method !== 'cheque') {{ 'd-none' }} @endif" data-type="cheque" >
    <div class="col-md-12">
            {!! Form::label('cheque_number', __('english.cheque_no')) !!}
            {!! Form::text('cheque_number', $payment_line->cheque_number, ['class' => 'form-control', 'placeholder' => __('english.cheque_no')]) !!}
        
    </div>
</div>
<div class="payment_details_div @if ($payment_line->method !== 'bank_transfer') {{ 'd-none' }} @endif" data-type="bank_transfer" >
    <div class="col-md-12">
            {!! Form::label('bank_account_number', __('english.bank_account_number')) !!}
            {!! Form::text('bank_account_number', $payment_line->bank_account_number, ['class' => 'form-control', 'placeholder' => __('english.bank_account_number')]) !!}
        
    </div>
</div>
<div class="payment_details_div @if ($payment_line->method !== 'advance_pay') {{ 'd-none' }} @endif" data-type="advance_pay" >
    <div class="col-md-12">
            {!! Form::label('transaction_no_1', __('english.transaction_no')) !!}
            {!! Form::text('transaction_no_1', $payment_line->transaction_no, ['class' => 'form-control', 'placeholder' => __('english.transaction_no')]) !!}
        
    </div>
</div>

