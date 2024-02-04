
@extends("admin_layouts.app")
@section('title', __('english.custom_navbar'))
@section('title', __('english.custom_navbar'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.custom_navbar_create')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.custom_navbar_create')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => action('Frontend\FrontCustomPageNavbarController@store'), 'method' => 'post', 'id' =>'weekend_holiday_add_form' ]) !!}

                <div class="row">
                    <div class="col-md-4 p-3">
                        {!! Form::label('name', __('english.title' ) . ':*') !!}
                        {!! Form::text('title',null, ['class' => 'form-control', 'required', 'placeholder' => __('english.title' )]) !!}
                    </div>
                   
                      <div class="col-md-4 p-3">
				      {!! Form::label('page_type', __('english.page_type') . ':*') !!} 
				      {!! Form::select('type', __('english.page_types'), 'active', ['class' => 'form-select', 'placeholder' => __('english.please_select'), 'required']); !!}

                     </div>
                    
                    <div class="col-md-12 p-3 ">
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
   
