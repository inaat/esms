<div class="modal fade" id="update_student_status_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

		{!! Form::open(['url' => action('StudentController@updateStatus'), 'method' => 'post', 'id' => 'update_student_status_form' ]) !!}


           <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.update_status')
                </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

		<div class="modal-body">
			<div class="form-group">
				{!! Form::label('status', __('english.student_status') . ':*') !!} 
				{!! Form::select('status', __('english.std_status'), null, ['class' => 'form-control', 'placeholder' => __('english.please_select'), 'required']); !!}

				{!! Form::hidden('student_id', null, ['id' => 'student_id']); !!}
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