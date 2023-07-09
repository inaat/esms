  <div class="accordion" id="passage_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="passage_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#passage_question_collapseOne" aria-expanded="false" aria-controls="passage_question_collapseOne">
                  <h5 class="card-title text-primary">Passage Questions</h5>
              </button>
          </h2>
          <div id="passage_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="passage_question" data-bs-parent="#passage_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(Passage Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('passage_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'passage Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'passage Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(passage Question Questions)' . ':*') !!}
                             {!! Form::textarea('passage_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Read the following passage and answer the questions given at the end.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_passage_marks', __('english.total_passage_marks') . ':*') !!}
                    {!! Form::text('total_passage_marks', ($passage_question_number*$passage_question_marks)-($passage_choice*$passage_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_passage_marks')]) !!}
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
                                      @foreach ($passage_questions as $passage)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="passage_questions[]" value="{{ $passage->id }}" id="{{ $passage->id }}" class="passage_question">
                                          </td>
                                           <td>
                                         {!! $passage->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $passage->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>passage Question</td>


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
