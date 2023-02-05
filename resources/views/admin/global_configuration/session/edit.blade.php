
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('\App\Http\Controllers\SessionController@update', [$session->id]), 'method' => 'PUT', 'id' => 'session_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_session')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">@lang('english.update')</button>
        </div>

        <div class="modal-body">
        <p class="text-muted">@lang('english.update_sessions_here._please_provide_required_information_to_proceed_next.')</p>
             <div class="row">
                <div class="col-md-6 p-3">
                    {!! Form::label('session', __( 'english.session_information' ) . ':') !!}
                    {!! Form::text('title',$session->title, ['class' => 'form-control title mask', 'required','min'=>8,'placeholder' => __( 'english.session_title' ) ]); !!}

                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('prefix', __('english.prefix') . ':*') !!}
                    {!! Form::text('prefix', $session->prefix, ['class' => 'form-control', 'required', 'placeholder' => __('english.prefix')]) !!}
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
