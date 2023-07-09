<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('\App\Http\Controllers\DiscountController@store'), 'method' => 'post', 'id' =>'discount_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('discount.add_new_discount')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 p-3">
                    {!! Form::label('discount_name', __('discount.discount_name') . ':*', ['classs' => 'form-lable']) !!}
                    {!! Form::text('discount_name', null, ['class' => 'form-control', 'required', 'placeholder' => __('discount.discount_name')]) !!}

                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id',$campuses,null, ['class' => 'form-select  select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>

                <div class="clearfix"></div>
                <div class="col-md-6 p-3">
                    {!! Form::label('discount_type', __('discount.discount_type') . ':*') !!}
                    {!! Form::select('discount_type', ['fixed' => __('english.fixed'), 'percentage' => __('english.percentage')], null, ['style' => 'width:100%', 'placeholder' => __('english.please_select'), 'class' => 'form-select  select2', 'required']); !!}

                </div>

                <div class="col-md-6 p-3">
                    {!! Form::label('discount_amount', __( 'discount.discount_amount' ) . ':*') !!}
                    {!! Form::text('discount_amount', null, ['class' => 'form-control input_number', 'required', 'placeholder' => __( 'discount.discount_amount' ) ]); !!}

                </div>


                <div class="clearfix"></div>
            </div>


            <div class="modal-footer">




                <button type="submit" class="btn btn-primary">@lang( 'english.save' )</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
            </div>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

