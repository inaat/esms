<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('\App\Http\Controllers\DistrictController@update',[$district->id]), 'method' => 'PUT', 'id' =>'district_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-name" id="exampleModalLabel">@lang('english.edit_district')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 p-3">
                    {!! Form::label('country_id', __('english.country_name') . ':*') !!}
                    {!! Form::select('country_id',$countries,$district->country_id, ['class' => 'form-select  select2 ','required','id'=>'country_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('province_id', __('english.provinces') . ':*') !!}
                    {!! Form::select('province_id',$provinces,$district->province_id, ['class' => 'form-select  select2 ','required', 'style' => 'width:100%', 'required','id' => 'provinces_ids','placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('name', __('english.district_name') . ':*', ['classs' => 'form-lable']) !!}
                    {!! Form::text('name', $district->name, ['class' => 'form-control', 'required', 'placeholder' => __('english.district_name')]) !!}

                </div>
                <div class="clearfix"></div>
            </div>


            <div class="modal-footer">




                <button type="submit" class="btn btn-primary">@lang( 'english.update' )</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
            </div>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
