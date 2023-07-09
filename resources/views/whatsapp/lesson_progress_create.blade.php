@extends("admin_layouts.app")
@section('title', __('english.lesson_send_to_students'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.lesson_send_to_students')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.lesson_send_to_students')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('NotificationController@lessonProgressSendPost'), 'method' => 'post', 'id' =>'notification_form' ]) !!}

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">

                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>

                    <div class="col-md-4 p-1">
                        {!! Form::label('english.date', __('english.date') . ':*') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                            {!! Form::text('date', @format_date('now'), ['class' => 'form-control date-picker', 'placeholder' => __('english.start_date'), 'readonly']) !!}

                        </div>
                    </div>
                    <div class="col-md-4 mt-4 ">
                        <input type="radio" class="form-check-input big-checkbox" name="send_through" checked value="whatsapp_group">
                        {{ Form::label('whatsapp_group', __('english.whatsapp_group') , ['class' => 'control-label mt-2 ']) }}

                        <input type="radio" class="form-check-input big-checkbox" name="send_through" value="single_wise_sms">
                        {{ Form::label('single_wise_sms', __('english.single_wise_sms') , ['class' => 'control-label mt-2 ']) }}

                    </div>
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fadeIn animated bx bx-send"></i>@lang('english.send')</button></div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}

    </div>
</div>

@endsection


@section('javascript')
<script type="text/javascript">
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

