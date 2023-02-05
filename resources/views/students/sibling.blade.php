<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">


        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.add_sibling')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 p-1">
                    {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id',$campuses,null, ['class' => 'form-select select2 campuses ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'campus_id']) !!}
                </div>
                <div class="clearfix"></div>

                <div class="col-md-12 p-1">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id',[],null, ['class' => 'form-select select2 classes','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'class_ids']) !!}
                </div>
                <div class="clearfix"></div>

                <div class="col-md-12 p-1">
                    {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                    {!! Form::select('class_section_id',[],null, ['class' => 'form-select select2 class_sections','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-12 p-1">
                    {!! Form::label('english.students', __('english.students') . ':*') !!}
                    {!! Form::select('sibiling_student_id',[],null, ['class' => 'form-select select2 sibiling_student_id','id'=>'sibiling_student_id','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                                 <div class="sibling_msg">
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>


            <div class="modal-footer">




                <button type="button]" class="btn btn-primary add_sibling">@lang( 'english.add_sibling' )</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
            </div>
        </div>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->



