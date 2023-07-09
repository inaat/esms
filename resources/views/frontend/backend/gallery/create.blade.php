<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Frontend\FrontGalleryController@store'), 'method' => 'post', 'id' =>'gallery_add_form' ,'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_album')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 p-3">
                    {!! Form::label('name', __('english.gallery_title' ) . ':*') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'required', 'placeholder' => __('english.gallery_title' )]); !!}
                </div>
                <div class="col-md-12 p-3">
                    {!! Form::label('description', __('english.description' ) . ':') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'id'=>'description','placeholder' => __('english.description' )]); !!}
                </div>
                <div class="col-md-12 p-3">
                    {!! Form::label('thumb_image', __('english.thumb_image') . ':') !!}
                    {!! Form::file('thumb_image', ['accept' => 'image/jpg, image/jpeg, image/png','class' => 'form-control upload_thumb_image']); !!} </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-check-label" for="flexSwitchCheckChecked">@lang( 'english.show_website' )</label>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="status" type="checkbox" id="flexSwitchCheckChecked" checked="1">
                    </div>
                </div>
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

