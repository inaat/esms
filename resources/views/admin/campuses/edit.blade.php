@extends("admin_layouts.app")
@section('title', __('english.campus'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.your_campus_details')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.campuses')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        {!! Form::open(['url' => action('\App\Http\Controllers\CampusController@update', [$campuses->id]), 'method' => 'PUT', 'class'=>'needs-validation was-validated','novalidate' ]) !!}

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.edit_campus')</h5>
                <div class="row">
                    <div class="col-md-4 p-3">
                        {!! Form::label('campus_name', __('english.campus_name') . ':*', ['classs' => 'form-lable']) !!}
                        {!! Form::text('campus_name',$campuses->campus_name, ['class' => 'form-control', 'required', 'placeholder' => __('english.campus_name')]) !!}

                    </div>
                    <div class="col-md-4 p-3">
                        {!! Form::label('registration_code', __('english.registration_code') . ':*') !!}
                        {!! Form::text('registration_code',$campuses->registration_code, ['class' => 'form-control','required', 'placeholder' => __('english.registration_code')]) !!}
                    </div>
                    <div class="col-md-4 p-3">
                        {!! Form::label('registration_date', __('english.registration_date') . ':*', ['classs' => 'form-lable']) !!}

                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                            {!! Form::text('registration_date', @format_date($campuses->registration_date), ['class' => 'form-control start-date-picker', 'placeholder' => __('english.registration_date'), 'readonly']) !!}

                        </div>
                    </div>
                                        <div class="clearfix"></div>

                    <div class="col-md-4 p-3">
                        {!! Form::label('mobile', __('english.mobile') . ':') !!}
                        {!! Form::text('mobile',$campuses->mobile, ['class' => 'form-control', 'required', 'pattern' => '\d{11}','maxlength' => '11','placeholder' => __('english.mobile')]) !!}
                    </div>

                    <div class="col-md-4 p-3">
                        {!! Form::label('phone', __('english.phone') . ':') !!}
                        {!! Form::text('phone',$campuses->phone, ['class' => 'form-control','required', 'placeholder' => __('english.phone')]) !!}
                    </div>
                    <div class="col-md-4 p-3">
                        {!! Form::label('address', __('english.address') . ':', ['classs' => 'form-lable']) !!}
                        {!! Form::text('address', $campuses->address, ['class' => 'form-control', 'required', 'placeholder' => __('english.address')]) !!}

                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="ms-auto">
                                <button class="btn- btn btn-primary radius-30 mt-2 mt-lg-0">@lang('english.update')</button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--end row-->


        {!! Form::close() !!}

    </div>
</div>
@endsection

