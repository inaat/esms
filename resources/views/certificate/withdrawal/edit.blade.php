@extends("admin_layouts.app")
@section('title', __('english.withdrawal'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.mark_entry')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.mark_entry')</li>
                    </ol>
                </nav>
            </div>
        </div>
        {!! Form::open(['url' => action('Certificate\WithdrawalRegisterController@update',[$withdrawal_register->id]), 'method' => 'PUT', 'id' =>'withdrawal_register_form' ]) !!}

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">

                    <div class="col-md-8 p-2 ">
                        {!! Form::label('withdraw_reason', __('english.withdraw_reason') . ':*', ['classs' => 'form-lable']) !!}
                        {!! Form::text('withdraw_reason',$withdrawal_register->withdraw_reason, ['class' => 'form-control', 'required','placeholder' => __('english.withdraw_reason')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('english.date_of_leaving', __('english.date_of_leaving') . ':*') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('date_of_leaving',!empty($withdrawal_register->date_of_leaving) ?@format_date($withdrawal_register->date_of_leaving):@format_date('now'), ['class' => 'form-control date-picker', 'readonly', 'placeholder' => __('english.date_of_leaving')]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-4 p-2">
                        {!! Form::label('slc_no', __('english.slc_no') . ':*', ['classs' => 'form-lable']) !!}
                        {!! Form::text('slc_no',$slc_no, ['class' => 'form-control', 'required','readonly','placeholder' => __('english.slc_no')]) !!}

                    </div>
                    <div class="col-md-4 p-1">
                        {!! Form::label('any_remarks', __('english.any_remarks') . ':*', ['classs' => 'form-lable']) !!}
                        {!! Form::text('any_remarks',$withdrawal_register->any_remarks, ['class' => 'form-control', 'required','placeholder' => __('english.any_remarks')]) !!}

                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('co_curricular_activities', __('english.co_curricular_activities') . ':*', ['classs' => 'form-lable']) !!}
                        {!! Form::text('co_curricular_activities',$withdrawal_register->co_curricular_activities, ['class' => 'form-control', 'required','placeholder' => __('english.co_curricular_activities')]) !!}


                    </div>
                        <div class="clearfix"></div>

                    <div class="col-md-12 p-2">
                    {!! Form::label('local_withdrawal_register_detail', __( 'english.local_withdrawal_register_detail' ) . ':') !!}
                    {!! Form::textarea('local_withdrawal_register_detail',$withdrawal_register->local_withdrawal_register_detail, ['class' => 'form-control' ]); !!}

                    </div>
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-plus"></i>@lang('english.withdraw_student')</button></div>
                </div>
            </div>
        </div>


        {{ Form::close() }}
    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {


    });

</script>
@endsection

