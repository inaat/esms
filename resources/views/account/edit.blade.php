

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('AccountController@update',$account->id), 'method' => 'PUT', 'id' => 'edit_payment_account_form' ]) !!}

        <div class="modal-header bg-primary">
            <h4 class="modal-title">@lang( 'account.edit_account' )</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <div class="modal-body">
            <div class="form-group">
                {!! Form::label('name', __( 'english.name' ) .":*") !!}
                {!! Form::text('name', $account->name, ['class' => 'form-control', 'required','placeholder' => __( 'english.name' ) ]); !!}
            </div>

             <div class="form-group">
                {!! Form::label('account_number', __( 'account.account_number' ) .":*") !!}
                {!! Form::text('account_number', $account->account_number, ['class' => 'form-control', 'required','placeholder' => __( 'account.account_number' ) ]); !!}
            </div>

            <div class="form-group">
                {!! Form::label('account_type_id', __( 'account.account_type' ) .":") !!}
                <select name="account_type_id" class="form-control select2">
                    <option>@lang('english.please_select')</option>
                    @foreach($account_types as $account_type)
                        <optgroup label="{{$account_type->name}}">
                            <option value="{{$account_type->id}}" @if($account->account_type_id == $account_type->id) selected @endif >{{$account_type->name}}</option>
                            @foreach($account_type->sub_types as $sub_type)
                                <option value="{{$sub_type->id}}" @if($account->account_type_id == $sub_type->id) selected @endif >{{$sub_type->name}}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                {!! Form::label('note', __( 'english.note' )) !!}
                {!! Form::textarea('note', $account->note, ['class' => 'form-control', 'placeholder' => __( 'english.note' ), 'rows' => 4]); !!}
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