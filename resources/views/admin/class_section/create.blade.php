<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('\App\Http\Controllers\ClassSectionController@store'), 'method' => 'post', 'id' =>'class_section_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_new_section')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 p-3">
                    {!! Form::label('english.section_name', __('english.section_name') . ':*', ['classs' => 'form-lable']) !!}
                    {!! Form::text('section_name', null, ['class' => 'form-control', 'required', 'placeholder' => __('english.section_name')]) !!}

                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id',$campuses,null, ['class' => 'form-select  select2 global-campuses ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="clearfix"></div>

                <div class="col-md-6 p-3">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id',[],null, ['class' => 'form-select  select2 global-classes ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-6 p-3">
                    {!! Form::label('english.whatsapp_group_name', __('english.whatsapp_group_name') . ':*') !!}
                    {!! Form::text('whatsapp_group_name', null, ['class' => 'form-control', 'placeholder' => __('english.whatsapp_group_name')]) !!}
                </div>
                <div class="col-md-6 p-2">
                    {!! Form::label('teacher', __('english.teachers') . ':*') !!}
                    {!! Form::select('teacher_id',$teachers,null, ['class' => 'form-select select2 ','required', 'id' => '', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
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

