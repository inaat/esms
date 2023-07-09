  @extends("admin_layouts.app")
@section('title', __('english.account_book'))
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
                      {!! Form::open(['url' => action('AccountController@postAccountLedger'), 'method' => 'post', 'id' =>'attendance_report_form']) !!}

              <hr />
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
                                          </tr>
                                          <tr>
                                              <th>@lang('english.account_number'):</th>
                                              <td>{{ $account->account_number }}</td>
                                          </tr>
                                          <tr>
                                              <th>@lang('english.balance'):</th>
                                              <td><span id="account_balance"></span></td>
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

                                          <div class="input-group flex-nowrap"> <span class="input-group-text"
                                                  id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                           {!! Form::hidden('account_id', $account->id ,['class' => 'form-control', 'id'=>'account_id'])!!}
                                           
                                              {!! Form::text('transaction_date_range', null, ['class' => 'form-control', 'readonly', 'placeholder' => __('english.date_range')]) !!}

                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          {!! Form::label('transaction_type', __('english.transaction_type') . ':') !!}

                                          <div class="input-group flex-nowrap"> <span class="input-group-text"
                                                  id="addon-wrapping"><i class="bx bx-transfer"></i></span>
                                              {!! Form::select('transaction_type', ['' => __('english.all'), 'debit' => __('english.debit'), 'credit' => __('english.credit')], '', ['class' => 'form-control']) !!}

                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                           <div class="d-lg-flex align-items-center mt-4 gap-3">
                              <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                      <i class="bx bx-printer"></i>@lang('english.print')</button></div>
                          </div>
                      </div>
                  </div>
              </div>
                                 {{ Form::close() }}

              <div class="card">
                  <div class="card-body">
                     <div class="table-responsive">
                              <table class="table mb-0" id="account_book">
                                  <thead >
                                      <tr>
                                          <th>@lang( 'messages.date' )</th>
                                          <th>@lang( 'english.description' )</th>
                                          <th>@lang( 'account.note' )</th>
                                          <th>@lang( 'english.added_by' )</th>
                                          <th>@lang('english.debit')</th>
                                          <th>@lang('english.credit')</th>
                                          <th>@lang( 'english.balance' )</th>
                                          <th>@lang( 'messages.action' )</th>
                                      </tr>
                                  </thead>
                              </table>
                          </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="modal fade account_model" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
      </div>
        <div class="modal fade account_model" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel" id="edit_account_transaction">
    </div>
  @endsection
  @section('javascript')
          <script src="{{ asset('js/account.js?v=' . $asset_v) }}"></script>

  @endsection
