@extends("admin_layouts.app")
@section('title', __('english.event'))
@section('title', __('english.event'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_event')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.event')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.event_list')</h5>
                @can('slider.create')
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 " href="{{ action('Frontend\FrontEventController@create') }}" >
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_event')</a></div>
                </div>
                @endcan

                <hr>
                @can('slider.view')
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="slider_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang( 'english.action' )</th>
                                <th>@lang( 'english.image' )</th>
                                <th>@lang( 'english.event_title' )</th>
                                <th>@lang( 'english.description' )</th>
                                <th>@lang( 'english.from' )</th>
                                <th>@lang( 'english.to' )</th>
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

@endsection
@section('javascript')
<script type="text/javascript">
    //slider table
    $(document).ready(function() {
        var slider_table = $('#slider_table').DataTable({
            processing: true
            , serverSide: true
            , ajax: '/front-events'
            , columns: [{
                    data: "action"
                    , name: "action"
                    , orderable: false
                    , searchable: false
                , }
                , {
                    data: "images"
                    , name: "images"
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
                    data: "from"
                    , name: "from"
                    , orderable: false
                    , searchable: false
                , }
                , {
                    data: "to"
                    , name: "to"
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
        $(document).on('click', 'a.delete_event_button', function() {
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
                                slider_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
        });

     
    });

</script>
@endsection

