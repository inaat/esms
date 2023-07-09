
@extends("admin_layouts.app")
@section('title', __('english.edit_expense'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.edit_expense')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.edit_expense')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
       {!! Form::open(['url' => action('ExpenseTransactionController@update', [$expense->id]), 'method' => 'PUT', 'id' => 'add_expense_form', 'files' => true ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row ">


                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses,$expense->campus_id,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.expense_categories', __('english.expense_categories') . ':*') !!}
                        {!! Form::select('expense_category_id', $expense_categories, $expense->expense_category_id,['class' => 'form-select select2','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2 ">
                        {!! Form::label('ref_no', __('english.ref_no').':') !!}
                        {!! Form::text('ref_no',$expense->ref_no, ['class' => 'form-control']); !!}
                        <p class="help-block">
                            @lang('english.leave_empty_to_auto_generate')
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 " id="datetimepicker" data-target-input="nearest" data-target="#datetimepicker" data-toggle="datetimepicker">
                        {!! Form::label('paid_on', __('english.paid_on') . ':*') !!}
                        <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('transaction_date', @format_datetime($expense->transaction_date), ['class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('expense_for', __('english.expense_for').':') !!} @show_tooltip(__('tooltip.expense_for'))
                        {!! Form::select('expense_for', $employees,$expense->expense_for, ['class' => 'form-control select2', 'placeholder' => __('english.please_select')]); !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('final_total', __('english.total_amount') . ':*') !!}
                        {!! Form::text('final_total',@num_format($expense->final_total), ['class' => 'form-control input_number', 'placeholder' => __('english.total_amount'), 'required']); !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('additional_notes', __('english.expense_note') . ':') !!}
                        {!! Form::textarea('additional_notes',$expense->additional_notes, ['class' => 'form-control', 'rows' => 1]); !!}
                    </div>
                     <div class="col-md-4 p">
                        {!! Form::label('english.vehicles', __('english.vehicles') . ':') !!}
                        {!! Form::select('vehicle_id',$vehicles,$expense->vehicle_id, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'id'=>'vehicle_id','placeholder' => __('english.please_select')]) !!}
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

        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-primary btn-big">@lang('english.update')</button>
        </div>
        {!! Form::close() !!}
        <!--end row-->
    </div>
</div>
@endsection

