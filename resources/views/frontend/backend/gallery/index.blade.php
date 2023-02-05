@extends("admin_layouts.app")
@section('title', __('english.gallery'))
@section('title', __('english.gallery'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_gallery')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.gallery')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.gallery_list')</h5>
                @can('gallery.create')
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-gallery-modal" href="#" data-href="{{ action('Frontend\FrontGalleryController@create') }}" data-container=".gallery_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_album')</a></div>
                </div>
                @endcan

                <hr>
                @can('gallery.view')
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="gallery_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang( 'english.action' )</th>
                                <th>@lang( 'english.thumb_image' )</th>
                                <th>@lang( 'english.gallery_title' )</th>
                                <th>@lang( 'english.description' )</th>
                                <th>@lang( 'english.show_website' )</th>
                            </tr>
                        </thead>

                    </table>
                </div>
                @endcan
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade gallery_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
@section('javascript')
<script type="text/javascript">
    //gallery table
    $(document).ready(function() {
        var gallery_table = $('#gallery_table').DataTable({
            processing: true
            , serverSide: true
            , ajax: '/galleries'
            , columns: [{
                    data: "action"
                    , name: "action"
                    , orderable: false
                    , searchable: false
                , }
                , {
                    data: "thumb_image"
                    , name: "thumb_image"
                , }
                , {
                    data: "title"
                    , name: "title"
                , }
                , {
                    data: "description"
                    , name: "description"
                    , orderable: false
                    , searchable: false
                , }
                , {
                    data: "status"
                    , name: "status"
                    , orderable: false
                    , searchable: false
                , },

            ]
        , });
        $(document).on('click', 'a.delete_gallery_button', function() {
            swal({
                title: LANG.sure
                , text: LANG.confirm_delete_role
                , icon: "warning"
                , buttons: true
                , dangerMode: true
            , }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    $.ajax({
                        method: "DELETE"
                        , url: href
                        , dataType: "json"
                        , data: data
                        , success: function(result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                gallery_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });

        $(document).on("click", ".btn-gallery-modal", function(e) {
            e.preventDefault();
            var container = $(this).data("container");

            $.ajax({
                url: $(this).data("href")
                , dataType: "html"
                , success: function(result) {
                    $(container).html(result).modal("show");

                }
            , });
        });
        $(document).on("click", "a.edit_gallery_button", function() {
            $("div.gallery_modal").load($(this).data("href"), function() {
                $(this).modal("show");

                $("form#gallery_edit_form").submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    $.ajax({
                        method: "POST"
                        , url: $(this).attr("action")
                        , data: new FormData(this)
                        , dataType: "json"
                        , contentType: false
                        , cache: false
                        , processData: false
                        , beforeSend: function(xhr) {
                            __disable_submit_button(
                                form.find('button[type="submit"]')
                            );
                        }
                        , success: function(result) {
                            if (result.success == true) {
                                $("div.gallery_modal").modal("hide");
                                toastr.success(result.msg);
                                gallery_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    , });
                });
            });
        });
    });

</script>
@endsection

