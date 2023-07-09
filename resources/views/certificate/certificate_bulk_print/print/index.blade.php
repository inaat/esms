@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.certificate_print')</div>
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
        {!! Form::open(['url' => action('Certificate\CertificatePrintController@store'), 'method' => 'post','id' =>'certificate-print-form']) !!}

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.select_ground')
                    <small class="text-info font-13"></small>
                </h5>
                
                <div class="row">
                 <div class="col-md-3 p-1">
                        {!! Form::label('roll_no', __('english.roll_no')) !!}
                        {!! Form::text('roll_no', null, ['class' => 'form-control', 'placeholder' => __('english.roll_no'), 'required','id' => 'students_list_filter_roll_no']) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id ?? null, ['class' => 'form-select select2 global-campuses', 'id'=>'students_list_filter_campus_id','style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                    </div>

                    <div class="col-md-3 p-1">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('class_id', [],  null, ['class' => 'form-select select2 global-classes', 'style' => 'width:100%', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('certificate_type', __('english.certificate_types') . ':*') !!}
                        {!! Form::select('certificate_type_id', $certificate_type,null, ['class' => 'form-select select2 ', 'required', 'id'=>'','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('certificate', __('english.certificate') . ':*') !!}
                        {!! Form::select('certificate',['original'=>'Original'],null, ['class' => 'form-select select2 ', 'required', 'id'=>'','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="clear-fix"></div>
                    <div class="d-lg-flex align-items-center mt-4 gap-3">
                        <div class="ms-auto">
                        {{-- <a class="btn btn-primary radius-30 mt-2 mt-lg-0 certificate-print"
                                 type="button"  href="#" data-href="{{ action('Examination\ExamDateSheetController@postClassWiseRollSlipPrint') }}">
                                 <i class="lni lni-printer"></i>@lang('english.print')</a> --}}

                                 <button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                <i class="fas fa-filter"></i>@lang('english.filter')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
       <!--end row-->
    {{ Form::close() }}
</div>
</div>
@endsection


@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
$('form#certificate-print-form').validate();
//Quick add brand
$(document).on('submit', 'form#certificate-print-form', function(e) {
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
           $('.pace-active')
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

