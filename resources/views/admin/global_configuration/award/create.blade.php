<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('\App\Http\Controllers\AwardController@store'), 'method' => 'post', 'id' =>'award_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_award')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 p-3">
                    {!! Form::label('award', __( 'english.award_title' ) . ':*') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'english.award_title' ) ]); !!}
                </div>
                <div class="col-md-12 p-3">
                    {!! Form::label('award', __( 'english.description' ) . ':*') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control' ]); !!}
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

