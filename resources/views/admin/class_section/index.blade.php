@extends("admin_layouts.app")
@section('title', __('english.section'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_sections')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.sections')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                            <h5 class="card-title text-primary">@lang('english.section_list')</h5>

               <div class="d-lg-flex align-items-center mb-4 gap-3">
               @can('section.create')
                    <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('ClassSectionController@create') }}"
                                data-container=".class_section_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_section')</button>
                                </div>
                                @endcan
                </div>



                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="class_section_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.class_name')</th>
                                <th>@lang('english.section_name')</th>
                                <th>@lang('english.whatsapp_group_name')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
 <div class="modal fade class_section_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {

        //class_section_table
        var class_section_table = $("#class_section_table").DataTable({
            processing: true
            , serverSide: true
            , ajax: "/sections"
            , columns: [{
                    data: "action"
                    , name: "action"
                }
                , {
                    data: "campus_name"
                    , name: "campus_name"
                }
                , {
                    data: "class_name"
                    , name: "class_name"
                }
                 , {
                    data: "section_name"
                    , name: "section_name"
                }
                 , {
                    data: "whatsapp_group_name"
                    , name: "whatsapp_group_name"
                }
            , ]
        , });

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
