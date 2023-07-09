@extends('admin_layouts.app')
@section('title', __('english.syllabus'))

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.all_your_class_syllabus')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.syllabus')</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->
        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                {!! Form::open(['url' => action('Curriculum\SyllabusMangerController@printSyllabus'), 'method' => 'post', 'id' =>'syllabus_print_form' ]) !!}

                <div class="row ">

                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','id'=>'filter_campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                     <div class="col-md-4 p-2 ">
                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                         {!! Form::select('class_ids[]', [], null, ['class' => 'form-select select2 global-classes', 'multiple','style' => 'width:100%']) !!}
                     </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('subjects', __('english.subjects') . ':') !!}
                        {!! Form::select('subject_id', [], null, ['class' => 'form-select select2 global-subjects', 'id'=>'filter_subject_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>

                    <div class="col-md-4 p-2 ">
                        {!! Form::label('chapter_id', __('english.chapters') . ':*') !!}
                        {!! Form::select('chapter_id',[], null, ['class' => 'form-select select2 global-chapter', 'id' => 'filter_chapter_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2 ">
                        {!! Form::label('exam_term', __('english.exam_term') . ':*') !!}
                        {!! Form::select('exam_term_id',$term, null, ['class' => 'form-select select2', 'required','id' => 'filter_exam_term_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
          <div class="d-lg-flex align-items-center mt-4 gap-3">
                             @can('syllabus.view')
                              <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                      <i class="bx bx-printer"></i>@lang('english.print')</button></div>
                                      @endcan
                          </div>
                </div>
           
                {!! Form::close() !!}

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.class_subject_syllabus_list')
                    <small class="text-info font-13"></small>
                </h5>


                <div class="d-lg-flex align-items-center mb-4 gap-3">
@can('syllabus.create')
                    <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('Curriculum\SyllabusMangerController@create') }}" data-container=".syllabus_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_syllabus')</button></div>
 @endcan
                </div>


                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="syllabus_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.class_name')</th>
                                <th>@lang('english.exam_term')</th>
                                <th>@lang('english.class_subject_name')</th>
                                <th>@lang('english.chapter_name')</th>
                                <th>@lang('english.description')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade syllabus_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection


@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('change', '#filter_campus_id,#filter_class_id,#filter_exam_term_id,#filter_subject_id,#filter_chapter_id', function() {
            syllabus_table.ajax.reload();
        });
        //syllabus table
        var syllabus_table = $("#syllabus_table").DataTable({
            processing: true
            , serverSide: true
            , "ajax": {
                "url": "/syllabus"
                , "data": function(d) {
                    if ($('#filter_campus_id').length) {
                        d.campus_id = $('#filter_campus_id').val();
                    }
                    if ($('#filter_class_id').length) {
                        d.class_id = $('#filter_class_id').val();
                    }
                    if ($('#filter_subject_id').length) {
                        d.subject_id = $('#filter_subject_id').val();
                    }
                    if ($('#filter_chapter_id').length) {
                        d.chapter_id = $('#filter_chapter_id').val();
                    }
                    if ($('#filter_exam_term_id').length) {
                        d.exam_term_id = $('#filter_exam_term_id').val();
                    }

                    d = __datatable_ajax_callback(d);
                }
            }
            , columns: [{
                        data: "action"
                        , name: "action"
                    }, {

                        data: "campus_name"
                        , name: "campus_name"
                    }
                    , {

                        data: "class_name"
                        , name: "class_name"
                    }
                    , {
                        data: "exam_term"
                        , name: "exam_term"
                    }
                    , {
                        data: "subject_name"
                        , name: "subject_name"
                    }
                    , {
                        data: "chapter_name"
                        , name: "chapter_name"
                    }
                    , {
                        data: "description"
                        , name: "description"
                    }
                , ]

            , fnDrawCallback: function(oSettings) {
                __currency_convert_recursively($('#syllabus_table'));
            }
        });

        $(document).on("submit", "form#syllabus_add_form", function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: "POST"
                , url: $(this).attr("action")
                , dataType: "json"
                , data: data
                , beforeSend: function(xhr) {
                    __disable_submit_button(form.find('button[type="submit"]'));
                }
                , success: function(result) {
                    if (result.success == true) {
                        {{-- $("div.syllabus_modal").modal("hide"); --}}
                        toastr.success(result.msg);
                         __enable_submit_button(form.find('button[type="submit"]'));
                        syllabus_table.ajax.reload();
                    } else {
                        __enable_submit_button(form.find('button[type="submit"]'));
                        toastr.error(result.msg);
                    }
                }
            , });
        });


        $(document).on("click", "a.edit_syllabus_button", function() {
            $("div.syllabus_modal").load($(this).data("href"), function() {
                $(this).modal("show");

                $("form#syllabus_edit_form").submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var data = form.serialize();

                    $.ajax({
                        method: "POST"
                        , url: $(this).attr("action")
                        , dataType: "json"
                        , data: data
                        , beforeSend: function(xhr) {
                            __disable_submit_button(
                                form.find('button[type="submit"]')
                            );
                        }
                        , success: function(result) {
                            if (result.success == true) {
                                $("div.syllabus_modal").modal("hide");
                                toastr.success(result.msg);
                                syllabus_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    , });
                });
            });
        });

        $(document).on("click", "a.delete_syllabus_button", function() {
            swal({
                title: LANG.sure
                , text: LANG.confirm_delete_syllabus
                , icon: "warning"
                , buttons: true
                , dangerMode: true
            , }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data("href");
                    var data = $(this).serialize();

                    $.ajax({
                        method: "DELETE"
                        , url: href
                        , dataType: "json"
                        , data: data
                        , success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                syllabus_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    , });
                }
            });
        });

        $(document).on('change', '#subject_input', function() {
            var subject_input = $(this).val();
            if (subject_input === 'ur') {

                $("#subject_name").addClass("urdu_input");
            }
        });
    });

</script>
@endsection
