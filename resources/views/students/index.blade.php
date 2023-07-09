 @extends("admin_layouts.app")
@section('title', __('english.student_details'))
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
                                         {!! Form::select('campus_id', $campuses, $campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'id'=>'students_list_filter_campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                         {!! Form::select('adm_class_id', $classes, $class_id, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                                     </div>
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                                         {!! Form::select('adm_class_section_id', $sections, $class_section_id, ['class' => 'form-select select2 global-class_sections', 'id' => 'students_list_filter_class_section_id', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                     <div class="clearfix"></div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('status', __('english.student_status') . ':*') !!}
                                         {!! Form::select('status', __('english.std_status'), 'active', ['class' => 'form-control select2 ','id'=>'students_list_filter_status','placeholder' => __('english.please_select'), 'required']); !!}
                                     </div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('vehicle_id', __('english.vehicles') . ':*') !!}
                                         {!! Form::select('vehicle_id', $vehicles, null, ['class' => 'form-control select2 ','id'=>'students_list_filter_vehicle_id','placeholder' => __('english.please_select'), 'required']); !!}
                                     </div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('admission_no', __('english.admission_no'), ['classs' => 'form-lable']) !!}
                                         {!! Form::text('admission_no', null, ['class' => 'form-control', 'id'=>'students_list_filter_admission_no','placeholder' => __('english.admission_no')]) !!}
                                     </div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('roll_no', __('english.roll_no')) !!}
                                         {!! Form::text('roll_no', null, ['class' => 'form-control', 'placeholder' => __('english.roll_no'), 'id' => 'students_list_filter_roll_no']) !!}
                                     </div>
                                     <div class="col-md-3 ">
                                         <div class="form-check mt-3 ">

                                             {!! Form::checkbox('only_transport', 1, null ,[ 'class' => 'form-check-input big-checkbox' , 'id' => 'only_transport'] ); !!}
                                             {!! Form::label('only_transport', __('english.only_transport')) !!}
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
                 <h5 class="card-title text-primary">@lang('english.student_list')</h5>
                @can('student.create')
                 <div class="d-lg-flex align-items-center mb-4 gap-3">
                     <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ action('StudentController@create') }}">
                             <i class="bx bxs-plus-square"></i>@lang('english.add_new_admission')</a></div>
                 </div>
                @endcan

                 <hr>

                 <div class="table-responsive">
                     <table class="table table-bordered table-striped" width="100%" id="students_table">
                         <thead class="table-light" width="100%">
                             <tr>
                                 {{-- <th>#</th> --}}
                                 <th>@lang('english.action')</th>
                                 <th>@lang('english.student_name')</th>
                                 <th>@lang('english.gender')</th>
                                 <th>@lang('DOB/ Age')</th>
                                 <th>@lang('english.father_name')</th>
                                 <th>@lang('english.status')</th>
                                 <th>@lang('english.roll_no')</th>
                                 <th>@lang('english.old_roll_no')</th>
                                 <th>@lang('english.mobile_no')</th>
                                 <th>@lang('english.permanent_address')</th>
                                 <th>@lang('english.admission_no')</th>
                                 <th>@lang('english.admission_date')</th>
                                 <th>@lang('english.campus_name')</th>
                                 <th>@lang('english.admission_class')</th>
                                 <th>@lang('english.current_class')</th>
                                 <th>@lang('english.vehicle_name')</th>
                                 <th>@lang('english.student_tuition_fee')</th>
                                 <th>@lang('english.student_transport_fee')</th>
                                 <th>@lang('Transport Due')</th>
                                 <th>@lang('english.balance')</th>
                             </tr>
                         </thead>
                         <tfoot>
                             <tr class=" footer-total">
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td><strong>Total</strong></td>
                                 <td class="footer_student_tuition_fee"></td>
                                 <td class="footer_student_transport_fee"></td>
                                 <td class="footer_total_due_transport_fee"></td>
                                 <td class="footer_total_due"></td>

                             </tr>
                         </tfoot>
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
 <script src="{{ asset('/js/student.js?v=' . $asset_v) }}"></script>
 <script type="text/javascript">
    $(document).ready(function() {
//         $('#students_list_filter_campus_id').trigger('change', function() {
//             //   $('#students_list_filter_class_id')
//             //         .val(1)
//             //         .trigger('change');
//     alert(4);

// });

//$("#students_list_filter_campus_id" ).trigger( "change" )

    });
 </script>
 @endsection

