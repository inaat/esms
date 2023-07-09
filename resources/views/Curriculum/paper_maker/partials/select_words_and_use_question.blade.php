  <div class="accordion" id="words_and_use_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="words_and_use_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#words_and_use_question_collapseOne" aria-expanded="false" aria-controls="words_and_use_question_collapseOne">
                  <h5 class="card-title text-primary">words_and_use Questions</h5>
              </button>
          </h2>
          <div id="words_and_use_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="words_and_use_question" data-bs-parent="#words_and_use_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(words_and_use Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('words_and_use_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'Words Use', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'words_and_use Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(words_and_use Question Questions)' . ':*') !!}
                             {!! Form::textarea('words_and_use_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Translate the following into English.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_words_and_use_marks', __('english.total_words_and_use_marks') . ':*') !!}
                    {!! Form::text('total_words_and_use_marks', ($words_and_use_question_number*$words_and_use_question_marks)-($words_and_use_choice*$words_and_use_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_words_and_use_marks')]) !!}
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
                                      @foreach ($words_and_use_questions as $words_and_use)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="words_and_use_questions[]" value="{{ $words_and_use->id }}" id="{{ $words_and_use->id }}" class="words_and_use_question">
                                          </td>
                                           <td>
                                         {!! $words_and_use->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $words_and_use->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>words_and_use Question</td>


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
