<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Hrm\HrmShiftController@postAssignUsers') , 'method' => 'post' ,'id' => 'add_employee_shift_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.assign_employee')(
	      			{{$shift->name}}
	      			@if($shift->type == 'fixed_shift')
	      			: {{@format_time($shift->start_time)}} - {{@format_time($shift->end_time)}}
	      			@endif
	      		)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            {!! Form::hidden('shift_id', $shift->id); !!}
            <div class="table-responsive">
                <table class="table table-condensed" width="100%">
                    <thead>
                        <tr>
                            <th> <input type="checkbox" id="checkAll" class="common-checkbox form-check-input mt-2" name="checkAll">
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
                                        {!! Form::text('employee_shift[' . $key . '][start_date]', !empty($employee_shifts[$key]['start_date']) ? $employee_shifts[$key]['start_date'] : null, ['class' => 'form-control form-control datepicker','placeholder' => __( 'english.start_date' ), 'id' => 'employee_shift[' . $key . '][start_date]']); !!}

                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col-md-12 p-1">
                                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                        {!! Form::text('employee_shift[' . $key . '][end_date]', !empty($employee_shifts[$key]['end_date']) ? $employee_shifts[$key]['end_date'] : null, ['class' => 'form-control form-control datepicker','placeholder' => __( 'english.end_date' ), 'id' => 'employee_shift[' . $key . '][end_date]']); !!}
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">

            <button type="submit" class="btn btn-primary">@lang( 'english.save' )</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
        </div>
    </div>

    {!! Form::close() !!}

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
