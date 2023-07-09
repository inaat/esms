@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_regions')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.regions')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                            <h5 class="card-title text-primary">@lang('english.region_list')</h5>

               <div class="d-lg-flex align-items-center mb-4 gap-3">
               @can('english.create')
                    <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('RegionController@create') }}"
                                data-container=".regions_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_region')</button></div>
               
               @endcan
               </div>



                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="regions_table">
                        <thead class="table-light" width="100%">
                            <tr>
                             <th>@lang('english.country_name')</th>
                                <th>@lang('english.province_name')</th>
                                <th>@lang('english.district_name')</th>
                                <th>@lang('english.city_name')</th>
                                <th>@lang('english.region_name')</th>
                                <th>@lang('english.transport_fee')</th>
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
 <div class="modal fade regions_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {

        //regions_table
        var regions_table = $("#regions_table").DataTable({
            processing: true
            , serverSide: true
            , ajax: "/regions"
            , columns: [{
                    data: "country_name",
                     name: "country_name",
                     orderable: false,
                       "searchable": false
                    
                }
                ,
                {
                    data: "province_name"
                    , name: "province_name",
                    orderable: false,
                       "searchable": false
                }
                ,
                {
                    data: "district_name"
                    , name: "district_name",
                    orderable: false,
                       "searchable": false
                }
                ,
                {
                    data: "city_name"
                    , name: "city_name",
                    orderable: false,
                       "searchable": false
                }

                , {
                    data: "name"
                    , name: "name"
                }
                , {
                    data: "transport_fee"
                    , name: "transport_fee"
                }
            , 
            {
                    data: "action"
                    , name: "action"
                }
                , 
            
            ]
        , });

   $(document).on("submit", "form#region_add_form", function (e) {
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
                    $("div.regions_modal").modal("hide");
                    toastr.success(result.msg);
                    regions_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
 $(document).on("click", ".edit_region_button", function () {
        $("div.regions_modal").load($(this).data("href"), function () {
            $(this).modal("show");

            $("form#region_edit_form").submit(function (e) {
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
                            $("div.regions_modal").modal("hide");
                            toastr.success(result.msg);
                           regions_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });
    
       $(document).on("click", "button.delete_region_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_region,
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
                                    regions_table.ajax.reload();
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
