<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('\App\Http\Controllers\ProvinceController@store'), 'method' => 'post', 'id' =>'province_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_province')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">

                <div class="col-md-6 p-3">
                   
                    {!! Form::label('english.countries', __('english.countries') . ':*') !!}
                    {!! Form::select('country_id',$countries,null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}

                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('english.province_name', __('english.province_name') . ':*') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('english.province_name')]) !!}
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
