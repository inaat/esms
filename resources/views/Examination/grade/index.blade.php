@extends("admin_layouts.app")
@section('title', __('english.grade'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.all_your_grade')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.grade')</li>
                        </ol>
                    </nav>
                </div>
               
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.grade_list')
                        <small class="text-info font-13"></small>
                    </h5>


                    <div class="d-lg-flex align-items-center mb-4 gap-3">

                        <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('Examination\GradeController@create') }}"
                                data-container=".grade_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_grade')</button></div>

                    </div>


                    <hr>

                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="grade_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('english.grade_name')</th>
                                    <th>@lang('english.point')</th>
                                    <th>@lang('english.percentage_from')</th>
                                    <th>@lang('english.percentage_to')</th>
                                    <th>@lang('english.remark')</th>
                                    <th>@lang('english.action')</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <div class="modal fade grade_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  @endsection


@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            $(".upload_org_logo").on('change', function() {
                __readURL(this, '.org_logo');
            });
            $(".upload_org_favicon").on('change', function() {
                __readURL(this, '.org_favicon');
            });
            //grade table
            var grade_table = $("#grade_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/exam/grades",
                columns: [{
                    data: "name",
                    name: "name"
                },
                {
                    data: "point",
                    name: "point"
                },
                {
                    data: "percentage_from",
                    name: "percentage_from"
                },
                {
                    data: "percentage_to",
                    name: "percentage_to"
                },
                {
                    data: "remark",
                    name: "remark"
                },
                 {
                    data: "action",
                    name: "action"
                }, ],
            });

            $(document).on("submit", "form#grade_add_form", function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $("div.grade_modal").modal("hide");
                            toastr.success(result.msg);
                            grade_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });


            $(document).on("click", "button.edit_grade_button", function() {
                $("div.grade_modal").load($(this).data("href"), function() {
                    $(this).modal("show");

                    $("form#grade_edit_form").submit(function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var data = form.serialize();

                        $.ajax({
                            method: "POST",
                            url: $(this).attr("action"),
                            dataType: "json",
                            data: data,
                            beforeSend: function(xhr) {
                                __disable_submit_button(
                                    form.find('button[type="submit"]')
                                );
                            },
                            success: function(result) {
                                if (result.success == true) {
                                    $("div.grade_modal").modal("hide");
                                    toastr.success(result.msg);
                                    grade_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    });
                });
            });

            $(document).on("click", "button.delete_grade_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_designation,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data("href");
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    grade_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
