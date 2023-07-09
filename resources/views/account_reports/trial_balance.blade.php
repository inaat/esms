  @extends("admin_layouts.app")
  @section('title', __( 'english.trial_balance' ))

  @section('wrapper')
  <div class="page-wrapper">
      <div class="page-content">
          <!--breadcrumb-->
          <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
              <div class="breadcrumb-title pe-3">@lang('english.trial_balance')</div>
              <div class="ps-3">
                  <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 p-0">
                          <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                          </li>
                          <li class="breadcrumb-item active" aria-current="page">@lang('english.accounts')</li>
                      </ol>
                  </nav>
              </div>
          </div>

          <!--end breadcrumb-->
          <div class="card">

              <div class="card-body">
                  <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                  <hr>
                  <div class="row m-0">
                      <div class="col-md-3  ">
                          {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                          {!! Form::select('trial_bal_campus_id', $campuses, null, ['class' => 'form-select select2 trial_bal_-campuses', 'id' => 'trial_bal_campus_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                      </div>

                      <div class="col-md-3 ">

                          {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                          <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                              {!! Form::text('end_date', @format_date('now'), ['class' => 'form-control', 'id'=>'trial_bal_end_date', 'placeholder' => __('english.date_range')]) !!}

                          </div>
                      </div>

                  </div>



                  <br>
                  <div class="box box-solid print_area">
                      <div class="box-header print_section">
                      @include('common.logo')

                          <h5 class="box-title text-center"> @lang( 'account.trial_balance') - <span id="trial_bal_hidden_date">{{@format_date('now')}}</span></h5>

                      </div>
                      <div class="box-body">
                          <table class="table table-border-center-col no-border table-pl-12" id="trial_balance_table">
                              <thead>
                                  <tr class="bg-primary">
                                      <th>Trial Balance</th>
                                      <th>Debit</th>
                                      <th>Credit</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <th>PayRoll Due:</th>
                                      <td>&nbsp;</td>
                                      <td>
                                          <input type="hidden" id="hidden_payroll_due" class="debit">
                                          <span class="remote-data" id="payroll_due">
                                              <i class="fas fa-sync fa-spin fa-fw"></i>
                                          </span>
                                      </td>
                                  </tr>
                                  <tr>
                                      <th>Expenses Due:</th>
                                      <td>&nbsp;</td>
                                      <td>
                                          <input type="hidden" id="hidden_expense_due" class="debit">
                                          <span class="remote-data" id="expense_due">
                                              <i class="fas fa-sync fa-spin fa-fw"></i>
                                          </span>
                                      </td>
                                  </tr>
                                  <tr>
                                      <th>Fee Due:</th>
                                      <td>
                                          <input type="hidden" id="hidden_fee_due" class="credit">
                                          <span class="remote-data" id="fee_due">
                                              <i class="fas fa-sync fa-spin fa-fw"></i>
                                          </span>
                                      </td>
                                      <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                      <th>Account Balances:</th>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                  </tr>
                              </tbody>
                              <tbody id="account_balances_details">
                                  <tr>
                                      <th>@lang('english.account_balances'):</th>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                  </tr>
                              </tbody>

                              <tfoot>
                                  <tr class="bg-primary">
                                      <th>@lang('english.total')</th>
                                      <td>
                                          <span class="remote-data" id="total_credit"></span>
                                      </td>
                                      <td>
                                          <span class="remote-data" id="total_debit"></span>
                                      </td>
                                  </tr>
                              </tfoot>
                          </table>
                      </div>
                      <div class="d-lg-flex align-items-center mt-4 gap-3">
                          <div class="ms-auto">
                              <button type="button" class="btn btn-primary no-print pull-right" id="print_invoice">
                                  <i class="fa fa-print"></i> Print</button>
                          </div>
                      </div>
                  </div>

                
              </div>
          </div>
      </div>
  </div>
  @endsection
  @section('javascript')

      <script src="{{ asset('js/account.js?v=' . $asset_v) }}"></script>


  @endsection
