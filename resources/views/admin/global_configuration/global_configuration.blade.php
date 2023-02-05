  @extends("admin_layouts.app")
@section('title', __('english.general_settings'))
  @section('wrapper')
      <div class="page-wrapper">
          <div class="page-content">
              <!--breadcrumb-->
              <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                  <div class="ps-3">
                      <nav aria-label="breadcrumb">
                          <ol class="breadcrumb mb-0 p-0">
                              <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                              </li>
                              <li class="breadcrumb-item active" aria-current="page">@lang('english.general_settings')</li>
                          </ol>
                      </nav>
                  </div>
               
              </div>
              {!! Form::open(['url' => action('SystemSettingController@store'), 'class'=>'needs-validation','method' => 'post', 'novalidate','id' => 'bussiness_edit_form',
           'files' => true ]) !!}
              <!--end breadcrumb-->
              <div class="row row-cols-12 row-cols-md-1 row-cols-lg-12 row-cols-xl-12">
                  <div class="card">
                      <div class="card-body">
                          <ul class="nav nav-tabs nav-primary" role="tablist">
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link active" data-bs-toggle="tab" href="#global_config" role="tab"
                                      aria-selected="true">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.general_settings')</div>
                                      </div>
                                  </a>
                              </li>
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link" data-bs-toggle="tab" href="#sms_settings" role="tab"
                                      aria-selected="false">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.sms_settings')</div>
                                      </div>
                                  </a>
                              </li>
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link" data-bs-toggle="tab" href="#prefixes_setting" role="tab"
                                      aria-selected="false">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.prefixes_settings')</div>
                                      </div>
                                  </a>
                              </li>
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link" data-bs-toggle="tab" href="#system_settings" role="tab"
                                      aria-selected="false">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.system_settings')</div>
                                      </div>
                                  </a>
                              </li>
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link" data-bs-toggle="tab" href="#email_settings" role="tab"
                                      aria-selected="false">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.email_settings')</div>
                                      </div>
                                  </a>
                              </li>

                          </ul>
                          <div class="tab-content py-">
                              <div class="tab-pane fade show active" id="global_config" role="tabpanel">
                                  @include('admin.global_configuration.partials.general_settings')
                              </div>
                              <div class="tab-pane fade" id="sms_settings" role="tabpanel">
                                  @include('admin.global_configuration.partials.sms_settings')

                              </div>
                              <div class="tab-pane fade" id="prefixes_setting" role="tabpanel">
                                  @include('admin.global_configuration.partials.prefixes')
                              </div>
                              <div class="tab-pane fade" id="system_settings" role="tabpanel">
                                  @include('admin.global_configuration.partials.system_settings')
                              </div>

                              <div class="tab-pane fade" id="email_settings" role="tabpanel">
                                  @include('admin.global_configuration.partials.email_settings')
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-12">

                      <div class="d-lg-flex align-items-center mb-4 gap-3">
                          <div class="ms-auto">
                          @can('general_settings.update')
                              <button class="btn btn-primary radius-30 mt-2 mt-lg-0"
                                  type="submit">@lang('english.update_settings')</button>
                          @endcan
                          </div>


                      </div>
                  </div>

              </div>
              {!! Form::close() !!}
          </div>

      </div>
      <!--end row-->
    

       @endsection

  @section('javascript')
      <script type="text/javascript">

      </script>
      <script type="text/javascript">
          $(document).ready(function() {

              $(".upload_org_logo").on('change', function() {
                  __readURL(this, '.org_logo');
              });
              $(".upload_org_favicon").on('change', function() {
                  __readURL(this, '.org_favicon');
              });
              $(".upload_page_header_logo").on('change', function() {
                  __readURL(this, '.page_header_logo');
              });
              $(".upload_id_card_logo").on('change', function() {
                  __readURL(this, '.id_card_logo');
              });
              {{-- $(".upload_org_logo").on('click', function() {
           $(".upload_org_logo").click();
        }); --}}
              {{-- $(".upload_org_favicon").on('click', function() {
           $(".upload_org_favicon").click();
        }); --}}
  $('#test_sms_btn').click( function() {
        var test_number = $('#test_number').val();
        if (test_number.trim() == '') {
            toastr.error('{{__("english.test_number_is_required")}}');
            $('#test_number').focus();

            return false;
        }

        var data = {
            url: $('#sms_settings_url').val(),
            send_to_param_name: $('#send_to_param_name').val(),
            msg_param_name: $('#msg_param_name').val(),
            request_method: $('#request_method').val(),
            param_1: $('#sms_settings_param_key1').val(),
            param_2: $('#sms_settings_param_key2').val(),
            param_3: $('#sms_settings_param_key3').val(),
            param_4: $('#sms_settings_param_key4').val(),
            param_5: $('#sms_settings_param_key5').val(),
            param_6: $('#sms_settings_param_key6').val(),
            param_7: $('#sms_settings_param_key7').val(),
            param_8: $('#sms_settings_param_key8').val(),
            param_9: $('#sms_settings_param_key9').val(),
            param_10: $('#sms_settings_param_key10').val(),

            param_val_1: $('#sms_settings_param_val1').val(),
            param_val_2: $('#sms_settings_param_val2').val(),
            param_val_3: $('#sms_settings_param_val3').val(),
            param_val_4: $('#sms_settings_param_val4').val(),
            param_val_5: $('#sms_settings_param_val5').val(),
            param_val_6: $('#sms_settings_param_val6').val(),
            param_val_7: $('#sms_settings_param_val7').val(),
            param_val_8: $('#sms_settings_param_val8').val(),
            param_val_9: $('#sms_settings_param_val9').val(),
            param_val_10: $('#sms_settings_param_val10').val(),
            test_number: test_number
        };

        $.ajax({
            method: 'post',
            data: data,
            url: "{{ action('SystemSettingController@testSmsConfiguration') }}",
            dataType: 'json',
            success: function(result) {
                if (result.success == true) {
                    swal({
                        text: result.msg,
                        icon: 'success'
                    });
                } else {
                    swal({
                        text: result.msg,
                        icon: 'error'
                    });
                }
            },
        });
    });

          });
      </script>

  @endsection
