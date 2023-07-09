@extends("admin_layouts.app")
@section('title', __('english.profile'))
@section('wrapper')
<div class="page-wrapper">
    @section("style")
    <link href="{{ asset('/js/calender/zabuto_calendar.min.css')}}" rel="stylesheet" />
    <style>
        .holiday {
            background-color: #420E5F !important;
            color: #fff !important;
        }

        .weekend {
            background-color: #3A565C !important;
            color: #fff !important;
        }

    </style>
    @endsection
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.profile')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.profile')</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="box-body box-profile text-center">
                                    <img src="{{ file_exists(public_path('uploads/employee_image/'.$employee->employee_image))  ? url('uploads/employee_image/', $employee->employee_image) : url('uploads/employee_image/default.jpg') }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="150" height="150">
                                    <h6 class="profile-username text-center  mt-2">{{ ucwords($employee->first_name . ' '. $employee->last_name) }}</h6>

                                    <ul class="list-group list-group-unbordered list-group list-group-flush">

                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.employeeID')</h6>
                                            <span class="text-secondary">{{ ucwords($employee->employeeID ) }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.designation')</h6>
                                            <span class="text-secondary">{{ $employee->designations ? $employee->designations->designation:''  }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.email')</h6>
                                            <span class="text-secondary">{{ ucwords($employee->email ) }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.religion')</h6>
                                            <span class="text-secondary">{{ ucwords($employee->religion) }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.gender')</h6>
                                            <span class="text-secondary">{{ ucwords($employee->gender) }}</span>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row row-cols-12 row-cols-md-1 row-cols-lg-12 row-cols-xl-12">

                                    <ul class="nav nav-tabs nav-primary" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#profile" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">

                                                    <div class="tab-title">@lang('english.profile')</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#attendance" role="tab" aria-selected="true">
                                                <div class="d-flex align-items-center">

                                                    <div class="tab-title">@lang('english.attendance')</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#payroll" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-title">@lang('english.pay_roll')</div>
                                                </div>
                                            </a>
                                        </li>
                                        @can('employee_document.view')

                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#documents" role="tab" aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-title">@lang('english.documents')</div>
                                                </div>
                                            </a>
                                        </li>
                                        @endcan

                                    </ul>
                                    <div class="tab-content py-">
                                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                            @include('hrm.employee.partials.profile_details')
                                        </div>
                                        <div class="tab-pane fade show" id="attendance" role="tabpanel">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title text-primary">@lang('english.attendance')</h4>
                                                    <hr>

                                                    <div id="my-calendar"></div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade " id="payroll" role="tabpanel">
                                            <div class="row">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-md-4 p-1">
                                                                {!! Form::label('ledger_date_range', __('english.date_range') . ':') !!}
                                                                {!! Form::text('ledger_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'class' => 'form-control']); !!}
                                                            </div>
                                                            <div class="col-md-4 p-4 ">
                                                                <button type="button" class="btn btn-primary no-print pull-right" id="print">
                                                                    <i class="fa fa-print"></i> @lang('english.print')</button>
                                                                <button data-href="{{action('Hrm\HrmEmployeeController@getLedger')}}?employee_id={{$employee->id}}&action=pdf" class="btn btn-default btn-xs" id="print_ledger_pdf"><i class="fas fa-file-pdf"></i></button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box box-solid print_area">

                                                    <div class="box-header print_section">
                                                        @include('common.logo')

                                                    </div>
                                                    <div id="employee_ledger_div">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        @can('employee_document.view')

                                        <div class="tab-pane fade show " id="documents" role="tabpanel">
                                            @include('hrm.employee.partials.documents')
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade document_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    @endsection

    @section('javascript')
    <script src="{{ asset('/js/calender/zabuto_calendar.min.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('/js/document.js?v=' . $asset_v) }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

    $("#my-calendar").zabuto_calendar({
                legend: [{
                        type: "block"
                        , label: "@lang('english.absent')"
                        , classname: 'icon-badge bg-danger'
                    }
                    , {
                        type: "block"
                        , label: "@lang('english.present')"
                        , classname: 'icon-badge bg-success'
                    }
                    , {
                        type: "block"
                        , label: "@lang('english.late')"
                        , classname: 'icon-badge  bg-warning'
                    }
                    , {
                        type: "block"
                        , label: "@lang('english.half_day')"
                        , classname: 'icon-badge bg-dark'
                    }
                    , {
                        type: "block"
                        , label: "@lang('english.holiday')"
                        , classname: 'icon-badge holiday'
                    }
                    , {
                        type: "block"
                        , label: "@lang('english.weekend')"
                        , classname: 'icon-badge weekend'
                    }
                    , {
                        type: "block"
                        , label: "@lang('english.leave')"
                        , classname: 'icon-badge bg-info'
                    }
                , ]
                , ajax: {
                    url: "/get_employee_attendance?employee_id={{$employee->id}}&grade=1"
                    , modal: true
                }
            });

            $('#ledger_date_range').daterangepicker(
                dateRangeSettings
                , function(start, end) {
                    $('#ledger_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                }
            );
            $('#ledger_date_range').change(function() {
                get_contact_ledger();
            });
            get_contact_ledger();







            function get_contact_ledger() {

                var start_date = '';
                var end_date = '';
                if ($('#ledger_date_range').val()) {
                    start_date = $('#ledger_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    end_date = $('#ledger_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
                $.ajax({
                    url: '/employee/ledger?employee_id={{$employee->id}}&start_date=' + start_date + '&end_date=' + end_date
                    , dataType: 'html'
                    , success: function(result) {
                        $('#employee_ledger_div')
                            .html(result);
                        __currency_convert_recursively($('#employee_ledger_div'));

                        $('#ledger_table').DataTable({
                            searching: false
                            , ordering: false
                            , paging: false
                            , dom: 't'
                        });
                    }
                , });
            }

            $(document).on('click', '#print', function() {
                $('.print_area').printThis();
            });

            $(document).on('click', '#print_ledger_pdf', function() {
                var start_date = $('#ledger_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var end_date = $('#ledger_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');

                var url = $(this).data('href') + '&start_date=' + start_date + '&end_date=' + end_date;
                window.open(url);
            });

        


        });

    </script>
    @endsection

