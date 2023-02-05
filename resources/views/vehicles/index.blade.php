 @extends("admin_layouts.app")
@section('title', __('english.vehicles'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
      
         <div class="card">
             <div class="card-body">
                 <h5 class="card-title text-primary">@lang('english.all_your_vehicles')</h5>

              
               <div class="d-lg-flex align-items-center mb-4 gap-3">
               @can('vehicle.create')
                    <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('VehicleController@create') }}"
                                data-container=".vehicle_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_vehicle')</button></div>
                                @endcan
                </div>



                 <hr>

                 <div class="table-responsive">
                     <table class="table mb-0" width="100%" id="vehicle_table">
                         <thead class="table-light" width="100%">
                             <tr>
                                 {{-- <th>#</th> --}}
                                 <th>@lang('english.action')</th>
                                 <th>@lang('english.name')</th>
                                  <th>@lang('english.vehicle_number')</th>
                                 <th>@lang('english.vehicle_model')</th>
                                 <th>@lang('english.driver_name')</th>
                                 <th>@lang('english.driver_license')</th>
                                 <th>@lang('english.year_made')</th>
                                

                             </tr>
                         </thead>

                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
  <div class="modal fade vehicle_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>
 @endsection

 @section('javascript')

 <script type="text/javascript">
     $(document).ready(function() {
         //vehicle_table
         var vehicle_table = $("#vehicle_table").DataTable({
             processing: true
             , serverSide: true
             , scrollY: "75vh"
             , scrollX: true
             , scrollCollapse: true
             , ajax: "/vehicles",

             columns: [{
                     data: "action"
                     , name: "action"
                     , orderable: false
                     , "searchable": false
                 }

                 , {
                     data: "name"
                     , name: "name"
                 }, {
                     data: "vehicle_number"
                     , name: "vehicle_number"
                     , orderable: false
                     , "searchable": false
                 }, {
                     data: "vehicle_model"
                     , name: "vehicle_model"
                     , orderable: false
                     , "searchable": false
                 }
                 , {
                     data: "employee_name"
                     , name: "employee_name",

                 }, {
                     data: "driver_license"
                     , name: "driver_license"
                     , orderable: false
                     , "searchable": false
                 }, {
                     data: "year_made"
                     , name: "year_made"
                     , orderable: false
                     , "searchable": false
                 },  ],

         });

      
         $(document).on('change', '#campus_id,#payment_status,#list_filter_date_range', function() {
             vehicle_table.ajax.reload();
         });

   $(document).on("submit", "form#vehicle_add_form", function (e) {
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
                    $("div.vehicle_modal").modal("hide");
                    toastr.success(result.msg);
                    vehicle_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });


    
 $(document).on("click", ".edit_vehicle_button", function () {
        $("div.vehicle_modal").load($(this).data("href"), function () {
            $(this).modal("show");

            $("form#vehicle_edit_form").submit(function (e) {
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
                            $("div.vehicle_modal").modal("hide");
                            toastr.success(result.msg);
                           vehicle_table.ajax.reload();
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

