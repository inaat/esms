<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

  		{!! Form::open(['url' => action('Curriculum\ClassTimeTableController@store') , 'method' => 'post' , 'id' => 'add_time_table_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.assign_new_period')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row ">
               <div class="col-md-4 p-2 ">
                    {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                    {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                    {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                    {!! Form::select('class_id',[],null, ['class' => 'form-select  select2 global-classes ','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                {!! Form::label('subjects', __('english.subjects') . ':') !!}
                {!! Form::select('subject_id', [], null, ['class' => 'form-select select2 global-section-subjects', 'id' => 'global-section-subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                {!! Form::label('periods', __('english.periods') . ':*') !!}
                {!! Form::select('period_id', [], null, ['class' => 'form-select select2 global-periods','required', 'id' => 'periods', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="col-md-4 p-2">
                    {!! Form::label('periods', __('english.other') . ':*') !!}
                    {!! Form::select('other',__('english.other_time_table'),null, ['class' => 'form-select select2 ', 'id' => 'periods', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                </div>
                <div class="clear-fix"></div>

                <div class="col-md-4 p-2">
                    {!! Form::label('multi_subject_ids', __('english.multi_subject_ids') . ':') !!} 

                    {!! Form::select('multi_subject_ids[]', [],null, ['class' => 'form-control select2', 'multiple', 'id' => 'multi_subject_ids']); !!}                    
                </div>         
                <div class="col-md-6 p-2">
                    {!! Form::label('note', __('english.note') . ':') !!}
                    {!! Form::textarea('note',null, ['class' => 'form-control','rows=4']); !!}
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
<script type="text/javascript">
    $(document).ready(function() {
         $("form#add_time_table_form").validate({
        rules: {
            period_id: {
                required: true,
            },
        },
    });
      
       
    });

</script>