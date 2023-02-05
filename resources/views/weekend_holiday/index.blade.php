@extends("admin_layouts.app")
@section('title', __('english.weekend_and_holiday'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_weekend_and_holiday')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.weekend_and_holiday')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.weekend_and_holiday_list')
                </h5>


                <div class="d-lg-flex align-items-center mb-4 gap-3">

                    <div class="ms-auto"><a type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 " href="{{ action('WeekendHolidayController@create') }}" >
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_weekend_or_holiday')</a></div>

                </div>


                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="weekend_holiday">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.weekend_and_holiday')</th>
                                <th>@lang('english.from')</th>
                                <th>@lang('english.to')</th>
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
<div class="modal fade weekend_holiday_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('shown.bs.modal', '.weekend_holiday_modal', function (e) {
    tinymce.init({
        selector: 'textarea#description',
        height: 500,
    });
});
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
$(document).on('hidden.bs.modal', '.weekend_holiday_modal', function (e) {
    tinymce.remove("textarea#description");
});
            //designations table
                        var i = 1;

            var weekend_holiday = $("#weekend_holiday").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/weekend-holiday",
                columns: [
                      {
                    data: "action",
                    name: "action"
                },
                
                {
                    data: "name",
                    name: "name"
                },
                {
                    data: "from",
                    name: "from"
                },
                {
                    data: "to",
                    name: "to"
                },
                {
                    data: "description",
                    name: "description"
                },
              
                ],
            });

        //Default settings for daterangePicker
        var ranges = {};
        ranges[LANG.today] = [moment(), moment()];
        ranges[LANG.yesterday] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
        ranges[LANG.last_7_days] = [moment().subtract(6, 'days'), moment()];
        ranges[LANG.last_30_days] = [moment().subtract(29, 'days'), moment()];
        ranges[LANG.this_month] = [moment().startOf('month'), moment().endOf('month')];
        ranges[LANG.last_month] = [
            moment()
            .subtract(1, 'month')
            .startOf('month')
            , moment()
            .subtract(1, 'month')
            .endOf('month')
        , ];


        var sdateRangeSettings = {
            ranges: ranges
            , startDate: moment()
            , endDate: moment()
            , locale: {
                cancelLabel: LANG.clear
                , applyLabel: LANG.apply
                , customRangeLabel: LANG.custom_range
                , format: moment_date_format
                , toLabel: '~'
            , }
        , };

    $(document).on("click", ".btnn-modal", function (e) {
        e.preventDefault();
        var container = $(this).data("container");

        $.ajax({
            url: $(this).data("href"),
            dataType: "html",
            success: function (result) {
                                $(container).html(result).modal("show");

                 //Date range as a button
        $('#month_filter_date_range').daterangepicker(
            sdateRangeSettings
            , function(start, end) {
                $('#month_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
                    .format(moment_date_format));

            }
        );

            },
        })
    });


            
            $(document).on("click", "a.delete_weekend_holiday_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_award,
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
                                    weekend_holiday.ajax.reload();
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
