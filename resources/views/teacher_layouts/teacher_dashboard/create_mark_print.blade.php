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
                           {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 teacher-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                       </div>
                    <div class="col-md-4 p-2">
                           {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                           {!! Form::select('class_id',[],null, ['class' => 'form-select select2 teacher-classes ','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                       </div>
                           <div class="col-md-4 p-2">
                           {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                           {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 teacher-class_sections', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                       </div>
                          <div class="col-md-4 p-2">
                                {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                {!! Form::select('session_id',$sessions,null, ['class' => 'form-select select2 teacher-exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                        </div>
                         <div class="col-md-4 p-2 ">
                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                        {!! Form::select('exam_create_id',[],null, ['class' => 'form-select select2 teacher-exam_term_id', 'style' => 'width:100%','required',  'placeholder' => __('english.please_select')]) !!}
                    </div>
                   
                       <div class="col-md-4 p-2">
                           {!! Form::label('subjects', __('english.subjects') . ':') !!}
                           {!! Form::select('subject_id', [], null, ['class' => 'form-select select2 teacher-section-subjects', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
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

$(document).on('change', '.teacher-campuses', function() {
    var campus_id = $(this).closest(".row").find(".teacher-campuses").val();
      $.ajax({
        method: "GET",
        url: "/get_teacher_campus_classes",
        dataType: "html",
        data: {
            campus_id: campus_id,
        },
        success: function (result) {
            if (result) {
                $(".teacher-classes").html(result);
            }
        },
    });
  
});

$(document).on('change', '.teacher-classes', function() {
    var class_id = $(this).closest(".row").find(".teacher-classes").val();
        var campus_id = $(this).closest(".row").find(".teacher-campuses").val();

      $.ajax({
        method: "GET",
        url: "/get_teacher_class_section",
        dataType: "html",
        data: {
            class_id: class_id,
            campus_id: campus_id,

        },
        success: function (result) {
            if (result) {
                $(".teacher-class_sections").html(result);
            }
        },
    });
});
$(document).on('change', '.teacher-exam-session', function() {
      var campus_id = $(this).closest(".row").find(".teacher-campuses").val();
    var session_id = $(this).closest(".row").find(".teacher-exam-session").val();
    $.ajax({
        method: "GET",
        url: "/exam/get_term",
        dataType: "html",
        data: {
            campus_id: campus_id,
            session_id: session_id
        },
        success: function (result) {
            if (result) {
                $(".teacher-exam_term_id").html(result);
            }
        },
    });
});
$(document).on('change', '.teacher-class_sections', function() {
     var class_id = $(".teacher-classes").val();
    var campus_id = $(".teacher-campuses").val();
    var class_section_id = $(".teacher-class_sections").val();

    $.ajax({
        method: "GET",
        url: "/get-teacher-subjects",
        dataType: "html",
        data: {
             class_id: class_id,
             class_section_id:class_section_id,
            campus_id: campus_id,
        },
        success: function (result) {
            if (result) {
                $(".teacher-section-subjects").html(result);
            }
        },
    });
});
});
     </script>
 @endsection
