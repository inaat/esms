@extends("admin_layouts.app")
@section('title', __('english.campuses'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_campuses')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.campuses')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                            <h5 class="card-title text-primary">@lang('english.campuses_list')</h5>

                <div class="d-lg-flex align-items-center mb-4 gap-3">
                @can('campuses.create')
                    <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ action('CampusController@create') }}">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_campus')</a></div>
                  @endcan
                </div>


                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="campuses_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.registration_date')</th>
                                <th>@lang('english.registration_code')</th>
                                <th>@lang('english.mobile')</th>
                                <th>@lang('english.phone')</th>
                                <th>@lang('english.address')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {

        //campuses_table
        var campuses_table = $("#campuses_table").DataTable({
            processing: true
            , serverSide: true
            , ajax: "/campuses"
            , columns: [{
                    data: "action"
                    , name: "action"
                }
                , {
                    data: "campus_name"
                    , name: "campus_name"
                }
                , {
                    data: "registration_date"
                    , name: "registration_date"
                }
                , {
                    data: "registration_code"
                    , name: "registration_code"
                }
                , {
                    data: "mobile"
                    , name: "mobile"
                }
                , {
                    data: "phone"
                    , name: "phone"
                }
                , {
                    data: "address"
                    , name: "address"
                }
            , ]
        , });



    });

</script>
@endsection
