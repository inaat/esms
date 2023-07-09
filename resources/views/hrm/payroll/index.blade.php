 @extends("admin_layouts.app")
 @section('title', __('english.pay_roll'))
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
                                     {{-- <div class="col-md-4 p-1">
                                         {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                         {!! Form::select('session_id',$sessions,session()->get('english.id')? session()->get('english.id') : null , ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'),'id'=>'session_id']) !!}
                                     </div>
                                     <div class="col-md-3 p-2">
                                         {!! Form::label('english.fee_month', __('english.fee_month') . ':*') !!}
                                         {!! Form::select('month_id',__('english.months'),session()->get('current_month')? session()->get('current_month') : null, ['class' => 'form-select select2', 'required', 'style' => 'width:100%','placeholder' => __('english.all'),'id'=>'month']) !!}
                                     </div> --}}
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('status', __('english.payment_status') . ':*') !!}
                                         {!! Form::select('payment_status', ['paid' => __('english.paid'), 'due' => __('english.due'), 'partial' => __('english.partial'), 'overdue' => __('english.overdue')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id'=>'payment_status','placeholder' => __('english.all')]); !!}
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
                 <h5 class="card-title text-primary">@lang('english.all_payroll_transaction')</h5>
                 @can('payroll.create')

                 <div class="d-lg-flex align-items-center mb-4 gap-3">
                     <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ action('Hrm\HrmPayrollController@create') }}">
                             <i class="bx bxs-plus-square"></i>@lang('english.add_new_payroll')</a></div>
                     @endcan
                 </div>


                 <hr>

                 <div class="table-responsive">
                     <table class="table mb-0" width="100%" id="payroll_transaction_table">
                         <thead class="table-light" width="100%">
                             <tr>
                                 {{-- <th>#</th> --}}
                                 <th>@lang('english.action')</th>
                                 <th>@lang('english.transaction_month')</th>
                                 <th>@lang('english.transaction_date')</th>
                                 <th>@lang('english.ref_no')</th>
                                 <th>@lang('english.campus_name')</th>
                                 <th>@lang('english.employee_name')</th>
                                 <th>@lang('english.payment_status')</th>
                                 <th>@lang('english.final_total')</th>
                                 <th>@lang('english.total_paid')</th>
                                 <th>@lang('english.payroll_due')</th>
                                 <th>@lang('english.father_name')</th>
                                 <th>@lang('english.status')</th>
                                 <th>@lang('english.employeeID')</th>

                             </tr>
                         </thead>

                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <div class="modal fade payment_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
 </div>
 <div class="modal fade pay_payroll_due_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
 </div>
 <div class="modal fade edit_payment_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
 </div>
 @endsection

 @section('javascript')
 <script src="{{ asset('js/hrm.js?v=' . $asset_v) }}"></script>

 @endsection

