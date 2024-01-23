@extends("admin_layouts.app")
@section('title', __('english.exam_setup'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_exams')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.exams')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.exams_list')
                </h5>


                <div class="d-lg-flex align-items-center mb-4 gap-3">

                    <div class="ms-auto"><a type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 " href="{{ action('Examination\ExamSetupController@create') }}" >
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_exam')</a></div>

                </div>


                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="exam_setup">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.session')</th>
                                <th>@lang('english.exam_term')</th>
                                <th>@lang('english.roll_no_type')</th>
                                <th>@lang('english.order_type')</th>
                                <th>@lang('english.start_from')</th>
                                <th>@lang('english.from_date')</th>
                                <th>@lang('english.to_date')</th>
                                <th>@lang('english.description')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade exam_setup_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
 

            //Exam Setup table
                        var i = 1;

            var exam_setup = $("#exam_setup").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/exam/setup",
                columns: [
                      {
                    data: "action",
                    name: "action"
                },
                {
                    data: "campus_name",
                    name: "campus_name",
                },
                 {
                    data: "title",
                    name: "title"
                },
               {
                    data: "name",
                    name: "name"
                },
                 {
                    data: "roll_no_type",
                    name: "roll_no_type"
                },
                {
                    data: "order_type",
                    name: "order_type"
                },
                {
                    data: "start_from",
                    name: "start_from"
                },
              {
                    data: "from_date",
                    name: "from_date"
                },
                 {
                    data: "to_date",
                    name: "to_date"
                },
                {
                    data: "description",
                    name: "description"
                }, 
              
                ],
            });
            $(document).on("click", "a.delete_exam_setup_button", function() {
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
                                    exam_terms_table.ajax.reload();
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
