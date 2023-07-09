@extends("admin_layouts.app")
@section('title', __('english.roles'))
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

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.shift_list')
                    <small class="text-info font-13"></small>
                </h5>


                <div class="d-lg-flex align-items-center mb-4 gap-3">

                    <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('Hrm\HrmShiftController@create') }}" data-container=".shift_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_shift')</button></div>

                </div>


                <hr>

                <div class="table-responsive">
                    {!! Form::hidden('shift_id', $shift->id); !!}
                    <table class="table table-condensed" width="100%" id="user_shift_modal">
                        <thead>
                            <tr>
                                   <th> <input type="checkbox" id="checkAll"
                                                             class="common-checkbox form-check-input mt-2" name="checkAll">
                                                         <label for="checkAll">@lang('english.all')</label>
                                  </th>
                                <th>@lang('english.employee')</th>
                                <th>@lang('english.start_date')</th>
                                <th>@lang('english.end_date')</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $key => $value)
                            <tr>
                                <td>{!! Form::checkbox('employee_shift[' . $key . '][is_added]', 1, array_key_exists($key, $employee_shifts), ['class' => 'common-checkbox form-check-input mt-2','id' => 'employee_check_' . $key ]); !!}</td>
                                <td>{{$value}}</td>
                                <td>
                                    <div class="col-md-12 p-1 ">
                                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar "></i></span>
                                            {!! Form::text('employee_shift[' . $key . '][start_date]', !empty($employee_shifts[$key]['start_date']) ? $employee_shifts[$key]['start_date'] : null, ['class' => 'form-control form-control datepicker','placeholder' => __( 'english.start_date' ),  'id' => 'employee_shift[' . $key . '][start_date]']); !!}

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-12 p-1" >
                                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                            {!! Form::text('employee_shift[' . $key . '][end_date]', !empty($employee_shifts[$key]['end_date']) ? $employee_shifts[$key]['end_date'] : null, ['class' => 'form-control form-control datepicker','placeholder' => __( 'english.end_date' ),  'id' => 'employee_shift[' . $key . '][end_date]']); !!}
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
@endsection


@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {

        $('#user_shift_modal').find('.datepicker').each(function() {
            $(this).datepicker({
                startDate: '-3d',
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

  $(document).on('submit', 'form#add_user_shift_form', function(e) {
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
                    $('div#user_shift_modal').modal('hide');
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
                $('form#add_user_shift_form').find('button[type="submit"]').attr('disabled', false);
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
