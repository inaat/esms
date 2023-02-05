  <div class="accordion" id="singular_and_plural_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="singular_and_plural_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#singular_and_plural_question_collapseOne" aria-expanded="false" aria-controls="singular_and_plural_question_collapseOne">
                  <h5 class="card-title text-primary">singular_and_plural Questions</h5>
              </button>
          </h2>
          <div id="singular_and_plural_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="singular_and_plural_question" data-bs-parent="#singular_and_plural_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(singular_and_plural Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('singular_and_plural_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'singular_and_plural Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'singular_and_plural Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(singular_and_plural Question Questions)' . ':*') !!}
                             {!! Form::textarea('singular_and_plural_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Read the following singular_and_plural and answer the questions given at the end.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_singular_and_plural_marks', __('english.total_singular_and_plural_marks') . ':*') !!}
                    {!! Form::text('total_singular_and_plural_marks', ($singular_and_plural_question_number*$singular_and_plural_question_marks)-($singular_and_plural_choice*$singular_and_plural_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_singular_and_plural_marks')]) !!}
                </div>
                         </div>
                          <div class="row ">
                              <table class="table table-responsive table-bordered">
                                  <thead>
                                      <tr>
                                          <th scope="col" style="width:5%">#</th>
                                          <th scope="col" style="width:5%">@lang('english.select')</th>
                                        <th scope="col" style="width:10%">@lang('english.chapter_name')</th>

                                          <th scope="col" style="width:70%">@lang('english.question')</th>
                                          <th scope="col" title="Question Type" style="width:10%">@lang('english.type')</th>

                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($singular_and_plural_questions as $singular_and_plural)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="singular_and_plural_questions[]" value="{{ $singular_and_plural->id }}" id="{{ $singular_and_plural->id }}" class="singular_and_plural_question">
                                          </td>
                                           <td>
                                         {!! $singular_and_plural->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $singular_and_plural->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>singular_and_plural Question</td>


                                      </tr>
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
