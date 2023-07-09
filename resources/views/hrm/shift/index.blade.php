@extends("admin_layouts.app")
@section('title', __('english.shift'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.all_your_shifts')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.shift')</li>
                    </ol>
                </nav>
            </div>
            {{-- <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">@lang('english.settings')</button>
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
                </div> --}}
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.shift_list')
                    <small class="text-info font-13"></small>
                </h5>

                 @can('shift.create')
                    
                
                <div class="d-lg-flex align-items-center mb-4 gap-3">

                    <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('Hrm\HrmShiftController@create') }}" data-container=".shift_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_shift')</button></div>

                </div>
 @endcan

                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="shift_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang( 'hrm.name' )</th>
                                <th>@lang( 'hrm.shift_type' )</th>
                                <th>@lang( 'hrm.start_time' )</th>
                                <th>@lang( 'hrm.end_time' )</th>
                                <th>@lang( 'hrm.holiday' )</th>
                                <th>@lang( 'hrm.action' )</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade shift_modal contains_select2" id="shift_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade contains_select2 edit_shift_modal" id="edit_shift_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade contains_select2 employee_shift_modal" id="employee_shift_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection


@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {

        //shift table
        var shift_table = $("#shift_table").DataTable({
            processing: true
            , serverSide: true
            , ajax: "/hrm-shift"
            , columnDefs: [{
                targets: 4
                , orderable: false
                , searchable: false
            , }, ]
            , columns: [{
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'type'
                    , name: 'type'
                }
                , {
                    data: 'start_time'
                    , name: 'start_time'
                }
                , {
                    data: 'end_time'
                    , name: 'end_time'
                }
                , {
                    data: 'holidays'
                    , name: 'holidays'
                }
                , {
                    data: 'action'
                    , name: 'action'
                }
            , ]
        , });
        $('#shift_modal,#edit_shift_modal').on('shown.bs.modal', function(e) {
            var $p = $(this);
            $('#shift_modal .select2').select2({
                dropdownParent: $p
            });
            $('#start_timepicker,#end_timepicker').datetimepicker({
                format: moment_time_format
                , ignoreReadonly: true
            , });
            $('#shift_modal .select2, #edit_shift_modal .select2').select2();

            if ($('select#shift_type').val() == 'fixed_shift') {
                $('div.time_div').show();
            } else if ($('select#shift_type').val() == 'flexible_shift') {
                $('div.time_div').hide();
            }

            $('select#shift_type').change(function() {
                var shift_type = $(this).val();
                if (shift_type == 'fixed_shift') {
                    $('div.time_div').fadeIn();
                } else if (shift_type == 'flexible_shift') {
                    $('div.time_div').fadeOut();
                }
            });
        });

        $(document).on("submit", "form#add_shift_form", function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
            $.ajax({
                method: "POST"
                , url: $(this).attr("action")
                , dataType: "json"
                , data: data
                , beforeSend: function(xhr) {
                    __enable_submit_button(form.find('button[type="submit"]'));
                }
                , success: function(result) {
                    if (result.success == true) {
                        if ($('div#edit_shift_modal').hasClass('edit_shift_modal')) {
                            $('div#edit_shift_modal').modal("hide");
                        } else if ($('div#shift_modal').hasClass('shift_modal')) {
                            $('div#shift_modal').modal('hide');
                        }
                        toastr.success(result.msg);
                        shift_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }

                }
            , });
        });
        $('#employee_shift_modal').on('shown.bs.modal', function(e) {
             $('#employee_shift_modal').find('.datepicker').each(function() {
            $(this).datepicker({
                autoclose: true,
                
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
        });

  $(document).on('submit', 'form#add_employee_shift_form', function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: $(this).attr('method'),
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function(result) {
                if (result.success == true) {
                    $('div#employee_shift_modal').modal('hide');
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
                $('form#add_employee_shift_form').find('button[type="submit"]').attr('disabled', false);
            },
        });
    });

    });

</script>
@endsection
 {{-- $('#datetimepicker').datetimepicker({
                             format: moment_date_format + ' ' + moment_time_format,
                             ignoreReadonly: true,
                });
                
                 $('#start_timepicker,.end_timepicker').datetimepicker({
                format: moment_date_format
                , ignoreReadonly: true
            , });
                
                 --}}