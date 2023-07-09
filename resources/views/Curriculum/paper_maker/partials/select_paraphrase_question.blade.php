  <div class="accordion" id="paraphrase_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="paraphrase_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#paraphrase_question_collapseOne" aria-expanded="false" aria-controls="paraphrase_question_collapseOne">
                  <h5 class="card-title text-primary">Paraphrase Questions</h5>
              </button>
          </h2>
          <div id="paraphrase_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="paraphrase_question" data-bs-parent="#paraphrase_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(Paraphrase Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('paraphrase_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'paraphrase Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'paraphrase Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(paraphrase Question Questions)' . ':*') !!}
                             {!! Form::textarea('paraphrase_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Paraphrase one of the following.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_paraphrase_marks', __('english.total_paraphrase_marks') . ':*') !!}
                    {!! Form::text('total_paraphrase_marks', ($paraphrase_question_number*$paraphrase_question_marks)-($paraphrase_choice*$paraphrase_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_paraphrase_marks')]) !!}
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
                                      @foreach ($paraphrase_questions as $paraphrase)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="paraphrase_questions[]" value="{{ $paraphrase->id }}" id="{{ $paraphrase->id }}" class="paraphrase_question">
                                          </td>
                                           <td>
                                         {!! $paraphrase->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $paraphrase->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>Paraphrase Question</td>


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
