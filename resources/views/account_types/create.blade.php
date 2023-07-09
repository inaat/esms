<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('AccountTypeController@store'), 'method' => 'post', 'id' => 'account_type_form' ]) !!}

        <div class="modal-header bg-primary">
            <h4 class="modal-title">@lang( 'english.add_account_type' )</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <div class="form-group">
                {!! Form::label('name', __( 'english.name' ) . ':*') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.name' )]); !!}
            </div>

            <div class="form-group">
                {!! Form::label('parent_account_type_id', __( 'english.parent_account_type' ) . ':') !!}
                {!! Form::select('parent_account_type_id', $account_types->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => __( 'messages.please_select' )]); !!}
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

