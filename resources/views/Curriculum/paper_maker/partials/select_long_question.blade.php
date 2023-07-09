  <div class="accordion" id="long_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="long_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#long_question_collapseOne" aria-expanded="false" aria-controls="long_question_collapseOne">
                  <h5 class="card-title text-primary">Long Questions</h5>
              </button>
          </h2>
          <div id="long_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="long_question" data-bs-parent="#long_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(Long Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                             <div class="row">
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('long_question_header',($class_subject->subject_input=='ur') ? '':'Subjective Type', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'placeholder'=>'Long Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                           <div class="col-md-4 p-2 ">
                             {!! Form::label('', 'Additional Text(Long Questions)' . ':*') !!}
                             {!! Form::textarea('long_top_text',($class_subject->subject_input=='ur') ? 'مندرجہ ذیل میں سے کسی بھی تین سوالات کی کوشش کریں۔':'Attempt  the following questions.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_long_marks', __('english.total_long_marks') . ':*') !!}
                    {!! Form::text('total_long_marks',($long_question_question_number*$long_question_question_marks)-($long_question_choice*$long_question_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_long_marks')]) !!}
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
                                      @foreach ($long_question_questions as $long_question)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="long_question_questions[]" value="{{ $long_question->id }}" id="{{ $long_question->id }}" class="long_question_question">
                                          </td>
                                          <td>
                                         {{ $long_question->chapter->chapter_name }}
                                        </td>
                                          <td>
                                              <strong>
                                                  {{ $long_question->question }}
                                              </strong> <br>
                                          </td>

                                          <td>Long Question</td>


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
