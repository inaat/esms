@extends("admin_layouts.app")
@section('title', __('english.enrolled_subjects_in_this_exam'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.enrolled_subjects_in_this_exam')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.enrolled_subjects_in_this_exam')</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="row">

                @if (isset($missing_subjects))
                        {!! Form::open(['url' => action('Examination\ExamSetupController@postDeleteSubjects'), 'method' => 'POST', 'id' => 'store_subject_fee' ]) !!}
                    <div class="row">

                        <div class="col-lg-12">
                              {!! Form::hidden('exam_create_id',$exam_create->id) !!}
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0" width="100%" id="missing_subjects_table">
                                            <thead class="table-light" width="100%">
                                                <tr>
                                                    {{-- <th>#</th> --}}

                                                    <th> <input type="checkbox" id="checkAll"
                                                            class="common-checkbox form-check-input mt-2" name="checkAll">
                                                        <label for="checkAll">@lang('english.all')</label>
                                                    </th>
                                                    <th>@lang('english.campus_name')</th>
                                                    <th>@lang('english.class_name')</th>
                                                    <th>@lang('english.subject_name')</th>
                                                    <th>@lang('english.teacher')</th>
                                                </tr>
                                            </thead>
                                            <tbody class="">

                                                @foreach ($missing_subjects as $subject)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" id="subject.{{ $subject->id }}"
                                                                class="common-checkbox form-check-input mt-2"
                                                                name="subject_checked[]" value="{{ $subject->id }}">
                                                            <label for="subject.{{ $subject->id }}"></label>
                                                        </td> 
                                                        <td>{{ $subject->campus_name }}</td> 
                                                        <td>{{ $subject->class_name }} {{ $subject->section_name }}</td>
                                                        <td>{{ $subject->subject_name }}</td>
                                                        <td>{{ $subject->teacher_name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            {{-- @if ($missing_subjects->count() > 0) --}}
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="text-center">
                                                            <button type="submit" id="btn-assign-fees-group"
                                                                class="btn btn-danger radius-30 mt-2 mt-lg-0 fix-gr-bg mb-0 submit"
                                                                id="btn-assign-fees-group"
                                                                data-loading-text="<i class='fas fa-spinner'></i> Processing Data">
                                                                <span class="ti-save pr"></span>
                                                                       @lang('english.delete')
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            {{-- @endif --}}
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





                // fees group assign

                $("form#store_subject_fee").submit(function(event) {
                    var url = $("#url").val();
                    var subject_checked = $("input[name='subject_checked[]']:checked")
                        .map(function() {
                            return $(this).val();
                        })
                        .get();
                    if (subject_checked.length < 1) {
                        event.preventDefault();
                        toastr.error("Please select at least one subject");
                        return false;
                    }
                });

            });
        </script>
    @endsection
