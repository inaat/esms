<div class="row transport_payment_details_div  @if ($transport_payment_line->method !== 'card') {{ 'd-none' }} @endif" data-type="card" >
    <div class="col-md-4 p-1">
        
            {!! Form::label('card_number', __('english.card_no')) !!}
            {!! Form::text('transport_card_number', $transport_payment_line->card_number, ['class' => 'form-control', 'placeholder' => __('english.card_no')]) !!}
        
    </div>
    
    <div class="col-md-4 p-1">
        
            {!! Form::label('card_holder_name', __('english.card_holder_name')) !!}
            {!! Form::text('transport_card_holder_name', $transport_payment_line->card_holder_name, ['class' => 'form-control', 'placeholder' => __('english.card_holder_name')]) !!}
        
    </div>
    <div class="col-md-4 p-1">
        
            {!! Form::label('card_transaction_number', __('english.card_transaction_no')) !!}
            {!! Form::text('transport_card_transaction_number', $transport_payment_line->card_transaction_number, ['class' => 'form-control', 'placeholder' => __('english.card_transaction_no')]) !!}
        
    </div>
    <div class="clearfix"></div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_type', __('english.card_type')) !!}
            {!! Form::select('transport_card_type', ['credit' => 'Credit Card', 'debit' => 'Debit Card', 'visa' => 'Visa', 'master' => 'MasterCard'], $transport_payment_line->card_type, ['class' => 'form-control select2']) !!}
        
    </div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_month', __('english.month')) !!}
            {!! Form::text('transport_card_month', $transport_payment_line->card_month, ['class' => 'form-control', 'placeholder' => __('english.month')]) !!}
        
    </div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_year', __('english.year')) !!}
            {!! Form::text('transport_card_year', $transport_payment_line->card_year, ['class' => 'form-control', 'placeholder' => __('english.year')]) !!}
        
    </div>
    <div class="col-md-2 p-1">
        
            {!! Form::label('card_security', __('english.security_code')) !!}
            {!! Form::text('transport_card_security', $transport_payment_line->card_security, ['class' => 'form-control', 'placeholder' => __('english.security_code')]) !!}
        
    </div>
    <div class="clearfix"></div>
</div>
<div class="transport_payment_details_div @if ($transport_payment_line->method !== 'cheque') {{ 'd-none' }} @endif" data-type="cheque" >
    <div class="col-md-12">
            {!! Form::label('cheque_number', __('english.cheque_no')) !!}
            {!! Form::text('transport_cheque_number', $transport_payment_line->cheque_number, ['class' => 'form-control', 'placeholder' => __('english.cheque_no')]) !!}
        
    </div>
</div>
<div class="transport_payment_details_div @if ($transport_payment_line->method !== 'bank_transfer') {{ 'd-none' }} @endif" data-type="bank_transfer" >
    <div class="col-md-12">
            {!! Form::label('bank_account_number', __('english.bank_account_number')) !!}
            {!! Form::text('transport_bank_account_number', $transport_payment_line->bank_account_number, ['class' => 'form-control', 'placeholder' => __('english.bank_account_number')]) !!}
        
    </div>
</div>
<div class="transport_payment_details_div @if ($transport_payment_line->method !== 'advance_pay') {{ 'd-none' }} @endif" data-type="advance_pay" >
    <div class="col-md-12">
            {!! Form::label('transaction_no_1', __('english.transaction_no')) !!}
            {!! Form::text('transport_transaction_no_1', $transport_payment_line->transaction_no, ['class' => 'form-control', 'placeholder' => __('english.transaction_no')]) !!}
        
    </div>
</div>

