<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\ManageSubjectController@store'), 'method' => 'post', 'id' =>'chapter_add_form', 'files' => true]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title text-uppercase text-white" id="exampleModalLabel">@lang('english.add_new_chapter')For<small>(@lang('english.subject_detail') - {{ $class_subject->name }} of class {{ $class_subject->classes->title }})</small></h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            {!! Form::hidden('subject_id', $class_subject->id,[ 'id' => 'subject_id', ]) !!}
                         @php
                             $urdu_input=($class_subject->subject_input == 'ur') ? 'urdu_input urdu' : '';
                         @endphp
        <div class="modal-body">
            <div class="row m-0">
 
                <div class="col-md-12 p-2 ">
                    {!! Form::label('chapter_name', __('english.chapter_name') . ':*') !!}
                    {!! Form::text('chapter_name', null, ['class' => 'form-control ' . ' '. $urdu_input , 'required','placeholder' => __('english.chapter_name')]) !!}
                </div>
                <div class="clear-fix"></div>
                    <div class="col-md-6 p-3">
                        {!! Form::label('chapter_icon', __('english.chapter_icon') . ':') !!}
                        {!! Form::file('chapter_icon', ['accept' => 'image/jpg, image/jpeg, image/png','class' => 'form-control ']); !!} 
                    </div>
                   
                    <h4 class="mb-3">{{ __('files') }}</h4>

                    <div class="form-group">

                        <div class="row file_type_div" id="file_type_div">
                            <div class="form-group col-md-2">
                                <label>{{ __('type') }} </label>
                                <select id="file_type" aria-label="Filter select" name="file[0][type]" class="form-select file_type">
                                    <option value="">--{{ __('select') }}--</option>
                                    <option value="file_upload">{{ __('file_upload') }}</option>
                                    <option value="youtube_link">{{ __('youtube_link') }}</option>
                                    <option value="video_upload">{{ __('video_upload') }}</option>
                                    {{-- <option value="other_link">{{ __('other_link') }}</option> --}}
                                </select>
                            </div>
                            <div class="form-group col-md-3" id="file_name_div" style="display: none">
                                <label>{{ __('file_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="file[0][name]" class="file_name form-control"
                                    placeholder="{{ __('file_name') }}" >
                            </div>
                            <div class="form-group col-md-3" id="file_thumbnail_div" style="display: none">
                                <label>{{ __('thumbnail') }} <span class="text-danger">*</span></label>
                                <input type="file" name="file[0][thumbnail]" class="file_thumbnail form-control"
                                    >
                            </div>
                            <div class="form-group col-md-3" id="file_div" style="display: none">
                                <label>{{ __('file_upload') }} <span class="text-danger">*</span></label>
                                <input type="file" name="file[0][file]" class="file form-control" placeholder=""
                                    >
                            </div>
                            <div class="form-group col-md-3" id="file_link_div" style="display: none">
                                <label>{{ __('link') }} <span class="text-danger">*</span></label>
                                <input type="text" name="file[0][link]" class="file_link form-control"
                                    placeholder="{{ __('link') }}" >
                            </div>

                            <div class="form-group col-md-1 col-md-1 pl-0 mt-4">
                                <button type="button" class="btn btn-inverse-success btn-icon add-lesson-file">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 extra-files"></div>
                    </div>
                    
                {{-- <div class="row">
                    <label class="col-sm-3 control-label">@lang('english.add_video_link'):*</label>
                    <div class="col-sm-7">
                       {!! Form::text('video_link[]', null, ['class' => 'form-control']); !!}
                    </div>
                    <div class="col-sm-2">
                      <button type="button" class="btn btn-primary" id="add_video_link">+</button>
                    </div>
                  </div>
                  <div id="video_link"></div> --}}
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
