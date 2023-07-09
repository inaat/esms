 @extends("admin_layouts.app")
 @section('title', __('english.dashboard'))
 @section('wrapper')
 @section("style")
 <link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
 <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
 @endsection
 <div class="page-wrapper">
     <div class="page-content">
        {{-- <a title="Scan" href="javascript:void(0)" id="textChange" class="badge bg-success p-2 qrQuote textChange8" value="8"><svg class="svg-inline--fa fa-qrcode" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="qrcode" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M144 32C170.5 32 192 53.49 192 80V176C192 202.5 170.5 224 144 224H48C21.49 224 0 202.5 0 176V80C0 53.49 21.49 32 48 32H144zM128 96H64V160H128V96zM144 288C170.5 288 192 309.5 192 336V432C192 458.5 170.5 480 144 480H48C21.49 480 0 458.5 0 432V336C0 309.5 21.49 288 48 288H144zM128 352H64V416H128V352zM256 80C256 53.49 277.5 32 304 32H400C426.5 32 448 53.49 448 80V176C448 202.5 426.5 224 400 224H304C277.5 224 256 202.5 256 176V80zM320 160H384V96H320V160zM352 448H384V480H352V448zM448 480H416V448H448V480zM416 288H448V416H352V384H320V480H256V288H352V320H416V288z"></path></svg><!-- <i class="fas fa-qrcode"></i> Font Awesome fontawesome.com -->&nbsp; Scan</a> --}}
         @can('dashboard.view')
         <div class="row">
             <div class="col-md-4 col-xs-12">
                 {{-- @if(count($all_locations) > 1)
                        {!! Form::select('dashboard_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('english.select_location'), 'id' => 'dashboard_location']); !!}
                    @endif --}}
                 @if(count($campuses) > 1)
                 {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.select_campus'), 'id' => 'campus']) !!}
                 @endif
             </div>
             <div class="col-md-8 col-xs-12">
                 <div class="form-group float-end">
                     <div class="input-group">
                         <button type="button" class="btn btn-primary" id="dashboard_date_filter">
                             <span>
                                 <i class="fa fa-calendar"></i> {{ __('english.filter_by_date') }}
                             </span>
                             <i class="fa fa-caret-down"></i>
                         </button>
                     </div>
                 </div>
             </div>
         </div>
         <br>
         <h3>Administration</h3>

         <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
             <div class="col {{!empty($common_settings['active_students']) ? '' : 'hide'}}">
                 <div class="card radius-10">
                     <div class="card-body" >
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary "><a href="{{ action('StudentController@index') }}">@lang('english.active_students')</a></p>
                                 <h4 class="font-weight-bold info-box-number active_students loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-group"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary "><a href="{{ action('AttendanceController@index').'?type=present' }}">@lang('english.total_student_present')</a></p>
                                <h4 class="font-weight-bold info-box-number total_student_present loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary "><a href="{{ action('AttendanceController@index').'?type=late' }}">@lang('english.total_student_late')</a></p>
                                <h4 class="font-weight-bold info-box-number total_student_late loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary "><a href="{{ action('AttendanceController@index').'?type=absent' }}">@lang('english.total_student_absent')</a></p>
                                <h4 class="font-weight-bold info-box-number total_student_absent loader"><i class="widgets-icons  bg-light-warning text-warning fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class="bx bx-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
             <div class="col  {{!empty($common_settings['inactive_students']) ? '' : 'hide'}}">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">@lang('english.inactive_students')</p>
                                 <h4 class="font-weight-bold info-box-number inactive_students loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class="bx bxs-group"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col  {{!empty($common_settings['pass_out_students']) ? '' : 'hide'}}">
                 <div class="card radius-10 ">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">@lang('english.pass_out_students')</p>
                                 <h4 class="font-weight-bold info-box-number pass_out_students loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class="bx bxs-group"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col {{!empty($common_settings['struck_up_students']) ? '' : 'hide'}} ">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">@lang('english.struck_up_students')</p>
                                 <h4 class="font-weight-bold info-box-number struck_up_students loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-info text-info ms-auto"><i class="bx bxs-group"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col  {{!empty($common_settings['took_slc_students']) ? '' : 'hide'}}">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">@lang('english.took_slc_students')</p>
                                 <h4 class="font-weight-bold info-box-number took_slc_students loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-moonlit text-moonlit ms-auto"><i class="bx bxs-group"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="clearfix"></div>

             <div class="col {{!empty($common_settings['active_employees']) ? '' : 'hide'}}">
                 <div class="card radius-10">
                     <div class="card-body " >
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary "><a href="{{ action('Hrm\HrmEmployeeController@index') }}">@lang('english.active_employees')</a></p>
                                 <h4 class="font-weight-bold info-box-number active_employees loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-gradient-Ohhappiness text-white ms-auto"><i class="bx bxs-user"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col {{!empty($common_settings['resign_employees']) ? '' : 'hide'}} ">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">@lang('english.resign_employees')</p>
                                 <h4 class="font-weight-bold info-box-number resign_employees loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-gradient-burning  text-white ms-auto"><i class="bx bxs-user"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary "><a href="{{ action('Hrm\HrmAttendanceController@index').'?type=present' }}">@lang('english.total_employee_present')</a></p>
                                 <h4 class="font-weight-bold info-box-number total_employee_present loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-calendar"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary "><a href="{{ action('Hrm\HrmAttendanceController@index').'?type=absent' }}">@lang('english.total_employee_absent')</a></p>
                                 <h4 class="font-weight-bold info-box-number total_employee_absent loader"><i class="widgets-icons  bg-light-warning text-warning fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class="bx bx-calendar"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             
         </div>
         <hr>
         <h3>Accounts</h3>

         <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Total Fee</p>
                                 <h4 class="font-weight-bold info-box-number total_due_amount loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-money"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center today" data-href="{{ action('Report\IncomeReportController@FeePaidToday') }}" data-container=".view_modal">
                            <div>
                                 <p class="mb-0 text-secondary ">Total Paid Fee Today</p>
                                 <h4 class="font-weight-bold info-box-number total_paid_amount loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-money   text-success "></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Progressive Fee Collection During The Month</p>
                                 <h4 class="font-weight-bold info-box-number total_progress_collection_during_month loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-money"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Remaining Fee</p>
                                 <h4 class="font-weight-bold info-box-number total_fee_balance_amount loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-money"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Total Expenses Today</p>
                                 <h4 class="font-weight-bold info-box-number total_expenses loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-gradient-burning  text-white ms-auto"><i class="bx bx-minus-circle"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Progressive Expenses Collection During The Month</p>
                                 <h4 class="font-weight-bold info-box-number total_hrm_paid_amount loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-gradient-burning  text-white ms-auto"><i class="bx bx-minus-circle"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Balance</p>
                                 <h4 class="font-weight-bold info-box-number total_profit_amount loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-gradient-burning  text-white ms-auto"><i class="bx bx-minus-circle"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             {{-- <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <ul>
                               <li ><b>Cash Drwaer: RS 0</b></li>
                               <li>Bank Khyber : RS 0</li> 
                               <li><b>Bank Alfla : RS 0</b></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div> --}}
            <div class="clearfix"></div>

             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Total Transport</p>
                                 <h4 class="font-weight-bold info-box-number total_transport_dues_this_month loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-money"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center  today" data-href="{{ action('Report\IncomeReportController@TransportFeePaidToday') }}" data-container=".view_modal">
                             <div>
                                 <p class="mb-0 text-secondary ">Total Transport Paid Today</p>
                                 <h4 class="font-weight-bold info-box-number total_transport_paid_amount loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-money"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
              <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Progressive Transport Collection During The Month</p>
                                 <h4 class="font-weight-bold info-box-number total_transport_progressive_amount loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success  text-white ms-auto"><i class="bx bx-money"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col">
                 <div class="card radius-10">
                     <div class="card-body">
                         <div class="d-flex align-items-center">
                             <div>
                                 <p class="mb-0 text-secondary ">Transport Balance</p>
                                 <h4 class="font-weight-bold info-box-number total_transport_balance loader"><i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i></h4>
                             </div>
                             <div class="widgets-icons bg-light-success  text-white ms-auto"><i class="bx bx-money"></i>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <!--end row-->


         <div class="row">

             <div class="col-6 d-flex">
                 <div class="card radius-10 w-100">
                     <div class="card-body">
                        <table class="table" id="assets_table">
                            <tbody>
                               
                                <tr>
                                    <th colspan="2">@lang('english.account_balances'):</th>
                                </tr>
                            </tbody>
                            <tbody id="account_balances" class="pl-20-td">
                                <tr>
                                    <td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td>
                                </tr>
                            </tbody>
                            <tbody class="text-primary"class="pl-20-td">
                                <tr>
                                    <th colspan="">       
                                        @lang('english.total_assets'):
                                    </th>
                                    <td colspan="">       
                                        <span id="total_assets"><i class="fas fa-sync fa-spin fa-fw"></i></span>
                                      </td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
                 </div>
             </div>
             <div class="col-6 d-flex">
                 <div class="card radius-10 w-100">
                     <div class="card-body">
                       
                        <h5 class="card-title text-primary">@lang('english.strength_list')</h5>

                        <div class="table-responsive">
                            <table class="table mb-0" width="100%" id="class_section_table">
                                <thead class="table-light" width="100%">
                                    <tr>
                                        <th>@lang('english.campus_name')</th>
                                        <th>@lang('english.class_name')</th>
                                        <th>@lang('english.section_name')</th>
                                        <th>@lang('english.strength')</th>
                                    </tr>
                                </thead>
        
                            </table>
                     </div>
                 </div>
             </div>
         </div>

         <div class="row">

             <div class="col-12 d-flex">
                 <div class="card radius-10 w-100">
                     <div class="card-body">
                         <div class="card radius-10 shadow-none bg-transparent border">

                             <div class="" id="chart5"></div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="row">

             <div class=" col-6 d-flex">
                 <div class="card radius-10 p-0 w-100 p-3">
                     <div class="card radius-10 shadow-none bg-transparent border">
                         <div class="card-body">
                             <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                 <div id="pieChart"></div>
                             </div>
                         </div>
                     </div>

                 </div>
             </div>
         </div>
         <!--end row-->
         @endcan
     </div>
 </div>

 @endsection

 @section('javascript')
 <script src="{{ asset('/js/apps.js?v=' . $asset_v) }}"></script>
 <script src="assets/plugins/highcharts/js/highcharts.js"></script>
 <script src="assets/plugins/highcharts/js/exporting.js"></script>
 <script src="assets/plugins/highcharts/js/variable-pie.js"></script>
 <script src="assets/plugins/highcharts/js/export-data.js"></script>
 <script src="assets/plugins/highcharts/js/accessibility.js"></script>
 <script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
 <script src="{{ asset('/js/home.js?v=' . $asset_v) }}"></script>

 @endsection

