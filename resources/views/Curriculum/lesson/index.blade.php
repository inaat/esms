  @extends("admin_layouts.app")
@section('title', __('english.lesson'))
  @section('wrapper')
      <div class="page-wrapper">
          <div class="page-content">
              <!--breadcrumb-->
              <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                  <div class="breadcrumb-title pe-3">@lang('english.subject_detail')</div>
                  <div class="ps-3">
                      <nav aria-label="breadcrumb">
                          <ol class="breadcrumb mb-0 p-0">
                              <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                              </li>
                              <li class="breadcrumb-item active" aria-current="page">@lang('english.subject_detail')</li>
                          </ol>
                      </nav>
                  </div>
                  
              </div>
              {{-- {!! Form::open(['url' => action('SystemSettingController@store'), 'class'=>'needs-validation','method' => 'post', 'novalidate','id' => 'bussiness_edit_form',
           'files' => true ]) !!} --}}
              <!--end breadcrumb-->
              <div class="row row-cols-12 row-cols-md-1 row-cols-lg-12 row-cols-xl-12">
                  <h6 class="mb-0 text-uppercase text-info"> @lang('english.subject_detail') - {{ $class_subject->name }} of class {{ $class_subject->classes->title }}</h6>
                  <hr />
                  <div class="card">
                      <div class="card-body">
                          <ul class="nav nav-tabs nav-primary" role="tablist">
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link " data-bs-toggle="tab" href="#lesson" role="tab"
                                      aria-selected="false">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='fadeIn animated bx bx-book-reader font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.lessons')</div>
                                      </div>
                                  </a>
                              </li>
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link active" data-bs-toggle="tab" href="#progress" role="tab"
                                      aria-selected="true">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='bx bx-book-content font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.planning_&_progress')</div>
                                      </div>
                                  </a>
                              </li>
                              <li class="nav-item" role="presentation">
                                  <a class="nav-link" data-bs-toggle="tab" href="#question_bank" role="tab"
                                      aria-selected="false">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='bx bx-briefcase font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">@lang('english.question_bank')</div>
                                      </div>
                                  </a>
                              </li>
                              {{-- <li class="nav-item" role="presentation">
                                  <a class="nav-link" data-bs-toggle="tab" href="#system_settings" role="tab"
                                      aria-selected="false">
                                      <div class="d-flex align-items-center">
                                          <div class="tab-icon"><i class='fal fa-users font-18 me-1'></i>
                                          </div>
                                          <div class="tab-title">FAculty</div>
                                      </div>
                                  </a>
                              </li> --}}

                          </ul>
                          <div class="tab-content py-">
                              <div class="tab-pane fade" id="lesson" role="tabpanel">
                                @include('Curriculum.lesson.partials.lesson')
                              </div>
                              <div class="tab-pane fade show active" id="progress" role="tabpanel">
                                @include('Curriculum.progress.partials.progress')
                              </div>
                              <div class="tab-pane fade" id="question_bank" role="tabpanel">
                                @include('Curriculum.question_bank.partials.question')

                              </div>
                              <div class="tab-pane fade" id="system_settings" role="tabpanel">
                              </div>

                             
                          </div>
                      </div>
                  </div>
              </div>
              
              {{-- {!! Form::close() !!} --}}
          </div>

      </div>
      <!--end row-->
 @endsection   
@section('script')
<script src="{{ asset('/js/school.js?v=' . $asset_v) }}"></script>
@endsection

