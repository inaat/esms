@extends("admin_layouts.app")
@section('title', __('english.whatsapp_messaging'))

 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->

  <!--breadcrumb-->

         <div class="card">
             <div class="card-body">
                 <div class="accordion" id="transaction-filter">
                     <div class="accordion-item">
                         <h2 class="accordion-header" id="transaction-filter">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                 <h5 class="card-title text-primary">@lang('english.transaction_filter')</h5>
                             </button>
                         </h2>
                         <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="transaction-filter" data-bs-parent="#transaction-filter" style="">
                             <div class="accordion-body">
                                 <div class="row">
                                     
                          
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('status', __('english.status') . ':*') !!}
                                         {!! Form::select('status', [1 => 'Pending', 2 => 'Schedule', 3 =>'Fail', 4 => 'Delivered',5=>'Processing'], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id'=>'status','placeholder' => __('english.all')]) !!}
                                     </div>
                                      <div class="col-md-4">
                                         {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                             {!! Form::text('list_filter_date_range', null, ['class' => 'form-control', 'id'=>'list_filter_date_range','readonly', 'placeholder' => __('english.date_range')]) !!}

                                         </div>
                                     </div> 
                                     <div class="clearfix"></div>

                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </div>



         <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.sms_settings')</h5>
                <div class="row">
                                     
                          
                    <div class="col-md-4 p-1">
                        {!! Form::label('status', __('english.sms_status') . ':*') !!}
                        {!! Form::select('sms_status', ['sms_off' => __('english.sms_off'), 'sms_on' => __('english.sms_on')], $device->sms_status, ['class' => 'form-control select2', 'style' => 'width:100%', 'id'=>'sms_status']) !!}
                    </div>
                   
                    <div class="d-lg-flex align-items-center col-md-8 mt-4">
                       
                           @can('student_attendance.mark_absent_today')
                           <div class=""><a class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ action('WhatsAppController@attendanceSmsSend') }}">
                                   @lang('english.send_sms_absent_today')</a>
                           </div>
                           <div class=""><a class="btn btn-danger radius-30 mt-2 mt-lg-0" href="{{ action('WhatsAppController@jobEmpty') }}">
                                   @lang('english.sms_delete')</a>
                           </div>
                           @endcan
                </div>

            </div>
        </div>


             <div class="card">
                 <div class="card-body">
                     <h5 class="card-title text-primary">@lang('english.all_fee_transaction')</h5>

                

                     <hr>

                     <div class="table-responsive">
                         <table class="table mb-0" width="100%" id="fee_transaction_table">
                             <thead class="table-light" width="100%">
                                 <tr>
                                     {{-- <th>#</th> --}}
                                     {{-- <tr>
                                        <th class="d-flex align-items-center">
                                            <input class="form-check-input mt-0 me-2 checkAll"
                                                   type="checkbox"
                                                   value=""
                                                   aria-label="Checkbox for following text input"> {{ translate('#')}}
                                        </th> --}}
                                        <th>@lang('english.action')</th>
                                        <th>@lang('english.added_by')</th>
                                        <th>@lang('english.initiated')</th>
                                        <th>@lang('english.status')</th>
                                        <th>@lang('english.to')</th>
                                        <th style="width:10%;">@lang('english.message')</th>
                                        <th>@lang('english.response_gateway')</th>

                                 </tr>
                             </thead>
                       
                         </table>
                     </div>
                 </div>
             </div>
         </div>
     </div>
    
 @endsection

 @section('javascript')
<script >

$(document).ready(function() {

    //fee_transaction_table
    var fee_transaction_table = $("#fee_transaction_table").DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": "/sms-logs",
            "data": function(d) {

             
                if ($('#status').length) {
                    d.status = $('#status').val();
                }
             
                var start = "";
                var end = "";
                if ($("#list_filter_date_range").val()) {
                    start = $("input#list_filter_date_range")
                        .data("daterangepicker")
                        .startDate.format("YYYY-MM-DD");
                    end = $("input#list_filter_date_range")
                        .data("daterangepicker")
                        .endDate.format("YYYY-MM-DD");
                }
                d.start_date = start;
                d.end_date = end;

            }
        },

        columns: 
        [
            {
                data: "action",
                name: "action",
                orderable: false,
                "searchable": false
            },
            {
                data: "added_by",
                name: "added_by",
                orderable: false,
                "searchable": false
            },
           
            {
                data: "initiated_time",
                name: "initiated_time",
                orderable: false,
                "searchable": false  
            },
            {
                data: "status",
                name: "status",
                orderable: false,
                "searchable": false  
            },
          
            {
                data: "to",
                name: "to",
                orderable: false,
                
            },
            {
                data: "message",
                name: "message",
                orderable: false,
                "searchable": false  
            },
            {
                data: "response_gateway",
                name: "response_gateway",
                orderable: false,
            }
        
        ],
      
            });
    $(document).on('change', '#campus_id,#status,#list_filter_date_range,#transaction_type', function() {
        fee_transaction_table.ajax.reload();
    });

     //single exam Print.
   $(document).on('change', '#sms_status', function(e) {
    e.preventDefault();

    var sms_status = $('#sms_status').val();

    var href = '/sms-status'
    $.ajax({
        method: 'GET',
        url: href,
        dataType: 'json',
        data: {sms_status: sms_status},
        success: function(result) {
            $('.pace-active')
            if (result.success == 1 ) {
                toastr.success(result.msg);

            } else {
                toastr.error(result.msg);
            }
        },
    });
    });

    });

</script>
 @endsection
