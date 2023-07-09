@extends("admin_layouts.app")
@section('title', __('english.manage_your_increment_decrement'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_increment_decrement')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.fee_increment_decrement')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.fee_increment_decrement_list')</h5>

                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    @can('fee.increment_decrement')
                    <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('FeeIncrementController@create') }}" data-container=".fee-increment_decrement_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_fee_increment_decrement')</button></div>
                    @endcan
                </div>



                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="fee-increment_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.session')</th>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.class_name')</th>
                                <th>@lang('english.section_name')</th>
                                <th>@lang('english.tuition_fee')</th>
                                <th>@lang('english.transport_fee')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade fee-increment_decrement_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {

        //fee_heead_table
        var fee_heead_table = $("#fee-increment_table").DataTable({
            processing: true
            , serverSide: true
            , ajax: "/fee-increment"
            , columns: [ 
                {
                      data:'session_info',
                      name:'session_info'
                },
                {
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
                    data: "tuition_fee"
                    , name: "tuition_fee"
                }
                , {
                    data: "transport_fee"
                    , name: "transport_fee"
                }
            , ]
        , });

        $(document).on("submit", "form#add_new_fee_increment_decrement", function(e) {
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
                        $("div.fee-increment_decrement_modal").modal("hide");
                        toastr.success(result.msg);
                        fee_heead_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            , });
        });
    });

</script>
@endsection

