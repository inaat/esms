 @extends("admin_layouts.app")
@section('title', __('english.online_applicant_list'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         <div class="card">
             <div class="card-body">
                 <div class="accordion" id="student-fillter">
                     <div class="accordion-item">
                         <h2 class="accordion-header" id="student-fillter">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                 <h5 class="card-title text-primary">@lang('english.students_flitters')</h5>
                             </button>
                         </h2>
                         <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="student-fillter" data-bs-parent="#student-fillter" style="">
                             <div class="accordion-body">
                                 <div class="row">
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id'=>'students_list_filter_campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                         {!! Form::select('adm_class_id',[], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                                     </div>
                                   
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('status', __('english.online_applicant_status') . ':*') !!}
                                         {!! Form::select('status', __('english.online_status'), 'online_admission', ['class' => 'form-control select2 ','id'=>'students_list_filter_status','placeholder' => __('english.please_select'), 'required']); !!}
                                     </div>
                                    
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.filter_date_range', __('english.filter_date_range') . ':') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                             {!! Form::text('list_filter_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'list_filter_date_range', 'class' => 'form-control']) !!}

                                         </div>
                                     </div>
                                  
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </div>





         <div class="card">
             <div class="card-body">
                 <h5 class="card-title text-primary">@lang('english.online_applicant_list')</h5>
              
                 <hr>

                 <div class="table-responsive">
                     <table class="table table-bordered table-striped" width="100%" id="students_table">
                         <thead class="table-light" width="100%">
                             <tr>
                                 {{-- <th>#</th> --}}
                                 <th>@lang('english.action')</th>
                                 <th>@lang('english.student_name')</th>
                                 <th>@lang('english.gender')</th>
                                 <th>@lang('english.cnic_number')</th>
                                 <th>@lang('DOB/ Age')</th>
                                 <th>@lang('english.father_name')</th>
                                 <th>@lang('english.father_cnic_no')</th>
                                 <th>@lang('english.status')</th>
                                 <th>@lang('english.mobile_no')</th>
                                 <th>@lang('english.permanent_address')</th>
                                 <th>@lang('english.online_applicant_no')</th>
                                 <th>@lang('english.applicant_submit_date')</th>
                                 <th>@lang('english.campus_name')</th>
                                 <th>@lang('english.admission_class')</th>
                          
                             </tr>
                         </thead>
                        
                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <div class="modal fade admission_fee_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
 </div>
 <div class="modal fade pay_fee_due_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
 </div>
 @include('students.partials.update_student_status_modal')

 @endsection

 @section('script')
 <script type="text/javascript">
    $(document).ready(function() {
  var students_table = $("#students_table").DataTable({
        processing: true,
        serverSide: true,
        scrollY: "75vh",
        scrollX: true,
        scrollCollapse: false,
        ajax: {
            url: "/online-applicants",
            data: function(d) {
                if ($("#students_list_filter_campus_id").length) {
                    d.campus_id = $("#students_list_filter_campus_id").val();
                }
              
                if ($("#students_list_filter_status").length) {
                    d.status = $("#students_list_filter_status").val();
                }
                if ($("#students_list_filter_class_id").length) {
                    d.class_id = $("#students_list_filter_class_id").val();
                }
                   if ($('#list_filter_date_range').val()) {
                         var start = $('#list_filter_date_range').data('daterangepicker')
                             .startDate.format('YYYY-MM-DD');
                         var end = $('#list_filter_date_range').data('daterangepicker')
                             .endDate.format('YYYY-MM-DD');
                         d.start_date = start;
                         d.end_date = end;
                     }
             
                d = __datatable_ajax_callback(d);
            },
        },

        columns: [{
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },

            {
                data: "student_name",
                name: "student_name",
            },
            {
                data: "gender",
                name: "gender",
                orderable: false,
                searchable: false,
            },
            {
                data: "cnic_no",
                name: "cnic_no",
                orderable: false,
                searchable: false,
            },
            {
                data: "birth_date",
                name: "birth_date",
                orderable: false,
                searchable: false,
            },
            {
                data: "father_name",
                name: "father_name",
            },{
                data: "father_cnic_no",
                name: "father_cnic_no",
            },
            {
                data: "status",
                name: "status",
                orderable: false,
                searchable: false,
            },
        
            {
                data: "mobile_no",
                name: "mobile_no",
            },
            {
                data: "std_permanent_address",
                name: "std_permanent_address",
            },
            {
                data: "online_applicant_no",
                name: "online_applicant_no",
            },
            {
                data: "applicant_submit_date",
                name: "applicant_submit_date",
            },
            {
                data: "campus_name",
                name: "campus_name",
                orderable: false,
                searchable: false,
            },
            {
                data: "adm_class",
                name: "adm_class",
                orderable: false,
                searchable: false,
            },
          
        ],
       
    });
    $(document).on(
        "change",
        "#students_list_filter_campus_id,#students_list_filter_class_id,#students_list_filter_status,#list_filter_date_range",
        function() {
            students_table.ajax.reload();
        }
    );
    });
 </script>
 @endsection

