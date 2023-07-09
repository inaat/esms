
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('ExpenseCategoryController@update', [$expense_category->id]), 'method' => 'PUT', 'id' => 'expense_category_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.edit_category')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 p-3">
                    {!! Form::label('name', __('english.category_name' ) . ':*') !!}
                    {!! Form::text('name', $expense_category->name, ['class' => 'form-control', 'required', 'placeholder' => __('english.category_name' )]); !!}
                </div>

                <div class="col-md-12 p-3">
                    {!! Form::label('code', __('english.category_code' ) . ':') !!}
                    {!! Form::text('code', $expense_category->code, ['class' => 'form-control', 'placeholder' => __('english.category_code' )]); !!}
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

