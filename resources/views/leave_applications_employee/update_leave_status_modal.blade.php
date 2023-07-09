<div class="modal fade" id="update_leave_status_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

		{!! Form::open(['url' => action('LeaveApplicationEmployeeController@updateStatus'), 'method' => 'post', 'id' => 'update_leave_status_form' ]) !!}


           <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_status')
                </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

		<div class="modal-body">
			<div class="form-group">
				{!! Form::label('status', __('english.status') . ':*') !!} 
				{!! Form::select('status', __('english.leave_status'), null, ['class' => 'form-control', 'placeholder' => __('english.please_select'), 'required']); !!}

				{!! Form::hidden('leave_id', null, ['id' => 'leave_id']); !!}
			</div>
		</div>

		<div class="modal-footer">
			
            <button type="submit" class="btn btn-primary">@lang( 'english.update' )</button>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>

		</div>

		{!! Form::close() !!}

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>