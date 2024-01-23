@extends("admin_layouts.app")
@section('title', __('english.class_section_subject_list'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.class_curriculum')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.class_section_subject_list')</li>
                        </ol>
                    </nav>
                </div>
            </div> 
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.class_section_subject_list')
                        <small class="text-info font-13"></small>
                    </h5>
                    <div class="row ">

                        <div class="col-md-4 p-2 ">
                            {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                            {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','id'=>'filter_campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                            {!! Form::select('class_id',[],null, ['class' => 'form-select select2 global-classes ','id'=>'filter_class_id','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                            {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'required','id'=>'filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                    </div>

                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        @can('assign_subject.create')
                        <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('Curriculum\AssignSubjectTeacherController@create') }}"
                                data-container=".class_subjects_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_class_subject')</button></div>
                                @endcan

                    </div>


                    <hr>

                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="class_subjects_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('english.class_subject_name')</th>
                                    <th>@lang('english.teacher')</th>
                                    <th>@lang('english.campus_name')</th>
                                    <th>@lang('english.class_name')</th>
                                    <th>@lang('english.section_name')</th>
                                    <th>@lang('english.action')</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <div class="modal fade class_subjects_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
  @endsection


@section('javascript')
    <script type="text/javascript">
   
        $(document).ready(function() {
            $(document).on('change', '#filter_campus_id,#filter_class_id,#filter_class_section_id',  function() {
          class_subjects_table.ajax.reload();
         });
            //class_subjects table
            var class_subjects_table = $("#class_subjects_table").DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
          "url": "/assign-subject",
          "data": function ( d ) {
                if ($('#filter_campus_id').length) {
                    d.campus_id = $('#filter_campus_id').val();
                }
                if ($('#filter_class_id').length) {
                    d.class_id = $('#filter_class_id').val();
                }
                if ($('#filter_class_section_id').length) {
                    d.class_section_id = $('#filter_class_section_id').val();
                }
               
                 d = __datatable_ajax_callback(d);
            }
            },
                columns: [{
                    data: "subject_name",
                    name: "subject_name"
                }, {
              
                    data: "teacher_name",
                    name: "teacher_name"
                }, 
                 {
              
                    data: "campus_name",
                    name: "campus_name"
                }, 
                 {
              
                    data: "class_name",
                    name: "class_name"
                },
                 {
              
                    data: "section_name",
                    name: "section_name"
                },
                 {
                    data: "action",
                    name: "action"
                }, ],
            });

            $(document).on("submit", "form#class_subject_add_form", function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $("div.class_subjects_modal").modal("hide");
                            toastr.success(result.msg);
                            class_subjects_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });


            $(document).on("click", "a.edit_class_subject_button", function() {
                $("div.class_subjects_modal").load($(this).data("href"), function() {
                    $(this).modal("show");

                    $("form#class_subject_edit_form").submit(function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var data = form.serialize();

                        $.ajax({
                            method: "POST",
                            url: $(this).attr("action"),
                            dataType: "json",
                            data: data,
                            beforeSend: function(xhr) {
                                __disable_submit_button(
                                    form.find('button[type="submit"]')
                                );
                            },
                            success: function(result) {
                                if (result.success == true) {
                                    $("div.class_subjects_modal").modal("hide");
                                    toastr.success(result.msg);
                                    class_subjects_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    });
                });
            });

            $(document).on("click", "a.delete_assign_subject_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_class_subject,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data("href");
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    class_subjects_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
