<header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="top-menu-left d-none d-lg-block">
                        <ul class="nav">
                             @can('whatsapp_check.view')
                              <li class="nav-item">
                                {{-- <a class="nav-link  qrQuote textChange8"  value="8" href="javascript:void(0)" id="textChange"  data-container=".view_modal"><i class='lni lni-whatsapp'></i><span class="whatappstatus"></span></a> --}}
                                <a title="Scan" href="javascript:void(0)" id="textChange" class="nav-link p-2 qrQuote textChange8" value="8"><i class="lni lni-whatsapp"></i>&nbsp; Scan</a>


                            </li>
                              @endcan
                            @can('device.sync')
                              <li class="nav-item">
                                <a class="nav-link clear-att"
                                 type="button"  href="#" data-href="{{ action('MappingController@clearAttendance') }}">
                                 <i class="lni lni-printer"></i>@lang('english.mapping')</a>
                              </li>
                              @endcan
                             

                              @can('print.admission_form')
                              <li class="nav-item">
                                <a class="nav-link "
                                   href="{{ action('StudentController@emptyAdmissionForm') }}">
                                 <i class="lni lni-printer"></i>@lang('english.print_admission_form')</a>
                              </li>
                              @endcan
                          </ul>
                     </div>

                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                          
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class='bx bx-category'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="row row-cols-3 g-3 p-3">
                                        @can('student.view')
                                        <div class="col text-center">
                                          <a  href="{{ action('StudentController@index') }}">
                                            <div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-group'></i>
                                            </div>
                                            <div class="app-title">@lang('english.students')</div>
                                            </a>
                                        </div>
                                        @endcan
                                        @can('fee.add_fee_payment')
                                        <div class="col text-center">
                                             <a  href="{{ action('FeeCollectionController@create') }}">
                                            <div class="app-box mx-auto bg-gradient-burning text-white"><i class='fadeIn animated bx bx-money'></i>
                                            </div>
                                            <div class="app-title">@lang('english.fee')</div>
                                            </a>
                                        </div>
                                        <div class="col text-center">
                                             <a  href="{{ action('IndividualFeeCollectionController@create') }}">
                                            <div class="app-box mx-auto bg-gradient-burning text-white"><i class='fadeIn animated bx bx-money'></i>
                                            </div>
                                            <div class="app-title">@lang('english.individual_fee_collect_with_detail')</div>
                                            </a>
                                        </div>
                                        @endcan
                                        @can('manual_paper.view')
                                        <div class="col text-center">
                                            <a  href="{{ action('Curriculum\PaperMakerController@manualPaperCreate') }}">
                                            <div class="app-box mx-auto bg-gradient-lush text-white"><i class="lni lni-remove-file"></i>
                                            </div>
                                            <div class="app-title">@lang('english.manual_paper')</div>
                                            </a>
                                        </div>
                                        @endcan
                                        @can('expense.create')
                                        <div class="col text-center">
                                            <a  href="{{ action('ExpenseTransactionController@create') }}">
                                            <div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class="fadeIn animated bx bx-store"></i>
                                            </div>
                                            <div class="app-title">@lang('english.add_expense')</div>
                                            </a>
                                        </div>
                                        @endcan
                                        @can('exam_mark_entry.create')
                                         <div class="col text-center">
                                            <a  href="{{ action('Examination\ExamMarkController@create') }}">
                                            <div class="app-box mx-auto bg-gradient-blues text-dark"><i class="bx bxs-graduation"></i>
                                            </div>
                                            <div class="app-title">@lang('english.mark_entry')</div>
                                            </a>
                                        </div>
                                        @endcan
                                        @can('account.access')
                                         <div class="col text-center">
                                            <a  href="{{ action('AccountController@index') }}">
                                            <div class="app-box mx-auto bg-gradient-moonlit text-white"><i class="fadeIn animated bx bx-wallet-alt"></i>
                                            </div>
                                            <div class="app-title">@lang('english.list_accounts')</div>
                                            </a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </li>
                           
                          
                        </ul>
                    </div>
                    <div class="user-box dropdown border-light-2">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ !empty(Auth::User()->image) ? url(Auth::User()->image) : url('uploads/employee_image/default.jpg') }}" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{ Auth::User()->first_name}}  {{ Auth::User()->last_name }}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                           @if(Auth::User()->user_type=='student')
                            <li><a class="dropdown-item" href="{{ action('StudentController@studentProfile', [Auth::User()->hook_id]) }}"><i class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            @elseif(Auth::User()->user_type=='guardian')
                            @else
                            <li><a class="dropdown-item" href="{{ action('Hrm\HrmEmployeeController@employeeProfile', [Auth::User()->hook_id]) }}"><i class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            @endif
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" href="{{action('Auth\LoginController@logout')}}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>