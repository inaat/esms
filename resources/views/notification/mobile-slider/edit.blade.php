<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('MobileSliderController@update',[$slider->id]), 'method' => 'PUT', 'id' =>'slider_edit_form' ,'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_slider')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
               
                <div class="col-md-6 p-3">
                    {!! Form::label('slider_image', __('english.slider_image') . ':') !!}
                    {!! Form::file('image', ['accept' => 'image/jpg, image/jpeg, image/png','class' => 'form-control upload_slider']) !!}
                </div>
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
