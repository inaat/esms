@extends("admin_layouts.app")
@section('title', __('english.top_ten_students'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.top_ten_students')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('Examination\ExamResultController@topPositionsPost'), 'method' => 'post', 'id' =>'top-print-form' ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('english.class_level', __('english.class_level') . ':*') !!}
                        {!! Form::select('class_level_id', $class_level, null, ['class' => 'form-select select2', 'required', 'id'=>'class_level_id','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 ">
                        {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                        {!! Form::select('session_id',$sessions,null, ['class' => 'form-select select2 exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                    </div>
                    <div class="col-md-3  p-2">
                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                        {!! Form::select('exam_create_id',[],null, ['class' => 'form-select select2 exam_create_id exam_term_id','required',  'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
     <div class="col-md-3 p-2">
                        {!! Form::label('term', __( 'english.top_position_limit' ) . ':*') !!}
                        {!! Form::text('top_position_number', 10, ['class' => 'form-control input_number', 'placeholder' => __('english.top_position_limit'), 'required']) !!}
                    </div>
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto">
                    
                            <button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                <i class="lni lni-printer"></i>@lang('english.print')</button>

                    </div>
                </div>
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
$('form#top-print-form').validate();
//Quick add brand
$(document).on('submit', 'form#top-print-form', function(e) {
    e.preventDefault();
    var form = $(this);
    var data = form.serialize();

    $.ajax({
        method: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        beforeSend: function(xhr) {
            __disable_submit_button(form.find('button[type="submit"]'));
        },
        success: function(result) {

            __enable_submit_button(form.find('button[type="submit"]'));
           $('.pace-active');
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

    });

</script>
@endsection

