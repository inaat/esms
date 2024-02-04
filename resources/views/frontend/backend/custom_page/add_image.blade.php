@extends("admin_layouts.app")
@section("style")
<style type="text/css">
    .hidden-div {
        display: none;
    }

</style>
@endsection
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ $gallery->title }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.upload')</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('Frontend\FrontCustomPageController@storeElement',[$gallery->id]), 'method' => 'PUT', 'id' =>'' ,'files' => true]) !!}

        <div class="row">
            <div class="col-xl-9 mx-auto">
                {!! Form::label('name', __('english.type' ) . ':*') !!}
                {!! Form::select('type', ['1' => 'Photo', '2' => 'Video URL' ,'3'=>'File'],1, ['class' => 'form-select select2', 'required', 'id' => 'type', 'style' => 'width:100%']) !!}
            </div>
            <div class="col-xl-9 mx-auto p-3  video_url hidden-div">
                {!! Form::label('name', __('english.video_url' ) . ':') !!}
                {!! Form::text('video_url', null, ['class' => 'form-control', 'placeholder' => __('english.video_url' )]); !!}
            </div>
        </div>
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">@lang('english.upload')</h6>
                <hr />

                <div class="card">
                    <div class="card-body">
                    {!! Form::file('thumb_image[]', ['class' => 'form-control upload_thumb_image','multiple']); !!} </div>
                    </div>
                </div>
            </div>
        </div>
        
            <div class="row">
                <div class="col-xl-9 mx-auto">

                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto">
                            <button type="submit" class="btn- btn btn-primary radius-30 mt-2 mt-lg-0">@lang('english.save')</button>
                        </div>

                    </div>
                </div>

            </div>
    {!! Form::close() !!}

    </div>
</div>
@endsection

@section("script")

<script>

    $('#type').on('change', function() {
        var value = $(this).val();
        if (value == 2) {
            $('.video_url').show(200);
        } else {
            $('.video_url').hide(200);
        }
    });
    $('#fancy-file-upload').FancyFileUpload({
        params: {
            action: 'fileuploader'
        }
        , maxfilesize: 1000000
    });
</script>

@endsection

