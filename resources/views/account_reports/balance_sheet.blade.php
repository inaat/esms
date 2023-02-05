  @extends("admin_layouts.app")
  @section('title', __( 'english.balance_sheet' ))

  @section('wrapper')
  <div class="page-wrapper">
      <div class="page-content">
          <!--breadcrumb-->
          <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
              <div class="breadcrumb-title pe-3">@lang('english.balance_sheet')</div>
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
                          {!! Form::select('bal_sheet_campus_id', $campuses, null, ['class' => 'form-select select2 trial_bal_-campuses', 'id' => 'bal_sheet_campus_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                      </div>

                      <div class="col-md-3 ">

                          {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                          <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                              {!! Form::text('end_date', @format_date('now'), ['class' => 'form-control', 'id'=>'end_date', 'placeholder' => __('english.date_range')]) !!}

                          </div>
                      </div>

                  </div>



                  <br>
                  <div class="box box-solid print_area">
                      <div class="box-header print_section">
                              @include('common.logo')

                          <h5 class="box-title text-center"> @lang( 'account.balance_sheet') - <span id="hidden_date">{{@format_date('now')}}</span></h5>

                      </div>
                      <div class="box-body">
                          <table class="table table-border-center no-border table-pl-12">
                              <thead>
                                  <tr class="text-primary">
                                      <th>@lang( 'account.liability')</th>
                                      <th>@lang( 'account.assets')</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr>
                                      <td>
                                          <table class="table">
                                              <tr>
                                                  <th>@lang('english.payroll_due'):</th>
                                                  <td>
                                                      <input type="hidden" id="hidden_payroll_due" class="liability">
                                                      <span class="remote-data" id="payroll_due">
                                                          <i class="fas fa-sync fa-spin fa-fw"></i>
                                                      </span>
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <th>@lang('english.expense_due'):</th>
                                                  <td>
                                                      <input type="hidden" id="hidden_expense_due" class="liability">
                                                      <span class="remote-data" id="expense_due">
                                                          <i class="fas fa-sync fa-spin fa-fw"></i>
                                                      </span>
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                      <td>
                                          <table class="table" id="assets_table">
                                              <tbody>
                                                  <tr>
                                                      <th>@lang('english.fee_due'):</th>
                                                      <td>
                                                          <input type="hidden" id="hidden_fee_due" class="asset">
                                                          <span class="remote-data" id="fee_due">
                                                              <i class="fas fa-sync fa-spin fa-fw"></i>
                                                          </span>
                                                      </td>
                                                  </tr>

                                                  <tr>
                                                      <th colspan="2">@lang('english.account_balances'):</th>
                                                  </tr>
                                              </tbody>
                                              <tbody id="account_balances" class="pl-20-td">
                                                  <tr>
                                                      <td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td>
                                                  </tr>
                                              </tbody>

                                          </table>
                                      </td>
                                  </tr>
                              </tbody>
                              <tfoot>
                                  <tr class="text-primary">
                                      <td>
                                          <table class="table text-primary mb-0 no-border">
                                              <tr>
                                                  <th>
                                                      @lang('english.total_liability'):
                                                  </th>
                                                  <td>
                                                      <span id="total_liabilty"><i class="fas fa-sync fa-spin fa-fw"></i></span>
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                      <td>
                                          <table class="table text-primary mb-0 no-border">
                                              <tr>
                                                  <th>
                                                      @lang('english.total_assets'):
                                                  </th>
                                                  <td>
                                                      <span id="total_assets"><i class="fas fa-sync fa-spin fa-fw"></i></span>
                                                  </td>
                                              </tr>
                                          </table>
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
