

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('Certificate\WithdrawalRegisterController@withdrawalRegisterUpdate',$withdrawal_register->id), 'method' => 'PUT', 'id' => 'withdrawal_update_form' ]) !!}

        <div class="modal-header bg-primary">
            <h4 class="modal-title">@lang( 'account.edit_withdrawal' )</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <div class="modal-body">
        <div class="row">
           <div class="col-md-6 p-3">
                    {!! Form::label('local_reg_no', __('english.local_reg_no') . ':*') !!}
                    {!! Form::text('local_reg_no',$withdrawal_register->local_reg_no, ['class' => 'form-control','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.local_reg_no')]) !!}
                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('status', __( 'english.status' ) . ':*') !!}
                    {!! Form::select('status',['C'=>'Receive Certificate','M'=>'Migrate'],$withdrawal_register->status, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.none')]) !!}

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