@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.certificate_issue')</div>
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
        {!! Form::open(['url' => action('Certificate\CertificateBulkPrintController@store'), 'method' => 'post', 'class'=>'needs-validation was-validated','novalidate'.'id' =>'search_student_fee' ,'files' => true]) !!}

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.select_ground')</h5>
                <small class="text-info font-13"></small>
                </h5>

                <div class="row">
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id ?? null, ['class' => 'form-select select2 global-campuses','required', 'id'=>'students_list_filter_campus_id','style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                    </div>

                    <div class="col-md-3 p-1">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('current_class_id', $classes ?? [], $class_id?? null, ['class' => 'form-select select2 global-classes','required','style' => 'width:100%', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('certificate_type', __('english.certificate_types') . ':*') !!}
                        {!! Form::select('certificate_type_id', $certificate_type,$certificate_type_id??null, ['class' => 'form-select select2 ', 'required', 'id'=>'','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('status', __('english.student_status') . ':*') !!}
                        {!! Form::select('status', __('english.std_status'),'active', ['class' => 'form-control','id'=>'students_list_filter_status','placeholder' => __('english.please_select'), 'required']); !!}
                    </div>
                    <div class="clear-fix"></div>
                    <div class="d-lg-flex align-items-center mt-4 gap-3">
                        <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}

    @if (isset($certificate_issue))
    {!! Form::open(['url' => action('Certificate\CertificateBulkPrintController@BulkPrint'), 'method' => 'post', 'class' => '', '' . 'id' => 'certificate-print-form', 'files' => true]) !!}
    <div class="row">
        {!! Form::hidden("certificate_type_id", $certificate_type_id) !!}

        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="students_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    {{-- <th>#</th> --}}

                                    <th> <input type="checkbox" id="checkAll" class="common-checkbox form-check-input mt-2" name="checkAll">
                                        <label for="checkAll">@lang('english.all')</label>
                                    </th>
                                    <th>@lang('english.student_name')</th>
                                    <th>@lang('english.certificate_type')</th>
                                    <th>@lang('english.current_class')</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($certificate_issue as $issue)
                                <tr>
                                    <td>
                                        <input type="checkbox" id="student.{{ $issue->id }}" class="common-checkbox form-check-input mt-2" name="student_checked[]" value="{{ $issue->id }}">
                                        <label for="student.{{ $issue->id }}"></label>
                                    </td>
                                    <td>{{ ucwords($issue->student_name) }}
                                        <input type="hidden" name="id[]" value="{{ $issue->id }}">
                                    </td>

                                    <td>{{ $issue->certificate_type }}</td>
                                    <td>{{ $issue->current_class }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                            @if ($certificate_issue->count() > 0)
                            <tr>
                                <td colspan="7">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary radius-30 mt-2 mt-lg-0 fix-gr-bg mb-0 submit" data-loading-text="<i class='fas fa-spinner'></i> Processing Data">
                                            <span class="ti-save pr"></span>
                                            @lang('english.print')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Form::close() }}
    @endif
</div>
</div>
@endsection


@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {


        if ($("#table_id_table").length) {
            $("#table_id_table").DataTable({
                dom: 'T<"clear"><"button">lfrtip'
                , bFilter: false
                , bLengthChange: false
            , });
        }




        // Fees Assign
        $("#checkAll").on("click", function() {
            $(".common-checkbox").prop("checked", this.checked);
        });

        $(".common-checkbox").on("click", function() {
            if (!$(this).is(":checked")) {
                $("#checkAll").prop("checked", false);
            }
            var numberOfChecked = $(".common-checkbox:checked").length;
            var totalCheckboxes = $(".common-checkbox").length;
            var totalCheckboxes = totalCheckboxes - 1;

            if (numberOfChecked == totalCheckboxes) {
                $("#checkAll").prop("checked", true);
            }
        });


        $(document).on('change', 'input.amount,input.fee-head-check', function() {
            var total = 0;
            var table = $(this).closest('table');
            table.find('tbody tr').each(function() {
                if ($(this).find('input.fee-head-check').is(':checked')) {
                    var denomination = $(this).find('input.amount').val() ? parseInt($(this)
                        .find('input.amount').val()) : 0;
                    var subtotal = denomination;
                    total = total + subtotal;
                }
            });
            table.find('span.final_total').text(__currency_trans_from_en(total, true));
            $('input#final_total').val(total);

        });


        // fees group assign

        $("form#certificate-print-form").submit(function(event) {
            var url = $("#url").val();
            var student_checked = $("input[name='student_checked[]']:checked")
                .map(function() {
                    return $(this).val();
                })
                .get();
            if (student_checked.length < 1) {
                event.preventDefault();
                toastr.error("Please select at least one student");
                return false;
            }
        });
      
    });

</script>
@endsection

