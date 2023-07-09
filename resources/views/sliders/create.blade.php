@extends('admin_layouts.app')
@section('title', __('english.slider'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.all_sliders')</h5>
                    {!! Form::open(['url' => action('SliderController@store'), 'method' => 'post',  'id' => 'slider_form', 'files' => true]) !!}
                    <hr>
                    <div class="row">
                        <div class="col-md-4 p-1">
                            {!! Form::label('files', __('english.files') . ':') !!}
                            <input type="file" name="image" class="form-control"  />        
                        </div>

                        <div class="col-md-6 p-4 text-right">
                            <button type="submit" class="btn btn-primary btn-big">@lang('english.save')</button>
                        </div>
                    </div>
                    
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
