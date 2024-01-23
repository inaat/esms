@extends("admin_layouts.app")
@section('title', __('english.slider'))
@section('title', __('english.slider'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_slider')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.slider')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.slider_list')</h5>
                @can('slider.create')
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-slider-modal" href="#" data-href="{{ action('MobileSliderController@create') }}" data-container=".slider_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_slider')</a></div>
                </div>
                @endcan

                <hr>
                @can('slider.view')
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="slider_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang( 'english.action' )</th>
                                <th>@lang( 'english.slider_image' )</th>
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
<div class="modal fade slider_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
@section('javascript')
<script type="text/javascript">
    //slider table
    $(document).ready(function() {
        var slider_table = $('#slider_table').DataTable({
            processing: true
            , serverSide: true
            , ajax: '/sliders'
            , columns: [{
                    data: "action"
                    , name: "action"
                    , orderable: false
                    , searchable: false
                , }
                , {
                    data: "image"
                    , name: "image"
                , }
                ,

            ]
        , });
        $(document).on('click', 'a.delete_slider_button', function() {
            swal({
                title: LANG.sure
                , text: LANG.confirm_delete
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
                                slider_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });

        $(document).on("click", ".btn-slider-modal", function(e) {
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
        $(document).on("click", "a.edit_slider_button", function() {
            $("div.slider_modal").load($(this).data("href"), function() {
                $(this).modal("show");

                $("form#slider_edit_form").submit(function(e) {
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
                                $("div.slider_modal").modal("hide");
                                toastr.success(result.msg);
                                slider_table.ajax.reload();
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

