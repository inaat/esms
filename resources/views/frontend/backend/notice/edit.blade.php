     
@extends("admin_layouts.app")
@section('title', __('english.notice'))
@section('title', __('english.notice'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.notice_edit')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.notice_edit')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => action('Frontend\FrontNoticeController@update',[$notice->id]), 'method' => 'PUT', 'id' =>'weekend_holiday_add_form']) !!}

                <div class="row">
                    <div class="col-md-6 p-3">
                        {!! Form::label('name', __('english.notice_title' ) . ':*') !!}
                        {!! Form::text('title',$notice->title, ['class' => 'form-control', 'required', 'placeholder' => __('english.notice_title' )]); !!}
                    </div>
                 
                    <div class="col-md-6 p-3 ">
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label class="form-check-label" for="flexSwitchCheckChecked">@lang( 'english.show_website' )</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="status" type="checkbox" id="flexSwitchCheckChecked" @if($notice->status=='publish') checked @endif>
                                </div>
                            </div>
                        </div>                    </div>
                    <div class="col-md-12 p-3">
                        {!! Form::label('description', __('english.description' ) . ':') !!}
                        {!! Form::textarea('description',$notice->description, ['class' => 'form-control', 'id'=>'description','placeholder' => __('english.description' )]); !!}
                    </div>
                      <div class="col-md-12 p-3">
                        {!! Form::label('link', __('english.link' ) . ':') !!}
                        {!! Form::text('link',$notice->link, ['class' => 'form-control', 'required', 'placeholder' => __('english.link' )]); !!}
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
        //notice table
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
