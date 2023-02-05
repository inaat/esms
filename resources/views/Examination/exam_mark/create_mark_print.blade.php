@extends("admin_layouts.app")
@section('title', __('english.mark_entry_print'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.mark_entry')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.mark_entry_print')</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
           {!! Form::open(['url' => action('Examination\ExamMarkController@markEnteryPrint'), 'method' => 'post', 'id' =>'print_report_form']) !!}

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
                          {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                          {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                      </div>
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
                          {!! Form::select('subject_id', [], null, ['class' => 'form-select select2 global-section-subjects', 'id' => 'global-section-subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                      </div>
                  </div>
                     <div class="d-lg-flex align-items-center mt-4 gap-3">
                        <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0"
                                type="submit">
                                <i class="fas fa-print"></i>@lang('english.print')</button></div>
                    </div>
                </div>
            </div>


           {{ Form::close() }}



        </div>
    </div>
  
@endsection

@section('javascript')

    <script type="text/javascript">
        $(document).ready(function() {



        });
    </script>
@endsection
