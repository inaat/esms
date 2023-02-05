@extends("admin_layouts.app")
@section('title', __('english.study_period'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.all_your_study_periods')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.study_period')</li>
                        </ol>
                    </nav>
                </div>
          
            </div> 
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.study_period')<br>
                        <small class="text-info font-13">@lang('english.study_period_info')</small>
                    </h5>


                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                         @can('study_period.create')
                        <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('Curriculum\ClassTimeTablePeriodController@create') }}" data-container=".period_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_period')</button></div>
                                @endcan

                    </div>


                    <hr>

                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="class_time_table_period">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('english.action')</th>
                                    <th>@lang('english.campus_name')</th>
                                    <th>@lang('english.name')</th>
                                    <th>@lang('english.start_time')</th>
                                    <th>@lang('english.end_time')</th>
                                    <th>@lang('english.duration')</th>
                                    <th>@lang('english.type')</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <div class="modal fade period_modal contains_select2" id="period_modal" tabindex="-1" role="dialog"
        aria-labelledby="gridSystemModalLabel"></div>
   
@endsection


@section('javascript')
    <script type="text/javascript">
            $(document).ready(function() {

    $('#period_modal').on('shown.bs.modal', function(e) {

                var $p = $(this);
                $('#period_modal .select2').select2({
                    dropdownParent: $p
                });
                $('#start_timepicker,#end_timepicker').datetimepicker({
                    format: moment_time_format,
                    ignoreReadonly: true,
                });


           
            });
      
            //class_time table_periodtable
            var class_time_table_period = $("#class_time_table_period").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/class-time-table-period",
                columns: [
                    {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false
                },
               
                {
                    data: "campus_name",
                    name: "campus_name",
                    
                },
                  {
                    data: "name",
                    name: "name",
                    
                }, {
                    data: "start_time",
                    name: "start_time",
                    orderable: false,
                    searchable: false
                }, {
                    data: "end_time",
                    name: "end_time"
                    ,
                    orderable: false,
                    searchable: false
                }, {
                    data: "total_time",
                    name: "total_time"
                    ,
                    orderable: false,
                    searchable: false
                }, {
                    data: "type",
                    name: "type"
                }
                ],
              
            });

            $(document).on("submit", "form#add_period_form", function(e) {
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
                            $("div.period_modal").modal("hide");
                            toastr.success(result.msg);
                            class_time_table_period.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });


            $(document).on("click", "a.edit_period_button", function() {
                $("div.period_modal").load($(this).data("href"), function() {
                    $(this).modal("show");

                    $("form#period_edit_form").submit(function(e) {
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
                                    $("div.period_modal").modal("hide");
                                    toastr.success(result.msg);
                                    class_time_table_period.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    });
                });
            });

            $(document).on("click", "a.delete_period_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_class_subject,
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
                                    class_time_table_period.ajax.reload();
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
