@extends("admin_layouts.app")
@section('title', __('english.departments'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.all_your_departments')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.departments')</li>
                        </ol>
                    </nav>
                </div>
               
            </div> 
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.department_list')
                        <small class="text-info font-13"></small>
                    </h5>

                   @can('department.create')
                    <div class="d-lg-flex align-items-center mb-4 gap-3">

                        <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('Hrm\HrmDepartmentController@create') }}"
                                data-container=".departments_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_department')</button></div>

                    </div>

                     @endcan
                    <hr>

                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="departments_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('english.department_name')</th>
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
    <div class="modal fade departments_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
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
            //departments table
            var departments_table = $("#departments_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/hrm-department",
                columns: [{
                    data: "department",
                    name: "department"
                }, {
                    data: "action",
                    name: "action"
                }, ],
            });

            $(document).on("submit", "form#department_add_form", function(e) {
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
                            $("div.departments_modal").modal("hide");
                            toastr.success(result.msg);
                            departments_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });


            $(document).on("click", "button.edit_department_button", function() {
                $("div.departments_modal").load($(this).data("href"), function() {
                    $(this).modal("show");

                    $("form#department_edit_form").submit(function(e) {
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
                                    $("div.departments_modal").modal("hide");
                                    toastr.success(result.msg);
                                    departments_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    });
                });
            });

            $(document).on("click", "button.delete_department_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_department,
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
                                    departments_table.ajax.reload();
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
