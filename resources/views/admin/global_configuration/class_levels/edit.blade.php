
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

    {!! Form::open(['url' => action('\App\Http\Controllers\ClassLevelController@update', [$class_level->id]), 'method' => 'PUT', 'id' => 'class_level_edit_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('class_level.edit_class_level')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
        <div class="row">
                <div class="col-md-12 p-3">
        {!! Form::label('class_level', __( 'class_level.class_level_title' ) . ':*') !!}
          {!! Form::text('title', $class_level->title, ['class' => 'form-control', 'required', 'placeholder' => __( 'class_level.class_level_title' ) ]); !!}
            </div>

            <div class="col-md-12 p-3">
                {!! Form::label('class_level', __( 'class_level.class_level_description' ) . ':*') !!}
                  {!! Form::textarea('description', $class_level->description, ['class' => 'form-control' ]); !!}
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
