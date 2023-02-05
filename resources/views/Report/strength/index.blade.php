@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.strength')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.strength')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                                             <h5 class="card-title text-primary">@lang('english.flitters')</h5>

  <div class="row align-items-center">
                    <div class="col-sm-3">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses,null,['class' => 'form-select select2 global-campuses','id'=>'filter_campus','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('english.classes', __('english.classes') . ':') !!}
                        {!! Form::select('class_id',[],null, ['class' => 'form-select select2 global-classes ','id'=>'filter_class','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('english.sections', __('english.sections') . ':') !!}
                        {!! Form::select('class_section_id', [],null, ['class' => 'form-select select2 global-class_sections', 'id'=>'filter_section','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('english.type', __('english.type') . ':') !!}
                        {!! Form::select('type', ['section_wise'=>'Section Wise','class_wise'=>'class Wise'],'section_wise', ['class' => 'form-select select2 ', 'id'=>'print-type','style' => 'width:100%']) !!}
                    </div>
                    <div class="col-sm-3 mt-3">
                        <button class="btn  btn-primary print">@lang( 'lang.print' )</button>
                    </div>
                </div>



                <hr>
                            <h5 class="card-title text-primary">@lang('english.strength_list')</h5>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="class_section_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.class_name')</th>
                                <th>@lang('english.section_name')</th>
                                <th>@lang('english.strength')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
 <div class="modal fade class_section_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {
       $(document).on("click", "button.print", function(e) {

           e.preventDefault();
    var campus_id = $('.global-campuses').val();
    var class_id = $('.global-classes').val();
    var class_section_id = $('.global-class_sections').val();
    var print_type = $('#print-type').val();
   

   

    $.ajax({
        method: 'GET',
        url: '/report/strength/show',
        dataType: 'json',
        data: {campus_id: campus_id,class_id: class_id,class_section_id: class_section_id,print_type:print_type},
        success: function(result) {
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
    });
        });
        //class_section_table
        var class_section_table = $("#class_section_table").DataTable({
            processing: true
            , serverSide: true
                         , "ajax": {
                 "url": "/report/strength"
                 , "data": function(d) {
                    

                     if ($('#filter_campus').length) {
                         d.campus_id = $('#filter_campus').val();
                     }
                     if ($('#filter_class').length) {
                         d.class_id = $('#filter_class').val();
                     }
                     if ($('#filter_section').length) {
                         d.class_section_id = $('#filter_section').val();
                     }
                    
                     d = __datatable_ajax_callback(d);
                 }
             }
            , columns: [{
                    data: "campus_name"
                    , name: "campus_name",
                    orderable: false,
                    searchable: false

                },{
                    data: "title"
                    , name: "title",
                    orderable: false,
                    searchable: false

                }
                , {
                    data: "section_name"
                    , name: "section_name",
                                        orderable: false,

                }
                , {
                    data: "total_student"
                    , name: "total_student",
                                        orderable: false,
                                        searchable: false

                }
                 
            , ]
        , });
 $(document).on('change', '#filter_campus,#filter_class,#filter_section',  function() {
          class_section_table.ajax.reload();
      });
   $(document).on("submit", "form#class_section_add_form", function (e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            dataType: "json",
            data: data,
            beforeSend: function (xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function (result) {
                if (result.success == true) {
                    $("div.class_section_modal").modal("hide");
                    toastr.success(result.msg);
                   class_section_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
 $(document).on("click", ".edit_class_section_button", function () {
        $("div.class_section_modal").load($(this).data("href"), function () {
            $(this).modal("show");

            $("form#class_section_edit_form").submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    beforeSend: function (xhr) {
                        __disable_submit_button(
                            form.find('button[type="submit"]')
                        );
                    },
                    success: function (result) {
                        if (result.success == true) {
                            $("div.class_section_modal").modal("hide");
                            toastr.success(result.msg);
                           class_section_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });
    });

</script>
@endsection
