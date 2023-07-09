@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('designation.all_your_designations')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('designation.designations')</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">@lang('designation.settings')</button>
                        <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                                href="{{ url('/home') }} ">Action</a>
                            <a class="dropdown-item" href="{{ url('/home') }} ">Another action</a>
                            <a class="dropdown-item" href="{{ url('/home') }} ">Something else here</a>
                            <div class="dropdown-divider"></div> <a class="dropdown-item" href="{{ url('/home') }} ">Separated
                                link</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('designation.designation_list')
                        <small class="text-info font-13"></small>
                    </h5>


                    <div class="d-lg-flex align-items-center mb-4 gap-3">

                        <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('DesignationController@create') }}"
                                data-container=".designations_modal">
                                <i class="bx bxs-plus-square"></i>Add
                                New Designation</button></div>

                    </div>


                    <hr>

                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="designations_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('designation.designation_name')</th>
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
    <div class="modal fade designations_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
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
            //designations table
            var designations_table = $("#designations_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/designation",
                columns: [{
                    data: "title",
                    name: "title"
                }, {
                    data: "action",
                    name: "action"
                }, ],
            });

            $(document).on("submit", "form#designation_add_form", function(e) {
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
                            $("div.designations_modal").modal("hide");
                            toastr.success(result.msg);
                            designations_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });


            $(document).on("click", "button.edit_designation_button", function() {
                $("div.designations_modal").load($(this).data("href"), function() {
                    $(this).modal("show");

                    $("form#designation_edit_form").submit(function(e) {
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
                                    $("div.designations_modal").modal("hide");
                                    toastr.success(result.msg);
                                    designations_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    });
                });
            });

            $(document).on("click", "button.delete_designation_button", function() {
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
                                    designations_table.ajax.reload();
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
