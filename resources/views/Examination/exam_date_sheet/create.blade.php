       <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">

               {!! Form::open(['url' => action('Examination\ExamDateSheetController@store') , 'method' => 'post' , 'id' => 'add_date_sheet_form' ]) !!}

               <div class="modal-header bg-primary">
                   <h5 class="modal-title" id="exampleModalLabel">@lang('english.assign_new_date_sheet_for_subject')</h5>
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
                           {!! Form::select('class_id',[],null, ['class' => 'form-select select2 global-classes ','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                       </div>
                       {{-- <div class="col-md-4 p-2">
                           {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                           {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                       </div> --}}
                           <div class="col-md-4 p-1">
                                {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                {!! Form::select('session_id',$sessions,null, ['class' => 'form-select select2 exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                        </div>
                        <div class="col-md-4 ">
                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                        {!! Form::select('exam_create_id',[],null, ['class' => 'form-select select2 exam_term_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                       <div class="col-md-4 p-2">
                           {!! Form::label('subjects', __('english.subjects') . ':') !!}
                           {!! Form::select('subject_id', [], null, ['class' => 'form-select select2 global-subjects', 'id' => 'global-subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                       </div>

                       <div class="col-md-4 p-2">
                           {!! Form::label('type', __('english.type') . ':*') !!}
                           {!! Form::select('type',['written'=>'Written','oral'=>'Oral','written_oral'=>'Written Oral'],null, ['class' => 'form-select select2 ', 'id' => 'date_sheets', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                       </div>
                       <div class="col-md-4 p-2">
                           {!! Form::label('english.date', __('english.date') . ':*') !!}
                           <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                               {!! Form::text('date',@format_date('now'), ['class' => 'form-control date-picker date-sheet-date',  'placeholder' => __('english.date')]) !!}

                           </div>
                       </div>
                       <div class="clear-fix"></div>
                       <div class="col-md-4 p-2 time_div" id="start_timepicker" data-target-input="nearest" data-target="#start_timepicker" data-toggle="datetimepicker">
                           {!! Form::label('start_time', __('english.start_time') . ':*') !!}
                           <div class="input-group flex-nowrap input-group-append  input-group date">
                               {!! Form::text('start_time', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#start_timepicker', 'required']) !!}
                               <span class="input-group-text" id="addon-wrapping"><i class="fa fa-clock"></i></span>
                           </div>
                       </div>
                       <div class="col-md-4 p-2 time_div" id="end_timepicker" data-target-input="nearest" data-target="#end_timepicker" data-toggle="datetimepicker">
                           {!! Form::label('end_time', __('english.end_time') . ':*') !!}
                           <div class="input-group flex-nowrap input-group-append  input-group date">
                               {!! Form::text('end_time', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#end_timepicker', 'required']) !!}
                               <span class="input-group-text" id="addon-wrapping"><i class="fa fa-clock"></i></span>
                           </div>
                       </div>
                       <div class="clearfix"></div>
                       <div class="col-sm-12">
                           <div class="form-group">
                               {!! Form::label('topic', __('english.topic') . ':') !!}
                               {!! Form::textarea('topic',null, ['class' => 'form-control' ]); !!}
       
                           </div>
                       </div>
                       <div class="clearfix"></div>
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
    

