  <div class="accordion" id="contextual_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="contextual_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contextual_question_collapseOne" aria-expanded="false" aria-controls="contextual_question_collapseOne">
                  <h5 class="card-title text-primary">contextual Questions</h5>
              </button>
          </h2>
          <div id="contextual_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="contextual_question" data-bs-parent="#contextual_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(contextual Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('contextual_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'contextual Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'contextual Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(contextual Question Questions)' . ':*') !!}
                             {!! Form::textarea('contextual_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Read the following contextual and answer the questions given at the end.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_contextual_marks', __('english.total_contextual_marks') . ':*') !!}
                    {!! Form::text('total_contextual_marks', ($contextual_question_number*$contextual_question_marks)-($contextual_choice*$contextual_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_contextual_marks')]) !!}
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
                                      @foreach ($contextual_questions as $contextual)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="contextual_questions[]" value="{{ $contextual->id }}" id="{{ $contextual->id }}" class="contextual_question">
                                          </td>
                                           <td>
                                         {!! $contextual->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $contextual->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>contextual Question</td>


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
