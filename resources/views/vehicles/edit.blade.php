<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('VehicleController@update',[$vehicle->id]), 'method' => 'PUT', 'id' =>'vehicle_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_vehicle')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 p-3">
                    {!! Form::label('name', __('english.vehicle_name') . ':*', ['classs' => 'form-lable']) !!}
                    {!! Form::text('name', $vehicle->name, ['class' => 'form-control', 'required', 'placeholder' => __('english.vehicle_name')]) !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('vehicle_number', __('english.vehicle_number') , ['classs' => 'form-lable']) !!}
                    {!! Form::text('vehicle_number',$vehicle->vehicle_number, ['class' => 'form-control',  'placeholder' => __('english.vehicle_number')]) !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('vehicle_model', __('english.vehicle_model') , ['classs' => 'form-lable']) !!}
                    {!! Form::text('vehicle_model',$vehicle->vehicle_model, ['class' => 'form-control',  'placeholder' => __('english.vehicle_model')]) !!}
                </div>
                 <div class="col-md-6 p-3">
                    {!! Form::label('employee_id', __('english.driver') . ':') !!}
                    {!! Form::select('employee_id',$employees,$vehicle->employee_id, ['class' => 'form-select select2 ','id'=>'country_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('driver_license', __('english.driver_license'), ['classs' => 'form-lable']) !!}
                    {!! Form::text('driver_license', $vehicle->driver_license, ['class' => 'form-control',  'placeholder' => __('english.driver_license')]) !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('year_made', __('english.year_made') , ['classs' => 'form-lable']) !!}
                    {!! Form::text('year_made',$vehicle->year_made, ['class' => 'form-control',  'placeholder' => __('english.year_made')]) !!}
                </div>
               

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'english.update' )</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
            </div>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

