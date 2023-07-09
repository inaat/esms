<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Frontend\FrontSliderController@update',[$slider->id]), 'method' => 'PUT', 'id' =>'slider_edit_form' ,'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_slider')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 p-3">
                    {!! Form::label('name', __('english.slider_title' ) . ':*') !!}
                    {!! Form::text('title', $slider->title, ['class' => 'form-control', 'required', 'placeholder' => __('english.slider_title' )]); !!}
                </div>
                <div class="col-md-12 p-3">
                    {!! Form::label('description', __('english.description' ) . ':') !!}
                    {!! Form::textarea('description',$slider->description, ['class' => 'form-control', 'id'=>'description','placeholder' => __('english.description' )]); !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('btn_name', __('english.btn_name' ) . ':') !!}
                    {!! Form::text('btn_name', $slider->btn_name, ['class' => 'form-control', 'id'=>'btn_name','placeholder' => __('english.btn_name' )]); !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('btn_url', __('english.btn_url' ) . ':') !!}
                    {!! Form::text('btn_url', $slider->btn_url, ['class' => 'form-control', 'id'=>'btn_url','placeholder' => __('english.btn_url' )]); !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('slider_image', __('english.slider_image') . ':') !!}
                    {!! Form::file('slider_image', ['accept' => 'image/jpg, image/jpeg, image/png','class' => 'form-control upload_slider']); !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-check-label" for="flexSwitchCheckChecked">@lang( 'english.show_website' )</label>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="status" type="checkbox" id="flexSwitchCheckChecked" @if($slider->status=='publish') checked @endif>
                    </div>
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

