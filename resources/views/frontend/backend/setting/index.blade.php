@extends("admin_layouts.app")
@section('title', __('english.settings'))
@section('title', __('english.settings'))
@section('style')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css" rel="stylesheet">
    <link href="https://www.jquery-az.com/boots/css/bootstrap-colorpicker/bootstrap-colorpicker.css" rel="stylesheet">
    <link href="https://www.jquery-az.com/boots/css/bootstrap-colorpicker/main.css" rel="stylesheet">
    <script src="https://www.jquery-az.com/boots/js/bootstrap-colorpicker/bootstrap-colorpicker.js"></script>
@endsection
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_settings')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.settings')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('Frontend\FrontSettingController@store'), 'class'=>'needs-validation','method' => 'post', 'novalidate','id' => 'bussiness_edit_form',
           'files' => true ]) !!}
        <!--end breadcrumb-->
        <div class="row row-cols-12 row-cols-md-1 row-cols-lg-12 row-cols-xl-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#global_config" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title">@lang('english.general_settings')</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#colors" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title">@lang('english.colors')</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-">
                        <div class="tab-pane fade show active" id="global_config" role="tabpanel">
                         @include('frontend.backend.setting.setting')
                        </div>
                        <div class="tab-pane fade show " id="colors" role="tabpanel">
                         @include('frontend.backend.setting.colors')
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">

                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="ms-auto">
                                @can('general_settings.update')
                                <button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">@lang('english.update_settings')</button>
                                @endcan
                            </div>


                        </div>
                    </div>

                </div>
                {!! Form::close() !!}

                <!--end row-->
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    //gallery table
    $(document).ready(function() {
        $('#main_color').colorpicker();
        $('#hover_color').colorpicker();

    });

</script>
@endsection

