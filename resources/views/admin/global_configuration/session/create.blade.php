<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('\App\Http\Controllers\SessionController@store'), 'method' => 'post', 'id' =>'session_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.register_new_session')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
        <p class="text-muted">@lang('english.register_as_many_sessions_as_you_need._please_provide_required_information_to_proceed_next...')</p>
            <div class="form-group">
            </div>
              <div class="row">
                <div class="col-md-6 p-3">
                    {!! Form::label('session', __( 'english.session_information' ) . ':') !!}
                    {!! Form::text('title', null, ['class' => 'form-control title mask', 'required','min'=>8,'placeholder' => __( 'english.session_title' ) ]); !!}

                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('prefix', __('english.prefix') . ':*') !!}
                    {!! Form::text('prefix', null, ['class' => 'form-control', 'required', 'placeholder' => __('english.prefix')]) !!}
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
