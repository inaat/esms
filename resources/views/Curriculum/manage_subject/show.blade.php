  @extends('admin_layouts.app')
  @section('title', __('english.manage_subject'))
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
                  <h6 class="text-uppercase text-info mb-0"> @lang('english.subject_detail') - {{ $class_subject->name }}
                      @lang('english.of') @lang('english.class') {{ $class_subject->classes->title }}</h6>
                  <hr />
                  <input type="hidden" id="subject_input" value="{{ $class_subject->subject_input }}" />
                  <div class="card">
                      <div class="card-body">
                          <ul class="nav nav-tabs nav-primary" role="tablist">
                              @can('chapter.view')
                                  <li class="nav-item" role="presentation">
                                      <a class="nav-link active" data-bs-toggle="tab" href="#chapter" role="tab"
                                          aria-selected="false">
                                          <div class="d-flex align-items-center">
                                              <div class="tab-icon"><i class='bx bx-chip font-18 me-1'></i>
                                              </div>
                                              <div class="tab-title">@lang('english.chapters')</div>
                                          </div>
                                      </a>
                                  </li>
                              @endcan
                              @can('lesson.view')
                                  <li class="nav-item" role="presentation">
                                      <a class="nav-link" data-bs-toggle="tab" href="#lesson" role="tab"
                                          aria-selected="false">
                                          <div class="d-flex align-items-center">
                                              <div class="tab-icon"><i
                                                      class='fadeIn animated bx bx-book-reader font-18 me-1'></i>
                                              </div>
                                              <div class="tab-title">@lang('english.lessons')</div>
                                          </div>
                                      </a>
                                  </li>
                              @endcan
                              @can('lesson_progress.view')
                                  <li class="nav-item" role="presentation">
                                      <a class="nav-link" data-bs-toggle="tab" href="#progress" role="tab"
                                          aria-selected="true">
                                          <div class="d-flex align-items-center">
                                              <div class="tab-icon"><i class='bx bx-book-content font-18 me-1'></i>
                                              </div>
                                              <div class="tab-title">@lang('english.planning_&_progress')</div>
                                          </div>
                                      </a>
                                  </li>
                              @endcan
                              @can('question_bank.view')
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
                              @endcan



                          </ul>
                          <div class="tab-content py-">
                              @can('chapter.view')
                                  <div class="tab-pane fade show active" id="chapter" role="tabpanel">
                                      @include('Curriculum.manage_subject.partials.chapter_index')

                                  </div>
                              @endcan
                              @can('lesson.view')
                                  <div class="tab-pane fade show" id="lesson" role="tabpanel">
                                      @include('Curriculum.lesson.partials.lesson')
                                  </div>
                              @endcan
                              @can('lesson_progress.view')
                                  <div class="tab-pane fade show" id="progress" role="tabpanel">
                                      @include('Curriculum.progress.partials.progress')
                                  </div>
                              @endcan
                              @can('question_bank.view')
                                  <div class="tab-pane fade show" id="question_bank" role="tabpanel">
                                      @include('Curriculum.question_bank.partials.question')

                                  </div>
                              @endcan

                              <div class="tab-pane fade" id="system_settings" role="tabpanel">
                              </div>


                          </div>
                      </div>
                  </div>
              </div>


          </div>
          <!--end row-->
      @endsection
      @section('script')
          <script src="{{ asset('/js/school.js?v=' . $asset_v) }}"></script>

          <script src="{{ asset('/js/questions.js?v=' . $asset_v) }}"></script>
          <script type="text/javascript">
              $(document).ready(function() {
                  @if ($class_subject->subject_input == 'ur')
                      $(document).find('.select2').each(function() {
                          var $p = $(this).parent();
                          $(this).select2({
                              dir: 'rtl',
                              dropdownParent: $p,
                              theme: 'bootstrap4',
                              width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                                  'w-100') ? '100%' : 'style',
                              placeholder: $(this).data('placeholder'),
                              allowClear: Boolean($(this).data('allow-clear')),
                          });
                      });
                  @endif
                  $(document).on("click", ".btn-question-modal", function(e) {
                      e.preventDefault();
                      var container = $(this).data("container");

                      $.ajax({
                          url: $(this).data("href"),
                          dataType: "html",
                          success: function(result) {
                              $(container).html(result).modal("show");
                              $('select#question_type_trigger').trigger('change');
                              @if ($class_subject->subject_input == 'ur')
                                  init_tinymce('question');
                                  init_tinymce('hint');
                              @else
                                  tinymce.init({
                                      selector: "#hint,#question,.mcqs-form-input",
                                      height: 300,
                                  });
                              @endif
                          },
                      });
                  });

                  $(document).on("shown.bs.modal", ".question_bank_modal,.chapter_modal,.lesson_modal", function(e) {

                      @if ($class_subject->subject_input == 'ur')
                          $(this).find('.select2').each(function() {
                              var $p = $(this).parent();
                              $(this).select2({
                                  dir: 'rtl',
                                  dropdownParent: $p,
                                  theme: 'bootstrap4',
                                  width: $(this).data('width') ? $(this).data('width') : $(this)
                                      .hasClass('w-100') ? '100%' : 'style',
                                  placeholder: $(this).data('placeholder'),
                                  allowClear: Boolean($(this).data('allow-clear')),
                              });
                          });
                          {{-- $(document).on('keypress', '.select2-search__field,.urdu_input', function(e) {

                  if (8 == e.keyCode || 13 == e.keyCode) {
                      return true;
                  } else {
                      e.preventDefault();
                      var newString = $(this).val() + __changeKey(e);
                      $(this).val(newString);
                  }


              }); --}}
                      @endif
                  });

                  $(document).on('change', '.file_type', function() {
                      var type = $(this).val();
                      var parent = $(this).parent();
                      if (type == "file_upload") {
                          parent.siblings('#file_name_div').show();
                          parent.siblings('#file_thumbnail_div').hide();
                          parent.siblings('#file_div').show();
                          parent.siblings('#file_link_div').hide();
                      } else if (type == "video_upload") {
                          parent.siblings('#file_name_div').show();
                          parent.siblings('#file_thumbnail_div').show();
                          parent.siblings('#file_div').show();
                          parent.siblings('#file_link_div').hide();
                      } else if (type == "youtube_link") {
                          parent.siblings('#file_name_div').show();
                          parent.siblings('#file_thumbnail_div').show();
                          parent.siblings('#file_div').hide();
                          parent.siblings('#file_link_div').show();
                      } else if (type == "other_link") {
                          parent.siblings('#file_name_div').show();
                          parent.siblings('#file_thumbnail_div').show();
                          parent.siblings('#file_div').hide();
                          parent.siblings('#file_link_div').show();
                      } else {
                          parent.siblings('#file_name_div').hide();
                          parent.siblings('#file_thumbnail_div').hide();
                          parent.siblings('#file_div').hide();
                          parent.siblings('#file_link_div').hide();
                      }
                  })

                  $(document).on('click', '.add-lesson-file', function(e) {
                      e.preventDefault();
                      
                      let html = $('.file_type_div:last').clone();
                      html.find('.error').remove();
                      html.find('.has-danger').removeClass('has-danger');
                      // This function will replace the last index value and increment in the multidimensional name attribute
                      html.find(':input').each(function(key, element) {
                          console.log(this.name);
                          this.name = this.name.replace(/\[(\d+)\]/, function(str, p1) {
                              return '[' + (parseInt(p1, 10) + 1) + ']';
                          });
                      })
                      html.find('.add-lesson-file i').addClass('fa-times').removeClass('fa-plus');
                      html.find('.add-lesson-file').addClass('btn-danger remove-lesson-file').removeClass(
                          'btn-success add-lesson-file');
                      $(this).parent().parent().siblings('.extra-files').append(html);
                      // Trigger change only after the html is appended to DOM
                      html.find('.file_type').val('').trigger('change');
                      html.find('input').val('');
                  });

                  $(document).on('click', '.edit-lesson-file', function(e) {
                      e.preventDefault();
                      let html = $('.file_type_div:last').clone();
                      html.find('.error').remove();
                      html.find('#file_thumbnail_preview').remove();
                      html.find('.has-danger').removeClass('has-danger');
                      // This function will replace the last index value and increment in the multidimensional name attribute
                      html.find(':input').each(function(key, element) {
                         
                              this.name = this.name.replace(/edit_file\[(\d+)\]/, function (str, p1) {
                        return 'file'+'[' + (parseInt(p1, 10) + 1) + ']';
                          });
                      })
                      html.find('.remove-lesson-file').removeAttr('data-id');
                      html.find('.add-lesson-file i').addClass('fa-times').removeClass('fa-plus');
                      html.find('.add-lesson-file').addClass('btn-danger remove-lesson-file').removeClass(
                          'btn-success add-lesson-file');
                      $(this).parent().parent().siblings('.edit-extra-files').append(html);
                      // Trigger change only after the html is appended to DOM
                      html.find('.file_type').val('').trigger('change');
                      html.find('input').val('');
                  });


                  $(document).on('click', '.remove-lesson-file', function(e) {
                      e.preventDefault();
                      var $this = $(this);
                      if ($(this).data('id')) {
                          var file_id = $(this).data('id');
                          let url = base_path + '/file/delete/' + file_id;
                          swal({
                              title: 'Are you sure?',
                              text: "You won't be able to revert this!",
                              icon: 'warning',
                              buttons: true,
                              dangerMode: true,
                          }).then(willDelete => {
                              if (willDelete) {
                                  $.ajax({
                                      url: url,
                                      method: 'delete',
                                      dataType: 'json',
                                      success: function(result) {
                                          if (result.success === true) {
                                              $this.parent().parent().remove();
                                              toastr.success(result.msg);

                                          } else {
                                              toastr.error(result.msg);
                                          }
                                      },
                                  });
                              }
                          });
                      }else{
                        $this.parent().parent().remove();

                      }
                  });

              });
          </script>

      @endsection
