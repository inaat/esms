 @extends("admin_layouts.app")
@section('title', __('english.roles'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.paper_maker')</div>
             <div class="ps-3">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb mb-0 p-0">
                         <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                         </li>
                         <li class="breadcrumb-item active" aria-current="page">@lang('english.paper_maker')</li>
                     </ol>
                 </nav>
             </div>
         </div>
         <!--end breadcrumb-->
         {!! Form::open(['url' => action('Curriculum\ManageSubjectController@getSubjectChapters'), 'method' => 'GET', 'onsubmit'=>'return false','id' =>'get_chapter_form' ]) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                 <hr>
                 <div class="row ">

                     <div class="col-md-4 p-2 ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                     <div class="col-md-4 p-2">
                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                         {!! Form::select('class_id',[],null, ['class' => 'form-select select2 global-classes ','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                     </div>

                     <div class="col-md-4 p-2">
                         {!! Form::label('subjects', __('english.subjects') . ':*') !!}
                         {!! Form::select('subject_id', [], null, ['class' => 'form-select select2 global-subjects', 'required','id' => 'subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                     </div>
                 </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                     <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" id="load_chapter" type="submit">
                             <i class="fas fa-filter"></i>@lang('english.load_chapter')</button></div>
                 </div>
             </div>
         </div>


         {{ Form::close() }}
         <div class="card">

             <div class="card-body">
                 <div class="row ">

                     <div class="col-md-12" style="margin-top:30px">
                         <div class="tab-content" id="PaperCardGrid">
                             <fieldset class="fieldSet">
                                 <legend class="lengend">
                                     <h4>
                                         <strong>@lang('english.select_chapters') <b style="color:red">(@lang('english.compulsory'))</b></strong><input type="checkbox" class="form-check-input mt-2 chapterName" name="allchap" id="allchap" style="  margin: revert;"> <strong style="border-bottom:4px  solid red;font-size: medium;">
                                            @lang('english.select_all_chapters')
                                         </strong>
                                     </h4>
                                 </legend>


                             </fieldset>
                              {!! Form::open(['url' => action('Curriculum\ManageSubjectController@getSubjectChaptersQuestions'), 'method' => 'POST','id' =>'post_chapter_question_form' ]) !!}

                             <div class="row" id="ChapTopicGrid">

                             </div>
                                      {{ Form::close() }}

                         </div>
                     </div>
                 </div>
             </div>
         </div>

     </div>
 </div>

 @endsection

 @section('javascript')
        <script src="{{ asset('js/paper.js?v=' . $asset_v) }}"></script>


 @endsection

