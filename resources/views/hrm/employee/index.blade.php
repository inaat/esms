 @extends("admin_layouts.app")
@section('title', __('english.employees'))
 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->
             <div class="card">
                 <div class="card-body">
                     <div class="accordion" id="employee-fillter">
                         <div class="accordion-item">
                             <h2 class="accordion-header" id="employee-fillter">
                                 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                     data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                     <h5 class="card-title text-primary">@lang('english.employees_flitters')</h5>
                                 </button>
                             </h2>
                             <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="employee-fillter"
                                 data-bs-parent="#employee-fillter" style="">
                                 <div class="accordion-body">
                                     <div class="row">
                                         <div class="col-md-3 p-1">
                                             {!! Form::label('campus_id', __('english.campuses') . ':*') !!}
                                             {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2', 'required', 'id' => 'list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                         </div>
                                         <div class="col-md-3 p-1">
                                             {!! Form::label('employeeID', __('english.employeeID')) !!}
                                             {!! Form::text('employeeID', null, ['class' => 'form-control', 'placeholder' => __('english.employeeID'), 'id' => 'employees_list_filter_employeeID']) !!}
                                         </div>
                                         <div class="col-md-3 p-1">
                                             {!! Form::label('status', __('english.employee_status') . ':*') !!}
                                             {!! Form::select('employees_list_filter', __('english.emp_status'), null, ['class' => 'form-select', 'id' => 'employees_list_filter', 'placeholder' => __('english.all'), 'required']) !!}
                                         </div>
                                         <div class="col-md-3 p-1">
                                             {!! Form::label('english.joining_date', __('english.joining_date') . ':*') !!}
                                             <div class="input-group flex-nowrap"> <span class="input-group-text"
                                                     id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                                 {!! Form::text('employees_list_filter_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'employees_list_filter_date_range', 'class' => 'form-control', 'readonly']) !!}

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
                     <h5 class="card-title text-primary">@lang('english.employee_list')</h5>
                    @can('employee.create')
                     <div class="d-lg-flex align-items-center mb-4 gap-3">
                         <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0"
                                 href="{{ action('Hrm\HrmEmployeeController@create') }}">
                                 <i class="bx bxs-plus-square"></i>@lang('english.add_new_employee')</a></div>
                     </div>
                     @endcan

                     <hr>

                     <div class="table-responsive">
                         <table class="table mb-0" width="100%" id="employees_table">
                             <thead class="table-light" width="100%">
                                 <tr>
                                     {{-- <th>#</th> --}}
                                     <th>@lang('english.action')</th>
                                     <th>@lang('english.employee_name')</th>
                                     <th>@lang('english.father_name')</th>
                                     <th>@lang('english.status')</th>
                                     <th>@lang('english.employeeID')</th>
                                     <th>@lang('english.joining_date')</th>
                                     <th>@lang('english.campus_name')</th>

                                 </tr>
                             </thead>

                         </table>
                     </div>
                 </div>
             </div>
         </div>
     </div>
    
     <div class="modal fade pay_payroll_due_modal contains_select2" tabindex="-1" role="dialog"
         aria-labelledby="gridSystemModalLabel">
     </div>
     @include('hrm.employee.partials.update_employee_status_modal')
     @include('hrm.employee.partials.employee_resign_modal')

 @endsection

 @section('javascript')
 <script src="{{ asset('js/hrm.js?v=' . $asset_v) }}"></script>

 @endsection
