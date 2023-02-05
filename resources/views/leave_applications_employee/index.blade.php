 @extends("admin_layouts.app")
@section('title', __('english.leave_applications_for_employee'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
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
                                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2', 'required', 'id'=>'campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('status', __('english.status') . ':*') !!}
                                         {!! Form::select('status',__('english.leave_status'), null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id'=>'leave_status','placeholder' => __('english.all')]); !!}
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
                 <h5 class="card-title text-primary">@lang('english.all') @lang('english.your') @lang('english.leave_applications_for_employee')</h5>
               <div class="d-lg-flex align-items-center mb-4 gap-3">
               @can('leave_applications_for_employee.create')
                    <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 add_new_leave_application"
                                data-href="{{ action('LeaveApplicationEmployeeController@create') }}"
                                data-container=".leave_applications_for_employee_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_leave_application')</button></div>
                                @endcan
                </div>


                 <hr>

                 <div class="table-responsive">
                     <table class="table mb-0" width="100%" id="leave_applications_for_employee_table">
                         <thead class="table-light" width="100%">
                             <tr>
                                 <th>@lang('english.action')</th>
                                 <th>@lang('english.campus_name')</th>
                                 <th>@lang('english.employee_name')</th>
                                 <th>@lang('english.status')</th>
                                 <th>@lang('english.reason')</th>
                                 <th>@lang('english.apply_date')</th>
                                 <th>@lang('english.from_date')</th>
                                 <th>@lang('english.to_date')</th>
                                 <th>@lang('english.approve_by')</th>

                             </tr>
                         </thead>

                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <div class="modal fade leave_applications_for_employee_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
 </div>
 @include('leave_applications_employee.update_leave_status_modal')

 @endsection

 @section('javascript')
  <script src="{{ asset('js/leave_application.js?v=' . $asset_v) }}"></script>

 @endsection

