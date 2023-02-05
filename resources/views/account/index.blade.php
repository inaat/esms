  @extends("admin_layouts.app")
@section('title', __('english.accounts'))
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
          <div class="row row-cols-12 row-cols-md-1 row-cols-lg-12 row-cols-xl-12">
              <hr />
              <div class="card">
                  <div class="card-body">
                      <ul class="nav nav-tabs nav-primary" role="tablist">
                          <li class="nav-item" role="presentation">
                              <a class="nav-link active" data-bs-toggle="tab" href="#other_accounts" role="tab" aria-selected="true">
                                  <div class="d-flex align-items-center">
                                      <div class="tab-icon">
                                          <i class="fa fa-book font-18 me-1"></i>

                                      </div>
                                      <div class="tab-title">@lang('english.accounts')</div>
                                  </div>
                              </a>
                          </li>
                          <li class="nav-item" role="presentation">
                              <a class="nav-link" data-bs-toggle="tab" href="#account-types" role="tab" aria-selected="true">
                                  <div class="d-flex align-items-center">
                                      <div class="tab-icon"><i class='fa fa-list font-18 me-1'></i>
                                      </div>
                                      <div class="tab-title">@lang('english.account_types') </div>
                                  </div>
                              </a>
                          </li>


                      </ul>
                      <div class="tab-content py-3">
                          <div class="tab-pane fade show active" id="other_accounts" role="tabpanel">

                              <div class="row">
                                  <div class="col-md-4">
                                      {!! Form::select('account_status', ['active' => __('business.is_active'), 'closed' => __('english.closed')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'account_status']) !!}
                                  </div>
                                  <div class="col-md-8">

                                      <div class="d-lg-flex align-items-center mb-4 gap-3">

                                          <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('AccountController@create') }}" data-container=".account_model">
                                                  <i class="bx bxs-plus-square"></i>@lang('english.add_new_account')</button>
                                          </div>

                                      </div>

                                  </div>
                              </div>
                              <div class="col-sm-12">
                                  <br>
                                  <div class="table-responsive">
                                      <table class="table mb-0 " id="other_account_table">
                                          <thead >
                                              <tr>
                                                  <th>@lang( 'messages.action' )</th>
                                                  <th>@lang( 'english.name' )</th>
                                                  <th>@lang( 'english.account_type' )</th>
                                                  <th>@lang( 'english.account_sub_type' )</th>
                                                  <th>@lang('english.account_number')</th>
                                                  <th>@lang( 'account.note' )</th>
                                                  <th>@lang('english.balance')</th>
                                                  <th>@lang('english.added_by')</th>
                                              </tr>
                                          </thead>
                                      </table>
                                  </div>
                              </div>
                          </div>

                          <div class="tab-pane fade show" id="account-types" role="tabpanel">
                              <div class="row">
                                  <div class="d-lg-flex align-items-center mb-4 gap-3">
                                      <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('AccountTypeController@create') }}" data-container="#account_type_modal">
                                              <i class="bx bxs-plus-square"></i>@lang('english.add_new_account_type')</button>
                                      </div>

                                  </div>
                              </div>

                              <div class="row">

                                  <div class="col-sm-12">

                                      <br>
                                      <div class="table-responsive">
                                          <table class="table mb-0" id="account_types_table">
                                              <thead>
                                                  <tr>
                                                      <th>@lang( 'english.name' )</th>
                                                      <th>@lang( 'messages.action' )</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  @foreach ($account_types as $account_type)
                                                  <tr class="account_type_{{ $account_type->id }}">
                                                      <th>{{ $account_type->name }}</th>
                                                      <td>

                                                          {!! Form::open(['url' => action('AccountTypeController@destroy', $account_type->id), 'method' => 'delete']) !!}
                                                          <button type="button" class="btn btn-primary btn-modal btn-xs" data-href="{{ action('AccountTypeController@edit', $account_type->id) }}" data-container="#account_type_modal">
                                                              <i class="fa fa-edit"></i> @lang( 'messages.edit'
                                                              )</button>

                                                          <button type="button" class="btn btn-danger btn-xs delete_account_type">
                                                              <i class="fa fa-trash"></i> @lang(
                                                              'messages.delete' )</button>
                                                          {!! Form::close() !!}
                                                      </td>
                                                  </tr>
                                                  @foreach ($account_type->sub_types as $sub_type)
                                                  <tr>
                                                      <td>&nbsp;&nbsp;-- {{ $sub_type->name }}</td>
                                                      <td>


                                                          {!! Form::open(['url' => action('AccountTypeController@destroy', $sub_type->id), 'method' => 'delete']) !!}
                                                          <button type="button" class="btn btn-primary btn-modal btn-xs" data-href="{{ action('AccountTypeController@edit', $sub_type->id) }}" data-container="#account_type_modal">
                                                              <i class="fa fa-edit"></i> @lang(
                                                              'messages.edit' )</button>
                                                          <button type="button" class="btn btn-danger btn-xs delete_account_type">
                                                              <i class="fa fa-trash"></i> @lang(
                                                              'messages.delete' )</button>
                                                          {!! Form::close() !!}
                                                      </td>
                                                  </tr>
                                                  @endforeach
                                                  @endforeach
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
          </div>
      </div>
  </div>

  <!--end row-->

  <div class="modal fade account_model" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="account_type_modal">
  </div>
  @endsection

  @section('javascript')
  <script>
  
  </script>
    <script src="{{ asset('js/account.js?v=' . $asset_v) }}"></script>

  @endsection
