@extends("admin_layouts.app")
@section('title', __('english.add_new_weekend_or_holiday'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.add_new_weekend_or_holiday')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.add_new_weekend_or_holiday')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('WeekendHolidayController@store'), 'method' => 'post', 'id' =>'weekend_holiday_add_form' ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 ">
                        {!! Form::label('name', __( 'english.name' ) . ':*') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.name' ) ]); !!}
                    </div>

                    <div class="col-md-4 ">
                        {!! Form::label('english.date_range', __('english.date_range') . ':') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('list_filter_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'month_filter_date_range', 'class' => 'form-control']) !!}

                        </div>
                    </div>
                    <div class="col-md-3 ">
                        {!! Form::label('english.type', __('english.type') . ':*') !!}
                        {!! Form::select('type',['holiday'=>'Holiday','weekend'=>'Weekend'],null, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-2">

                        <div class="checkbox mt-4">
                            <label>
                                {!! Form::checkbox('sms', 1, null, ['class' => ' form-check-input ']); !!} @lang('english.sms_status')</label>
                        </div>

                    </div>
                    <div class="col-md-4">

                        <div class="checkbox mt-4">
                            <label>
                                {!! Form::checkbox('employee_include', 1, null, ['class' => ' form-check-input ']); !!} @lang('english.employee_include')</label>
                        </div>

                    </div>

                    <div class="clearfix"></div>
                    <hr class="mt-2">
                    <div class="col-md-6 mt-2 ">
                        {!! Form::label('description', __( 'english.description' ) . ':*') !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control' ,'id' =>'description']); !!}
                    </div>
                    <div class="col-md-6  mt-2">
                        {!! Form::label('select_class_section', __( 'english.select_class_section' ) . ':*') !!}

                        <table id="table_id_table" class="table table-condensed table-striped " width="100%">
                            <thead class="table-light" width="100%">
                                <tr>
                                    {{-- <th>#</th> --}}
                                    <th> <input type="checkbox" id="checkAll" class="common-checkbox form-check-input mt-2" name="checkAll">
                                        <label for="checkAll">@lang('english.all')</label>
                                    </th>
                                    <th class="text-center">@lang('english.enable')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $class_sections as $section)

                                <tr>
                                    <td class="text-left">
                                        @lang('english.class'):{{ $section->classes->title }} @lang('english.section') {{ $section->section_name }} ({{ $section->campuses->campus_name }})
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" id="section.{{ $section->id }}" class="common-checkbox form-check-input mt-2" name="class_section[]" value="{{ $section->id }}" >
                                        <label for="section.{{ $section->id }}"></label>
                                    </td>

                                </tr>
                                @endforeach
                            <tfoot>

                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
        <div class="d-lg-flex align-items-center mt-4 gap-3">
            <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                    @lang('english.save')</button></div>
        </div>
        {!! Form::close() !!}

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
                , scrollY: "40vh"
                , scrollX: true
                , scrollCollapse: true
                , paging: false
            });
        }

        if ($('textarea#description').length > 0) {
            tinymce.init({
                selector: 'textarea#description'
                , height: "50vh"
            });
        }
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

        //designations table
        var i = 1;

        var weekend_holiday = $("#weekend_holiday").DataTable({
            processing: true
            , serverSide: true
            , ajax: "/weekend-holiday"
            , columns: [{
                    data: "rownum"
                    , name: "rownum"
                , }
                , {
                    data: "name"
                    , name: "name"
                }
                , {
                    data: "from"
                    , name: "from"
                }
                , {
                    data: "to"
                    , name: "to"
                }
                , {
                    data: "description"
                    , name: "description"
                }
                , {
                    data: "action"
                    , name: "action"
                }
            , ]
        , });

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
        //Date range as a button
        $('#month_filter_date_range').daterangepicker(
            sdateRangeSettings
            , function(start, end) {
                $('#month_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
                    .format(moment_date_format));

            }
        );
    });

</script>
@endsection

