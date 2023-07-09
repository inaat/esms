
@extends("admin_layouts.app")
@section('title', __('english.news'))
@section('title', __('english.news'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.news_create')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.news_create')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => action('Frontend\FrontNewsController@store'), 'method' => 'post', 'id' =>'weekend_holiday_add_form' ,'files' => true]) !!}

                <div class="row">
                    <div class="col-md-6 p-3">
                        {!! Form::label('name', __('english.news_title' ) . ':*') !!}
                        {!! Form::text('title',null, ['class' => 'form-control', 'required', 'placeholder' => __('english.news_title' )]); !!}
                    </div>
                   
                    <div class="col-md-6 p-3">
                        {!! Form::label('english.date', __('english.date') . ':*') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                            {!! Form::text('date',@format_date('now'), ['class' => 'form-control date-picker', 'readonly', 'placeholder' => __('english.date')]) !!}

                        </div>
                    </div>
                 
                    <div class="col-md-6 p-3 ">
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label class="form-check-label" for="flexSwitchCheckChecked">@lang( 'english.show_website' )</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="status" type="checkbox" id="flexSwitchCheckChecked" checked="1">
                                </div>
                            </div>
                        </div>                    </div>
                    <div class="col-md-12 p-3">
                        {!! Form::label('description', __('english.description' ) . ':') !!}
                        {!! Form::textarea('description',null, ['class' => 'form-control', 'id'=>'description','placeholder' => __('english.description' )]); !!}
                    </div>
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            @lang('english.save')</button></div>
                </div>
                {!! Form::close() !!}
            </div>
            <!--end row-->
        </div>
    </div>

    @endsection
    @section('javascript')
    <script type="text/javascript">
        //news table
        $(document).ready(function() {
            if ($('textarea#description').length > 0) {
            tinymce.init({
                selector: 'textarea#description'
                , height: "50vh"
            });
        }


        });

    </script>
    @endsection
