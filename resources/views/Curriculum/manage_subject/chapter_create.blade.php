<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action('Curriculum\ManageSubjectController@store'), 'method' => 'post', 'id' =>'chapter_add_form' ]) !!}

        <div class="modal-header bg-primary">
            <h5 class="modal-title text-uppercase" id="exampleModalLabel">@lang('english.add_new_chapter')For<small>(@lang('english.subject_detail') - {{ $class_subject->name }} of class {{ $class_subject->classes->title }})</small></h5>

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
