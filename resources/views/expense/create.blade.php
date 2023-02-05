@extends("admin_layouts.app")
@section('title', __('english.add_new_expense'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.add_new_expense')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.add_new_expense')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
	{!! Form::open(['url' => action('ExpenseTransactionController@store'), 'method' => 'post', 'id' => 'add_expense_form', 'files' => true ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row ">


                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.expense_categories', __('english.expense_categories') . ':*') !!}
                        {!! Form::select('expense_category_id', $expense_categories, null,['class' => 'form-select select2','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2 ">
                        {!! Form::label('ref_no', __('english.ref_no').':') !!}
                        {!! Form::text('ref_no', null, ['class' => 'form-control']); !!}
                        <p class="help-block">
                            @lang('english.leave_empty_to_auto_generate')
                        </p>
                    </div>
                    <div class="clearfix"></div>
                     <div class="col-md-4 " id="datetimepicker" data-target-input="nearest" data-target="#datetimepicker" data-toggle="datetimepicker">
                        {!! Form::label('paid_on', __('english.paid_on') . ':*') !!}
                        <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('transaction_date', @format_datetime('now'), ['class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('expense_for', __('english.expense_for').':*') !!} @show_tooltip(__('tooltip.expense_for'))
                        {!! Form::select('expense_for', $employees, null, ['class' => 'form-control select2','required', 'placeholder' => __('english.please_select')]); !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('final_total', __('english.total_amount') . ':*') !!}
                        {!! Form::text('final_total', null, ['class' => 'form-control input_number', 'placeholder' => __('english.total_amount'), 'required']); !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('additional_notes', __('english.expense_note') . ':') !!}
                        {!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 1]); !!}
                    </div>
                    <div class="col-md-4 p">
                        {!! Form::label('english.vehicles', __('english.vehicles') . ':') !!}
                        {!! Form::select('vehicle_id',$vehicles,null, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'id'=>'vehicle_id','placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('document', __('english.attach_document') . ':') !!}
                        {!! Form::file('document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                        <small>
                            <p class="help-block">@lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])

                    </div>


                </div>

            </div>
        </div>
        <!--end row-->
        <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Add Payment
                    </h5>
                <div class="row ">
                    <div class="border border-3 p-2 rounded">
                         <div class="row payment_row p-2">
                             <div class="col-md-6 p-1">

                                 {!! Form::label('english.amount', __('english.amount') . ':*') !!}
                                 <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                     {!! Form::text("amount", null, ['class' => 'form-control input_number amount tabkey',  'placeholder' => 'Amount']); !!}

                                 </div>
                             </div>

                             <div class="col-md-6 p-1 hide">
                                 {!! Form::label('english.amount', __('english.discount').' '. __('english.amount') ) !!}
                                 <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                     {!! Form::text("discount_amount",0, ['class' => 'form-control input_number discount_amount tabkey', 'required', 'placeholder' => 'Amount', 'id'=>'discount_amount']); !!}
                                 </div>
                             </div>
                             <div class="clearfix"></div>

                             <div class="col-md-6 p-1" id="2datetimepicker" data-target-input="nearest" data-target="#2datetimepicker" data-toggle="datetimepicker">
                                 {!! Form::label('paid_on', __('english.paid_on') . ':*') !!}
                                 <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                     {!! Form::text('paid_on', @format_datetime($payment_line->paid_on), ['class' => 'form-control datetimepicker-input', 'data-target' => '#2datetimepicker', 'required']) !!}
                                 </div>
                             </div>
                             <div class="col-md-6 p-1">
                                 {!! Form::label('method', __('english.payment_method') . ':*') !!}
                                 <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
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
                                 <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                     {!! Form::select('account_id', $accounts, !empty($payment_line->account_id) ? $payment_line->account_id : '', ['class' => 'form-select select2 ', 'id' => 'account_id', 'required', 'style' => 'width:100%;']) !!}

                                 </div>
                             </div>
                             @endif
                             <div class="clearfix"></div>

                             @include('fee_transaction_payment.payment_type_details')
                             <div class="col-md-4 ">
                                 <div class="form-group hide">
                                     {!! Form::label('note', __('english.payment_note') . ':') !!}
                                     {!! Form::textarea('note', $payment_line->note, ['class' => 'form-control', 'rows' => 3]) !!}
                                 </div>
                             </div>
                             
                         </div>

                     </div>
                </div>

            </div>
        </div>
			<div class="col-sm-12 text-center">
		<button type="submit" class="btn btn-primary btn-big">@lang('english.save')</button>
	</div>
{!! Form::close() !!}
        <!--end row-->
    </div>
</div>
@endsection

