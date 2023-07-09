@extends("admin_layouts.app")
@section('title', __('english.missing_students_in_this_exam'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.missing_students_in_this_exam')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.missing_students_in_this_exam')</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">
              
                @if (isset($students))
                        {!! Form::open(['url' => action('Examination\ExamSetupController@update', [$exam_create->id]), 'method' => 'PUT', 'id' => 'store_student_fee' ]) !!}
                    <div class="row">
                        
                        <div class="col-lg-12">

                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0" width="100%" id="students_table">
                                            <thead class="table-light" width="100%">
                                                <tr>
                                                    {{-- <th>#</th> --}}

                                                    <th> <input type="checkbox" id="checkAll"
                                                            class="common-checkbox form-check-input mt-2" name="checkAll">
                                                        <label for="checkAll">@lang('english.all')</label>
                                                    </th>
                                                    <th>@lang('english.student_name')</th>
                                                    <th>@lang('english.father_name')</th>
                                                    <th>@lang('english.roll_no')</th>
                                                    <th>@lang('english.current_class')</th>
                                                    <th>@lang('english.gender')</th>
                                                </tr>
                                            </thead>
                                            <tbody class="">
                                                @foreach ($students as $student)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" id="student.{{ $student->id }}"
                                                                class="common-checkbox form-check-input mt-2"
                                                                name="student_checked[]" value="{{ $student->id }}">
                                                            <label for="student.{{ $student->id }}"></label>
                                                        </td>
                                                        <td>{{ ucwords($student->student_name) }}
                                                            <input type="hidden" name="id[]" value="{{ $student->id }}">
                                                        </td>
                                                        <td>{{ ucwords($student->father_name) }}</td>
                                                        <td>{{ $student->roll_no }}</td>
                                                        <td>{{ $student->current_class }}</td>
                                                        <td>{{ ucwords($student->gender) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            @if ($students->count() > 0)
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="text-center">
                                                            <button type="submit" id="btn-assign-fees-group"
                                                                class="btn btn-primary radius-30 mt-2 mt-lg-0 fix-gr-bg mb-0 submit"
                                                                id="btn-assign-fees-group"
                                                                data-loading-text="<i class='fas fa-spinner'></i> Processing Data">
                                                                <span class="ti-save pr"></span>
                                                                       @lang('english.submit')
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
                        dom: 'T<"clear"><"button">lfrtip',
                        bFilter: false,
                        bLengthChange: false,
                    });
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

                $("form#store_student_fee").submit(function(event) {
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
