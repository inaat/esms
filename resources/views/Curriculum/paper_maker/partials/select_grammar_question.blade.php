  <div class="accordion" id="grammar_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="grammar_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#grammar_question_collapseOne" aria-expanded="false" aria-controls="grammar_question_collapseOne">
                  <h5 class="card-title text-primary">grammar Questions</h5>
              </button>
          </h2>
          <div id="grammar_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="grammar_question" data-bs-parent="#grammar_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(grammar Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('grammar_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'grammar Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'grammar Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(grammar Question Questions)' . ':*') !!}
                             {!! Form::textarea('grammar_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Read the following grammar and answer the questions given at the end.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_grammar_marks', __('english.total_grammar_marks') . ':*') !!}
                    {!! Form::text('total_grammar_marks', ($grammar_question_number*$grammar_question_marks)-($grammar_choice*$grammar_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_grammar_marks')]) !!}
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
                                      @foreach ($grammar_questions as $grammar)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="grammar_questions[]" value="{{ $grammar->id }}" id="{{ $grammar->id }}" class="grammar_question">
                                          </td>
                                           <td>
                                         {!! $grammar->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $grammar->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>grammar Question</td>


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
