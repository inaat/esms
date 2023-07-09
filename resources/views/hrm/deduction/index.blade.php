@extends("admin_layouts.app")
@section('title', __('english.deductions'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.all_your_deductions')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.deductions')</li>
                        </ol>
                    </nav>
                </div>
               
            </div> 
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.deduction_list')
                        <small class="text-info font-13"></small>
                    </h5>


                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                    @can("deduction.create")
                        <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('Hrm\HrmDeductionController@create') }}"
                                data-container=".deductions_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_deduction')</button></div>
                                @endcan

                    </div>


                    <hr>

                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="deductions_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('english.deduction_name')</th>
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
    <div class="modal fade deductions_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
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
            //deductions table
            var deductions_table = $("#deductions_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/hrm-deduction",
                columns: [{
                    data: "deduction",
                    name: "deduction"
                }, {
                    data: "action",
                    name: "action"
                }, ],
            });

            $(document).on("submit", "form#deduction_add_form", function(e) {
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
                            $("div.deductions_modal").modal("hide");
                            toastr.success(result.msg);
                            deductions_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });


            $(document).on("click", "button.edit_deduction_button", function() {
                $("div.deductions_modal").load($(this).data("href"), function() {
                    $(this).modal("show");

                    $("form#deduction_edit_form").submit(function(e) {
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
                                    $("div.deductions_modal").modal("hide");
                                    toastr.success(result.msg);
                                    deductions_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    });
                });
            });

            $(document).on("click", "button.delete_deduction_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_deduction,
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
                                    deductions_table.ajax.reload();
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
