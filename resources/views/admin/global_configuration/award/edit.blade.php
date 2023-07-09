
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('\App\Http\Controllers\AwardController@update', [$award->id]), 'method' => 'PUT', 'id' => 'award_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_award')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
        <div class="row">
                <div class="col-md-12 p-3">
        {!! Form::label('award', __( 'english.award_title' ) . ':*') !!}
          {!! Form::text('title', $award->title, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.award_title' ) ]); !!}
            </div>

            <div class="col-md-12 p-3">
                {!! Form::label('award', __( 'english.award_description' ) . ':*') !!}
                  {!! Form::textarea('description', $award->description, ['class' => 'form-control' ]); !!}
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
