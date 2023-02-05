  @extends("admin_layouts.app")
@section('title', __('english.account_ledger'))
  @section('wrapper')
  <div class="page-wrapper">
      <div class="page-content">
          <!--breadcrumb-->
          <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
              <div class="breadcrumb-title pe-3">@lang('english.manage_your_account')</div>
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
          <hr />
        {!! Form::open(['url' => action('AccountController@postAccountLedger'), 'method' => 'post', 'id' =>'attendance_report_form']) !!}

          <div class="card">
              <div class="card-body">
                  <div class="row">
                      <div class="col-sm-4">
                          <div class="box box-solid">
                              <div class="box-body">
                                  <table class="table">
                                      <tr>
                                          <th>@lang('english.account_name'): </th>
                                          <td>{{ $account->name }}</td>
                                      </tr>
                                      <tr>
                                          <th>@lang('english.account_type'):</th>
                                          <td>@if (!empty($account->account_type->parent_account)) {{ $account->account_type->parent_account->name }} - @endif {{ $account->account_type->name ?? '' }}</td>
                                           {!! Form::hidden('account_id', $account->id )!!}
                                      </tr>
                                      <tr>
                                          <th>@lang('english.account_number'):</th>
                                          <td>{{ $account->account_number }}</td>
                                      </tr>
                                      <tr>
                                          <th>@lang('english.balance'):</th>
                                          <td><span id="account_balance" class="display_currency" data-currency_symbol="true">{{ @num_format($balance) }}</span></td>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                      </div>
                      <div class="col-sm-8">
                          <div class="box box-solid">
                              <div class="box-header">
                                  <h5 class="box-title"> <i class="bx bx-filter" aria-hidden="true"></i>
                                      @lang('english.filters'):</h5>
                              </div>
                              <div class="row">

                                  <div class="col-sm-6">
                                      {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                                      <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                          {!! Form::text('transaction_date_range', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('english.date_range')]) !!}

                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      {!! Form::label('transaction_type', __('english.transaction_type') . ':') !!}

                                      <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="bx bx-transfer"></i></span>
                                          {!! Form::select('transaction_type', ['' => __('english.all'), 'debit' => __('english.debit'), 'credit' => __('english.credit')], '', ['class' => 'form-control']) !!}

                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="d-lg-flex align-items-center mt-4 gap-3">
                              <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                      <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
                   {{ Form::close() }}
          @if(!empty($ledger_transaction))
          <div class="card">
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table mb-0" id="account_book">
                          <thead>
                              <tr>
                                  <th>@lang( 'messages.date' )</th>
                                  <th>@lang( 'english.description' )</th>
                                  <th  style="width:1%">@lang( 'account.note' )</th>
                                  <th>@lang( 'english.added_by' )</th>
                                  <th>@lang('english.debit')</th>
                                  <th>@lang('english.credit')</th>
                                  <th>@lang( 'english.balance' )</th>
                              </tr>
                          </thead>
                          <tbody>
                          @foreach ($ledger_transaction as  $data)
                          <tr>
                              <td>{{@format_datetime($data['date'])}}</td>
                              <td>{!! $data['description'] !!}</td>
                              <td>{{ $data['note'] }}</td>
                              <td>{!! $data['added_by'] !!}</td>
                              <td>{{ @num_format($data['debit']) }}</td>
                              <td>{{ @num_format($data['credit']) }}</td>
                              <td>{{ @num_format($data['balance']) }}</td>

                          </tr>
                              
                          @endforeach
                          
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
          @endif
      </div>
  </div>

  <div class="modal fade account_model" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>
  <div class="modal fade account_model" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="edit_account_transaction">
  </div>
  @endsection
  @section('javascript')
  <script>
      $(document).ready(function() {
          var ranges = {};
          ranges[LANG.today] = [moment(), moment()];
          ranges[LANG.yesterday] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
          ranges[LANG.last_7_days] = [moment().subtract(6, 'days'), moment()];
          ranges[LANG.last_30_days] = [moment().subtract(29, 'days'), moment()];
          ranges[LANG.this_month] = [moment().startOf('month'), moment().endOf('month')];
          ranges[LANG.last_month] = [
              moment()
              .subtract(1, 'month')
              .startOf('month')
              , moment()
              .subtract(1, 'month')
              .endOf('month')
          , ];
          ranges[LANG.this_month_last_year] = [
              moment()
              .subtract(1, 'year')
              .startOf('month')
              , moment()
              .subtract(1, 'year')
              .endOf('month')
          , ];
          ranges[LANG.this_year] = [moment().startOf('year'), moment().endOf('year')];
          ranges[LANG.last_year] = [
              moment().startOf('year').subtract(1, 'year')
              , moment().endOf('year').subtract(1, 'year')
          ];
          ranges[LANG.this_financial_year] = [financial_year.start, financial_year.end];
          ranges[LANG.last_financial_year] = [
              moment(financial_year.start._i).subtract(1, 'year')
              , moment(financial_year.end._i).subtract(1, 'year')
          ];
          var dateRangeSettings = {
              ranges: ranges
              , startDate: financial_year.start
              , endDate: financial_year.end
              , locale: {
                  cancelLabel: LANG.clear
                  , applyLabel: LANG.apply
                  , customRangeLabel: LANG.custom_range
                  , format: moment_date_format
                  , toLabel: '-'
              , }
          , };


          dateRangeSettings.startDate = moment().subtract(6, 'days');
          dateRangeSettings.endDate = moment();
          $('#transaction_date_range').daterangepicker(
              dateRangeSettings
              , function(start, end) {
                  $('#transaction_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));

              }
          );

      });

  </script>
  @endsection

